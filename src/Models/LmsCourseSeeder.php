<?php

namespace App\Models;

use App\DB;
use PDO;

class LmsCourseSeeder
{
    public static function seed(): array
    {
        $pdo = DB::connection();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $logs = [];

        // 1. Tạo hoặc cập nhật các Giảng viên (Instructors)
        $passHash = password_hash('password123', PASSWORD_BCRYPT);
        $instructorsData = [
            [
                'email' => 'giangvien@lms.local',
                'name' => 'ThS. Nguyễn Văn A',
                'headline' => 'Senior Fullstack Engineer & Tech Lead',
                'bio' => 'Hơn 8 năm kinh nghiệm đào tạo và phát triển hệ thống web quy mô lớn với PHP, Node.js và React.',
                'qualification' => 'Thạc sĩ Khoa học Máy tính',
                'exp' => 8
            ],
            [
                'email' => 'minh.tran@lms.local',
                'name' => 'TS. Trần Hoàng Minh',
                'headline' => 'AI Research Scientist & Data Lead',
                'bio' => 'Tiến sĩ Trí tuệ Nhân tạo tại ĐH Quốc gia, chuyên gia Machine Learning và phân tích dữ liệu lớn.',
                'qualification' => 'Tiến sĩ Trí tuệ Nhân tạo',
                'exp' => 10
            ],
            [
                'email' => 'huong.le@lms.local',
                'name' => 'Lê Thị Mai Hương',
                'headline' => 'Lead Product Designer & UX Specialist',
                'bio' => 'Hơn 6 năm thiết kế sản phẩm số cho các tập đoàn công nghệ, chuyên sâu về Design System trên Figma.',
                'qualification' => 'Cử nhân Thiết kế Đồ họa & Tương tác',
                'exp' => 6
            ],
            [
                'email' => 'duc.hoang@lms.local',
                'name' => 'Hoàng Văn Đức',
                'headline' => 'DevOps & Cloud Solutions Architect',
                'bio' => 'Chuyên gia tư vấn hạ tầng đám mây, Kubernetes, Docker và tự động hóa quy trình CI/CD cho doanh nghiệp.',
                'qualification' => 'Kỹ sư Hệ thống & AWS Certified Solutions Architect',
                'exp' => 7
            ],
        ];

        $insMap = [];
        foreach ($instructorsData as $ins) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$ins['email']]);
            $uId = $stmt->fetchColumn();

            if (!$uId) {
                $insStmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'instructor', 'active')");
                $insStmt->execute([$ins['name'], $ins['email'], $passHash]);
                $uId = $pdo->lastInsertId();

                $pStmt = $pdo->prepare("INSERT INTO instructor_profiles (user_id, headline, bio, qualification, experience_years) VALUES (?, ?, ?, ?, ?)");
                $pStmt->execute([$uId, $ins['headline'], $ins['bio'], $ins['qualification'], $ins['exp']]);
                $logs[] = "Tạo giảng viên: {$ins['name']} ({$ins['email']})";
            }
            $insMap[$ins['email']] = (int) $uId;
        }

        // 2. Tạo hoặc lấy Danh mục
        $categoriesData = [
            ['name' => 'Lập trình Web & Frontend', 'slug' => 'lap-trinh-web', 'desc' => 'HTML, CSS, JS, PHP, React, Next.js'],
            ['name' => 'Khoa học Dữ liệu & AI', 'slug' => 'data-ai', 'desc' => 'Python, Machine Learning, Deep Learning, SQL'],
            ['name' => 'Thiết kế UI/UX', 'slug' => 'ui-ux-design', 'desc' => 'Figma, Design System, User Research'],
            ['name' => 'Hệ thống & DevOps Cloud', 'slug' => 'devops-system', 'desc' => 'Docker, Kubernetes, Linux, CI/CD'],
        ];

        $catMap = [];
        foreach ($categoriesData as $c) {
            $stmt = $pdo->prepare("SELECT id FROM categories WHERE slug = ?");
            $stmt->execute([$c['slug']]);
            $cId = $stmt->fetchColumn();

            if (!$cId) {
                $insCat = $pdo->prepare("INSERT INTO categories (name, slug, description, is_active) VALUES (?, ?, ?, 1)");
                $insCat->execute([$c['name'], $c['slug'], $c['desc']]);
                $cId = $pdo->lastInsertId();
            }
            $catMap[$c['slug']] = (int) $cId;
        }

        // 3. Danh sách 6 Khóa học chất lượng cao với ảnh cục bộ và video YouTube thực tế
        $coursesToSeed = [
            [
                'slug' => 'khoa-hoc-lap-trinh-web-fullstack-tu-zero-den-hero',
                'title' => 'Khóa học Lập trình Web Fullstack từ Zero đến Hero',
                'instructor_id' => $insMap['giangvien@lms.local'],
                'category_id' => $catMap['lap-trinh-web'],
                'summary' => 'Lộ trình toàn diện làm chủ PHP, MySQL, Frontend hiện đại và tư duy kiến trúc dự án thực tế.',
                'description' => '<h3>Chào mừng bạn đến với khóa học Lập trình Web Fullstack!</h3><p>Khóa học này sẽ đưa bạn từ người chưa biết gì tới khi có thể tự tay xây dựng một website hoàn chỉnh với đầy đủ tính năng xác thực, thanh toán, thi trắc nghiệm và cấp chứng chỉ.</p><h4>Bạn sẽ học được gì:</h4><ul><li>Cú pháp và tư duy lập trình web hiện đại</li><li>Mô hình MVC (Model-View-Controller) trong thực tế</li><li>Thiết kế Cơ sở dữ liệu chuẩn hóa 3NF với MySQL</li><li>Tích hợp các cổng thanh toán phổ biến: VNPay, MoMo, VietQR</li></ul>',
                'thumbnail' => '/image/courses/web_fullstack.jpg',
                'price' => 599000.00,
                'sale_price' => 299000.00,
                'level' => 'beginner',
                'total_duration_minutes' => 180,
                'total_lessons' => 3,
                'sections' => [
                    [
                        'title' => 'Chương 1: Khởi động & Nền tảng Web Development',
                        'lessons' => [
                            [
                                'title' => 'Bài 1: Tổng quan lộ trình Fullstack & Thiết lập môi trường',
                                'type' => 'video',
                                'is_preview' => 1,
                                'video' => 'https://www.youtube.com/embed/zJSY8tbf_ys',
                                'duration' => 600,
                                'materials' => ['Giao_trinh_tong_quan_fullstack.pdf']
                            ],
                            [
                                'title' => 'Bài 2: Làm chủ kiến trúc Backend MVC & Hướng đối tượng OOP',
                                'type' => 'video',
                                'is_preview' => 0,
                                'video' => 'https://www.youtube.com/embed/2eebptXfEvw',
                                'duration' => 720,
                                'materials' => ['Source_code_oop_mvc.zip']
                            ],
                            [
                                'title' => 'Bài 3: Bài kiểm tra đánh giá kiến thức Chương 1',
                                'type' => 'quiz',
                                'is_preview' => 0,
                                'quiz' => [
                                    'title' => 'Kiểm tra trắc nghiệm Kiến trúc MVC & Web Core',
                                    'passing_score' => 80.00,
                                    'questions' => [
                                        [
                                            'q' => 'Trong mô hình MVC, thành phần nào trực tiếp tương tác với Cơ sở Dữ liệu?',
                                            'points' => 1.0,
                                            'exp' => 'Model đại diện cho dữ liệu và các quy tắc nghiệp vụ truy vấn DB.',
                                            'answers' => [
                                                ['Model', 1],
                                                ['View', 0],
                                                ['Controller', 0],
                                                ['Router', 0]
                                            ]
                                        ],
                                        [
                                            'q' => 'Kỹ thuật nào dùng để chống tấn công SQL Injection hiệu quả nhất?',
                                            'points' => 1.0,
                                            'exp' => 'Prepared Statements với tham số buộc chặt (parameter binding) ngăn chặn chèn mã độc.',
                                            'answers' => [
                                                ['Sử dụng Prepared Statements (PDO)', 1],
                                                ['Chỉ dùng hàm md5()', 0],
                                                ['Tắt firewall máy chủ', 0],
                                                ['Nối chuỗi trực tiếp vào câu lệnh SQL', 0]
                                            ]
                                        ],
                                        [
                                            'q' => 'Trạng thái HTTP 200 biểu thị điều gì?',
                                            'points' => 1.0,
                                            'exp' => 'HTTP 200 OK báo hiệu yêu cầu đã được máy chủ xử lý thành công.',
                                            'answers' => [
                                                ['Yêu cầu xử lý thành công (OK)', 1],
                                                ['Không tìm thấy trang (Not Found)', 0],
                                                ['Lỗi máy chủ nội bộ (Internal Server Error)', 0],
                                                ['Yêu cầu bị từ chối (Forbidden)', 0]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                'slug' => 'chinh-phuc-python-va-tri-tue-nhan-tao',
                'title' => 'Chinh Phục Python & Trí Tuệ Nhân Tạo (AI / Machine Learning)',
                'instructor_id' => $insMap['minh.tran@lms.local'],
                'category_id' => $catMap['data-ai'],
                'summary' => 'Học cú pháp Python từ căn bản, làm việc với thư viện Numpy/Pandas và huấn luyện mô hình Machine Learning thực tế.',
                'description' => '<h3>Trở thành kỹ sư AI với lộ trình bài bản</h3><p>Khóa học trang bị cho bạn tư duy toán học ứng dụng, kỹ năng xử lý dữ liệu lớn và thuật toán Machine Learning / Deep Learning từ căn bản đến nâng cao.</p><h4>Nội dung trọng tâm:</h4><ul><li>Lập trình Python chuyên sâu: Generators, Decorators, OOP</li><li>Xử lý và làm sạch dữ liệu lớn với Pandas & NumPy</li><li>Huấn luyện mô hình Hồi quy (Regression) và Phân loại (Classification)</li><li>Đánh giá độ chính xác Precision, Recall, F1-Score</li></ul>',
                'thumbnail' => '/image/courses/python_ai.jpg',
                'price' => 899000.00,
                'sale_price' => 499000.00,
                'level' => 'intermediate',
                'total_duration_minutes' => 240,
                'total_lessons' => 3,
                'sections' => [
                    [
                        'title' => 'Chương 1: Python Căn Bản & Cấu Trúc Dữ Liệu',
                        'lessons' => [
                            [
                                'title' => 'Bài 1: Cú pháp Python, List, Dict và Hàm xử lý',
                                'type' => 'video',
                                'is_preview' => 1,
                                'video' => 'https://www.youtube.com/embed/kqtD5dpn9C8',
                                'duration' => 750,
                                'materials' => ['Python_CheatSheet_LMS.pdf']
                            ],
                            [
                                'title' => 'Bài 2: Tổng quan về Machine Learning & Học có giám sát',
                                'type' => 'video',
                                'is_preview' => 0,
                                'video' => 'https://www.youtube.com/embed/i_LwzRVP7bg',
                                'duration' => 900,
                                'materials' => ['Machine_Learning_Datasets.zip']
                            ],
                            [
                                'title' => 'Bài 3: Bài kiểm tra Kiến thức Python & Thuật toán Học máy',
                                'type' => 'quiz',
                                'is_preview' => 0,
                                'quiz' => [
                                    'title' => 'Đánh giá Năng lực Python & Machine Learning',
                                    'passing_score' => 80.00,
                                    'questions' => [
                                        [
                                            'q' => 'Thư viện Python nào chuyên dùng để thao tác và phân tích cấu trúc dữ liệu bảng (DataFrames)?',
                                            'points' => 1.0,
                                            'exp' => 'Pandas cung cấp cấu trúc DataFrame mạnh mẽ cho phân tích số liệu.',
                                            'answers' => [
                                                ['Pandas', 1],
                                                ['Matplotlib', 0],
                                                ['Flask', 0],
                                                ['Requests', 0]
                                            ]
                                        ],
                                        [
                                            'q' => 'Thuật toán nào sau đây thuộc nhóm học có giám sát (Supervised Learning)?',
                                            'points' => 1.0,
                                            'exp' => 'Linear Regression và Random Forest là các thuật toán học có giám sát.',
                                            'answers' => [
                                                ['Linear Regression', 1],
                                                ['K-Means Clustering', 0],
                                                ['PCA (Giảm chiều dữ liệu)', 0],
                                                ['Apriori', 0]
                                            ]
                                        ],
                                        [
                                            'q' => 'Hiện tượng mô hình hoạt động cực tốt trên tập Train nhưng kém trên tập Test gọi là gì?',
                                            'points' => 1.0,
                                            'exp' => 'Overfitting (Quá khớp) xảy ra khi mô hình học vẹt cả nhiễu của tập huấn luyện.',
                                            'answers' => [
                                                ['Overfitting (Quá khớp)', 1],
                                                ['Underfitting (Chưa khớp)', 0],
                                                ['Regularization', 0],
                                                ['Cross-validation', 0]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                'slug' => 'lap-trinh-frontend-hien-dai-reactjs',
                'title' => 'Lập trình Frontend Hiện đại với ReactJS & Next.js',
                'instructor_id' => $insMap['giangvien@lms.local'],
                'category_id' => $catMap['lap-trinh-web'],
                'summary' => 'Xây dựng Single Page Application (SPA), làm chủ React Hooks, Redux Toolkit và Server Components.',
                'description' => '<h3>Nâng cấp kỹ năng Frontend lên tầm Senior</h3><p>Khóa học đi sâu vào cơ chế Virtual DOM, cách tối ưu Render với React.memo, useMemo, useCallback và xây dựng ứng dụng chuẩn Production với Next.js.</p>',
                'thumbnail' => '/image/courses/reactjs.jpg',
                'price' => 650000.00,
                'sale_price' => 320000.00,
                'level' => 'intermediate',
                'total_duration_minutes' => 200,
                'total_lessons' => 3,
                'sections' => [
                    [
                        'title' => 'Chương 1: Kiến trúc React Core & Tư duy Component',
                        'lessons' => [
                            [
                                'title' => 'Bài 1: JSX, Props, State và Vòng đời Component',
                                'type' => 'video',
                                'is_preview' => 1,
                                'video' => 'https://www.youtube.com/embed/bMknfKXIFA8',
                                'duration' => 680,
                                'materials' => ['React_Core_Slides.pdf']
                            ],
                            [
                                'title' => 'Bài 2: Làm chủ React Hooks: useState, useEffect, useRef',
                                'type' => 'video',
                                'is_preview' => 0,
                                'video' => 'https://www.youtube.com/embed/W6NZfCO5SIk',
                                'duration' => 750,
                                'materials' => ['Hooks_Exercise_Starter.zip']
                            ],
                            [
                                'title' => 'Bài 3: Bài thi trắc nghiệm ReactJS & Virtual DOM',
                                'type' => 'quiz',
                                'is_preview' => 0,
                                'quiz' => [
                                    'title' => 'Đánh giá Kiến thức ReactJS',
                                    'passing_score' => 80.00,
                                    'questions' => [
                                        [
                                            'q' => 'Hook nào trong React được dùng để thực hiện các side-effect (gọi API, lắng nghe event)?',
                                            'points' => 1.0,
                                            'exp' => 'useEffect xử lý các tác vụ ngoài luồng render như fetch data, timer, subscriptions.',
                                            'answers' => [
                                                ['useEffect', 1],
                                                ['useState', 0],
                                                ['useMemo', 0],
                                                ['useReducer', 0]
                                            ]
                                        ],
                                        [
                                            'q' => 'Điều gì xảy ra khi bạn gọi hàm cập nhật State trong React?',
                                            'points' => 1.0,
                                            'exp' => 'React xếp lịch re-render component và so sánh Virtual DOM để cập nhật DOM thật.',
                                            'answers' => [
                                                ['Component được lên lịch để Re-render', 1],
                                                ['Trình duyệt reload lại toàn bộ trang', 0],
                                                ['Xóa toàn bộ bộ nhớ Cache', 0],
                                                ['Không có thay đổi nào xảy ra', 0]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                'slug' => 'thiet-ke-giao-dien-uiux-chuyen-nghiep-figma',
                'title' => 'Thiết kế Giao diện UI/UX Chuyên nghiệp với Figma',
                'instructor_id' => $insMap['huong.le@lms.local'],
                'category_id' => $catMap['ui-ux-design'],
                'summary' => 'Học tư duy thiết kế lấy người dùng làm trung tâm (User-Centered Design), tạo Wireframe và Design System trên Figma.',
                'description' => '<h3>Khóa học thiết kế thực chiến từ Figma cơ bản đến nâng cao</h3><p>Dành cho các bạn muốn bước chân vào ngành Product Design, chuyển đổi từ Designer hoặc Developer muốn nâng cao tính thẩm mỹ của sản phẩm.</p>',
                'thumbnail' => '/image/courses/uiux_figma.jpg',
                'price' => 450000.00,
                'sale_price' => 0.00,
                'level' => 'beginner',
                'total_duration_minutes' => 160,
                'total_lessons' => 3,
                'sections' => [
                    [
                        'title' => 'Chương 1: Công cụ Figma & Nguyên lý Visual Design',
                        'lessons' => [
                            [
                                'title' => 'Bài 1: Làm quen giao diện Figma, Frame, Auto Layout và Variants',
                                'type' => 'video',
                                'is_preview' => 1,
                                'video' => 'https://www.youtube.com/embed/FTFaQWZBqQ8',
                                'duration' => 640,
                                'materials' => ['Figma_UI_Kit_Template.fig']
                            ],
                            [
                                'title' => 'Bài 2: Tư duy UX, Quy trình Phỏng vấn Người dùng & Wireframing',
                                'type' => 'video',
                                'is_preview' => 0,
                                'video' => 'https://www.youtube.com/embed/c9Wg6Cb_YlU',
                                'duration' => 800,
                                'materials' => ['UX_Research_Framework.pdf']
                            ],
                            [
                                'title' => 'Bài 3: Trắc nghiệm Kiến thức UI/UX & Nguyên lý Thiết kế',
                                'type' => 'quiz',
                                'is_preview' => 0,
                                'quiz' => [
                                    'title' => 'Kiểm tra Kiến thức UI/UX Design',
                                    'passing_score' => 80.00,
                                    'questions' => [
                                        [
                                            'q' => 'Tính năng Auto Layout trong Figma tương tự với thuộc tính CSS nào?',
                                            'points' => 1.0,
                                            'exp' => 'Auto Layout mô phỏng hành vi của CSS Flexbox (hướng sắp xếp, khoảng cách, căn chỉnh).',
                                            'answers' => [
                                                ['CSS Flexbox', 1],
                                                ['CSS Float', 0],
                                                ['CSS Position Absolute', 0],
                                                ['CSS Z-Index', 0]
                                            ]
                                        ],
                                        [
                                            'q' => 'Nguyên tắc nào sau đây đảm bảo khoảng cách ngón tay bấm (tap target) thuận tiện trên Mobile?',
                                            'points' => 1.0,
                                            'exp' => 'Theo chuẩn Apple & Google, kích thước nút bấm tối thiểu khuyến nghị là 44x44 hoặc 48x48 dp/px.',
                                            'answers' => [
                                                ['Tối thiểu 44x44 hoặc 48x48 px', 1],
                                                ['Tối đa 10x10 px', 0],
                                                ['Không cần quan tâm kích thước', 0],
                                                ['Càng nhỏ càng tiết kiệm diện tích', 0]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                'slug' => 'devops-toan-dien-docker-kubernetes-cicd',
                'title' => 'DevOps Toàn Diện: Docker, Kubernetes & CI/CD Pipeline',
                'instructor_id' => $insMap['duc.hoang@lms.local'],
                'category_id' => $catMap['devops-system'],
                'summary' => 'Đóng gói ứng dụng với Docker Container, điều phối cụm Kubernetes và tự động hóa triển khai CI/CD trên GitHub Actions.',
                'description' => '<h3>Chinh phục kỹ năng hạ tầng Cloud & Tự động hóa</h3><p>Khóa học thực chiến dành cho lập trình viên muốn tự chủ triển khai và mở rộng quy mô ứng dụng mà không phụ thuộc vào đội ngũ Sysadmin.</p>',
                'thumbnail' => '/image/courses/devops_docker.jpg',
                'price' => 1200000.00,
                'sale_price' => 799000.00,
                'level' => 'advanced',
                'total_duration_minutes' => 220,
                'total_lessons' => 3,
                'sections' => [
                    [
                        'title' => 'Chương 1: Đóng gói Ứng dụng với Docker',
                        'lessons' => [
                            [
                                'title' => 'Bài 1: Docker Architecture, Images, Containers & Viết Dockerfile',
                                'type' => 'video',
                                'is_preview' => 1,
                                'video' => 'https://www.youtube.com/embed/3c-iBn73dDE',
                                'duration' => 820,
                                'materials' => ['Dockerfile_Best_Practices.pdf']
                            ],
                            [
                                'title' => 'Bài 2: GitOps & Tự động hóa Pipeline CI/CD với GitHub Actions',
                                'type' => 'video',
                                'is_preview' => 0,
                                'video' => 'https://www.youtube.com/embed/pg19Z8LL0vQ',
                                'duration' => 780,
                                'materials' => ['github-actions-workflow.yml']
                            ],
                            [
                                'title' => 'Bài 3: Trắc nghiệm Kiến thức Docker & Containerization',
                                'type' => 'quiz',
                                'is_preview' => 0,
                                'quiz' => [
                                    'title' => 'Đánh giá Kỹ năng Docker & DevOps',
                                    'passing_score' => 80.00,
                                    'questions' => [
                                        [
                                            'q' => 'Chỉ thị nào trong Dockerfile được dùng để chỉ định Image nền khởi tạo?',
                                            'points' => 1.0,
                                            'exp' => 'FROM xác định base image (vd: FROM php:8.2-fpm hoặc FROM node:20).',
                                            'answers' => [
                                                ['FROM', 1],
                                                ['RUN', 0],
                                                ['COPY', 0],
                                                ['EXPOSE', 0]
                                            ]
                                        ],
                                        [
                                            'q' => 'Lệnh Docker nào dùng để chạy các container đa dịch vụ được định nghĩa trong file YAML?',
                                            'points' => 1.0,
                                            'exp' => 'docker compose up khởi chạy toàn bộ dịch vụ được cấu hình trong docker-compose.yml.',
                                            'answers' => [
                                                ['docker compose up', 1],
                                                ['docker build', 0],
                                                ['docker ps', 0],
                                                ['docker commit', 0]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                'slug' => 'phan-tich-du-lieu-kinh-doanh-sql-mastery',
                'title' => 'Phân tích Dữ liệu Kinh doanh (Data Analytics & SQL Mastery)',
                'instructor_id' => $insMap['minh.tran@lms.local'],
                'category_id' => $catMap['data-ai'],
                'summary' => 'Truy vấn dữ liệu từ cơ bản tới nâng cao với SQL, tối ưu hóa Index triệu bản ghi và trực quan hóa Dashboard kinh doanh.',
                'description' => '<h3>Kỹ năng phân tích số liệu không thể thiếu trong kỷ nguyên số</h3><p>Khóa học giúp bạn đọc hiểu hành vi người dùng, tìm kiếm insight từ bảng số liệu thô và hỗ trợ ra quyết định kinh doanh chuẩn xác.</p>',
                'thumbnail' => '/image/courses/data_analytics.jpg',
                'price' => 0.00,
                'sale_price' => 0.00,
                'level' => 'all',
                'total_duration_minutes' => 150,
                'total_lessons' => 3,
                'sections' => [
                    [
                        'title' => 'Chương 1: SQL Truy Vấn & Tối Ưu Hóa Dữ Liệu',
                        'lessons' => [
                            [
                                'title' => 'Bài 1: Cú pháp SELECT, GROUP BY, HAVING & Kỹ thuật JOIN nhiều bảng',
                                'type' => 'video',
                                'is_preview' => 1,
                                'video' => 'https://www.youtube.com/embed/ua-CiDNNj30',
                                'duration' => 650,
                                'materials' => ['SQL_Practice_Database.sql']
                            ],
                            [
                                'title' => 'Bài 2: Đánh chỉ mục Index (B-Tree) & Tối ưu hiệu năng câu truy vấn',
                                'type' => 'video',
                                'is_preview' => 0,
                                'video' => 'https://www.youtube.com/embed/2eebptXfEvw',
                                'duration' => 700,
                                'materials' => ['Query_Optimization_Guide.pdf']
                            ],
                            [
                                'title' => 'Bài 3: Trắc nghiệm Kiến thức SQL & Phân tích Dữ liệu',
                                'type' => 'quiz',
                                'is_preview' => 0,
                                'quiz' => [
                                    'title' => 'Đánh giá Kỹ năng SQL Thực chiến',
                                    'passing_score' => 80.00,
                                    'questions' => [
                                        [
                                            'q' => 'Mệnh đề nào dùng để lọc điều kiện trên kết quả đã được gom nhóm bởi GROUP BY?',
                                            'points' => 1.0,
                                            'exp' => 'HAVING dùng để lọc điều kiện sau khi nhóm (vd: HAVING COUNT(*) > 5), trong khi WHERE lọc trước khi nhóm.',
                                            'answers' => [
                                                ['HAVING', 1],
                                                ['WHERE', 0],
                                                ['ORDER BY', 0],
                                                ['LIMIT', 0]
                                            ]
                                        ],
                                        [
                                            'q' => 'Loại JOIN nào trả về tất cả bản ghi từ bảng bên trái bất kể có bản ghi khớp bên phải hay không?',
                                            'points' => 1.0,
                                            'exp' => 'LEFT JOIN (hoặc LEFT OUTER JOIN) giữ lại toàn bộ hàng từ bảng bên trái.',
                                            'answers' => [
                                                ['LEFT JOIN', 1],
                                                ['INNER JOIN', 0],
                                                ['CROSS JOIN', 0],
                                                ['RIGHT JOIN', 0]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // 4. Lặp và nạp toàn bộ khóa học vào DB
        foreach ($coursesToSeed as $cData) {
            $checkStmt = $pdo->prepare("SELECT id FROM courses WHERE slug = ?");
            $checkStmt->execute([$cData['slug']]);
            $existingId = $checkStmt->fetchColumn();

            if ($existingId) {
                // Cập nhật lại thumbnail, video, giá nếu đã có
                $upd = $pdo->prepare("UPDATE courses SET title = ?, summary = ?, description = ?, thumbnail = ?, price = ?, sale_price = ?, level = ?, status = 'published', instructor_id = ?, category_id = ?, total_duration_minutes = ?, total_lessons = ? WHERE id = ?");
                $upd->execute([
                    $cData['title'],
                    $cData['summary'],
                    $cData['description'],
                    $cData['thumbnail'],
                    $cData['price'],
                    $cData['sale_price'],
                    $cData['level'],
                    $cData['instructor_id'],
                    $cData['category_id'],
                    $cData['total_duration_minutes'],
                    $cData['total_lessons'],
                    $existingId
                ]);
                $courseId = (int) $existingId;
                $logs[] = "Cập nhật khóa học: {$cData['title']}";
            } else {
                $insCourse = $pdo->prepare("INSERT INTO courses (instructor_id, category_id, title, slug, summary, description, thumbnail, price, sale_price, level, status, total_duration_minutes, total_lessons) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'published', ?, ?)");
                $insCourse->execute([
                    $cData['instructor_id'],
                    $cData['category_id'],
                    $cData['title'],
                    $cData['slug'],
                    $cData['summary'],
                    $cData['description'],
                    $cData['thumbnail'],
                    $cData['price'],
                    $cData['sale_price'],
                    $cData['level'],
                    $cData['total_duration_minutes'],
                    $cData['total_lessons']
                ]);
                $courseId = (int) $pdo->lastInsertId();
                $logs[] = "Tạo mới khóa học: {$cData['title']}";
            }

            // Xử lý Sections & Lessons cho khóa học này
            foreach ($cData['sections'] as $sIdx => $sec) {
                $checkSec = $pdo->prepare("SELECT id FROM sections WHERE course_id = ? AND title = ?");
                $checkSec->execute([$courseId, $sec['title']]);
                $secId = $checkSec->fetchColumn();

                if (!$secId) {
                    $insSec = $pdo->prepare("INSERT INTO sections (course_id, title, sort_order) VALUES (?, ?, ?)");
                    $insSec->execute([$courseId, $sec['title'], $sIdx + 1]);
                    $secId = $pdo->lastInsertId();
                }

                foreach ($sec['lessons'] as $lIdx => $les) {
                    $checkLes = $pdo->prepare("SELECT id FROM lessons WHERE section_id = ? AND title = ?");
                    $checkLes->execute([$secId, $les['title']]);
                    $lesId = $checkLes->fetchColumn();

                    if (!$lesId) {
                        $insLes = $pdo->prepare("INSERT INTO lessons (section_id, title, slug, lesson_type, is_preview, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
                        $lesSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $les['title'])));
                        $insLes->execute([$secId, $les['title'], $lesSlug, $les['type'], $les['is_preview'], $lIdx + 1]);
                        $lesId = $pdo->lastInsertId();
                    }

                    // Nếu là video bài giảng
                    if ($les['type'] === 'video' && !empty($les['video'])) {
                        $vCheck = $pdo->prepare("SELECT id FROM lesson_videos WHERE lesson_id = ?");
                        $vCheck->execute([$lesId]);
                        if (!$vCheck->fetchColumn()) {
                            $insV = $pdo->prepare("INSERT INTO lesson_videos (lesson_id, provider, video_url, duration_seconds) VALUES (?, 'youtube', ?, ?)");
                            $insV->execute([$lesId, $les['video'], $les['duration']]);
                        } else {
                            $updV = $pdo->prepare("UPDATE lesson_videos SET video_url = ?, duration_seconds = ? WHERE lesson_id = ?");
                            $updV->execute([$les['video'], $les['duration'], $lesId]);
                        }
                    }

                    // Nếu có tài liệu đính kèm
                    if (!empty($les['materials'])) {
                        foreach ($les['materials'] as $matTitle) {
                            $mCheck = $pdo->prepare("SELECT id FROM lesson_materials WHERE lesson_id = ? AND title = ?");
                            $mCheck->execute([$lesId, $matTitle]);
                            if (!$mCheck->fetchColumn()) {
                                $ext = pathinfo($matTitle, PATHINFO_EXTENSION) ?: 'pdf';
                                $insM = $pdo->prepare("INSERT INTO lesson_materials (lesson_id, title, file_path, file_type, file_size_bytes) VALUES (?, ?, ?, ?, ?)");
                                $insM->execute([$lesId, $matTitle, '/storage/materials/' . $matTitle, $ext, 1048576]);
                            }
                        }
                    }

                    // Nếu là Quiz
                    if ($les['type'] === 'quiz' && !empty($les['quiz'])) {
                        $qCheck = $pdo->prepare("SELECT id FROM quizzes WHERE lesson_id = ?");
                        $qCheck->execute([$lesId]);
                        $quizId = $qCheck->fetchColumn();

                        if (!$quizId) {
                            $insQ = $pdo->prepare("INSERT INTO quizzes (course_id, lesson_id, title, description, duration_minutes, passing_score, max_attempts) VALUES (?, ?, ?, ?, 15, ?, 3)");
                            $insQ->execute([$courseId, $lesId, $les['quiz']['title'], 'Bài kiểm tra trắc nghiệm đánh giá kiến thức bài học.', $les['quiz']['passing_score']]);
                            $quizId = $pdo->lastInsertId();

                            // Thêm câu hỏi và đáp án
                            foreach ($les['quiz']['questions'] as $qIdx => $qItem) {
                                $insQues = $pdo->prepare("INSERT INTO quiz_questions (quiz_id, question_text, question_type, points, explanation, sort_order) VALUES (?, ?, 'single_choice', ?, ?, ?)");
                                $insQues->execute([$quizId, $qItem['q'], $qItem['points'], $qItem['exp'], $qIdx + 1]);
                                $quesId = $pdo->lastInsertId();

                                foreach ($qItem['answers'] as $aItem) {
                                    $insAns = $pdo->prepare("INSERT INTO quiz_answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
                                    $insAns->execute([$quesId, $aItem[0], $aItem[1]]);
                                }
                            }
                        }
                    }
                }
            }
        }

        // 5. Seed reviews mẫu cho các khóa học nếu chưa có
        $revCheck = (int) $pdo->query("SELECT COUNT(*) FROM course_reviews")->fetchColumn();
        if ($revCheck === 0) {
            $studentId = (int) $pdo->query("SELECT id FROM users WHERE role IN ('student', 'customer') LIMIT 1")->fetchColumn();
            if ($studentId) {
                $reviews = [
                    [1, 5, 'Khóa học cực kỳ chi tiết! Giảng viên dạy rất có tâm, giải thích mô hình MVC và bảo mật dễ hiểu.'],
                    [1, 5, 'Nhờ khóa học mà mình đã tự xây dựng được đồ án website hoàn chỉnh nộp tốt nghiệp.'],
                    [2, 5, 'Lộ trình Machine Learning rất logic, bài tập thực hành sát với yêu cầu tuyển dụng.'],
                    [3, 5, 'Phần React Hooks và Context API giảng rất sâu sắc, giải quyết triệt để vấn đề re-render.'],
                    [4, 5, 'Tuyệt vời! Khóa học hoàn toàn miễn phí nhưng chất lượng còn hơn cả nhiều khóa có phí.'],
                    [5, 5, 'Hướng dẫn Docker và CI/CD thực chiến, triển khai mượt mà lên server.'],
                ];
                $revStmt = $pdo->prepare("INSERT INTO course_reviews (course_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
                foreach ($reviews as $r) {
                    $revStmt->execute([$r[0], $studentId, $r[1], $r[2]]);
                }
                $logs[] = "Seeded course reviews";
            }
        }

        // 6. Seed thảo luận hỏi đáp mẫu trong bài học
        $discCheck = (int) $pdo->query("SELECT COUNT(*) FROM course_discussions")->fetchColumn();
        if ($discCheck === 0) {
            $studentId = (int) $pdo->query("SELECT id FROM users WHERE role IN ('student', 'customer') LIMIT 1")->fetchColumn();
            $firstLessonId = (int) $pdo->query("SELECT id FROM lessons LIMIT 1")->fetchColumn();
            if ($studentId && $firstLessonId) {
                $discStmt = $pdo->prepare("INSERT INTO course_discussions (lesson_id, user_id, content) VALUES (?, ?, ?)");
                $discStmt->execute([$firstLessonId, $studentId, 'Chào thầy, em đã hoàn thành xong bài 1 và cấu hình môi trường thành công! Em sẵn sàng sang bài tiếp theo.']);
                $logs[] = "Seeded lesson discussion";
            }
        }

        return $logs;
    }
}
