<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\Quiz;
use App\Models\Certificate;
use Tests\TestCase;

class LmsFlowTest extends TestCase
{
    public function testCourseCatalogAndCurriculum(): void
    {
        $courseModel = new Course();
        $courses = $courseModel->all(['status' => 'published']);
        $this->assertNotEmpty($courses, "Phải có ít nhất 1 khóa học công khai");

        $firstCourse = $courses[0];
        $this->assertArrayHasKey('title', $firstCourse);
        $this->assertArrayHasKey('price', $firstCourse);

        $curriculum = $courseModel->getCurriculum((int) $firstCourse['id']);
        $this->assertNotEmpty($curriculum, "Khóa học phải có ít nhất 1 chương học");
        $this->assertNotEmpty($curriculum[0]['lessons'], "Chương học phải có bài học");
    }

    public function testEnrollmentAndProgressTracking(): void
    {
        $enrollModel = new Enrollment();
        $progModel = new LessonProgress();
        $courseModel = new Course();

        $courses = $courseModel->all();
        $courseId = (int) $courses[0]['id'];
        $testUserId = 2; // Học viên test

        // 1. Ghi danh
        $ok = $enrollModel->enroll($testUserId, $courseId);
        $this->assertTrue($ok, "Ghi danh phải thành công");
        $this->assertTrue($enrollModel->isEnrolled($testUserId, $courseId), "Kiểm tra isEnrolled phải trả về true");

        // 2. Lấy bài học đầu tiên và đánh dấu hoàn thành
        $curriculum = $courseModel->getCurriculum($courseId, $testUserId);
        $lessonId = (int) $curriculum[0]['lessons'][0]['id'];

        $progSaved = $progModel->saveProgress($testUserId, $lessonId, 120, true);
        $this->assertTrue($progSaved, "Lưu tiến độ bài học phải thành công");

        // 3. Tính % tiến độ
        $percent = $enrollModel->updateProgress($testUserId, $courseId);
        $this->assertGreaterThan(0.00, $percent, "Tiến độ phải lớn hơn 0%");

        // Dọn dẹp
        \App\DB::connection()->exec("DELETE FROM enrollments WHERE user_id = {$testUserId} AND course_id = {$courseId}");
        \App\DB::connection()->exec("DELETE FROM lesson_progress WHERE user_id = {$testUserId} AND lesson_id = {$lessonId}");
    }

    public function testQuizTakingAndAutoGrading(): void
    {
        $quizModel = new Quiz();
        $quizzes = \App\DB::connection()->query("SELECT id FROM quizzes LIMIT 1")->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($quizzes)) {
            $this->markTestSkipped("Chưa có bài quiz trong DB");
        }

        $quizId = (int) $quizzes[0]['id'];
        $questions = $quizModel->getQuestions($quizId, true);
        $this->assertNotEmpty($questions, "Quiz phải có câu hỏi");

        // Chuẩn bị câu trả lời đúng
        $answers = [];
        foreach ($questions as $q) {
            foreach ($q['answers'] as $a) {
                if (!empty($a['is_correct'])) {
                    $answers[$q['id']] = $a['id'];
                    break;
                }
            }
        }

