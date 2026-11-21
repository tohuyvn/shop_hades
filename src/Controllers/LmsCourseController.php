<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;

class LmsCourseController
{
    private Course $courseModel;
    private Enrollment $enrollmentModel;

    public function __construct()
    {
        $this->courseModel = new Course();
        $this->enrollmentModel = new Enrollment();
    }

    public function index(): void
    {
        $filters = [
            'q' => $_GET['q'] ?? null,
            'category_id' => $_GET['category'] ?? null,
            'level' => $_GET['level'] ?? null,
            'price_type' => $_GET['price'] ?? null,
            'status' => 'published',
        ];

        $courses = $this->courseModel->all($filters);

        // Fetch categories for filter dropdown
        $db = \App\DB::connection();
        $categories = $db->query("SELECT id, name FROM categories WHERE is_active = 1 OR is_active IS NULL ORDER BY name ASC")->fetchAll(\PDO::FETCH_ASSOC);

        view('client.courses.index', [
            'courses' => $courses,
            'categories' => $categories,
            'filters' => $filters,
            'title' => 'Danh sách Khóa học Trực tuyến',
        ]);
    }

    public function show(string|int $idOrSlug): void
    {
        $course = is_numeric($idOrSlug)
            ? $this->courseModel->find((int) $idOrSlug)
            : $this->courseModel->findBySlug((string) $idOrSlug);

        if (!$course) {
            http_response_code(404);
            flash('error', 'Không tìm thấy khóa học yêu cầu.');
            redirect('/courses');
        }

        $user = authUser();
        $userId = $user ? (int) $user['id'] : null;
        $isEnrolled = $userId ? $this->enrollmentModel->isEnrolled($userId, (int) $course['id']) : false;

        $curriculum = $this->courseModel->getCurriculum((int) $course['id'], $userId);
        $reviews = $this->courseModel->getReviews((int) $course['id']);
        $ratingStats = $this->courseModel->getAverageRating((int) $course['id']);

        $totalCourseSeconds = 0;
        $totalWatchedSeconds = 0;
        $totalLessonsCount = 0;
        $completedLessonsCount = 0;

        foreach ($curriculum as $sec) {
            foreach ($sec['lessons'] as $les) {
                $totalLessonsCount++;
                $dur = (int) ($les['duration_seconds'] ?? 0);
                $wat = (int) ($les['last_watch_second'] ?? 0);
                if (!empty($les['is_completed'])) {
                    $completedLessonsCount++;
                    if ($wat === 0 && $dur > 0) {
                        $wat = $dur;
                    }
                }
                $totalCourseSeconds += $dur;
                $totalWatchedSeconds += ($dur > 0) ? min($dur, $wat) : $wat;
            }
        }

        $watchPercent = 0;
        if ($totalCourseSeconds > 0) {
            $watchPercent = (int) round(($totalWatchedSeconds / $totalCourseSeconds) * 100);
        } elseif ($totalLessonsCount > 0) {
            $watchPercent = (int) round(($completedLessonsCount / $totalLessonsCount) * 100);
        }

        $formatTime = function (int $sec): string {
            $m = floor($sec / 60);
            $s = $sec % 60;
            return sprintf('%02d:%02d', $m, $s);
        };

        $watchStats = [
            'total_lessons' => $totalLessonsCount,
            'completed_lessons' => $completedLessonsCount,
            'total_seconds' => $totalCourseSeconds,
            'watched_seconds' => $totalWatchedSeconds,
            'watch_percent' => min(100, $watchPercent),
            'formatted_total' => $formatTime($totalCourseSeconds),
            'formatted_watched' => $formatTime($totalWatchedSeconds),
        ];

        view('client.courses.show', [
            'course' => $course,
            'curriculum' => $curriculum,
            'reviews' => $reviews,
            'ratingStats' => $ratingStats,
            'isEnrolled' => $isEnrolled,
            'watchStats' => $watchStats,
            'currentUser' => $user,
            'title' => $course['title'],
        ]);
    }

