# 🎓 Hades Academy LMS — Hệ Thống Đào Tạo & Khóa Học Trực Tuyến

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777bb4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479a1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.3-7952b3?style=flat-square&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](LICENSE)

**Hades Academy LMS** là nền tảng quản lý đào tạo & học tập trực tuyến (Learning Management System) toàn diện, hiện đại. Dự án được xây dựng trên nền tảng **PHP MVC thuần** tối ưu hóa hiệu năng, tích hợp giao diện **Refract Glassmorphism (Glassy Dashboard v31)** sang trọng, hỗ trợ đa cổng thanh toán (VNPay, MoMo, VietQR MB Bank) và phòng học trực tuyến đầy đủ tính năng.

---

## 🌟 Tính Năng Nổi Bật

### 1. Phân hệ Học viên (Learner / Student)
- **Catalog Khóa Học**: Tìm kiếm, lọc theo chuyên ngành (Lập trình, AI & Data, UI/UX, Cloud DevOps, Marketing,...), cấp độ và học phí.
- **Phòng Học Trực Tuyến (LMS Player)**:
  - Video Player đồng bộ tiến độ học từng giây, tự động đánh dấu bài học hoàn thành.
  - **Ghi chú thông minh (Video Timestamped Notes)**: Gắn ghi chú trực tiếp theo mốc thời gian của bài giảng, click là tua video đến đoạn tương ứng.
  - **Hỏi đáp & Thảo luận (Q&A)**: Trao đổi trực tiếp dưới bài giảng cùng giảng viên và bạn học.
  - **Nhắc nhở học tập (Checkpoint 15 Phút)**: Cơ chế Spaced Repetition kích hoạt pop-up nhắc làm bài kiểm tra củng cố kiến thức sau mỗi 15 phút học.
  - **Tài liệu đính kèm**: Xem và tải Slide bài giảng, mã nguồn, tài liệu PDF.
- **Hệ Thống Kiểm Tra Trắc Nghiệm (Quiz Engine)**:
  - Bộ đếm ngược thời gian làm bài, trộn ngẫu nhiên câu hỏi.
  - Tự động chấm điểm, hiển thị đáp án và giải thích chi tiết.
- **Chứng chỉ Tự Động (QR Verified Certificate)**:
  - Cấp chứng chỉ điện tử khi hoàn thành 100% khóa học.
  - Mã QR động chống giả mạo, cho phép quét tra cứu công khai trực tiếp.

### 2. Cổng Thanh Toán Đa Kênh (Fintech Integrations)
- **VNPay Sandbox**: Giao diện Refract Glassy thẻ ATM mô phỏng Napas 247, xác thực OTP 3D-Secure có timer đếm ngược và nút tự điền OTP test.
- **MoMo Sandbox**: Quét mã QR code thanh toán hoặc mở ứng dụng MoMo thử nghiệm.
- **VietQR MB Bank**: Tự động sinh mã VietQR chuẩn NAPAS 247, tự động kiểm tra trạng thái kích hoạt khóa học tức thì.

### 3. Phân hệ Giảng viên & Quản trị (Instructor / Admin)
- **Curriculum Builder**: Kéo thả, xây dựng giáo trình khóa học theo Chương (Sections) và Bài học (Lessons).
- **Quản lý Video & Tài liệu**: Hỗ trợ streaming video từ Cloud/Local, cấu hình bài học thử (`is_preview`).
- **Ngân hàng Câu hỏi & Đề thi**: Soạn thảo trắc nghiệm nhiều lựa chọn, điểm sàn qua môn (Passing Score).
- **Thống kê & Doanh thu**: Báo cáo học viên ghi danh, tỷ lệ hoàn thành, đơn hàng thanh toán.

---

## 📌 Yêu Cầu Môi Trường

- **PHP**: `>= 8.2` (yêu cầu các extension: `pdo_mysql`, `curl`, `mbstring`, `openssl`, `gd`)
- **Web Server**: Apache / Nginx hoặc PHP Built-in Server có worker đa luồng
- **MySQL / MariaDB**: `>= 8.0`
- **Composer**: `>= 2.0`

---

## 🛠️ Cài Đặt & Cấu Hình

### Bước 1: Clone dự án & Cài đặt dependencies
```bash
git clone git@github.com:tohuyvn/quan_li_khoa_hoc.git
cd quan_li_khoa_hoc/btvn
composer install
```

### Bước 2: Cấu hình Môi trường `.env`
Sao chép file cấu hình mẫu và cập nhật thông số kết nối Database:
```bash
cp .env.example .env
```
Chỉnh sửa các biến môi trường trong `.env`:
```env
APP_NAME="Hades Academy"
APP_ENV=local
APP_BASE_URL=http://127.0.0.1:8000

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shop_hades
DB_USERNAME=root
DB_PASSWORD=
```

### Bước 3: Khởi tạo Cơ sở Dữ liệu
Import schema hoàn chỉnh của hệ thống LMS:
```bash
mysql -u root -p shop_hades < ../lms_complete_backup.sql
```
*(Hoặc import file `lms_complete_backup.sql` trực tiếp từ giao diện phpMyAdmin).*

---

## 🚀 Khởi Chạy Hệ Thống

> [!IMPORTANT]
> **Không chạy lệnh `php -S 127.0.0.1:8000` đơn luồng thủ công!**  
> Hệ thống sử dụng Server-Sent Events (SSE) để theo dõi tiến độ và giao tiếp real-time. Chạy đơn luồng sẽ khiến server bị nghẽn/treo. Hãy sử dụng các script đa luồng bên dưới:

