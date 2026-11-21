<?php

namespace App\Models;

use App\DB;
use PDO;

class Course
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DB::connection();
    }

    public function all(array $filters = []): array
    {
        $sql = "SELECT c.*, u.name AS instructor_name, cat.name AS category_name,
                       (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id) AS student_count
                FROM courses c
                LEFT JOIN users u ON u.id = c.instructor_id
                LEFT JOIN categories cat ON cat.id = c.category_id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND c.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['category_id'])) {
            $sql .= " AND c.category_id = ?";
            $params[] = (int) $filters['category_id'];
        }

        if (!empty($filters['level']) && $filters['level'] !== 'all') {
            $sql .= " AND (c.level = ? OR c.level = 'all')";
            $params[] = $filters['level'];
        }

        if (!empty($filters['price_type'])) {
            if ($filters['price_type'] === 'free') {
                $sql .= " AND (c.price = 0 OR c.sale_price = 0)";
            } elseif ($filters['price_type'] === 'paid') {
                $sql .= " AND c.price > 0";
            }
        }

        if (!empty($filters['q'])) {
            $sql .= " AND (c.title LIKE ? OR c.summary LIKE ?)";
            $keyword = '%' . trim($filters['q']) . '%';
            $params[] = $keyword;
            $params[] = $keyword;
        }

        $sql .= " ORDER BY c.id DESC";

        if (!empty($filters['limit'])) {
            $limit = (int) $filters['limit'];
            $offset = (int) ($filters['offset'] ?? 0);
            $sql .= " LIMIT {$offset}, {$limit}";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT c.*, u.name AS instructor_name, ip.headline AS instructor_headline, ip.bio AS instructor_bio, cat.name AS category_name
            FROM courses c
            LEFT JOIN users u ON u.id = c.instructor_id
            LEFT JOIN instructor_profiles ip ON ip.user_id = u.id
            LEFT JOIN categories cat ON cat.id = c.category_id
            WHERE c.id = ? LIMIT 1");
        $stmt->execute([$id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        return $course ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("SELECT c.*, u.name AS instructor_name, ip.headline AS instructor_headline, ip.bio AS instructor_bio, cat.name AS category_name
            FROM courses c
            LEFT JOIN users u ON u.id = c.instructor_id
            LEFT JOIN instructor_profiles ip ON ip.user_id = u.id
            LEFT JOIN categories cat ON cat.id = c.category_id
            WHERE c.slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        return $course ?: null;
    }

    public function getCurriculum(int $courseId, ?int $userId = null): array
    {
        $secStmt = $this->db->prepare("SELECT * FROM sections WHERE course_id = ? ORDER BY sort_order ASC, id ASC");
        $secStmt->execute([$courseId]);
        $sections = $secStmt->fetchAll(PDO::FETCH_ASSOC);

        $lesStmt = $this->db->prepare("SELECT l.*, lv.provider AS video_provider, lv.video_url, lv.duration_seconds,
                       q.id AS quiz_id, q.passing_score, q.duration_minutes
                FROM lessons l
                LEFT JOIN lesson_videos lv ON lv.lesson_id = l.id
                LEFT JOIN quizzes q ON q.lesson_id = l.id
                WHERE l.section_id = ?
                ORDER BY l.sort_order ASC, l.id ASC");

        $progressMap = [];
        if ($userId !== null) {
            $progStmt = $this->db->prepare("SELECT lp.lesson_id, lp.is_completed, lp.last_watch_second
                FROM lesson_progress lp
                INNER JOIN lessons l ON l.id = lp.lesson_id
                INNER JOIN sections s ON s.id = l.section_id
                WHERE s.course_id = ? AND lp.user_id = ?");
            $progStmt->execute([$courseId, $userId]);
            foreach ($progStmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $progressMap[$row['lesson_id']] = $row;
            }
        }

        foreach ($sections as &$section) {
            $lesStmt->execute([$section['id']]);
            $lessons = $lesStmt->fetchAll(PDO::FETCH_ASSOC);
            $secDuration = 0;
            $secWatched = 0;
            $secCompleted = 0;

            foreach ($lessons as &$lesson) {
                $lesson['is_completed'] = !empty($progressMap[$lesson['id']]['is_completed']);
                $watchSec = (int) ($progressMap[$lesson['id']]['last_watch_second'] ?? 0);
                $duration = (int) ($lesson['duration_seconds'] ?? 0);

                if ($lesson['is_completed']) {
                    $secCompleted++;
                    if ($watchSec === 0 && $duration > 0) {
                        $watchSec = $duration;
                    }
                }

                $lesson['last_watch_second'] = $watchSec;
                $lesson['watch_percent'] = ($duration > 0) ? min(100, (int) round(($watchSec / $duration) * 100)) : ($lesson['is_completed'] ? 100 : 0);

                $secDuration += $duration;
                $secWatched += ($duration > 0) ? min($duration, $watchSec) : $watchSec;

                // Fetch materials
                $matStmt = $this->db->prepare("SELECT id, title, file_type, file_size_bytes FROM lesson_materials WHERE lesson_id = ?");
                $matStmt->execute([$lesson['id']]);
                $lesson['materials'] = $matStmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $section['lessons'] = $lessons;
            $section['total_duration_seconds'] = $secDuration;
            $section['total_watched_seconds'] = $secWatched;
            $section['completed_lessons_count'] = $secCompleted;
        }

        return $sections;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO courses 
            (instructor_id, category_id, title, slug, summary, description, thumbnail, price, sale_price, level, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['instructor_id'],
            $data['category_id'] ?? null,
            $data['title'],
            $data['slug'],
            $data['summary'] ?? null,
            $data['description'] ?? null,
            $data['thumbnail'] ?? null,
            $data['price'] ?? 0,
            $data['sale_price'] ?? null,
            $data['level'] ?? 'all',
            $data['status'] ?? 'draft',
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE courses SET
            instructor_id = ?, category_id = ?, title = ?, slug = ?, summary = ?,
            description = ?, thumbnail = ?, price = ?, sale_price = ?, level = ?, status = ?
            WHERE id = ?");

        return $stmt->execute([
            $data['instructor_id'],
            $data['category_id'] ?? null,
            $data['title'],
            $data['slug'],
            $data['summary'] ?? null,
            $data['description'] ?? null,
            $data['thumbnail'] ?? null,
            $data['price'] ?? 0,
            $data['sale_price'] ?? null,
            $data['level'] ?? 'all',
            $data['status'] ?? 'draft',
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateCounters(int $courseId): void
    {
        $stmt = $this->db->prepare("UPDATE courses c SET 
            total_lessons = (SELECT COUNT(l.id) FROM lessons l INNER JOIN sections s ON s.id = l.section_id WHERE s.course_id = c.id),
            total_duration_minutes = (SELECT CEIL(COALESCE(SUM(lv.duration_seconds), 0) / 60) FROM lesson_videos lv INNER JOIN lessons l ON l.id = lv.lesson_id INNER JOIN sections s ON s.id = l.section_id WHERE s.course_id = c.id)
            WHERE c.id = ?");
        $stmt->execute([$courseId]);
    }

    public function getReviews(int $courseId): array
    {
        $stmt = $this->db->prepare("SELECT cr.*, u.name AS user_name, u.avatar AS user_avatar
            FROM course_reviews cr
            INNER JOIN users u ON u.id = cr.user_id
            WHERE cr.course_id = ?
            ORDER BY cr.id DESC");
        $stmt->execute([$courseId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAverageRating(int $courseId): array
    {
        $stmt = $this->db->prepare("SELECT ROUND(COALESCE(AVG(rating), 5.0), 1) AS avg_rating, COUNT(id) AS total_reviews
            FROM course_reviews WHERE course_id = ?");
        $stmt->execute([$courseId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'avg_rating' => (float) ($row['avg_rating'] ?? 5.0),
            'total_reviews' => (int) ($row['total_reviews'] ?? 0),
        ];
    }

    public function addReview(int $courseId, int $userId, int $rating, string $comment): bool
    {
        $rating = max(1, min(5, $rating));
        $stmt = $this->db->prepare("INSERT INTO course_reviews (course_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");

        return $stmt->execute([$courseId, $userId, $rating, trim($comment)]);
    }
}
