<?php

namespace App\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;

class ClassroomController
{
    private Course $courseModel;
    private Enrollment $enrollmentModel;
    private LessonProgress $progressModel;

    public function __construct()
    {
        $this->courseModel = new Course();
        $this->enrollmentModel = new Enrollment();
        $this->progressModel = new LessonProgress();
    }

    public function learn(int $courseId, ?int $lessonId = null): void
    {
        $user = authUser();
        if (!$user) {
            flash('error', 'Vui lòng đăng nhập để vào phòng học.');
            redirect('/login?redirect=' . urlencode("/learn/{$courseId}"));
        }

        $userId = (int) $user['id'];
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            http_response_code(404);
            flash('error', 'Không tìm thấy khóa học.');
            redirect('/courses');
        }

        // Kiểm tra quyền: Giảng viên phụ trách khóa học, hoặc học viên đã thanh toán/ghi danh
        $effectivePrice = ($course['sale_price'] !== null && $course['sale_price'] >= 0)
            ? (float) $course['sale_price']
            : (float) $course['price'];
        $isFree = $effectivePrice <= 0;

        $isInstructor = ((int) ($course['instructor_id'] ?? 0) === $userId);
        $isEnrolled = $this->enrollmentModel->isEnrolled($userId, $courseId);

        if (!$isFree && !$isInstructor && !$isEnrolled) {
            flash('error', 'Khóa học này yêu cầu thanh toán để mở khóa bài học. Vui lòng hoàn tất thanh toán.');
            redirect('/courses/' . $courseId);
        }

        $curriculum = $this->courseModel->getCurriculum($courseId, $userId);

        // Tìm bài học hiện tại (nếu không chỉ định, lấy bài đầu tiên hoặc bài đang học dở)
        $currentLesson = null;
        $firstLesson = null;
        $allLessonsList = [];

        foreach ($curriculum as $sec) {
            foreach ($sec['lessons'] as $les) {
                $allLessonsList[] = $les;
                if ($firstLesson === null) {
                    $firstLesson = $les;
                }
                if ($lessonId !== null && (int) $les['id'] === $lessonId) {
                    $currentLesson = $les;
                }
                if ($lessonId === null && !$les['is_completed'] && $currentLesson === null) {
                    $currentLesson = $les;
                }
            }
        }

        if ($currentLesson === null) {
            $currentLesson = $firstLesson;
        }

        // Tìm bài học trước (prevLesson) và bài học sau (nextLesson)
        $prevLesson = null;
        $nextLesson = null;
        if ($currentLesson) {
            foreach ($allLessonsList as $idx => $item) {
                if ((int) $item['id'] === (int) $currentLesson['id']) {
                    if (isset($allLessonsList[$idx - 1])) {
                        $prevLesson = $allLessonsList[$idx - 1];
                    }
                    if (isset($allLessonsList[$idx + 1])) {
                        $nextLesson = $allLessonsList[$idx + 1];
                    }
                    break;
                }
            }
        }