        $result = $quizModel->submitAttempt(2, $quizId, $answers);
        $this->assertTrue($result['is_passed'], "Trả lời đúng toàn bộ đáp án thì bài thi phải ĐẠT (is_passed = true)");
        $this->assertEquals(100.00, $result['score_percentage'], "Tỷ lệ điểm phải là 100%");
    }

    public function testCertificateLookupAndVerification(): void
    {
        $certModel = new Certificate();
        $code = 'CERT-2026-X8B9K2';

        $cert = $certModel->findByCode($code);
        $this->assertNotNull($cert, "Mã chứng chỉ mẫu phải tìm thấy trong CSDL");
        $this->assertEquals('valid', $cert['status'], "Trạng thái chứng chỉ phải là valid");
        $this->assertNotEmpty($cert['student_name'], "Chứng chỉ phải có tên học viên");
    }

    public function testCourseFiltersAndSearch(): void
    {
        $courseModel = new Course();

        // 1. Lọc theo từ khóa
        $pythonCourses = $courseModel->all(['q' => 'Python']);
        $this->assertNotEmpty($pythonCourses, "Tìm kiếm theo từ khóa 'Python' phải có kết quả");
        $this->assertStringContainsStringIgnoringCase('Python', $pythonCourses[0]['title']);

        // 2. Lọc theo cấp độ
        $beginnerCourses = $courseModel->all(['level' => 'beginner']);
        $this->assertNotEmpty($beginnerCourses, "Lọc cấp độ beginner phải có kết quả");

        // 3. Lọc khóa học miễn phí
        $freeCourses = $courseModel->all(['price' => 'free']);
        $this->assertNotEmpty($freeCourses, "Lọc khóa học miễn phí (0đ) phải có kết quả");
        $this->assertEquals(0.00, (float) $freeCourses[0]['sale_price'] > 0 ? (float)$freeCourses[0]['sale_price'] : (float)$freeCourses[0]['price']);
    }

    public function testCourseReviewsAndRatingCalculation(): void
    {
        $courseModel = new Course();
        $courses = $courseModel->all();
        $courseId = (int) $courses[0]['id'];
        $testUserId = 2;

        // Thêm đánh giá 5 sao
        $comment = "Khóa học rất hay và trực quan! Đã học được rất nhiều kiến thức bổ ích.";
        $ok = $courseModel->addReview($courseId, $testUserId, 5, $comment);
        $this->assertTrue($ok, "Gửi đánh giá khóa học phải thành công");

        // Lấy danh sách đánh giá
        $reviews = $courseModel->getReviews($courseId);
        $this->assertNotEmpty($reviews, "Danh sách đánh giá không được rỗng");
        $this->assertEquals($testUserId, $reviews[0]['user_id']);

        // Kiểm tra điểm trung bình
        $ratingData = $courseModel->getAverageRating($courseId);
        $this->assertGreaterThanOrEqual(1.0, $ratingData['avg_rating']);
        $this->assertGreaterThan(0, $ratingData['total_reviews']);
    }

    public function testLessonDiscussions(): void
    {
        $db = \App\DB::connection();
        $lesson = $db->query("SELECT id, section_id FROM lessons LIMIT 1")->fetch(\PDO::FETCH_ASSOC);
        $this->assertNotEmpty($lesson, "Phải có bài học trong DB");

        $lessonId = (int) $lesson['id'];
        $testUserId = 2;
        $content = "Em có câu hỏi về phần cài đặt môi trường trong bài học này.";

        $stmt = $db->prepare("INSERT INTO course_discussions (lesson_id, user_id, content) VALUES (?, ?, ?)");
        $saved = $stmt->execute([$lessonId, $testUserId, $content]);
        $this->assertTrue($saved, "Đăng bình luận hỏi đáp phải thành công");

        $checkStmt = $db->prepare("SELECT * FROM course_discussions WHERE lesson_id = ? ORDER BY id DESC LIMIT 1");
        $checkStmt->execute([$lessonId]);
        $row = $checkStmt->fetch(\PDO::FETCH_ASSOC);

        $this->assertNotEmpty($row);
        $this->assertEquals($content, $row['content']);
        $this->assertEquals($testUserId, $row['user_id']);
    }

    public function testPerAccountVideoWatchDurationCacheAndIsolation(): void
    {
        $progModel = new LessonProgress();
        $courseModel = new Course();

        $courses = $courseModel->all();
        $courseId = (int) $courses[0]['id'];
        $curriculum = $courseModel->getCurriculum($courseId);
        $lessonId = (int) $curriculum[0]['lessons'][0]['id'];

        $userA = 1;
        $userB = 2;

        // User A đã xem 450 giây
        $progModel->saveProgress($userA, $lessonId, 450, false);

        // User B đã xem 180 giây
        $progModel->saveProgress($userB, $lessonId, 180, false);

        // Truy vấn curriculum cho User A
        $currA = $courseModel->getCurriculum($courseId, $userA);
        $lessonA = $currA[0]['lessons'][0];
        $this->assertEquals(450, $lessonA['last_watch_second'], "User A phải có thời lượng xem lưu là 450s");

        // Truy vấn curriculum cho User B
        $currB = $courseModel->getCurriculum($courseId, $userB);
        $lessonB = $currB[0]['lessons'][0];
        $this->assertEquals(180, $lessonB['last_watch_second'], "User B phải có thời lượng xem lưu là 180s độc lập với User A");
        $this->assertNotEquals($lessonA['last_watch_second'], $lessonB['last_watch_second'], "Thời lượng xem của 2 tài khoản phải được cách ly hoàn toàn");
    }

    public function testPaidCoursePaymentAndOrderCreation(): void
    {
        $db = \App\DB::connection();
        $enrollModel = new Enrollment();
        $courseModel = new Course();

        $course = $courseModel->find(2);
        $this->assertNotNull($course, "Phải tìm thấy Course 2");

        $userId = 2;
        $orderCode = 'LMS' . date('ymd') . 'TESTPAY';
        $price = (float) ($course['sale_price'] ?? $course['price']);
        $paymentMethod = 'bank_transfer';

        // Tạo order
        $stmt = $db->prepare("INSERT INTO orders 
            (user_id, customer_name, phone, email, payment_method, payment_status, total_amount, status, order_code, created_at)
            VALUES (?, ?, ?, ?, ?, 'paid', ?, 'completed', ?, NOW())");
        $saved = $stmt->execute([
            $userId,
            'Test Student',
            '0901234567',
            'student@test.local',
            $paymentMethod,
            (int) $price,
            $orderCode
        ]);
        $this->assertTrue($saved, "Tạo đơn hàng thanh toán khóa học phải thành công");
        $orderId = (int) $db->lastInsertId();

        // Tạo order item
        $itemStmt = $db->prepare("INSERT INTO order_items (order_id, course_id, product_name, quantity, price) VALUES (?, ?, ?, 1, ?)");
        $itemSaved = $itemStmt->execute([$orderId, (int)$course['id'], $course['title'], (int)$price]);
        $this->assertTrue($itemSaved, "Tạo chi tiết đơn hàng khóa học phải thành công");

        // Ghi danh
        $enrolled = $enrollModel->enroll($userId, (int)$course['id']);
        $this->assertTrue($enrolled, "Ghi danh sau thanh toán phải thành công");
        $this->assertTrue($enrollModel->isEnrolled($userId, (int)$course['id']), "Kiểm tra isEnrolled phải trả về true");

        // Dọn dẹp test order & enrollment
        $db->exec("DELETE FROM enrollments WHERE user_id = {$userId} AND course_id = " . (int)$course['id']);
        $db->exec("DELETE FROM order_items WHERE order_id = {$orderId}");
        $db->exec("DELETE FROM orders WHERE id = {$orderId}");
    }

    public function testPaymentServiceAutoEnrollsCourseOnMarkPaid(): void
    {
        $db = \App\DB::connection();
        $enrollModel = new Enrollment();
        $userId = 2;
        $courseId = 3;

        // Xóa enrollment nếu có sẵn
        $db->exec("DELETE FROM enrollments WHERE user_id = {$userId} AND course_id = {$courseId}");
        $this->assertFalse($enrollModel->isEnrolled($userId, $courseId));

        // Tạo order pending
        $orderCode = \App\Models\Order::generateCode();
        $stmt = $db->prepare("INSERT INTO orders 
            (user_id, customer_name, phone, email, payment_method, payment_status, total_amount, status, order_code, created_at)
            VALUES (?, ?, ?, ?, 'bank_transfer', 'pending', 699000, 'pending', ?, NOW())");
        $stmt->execute([$userId, 'Test Student 2', '0912345678', 'khach@shop.test', $orderCode]);
        $orderId = (int) $db->lastInsertId();

        $itemStmt = $db->prepare("INSERT INTO order_items (order_id, course_id, product_name, quantity, price) VALUES (?, ?, ?, 1, ?)");
        $itemStmt->execute([$orderId, $courseId, 'DevOps Test Course', 699000]);

        // Đánh dấu thanh toán thành công qua PaymentService::markPaid
        $result = \App\PaymentService::markPaid($orderId, [], false);
        $this->assertTrue($result['ok']);

        // Xác nhận học viên đã được tự động kích hoạt ghi danh
        $this->assertTrue($enrollModel->isEnrolled($userId, $courseId), "Học viên phải được ghi danh tự động khi PaymentService::markPaid kích hoạt");

        // Dọn dẹp
        $db->exec("DELETE FROM enrollments WHERE user_id = {$userId} AND course_id = {$courseId}");
        $db->exec("DELETE FROM order_items WHERE order_id = {$orderId}");
        $db->exec("DELETE FROM orders WHERE id = {$orderId}");
    }

    public function testCashPaymentFlowForCourse(): void
    {
        $db = \App\DB::connection();
        $enrollModel = new Enrollment();
        $userId = 2;
        $courseId = 2;

        // Xóa enrollment nếu có sẵn
        $db->exec("DELETE FROM enrollments WHERE user_id = {$userId} AND course_id = {$courseId}");
        $this->assertFalse($enrollModel->isEnrolled($userId, $courseId));

        // 1. Tạo order thanh toán tiền mặt (pending)
        $orderCode = \App\Models\Order::generateCode();
        $stmt = $db->prepare("INSERT INTO orders 
            (user_id, customer_name, phone, email, payment_method, payment_status, total_amount, status, order_code, created_at)
            VALUES (?, ?, ?, ?, 'cash', 'pending', 499000, 'pending', ?, NOW())");
        $stmt->execute([$userId, 'Nguyễn Văn A', '0912345678', 'khach@shop.test', $orderCode]);
        $orderId = (int) $db->lastInsertId();

        $itemStmt = $db->prepare("INSERT INTO order_items (order_id, course_id, product_name, quantity, price) VALUES (?, ?, ?, 1, ?)");
        $itemStmt->execute([$orderId, $courseId, 'Python AI Course', 499000]);

        // 2. Kiểm tra: Khi chưa thanh toán tiền mặt thì học viên KHÔNG được vào học
        $this->assertFalse($enrollModel->isEnrolled($userId, $courseId), "Chưa thanh toán tiền mặt thì không được ghi danh");

        // 3. Thu ngân xác nhận thu tiền mặt thành công (markPaid)
        $result = \App\PaymentService::markPaid($orderId, [], false);
        $this->assertTrue($result['ok']);

        // 4. Kiểm tra: Sau khi thu ngân xác nhận thì học viên ĐÃ được kích hoạt vào học
        $this->assertTrue($enrollModel->isEnrolled($userId, $courseId), "Sau khi thu ngân xác nhận thì học viên được vào học");

        // Dọn dẹp
        $db->exec("DELETE FROM enrollments WHERE user_id = {$userId} AND course_id = {$courseId}");
        $db->exec("DELETE FROM order_items WHERE order_id = {$orderId}");
        $db->exec("DELETE FROM orders WHERE id = {$orderId}");
    }
}
