-- =============================================================
-- FILE EXPORT TOÀN BỘ CSDL VÀ DỮ LIỆU LMS CHO PHPMYADMIN
-- Ngày tạo: 2026-11-21 21:15:33
-- =============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- -------------------------------------------------------------
-- Bảng: `users`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','customer','shipper','student','instructor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `status` enum('active','inactive','banned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `failed_login_attempts` int unsigned NOT NULL DEFAULT '0',
  `lockout_until` datetime DEFAULT NULL,
  `loyalty_points` int unsigned NOT NULL DEFAULT '0',
  `loyalty_tier` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Standard',
  `two_factor_secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `users`
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `avatar`, `password`, `role`, `status`, `created_at`, `failed_login_attempts`, `lockout_until`, `loyalty_points`, `loyalty_tier`, `two_factor_secret`, `two_factor_enabled`) VALUES
(1, 'Admin', 'admin@shop.test', '0901234567', NULL, '$2y$10$kdcd7bTl11qWkeuQlDjHd.wgsCUWuTjJnwtXad0WNKtB/9154yWwu', 'admin', 'active', '2026-06-16 01:46:26', 0, NULL, 0, 'Standard', NULL, 0),
(2, 'Nguyễn Văn A', 'khach@shop.test', '0912345678', NULL, '$2y$10$Qrp7GX7/3abDVbnAZdVLX.1edRvM80TkGu4fyTWJPaFa27LA2WbAC', 'customer', 'active', '2026-06-16 01:46:26', 0, NULL, 17495, 'Diamond', NULL, 0),
(4, 'huy', 'huy28072k5@gmail.com', '028282728', NULL, '$2y$10$TR6H/lL76w.sf6an91P4CONNyFezc3xRAf9dUyQqKPj5FEcw.BIGG', 'customer', 'active', '2026-06-21 08:42:25', 0, NULL, 166646, 'Diamond', NULL, 0),
(21, 'Nguyễn Văn Shipper', 'shipper@shop.test', '0987654321', NULL, '$2y$10$Ad7KPM/fgPDwS9pfcemkVO.Rj33Co7FWQUtjX3faWjor3mIavORK6', 'shipper', 'active', '2026-07-10 20:00:06', 0, NULL, 0, 'Standard', NULL, 0),
(22, 'Tài khoản Google', 'user.google@gmail.com', '', NULL, '$2y$10$yp30kH9E0kFiPmcL3z2/gu.QGbEMuIIdCjjrHo//Xi5MeMH7m/bna', 'customer', 'active', '2026-08-10 00:13:29', 0, NULL, 0, 'Standard', NULL, 0),
(23, 'Google User', 'fanhinbenho@gmail.com', '', NULL, '$2y$10$KiESqwlXqDz72OJL1xu0PO/QHfnbMMVP9ylvgkTcUT0wo0Uruq2m6', 'customer', 'active', '2026-08-12 13:19:52', 0, NULL, 0, 'Standard', NULL, 0),
(24, 'Google User', 'huy28072k5@gmail.comgmaigmai', '', NULL, '$2y$10$.xlA7djohqSRJxDSAxwaOe2bbiP9Jr2PyjCIrceWECc9KBd/agHxi', 'customer', 'active', '2026-08-12 14:35:25', 0, NULL, 0, 'Standard', NULL, 0),
(25, 'ThS. Nguyễn Văn A', 'giangvien@lms.local', '', NULL, '$2y$10$DJs1JtsYS7c8XSc.//lW7OIj/ppI5T8vxyBzpERwKo/MDFB.t4z.6', 'instructor', 'active', '2026-11-21 11:19:27', 0, NULL, 0, 'Standard', NULL, 0),
(26, 'TS. Trần Hoàng Minh', 'minh.tran@lms.local', '', NULL, '$2y$10$.algbcOerNda/HuOgNCyeOjEDlPqhF.g8FT7ryw3FsX3CUNTOzOTm', 'instructor', 'active', '2026-11-21 11:33:11', 0, NULL, 0, 'Standard', NULL, 0),
(27, 'Lê Thị Mai Hương', 'huong.le@lms.local', '', NULL, '$2y$10$.algbcOerNda/HuOgNCyeOjEDlPqhF.g8FT7ryw3FsX3CUNTOzOTm', 'instructor', 'active', '2026-11-21 11:33:11', 0, NULL, 0, 'Standard', NULL, 0),
(28, 'Hoàng Văn Đức', 'duc.hoang@lms.local', '', NULL, '$2y$10$.algbcOerNda/HuOgNCyeOjEDlPqhF.g8FT7ryw3FsX3CUNTOzOTm', 'instructor', 'active', '2026-11-21 11:33:11', 0, NULL, 0, 'Standard', NULL, 0),
(40, 'ThS. Đặng Tuấn Anh', 'tuananh.dang@lms.local', '0912345678', NULL, '$2y$10$5G870H/L0syCspwd8XNQvez5jXU1X7iuDHPS3BHGMPHV4G/iv.2/m', 'instructor', 'active', '2026-11-21 11:47:30', 0, NULL, 0, 'Standard', NULL, 0),
(41, 'KTS. Vũ Hải Nam', 'hainam.vu@lms.local', '0912345678', NULL, '$2y$10$5G870H/L0syCspwd8XNQvez5jXU1X7iuDHPS3BHGMPHV4G/iv.2/m', 'instructor', 'active', '2026-11-21 11:47:30', 0, NULL, 0, 'Standard', NULL, 0),
(42, 'Bùi Quỳnh Chi', 'quynhchi.bui@lms.local', '0912345678', NULL, '$2y$10$5G870H/L0syCspwd8XNQvez5jXU1X7iuDHPS3BHGMPHV4G/iv.2/m', 'instructor', 'active', '2026-11-21 11:47:30', 0, NULL, 0, 'Standard', NULL, 0),
(43, 'Nguyễn Đình Quân', 'quan.nguyen@gmail.com', '0988776655', NULL, '$2y$10$5G870H/L0syCspwd8XNQvez5jXU1X7iuDHPS3BHGMPHV4G/iv.2/m', 'student', 'active', '2026-11-21 11:47:30', 0, NULL, 0, 'Standard', NULL, 0),
(44, 'Phạm Thuỳ Linh', 'linh.pham@gmail.com', '0988776655', NULL, '$2y$10$5G870H/L0syCspwd8XNQvez5jXU1X7iuDHPS3BHGMPHV4G/iv.2/m', 'student', 'active', '2026-11-21 11:47:30', 0, NULL, 0, 'Standard', NULL, 0),
(45, 'Hoàng Minh Tuấn', 'tuan.hoang@gmail.com', '0988776655', NULL, '$2y$10$5G870H/L0syCspwd8XNQvez5jXU1X7iuDHPS3BHGMPHV4G/iv.2/m', 'student', 'active', '2026-11-21 11:47:30', 0, NULL, 0, 'Standard', NULL, 0),
(46, 'Đỗ Phương Anh', 'anh.do@gmail.com', '0988776655', NULL, '$2y$10$5G870H/L0syCspwd8XNQvez5jXU1X7iuDHPS3BHGMPHV4G/iv.2/m', 'student', 'active', '2026-11-21 11:47:30', 0, NULL, 0, 'Standard', NULL, 0),
(47, 'Trịnh Quốc Bảo', 'bao.trinh@gmail.com', '0988776655', NULL, '$2y$10$5G870H/L0syCspwd8XNQvez5jXU1X7iuDHPS3BHGMPHV4G/iv.2/m', 'student', 'active', '2026-11-21 11:47:30', 0, NULL, 0, 'Standard', NULL, 0),
(48, 'Ngô Thị Cẩm Tú', 'tu.ngo@gmail.com', '0988776655', NULL, '$2y$10$5G870H/L0syCspwd8XNQvez5jXU1X7iuDHPS3BHGMPHV4G/iv.2/m', 'student', 'active', '2026-11-21 11:47:30', 0, NULL, 0, 'Standard', NULL, 0);

-- -------------------------------------------------------------
-- Bảng: `categories`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `categories`
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `sort_order`, `is_active`, `status`, `created_at`) VALUES
(79, 'Lập trình & Phát triển Phần mềm', 'lap-trinh-phan-mem', NULL, 0, 1, 'active', '2026-11-21 11:40:52'),
(80, 'Khoa học Dữ liệu & Trí tuệ Nhân tạo', 'khoa-hoc-du-lieu-ai', NULL, 0, 1, 'active', '2026-11-21 11:40:52'),
(81, 'Thiết kế Đồ họa & UI/UX', 'thiet-ke-do-hoa-ui-ux', NULL, 0, 1, 'active', '2026-11-21 11:40:52'),
(82, 'Quản trị Hệ thống, DevOps & Cloud', 'he-thong-devops-cloud', NULL, 0, 1, 'active', '2026-11-21 11:40:52'),
(83, 'Kinh doanh & Marketing Kỹ thuật số', 'kinh-doanh-digital-marketing', NULL, 0, 1, 'active', '2026-11-21 11:40:52'),
(84, 'Ngoại ngữ & Kỹ năng Mềm', 'ngoai-ngu-ky-nang-mem', NULL, 0, 1, 'active', '2026-11-21 11:40:52'),
(87, 'Lập trình Web & Frontend', 'lap-trinh-web', 'HTML, CSS, JS, PHP, React, Next.js', 0, 1, 'active', '2026-11-21 11:42:31'),
(88, 'Khoa học Dữ liệu & AI', 'data-ai', 'Python, Machine Learning, Deep Learning, SQL', 0, 1, 'active', '2026-11-21 11:42:31'),
(89, 'Thiết kế UI/UX', 'ui-ux-design', 'Figma, Design System, User Research', 0, 1, 'active', '2026-11-21 11:42:31'),
(90, 'Hệ thống & DevOps Cloud', 'devops-system', 'Docker, Kubernetes, Linux, CI/CD', 0, 1, 'active', '2026-11-21 11:42:31');

-- -------------------------------------------------------------
-- Bảng: `courses`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` int unsigned NOT NULL,
  `category_id` int unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `sale_price` decimal(12,2) DEFAULT NULL,
  `level` enum('all','beginner','intermediate','advanced') COLLATE utf8mb4_unicode_ci DEFAULT 'all',
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `total_duration_minutes` int unsigned DEFAULT '0',
  `total_lessons` int unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `instructor_id` (`instructor_id`),
  KEY `category_id` (`category_id`),
  KEY `idx_course_status` (`status`),
  KEY `idx_course_price` (`price`,`sale_price`),
  CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `courses_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `courses`
INSERT INTO `courses` (`id`, `instructor_id`, `category_id`, `title`, `slug`, `summary`, `description`, `thumbnail`, `price`, `sale_price`, `level`, `status`, `total_duration_minutes`, `total_lessons`, `created_at`, `updated_at`) VALUES
(1, 25, 87, 'Khóa học Lập trình Web Fullstack từ Zero đến Hero', 'khoa-hoc-lap-trinh-web-fullstack-tu-zero-den-hero', 'Lộ trình toàn diện làm chủ PHP, MySQL, Frontend hiện đại và tư duy kiến trúc dự án thực tế.', '<h3>Chào mừng bạn đến với khóa học Lập trình Web Fullstack!</h3><p>Khóa học này sẽ đưa bạn từ người chưa biết gì tới khi có thể tự tay xây dựng một website hoàn chỉnh với đầy đủ tính năng xác thực, thanh toán, thi trắc nghiệm và cấp chứng chỉ.</p><h4>Bạn sẽ học được gì:</h4><ul><li>Cú pháp và tư duy lập trình web hiện đại</li><li>Mô hình MVC (Model-View-Controller) trong thực tế</li><li>Thiết kế Cơ sở dữ liệu chuẩn hóa 3NF với MySQL</li><li>Tích hợp các cổng thanh toán phổ biến: VNPay, MoMo, VietQR</li></ul>', '/image/courses/web_fullstack.jpg', '599000.00', '299000.00', 'beginner', 'published', 57, 6, '2026-11-21 11:19:46', '2026-11-21 14:07:38'),
(2, 26, 88, 'Chinh Phục Python & Trí Tuệ Nhân Tạo (AI / Machine Learning)', 'chinh-phuc-python-va-tri-tue-nhan-tao', 'Học cú pháp Python từ căn bản, làm việc với thư viện Numpy/Pandas và huấn luyện mô hình Machine Learning thực tế.', '<h3>Trở thành kỹ sư AI với lộ trình bài bản</h3><p>Khóa học trang bị cho bạn tư duy toán học ứng dụng, kỹ năng xử lý dữ liệu lớn và thuật toán Machine Learning / Deep Learning từ căn bản đến nâng cao.</p><h4>Nội dung trọng tâm:</h4><ul><li>Lập trình Python chuyên sâu: Generators, Decorators, OOP</li><li>Xử lý và làm sạch dữ liệu lớn với Pandas & NumPy</li><li>Huấn luyện mô hình Hồi quy (Regression) và Phân loại (Classification)</li><li>Đánh giá độ chính xác Precision, Recall, F1-Score</li></ul>', '/image/courses/python_ai.jpg', '899000.00', '499000.00', 'intermediate', 'published', 28, 4, '2026-11-21 11:33:12', '2026-11-21 14:08:32'),
(3, 25, 87, 'Lập trình Frontend Hiện đại với ReactJS & Next.js', 'lap-trinh-frontend-hien-dai-reactjs', 'Xây dựng Single Page Application (SPA), làm chủ React Hooks, Redux Toolkit và Server Components.', '<h3>Nâng cấp kỹ năng Frontend lên tầm Senior</h3><p>Khóa học đi sâu vào cơ chế Virtual DOM, cách tối ưu Render với React.memo, useMemo, useCallback và xây dựng ứng dụng chuẩn Production với Next.js.</p>', '/image/courses/reactjs.jpg', '650000.00', '320000.00', 'intermediate', 'published', 24, 4, '2026-11-21 11:33:12', '2026-11-21 14:08:32'),
(4, 27, 89, 'Thiết kế Giao diện UI/UX Chuyên nghiệp với Figma', 'thiet-ke-giao-dien-uiux-chuyen-nghiep-figma', 'Học tư duy thiết kế lấy người dùng làm trung tâm (User-Centered Design), tạo Wireframe và Design System trên Figma.', '<h3>Khóa học thiết kế thực chiến từ Figma cơ bản đến nâng cao</h3><p>Dành cho các bạn muốn bước chân vào ngành Product Design, chuyển đổi từ Designer hoặc Developer muốn nâng cao tính thẩm mỹ của sản phẩm.</p>', '/image/courses/uiux_figma.jpg', '450000.00', '0.00', 'beginner', 'published', 24, 4, '2026-11-21 11:33:12', '2026-11-21 14:08:32'),
(5, 28, 90, 'DevOps Toàn Diện: Docker, Kubernetes & CI/CD Pipeline', 'devops-toan-dien-docker-kubernetes-cicd', 'Đóng gói ứng dụng với Docker Container, điều phối cụm Kubernetes và tự động hóa triển khai CI/CD trên GitHub Actions.', '<h3>Chinh phục kỹ năng hạ tầng Cloud & Tự động hóa</h3><p>Khóa học thực chiến dành cho lập trình viên muốn tự chủ triển khai và mở rộng quy mô ứng dụng mà không phụ thuộc vào đội ngũ Sysadmin.</p>', '/image/courses/devops_docker.jpg', '1200000.00', '799000.00', 'advanced', 'published', 27, 4, '2026-11-21 11:33:12', '2026-11-21 14:08:32'),
(6, 26, 88, 'Phân tích Dữ liệu Kinh doanh (Data Analytics & SQL Mastery)', 'phan-tich-du-lieu-kinh-doanh-sql-mastery', 'Truy vấn dữ liệu từ cơ bản tới nâng cao với SQL, tối ưu hóa Index triệu bản ghi và trực quan hóa Dashboard kinh doanh.', '<h3>Kỹ năng phân tích số liệu không thể thiếu trong kỷ nguyên số</h3><p>Khóa học giúp bạn đọc hiểu hành vi người dùng, tìm kiếm insight từ bảng số liệu thô và hỗ trợ ra quyết định kinh doanh chuẩn xác.</p>', '/image/courses/data_analytics.jpg', '0.00', '0.00', 'all', 'published', 23, 4, '2026-11-21 11:33:12', '2026-11-21 14:08:32'),
(7, 40, 79, 'Chuyên Sâu Golang & Kiến Trúc Microservices Hiệu Năng Cao', 'chuyen-sau-golang-kien-truc-microservices-hieu-nang-cao', 'Nắm vững ngôn ngữ Go từ cơ bản đến nâng cao, thiết kế API gRPC, Docker hóa dịch vụ và tối ưu Goroutines.', '<p>Khóa học dẫn dắt bạn qua hành trình làm chủ ngôn ngữ lập trình Golang - sự lựa chọn hàng đầu cho các hệ thống backend chịu tải cao hiện nay.</p><h5>Bạn sẽ học được gì:</h5><ul><li>Cú pháp, Pointer, Interface và Concurrency trong Go (Channel, Goroutine).</li><li>Xây dựng RESTful API chuẩn và truyền thông tốc độ cao với gRPC / Protocol Buffers.</li><li>Quản trị kết nối CSDL PostgreSQL với GORM và caching Redis.</li><li>Triển khai kiến trúc Microservices và Event-Driven với Apache Kafka.</li></ul>', '/image/courses/golang_microservices.jpg', '890000.00', '450000.00', 'intermediate', 'published', 780, 8, '2026-11-21 11:47:30', '2026-11-21 11:47:30'),
(8, 40, 79, 'Lập Trình Di Động Đa Nền Tảng với Flutter & Dart 2026', 'lap-trinh-di-dong-da-nen-tang-voi-flutter-dart-2026', 'Phát triển ứng dụng Android và iOS mượt mà với 1 mã nguồn duy nhất, quản lý State với Bloc / Riverpod.', '<p>Flutter là framework cross-platform số 1 của Google giúp bạn tiết kiệm thời gian phát triển và tối ưu hiệu suất native 60fps.</p><h5>Nội dung nổi bật:</h5><ul><li>Ngôn ngữ Dart 3 hiện đại, lập trình hướng đối tượng.</li><li>Widget Tree: Stateless, Stateful và Custom Canvas.</li><li>Quản lý trạng thái chuyên nghiệp với BLoC & Riverpod.</li><li>Tích hợp Firebase Auth, Firestore, Push Notifications và thanh toán in-app.</li></ul>', '/image/courses/flutter_mobile.jpg', '750000.00', '390000.00', 'beginner', 'published', 620, 6, '2026-11-21 11:47:30', '2026-11-21 11:47:30'),
(9, 41, 82, 'Chinh Phục Điện Toán Đám Mây AWS Solutions Architect', 'chinh-phuc-dien-toan-dam-may-aws-solutions-architect', 'Luyện thi chứng chỉ quốc tế AWS SAA-C03 và thực hành thiết kế kiến trúc High Availability, Auto Scaling, Serverless.', '<p>Khóa học chuẩn hóa kiến thức điện toán đám mây Amazon Web Services theo lộ trình thi chứng chỉ quốc tế Solutions Architect Associate.</p><h5>Lợi ích khóa học:</h5><ul><li>Hiểu sâu các dịch vụ cốt lõi: EC2, VPC, S3, RDS, DynamoDB, IAM, Lambda.</li><li>Thiết kế hệ thống có tính sẵn sàng cao (Multi-AZ, Load Balancer, Route 53).</li><li>Tối ưu chi phí và bảo mật hạ tầng mạng đám mây.</li><li>Luyện bộ đề thi thử 400 câu hỏi sát với đề thi thực tế có đáp án chi tiết.</li></ul>', '/image/courses/aws_cloud_architect.jpg', '1290000.00', '690000.00', 'advanced', 'published', 950, 8, '2026-11-21 11:47:30', '2026-11-21 11:47:30'),
(10, 41, 82, 'An Toàn Thông Tin & Thực Hành Ethical Hacking (Pentest Web)', 'an-toan-thong-tin-thuc-hanh-ethical-hacking-pentest-web', 'Tìm hiểu các lỗ hổng OWASP Top 10 (SQLi, XSS, CSRF, RCE), kỹ thuật tấn công & phòng thủ bảo vệ ứng dụng doanh nghiệp.', '<p>Khóa học trang bị tư duy của một chuyên gia đánh giá an ninh mạng (Penetration Tester) để bảo vệ hệ thống trước các nguy cơ tấn công mạng.</p>', '/image/courses/cybersecurity_ethical_hacking.jpg', '950000.00', '490000.00', 'intermediate', 'published', 540, 5, '2026-11-21 11:47:30', '2026-11-21 11:47:30'),
(11, 42, 83, 'Digital Marketing Toàn Diện & Tăng Trưởng Doanh Thu với AI', 'digital-marketing-toan-dien-tang-truong-doanh-thu-voi-ai', 'Chiến lược chạy Ads Facebook, Google Ads, SEO Onpage/Offpage và tự động hóa sáng tạo nội dung tiếp thị bằng AI.', '<p>Khóa học thực chiến giúp bạn tự xây dựng phễu bán hàng, thiết lập chiến dịch quảng cáo ra đơn và đo lường ROI chuyển đổi chuẩn xác.</p>', '/image/courses/digital_marketing_growth.jpg', '550000.00', '0.00', 'all', 'published', 420, 5, '2026-11-21 11:47:30', '2026-11-21 11:47:30'),
(12, 42, 84, 'Tiếng Anh Giao Tiếp & Phỏng Vấn Dành Cho Dân IT (Tech English)', 'tieng-anh-giao-tiep-phong-van-danh-cho-dan-it-tech-english', 'Nâng cao khả năng đọc tài liệu chuyên ngành, viết email chuyên nghiệp và tự tin đàm phán lương trong phỏng vấn công ty Global.', '<p>Trang bị trọn gói bộ kỹ năng giao tiếp tiếng Anh chuẩn môi trường làm việc công nghệ quốc tế (Agile/Scrum, Standup meeting, Code Review).</p>', '/image/courses/ielts_tech.jpg', '0.00', '0.00', 'beginner', 'published', 360, 4, '2026-11-21 11:47:30', '2026-11-21 11:47:30');

-- -------------------------------------------------------------
-- Bảng: `sections`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `sections`;
CREATE TABLE `sections` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_section_sort` (`course_id`,`sort_order`),
  CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `sections`
INSERT INTO `sections` (`id`, `course_id`, `title`, `sort_order`, `created_at`) VALUES
(1, 1, 'Chương 1: Tổng quan và Thiết lập môi trường', 1, '2026-11-21 11:19:46'),
(2, 1, 'Chương 2: Kiến trúc Nâng cao & Lập trình Hướng đối tượng', 2, '2026-11-21 11:33:11'),
(3, 2, 'Chương 1: Python Căn Bản & Cấu Trúc Dữ Liệu', 1, '2026-11-21 11:33:12'),
(4, 3, 'Chương 1: Kiến trúc React Core & Tư duy Component', 1, '2026-11-21 11:33:12'),
(5, 4, 'Chương 1: Công cụ Figma & Nguyên lý Visual Design', 1, '2026-11-21 11:33:12'),
(6, 5, 'Chương 1: Đóng gói Ứng dụng với Docker', 1, '2026-11-21 11:33:12'),
(7, 6, 'Chương 1: SQL Truy Vấn & Tối Ưu Hóa Dữ Liệu', 1, '2026-11-21 11:33:12'),
(8, 7, 'Chương 1: Nền tảng kiến thức & Thiết lập môi trường', 1, '2026-11-21 11:47:30'),
(9, 7, 'Chương 2: Thực chiến chuyên sâu & Kiểm tra đánh giá', 2, '2026-11-21 11:47:30'),
(10, 8, 'Chương 1: Nền tảng kiến thức & Thiết lập môi trường', 1, '2026-11-21 11:47:30'),
(11, 8, 'Chương 2: Thực chiến chuyên sâu & Kiểm tra đánh giá', 2, '2026-11-21 11:47:30'),
(12, 9, 'Chương 1: Nền tảng kiến thức & Thiết lập môi trường', 1, '2026-11-21 11:47:30'),
(13, 9, 'Chương 2: Thực chiến chuyên sâu & Kiểm tra đánh giá', 2, '2026-11-21 11:47:30'),
(14, 10, 'Chương 1: Nền tảng kiến thức & Thiết lập môi trường', 1, '2026-11-21 11:47:30'),
(15, 10, 'Chương 2: Thực chiến chuyên sâu & Kiểm tra đánh giá', 2, '2026-11-21 11:47:30'),
(16, 11, 'Chương 1: Nền tảng kiến thức & Thiết lập môi trường', 1, '2026-11-21 11:47:30'),
(17, 11, 'Chương 2: Thực chiến chuyên sâu & Kiểm tra đánh giá', 2, '2026-11-21 11:47:30'),
(18, 12, 'Chương 1: Nền tảng kiến thức & Thiết lập môi trường', 1, '2026-11-21 11:47:30'),
(19, 12, 'Chương 2: Thực chiến chuyên sâu & Kiểm tra đánh giá', 2, '2026-11-21 11:47:30');

-- -------------------------------------------------------------
-- Bảng: `lessons`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `lessons`;
CREATE TABLE `lessons` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `section_id` int unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lesson_type` enum('video','document','quiz') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'video',
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `is_preview` tinyint(1) DEFAULT '0',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lesson_sort` (`section_id`,`sort_order`),
  CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `lessons`
INSERT INTO `lessons` (`id`, `section_id`, `title`, `slug`, `lesson_type`, `content`, `is_preview`, `sort_order`, `created_at`) VALUES
(1, 1, 'Bài 1: Giới thiệu khóa học & Phương pháp học hiệu quả', 'bai-1-gioi-thieu-khoa-hoc', 'video', NULL, 1, 1, '2026-11-21 11:19:46'),
(2, 1, 'Bài 2: Hiểu rõ mô hình MVC trong thực tế', 'bai-2-hieu-ro-mo-hinh-mvc', 'video', NULL, 1, 2, '2026-11-21 11:19:46'),
(3, 2, 'Bài 1: Tổng quan lộ trình Fullstack & Thiết lập môi trường', 'b-i-1-t-ng-quan-l-tr-nh-fullstack-thi-t-l-p-m-i-tr-ng', 'video', NULL, 1, 1, '2026-11-21 11:33:11'),
(4, 2, 'Bài 2: Làm chủ kiến trúc Backend MVC & Hướng đối tượng OOP', 'b-i-2-l-m-ch-ki-n-tr-c-backend-mvc-h-ng-i-t-ng-oop', 'video', NULL, 0, 2, '2026-11-21 11:33:11'),
(5, 2, 'Bài 3: Bài kiểm tra đánh giá kiến thức Chương 1', 'b-i-3-b-i-ki-m-tra-nh-gi-ki-n-th-c-ch-ng-1', 'quiz', NULL, 0, 3, '2026-11-21 11:33:12'),
(6, 3, 'Bài 1: Cú pháp Python, List, Dict và Hàm xử lý', 'b-i-1-c-ph-p-python-list-dict-v-h-m-x-l-', 'video', NULL, 1, 1, '2026-11-21 11:33:12'),
(7, 3, 'Bài 2: Tổng quan về Machine Learning & Học có giám sát', 'b-i-2-t-ng-quan-v-machine-learning-h-c-c-gi-m-s-t', 'video', NULL, 0, 3, '2026-11-21 11:33:12'),
(8, 3, 'Bài 3: Bài kiểm tra Kiến thức Python & Thuật toán Học máy', 'b-i-3-b-i-ki-m-tra-ki-n-th-c-python-thu-t-to-n-h-c-m-y', 'quiz', NULL, 0, 4, '2026-11-21 11:33:12'),
(9, 4, 'Bài 1: JSX, Props, State và Vòng đời Component', 'b-i-1-jsx-props-state-v-v-ng-i-component', 'video', NULL, 1, 1, '2026-11-21 11:33:12'),
(10, 4, 'Bài 2: Làm chủ React Hooks: useState, useEffect, useRef', 'b-i-2-l-m-ch-react-hooks-usestate-useeffect-useref', 'video', NULL, 0, 3, '2026-11-21 11:33:12'),
(11, 4, 'Bài 3: Bài thi trắc nghiệm ReactJS & Virtual DOM', 'b-i-3-b-i-thi-tr-c-nghi-m-reactjs-virtual-dom', 'quiz', NULL, 0, 4, '2026-11-21 11:33:12'),
(12, 5, 'Bài 1: Làm quen giao diện Figma, Frame, Auto Layout và Variants', 'b-i-1-l-m-quen-giao-di-n-figma-frame-auto-layout-v-variants', 'video', NULL, 1, 1, '2026-11-21 11:33:12'),
(13, 5, 'Bài 2: Tư duy UX, Quy trình Phỏng vấn Người dùng & Wireframing', 'b-i-2-t-duy-ux-quy-tr-nh-ph-ng-v-n-ng-i-d-ng-wireframing', 'video', NULL, 0, 3, '2026-11-21 11:33:12'),
(14, 5, 'Bài 3: Trắc nghiệm Kiến thức UI/UX & Nguyên lý Thiết kế', 'b-i-3-tr-c-nghi-m-ki-n-th-c-ui-ux-nguy-n-l-thi-t-k-', 'quiz', NULL, 0, 4, '2026-11-21 11:33:12'),
(15, 6, 'Bài 1: Docker Architecture, Images, Containers & Viết Dockerfile', 'b-i-1-docker-architecture-images-containers-vi-t-dockerfile', 'video', NULL, 1, 1, '2026-11-21 11:33:12'),
(16, 6, 'Bài 2: GitOps & Tự động hóa Pipeline CI/CD với GitHub Actions', 'b-i-2-gitops-t-ng-h-a-pipeline-ci-cd-v-i-github-actions', 'video', NULL, 0, 3, '2026-11-21 11:33:12'),
(17, 6, 'Bài 3: Trắc nghiệm Kiến thức Docker & Containerization', 'b-i-3-tr-c-nghi-m-ki-n-th-c-docker-containerization', 'quiz', NULL, 0, 4, '2026-11-21 11:33:12'),
(18, 7, 'Bài 1: Cú pháp SELECT, GROUP BY, HAVING & Kỹ thuật JOIN nhiều bảng', 'b-i-1-c-ph-p-select-group-by-having-k-thu-t-join-nhi-u-b-ng', 'video', NULL, 1, 1, '2026-11-21 11:33:12'),
(19, 7, 'Bài 2: Đánh chỉ mục Index (B-Tree) & Tối ưu hiệu năng câu truy vấn', 'b-i-2-nh-ch-m-c-index-b-tree-t-i-u-hi-u-n-ng-c-u-truy-v-n', 'video', NULL, 0, 3, '2026-11-21 11:33:12'),
(20, 7, 'Bài 3: Trắc nghiệm Kiến thức SQL & Phân tích Dữ liệu', 'b-i-3-tr-c-nghi-m-ki-n-th-c-sql-ph-n-t-ch-d-li-u', 'quiz', NULL, 0, 4, '2026-11-21 11:33:12'),
(21, 8, '01. Giới thiệu tổng quan & Lộ trình học tập', 'chuyen-sau-golang-kien-truc-microservices-hieu-nang-cao-bai-1', 'video', 'Chào mừng các bạn đến với khóa học!', 1, 1, '2026-11-21 11:47:30'),
(22, 8, '02. Cài đặt công cụ và Hướng dẫn thực hành', 'chuyen-sau-golang-kien-truc-microservices-hieu-nang-cao-bai-2', 'document', 'Tài liệu hướng dẫn chi tiết từng bước cài đặt.', 0, 2, '2026-11-21 11:47:30'),
(23, 9, '03. Kỹ thuật nâng cao và Best Practices', 'chuyen-sau-golang-kien-truc-microservices-hieu-nang-cao-bai-3', 'video', 'Video phân tích chi tiết các kỹ thuật thực chiến trong dự án thực tế.', 0, 1, '2026-11-21 11:47:30'),
(24, 9, '04. Bài kiểm tra trắc nghiệm đánh giá năng lực', 'chuyen-sau-golang-kien-truc-microservices-hieu-nang-cao-bai-4', 'quiz', 'Hoàn thành bài kiểm tra để đủ điều kiện nhận chứng chỉ.', 0, 2, '2026-11-21 11:47:30'),
(25, 10, '01. Giới thiệu tổng quan & Lộ trình học tập', 'lap-trinh-di-dong-da-nen-tang-voi-flutter-dart-2026-bai-1', 'video', 'Chào mừng các bạn đến với khóa học!', 1, 1, '2026-11-21 11:47:30'),
(26, 10, '02. Cài đặt công cụ và Hướng dẫn thực hành', 'lap-trinh-di-dong-da-nen-tang-voi-flutter-dart-2026-bai-2', 'document', 'Tài liệu hướng dẫn chi tiết từng bước cài đặt.', 0, 2, '2026-11-21 11:47:30'),
(27, 11, '03. Kỹ thuật nâng cao và Best Practices', 'lap-trinh-di-dong-da-nen-tang-voi-flutter-dart-2026-bai-3', 'video', 'Video phân tích chi tiết các kỹ thuật thực chiến trong dự án thực tế.', 0, 1, '2026-11-21 11:47:30'),
(28, 11, '04. Bài kiểm tra trắc nghiệm đánh giá năng lực', 'lap-trinh-di-dong-da-nen-tang-voi-flutter-dart-2026-bai-4', 'quiz', 'Hoàn thành bài kiểm tra để đủ điều kiện nhận chứng chỉ.', 0, 2, '2026-11-21 11:47:30'),
(29, 12, '01. Giới thiệu tổng quan & Lộ trình học tập', 'chinh-phuc-dien-toan-dam-may-aws-solutions-architect-bai-1', 'video', 'Chào mừng các bạn đến với khóa học!', 1, 1, '2026-11-21 11:47:30'),
(30, 12, '02. Cài đặt công cụ và Hướng dẫn thực hành', 'chinh-phuc-dien-toan-dam-may-aws-solutions-architect-bai-2', 'document', 'Tài liệu hướng dẫn chi tiết từng bước cài đặt.', 0, 2, '2026-11-21 11:47:30'),
(31, 13, '03. Kỹ thuật nâng cao và Best Practices', 'chinh-phuc-dien-toan-dam-may-aws-solutions-architect-bai-3', 'video', 'Video phân tích chi tiết các kỹ thuật thực chiến trong dự án thực tế.', 0, 1, '2026-11-21 11:47:30'),
(32, 13, '04. Bài kiểm tra trắc nghiệm đánh giá năng lực', 'chinh-phuc-dien-toan-dam-may-aws-solutions-architect-bai-4', 'quiz', 'Hoàn thành bài kiểm tra để đủ điều kiện nhận chứng chỉ.', 0, 2, '2026-11-21 11:47:30'),
(33, 14, '01. Giới thiệu tổng quan & Lộ trình học tập', 'an-toan-thong-tin-thuc-hanh-ethical-hacking-pentest-web-bai-1', 'video', 'Chào mừng các bạn đến với khóa học!', 1, 1, '2026-11-21 11:47:30'),
(34, 14, '02. Cài đặt công cụ và Hướng dẫn thực hành', 'an-toan-thong-tin-thuc-hanh-ethical-hacking-pentest-web-bai-2', 'document', 'Tài liệu hướng dẫn chi tiết từng bước cài đặt.', 0, 2, '2026-11-21 11:47:30'),
(35, 15, '03. Kỹ thuật nâng cao và Best Practices', 'an-toan-thong-tin-thuc-hanh-ethical-hacking-pentest-web-bai-3', 'video', 'Video phân tích chi tiết các kỹ thuật thực chiến trong dự án thực tế.', 0, 1, '2026-11-21 11:47:30'),
(36, 15, '04. Bài kiểm tra trắc nghiệm đánh giá năng lực', 'an-toan-thong-tin-thuc-hanh-ethical-hacking-pentest-web-bai-4', 'quiz', 'Hoàn thành bài kiểm tra để đủ điều kiện nhận chứng chỉ.', 0, 2, '2026-11-21 11:47:30'),
(37, 16, '01. Giới thiệu tổng quan & Lộ trình học tập', 'digital-marketing-toan-dien-tang-truong-doanh-thu-voi-ai-bai-1', 'video', 'Chào mừng các bạn đến với khóa học!', 1, 1, '2026-11-21 11:47:30'),
(38, 16, '02. Cài đặt công cụ và Hướng dẫn thực hành', 'digital-marketing-toan-dien-tang-truong-doanh-thu-voi-ai-bai-2', 'document', 'Tài liệu hướng dẫn chi tiết từng bước cài đặt.', 0, 2, '2026-11-21 11:47:30'),
(39, 17, '03. Kỹ thuật nâng cao và Best Practices', 'digital-marketing-toan-dien-tang-truong-doanh-thu-voi-ai-bai-3', 'video', 'Video phân tích chi tiết các kỹ thuật thực chiến trong dự án thực tế.', 0, 1, '2026-11-21 11:47:30'),
(40, 17, '04. Bài kiểm tra trắc nghiệm đánh giá năng lực', 'digital-marketing-toan-dien-tang-truong-doanh-thu-voi-ai-bai-4', 'quiz', 'Hoàn thành bài kiểm tra để đủ điều kiện nhận chứng chỉ.', 0, 2, '2026-11-21 11:47:30'),
(41, 18, '01. Giới thiệu tổng quan & Lộ trình học tập', 'tieng-anh-giao-tiep-phong-van-danh-cho-dan-it-tech-english-bai-1', 'video', 'Chào mừng các bạn đến với khóa học!', 1, 1, '2026-11-21 11:47:30'),
(42, 18, '02. Cài đặt công cụ và Hướng dẫn thực hành', 'tieng-anh-giao-tiep-phong-van-danh-cho-dan-it-tech-english-bai-2', 'document', 'Tài liệu hướng dẫn chi tiết từng bước cài đặt.', 0, 2, '2026-11-21 11:47:30'),
(43, 19, '03. Kỹ thuật nâng cao và Best Practices', 'tieng-anh-giao-tiep-phong-van-danh-cho-dan-it-tech-english-bai-3', 'video', 'Video phân tích chi tiết các kỹ thuật thực chiến trong dự án thực tế.', 0, 1, '2026-11-21 11:47:30'),
(44, 19, '04. Bài kiểm tra trắc nghiệm đánh giá năng lực', 'tieng-anh-giao-tiep-phong-van-danh-cho-dan-it-tech-english-bai-4', 'quiz', 'Hoàn thành bài kiểm tra để đủ điều kiện nhận chứng chỉ.', 0, 2, '2026-11-21 11:47:30'),
(45, 1, 'Kiểm tra 15 phút: Kiến thức Nền tảng Web & Cấu trúc MVC', NULL, 'quiz', NULL, 0, 3, '2026-11-21 14:07:38'),
(46, 3, 'Kiểm tra 15 phút: Cú pháp Python & Cấu trúc dữ liệu', NULL, 'quiz', NULL, 0, 2, '2026-11-21 14:08:32'),
(47, 4, 'Kiểm tra 15 phút: JSX, Props và Component React', NULL, 'quiz', NULL, 0, 2, '2026-11-21 14:08:32'),
(48, 5, 'Kiểm tra 15 phút: Auto Layout & Hệ thống Variants Figma', NULL, 'quiz', NULL, 0, 2, '2026-11-21 14:08:32'),
(49, 6, 'Kiểm tra 15 phút: Khái niệm Docker, Image và Container', NULL, 'quiz', NULL, 0, 2, '2026-11-21 14:08:32'),
(50, 7, 'Kiểm tra 15 phút: Kỹ thuật Truy vấn SQL & JOIN Bảng', NULL, 'quiz', NULL, 0, 2, '2026-11-21 14:08:32');

-- -------------------------------------------------------------
-- Bảng: `lesson_videos`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `lesson_videos`;
CREATE TABLE `lesson_videos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` int unsigned NOT NULL,
  `provider` enum('local','youtube','vimeo','s3') COLLATE utf8mb4_unicode_ci DEFAULT 'local',
  `video_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_seconds` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lesson_id` (`lesson_id`),
  CONSTRAINT `lesson_videos_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `lesson_videos`
INSERT INTO `lesson_videos` (`id`, `lesson_id`, `provider`, `video_url`, `duration_seconds`, `created_at`) VALUES
(1, 1, 'youtube', 'https://www.youtube.com/embed/zJSY8tbf_ys', 900, '2026-11-21 11:19:46'),
(2, 3, 'youtube', 'https://www.youtube.com/embed/zJSY8tbf_ys', 600, '2026-11-21 11:33:11'),
(3, 4, 'youtube', 'https://www.youtube.com/embed/2eebptXfEvw', 720, '2026-11-21 11:33:11'),
(4, 6, 'youtube', 'https://www.youtube.com/embed/kqtD5dpn9C8', 750, '2026-11-21 11:33:12'),
(5, 7, 'youtube', 'https://www.youtube.com/embed/i_LwzRVP7bg', 900, '2026-11-21 11:33:12'),
(6, 9, 'youtube', 'https://www.youtube.com/embed/bMknfKXIFA8', 680, '2026-11-21 11:33:12'),
(7, 10, 'youtube', 'https://www.youtube.com/embed/W6NZfCO5SIk', 750, '2026-11-21 11:33:12'),
(8, 12, 'youtube', 'https://www.youtube.com/embed/FTFaQWZBqQ8', 640, '2026-11-21 11:33:12'),
(9, 13, 'youtube', 'https://www.youtube.com/embed/c9Wg6Cb_YlU', 800, '2026-11-21 11:33:12'),
(10, 15, 'youtube', 'https://www.youtube.com/embed/3c-iBn73dDE', 820, '2026-11-21 11:33:12'),
(11, 16, 'youtube', 'https://www.youtube.com/embed/pg19Z8LL0vQ', 780, '2026-11-21 11:33:12'),
(12, 18, 'youtube', 'https://www.youtube.com/embed/ua-CiDNNj30', 650, '2026-11-21 11:33:12'),
(13, 19, 'youtube', 'https://www.youtube.com/embed/2eebptXfEvw', 700, '2026-11-21 11:33:12'),
(14, 21, 'youtube', 'https://www.youtube.com/embed/YS4e4q9oBaU', 900, '2026-11-21 11:47:30'),
(15, 23, 'youtube', 'https://www.youtube.com/embed/YS4e4q9oBaU', 1500, '2026-11-21 11:47:30'),
(16, 25, 'youtube', 'https://www.youtube.com/embed/VPvVD8t02U8', 900, '2026-11-21 11:47:30'),
(17, 27, 'youtube', 'https://www.youtube.com/embed/VPvVD8t02U8', 1500, '2026-11-21 11:47:30'),
(18, 29, 'youtube', 'https://www.youtube.com/embed/Ia-UEYYR44s', 900, '2026-11-21 11:47:30'),
(19, 31, 'youtube', 'https://www.youtube.com/embed/Ia-UEYYR44s', 1500, '2026-11-21 11:47:30'),
(20, 33, 'youtube', 'https://www.youtube.com/embed/3Kq1MIfTWCE', 900, '2026-11-21 11:47:30'),
(21, 35, 'youtube', 'https://www.youtube.com/embed/3Kq1MIfTWCE', 1500, '2026-11-21 11:47:30'),
(22, 37, 'youtube', 'https://www.youtube.com/embed/nU-IIXBWlS4', 900, '2026-11-21 11:47:30'),
(23, 39, 'youtube', 'https://www.youtube.com/embed/nU-IIXBWlS4', 1500, '2026-11-21 11:47:30'),
(24, 41, 'youtube', 'https://www.youtube.com/embed/f02mOEt11OQ', 600, '2026-11-21 11:47:30'),
(25, 43, 'youtube', 'https://www.youtube.com/embed/0fYi8SGA20k', 1500, '2026-11-21 11:47:30'),
(26, 42, 'youtube', 'https://www.youtube.com/embed/8pDqJVdNa44', 900, '2026-11-21 12:05:36'),
(27, 2, 'youtube', 'https://www.youtube.com/embed/2eebptXfEvw', 1200, '2026-11-21 12:05:36'),
(28, 22, 'youtube', 'https://www.youtube.com/embed/YS4e4q9oBaU', 1200, '2026-11-21 12:05:36'),
(29, 26, 'youtube', 'https://www.youtube.com/embed/VPvVD8t02U8', 1200, '2026-11-21 12:05:36'),
(30, 30, 'youtube', 'https://www.youtube.com/embed/Ia-UEYYR44s', 1200, '2026-11-21 12:05:36'),
(31, 34, 'youtube', 'https://www.youtube.com/embed/3Kq1MIfTWCE', 1200, '2026-11-21 12:05:36'),
(32, 38, 'youtube', 'https://www.youtube.com/embed/nU-IIXBWlS4', 1200, '2026-11-21 12:05:36');

-- -------------------------------------------------------------
-- Bảng: `lesson_materials`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `lesson_materials`;
CREATE TABLE `lesson_materials` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` int unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size_bytes` bigint unsigned DEFAULT '0',
  `download_count` int unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lesson_id` (`lesson_id`),
  CONSTRAINT `lesson_materials_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `lesson_materials`
INSERT INTO `lesson_materials` (`id`, `lesson_id`, `title`, `file_path`, `file_type`, `file_size_bytes`, `download_count`, `created_at`) VALUES
(1, 1, 'Giao_trinh_tong_quan_khoa_hoc.pdf', '/storage/materials/sample_doc.pdf', 'pdf', 1048576, 0, '2026-11-21 11:19:46'),
(2, 3, 'Giao_trinh_tong_quan_fullstack.pdf', '/storage/materials/Giao_trinh_tong_quan_fullstack.pdf', 'pdf', 1048576, 0, '2026-11-21 11:33:11'),
(3, 4, 'Source_code_oop_mvc.zip', '/storage/materials/Source_code_oop_mvc.zip', 'zip', 1048576, 0, '2026-11-21 11:33:12'),
(4, 6, 'Python_CheatSheet_LMS.pdf', '/storage/materials/Python_CheatSheet_LMS.pdf', 'pdf', 1048576, 0, '2026-11-21 11:33:12'),
(5, 7, 'Machine_Learning_Datasets.zip', '/storage/materials/Machine_Learning_Datasets.zip', 'zip', 1048576, 0, '2026-11-21 11:33:12'),
(6, 9, 'React_Core_Slides.pdf', '/storage/materials/React_Core_Slides.pdf', 'pdf', 1048576, 0, '2026-11-21 11:33:12'),
(7, 10, 'Hooks_Exercise_Starter.zip', '/storage/materials/Hooks_Exercise_Starter.zip', 'zip', 1048576, 0, '2026-11-21 11:33:12'),
(8, 12, 'Figma_UI_Kit_Template.fig', '/storage/materials/Figma_UI_Kit_Template.fig', 'fig', 1048576, 0, '2026-11-21 11:33:12'),
(9, 13, 'UX_Research_Framework.pdf', '/storage/materials/UX_Research_Framework.pdf', 'pdf', 1048576, 0, '2026-11-21 11:33:12'),
(10, 15, 'Dockerfile_Best_Practices.pdf', '/storage/materials/Dockerfile_Best_Practices.pdf', 'pdf', 1048576, 0, '2026-11-21 11:33:12'),
(11, 16, 'github-actions-workflow.yml', '/storage/materials/github-actions-workflow.yml', 'yml', 1048576, 0, '2026-11-21 11:33:12'),
(12, 18, 'SQL_Practice_Database.sql', '/storage/materials/SQL_Practice_Database.sql', 'sql', 1048576, 0, '2026-11-21 11:33:12'),
(13, 19, 'Query_Optimization_Guide.pdf', '/storage/materials/Query_Optimization_Guide.pdf', 'pdf', 1048576, 0, '2026-11-21 11:33:12'),
(14, 22, 'Giao-trinh-thuc-hanh.pdf', '/storage/docs/sample.pdf', 'pdf', 2450000, 0, '2026-11-21 11:47:30'),
(15, 26, 'Giao-trinh-thuc-hanh.pdf', '/storage/docs/sample.pdf', 'pdf', 2450000, 0, '2026-11-21 11:47:30'),
(16, 30, 'Giao-trinh-thuc-hanh.pdf', '/storage/docs/sample.pdf', 'pdf', 2450000, 0, '2026-11-21 11:47:30'),
(17, 34, 'Giao-trinh-thuc-hanh.pdf', '/storage/docs/sample.pdf', 'pdf', 2450000, 0, '2026-11-21 11:47:30'),
(18, 38, 'Giao-trinh-thuc-hanh.pdf', '/storage/docs/sample.pdf', 'pdf', 2450000, 0, '2026-11-21 11:47:30'),
(19, 42, 'Giao-trinh-thuc-hanh.pdf', '/storage/docs/sample.pdf', 'pdf', 2450000, 0, '2026-11-21 11:47:30');

-- -------------------------------------------------------------
-- Bảng: `lesson_progress`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `lesson_progress`;
CREATE TABLE `lesson_progress` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `lesson_id` int unsigned NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `last_watch_second` int unsigned NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_lesson` (`user_id`,`lesson_id`),
  KEY `lesson_id` (`lesson_id`),
  CONSTRAINT `lesson_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lesson_progress_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `lesson_progress`
INSERT INTO `lesson_progress` (`id`, `user_id`, `lesson_id`, `is_completed`, `last_watch_second`, `completed_at`, `updated_at`) VALUES
(1, 2, 18, 1, 120, '2026-11-21 18:39:21', '2026-11-21 11:39:21'),
(2, 2, 5, 1, 0, '2026-11-21 18:39:21', '2026-11-21 11:39:21'),
(23, 1, 41, 0, 450, NULL, '2026-11-21 11:54:48'),
(29, 1, 1, 0, 76, NULL, '2026-11-21 12:32:45'),
(51, 1, 2, 0, 9, NULL, '2026-11-21 12:32:54'),
(58, 1, 25, 0, 8, NULL, '2026-11-21 13:12:25'),
(63, 1, 15, 0, 5, NULL, '2026-11-21 13:14:38'),
(69, 2, 15, 0, 6, NULL, '2026-11-21 13:28:45'),
(72, 2, 6, 0, 4, NULL, '2026-11-21 14:11:49'),
(73, 2, 21, 0, 29, NULL, '2026-11-21 13:29:39'),
(93, 2, 33, 0, 3, NULL, '2026-11-21 14:01:58'),
(110, 2, 37, 0, 0, NULL, '2026-11-21 14:10:53'),
(112, 2, 46, 0, 0, NULL, '2026-11-21 14:11:42'),
(120, 2, 41, 0, 180, NULL, '2026-11-21 14:15:27');

-- -------------------------------------------------------------
-- Bảng: `quizzes`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE `quizzes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int unsigned NOT NULL,
  `lesson_id` int unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `duration_minutes` int unsigned NOT NULL DEFAULT '15',
  `passing_score` decimal(5,2) NOT NULL DEFAULT '80.00',
  `max_attempts` tinyint unsigned NOT NULL DEFAULT '0',
  `is_random_questions` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lesson_id` (`lesson_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quizzes_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `quizzes`
INSERT INTO `quizzes` (`id`, `course_id`, `lesson_id`, `title`, `description`, `duration_minutes`, `passing_score`, `max_attempts`, `is_random_questions`, `created_at`) VALUES
(1, 1, 5, 'Kiểm tra trắc nghiệm Kiến trúc MVC & Web Core', 'Bài kiểm tra trắc nghiệm đánh giá kiến thức bài học.', 15, '80.00', 3, 0, '2026-11-21 11:33:12'),
(2, 2, 8, 'Đánh giá Năng lực Python & Machine Learning', 'Bài kiểm tra trắc nghiệm đánh giá kiến thức bài học.', 15, '80.00', 3, 0, '2026-11-21 11:33:12'),
(3, 3, 11, 'Đánh giá Kiến thức ReactJS', 'Bài kiểm tra trắc nghiệm đánh giá kiến thức bài học.', 15, '80.00', 3, 0, '2026-11-21 11:33:12'),
(4, 4, 14, 'Kiểm tra Kiến thức UI/UX Design', 'Bài kiểm tra trắc nghiệm đánh giá kiến thức bài học.', 15, '80.00', 3, 0, '2026-11-21 11:33:12'),
(5, 5, 17, 'Đánh giá Kỹ năng Docker & DevOps', 'Bài kiểm tra trắc nghiệm đánh giá kiến thức bài học.', 15, '80.00', 3, 0, '2026-11-21 11:33:12'),
(6, 6, 20, 'Đánh giá Kỹ năng SQL Thực chiến', 'Bài kiểm tra trắc nghiệm đánh giá kiến thức bài học.', 15, '80.00', 3, 0, '2026-11-21 11:33:12'),
(7, 7, 24, 'Kiểm tra tổng hợp: Chuyên Sâu Golang & Kiến Trúc Microservices Hiệu Năng Cao', 'Bài kiểm tra gồm các câu hỏi trắc nghiệm kiến thức cốt lõi.', 15, '80.00', 3, 0, '2026-11-21 11:47:30'),
(8, 8, 28, 'Kiểm tra tổng hợp: Lập Trình Di Động Đa Nền Tảng với Flutter & Dart 2026', 'Bài kiểm tra gồm các câu hỏi trắc nghiệm kiến thức cốt lõi.', 15, '80.00', 3, 0, '2026-11-21 11:47:30'),
(9, 9, 32, 'Kiểm tra tổng hợp: Chinh Phục Điện Toán Đám Mây AWS Solutions Architect', 'Bài kiểm tra gồm các câu hỏi trắc nghiệm kiến thức cốt lõi.', 15, '80.00', 3, 0, '2026-11-21 11:47:30'),
(10, 10, 36, 'Kiểm tra tổng hợp: An Toàn Thông Tin & Thực Hành Ethical Hacking (Pentest Web)', 'Bài kiểm tra gồm các câu hỏi trắc nghiệm kiến thức cốt lõi.', 15, '80.00', 3, 0, '2026-11-21 11:47:30'),
(11, 11, 40, 'Kiểm tra tổng hợp: Digital Marketing Toàn Diện & Tăng Trưởng Doanh Thu với AI', 'Bài kiểm tra gồm các câu hỏi trắc nghiệm kiến thức cốt lõi.', 15, '80.00', 3, 0, '2026-11-21 11:47:30'),
(12, 12, 44, 'Kiểm tra tổng hợp: Tiếng Anh Giao Tiếp & Phỏng Vấn Dành Cho Dân IT (Tech English)', 'Bài kiểm tra gồm các câu hỏi trắc nghiệm kiến thức cốt lõi.', 15, '80.00', 3, 0, '2026-11-21 11:47:30'),
(13, 1, 45, 'Kiểm tra 15 phút: Kiến thức Nền tảng Web & Cấu trúc MVC', 'Bài kiểm tra nhanh củng cố kiến thức sau khi học xong Bài 1 & Bài 2 (35 phút video).', 15, '80.00', 0, 0, '2026-11-21 14:07:38'),
(14, 2, 46, 'Kiểm tra 15 phút: Cú pháp Python & Cấu trúc dữ liệu', 'Củng cố kiến thức sau khi học xong phần cú pháp cơ bản của Python (13 phút).', 15, '80.00', 0, 0, '2026-11-21 14:08:32'),
(15, 3, 47, 'Kiểm tra 15 phút: JSX, Props và Component React', 'Kiểm tra nhanh sau khi hoàn thành bài học về Component và Props trong React.', 15, '80.00', 0, 0, '2026-11-21 14:08:32'),
(16, 4, 48, 'Kiểm tra 15 phút: Auto Layout & Hệ thống Variants Figma', 'Kiểm tra nhanh kỹ năng dựng khung layout linh hoạt trong Figma sau bài học 1.', 15, '80.00', 0, 0, '2026-11-21 14:08:32'),
(17, 5, 49, 'Kiểm tra 15 phút: Khái niệm Docker, Image và Container', 'Kiểm tra thực hành các lệnh cơ bản Docker sau khi hoàn thành Bài 1.', 15, '80.00', 0, 0, '2026-11-21 14:08:32'),
(18, 6, 50, 'Kiểm tra 15 phút: Kỹ thuật Truy vấn SQL & JOIN Bảng', 'Củng cố kiến thức cú pháp SQL sau bài giảng thực chiến đầu tiên.', 15, '80.00', 0, 0, '2026-11-21 14:08:32');

-- -------------------------------------------------------------
-- Bảng: `quiz_questions`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `quiz_questions`;
CREATE TABLE `quiz_questions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `quiz_id` int unsigned NOT NULL,
  `question_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_type` enum('single_choice','multiple_choice','true_false') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'single_choice',
  `points` decimal(5,2) NOT NULL DEFAULT '1.00',
  `explanation` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `quiz_id` (`quiz_id`),
  CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `quiz_questions`
INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question_text`, `question_type`, `points`, `explanation`, `sort_order`, `created_at`) VALUES
(1, 1, 'Trong mô hình MVC, thành phần nào trực tiếp tương tác với Cơ sở Dữ liệu?', 'single_choice', '1.00', 'Model đại diện cho dữ liệu và các quy tắc nghiệp vụ truy vấn DB.', 1, '2026-11-21 11:33:12'),
(2, 1, 'Kỹ thuật nào dùng để chống tấn công SQL Injection hiệu quả nhất?', 'single_choice', '1.00', 'Prepared Statements với tham số buộc chặt (parameter binding) ngăn chặn chèn mã độc.', 2, '2026-11-21 11:33:12'),
(3, 1, 'Trạng thái HTTP 200 biểu thị điều gì?', 'single_choice', '1.00', 'HTTP 200 OK báo hiệu yêu cầu đã được máy chủ xử lý thành công.', 3, '2026-11-21 11:33:12'),
(4, 2, 'Thư viện Python nào chuyên dùng để thao tác và phân tích cấu trúc dữ liệu bảng (DataFrames)?', 'single_choice', '1.00', 'Pandas cung cấp cấu trúc DataFrame mạnh mẽ cho phân tích số liệu.', 1, '2026-11-21 11:33:12'),
(5, 2, 'Thuật toán nào sau đây thuộc nhóm học có giám sát (Supervised Learning)?', 'single_choice', '1.00', 'Linear Regression và Random Forest là các thuật toán học có giám sát.', 2, '2026-11-21 11:33:12'),
(6, 2, 'Hiện tượng mô hình hoạt động cực tốt trên tập Train nhưng kém trên tập Test gọi là gì?', 'single_choice', '1.00', 'Overfitting (Quá khớp) xảy ra khi mô hình học vẹt cả nhiễu của tập huấn luyện.', 3, '2026-11-21 11:33:12'),
(7, 3, 'Hook nào trong React được dùng để thực hiện các side-effect (gọi API, lắng nghe event)?', 'single_choice', '1.00', 'useEffect xử lý các tác vụ ngoài luồng render như fetch data, timer, subscriptions.', 1, '2026-11-21 11:33:12'),
(8, 3, 'Điều gì xảy ra khi bạn gọi hàm cập nhật State trong React?', 'single_choice', '1.00', 'React xếp lịch re-render component và so sánh Virtual DOM để cập nhật DOM thật.', 2, '2026-11-21 11:33:12'),
(9, 4, 'Tính năng Auto Layout trong Figma tương tự với thuộc tính CSS nào?', 'single_choice', '1.00', 'Auto Layout mô phỏng hành vi của CSS Flexbox (hướng sắp xếp, khoảng cách, căn chỉnh).', 1, '2026-11-21 11:33:12'),
(10, 4, 'Nguyên tắc nào sau đây đảm bảo khoảng cách ngón tay bấm (tap target) thuận tiện trên Mobile?', 'single_choice', '1.00', 'Theo chuẩn Apple & Google, kích thước nút bấm tối thiểu khuyến nghị là 44x44 hoặc 48x48 dp/px.', 2, '2026-11-21 11:33:12'),
(11, 5, 'Chỉ thị nào trong Dockerfile được dùng để chỉ định Image nền khởi tạo?', 'single_choice', '1.00', 'FROM xác định base image (vd: FROM php:8.2-fpm hoặc FROM node:20).', 1, '2026-11-21 11:33:12'),
(12, 5, 'Lệnh Docker nào dùng để chạy các container đa dịch vụ được định nghĩa trong file YAML?', 'single_choice', '1.00', 'docker compose up khởi chạy toàn bộ dịch vụ được cấu hình trong docker-compose.yml.', 2, '2026-11-21 11:33:12'),
(13, 6, 'Mệnh đề nào dùng để lọc điều kiện trên kết quả đã được gom nhóm bởi GROUP BY?', 'single_choice', '1.00', 'HAVING dùng để lọc điều kiện sau khi nhóm (vd: HAVING COUNT(*) > 5), trong khi WHERE lọc trước khi nhóm.', 1, '2026-11-21 11:33:12'),
(14, 6, 'Loại JOIN nào trả về tất cả bản ghi từ bảng bên trái bất kể có bản ghi khớp bên phải hay không?', 'single_choice', '1.00', 'LEFT JOIN (hoặc LEFT OUTER JOIN) giữ lại toàn bộ hàng từ bảng bên trái.', 2, '2026-11-21 11:33:12'),
(15, 7, 'Ưu điểm lớn nhất của công nghệ được giảng dạy trong khóa học là gì?', 'single_choice', '5.00', 'Đáp án phản ánh chính xác nguyên lý thiết kế và tối ưu hiệu suất.', 1, '2026-11-21 11:47:30'),
(16, 7, 'Để bảo mật ứng dụng khi triển khai lên môi trường Production, phương pháp nào là bắt buộc?', 'single_choice', '5.00', 'HTTPS và xác thực Token an toàn là yêu cầu tiên quyết.', 2, '2026-11-21 11:47:30'),
(17, 8, 'Ưu điểm lớn nhất của công nghệ được giảng dạy trong khóa học là gì?', 'single_choice', '5.00', 'Đáp án phản ánh chính xác nguyên lý thiết kế và tối ưu hiệu suất.', 1, '2026-11-21 11:47:30'),
(18, 8, 'Để bảo mật ứng dụng khi triển khai lên môi trường Production, phương pháp nào là bắt buộc?', 'single_choice', '5.00', 'HTTPS và xác thực Token an toàn là yêu cầu tiên quyết.', 2, '2026-11-21 11:47:30'),
(19, 9, 'Ưu điểm lớn nhất của công nghệ được giảng dạy trong khóa học là gì?', 'single_choice', '5.00', 'Đáp án phản ánh chính xác nguyên lý thiết kế và tối ưu hiệu suất.', 1, '2026-11-21 11:47:30'),
(20, 9, 'Để bảo mật ứng dụng khi triển khai lên môi trường Production, phương pháp nào là bắt buộc?', 'single_choice', '5.00', 'HTTPS và xác thực Token an toàn là yêu cầu tiên quyết.', 2, '2026-11-21 11:47:30'),
(21, 10, 'Ưu điểm lớn nhất của công nghệ được giảng dạy trong khóa học là gì?', 'single_choice', '5.00', 'Đáp án phản ánh chính xác nguyên lý thiết kế và tối ưu hiệu suất.', 1, '2026-11-21 11:47:30'),
(22, 10, 'Để bảo mật ứng dụng khi triển khai lên môi trường Production, phương pháp nào là bắt buộc?', 'single_choice', '5.00', 'HTTPS và xác thực Token an toàn là yêu cầu tiên quyết.', 2, '2026-11-21 11:47:30'),
(23, 11, 'Ưu điểm lớn nhất của công nghệ được giảng dạy trong khóa học là gì?', 'single_choice', '5.00', 'Đáp án phản ánh chính xác nguyên lý thiết kế và tối ưu hiệu suất.', 1, '2026-11-21 11:47:30'),
(24, 11, 'Để bảo mật ứng dụng khi triển khai lên môi trường Production, phương pháp nào là bắt buộc?', 'single_choice', '5.00', 'HTTPS và xác thực Token an toàn là yêu cầu tiên quyết.', 2, '2026-11-21 11:47:30'),
(25, 12, 'Ưu điểm lớn nhất của công nghệ được giảng dạy trong khóa học là gì?', 'single_choice', '5.00', 'Đáp án phản ánh chính xác nguyên lý thiết kế và tối ưu hiệu suất.', 1, '2026-11-21 11:47:30'),
(26, 12, 'Để bảo mật ứng dụng khi triển khai lên môi trường Production, phương pháp nào là bắt buộc?', 'single_choice', '5.00', 'HTTPS và xác thực Token an toàn là yêu cầu tiên quyết.', 2, '2026-11-21 11:47:30'),
(27, 13, 'Trong mô hình MVC, thành phần nào chịu trách nhiệm trực tiếp tương tác và truy vấn Cơ sở dữ liệu?', 'single_choice', '1.00', 'Model là tầng đảm nhận toàn bộ logic dữ liệu, định nghĩa quan hệ và truy vấn CSDL.', 1, '2026-11-21 14:07:38'),
(28, 13, 'Giao thức HTTP hoạt động theo cơ chế nào sau đây giữa Trình duyệt và Máy chủ?', 'single_choice', '1.00', 'HTTP hoạt động theo mô hình Request - Response (Yêu cầu và Phản hồi).', 2, '2026-11-21 14:07:38'),
(29, 13, 'Mục đích quan trọng nhất của việc phân tách Controller và View trong kiến trúc MVC là gì?', 'single_choice', '1.00', 'Tách biệt luồng xử lý logic nghiệp vụ và giao diện hiển thị, giúp code dễ bảo trì và tái sử dụng.', 3, '2026-11-21 14:07:38'),
(30, 14, 'Kiểu dữ liệu nào trong Python là kiểu bất biến (Immutable)?', 'single_choice', '1.00', 'Tuple trong Python là immutable, không thể thay đổi giá trị sau khi khởi tạo.', 1, '2026-11-21 14:08:32'),
(31, 14, 'Cú pháp List Comprehension nào sau đây tạo ra danh sách số chẵn từ 0 đến 8?', 'single_choice', '1.00', '[x for x in range(10) if x % 2 == 0] sẽ sinh ra [0, 2, 4, 6, 8].', 2, '2026-11-21 14:08:32'),
(32, 15, 'Điểm khác biệt căn bản giữa Props và State trong React là gì?', 'single_choice', '1.00', 'Props được truyền từ component cha xuống và mang tính read-only; State được quản lý nội bộ bên trong component và có thể thay đổi.', 1, '2026-11-21 14:08:32'),
(33, 15, 'Quy tắc bắt buộc khi viết thẻ JSX trong React là gì?', 'single_choice', '1.00', 'JSX phải luôn được bao bọc bởi một thẻ cha duy nhất (hoặc React Fragment).', 2, '2026-11-21 14:08:32'),
(34, 16, 'Phím tắt mặc định để tạo nhanh Auto Layout cho một cụm layer trong Figma là gì?', 'single_choice', '1.00', 'Shift + A là phím tắt tiêu chuẩn để bật Auto Layout trong Figma.', 1, '2026-11-21 14:08:32'),
(35, 16, 'Chế độ \'Fill container\' trong Auto Layout có ý nghĩa gì?', 'single_choice', '1.00', 'Layer con sẽ tự động co giãn để lấp đầy toàn bộ chiều rộng hoặc chiều cao của frame cha.', 2, '2026-11-21 14:08:32'),
(36, 17, 'Mối quan hệ giữa Docker Image và Docker Container là gì?', 'single_choice', '1.00', 'Image là bản thiết kế/khuôn mẫu chỉ đọc (Read-only Template), Container là một thể hiện (Instance) đang chạy của Image.', 1, '2026-11-21 14:08:32'),
(37, 17, 'Lệnh nào sau đây dùng để build một Docker Image từ Dockerfile trong thư mục hiện tại?', 'single_choice', '1.00', 'docker build -t <tên-image> . là lệnh chuẩn để build image.', 2, '2026-11-21 14:08:32'),
(38, 18, 'Mệnh đề nào trong SQL được dùng để lọc dữ liệu trên các hàm tổng hợp (AGGREGATE functions)?', 'single_choice', '1.00', 'Mệnh đề HAVING lọc kết quả sau khi đã GROUP BY và tính hàm tổng hợp; WHERE chỉ lọc dòng dữ liệu thô.', 1, '2026-11-21 14:08:32'),
(39, 18, 'INNER JOIN trả về kết quả như thế nào giữa 2 bảng?', 'single_choice', '1.00', 'INNER JOIN chỉ trả về các bản ghi thỏa mãn điều kiện kết nối ở cả 2 bảng.', 2, '2026-11-21 14:08:32');

-- -------------------------------------------------------------
-- Bảng: `quiz_answers`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `quiz_answers`;
CREATE TABLE `quiz_answers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int unsigned NOT NULL,
  `answer_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `quiz_answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `quiz_answers`
INSERT INTO `quiz_answers` (`id`, `question_id`, `answer_text`, `is_correct`, `created_at`) VALUES
(1, 1, 'Model', 1, '2026-11-21 11:33:12'),
(2, 1, 'View', 0, '2026-11-21 11:33:12'),
(3, 1, 'Controller', 0, '2026-11-21 11:33:12'),
(4, 1, 'Router', 0, '2026-11-21 11:33:12'),
(5, 2, 'Sử dụng Prepared Statements (PDO)', 1, '2026-11-21 11:33:12'),
(6, 2, 'Chỉ dùng hàm md5()', 0, '2026-11-21 11:33:12'),
(7, 2, 'Tắt firewall máy chủ', 0, '2026-11-21 11:33:12'),
(8, 2, 'Nối chuỗi trực tiếp vào câu lệnh SQL', 0, '2026-11-21 11:33:12'),
(9, 3, 'Yêu cầu xử lý thành công (OK)', 1, '2026-11-21 11:33:12'),
(10, 3, 'Không tìm thấy trang (Not Found)', 0, '2026-11-21 11:33:12'),
(11, 3, 'Lỗi máy chủ nội bộ (Internal Server Error)', 0, '2026-11-21 11:33:12'),
(12, 3, 'Yêu cầu bị từ chối (Forbidden)', 0, '2026-11-21 11:33:12'),
(13, 4, 'Pandas', 1, '2026-11-21 11:33:12'),
(14, 4, 'Matplotlib', 0, '2026-11-21 11:33:12'),
(15, 4, 'Flask', 0, '2026-11-21 11:33:12'),
(16, 4, 'Requests', 0, '2026-11-21 11:33:12'),
(17, 5, 'Linear Regression', 1, '2026-11-21 11:33:12'),
(18, 5, 'K-Means Clustering', 0, '2026-11-21 11:33:12'),
(19, 5, 'PCA (Giảm chiều dữ liệu)', 0, '2026-11-21 11:33:12'),
(20, 5, 'Apriori', 0, '2026-11-21 11:33:12'),
(21, 6, 'Overfitting (Quá khớp)', 1, '2026-11-21 11:33:12'),
(22, 6, 'Underfitting (Chưa khớp)', 0, '2026-11-21 11:33:12'),
(23, 6, 'Regularization', 0, '2026-11-21 11:33:12'),
(24, 6, 'Cross-validation', 0, '2026-11-21 11:33:12'),
(25, 7, 'useEffect', 1, '2026-11-21 11:33:12'),
(26, 7, 'useState', 0, '2026-11-21 11:33:12'),
(27, 7, 'useMemo', 0, '2026-11-21 11:33:12'),
(28, 7, 'useReducer', 0, '2026-11-21 11:33:12'),
(29, 8, 'Component được lên lịch để Re-render', 1, '2026-11-21 11:33:12'),
(30, 8, 'Trình duyệt reload lại toàn bộ trang', 0, '2026-11-21 11:33:12'),
(31, 8, 'Xóa toàn bộ bộ nhớ Cache', 0, '2026-11-21 11:33:12'),
(32, 8, 'Không có thay đổi nào xảy ra', 0, '2026-11-21 11:33:12'),
(33, 9, 'CSS Flexbox', 1, '2026-11-21 11:33:12'),
(34, 9, 'CSS Float', 0, '2026-11-21 11:33:12'),
(35, 9, 'CSS Position Absolute', 0, '2026-11-21 11:33:12'),
(36, 9, 'CSS Z-Index', 0, '2026-11-21 11:33:12'),
(37, 10, 'Tối thiểu 44x44 hoặc 48x48 px', 1, '2026-11-21 11:33:12'),
(38, 10, 'Tối đa 10x10 px', 0, '2026-11-21 11:33:12'),
(39, 10, 'Không cần quan tâm kích thước', 0, '2026-11-21 11:33:12'),
(40, 10, 'Càng nhỏ càng tiết kiệm diện tích', 0, '2026-11-21 11:33:12'),
(41, 11, 'FROM', 1, '2026-11-21 11:33:12'),
(42, 11, 'RUN', 0, '2026-11-21 11:33:12'),
(43, 11, 'COPY', 0, '2026-11-21 11:33:12'),
(44, 11, 'EXPOSE', 0, '2026-11-21 11:33:12'),
(45, 12, 'docker compose up', 1, '2026-11-21 11:33:12'),
(46, 12, 'docker build', 0, '2026-11-21 11:33:12'),
(47, 12, 'docker ps', 0, '2026-11-21 11:33:12'),
(48, 12, 'docker commit', 0, '2026-11-21 11:33:12'),
(49, 13, 'HAVING', 1, '2026-11-21 11:33:12'),
(50, 13, 'WHERE', 0, '2026-11-21 11:33:12');
INSERT INTO `quiz_answers` (`id`, `question_id`, `answer_text`, `is_correct`, `created_at`) VALUES
(51, 13, 'ORDER BY', 0, '2026-11-21 11:33:12'),
(52, 13, 'LIMIT', 0, '2026-11-21 11:33:12'),
(53, 14, 'LEFT JOIN', 1, '2026-11-21 11:33:12'),
(54, 14, 'INNER JOIN', 0, '2026-11-21 11:33:12'),
(55, 14, 'CROSS JOIN', 0, '2026-11-21 11:33:12'),
(56, 14, 'RIGHT JOIN', 0, '2026-11-21 11:33:12'),
(57, 15, 'Hiệu năng cao, khả năng mở rộng tốt và cộng đồng hỗ trợ mạnh mẽ', 1, '2026-11-21 11:47:30'),
(58, 15, 'Chỉ chạy được trên môi trường Windows', 0, '2026-11-21 11:47:30'),
(59, 15, 'Không cần viết kiểm thử phần mềm', 0, '2026-11-21 11:47:30'),
(60, 16, 'Bật chứng chỉ mã hóa SSL/TLS (HTTPS) và bảo vệ dữ liệu bí mật qua Biến môi trường (.env)', 1, '2026-11-21 11:47:30'),
(61, 16, 'Lưu trực tiếp mật khẩu và API Key vào mã nguồn Git công khai', 0, '2026-11-21 11:47:30'),
(62, 16, 'Tắt tường lửa Firewall máy chủ', 0, '2026-11-21 11:47:30'),
(63, 17, 'Hiệu năng cao, khả năng mở rộng tốt và cộng đồng hỗ trợ mạnh mẽ', 1, '2026-11-21 11:47:30'),
(64, 17, 'Chỉ chạy được trên môi trường Windows', 0, '2026-11-21 11:47:30'),
(65, 17, 'Không cần viết kiểm thử phần mềm', 0, '2026-11-21 11:47:30'),
(66, 18, 'Bật chứng chỉ mã hóa SSL/TLS (HTTPS) và bảo vệ dữ liệu bí mật qua Biến môi trường (.env)', 1, '2026-11-21 11:47:30'),
(67, 18, 'Lưu trực tiếp mật khẩu và API Key vào mã nguồn Git công khai', 0, '2026-11-21 11:47:30'),
(68, 18, 'Tắt tường lửa Firewall máy chủ', 0, '2026-11-21 11:47:30'),
(69, 19, 'Hiệu năng cao, khả năng mở rộng tốt và cộng đồng hỗ trợ mạnh mẽ', 1, '2026-11-21 11:47:30'),
(70, 19, 'Chỉ chạy được trên môi trường Windows', 0, '2026-11-21 11:47:30'),
(71, 19, 'Không cần viết kiểm thử phần mềm', 0, '2026-11-21 11:47:30'),
(72, 20, 'Bật chứng chỉ mã hóa SSL/TLS (HTTPS) và bảo vệ dữ liệu bí mật qua Biến môi trường (.env)', 1, '2026-11-21 11:47:30'),
(73, 20, 'Lưu trực tiếp mật khẩu và API Key vào mã nguồn Git công khai', 0, '2026-11-21 11:47:30'),
(74, 20, 'Tắt tường lửa Firewall máy chủ', 0, '2026-11-21 11:47:30'),
(75, 21, 'Hiệu năng cao, khả năng mở rộng tốt và cộng đồng hỗ trợ mạnh mẽ', 1, '2026-11-21 11:47:30'),
(76, 21, 'Chỉ chạy được trên môi trường Windows', 0, '2026-11-21 11:47:30'),
(77, 21, 'Không cần viết kiểm thử phần mềm', 0, '2026-11-21 11:47:30'),
(78, 22, 'Bật chứng chỉ mã hóa SSL/TLS (HTTPS) và bảo vệ dữ liệu bí mật qua Biến môi trường (.env)', 1, '2026-11-21 11:47:30'),
(79, 22, 'Lưu trực tiếp mật khẩu và API Key vào mã nguồn Git công khai', 0, '2026-11-21 11:47:30'),
(80, 22, 'Tắt tường lửa Firewall máy chủ', 0, '2026-11-21 11:47:30'),
(81, 23, 'Hiệu năng cao, khả năng mở rộng tốt và cộng đồng hỗ trợ mạnh mẽ', 1, '2026-11-21 11:47:30'),
(82, 23, 'Chỉ chạy được trên môi trường Windows', 0, '2026-11-21 11:47:30'),
(83, 23, 'Không cần viết kiểm thử phần mềm', 0, '2026-11-21 11:47:30'),
(84, 24, 'Bật chứng chỉ mã hóa SSL/TLS (HTTPS) và bảo vệ dữ liệu bí mật qua Biến môi trường (.env)', 1, '2026-11-21 11:47:30'),
(85, 24, 'Lưu trực tiếp mật khẩu và API Key vào mã nguồn Git công khai', 0, '2026-11-21 11:47:30'),
(86, 24, 'Tắt tường lửa Firewall máy chủ', 0, '2026-11-21 11:47:30'),
(87, 25, 'Hiệu năng cao, khả năng mở rộng tốt và cộng đồng hỗ trợ mạnh mẽ', 1, '2026-11-21 11:47:30'),
(88, 25, 'Chỉ chạy được trên môi trường Windows', 0, '2026-11-21 11:47:30'),
(89, 25, 'Không cần viết kiểm thử phần mềm', 0, '2026-11-21 11:47:30'),
(90, 26, 'Bật chứng chỉ mã hóa SSL/TLS (HTTPS) và bảo vệ dữ liệu bí mật qua Biến môi trường (.env)', 1, '2026-11-21 11:47:30'),
(91, 26, 'Lưu trực tiếp mật khẩu và API Key vào mã nguồn Git công khai', 0, '2026-11-21 11:47:30'),
(92, 26, 'Tắt tường lửa Firewall máy chủ', 0, '2026-11-21 11:47:30'),
(93, 27, 'View', 0, '2026-11-21 14:07:38'),
(94, 27, 'Controller', 0, '2026-11-21 14:07:38'),
(95, 27, 'Model', 1, '2026-11-21 14:07:38'),
(96, 27, 'Router', 0, '2026-11-21 14:07:38'),
(97, 28, 'Request - Response (Yêu cầu và Phản hồi)', 1, '2026-11-21 14:07:38'),
(98, 28, 'Publish - Subscribe (Phát và Đăng ký)', 0, '2026-11-21 14:07:38'),
(99, 28, 'Peer to Peer (Ngang hàng)', 0, '2026-11-21 14:07:38'),
(100, 28, 'Broadcast không đồng bộ', 0, '2026-11-21 14:07:38');
INSERT INTO `quiz_answers` (`id`, `question_id`, `answer_text`, `is_correct`, `created_at`) VALUES
(101, 29, 'Tăng dung lượng lưu trữ của máy chủ', 0, '2026-11-21 14:07:38'),
(102, 29, 'Tách biệt hoàn toàn xử lý nghiệp vụ và hiển thị giao diện HTML', 1, '2026-11-21 14:07:38'),
(103, 29, 'Giúp file mã nguồn chạy nhanh hơn gấp 10 lần', 0, '2026-11-21 14:07:38'),
(104, 29, 'Bắt buộc người dùng phải đăng ký tài khoản', 0, '2026-11-21 14:07:38'),
(105, 30, 'List', 0, '2026-11-21 14:08:32'),
(106, 30, 'Tuple', 1, '2026-11-21 14:08:32'),
(107, 30, 'Dictionary', 0, '2026-11-21 14:08:32'),
(108, 30, 'Set', 0, '2026-11-21 14:08:32'),
(109, 31, '[x for x in range(10) if x % 2 == 0]', 1, '2026-11-21 14:08:32'),
(110, 31, '[x if x % 2 == 0 for x in range(10)]', 0, '2026-11-21 14:08:32'),
(111, 31, '{x: x % 2 == 0 for x in range(10)}', 0, '2026-11-21 14:08:32'),
(112, 31, '(x for x in range(10) if x % 2 == 0)', 0, '2026-11-21 14:08:32'),
(113, 32, 'Props có thể thay đổi trực tiếp, State thì không', 0, '2026-11-21 14:08:32'),
(114, 32, 'Props nhận từ bên ngoài (read-only), State được quản lý nội bộ component', 1, '2026-11-21 14:08:32'),
(115, 32, 'State chỉ dùng được trong class component', 0, '2026-11-21 14:08:32'),
(116, 32, 'Props bắt buộc phải là chuỗi ký tự String', 0, '2026-11-21 14:08:32'),
(117, 33, 'Phải có một phần tử cha duy nhất bao bọc', 1, '2026-11-21 14:08:32'),
(118, 33, 'Không được phép sử dụng thẻ div', 0, '2026-11-21 14:08:32'),
(119, 33, 'Bắt buộc phải nhúng CSS inline', 0, '2026-11-21 14:08:32'),
(120, 33, 'Phải khai báo kiểu dữ liệu TypeScript', 0, '2026-11-21 14:08:32'),
(121, 34, 'Ctrl + G', 0, '2026-11-21 14:08:32'),
(122, 34, 'Shift + A', 1, '2026-11-21 14:08:32'),
(123, 34, 'Alt + L', 0, '2026-11-21 14:08:32'),
(124, 34, 'Ctrl + Alt + K', 0, '2026-11-21 14:08:32'),
(125, 35, 'Tự động co giãn theo kích thước của frame cha', 1, '2026-11-21 14:08:32'),
(126, 35, 'Cố định kích thước không bao giờ thay đổi', 0, '2026-11-21 14:08:32'),
(127, 35, 'Tự động tô màu nền ngẫu nhiên', 0, '2026-11-21 14:08:32'),
(128, 35, 'Ẩn layer khi màn hình nhỏ lại', 0, '2026-11-21 14:08:32'),
(129, 36, 'Image là thể hiện đang chạy của Container', 0, '2026-11-21 14:08:32'),
(130, 36, 'Container là một instance đang chạy được tạo ra từ Image', 1, '2026-11-21 14:08:32'),
(131, 36, 'Image và Container là hai thuật ngữ hoàn toàn giống nhau', 0, '2026-11-21 14:08:32'),
(132, 36, 'Image chỉ dùng cho hệ điều hành Windows', 0, '2026-11-21 14:08:32'),
(133, 37, 'docker run -d .', 0, '2026-11-21 14:08:32'),
(134, 37, 'docker build -t my-app .', 1, '2026-11-21 14:08:32'),
(135, 37, 'docker compose up', 0, '2026-11-21 14:08:32'),
(136, 37, 'docker create image', 0, '2026-11-21 14:08:32'),
(137, 38, 'WHERE', 0, '2026-11-21 14:08:32'),
(138, 38, 'HAVING', 1, '2026-11-21 14:08:32'),
(139, 38, 'ORDER BY', 0, '2026-11-21 14:08:32'),
(140, 38, 'GROUP BY', 0, '2026-11-21 14:08:32'),
(141, 39, 'Chỉ những dòng có giá trị khớp ở cả 2 bảng', 1, '2026-11-21 14:08:32'),
(142, 39, 'Toàn bộ dữ liệu của bảng bên trái', 0, '2026-11-21 14:08:32'),
(143, 39, 'Toàn bộ dữ liệu của bảng bên phải', 0, '2026-11-21 14:08:32'),
(144, 39, 'Tất cả các dòng của cả hai bảng bất kể có khớp hay không', 0, '2026-11-21 14:08:32');

-- -------------------------------------------------------------
-- Bảng: `quiz_attempts`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `quiz_attempts`;
CREATE TABLE `quiz_attempts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `quiz_id` int unsigned NOT NULL,
  `score_achieved` decimal(5,2) NOT NULL DEFAULT '0.00',
  `total_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `score_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `is_passed` tinyint(1) NOT NULL DEFAULT '0',
  `started_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `submitted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_id` (`quiz_id`),
  KEY `idx_attempt_user` (`user_id`,`quiz_id`),
  CONSTRAINT `quiz_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quiz_attempts_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `quiz_attempts`
INSERT INTO `quiz_attempts` (`id`, `user_id`, `quiz_id`, `score_achieved`, `total_score`, `score_percentage`, `is_passed`, `started_at`, `submitted_at`) VALUES
(1, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 11:39:21', '2026-11-21 18:39:21'),
(2, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 11:39:21', '2026-11-21 18:39:21'),
(3, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 11:42:49', '2026-11-21 18:42:49'),
(4, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 11:45:38', '2026-11-21 18:45:38'),
(5, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 11:47:06', '2026-11-21 18:47:06'),
(6, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 11:47:30', '2026-11-21 18:47:30'),
(7, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 11:47:35', '2026-11-21 18:47:35'),
(8, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 11:48:19', '2026-11-21 18:48:19'),
(9, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 11:53:59', '2026-11-21 18:53:59'),
(10, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 11:54:34', '2026-11-21 18:54:34'),
(11, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 11:54:48', '2026-11-21 18:54:48'),
(12, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 12:06:40', '2026-11-21 19:06:40'),
(13, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 12:14:27', '2026-11-21 19:14:27'),
(14, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 13:10:30', '2026-11-21 20:10:30'),
(15, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 13:36:14', '2026-11-21 20:36:14'),
(16, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 13:36:38', '2026-11-21 20:36:38'),
(17, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 13:42:34', '2026-11-21 20:42:34'),
(18, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 14:07:17', '2026-11-21 21:07:17'),
(19, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 14:08:37', '2026-11-21 21:08:37'),
(20, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 14:09:50', '2026-11-21 21:09:50'),
(21, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 14:09:56', '2026-11-21 21:09:56'),
(22, 2, 1, '3.00', '3.00', '100.00', 1, '2026-11-21 14:15:27', '2026-11-21 21:15:27');

-- -------------------------------------------------------------
-- Bảng: `quiz_attempt_answers`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `quiz_attempt_answers`;
CREATE TABLE `quiz_attempt_answers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `attempt_id` int unsigned NOT NULL,
  `question_id` int unsigned NOT NULL,
  `answer_id` int unsigned NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `attempt_id` (`attempt_id`),
  KEY `question_id` (`question_id`),
  KEY `answer_id` (`answer_id`),
  CONSTRAINT `quiz_attempt_answers_ibfk_1` FOREIGN KEY (`attempt_id`) REFERENCES `quiz_attempts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quiz_attempt_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quiz_attempt_answers_ibfk_3` FOREIGN KEY (`answer_id`) REFERENCES `quiz_answers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `quiz_attempt_answers`
INSERT INTO `quiz_attempt_answers` (`id`, `attempt_id`, `question_id`, `answer_id`, `is_correct`) VALUES
(1, 1, 1, 1, 1),
(2, 1, 2, 5, 1),
(3, 1, 3, 9, 1),
(4, 2, 1, 1, 1),
(5, 2, 2, 5, 1),
(6, 2, 3, 9, 1),
(7, 3, 1, 1, 1),
(8, 3, 2, 5, 1),
(9, 3, 3, 9, 1),
(10, 4, 1, 1, 1),
(11, 4, 2, 5, 1),
(12, 4, 3, 9, 1),
(13, 5, 1, 1, 1),
(14, 5, 2, 5, 1),
(15, 5, 3, 9, 1),
(16, 6, 1, 1, 1),
(17, 6, 2, 5, 1),
(18, 6, 3, 9, 1),
(19, 7, 1, 1, 1),
(20, 7, 2, 5, 1),
(21, 7, 3, 9, 1),
(22, 8, 1, 1, 1),
(23, 8, 2, 5, 1),
(24, 8, 3, 9, 1),
(25, 9, 1, 1, 1),
(26, 9, 2, 5, 1),
(27, 9, 3, 9, 1),
(28, 10, 1, 1, 1),
(29, 10, 2, 5, 1),
(30, 10, 3, 9, 1),
(31, 11, 1, 1, 1),
(32, 11, 2, 5, 1),
(33, 11, 3, 9, 1),
(34, 12, 1, 1, 1),
(35, 12, 2, 5, 1),
(36, 12, 3, 9, 1),
(37, 13, 1, 1, 1),
(38, 13, 2, 5, 1),
(39, 13, 3, 9, 1),
(40, 14, 1, 1, 1),
(41, 14, 2, 5, 1),
(42, 14, 3, 9, 1),
(43, 15, 1, 1, 1),
(44, 15, 2, 5, 1),
(45, 15, 3, 9, 1),
(46, 16, 1, 1, 1),
(47, 16, 2, 5, 1),
(48, 16, 3, 9, 1),
(49, 17, 1, 1, 1),
(50, 17, 2, 5, 1);
INSERT INTO `quiz_attempt_answers` (`id`, `attempt_id`, `question_id`, `answer_id`, `is_correct`) VALUES
(51, 17, 3, 9, 1),
(52, 18, 1, 1, 1),
(53, 18, 2, 5, 1),
(54, 18, 3, 9, 1),
(55, 19, 1, 1, 1),
(56, 19, 2, 5, 1),
(57, 19, 3, 9, 1),
(58, 20, 1, 1, 1),
(59, 20, 2, 5, 1),
(60, 20, 3, 9, 1),
(61, 21, 1, 1, 1),
(62, 21, 2, 5, 1),
(63, 21, 3, 9, 1),
(64, 22, 1, 1, 1),
(65, 22, 2, 5, 1),
(66, 22, 3, 9, 1);

-- -------------------------------------------------------------
-- Bảng: `certificates`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `certificates`;
CREATE TABLE `certificates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `certificate_code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int unsigned NOT NULL,
  `course_id` int unsigned NOT NULL,
  `issued_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('valid','revoked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'valid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `certificate_code` (`certificate_code`),
  UNIQUE KEY `uq_user_course_cert` (`user_id`,`course_id`),
  KEY `course_id` (`course_id`),
  KEY `idx_cert_code` (`certificate_code`),
  CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `certificates_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `certificates`
INSERT INTO `certificates` (`id`, `certificate_code`, `user_id`, `course_id`, `issued_at`, `pdf_path`, `status`) VALUES
(1, 'CERT-2026-X8B9K2', 2, 1, '2026-11-21 11:29:15', NULL, 'valid'),
(2, 'CERT-2026-706CF3BC', 1, 1, '2026-11-21 11:38:44', NULL, 'valid'),
(3, 'CERT-2026-882D3383', 43, 1, '2026-11-21 11:47:30', NULL, 'valid'),
(4, 'CERT-2026-7B1F4939', 45, 3, '2026-11-21 11:51:24', NULL, 'valid'),
(5, 'CERT-2026-2034C86F', 45, 4, '2026-11-21 11:51:24', NULL, 'valid'),
(6, 'CERT-2026-CCA8C0B0', 45, 5, '2026-11-21 11:51:24', NULL, 'valid'),
(7, 'CERT-2026-4AF648B6', 46, 4, '2026-11-21 11:51:24', NULL, 'valid'),
(8, 'CERT-2026-07EE4BBD', 46, 5, '2026-11-21 11:51:24', NULL, 'valid'),
(9, 'CERT-2026-8063B4BB', 46, 6, '2026-11-21 11:51:24', NULL, 'valid'),
(10, 'CERT-2026-2AAA51BC', 47, 5, '2026-11-21 11:51:24', NULL, 'valid'),
(11, 'CERT-2026-14FA3F86', 47, 6, '2026-11-21 11:51:24', NULL, 'valid'),
(12, 'CERT-2026-C6D8B1D1', 47, 7, '2026-11-21 11:51:24', NULL, 'valid'),
(13, 'CERT-2026-F0EB7673', 48, 6, '2026-11-21 11:51:24', NULL, 'valid'),
(14, 'CERT-2026-C12FC5D8', 48, 7, '2026-11-21 11:51:24', NULL, 'valid'),
(15, 'CERT-2026-82F61ACD', 48, 8, '2026-11-21 11:51:24', NULL, 'valid');

-- -------------------------------------------------------------
-- Bảng: `enrollments`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `enrollments`;
CREATE TABLE `enrollments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `course_id` int unsigned NOT NULL,
  `status` enum('active','expired','revoked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `progress_percent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `enrolled_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_course` (`user_id`,`course_id`),
  KEY `course_id` (`course_id`),
  KEY `idx_enroll_user` (`user_id`),
  CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `enrollments`
INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `status`, `progress_percent`, `enrolled_at`, `completed_at`) VALUES
(2, 1, 1, 'active', '0.00', '2026-11-21 11:38:44', NULL),
(3, 2, 6, 'active', '33.33', '2026-11-21 11:39:21', NULL),
(8, 43, 1, 'active', '100.00', '2026-11-21 11:47:30', '2026-11-21 18:47:30'),
(9, 43, 2, 'active', '25.00', '2026-11-21 11:47:30', NULL),
(10, 43, 3, 'active', '25.00', '2026-11-21 11:47:30', NULL),
(14, 44, 2, 'active', '70.00', '2026-11-21 11:50:40', NULL),
(15, 44, 3, 'active', '70.00', '2026-11-21 11:50:40', NULL),
(16, 44, 4, 'active', '70.00', '2026-11-21 11:50:40', NULL),
(17, 45, 3, 'active', '100.00', '2026-11-21 11:51:24', '2026-11-21 18:51:24'),
(18, 45, 4, 'active', '100.00', '2026-11-21 11:51:24', '2026-11-21 18:51:24'),
(19, 45, 5, 'active', '100.00', '2026-11-21 11:51:24', '2026-11-21 18:51:24'),
(20, 46, 4, 'active', '100.00', '2026-11-21 11:51:24', '2026-11-21 18:51:24'),
(21, 46, 5, 'active', '100.00', '2026-11-21 11:51:24', '2026-11-21 18:51:24'),
(22, 46, 6, 'active', '100.00', '2026-11-21 11:51:24', '2026-11-21 18:51:24'),
(23, 47, 5, 'active', '100.00', '2026-11-21 11:51:24', '2026-11-21 18:51:24'),
(24, 47, 6, 'active', '100.00', '2026-11-21 11:51:24', '2026-11-21 18:51:24'),
(25, 47, 7, 'active', '100.00', '2026-11-21 11:51:24', '2026-11-21 18:51:24'),
(26, 48, 6, 'active', '100.00', '2026-11-21 11:51:24', '2026-11-21 18:51:24'),
(27, 48, 7, 'active', '100.00', '2026-11-21 11:51:24', '2026-11-21 18:51:24'),
(28, 48, 8, 'active', '100.00', '2026-11-21 11:51:24', '2026-11-21 18:51:24'),
(36, 1, 8, 'active', '0.00', '2026-11-21 13:12:03', NULL),
(39, 1, 5, 'active', '0.00', '2026-11-21 13:12:34', NULL),
(67, 2, 11, 'active', '0.00', '2026-11-21 14:10:52', NULL);

-- -------------------------------------------------------------
-- Bảng: `instructor_profiles`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `instructor_profiles`;
CREATE TABLE `instructor_profiles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `headline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `qualification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience_years` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `instructor_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `instructor_profiles`
INSERT INTO `instructor_profiles` (`id`, `user_id`, `headline`, `bio`, `qualification`, `experience_years`, `created_at`) VALUES
(1, 25, 'Senior Fullstack Engineer & Giảng viên Công nghệ', 'Hơn 8 năm kinh nghiệm phát triển hệ thống và giảng dạy cho hàng ngàn học viên.', 'Thạc sĩ Khoa học Máy tính', 8, '2026-11-21 11:19:27'),
(2, 26, 'AI Research Scientist & Data Lead', 'Tiến sĩ Trí tuệ Nhân tạo tại ĐH Quốc gia, chuyên gia Machine Learning và phân tích dữ liệu lớn.', 'Tiến sĩ Trí tuệ Nhân tạo', 10, '2026-11-21 11:33:11'),
(3, 27, 'Lead Product Designer & UX Specialist', 'Hơn 6 năm thiết kế sản phẩm số cho các tập đoàn công nghệ, chuyên sâu về Design System trên Figma.', 'Cử nhân Thiết kế Đồ họa & Tương tác', 6, '2026-11-21 11:33:11'),
(4, 28, 'DevOps & Cloud Solutions Architect', 'Chuyên gia tư vấn hạ tầng đám mây, Kubernetes, Docker và tự động hóa quy trình CI/CD cho doanh nghiệp.', 'Kỹ sư Hệ thống & AWS Certified Solutions Architect', 7, '2026-11-21 11:33:11'),
(5, 40, 'Senior Backend Engineer & Go Specialist tại VNG', 'Hơn 9 năm kinh nghiệm thiết kế hệ thống Microservices xử lý hàng chục triệu requests mỗi ngày với Golang, Docker, Kafka.', 'Thạc sĩ Khoa học Máy tính (ĐH Bách Khoa)', 9, '2026-11-21 11:47:30'),
(6, 41, 'AWS Certified Solutions Architect - Professional', 'Từng tư vấn di chuyển hạ tầng đám mây cho hơn 40 tập đoàn lớn, chuyên sâu về Kubernetes, Terraform và kiến trúc Serverless.', 'Kỹ sư Hệ thống Viễn thông & Đám mây', 11, '2026-11-21 11:47:30'),
(7, 42, 'Lead Growth Marketer & Digital Strategist', 'Chuyên gia tối ưu tỷ lệ chuyển đổi, quản lý ngân sách quảng cáo hơn 2 triệu USD/năm trên nền tảng Meta và Google Ads.', 'Cử nhân Kinh tế Đối ngoại (ĐH Ngoại Thương)', 7, '2026-11-21 11:47:30');

-- -------------------------------------------------------------
-- Bảng: `course_reviews`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `course_reviews`;
CREATE TABLE `course_reviews` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `rating` tinyint unsigned NOT NULL DEFAULT '5',
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_course_rating` (`course_id`,`rating`),
  CONSTRAINT `course_reviews_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `course_reviews`
INSERT INTO `course_reviews` (`id`, `course_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 2, 5, 'Khóa học cực kỳ chi tiết! Giảng viên dạy rất có tâm, giải thích mô hình MVC và bảo mật dễ hiểu.', '2026-11-21 11:45:35'),
(2, 1, 2, 5, 'Nhờ khóa học mà mình đã tự xây dựng được đồ án website hoàn chỉnh nộp tốt nghiệp.', '2026-11-21 11:45:35'),
(3, 2, 2, 5, 'Lộ trình Machine Learning rất logic, bài tập thực hành sát với yêu cầu tuyển dụng.', '2026-11-21 11:45:35'),
(4, 3, 2, 5, 'Phần React Hooks và Context API giảng rất sâu sắc, giải quyết triệt để vấn đề re-render.', '2026-11-21 11:45:35'),
(5, 4, 2, 5, 'Tuyệt vời! Khóa học hoàn toàn miễn phí nhưng chất lượng còn hơn cả nhiều khóa có phí.', '2026-11-21 11:45:35'),
(6, 5, 2, 5, 'Hướng dẫn Docker và CI/CD thực chiến, triển khai mượt mà lên server.', '2026-11-21 11:45:35'),
(7, 6, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 11:47:06'),
(8, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 11:47:30'),
(9, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 11:47:35'),
(10, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 11:48:19'),
(11, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 11:53:59'),
(12, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 11:54:34'),
(13, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 11:54:48'),
(14, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 12:06:40'),
(15, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 12:14:27'),
(16, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 13:10:30'),
(17, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 13:36:15'),
(18, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 13:36:38'),
(19, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 13:42:34'),
(20, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 14:07:17'),
(21, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 14:08:37'),
(22, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 14:09:50'),
(23, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 14:09:56'),
(24, 12, 2, 5, 'Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.', '2026-11-21 14:15:27');

-- -------------------------------------------------------------
-- Bảng: `course_discussions`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `course_discussions`;
CREATE TABLE `course_discussions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_lesson_discuss` (`lesson_id`),
  CONSTRAINT `course_discussions_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_discussions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `course_discussions`
INSERT INTO `course_discussions` (`id`, `lesson_id`, `user_id`, `content`, `created_at`) VALUES
(1, 1, 2, 'Chào thầy, em đã hoàn thành xong bài 1 và cấu hình môi trường thành công! Em sẵn sàng sang bài tiếp theo.', '2026-11-21 11:45:35'),
(2, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 11:47:06'),
(3, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 11:47:30'),
(4, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 11:47:35'),
(5, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 11:48:19'),
(6, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 11:53:59'),
(7, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 11:54:34'),
(8, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 11:54:48'),
(9, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 12:06:40'),
(10, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 12:14:27'),
(11, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 13:10:30'),
(12, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 13:36:15'),
(13, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 13:36:38'),
(14, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 13:42:34'),
(15, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 14:07:17'),
(16, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 14:08:37'),
(17, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 14:09:50'),
(18, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 14:09:56'),
(19, 1, 2, 'Em có câu hỏi về phần cài đặt môi trường trong bài học này.', '2026-11-21 14:15:27');

-- -------------------------------------------------------------
-- Bảng: `lesson_notes`
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `lesson_notes`;
CREATE TABLE `lesson_notes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `timestamp_seconds` int unsigned NOT NULL DEFAULT '0',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lesson_id` (`lesson_id`),
  KEY `idx_user_lesson` (`user_id`,`lesson_id`),
  CONSTRAINT `lesson_notes_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lesson_notes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `lesson_notes`
INSERT INTO `lesson_notes` (`id`, `lesson_id`, `user_id`, `timestamp_seconds`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 45, 'Lưu ý về cấu trúc thư mục MVC và cách gọi Database connection.', '2026-11-21 11:52:22', '2026-11-21 11:52:22');

SET FOREIGN_KEY_CHECKS = 1;