        // Lấy danh sách thảo luận / hỏi đáp cho bài học này
        $db = \App\DB::connection();
        $discStmt = $db->prepare("SELECT cd.*, u.name AS user_name, u.role AS user_role, u.avatar AS user_avatar
            FROM course_discussions cd
            INNER JOIN users u ON u.id = cd.user_id
            WHERE cd.lesson_id = ?
            ORDER BY cd.id ASC");
        $discStmt->execute([(int) ($currentLesson['id'] ?? 0)]);
        $discussions = $discStmt->fetchAll(\PDO::FETCH_ASSOC);

        // Lấy danh sách ghi chú cá nhân của học viên cho bài học này
        $notesStmt = $db->prepare("SELECT * FROM lesson_notes 
            WHERE lesson_id = ? AND user_id = ?
            ORDER BY timestamp_seconds ASC, id ASC");
        $notesStmt->execute([(int) ($currentLesson['id'] ?? 0), $userId]);
        $notes = $notesStmt->fetchAll(\PDO::FETCH_ASSOC);

        $enrollment = $this->enrollmentModel->find($userId, $courseId);

        view('client.classroom.index', [
            'course' => $course,
            'curriculum' => $curriculum,
            'currentLesson' => $currentLesson,
            'prevLesson' => $prevLesson,
            'nextLesson' => $nextLesson,
            'discussions' => $discussions,
            'notes' => $notes,
            'enrollment' => $enrollment,
            'user' => $user,
            'title' => 'Phòng học: ' . $course['title'],
        ], 'layouts.blank');
    }

    public function postDiscussion(int $courseId, int $lessonId): void
    {
        $user = authUser();
        if (!$user) {
            flash('error', 'Vui lòng đăng nhập.');
            redirect('/login');
        }

        $content = trim($_POST['content'] ?? '');
        if ($content !== '') {
            $db = \App\DB::connection();
            $stmt = $db->prepare("INSERT INTO course_discussions (lesson_id, user_id, content) VALUES (?, ?, ?)");
            $stmt->execute([$lessonId, $user['id'], $content]);
            flash('success', 'Đã đăng bình luận / câu hỏi thành công!');
        }

        redirect("/learn/{$courseId}/lesson/{$lessonId}#discussionTab");
    }

    public function saveNote(int $lessonId): void
    {
        header('Content-Type: application/json; charset=UTF-8');
        $user = authUser();
        if (!$user) {
            http_response_code(401);
            echo json_encode(['ok' => false, 'error' => 'Chưa đăng nhập']);
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        if (!$payload) {
            $payload = $_POST;
        }

        $content = trim($payload['content'] ?? '');
        $timestamp = max(0, (int) ($payload['timestamp_seconds'] ?? 0));

        if ($content === '') {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Nội dung ghi chú không được để trống']);
            return;
        }

        $db = \App\DB::connection();
        $stmt = $db->prepare("INSERT INTO lesson_notes (lesson_id, user_id, timestamp_seconds, content, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$lessonId, (int) $user['id'], $timestamp, $content]);
        $noteId = (int) $db->lastInsertId();

        echo json_encode([
            'ok' => true,
            'note' => [
                'id' => $noteId,
                'lesson_id' => $lessonId,
                'timestamp_seconds' => $timestamp,
                'content' => $content,
                'created_at' => date('Y-m-d H:i:s'),
                'time_formatted' => gmdate("i:s", $timestamp),
            ]
        ]);
    }

    public function deleteNote(int $noteId): void
    {
        header('Content-Type: application/json; charset=UTF-8');
        $user = authUser();
        if (!$user) {
            http_response_code(401);
            echo json_encode(['ok' => false, 'error' => 'Chưa đăng nhập']);
            return;
        }

        $db = \App\DB::connection();
        $stmt = $db->prepare("DELETE FROM lesson_notes WHERE id = ? AND user_id = ?");
        $stmt->execute([$noteId, (int) $user['id']]);

        echo json_encode(['ok' => true]);
    }

    public function updateProgress(): void
    {
        header('Content-Type: application/json; charset=UTF-8');
        $user = authUser();
        if (!$user) {
            http_response_code(401);
            echo json_encode(['ok' => false, 'error' => 'Chưa đăng nhập']);
            return;
        }

        $userId = (int) $user['id'];
        $payload = json_decode(file_get_contents('php://input'), true);

        $courseId = (int) ($payload['course_id'] ?? 0);
        $lessonId = (int) ($payload['lesson_id'] ?? 0);
        $lastWatchSecond = (int) ($payload['last_watch_second'] ?? 0);
        $isCompleted = !empty($payload['is_completed']);

        if (!$courseId || !$lessonId) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Thiếu thông tin bài học']);
            return;
        }

        // Lưu tiến độ bài học
        $this->progressModel->saveProgress($userId, $lessonId, $lastWatchSecond, $isCompleted);

        // Tính lại % tiến độ tổng thể của khóa học
        $newPercent = $this->enrollmentModel->updateProgress($userId, $courseId);

        // Kiểm tra xem có chứng chỉ chưa
        $certCode = null;
        if ($newPercent >= 100.00) {
            $certCode = $this->enrollmentModel->checkAndIssueCertificate($userId, $courseId);
        }

        echo json_encode([
            'ok' => true,
            'progress_percent' => $newPercent,
            'is_completed' => $isCompleted,
            'certificate_code' => $certCode,
        ]);
    }

    public function downloadMaterial(int $courseId, int $materialId): void
    {
        $user = authUser();
        if (!$user) {
            http_response_code(403);
            die('Vui lòng đăng nhập để tải tài liệu.');
        }

        $userId = (int) $user['id'];
        if (!isAdmin() && !$this->enrollmentModel->isEnrolled($userId, $courseId)) {
            http_response_code(403);
            die('Bạn chưa đăng ký khóa học này.');
        }

        $db = \App\DB::connection();
        $stmt = $db->prepare("SELECT * FROM lesson_materials WHERE id = ?");
        $stmt->execute([$materialId]);
        $material = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$material) {
            http_response_code(404);
            die('Tài liệu không tồn tại.');
        }

        // Tăng lượt tải
        $db->prepare("UPDATE lesson_materials SET download_count = download_count + 1 WHERE id = ?")->execute([$materialId]);

        $filePath = BASE_PATH . $material['file_path'];
        if (!file_exists($filePath)) {
            // Demo fallback text file
            header('Content-Type: text/plain; charset=UTF-8');
            header('Content-Disposition: attachment; filename="' . basename($material['title']) . '.txt"');
            echo "Tài liệu học tập: " . $material['title'] . "\nKhóa học ID: " . $courseId . "\nHọc viên: " . $user['name'];
            exit;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($material['title']) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
}
