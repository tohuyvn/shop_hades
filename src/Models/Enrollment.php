<?php

namespace App\Models;

use App\DB;
use PDO;

class Enrollment
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DB::connection();
    }

    public function enroll(int $userId, int $courseId): bool
    {
        $stmt = $this->db->prepare("INSERT INTO enrollments (user_id, course_id, status, progress_percent)
            VALUES (?, ?, 'active', 0.00)
            ON DUPLICATE KEY UPDATE status = 'active'");

        return $stmt->execute([$userId, $courseId]);
    }

    public function isEnrolled(int $userId, int $courseId): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ? AND status = 'active' LIMIT 1");
        $stmt->execute([$userId, $courseId]);

        return (bool) $stmt->fetchColumn();
    }

    public function find(int $userId, int $courseId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ? LIMIT 1");
        $stmt->execute([$userId, $courseId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function getUserCourses(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT e.*, c.title, c.slug, c.thumbnail, c.total_lessons, c.total_duration_minutes,
                                           u.name AS instructor_name,
                                           cert.certificate_code,
                                           (SELECT COUNT(lp.id) FROM lesson_progress lp 
                                            INNER JOIN lessons l ON l.id = lp.lesson_id 
                                            INNER JOIN sections s ON s.id = l.section_id 
                                            WHERE s.course_id = c.id AND lp.user_id = e.user_id AND lp.is_completed = 1) AS completed_lessons_count
                FROM enrollments e
                INNER JOIN courses c ON c.id = e.course_id
                LEFT JOIN users u ON u.id = c.instructor_id
                LEFT JOIN certificates cert ON cert.course_id = c.id AND cert.user_id = e.user_id
                WHERE e.user_id = ? AND e.status = 'active'
                ORDER BY e.enrolled_at DESC");
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateProgress(int $userId, int $courseId): float
    {
        // 1. Tính tổng số bài học của khóa học
        $stmt = $this->db->prepare("SELECT COUNT(l.id) FROM lessons l INNER JOIN sections s ON s.id = l.section_id WHERE s.course_id = ?");
        $stmt->execute([$courseId]);
        $totalLessons = (int) $stmt->fetchColumn();

        if ($totalLessons === 0) {
            return 0.00;
        }

        // 2. Tính số bài học đã hoàn thành
        $stmt = $this->db->prepare("SELECT COUNT(lp.id) 
            FROM lesson_progress lp
            INNER JOIN lessons l ON l.id = lp.lesson_id
            INNER JOIN sections s ON s.id = l.section_id
            WHERE s.course_id = ? AND lp.user_id = ? AND lp.is_completed = 1");
        $stmt->execute([$courseId, $userId]);
        $completedLessons = (int) $stmt->fetchColumn();

        $percent = round(($completedLessons / $totalLessons) * 100, 2);
        if ($percent > 100.00) {
            $percent = 100.00;
        }

        $completedAt = ($percent >= 100.00) ? date('Y-m-d H:i:s') : null;

        $updateStmt = $this->db->prepare("UPDATE enrollments SET progress_percent = ?, completed_at = COALESCE(completed_at, ?) WHERE user_id = ? AND course_id = ?");
        $updateStmt->execute([$percent, $completedAt, $userId, $courseId]);

        // Nếu đạt 100%, kiểm tra cấp chứng chỉ tự động
        if ($percent >= 100.00) {
            $this->checkAndIssueCertificate($userId, $courseId);
        }

        return $percent;
    }

    public function checkAndIssueCertificate(int $userId, int $courseId): ?string
    {
        // 1. Kiểm tra xem đã có chứng chỉ chưa
        $certStmt = $this->db->prepare("SELECT certificate_code FROM certificates WHERE user_id = ? AND course_id = ? LIMIT 1");
        $certStmt->execute([$userId, $courseId]);
        $existing = $certStmt->fetchColumn();
        if ($existing) {
            return $existing;
        }

        // 2. Kiểm tra xem các bài Quiz trong khóa có đạt passing_score không
        $quizStmt = $this->db->prepare("SELECT q.id, q.passing_score
            FROM quizzes q
            WHERE q.course_id = ?");
        $quizStmt->execute([$courseId]);
        $quizzes = $quizStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($quizzes as $quiz) {
            $passStmt = $this->db->prepare("SELECT is_passed FROM quiz_attempts WHERE user_id = ? AND quiz_id = ? AND is_passed = 1 LIMIT 1");
            $passStmt->execute([$userId, $quiz['id']]);
            if (!$passStmt->fetchColumn()) {
                // Chưa vượt qua tất cả bài kiểm tra bắt buộc
                return null;
            }
        }

        // 3. Cấp chứng chỉ
        $code = 'CERT-' . date('Y') . '-' . strtoupper(substr(md5($userId . '-' . $courseId . '-' . time()), 0, 8));
        $insert = $this->db->prepare("INSERT INTO certificates (certificate_code, user_id, course_id, status) VALUES (?, ?, ?, 'valid')");
        $insert->execute([$code, $userId, $courseId]);

        return $code;
    }
}