    public function enroll(int $courseId): void
    {
        $user = authUser();
        if (!$user) {
            flash('error', 'Vui lòng đăng nhập để đăng ký khóa học.');
            redirect('/login?redirect=' . urlencode('/courses/' . $courseId));
        }

        $course = $this->courseModel->find($courseId);
        if (!$course) {
            flash('error', 'Khóa học không tồn tại.');
            redirect('/courses');
        }

        $userId = (int) $user['id'];

        if ($this->enrollmentModel->isEnrolled($userId, $courseId)) {
            flash('info', 'Bạn đã đăng ký khóa học này rồi.');
            redirect('/learn/' . $courseId);
        }

        $effectivePrice = ($course['sale_price'] !== null && $course['sale_price'] >= 0)
            ? (float) $course['sale_price']
            : (float) $course['price'];

        // Khóa học miễn phí -> ghi danh ngay
        if ($effectivePrice <= 0) {
            $this->enrollmentModel->enroll($userId, $courseId);
            flash('success', 'Chúc mừng bạn đã đăng ký khóa học miễn phí thành công!');
            redirect('/learn/' . $courseId);
        }

        // Khóa học có phí -> chuyển đến trang thanh toán chọn phương thức (VietQR / VNPay / MoMo)
        redirect('/courses/' . $courseId . '/checkout');
    }

    public function checkout(int $courseId): void
    {
        $user = authUser();
        if (!$user) {
            flash('error', 'Vui lòng đăng nhập để thanh toán.');
            redirect('/login?redirect=' . urlencode("/courses/{$courseId}/checkout"));
        }

        $course = $this->courseModel->find($courseId);
        if (!$course) {
            flash('error', 'Khóa học không tồn tại.');
            redirect('/courses');
        }

        $userId = (int) $user['id'];
        if ($this->enrollmentModel->isEnrolled($userId, $courseId)) {
            flash('info', 'Bạn đã sở hữu khóa học này.');
            redirect('/learn/' . $courseId);
        }

        $price = ($course['sale_price'] !== null && $course['sale_price'] >= 0) ? (float) $course['sale_price'] : (float) $course['price'];
        $orderCode = \App\Models\Order::generateCode();

        $vietQrConfig = \App\Services\VietQrService::config();
        $vietQrInfo = \App\Services\VietQrService::sanitizeAddInfo('THANH TOAN ' . $orderCode);
        $vietQrUrl = \App\Services\VietQrService::imageUrl((int)$price, $vietQrInfo);

        view('client.courses.checkout', [
            'course' => $course,
            'price' => $price,
            'orderCode' => $orderCode,
            'user' => $user,
            'vietQrConfig' => $vietQrConfig,
            'vietQrUrl' => $vietQrUrl,
            'vietQrInfo' => $vietQrInfo,
            'banks' => config('vnpay.banks', []),
            'title' => 'Thanh toán Khóa học: ' . $course['title'],
        ]);
    }

