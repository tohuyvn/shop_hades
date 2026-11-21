<?php

namespace App\Models;

use App\DB;
use PDO;

class Certificate
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DB::connection();
    }

    public function findByCode(string $code): ?array
    {
        $stmt = $this->db->prepare("SELECT cert.*, u.name AS student_name, u.email AS student_email,
                       c.title AS course_title, c.slug AS course_slug,
                       ins.name AS instructor_name
                FROM certificates cert
                INNER JOIN users u ON u.id = cert.user_id
                INNER JOIN courses c ON c.id = cert.course_id
                LEFT JOIN users ins ON ins.id = c.instructor_id
                WHERE cert.certificate_code = ? LIMIT 1");
        $stmt->execute([trim($code)]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function findByUserAndCourse(int $userId, int $courseId): ?array
    {
        $stmt = $this->db->prepare("SELECT cert.*, u.name AS student_name, c.title AS course_title, ins.name AS instructor_name
            FROM certificates cert
            INNER JOIN users u ON u.id = cert.user_id
            INNER JOIN courses c ON c.id = cert.course_id
            LEFT JOIN users ins ON ins.id = c.instructor_id
            WHERE cert.user_id = ? AND cert.course_id = ? LIMIT 1");
        $stmt->execute([$userId, $courseId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT cert.*, u.name AS student_name, u.email AS student_email, c.title AS course_title
            FROM certificates cert
            INNER JOIN users u ON u.id = cert.user_id
            INNER JOIN courses c ON c.id = cert.course_id
            ORDER BY cert.id DESC");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
