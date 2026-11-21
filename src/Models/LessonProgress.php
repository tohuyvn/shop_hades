<?php

namespace App\Models;

use App\DB;
use PDO;

class LessonProgress
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DB::connection();
    }

    public function saveProgress(int $userId, int $lessonId, int $lastWatchSecond, bool $isCompleted = false): bool
    {
        $completedAt = $isCompleted ? date('Y-m-d H:i:s') : null;

        $stmt = $this->db->prepare("INSERT INTO lesson_progress (user_id, lesson_id, last_watch_second, is_completed, completed_at)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                last_watch_second = VALUES(last_watch_second),
                is_completed = CASE WHEN is_completed = 1 THEN 1 ELSE VALUES(is_completed) END,
                completed_at = CASE WHEN completed_at IS NOT NULL THEN completed_at ELSE VALUES(completed_at) END");

        return $stmt->execute([
            $userId,
            $lessonId,
            $lastWatchSecond,
            $isCompleted ? 1 : 0,
            $completedAt,
        ]);
    }

    public function markCompleted(int $userId, int $lessonId): bool
    {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("INSERT INTO lesson_progress (user_id, lesson_id, is_completed, completed_at)
            VALUES (?, ?, 1, ?)
            ON DUPLICATE KEY UPDATE is_completed = 1, completed_at = COALESCE(completed_at, VALUES(completed_at))");

        return $stmt->execute([$userId, $lessonId, $now]);
    }

    public function getProgress(int $userId, int $lessonId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM lesson_progress WHERE user_id = ? AND lesson_id = ? LIMIT 1");
        $stmt->execute([$userId, $lessonId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }
}
