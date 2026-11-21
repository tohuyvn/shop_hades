<?php

namespace App;

use PDO;
use Throwable;

class LmsMigrator
{
    public static function run(): array
    {
        $pdo = DB::connection();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $logs = [];

        // 1. Cập nhật bảng users (thêm role student, instructor, avatar, status)
        try {
            $pdo->exec("ALTER TABLE users MODIFY role ENUM('admin','customer','shipper','student','instructor') NOT NULL DEFAULT 'student'");
            $logs[] = "Updated users.role enum";
        } catch (Throwable $e) {
            $logs[] = "users.role enum update note: " . $e->getMessage();
        }

        try {
            $pdo->exec("ALTER TABLE users ADD COLUMN avatar VARCHAR(255) NULL AFTER phone");
            $logs[] = "Added users.avatar";
        } catch (Throwable $e) {
            // Already exists
        }

        try {
            $pdo->exec("ALTER TABLE users ADD COLUMN status ENUM('active','inactive','banned') NOT NULL DEFAULT 'active' AFTER role");
            $logs[] = "Added users.status";
        } catch (Throwable $e) {
            // Already exists
        }

        // 2. Bổ sung cột cho bảng categories nếu cần
        try {
            $pdo->exec("ALTER TABLE categories ADD COLUMN slug VARCHAR(191) NULL UNIQUE AFTER name");
        } catch (Throwable) {}
        try {
            $pdo->exec("ALTER TABLE categories ADD COLUMN description VARCHAR(255) NULL AFTER slug");
        } catch (Throwable) {}
        try {
            $pdo->exec("ALTER TABLE categories ADD COLUMN sort_order INT DEFAULT 0 AFTER description");
        } catch (Throwable) {}
        try {
            $pdo->exec("ALTER TABLE categories ADD COLUMN is_active BOOLEAN DEFAULT TRUE AFTER sort_order");
        } catch (Throwable) {}

        // 3. Bảng instructor_profiles
        $pdo->exec("CREATE TABLE IF NOT EXISTS instructor_profiles (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL UNIQUE,
            headline VARCHAR(255) NULL,
            bio TEXT NULL,
            qualification VARCHAR(255) NULL,
            experience_years TINYINT UNSIGNED DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table instructor_profiles";

        // 3. Bảng courses
        $pdo->exec("CREATE TABLE IF NOT EXISTS courses (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            instructor_id INT UNSIGNED NOT NULL,
            category_id INT UNSIGNED NULL,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(191) NOT NULL UNIQUE,
            summary VARCHAR(500) NULL,
            description LONGTEXT NULL,
            thumbnail VARCHAR(255) NULL,
            price DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
            sale_price DECIMAL(12, 2) NULL,
            level ENUM('all', 'beginner', 'intermediate', 'advanced') DEFAULT 'all',
            status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
            total_duration_minutes INT UNSIGNED DEFAULT 0,
            total_lessons INT UNSIGNED DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE RESTRICT,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
            INDEX idx_course_status (status),
            INDEX idx_course_price (price, sale_price)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table courses";

        // 4. Bảng sections
        $pdo->exec("CREATE TABLE IF NOT EXISTS sections (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            course_id INT UNSIGNED NOT NULL,
            title VARCHAR(255) NOT NULL,
            sort_order INT UNSIGNED NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
            INDEX idx_section_sort (course_id, sort_order)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table sections";

        // 5. Bảng lessons
        $pdo->exec("CREATE TABLE IF NOT EXISTS lessons (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            section_id INT UNSIGNED NOT NULL,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(191) NULL,
            lesson_type ENUM('video', 'document', 'quiz') NOT NULL DEFAULT 'video',
            content LONGTEXT NULL,
            is_preview BOOLEAN DEFAULT FALSE,
            sort_order INT UNSIGNED NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE,
            INDEX idx_lesson_sort (section_id, sort_order)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table lessons";

        // 6. Bảng lesson_videos
        $pdo->exec("CREATE TABLE IF NOT EXISTS lesson_videos (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            lesson_id INT UNSIGNED NOT NULL UNIQUE,
            provider ENUM('local', 'youtube', 'vimeo', 's3') DEFAULT 'local',
            video_url VARCHAR(500) NOT NULL,
            duration_seconds INT UNSIGNED NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table lesson_videos";

        // 7. Bảng lesson_materials
        $pdo->exec("CREATE TABLE IF NOT EXISTS lesson_materials (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            lesson_id INT UNSIGNED NOT NULL,
            title VARCHAR(255) NOT NULL,
            file_path VARCHAR(500) NOT NULL,
            file_type VARCHAR(50) NULL,
            file_size_bytes BIGINT UNSIGNED DEFAULT 0,
            download_count INT UNSIGNED DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table lesson_materials";

        // 8. Bảng enrollments
        $pdo->exec("CREATE TABLE IF NOT EXISTS enrollments (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            course_id INT UNSIGNED NOT NULL,
            status ENUM('active', 'expired', 'revoked') NOT NULL DEFAULT 'active',
            progress_percent DECIMAL(5, 2) NOT NULL DEFAULT 0.00,
            enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            completed_at TIMESTAMP NULL,
            UNIQUE KEY uq_user_course (user_id, course_id),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
            INDEX idx_enroll_user (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table enrollments";

        // 9. Bảng lesson_progress
        $pdo->exec("CREATE TABLE IF NOT EXISTS lesson_progress (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            lesson_id INT UNSIGNED NOT NULL,
            is_completed BOOLEAN NOT NULL DEFAULT FALSE,
            last_watch_second INT UNSIGNED NOT NULL DEFAULT 0,
            completed_at TIMESTAMP NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY uq_user_lesson (user_id, lesson_id),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table lesson_progress";

        // 10. Bảng quizzes
        $pdo->exec("CREATE TABLE IF NOT EXISTS quizzes (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            course_id INT UNSIGNED NOT NULL,
            lesson_id INT UNSIGNED NULL UNIQUE,
            title VARCHAR(255) NOT NULL,
            description TEXT NULL,
            duration_minutes INT UNSIGNED NOT NULL DEFAULT 15,
            passing_score DECIMAL(5, 2) NOT NULL DEFAULT 80.00,
            max_attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
            is_random_questions BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
            FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table quizzes";

        // 11. Bảng quiz_questions
        $pdo->exec("CREATE TABLE IF NOT EXISTS quiz_questions (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            quiz_id INT UNSIGNED NOT NULL,
            question_text TEXT NOT NULL,
            question_type ENUM('single_choice', 'multiple_choice', 'true_false') NOT NULL DEFAULT 'single_choice',
            points DECIMAL(5, 2) NOT NULL DEFAULT 1.00,
            explanation TEXT NULL,
            sort_order INT UNSIGNED NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table quiz_questions";

        // 12. Bảng quiz_answers
        $pdo->exec("CREATE TABLE IF NOT EXISTS quiz_answers (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            question_id INT UNSIGNED NOT NULL,
            answer_text TEXT NOT NULL,
            is_correct BOOLEAN NOT NULL DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (question_id) REFERENCES quiz_questions(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table quiz_answers";

        // 13. Bảng quiz_attempts
        $pdo->exec("CREATE TABLE IF NOT EXISTS quiz_attempts (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            quiz_id INT UNSIGNED NOT NULL,
            score_achieved DECIMAL(5, 2) NOT NULL DEFAULT 0.00,
            total_score DECIMAL(5, 2) NOT NULL DEFAULT 0.00,
            score_percentage DECIMAL(5, 2) NOT NULL DEFAULT 0.00,
            is_passed BOOLEAN NOT NULL DEFAULT FALSE,
            started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            submitted_at TIMESTAMP NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
            INDEX idx_attempt_user (user_id, quiz_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table quiz_attempts";

        // 14. Bảng quiz_attempt_answers
        $pdo->exec("CREATE TABLE IF NOT EXISTS quiz_attempt_answers (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            attempt_id INT UNSIGNED NOT NULL,
            question_id INT UNSIGNED NOT NULL,
            answer_id INT UNSIGNED NOT NULL,
            is_correct BOOLEAN NOT NULL DEFAULT FALSE,
            FOREIGN KEY (attempt_id) REFERENCES quiz_attempts(id) ON DELETE CASCADE,
            FOREIGN KEY (question_id) REFERENCES quiz_questions(id) ON DELETE CASCADE,
            FOREIGN KEY (answer_id) REFERENCES quiz_answers(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table quiz_attempt_answers";

        // 15. Bảng certificates
        $pdo->exec("CREATE TABLE IF NOT EXISTS certificates (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            certificate_code VARCHAR(64) NOT NULL UNIQUE,
            user_id INT UNSIGNED NOT NULL,
            course_id INT UNSIGNED NOT NULL,
            issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            pdf_path VARCHAR(255) NULL,
            status ENUM('valid', 'revoked') NOT NULL DEFAULT 'valid',
            UNIQUE KEY uq_user_course_cert (user_id, course_id),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
            INDEX idx_cert_code (certificate_code)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table certificates";

        // 16. Bảng course_reviews (Đánh giá & nhận xét khóa học)
        $pdo->exec("CREATE TABLE IF NOT EXISTS course_reviews (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            course_id INT UNSIGNED NOT NULL,
            user_id INT UNSIGNED NOT NULL,
            rating TINYINT UNSIGNED NOT NULL DEFAULT 5,
            comment TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_course_rating (course_id, rating)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table course_reviews";

        // 17. Bảng course_discussions (Hỏi đáp & Thảo luận bài học)
        $pdo->exec("CREATE TABLE IF NOT EXISTS course_discussions (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            lesson_id INT UNSIGNED NOT NULL,
            user_id INT UNSIGNED NOT NULL,
            content TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_lesson_discuss (lesson_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $logs[] = "Created table course_discussions";

        // 18. Khởi tạo dữ liệu mẫu nếu chưa có
        self::seedSampleData($pdo, $logs);

        return $logs;
    }

    private static function seedSampleData(PDO $pdo, array &$logs): void
    {
        // Kiểm tra xem đã có giảng viên mẫu chưa
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute(['giangvien@lms.local']);
        $instructorId = $stmt->fetchColumn();

        if (!$instructorId) {
            $passHash = password_hash('password123', PASSWORD_BCRYPT);
            $insStmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'instructor', 'active')");
            $insStmt->execute(['ThS. Nguyễn Văn A', 'giangvien@lms.local', $passHash]);
            $instructorId = $pdo->lastInsertId();

            $profStmt = $pdo->prepare("INSERT INTO instructor_profiles (user_id, headline, bio, qualification, experience_years) VALUES (?, ?, ?, ?, ?)");
            $profStmt->execute([
                $instructorId,
                'Senior Fullstack Engineer & Giảng viên Công nghệ',
                'Hơn 8 năm kinh nghiệm phát triển hệ thống và giảng dạy cho hàng ngàn học viên.',
                'Thạc sĩ Khoa học Máy tính',
                8
            ]);
            $logs[] = "Seeded instructor user: giangvien@lms.local (password: password123)";
        }

        // Tạo danh mục LMS
        $catStmt = $pdo->query("SELECT id FROM categories LIMIT 1");
        $catId = $catStmt->fetchColumn();

        if (!$catId) {
            $pdo->exec("INSERT INTO categories (name, slug, description) VALUES ('Lập trình Web Fullstack', 'lap-trinh-web-fullstack', 'Khóa học lập trình từ cơ bản tới nâng cao')");
            $catId = $pdo->lastInsertId();
            $logs[] = "Seeded LMS categories";
        } else {
            // Cập nhật slug an toàn tránh trùng lặp
            $pdo->exec("UPDATE categories SET slug = CONCAT('cat-', id) WHERE slug IS NULL OR slug = ''");
        }

        // Tạo khóa học mẫu
        $courseStmt = $pdo->prepare("SELECT id FROM courses WHERE slug = ?");
        $courseStmt->execute(['khoa-hoc-lap-trinh-web-fullstack-tu-zero-den-hero']);
        $courseId = $courseStmt->fetchColumn();

        if (!$courseId) {
            $stmt = $pdo->prepare("INSERT INTO courses (instructor_id, category_id, title, slug, summary, description, thumbnail, price, sale_price, level, status, total_duration_minutes, total_lessons) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'published', ?, ?)");
            $stmt->execute([
                $instructorId,
                $catId,
                'Khóa học Lập trình Web Fullstack từ Zero đến Hero',
                'khoa-hoc-lap-trinh-web-fullstack-tu-zero-den-hero',
                'Lộ trình toàn diện làm chủ PHP, MySQL, Frontend hiện đại và tư duy kiến trúc dự án thực tế.',
                '<h3>Chào mừng bạn đến với khóa học Lập trình Web Fullstack!</h3><p>Khóa học này sẽ đưa bạn từ người chưa biết gì tới khi có thể tự tay xây dựng một website hoàn chỉnh với đầy đủ tính năng xác thực, thanh toán, thi trắc nghiệm và cấp chứng chỉ.</p>',
                '/image/banner1.png',
                599000.00,
                299000.00,
                'beginner',
                180,
                3
            ]);
            $courseId = $pdo->lastInsertId();
            $logs[] = "Seeded sample course (ID: $courseId)";

            // Tạo chương 1: Giới thiệu
            $secStmt = $pdo->prepare("INSERT INTO sections (course_id, title, sort_order) VALUES (?, ?, ?)");
            $secStmt->execute([$courseId, 'Chương 1: Tổng quan và Thiết lập môi trường', 1]);
            $sec1Id = $pdo->lastInsertId();

            // Tạo bài học 1: Video Giới thiệu (học thử preview)
            $lesStmt = $pdo->prepare("INSERT INTO lessons (section_id, title, slug, lesson_type, is_preview, sort_order) VALUES (?, ?, ?, 'video', 1, 1)");
            $lesStmt->execute([$sec1Id, 'Bài 1: Giới thiệu khóa học & Phương pháp học hiệu quả', 'bai-1-gioi-thieu-khoa-hoc']);
            $les1Id = $pdo->lastInsertId();

            $vidStmt = $pdo->prepare("INSERT INTO lesson_videos (lesson_id, provider, video_url, duration_seconds) VALUES (?, ?, ?, ?)");
            $vidStmt->execute([$les1Id, 'youtube', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 360]);

            // Tài liệu đính kèm cho bài 1
            $matStmt = $pdo->prepare("INSERT INTO lesson_materials (lesson_id, title, file_path, file_type, file_size_bytes) VALUES (?, ?, ?, 'pdf', ?)");
            $matStmt->execute([$les1Id, 'Giao_trinh_tong_quan_khoa_hoc.pdf', '/storage/materials/sample_doc.pdf', 1048576]);

            // Tạo bài học 2: Video Kiến trúc MVC
            $lesStmt->execute([$sec1Id, 'Bài 2: Hiểu rõ mô hình MVC trong thực tế', 'bai-2-hieu-ro-mo-hinh-mvc']);
            $les2Id = $pdo->lastInsertId();
            $vidStmt->execute([$les2Id, 'youtube', 'https://www.youtube.com/embed/M7lc1UVf-VE', 720]);

            // Tạo bài học 3: Bài kiểm tra trắc nghiệm kết thúc chương
            $lesStmt->execute([$sec1Id, 'Bài 3: Bài kiểm tra đánh giá kiến thức Chương 1', 'bai-3-kiem-tra-chuong-1']);
            $les3Id = $pdo->lastInsertId();

            // Tạo Quiz
            $quizStmt = $pdo->prepare("INSERT INTO quizzes (course_id, lesson_id, title, description, duration_minutes, passing_score, max_attempts) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $quizStmt->execute([
                $courseId,
                $les3Id,
                'Kiểm tra trắc nghiệm Chương 1',
                'Bài kiểm tra gồm 3 câu hỏi trắc nghiệm kiến thức cơ bản. Đạt từ 80% trở lên để qua bài.',
                10,
                80.00,
                3
            ]);
            $quizId = $pdo->lastInsertId();

            // Câu hỏi 1
            $qStmt = $pdo->prepare("INSERT INTO quiz_questions (quiz_id, question_text, question_type, points, explanation, sort_order) VALUES (?, ?, 'single_choice', 1.00, ?, ?)");
            $qStmt->execute([$quizId, 'Trong mô hình MVC, thành phần nào chịu trách nhiệm truy vấn và xử lý dữ liệu từ Database?', 'Model đại diện cho cấu trúc dữ liệu và xử lý logic truy vấn Database.', 1]);
            $q1Id = $pdo->lastInsertId();

            $ansStmt = $pdo->prepare("INSERT INTO quiz_answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
            $ansStmt->execute([$q1Id, 'Model', 1]);
            $ansStmt->execute([$q1Id, 'View', 0]);
            $ansStmt->execute([$q1Id, 'Controller', 0]);
            $ansStmt->execute([$q1Id, 'Router', 0]);

            // Câu hỏi 2
            $qStmt->execute([$quizId, 'Khi người dùng gửi request từ trình duyệt, thành phần nào nhận và điều phối đầu tiên?', 'Router/Controller nhận request và điều phối đến các hành động tương ứng.', 2]);
            $q2Id = $pdo->lastInsertId();
            $ansStmt->execute([$q2Id, 'Controller', 1]);
            $ansStmt->execute([$q2Id, 'View', 0]);
            $ansStmt->execute([$q2Id, 'Database Server', 0]);
            $ansStmt->execute([$q2Id, 'Session Storage', 0]);

            // Câu hỏi 3
            $qStmt->execute([$quizId, 'Giao diện hiển thị HTML tới người dùng thuộc thành phần nào?', 'View là nơi render giao diện HTML/CSS/JS cho người dùng.', 3]);
            $q3Id = $pdo->lastInsertId();
            $ansStmt->execute([$q3Id, 'View', 1]);
            $ansStmt->execute([$q3Id, 'Model', 0]);
            $ansStmt->execute([$q3Id, 'Migration', 0]);
            $ansStmt->execute([$q3Id, 'Middleware', 0]);

            $logs[] = "Seeded quiz with 3 questions and answers";
        }

        // Tạo ghi danh và chứng chỉ mẫu cho học viên demo nếu chưa có
        $sampleCertCode = 'CERT-2026-X8B9K2';
        $certCheck = $pdo->prepare("SELECT id FROM certificates WHERE certificate_code = ?");
        $certCheck->execute([$sampleCertCode]);
        if (!$certCheck->fetchColumn()) {
            $stuId = (int) $pdo->query("SELECT id FROM users WHERE role IN ('student', 'customer') LIMIT 1")->fetchColumn();
            if ($stuId && $courseId) {
                $pdo->exec("INSERT INTO enrollments (user_id, course_id, status, progress_percent, completed_at) VALUES ({$stuId}, {$courseId}, 'active', 100.00, NOW()) ON DUPLICATE KEY UPDATE progress_percent = 100.00, status = 'active'");
                $pdo->exec("INSERT INTO certificates (certificate_code, user_id, course_id, status) VALUES ('{$sampleCertCode}', {$stuId}, {$courseId}, 'valid')");
                $logs[] = "Seeded sample certificate: {$sampleCertCode} for user ID {$stuId}";
            }
        }
    }
}