    public function processPayment(int $courseId): void
    {
        $user = authUser();
        if (!$user) {
            flash('error', 'Vui lòng đăng nhập để hoàn tất thanh toán.');
            redirect('/login?redirect=' . urlencode("/courses/{$courseId}/checkout"));
        }

        $course = $this->courseModel->find($courseId);
        if (!$course) {
            flash('error', 'Khóa học không tồn tại.');
            redirect('/courses');
        }

        $userId = (int) $user['id'];
        $rawMethod = strtolower(trim($_POST['payment_method'] ?? 'vietqr'));
        $price = ($course['sale_price'] !== null && $course['sale_price'] >= 0) ? (float) $course['sale_price'] : (float) $course['price'];
        $orderCode = trim($_POST['order_code'] ?? '');
        if ($orderCode === '') {
            $orderCode = \App\Models\Order::generateCode();
        }

        $customerName = trim($_POST['customer_name'] ?? '') ?: ($user['name'] ?? 'Học viên LMS');
        $phone = trim($_POST['phone'] ?? '') ?: (!empty($user['phone']) ? $user['phone'] : '0912345678');
        $email = trim($_POST['email'] ?? '') ?: (!empty($user['email']) ? $user['email'] : 'hocvien@shop.test');

        $paymentMethod = match ($rawMethod) {
            'vnpay' => 'vnpay',
            'momo' => 'momo',
            'cash', 'direct', 'offline' => 'cash',
            default => 'bank_transfer',
        };

        $db = \App\DB::connection();
        $orderId = 0;

        try {
            $paymentStatus = 'pending';
            $orderStatus = 'pending';

            $stmt = $db->prepare("INSERT INTO orders 
                (user_id, customer_name, phone, email, payment_method, payment_status, total_amount, status, order_code, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $userId,
                $customerName,
                $phone,
                $email,
                $paymentMethod,
                $paymentStatus,
                (int) $price,
                $orderStatus,
                $orderCode
            ]);
            $orderId = (int) $db->lastInsertId();

            $itemStmt = $db->prepare("INSERT INTO order_items (order_id, course_id, product_name, quantity, price) VALUES (?, ?, ?, 1, ?)");
            $itemStmt->execute([$orderId, $courseId, $course['title'], (int) $price]);
        } catch (\Throwable $e) {
            error_log('LMS Payment order insert error: ' . $e->getMessage());
        }

        if ($orderId <= 0) {
            flash('error', 'Không thể khởi tạo đơn thanh toán. Vui lòng thử lại.');
            redirect("/courses/{$courseId}/checkout");
            return;
        }

        $_SESSION['last_order_id'] = $orderId;
        $_SESSION['lms_pending_course_id'] = $courseId;

        // Chuyển hướng sang cổng thanh toán tương ứng
        if ($paymentMethod === 'bank_transfer') {
            $_SESSION['pending_vietqr_order_id'] = $orderId;
            flash('info', "Vui lòng chuyển khoản đúng số tiền và nội dung để kích hoạt khóa học.");
            redirect('/payment/vietqr/pay?order_id=' . $orderId);
            return;
        }

        if ($paymentMethod === 'vnpay') {
            $bankCode = trim($_POST['bank'] ?? 'NCB');
            $_SESSION['pending_vnpay_order_id'] = $orderId;
            $_SESSION['vnpay_card'] = [
                'bank' => $bankCode,
                'bank_name' => vnpayBankName($bankCode),
                'card_number' => preg_replace('/\D+/', '', $_POST['card_number'] ?? '9704198526191432198'),
                'cardholder' => strtoupper(trim($_POST['card_holder'] ?? ($user['name'] ?? 'NGUYEN VAN A'))),
                'issue_date' => trim($_POST['issue_date'] ?? '07/15'),
            ];
            redirect('/payment/vnpay/pay');
            return;
        }

        if ($paymentMethod === 'momo') {
            $_SESSION['pending_momo_order_id'] = $orderId;
            redirect('/payment/momo/pay');
            return;
        }

        if ($paymentMethod === 'cash') {
            $_SESSION['last_order_id'] = $orderId;
            flash('info', "Đã tạo đơn đăng ký học #{$orderCode}! Vui lòng hoàn tất nộp học phí tiền mặt tại quầy tuyển sinh để mở khóa bài học.");
            redirect('/orders/success');
            return;
        }

        flash('error', 'Phương thức thanh toán không hợp lệ.');
        redirect("/courses/{$courseId}/checkout");
    }

    public function storeReview(int $courseId): void
    {
        $user = authUser();
        if (!$user) {
            flash('error', 'Vui lòng đăng nhập để đánh giá.');
            redirect('/login');
        }

        $rating = (int) ($_POST['rating'] ?? 5);
        $comment = trim($_POST['comment'] ?? '');

        if ($comment !== '') {
            $this->courseModel->addReview($courseId, (int) $user['id'], $rating, $comment);
            flash('success', 'Cảm ơn bạn đã để lại đánh giá hữu ích cho khóa học!');
        }

        redirect('/courses/' . $courseId . '#reviewsSection');
    }

    public function myCourses(): void
    {
        $user = authUser();
        if (!$user) {
            flash('error', 'Vui lòng đăng nhập để xem các khóa học của bạn.');
            redirect('/login?redirect=' . urlencode('/my-courses'));
        }

        $userId = (int) $user['id'];
        $enrolledCourses = $this->enrollmentModel->getUserCourses($userId);

        view('client.courses.my_courses', [
            'enrolledCourses' => $enrolledCourses,
            'title' => 'Khóa học của tôi',
        ]);
    }
}