### Cách 1: Chạy Local Đa Luồng (Khuyên Dùng)
Sử dụng script khởi chạy với **10 luồng xử lý đồng thời (`PHP_CLI_SERVER_WORKERS=10`)**:
```bash
chmod +x start.sh
./start.sh
```
- **Trang chủ LMS:** [http://127.0.0.1:8000/](http://127.0.0.1:8000/)
- **Đăng nhập Quản trị:** [http://127.0.0.1:8000/admin/login](http://127.0.0.1:8000/admin/login)
- **Kiểm tra sức khỏe hệ thống:** [http://127.0.0.1:8000/health](http://127.0.0.1:8000/health)

---

### Cách 2: Khởi Chạy Public (Kiểm Thử Webhook VNPay / MoMo)
Để nhận IPN/Callback thanh toán từ Internet, dự án hỗ trợ các tunnel tự động:

#### Phương án A: Cloudflare Tunnel (Miễn phí & Tự động cập nhật `.env`)
```bash
chmod +x start-cloudflare.sh
./start-cloudflare.sh
```
*Script sẽ tự động khởi tạo URL `https://xxxx.trycloudflare.com`, backup `.env`, điền URL callback thanh toán và tự hoàn tác khi bạn tắt (`Ctrl + C`).*

#### Phương án B: ngrok (Domain cố định)
```bash
chmod +x start-public.sh
./start-public.sh
```

---

### Cách 3: Chạy Toàn Bộ Qua Docker & Docker Compose
Hệ thống đóng gói sẵn PHP-FPM 8.2, Nginx, MySQL 8.0 và Cloudflare Tunnel:

```bash
# Khởi động toàn bộ container
sudo docker compose up -d --build

# Chạy migration dữ liệu (trong lần đầu)
sudo docker compose exec app php scripts/migrate.php

# Xem public URL tạo bởi tunnel
sudo docker compose logs tunnel | grep -Eo 'https://[-a-z0-9]+\.trycloudflare\.com' | tail -n 1
```

Để tắt hệ thống Docker:
```bash
sudo docker compose down
```

---

## 🧪 Kiểm Thử Hệ Thống (Testing)

Dự án có bộ test tự động kiểm tra toàn bộ luồng nghiệp vụ LMS (Khóa học, Ghi danh, Thanh toán, Tiến độ học, Quiz, Cấp chứng chỉ):

```bash
# Chạy PHPUnit
vendor/bin/phpunit
```

---

## 🔑 Tài Khoản Mẫu Trải Nghiệm (Demo Accounts)

| Vai trò | Email đăng nhập | Mật khẩu | Quyền hạn |
| :--- | :--- | :--- | :--- |
| **Quản trị viên (Admin)** | `admin@shop.test` | `admin123` | Quản trị toàn hệ thống, khóa học, doanh thu |
| **Giảng viên (Instructor)** | `instructor@shop.test` | `instructor123` | Soạn giáo trình, tạo đề thi, quản lý bài giảng |
| **Học viên (Student)** | `user@shop.test` | `user123` | Mua khóa học, xem bài giảng, làm bài test |

---

## 💳 Thông Tin Thẻ Test Sandbox

### Cổng VNPay Sandbox
- **Ngân hàng:** `NCB`
- **Số thẻ:** `9704198526191432`
- **Tên chủ thẻ:** `NGUYEN VAN A`
- **Ngày phát hành:** `07/15`
- **Mã OTP:** `123456`

---

## 📂 Cấu Trúc Dự Án (Architecture)

```text
btvn/
├── config/              # File cấu hình ứng dụng, DB, cổng thanh toán
├── docker/              # Cấu hình Nginx, PHP-FPM và Dockerfile
├── public/              # Document Root công khai (CSS, JS, Images, index.php)
│   ├── css/             # Glassy theme v31, Bootstrap, Refract custom styles
│   └── index.php        # Entry point duy nhất điều hướng Request
├── routes/              # Khai báo định tuyến (web.php, api.php)
├── scripts/             # Script CLI migration, import database
├── src/                 # Mã nguồn backend (MVC Pattern)
│   ├── Controllers/     # Điều hướng logic (Admin, Client, LMS, API)
│   ├── Middlewares/     # Bộ lọc phân quyền (Auth, Admin, CSRF, RateLimit)
│   ├── Models/          # Tương tác CSDL (Course, Lesson, Quiz, Order, User)
│   └── Services/        # Dịch vụ tích hợp (VNPay, MoMo, VietQR, Certificate)
├── tests/               # Bộ kiểm thử tự động Feature & Unit Test
├── views/               # Giao diện Blade-style PHP Views
│   ├── admin/           # Phân hệ quản trị (LMS, Doanh thu, User)
│   ├── client/          # Phân hệ học viên (Catalog, Checkout, Classroom)
│   └── layouts/         # Layout chính (Refract Glassy Sidebar)
├── .env.example         # File cấu hình môi trường mẫu
├── composer.json        # Định nghĩa thư viện & PSR-4 Autoloading
└── start.sh             # Script khởi động server local tối ưu đa luồng
```

---

## 📄 Bản Quyền & Giấy Phép
Phát triển và bảo trì bởi đội ngũ **Hades Academy**. Phát hành theo giấy phép [MIT License](LICENSE).
