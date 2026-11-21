<?php

use App\Controllers\Admin\PaymentController;
use App\Controllers\Admin\ProductController as AdminProductController;
use App\Controllers\Admin\UserController as AdminUserController;
use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\MoMoController;
use App\Controllers\OrderController;
use App\Controllers\ProductController;
use App\Controllers\VnpayController;
use App\Router;

$router = new Router();
$GLOBALS['router'] = $router;

// Client
$router->get('/health', function () {
    $health = \App\DB::health();
    http_response_code($health['ok'] ? 200 : 503);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'ok' => $health['ok'],
        'app' => config('app_name', 'Shop Hades'),
        'time' => date(DATE_ATOM),
        'database' => $health,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
});

$router->get('/', fn() => (new App\Controllers\LmsCourseController())->index());
$router->get('/products/{id}', fn($id) => (new ProductController())->show((int) $id));
$router->get('/api/products/search', fn() => (new ProductController())->searchApi());
$router->post('/products/{id}/review', fn($id) => (new ProductController())->storeReview((int) $id));
$router->post('/reviews/{id}/like', fn($id) => (new ProductController())->likeReview((int) $id));
$router->post('/reviews/{id}/reply', fn($id) => (new ProductController())->replyReview((int) $id));

$router->get('/cart', fn() => (new CartController())->index());
$router->post('/cart/add/{id}', fn($id) => (new CartController())->add((int) $id));
$router->post('/cart/buy/{id}', fn($id) => (new CartController())->buyNow((int) $id));
$router->post('/cart/update', fn() => (new CartController())->update());
$router->post('/cart/remove/{key}', fn($key) => (new CartController())->remove((string) $key));

$router->get('/wishlist', fn() => (new \App\Controllers\WishlistController())->index());
$router->post('/wishlist/toggle/{id}', fn($id) => (new \App\Controllers\WishlistController())->toggle((int) $id));
$router->post('/wishlist/remove/{id}', fn($id) => (new \App\Controllers\WishlistController())->remove((int) $id));
$router->post('/wishlist/sync', fn() => (new \App\Controllers\WishlistController())->sync());

$router->get('/checkout', fn() => (new CheckoutController())->index());
$router->post('/checkout', fn() => (new CheckoutController())->placeOrder());

$router->get('/payment/vnpay/pay', fn() => (new VnpayController())->pay());
$router->post('/payment/vnpay/process', fn() => (new VnpayController())->process());
$router->get('/payment/vnpay/gateway', fn() => (new VnpayController())->gateway());
$router->get('/payment/vnpay/return', fn() => (new VnpayController())->return());
$router->get('/payment/momo/pay', fn() => (new MoMoController())->pay());
$router->post('/payment/momo/process', fn() => (new MoMoController())->process());
$router->get('/payment/momo/gateway', fn() => (new MoMoController())->gateway());
$router->get('/payment/momo/status', fn() => (new MoMoController())->status());
$router->get('/payment/momo/return', fn() => (new MoMoController())->return());
$router->post('/payment/momo/callback', fn() => (new MoMoController())->callback());
$router->get('/payment/vietqr/pay', fn() => (new App\Controllers\VietQrController())->pay());
$router->get('/payment/vietqr/status', fn() => (new App\Controllers\VietQrController())->status());
$router->post('/payment/vietqr/webhook', fn() => (new App\Controllers\VietQrController())->webhook());
$router->post('/payment/vietqr/simulate', fn() => (new App\Controllers\VietQrController())->simulatePaid());
$router->post('/payment/vietqr/confirm', fn() => (new App\Controllers\VietQrController())->confirmTransferred());
$router->get('/payment/vietqr', fn() => (new App\Controllers\VietQrController())->pay());
$router->post('/payment/cash/simulate-confirm', function() {
    $orderId = (int) ($_POST['order_id'] ?? $_SESSION['last_order_id'] ?? 0);
    $orderModel = new \App\Models\Order();
    $order = $orderId > 0 ? $orderModel->find($orderId) : null;
    if (!$order || ($order['payment_method'] ?? '') !== 'cash') {
        flash('error', 'Không tìm thấy đơn hàng tiền mặt.');
        redirect('/orders/success');
    }
    \App\PaymentService::markPaid($orderId);
    flash('success', 'Thu ngân đã xác nhận nhận tiền mặt thành công! Khóa học đã được mở khóa.');
    redirect('/orders/success');
});

$router->get('/orders', fn() => (new OrderController())->index());
$router->get('/orders/track', fn() => (new OrderController())->trackForm());
$router->post('/orders/track', fn() => (new OrderController())->trackSubmit());
$router->get('/orders/{id}', fn($id) => (new OrderController())->show((int) $id));
$router->get('/orders/{id}/invoice', fn($id) => (new OrderController())->invoice((int) $id));
$router->post('/orders/{id}/cancel', fn($id) => (new OrderController())->cancel((int) $id));
$router->post('/orders/{id}/refund', fn($id) => (new OrderController())->refund((int) $id));
$router->get('/orders/success', fn() => (new OrderController())->success());

$router->get('/compare', fn() => (new \App\Controllers\CompareController())->index());
$router->get('/sitemap.xml', fn() => (new ProductController())->sitemap());

// Trade-in
$router->get('/trade-in', fn() => (new App\Controllers\TradeInController())->index());
$router->post('/trade-in/submit', fn() => (new App\Controllers\TradeInController())->submit());

// Static Pages
$router->get('/pages/ve-chung-toi', fn() => (new App\Controllers\PageController())->about());
$router->get('/pages/chinh-sach-doi-tra', fn() => (new App\Controllers\PageController())->returnPolicy());
$router->get('/pages/bao-hanh', fn() => (new App\Controllers\PageController())->warranty());
$router->get('/pages/huong-dan-mua-hang', fn() => (new App\Controllers\PageController())->buyingGuide());

// Auth
$router->get('/login', fn() => (new AuthController())->showLogin());
$router->post('/login', fn() => (new AuthController())->login());
$router->get('/login/google', fn() => (new AuthController())->redirectToGoogle());
$router->get('/login/google/callback', fn() => (new AuthController())->handleGoogleCallback());
$router->get('/register', fn() => (new AuthController())->showRegister());
$router->post('/register', fn() => (new AuthController())->register());
$router->get('/verify-otp', fn() => (new AuthController())->showVerifyOtp());
$router->post('/verify-otp', fn() => (new AuthController())->verifyOtp());
$router->post('/resend-otp', fn() => (new AuthController())->resendOtp());
$router->post('/logout', fn() => (new AuthController())->logout());
$router->get('/profile', fn() => (new App\Controllers\ProfileController())->edit());
$router->post('/profile', fn() => (new App\Controllers\ProfileController())->update());
$router->post('/profile/addresses', fn() => (new App\Controllers\ProfileController())->storeAddress());
$router->post('/profile/addresses/{id}/delete', fn($id) => (new App\Controllers\ProfileController())->deleteAddress((int)$id));
$router->post('/profile/addresses/{id}/default', fn($id) => (new App\Controllers\ProfileController())->setDefaultAddress((int)$id));
$router->get('/wallet', fn() => (new App\Controllers\WalletController())->index());

// Forgot Password routes
$router->get('/forgot-password', fn() => (new AuthController())->showForgotPassword());
$router->post('/forgot-password', fn() => (new AuthController())->sendResetOtp());
$router->get('/forgot-password/verify', fn() => (new AuthController())->showVerifyResetOtp());
$router->post('/forgot-password/verify', fn() => (new AuthController())->verifyResetOtp());
$router->post('/forgot-password/resend', fn() => (new AuthController())->resendResetOtp());
$router->get('/reset-password', fn() => (new AuthController())->showResetPassword());
$router->get('/reset-password/{token}', fn($token) => (new AuthController())->showResetPassword($token));
$router->post('/reset-password', fn() => (new AuthController())->resetPassword());

// Admin
$router->get('/admin/login', fn() => (new AuthController())->showAdminLogin());
$router->post('/admin/login', fn() => (new AuthController())->adminLogin());
$router->get('/admin', admin(fn() => (new App\Controllers\Admin\DashboardController())->index()));
$router->get('/admin/dashboard', admin(fn() => (new App\Controllers\Admin\DashboardController())->index()));
$router->get('/admin/audit-logs', admin(fn() => (new App\Controllers\Admin\DashboardController())->auditLogs()));
$router->get('/admin/products', admin(fn() => (new AdminProductController())->index()));
$router->get('/admin/products/create', admin(fn() => (new AdminProductController())->createForm()));
$router->post('/admin/products', admin(fn() => (new AdminProductController())->store()));
$router->get('/admin/products/{id}/edit', admin(fn($id) => (new AdminProductController())->editForm((int) $id)));
$router->post('/admin/products/{id}/update', admin(fn($id) => (new AdminProductController())->update((int) $id)));
$router->post('/admin/products/{id}/delete', admin(fn($id) => (new AdminProductController())->destroy((int) $id)));
$router->post('/admin/products/bulk-action', admin(fn() => (new AdminProductController())->bulkAction()));
$router->post('/admin/products/{id}/variants', admin(fn($id) => (new AdminProductController())->addVariant((int) $id)));
$router->post('/admin/variants/{id}/update', admin(fn($id) => (new AdminProductController())->updateVariant((int) $id)));
$router->post('/admin/variants/{id}/delete', admin(fn($id) => (new AdminProductController())->deleteVariant((int) $id)));

// Category Management
$router->get('/admin/categories', admin(fn() => (new App\Controllers\Admin\CategoryController())->index()));
$router->get('/admin/categories/create', admin(fn() => (new App\Controllers\Admin\CategoryController())->createForm()));
$router->post('/admin/categories', admin(fn() => (new App\Controllers\Admin\CategoryController())->store()));
$router->get('/admin/categories/{id}/edit', admin(fn($id) => (new App\Controllers\Admin\CategoryController())->editForm((int) $id)));
$router->post('/admin/categories/{id}/update', admin(fn($id) => (new App\Controllers\Admin\CategoryController())->update((int) $id)));
$router->post('/admin/categories/{id}/delete', admin(fn($id) => (new App\Controllers\Admin\CategoryController())->destroy((int) $id)));


// Inventory Management
$router->get('/admin/inventory', admin(fn() => (new App\Controllers\Admin\InventoryController())->index()));
$router->get('/admin/inventory/export-csv', admin(fn() => (new App\Controllers\Admin\InventoryController())->exportCsv()));
$router->post('/admin/inventory/{id}/update-stock', admin(fn($id) => (new App\Controllers\Admin\InventoryController())->updateStock((int) $id)));

// Stores & IMEI inventory
$router->get('/admin/stores', admin(fn() => (new App\Controllers\Admin\StoreController())->index()));
$router->get('/admin/stores/create', admin(fn() => (new App\Controllers\Admin\StoreController())->createForm()));
$router->post('/admin/stores', admin(fn() => (new App\Controllers\Admin\StoreController())->store()));
$router->get('/admin/stores/{id}/edit', admin(fn($id) => (new App\Controllers\Admin\StoreController())->editForm((int) $id)));
$router->post('/admin/stores/{id}/update', admin(fn($id) => (new App\Controllers\Admin\StoreController())->update((int) $id)));
$router->post('/admin/stores/{id}/stock', admin(fn($id) => (new App\Controllers\Admin\StoreController())->updateStock((int) $id)));
$router->get('/admin/device-units', admin(fn() => (new App\Controllers\Admin\DeviceUnitController())->index()));
$router->post('/admin/device-units', admin(fn() => (new App\Controllers\Admin\DeviceUnitController())->store()));
$router->post('/admin/device-units/auto-seed', admin(fn() => (new App\Controllers\Admin\DeviceUnitController())->autoSeed()));
$router->post('/admin/device-units/{id}/delete', admin(fn($id) => (new App\Controllers\Admin\DeviceUnitController())->destroy((int) $id)));
$router->get('/admin/trade-ins', admin(fn() => (new App\Controllers\Admin\TradeInController())->index()));
$router->post('/admin/trade-ins/{id}/status', admin(fn($id) => (new App\Controllers\Admin\TradeInController())->updateStatus((int)$id)));

// Order Management
$router->get('/admin/orders', admin(fn() => (new App\Controllers\Admin\OrderController())->index()));
$router->get('/admin/orders/export-csv', admin(fn() => (new App\Controllers\Admin\OrderController())->exportCsv()));
$router->get('/admin/orders/{id}', admin(fn($id) => (new App\Controllers\Admin\OrderController())->show((int) $id)));
$router->post('/admin/orders/{id}/status', admin(fn($id) => (new App\Controllers\Admin\OrderController())->updateStatus((int) $id)));
$router->post('/admin/orders/{id}/preorder-deposit', admin(fn($id) => (new App\Controllers\Admin\OrderController())->updatePreorderDeposit((int) $id)));
$router->post('/admin/orders/{id}/preorder-complete', admin(fn($id) => (new App\Controllers\Admin\OrderController())->completePreorder((int) $id)));
$router->post('/admin/orders/{id}/issue-vat-invoice', admin(fn($id) => (new App\Controllers\Admin\OrderController())->issueVatInvoice((int) $id)));
$router->get('/admin/orders/{id}/invoice', admin(fn($id) => (new App\Controllers\Admin\OrderController())->invoice((int) $id)));
$router->post('/admin/orders/{id}/store', admin(fn($id) => (new App\Controllers\Admin\OrderController())->updateStore((int) $id)));
$router->post('/admin/orders/{id}/installment', admin(fn($id) => (new App\Controllers\Admin\OrderController())->updateInstallment((int) $id)));
$router->post('/admin/orders/{id}/confirm-bank-transfer', admin(fn($id) => (new App\Controllers\Admin\OrderController())->confirmBankTransfer((int) $id)));
$router->get('/admin/refunds', admin(fn() => (new App\Controllers\Admin\RefundController())->index()));
$router->post('/admin/refunds/{id}/approve', admin(fn($id) => (new App\Controllers\Admin\RefundController())->approve((int) $id)));
$router->post('/admin/refunds/{id}/reject', admin(fn($id) => (new App\Controllers\Admin\RefundController())->reject((int) $id)));

// Promotion Management (coupon codes)
$router->get('/admin/promotions', admin(fn() => (new App\Controllers\Admin\PromotionController())->index()));
$router->get('/admin/promotions/create', admin(fn() => (new App\Controllers\Admin\PromotionController())->createForm()));
$router->post('/admin/promotions', admin(fn() => (new App\Controllers\Admin\PromotionController())->store()));
$router->get('/admin/promotions/{id}/edit', admin(fn($id) => (new App\Controllers\Admin\PromotionController())->editForm((int) $id)));
$router->post('/admin/promotions/{id}/update', admin(fn($id) => (new App\Controllers\Admin\PromotionController())->update((int) $id)));
$router->post('/admin/promotions/{id}/delete', admin(fn($id) => (new App\Controllers\Admin\PromotionController())->destroy((int) $id)));

// Site-wide promotions
$router->get('/admin/site-promotions', admin(fn() => (new App\Controllers\Admin\SitePromotionController())->index()));
$router->get('/admin/site-promotions/create', admin(fn() => (new App\Controllers\Admin\SitePromotionController())->createForm()));
$router->post('/admin/site-promotions', admin(fn() => (new App\Controllers\Admin\SitePromotionController())->store()));
$router->get('/admin/site-promotions/{id}/edit', admin(fn($id) => (new App\Controllers\Admin\SitePromotionController())->editForm((int) $id)));
$router->post('/admin/site-promotions/{id}/update', admin(fn($id) => (new App\Controllers\Admin\SitePromotionController())->update((int) $id)));
$router->post('/admin/site-promotions/{id}/delete', admin(fn($id) => (new App\Controllers\Admin\SitePromotionController())->destroy((int) $id)));

$router->get('/admin/users', admin(fn() => (new AdminUserController())->index()));
$router->get('/admin/users/create', admin(fn() => (new AdminUserController())->createForm()));
$router->post('/admin/users', admin(fn() => (new AdminUserController())->store()));
$router->get('/admin/users/{id}/edit', admin(fn($id) => (new AdminUserController())->editForm((int) $id)));
$router->post('/admin/users/{id}/update', admin(fn($id) => (new AdminUserController())->update((int) $id)));
$router->post('/admin/users/{id}/delete', admin(fn($id) => (new AdminUserController())->destroy((int) $id)));
$router->post('/admin/users/{id}/reset-password', admin(fn($id) => (new AdminUserController())->requestPasswordReset((int) $id)));



$router->get('/admin/payments', admin(fn() => (new PaymentController())->index()));
$router->get('/admin/payments/{id}', admin(fn($id) => (new PaymentController())->show((int) $id)));
$router->get('/admin/profile', admin(fn() => (new App\Controllers\ProfileController())->adminEdit()));
$router->post('/admin/profile', admin(fn() => (new App\Controllers\ProfileController())->adminUpdate()));

// ==========================================
// RESTful API Routes (Chapter 5)
// ==========================================
$router->post('/api/register', fn() => (new App\Controllers\Api\AuthController())->register());
$router->post('/api/verify-register-otp', fn() => (new App\Controllers\Api\AuthController())->verifyRegisterOtp());
$router->post('/api/login', fn() => (new App\Controllers\Api\AuthController())->login());
$router->post('/api/verify-login-otp', fn() => (new App\Controllers\Api\AuthController())->verifyLoginOtp());
$router->post('/api/resend-otp', fn() => (new App\Controllers\Api\AuthController())->resendOtp());
$router->get('/api/profile', fn() => (new App\Controllers\Api\AuthController())->profile());

$router->post('/api/password/forgot', fn() => (new App\Controllers\Api\PasswordResetController())->forgot());
$router->post('/api/password/verify-otp', fn() => (new App\Controllers\Api\PasswordResetController())->verifyOtp());
$router->post('/api/password/reset', fn() => (new App\Controllers\Api\PasswordResetController())->reset());
$router->post('/api/password/resend', fn() => (new App\Controllers\Api\PasswordResetController())->resend());
$router->post('/api/admin/users/{id}/reset-password', fn($id) => (new App\Controllers\Api\PasswordResetController())->adminReset((int) $id));

$router->post('/api/orders/pay', fn() => (new App\Controllers\Api\PaymentController())->createPayment());
$router->get('/api/payment/vnpay/return', fn() => (new App\Controllers\Api\PaymentController())->paymentReturn());
$router->get('/api/payment/vnpay/ipn', fn() => (new App\Controllers\Api\PaymentController())->paymentIpn());
$router->get('/api/promotions/check', fn() => (new App\Controllers\Api\PromotionApiController())->check());
$router->post('/api/chatbot', fn() => (new App\Controllers\Api\ChatbotController())->chat());

// Support Chat API (client side)
$router->get('/api/support/messages', fn() => (new App\Controllers\Api\SupportChatController())->getMessages());
$router->post('/api/support/send',    fn() => (new App\Controllers\Api\SupportChatController())->sendMessage());
$router->get('/api/support/unread',   fn() => (new App\Controllers\Api\SupportChatController())->unreadCount());
$router->get('/api/support/sse',      fn() => (new App\Controllers\Api\SupportChatController())->sse());
$router->get('/api/orders/{id}/tracking',  fn($id) => (new App\Controllers\OrderTrackingController())->snapshot((int)$id));
$router->get('/api/orders/{id}/sse',  fn($id) => (new App\Controllers\OrderTrackingController())->sse((int)$id));
$router->post('/api/support/typing',  fn() => (new App\Controllers\Api\SupportChatController())->typing());

// Admin: Messages & Reviews
$router->get('/admin/messages',                      admin(fn() => (new App\Controllers\Admin\MessageController())->index()));
$router->get('/admin/messages/reviews',              admin(fn() => (new App\Controllers\Admin\MessageController())->reviews()));
$router->post('/admin/messages/reviews/{id}/delete', admin(fn($id) => (new App\Controllers\Admin\MessageController())->deleteReview((int) $id)));
$router->get('/admin/messages/{sid}',                admin(fn($sid) => (new App\Controllers\Admin\MessageController())->show($sid)));
$router->post('/admin/messages/{sid}/reply',         admin(fn($sid) => (new App\Controllers\Admin\MessageController())->reply($sid)));
$router->get('/admin/messages/{sid}/poll',           admin(fn($sid) => (new App\Controllers\Admin\MessageController())->poll($sid)));
$router->post('/admin/messages/{sid}/delete',        admin(fn($sid) => (new App\Controllers\Admin\MessageController())->deleteConversation($sid)));
$router->get('/admin/messages/{sid}/sse',            admin(fn($sid) => (new App\Controllers\Admin\MessageController())->sse($sid)));
$router->post('/admin/messages/{sid}/typing',        admin(fn($sid) => (new App\Controllers\Admin\MessageController())->typing($sid)));

// Shipper Routes
$router->get('/shipper/orders', fn() => (new App\Controllers\ShipperController())->orders());
$router->post('/shipper/orders/{id}/accept', fn($id) => (new App\Controllers\ShipperController())->acceptOrder((int)$id));
$router->get('/shipper/orders/{id}/track', fn($id) => (new App\Controllers\ShipperController())->track((int)$id));
$router->post('/shipper/orders/{id}/complete', fn($id) => (new App\Controllers\ShipperController())->completeOrder((int)$id));
$router->post('/api/shipper/location', fn() => (new App\Controllers\ShipperController())->updateLocation());

// Warranty Routes
$router->get('/warranty', fn() => (new App\Controllers\WarrantyController())->index());
$router->post('/warranty/lookup', fn() => (new App\Controllers\WarrantyController())->lookup());
$router->post('/warranty/claim', fn() => (new App\Controllers\WarrantyController())->claim());

// Admin Warranty Routes
$router->get('/admin/warranties', admin(fn() => (new App\Controllers\Admin\WarrantyController())->index()));
$router->post('/admin/warranties/activate', admin(fn() => (new App\Controllers\Admin\WarrantyController())->activate()));
$router->get('/admin/warranties/claims', admin(fn() => (new App\Controllers\Admin\WarrantyController())->claims()));
$router->post('/admin/warranties/claims/{id}/update', admin(fn($id) => (new App\Controllers\Admin\WarrantyController())->updateClaim((int)$id)));

// Admin 2FA
$router->get('/admin/2fa', fn() => (new AuthController())->showAdmin2fa());
$router->post('/admin/2fa', fn() => (new AuthController())->verifyAdmin2fa());
$router->post('/admin/profile/2fa/enable', admin(fn() => (new App\Controllers\ProfileController())->enable2fa()));
$router->post('/admin/profile/2fa/disable', admin(fn() => (new App\Controllers\ProfileController())->disable2fa()));
$router->post('/admin/profile/2fa/confirm', admin(fn() => (new App\Controllers\ProfileController())->confirm2fa()));

// LMS Setup & Migration
$router->get('/api/lms/setup', function () {
    header('Content-Type: application/json; charset=UTF-8');
    try {
        $logs = \App\LmsMigrator::run();
        $seedLogs = \App\Models\LmsCourseSeeder::seed();
        $pdo = \App\DB::connection();
        $courses = $pdo->query("SELECT id, title, thumbnail, price, sale_price FROM courses")->fetchAll(\PDO::FETCH_ASSOC);

        echo json_encode([
            'ok' => true,
            'message' => 'LMS Database migration and course seeding executed successfully!',
            'migration_logs' => $logs,
            'seed_logs' => $seedLogs,
            'courses' => $courses,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (\Throwable $e) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    }
});

// ==========================================
// LMS CLIENT ROUTES (Học viên)
// ==========================================
$router->get('/courses', fn() => (new App\Controllers\LmsCourseController())->index());
$router->get('/courses/{slug}', fn($slug) => (new App\Controllers\LmsCourseController())->show($slug));
$router->get('/courses/{id}/enroll', fn($id) => (new App\Controllers\LmsCourseController())->enroll((int)$id));
$router->post('/courses/{id}/enroll', fn($id) => (new App\Controllers\LmsCourseController())->enroll((int)$id));
$router->get('/courses/{id}/checkout', fn($id) => (new App\Controllers\LmsCourseController())->checkout((int)$id));
$router->get('/courses/{id}/payment', fn($id) => redirect('/courses/' . (int)$id . '/checkout'));
$router->post('/courses/{id}/payment', fn($id) => (new App\Controllers\LmsCourseController())->processPayment((int)$id));
$router->post('/courses/{id}/review', fn($id) => (new App\Controllers\LmsCourseController())->storeReview((int)$id));
$router->get('/my-courses', fn() => (new App\Controllers\LmsCourseController())->myCourses());

// Classroom, Progress & Discussion
$router->get('/learn/{courseId}', fn($courseId) => (new App\Controllers\ClassroomController())->learn((int)$courseId));
$router->get('/learn/{courseId}/lesson/{lessonId}', fn($courseId, $lessonId) => (new App\Controllers\ClassroomController())->learn((int)$courseId, (int)$lessonId));
$router->post('/api/progress/update', fn() => (new App\Controllers\ClassroomController())->updateProgress());
$router->get('/learn/{courseId}/download/{materialId}', fn($courseId, $materialId) => (new App\Controllers\ClassroomController())->downloadMaterial((int)$courseId, (int)$materialId));
$router->post('/learn/{courseId}/lesson/{lessonId}/discussion', fn($courseId, $lessonId) => (new App\Controllers\ClassroomController())->postDiscussion((int)$courseId, (int)$lessonId));
$router->post('/api/lessons/{lessonId}/notes', fn($lessonId) => (new App\Controllers\ClassroomController())->saveNote((int)$lessonId));
$router->post('/api/notes/{noteId}/delete', fn($noteId) => (new App\Controllers\ClassroomController())->deleteNote((int)$noteId));
$router->delete('/api/notes/{noteId}', fn($noteId) => (new App\Controllers\ClassroomController())->deleteNote((int)$noteId));

// Quiz
$router->get('/quiz/{id}', fn($id) => (new App\Controllers\QuizController())->show((int)$id));
$router->post('/quiz/{id}/submit', fn($id) => (new App\Controllers\QuizController())->submit((int)$id));

// Certificates & Public Verification
$router->get('/certificates/{code}', fn($code) => (new App\Controllers\CertificateController())->show($code));
$router->get('/verify-certificate', fn() => (new App\Controllers\CertificateController())->verify());
$router->post('/verify-certificate', fn() => (new App\Controllers\CertificateController())->verify());

// ==========================================
// LMS ADMIN ROUTES (Quản trị viên)
// ==========================================
$router->get('/admin/lms', admin(fn() => (new App\Controllers\Admin\LmsController())->dashboard()));
$router->get('/admin/lms/dashboard', admin(fn() => (new App\Controllers\Admin\LmsController())->dashboard()));
$router->get('/admin/lms/courses', admin(fn() => (new App\Controllers\Admin\LmsController())->courses()));
$router->get('/admin/lms/courses/create', admin(fn() => (new App\Controllers\Admin\LmsController())->createCourse()));
$router->post('/admin/lms/courses/store', admin(fn() => (new App\Controllers\Admin\LmsController())->storeCourse()));
$router->get('/admin/lms/courses/{id}/edit', admin(fn($id) => (new App\Controllers\Admin\LmsController())->editCourse((int)$id)));
$router->post('/admin/lms/courses/{id}/update', admin(fn($id) => (new App\Controllers\Admin\LmsController())->updateCourse((int)$id)));
$router->post('/admin/lms/courses/{id}/delete', admin(fn($id) => (new App\Controllers\Admin\LmsController())->deleteCourse((int)$id)));
$router->get('/admin/lms/courses/{id}/curriculum', admin(fn($id) => (new App\Controllers\Admin\LmsController())->curriculum((int)$id)));
$router->post('/admin/lms/courses/{id}/sections', admin(fn($id) => (new App\Controllers\Admin\LmsController())->storeSection((int)$id)));
$router->post('/admin/lms/courses/{id}/sections/{sid}/delete', admin(fn($id, $sid) => (new App\Controllers\Admin\LmsController())->deleteSection((int)$id, (int)$sid)));
$router->post('/admin/lms/courses/{id}/lessons', admin(fn($id) => (new App\Controllers\Admin\LmsController())->storeLesson((int)$id)));
$router->post('/admin/lms/courses/{id}/lessons/{lid}/delete', admin(fn($id, $lid) => (new App\Controllers\Admin\LmsController())->deleteLesson((int)$id, (int)$lid)));
$router->post('/admin/lms/courses/{id}/lessons/{lid}/materials', admin(fn($id, $lid) => (new App\Controllers\Admin\LmsController())->storeMaterial((int)$id, (int)$lid)));

// Quizzes & Questions
$router->get('/admin/lms/quizzes', admin(fn() => (new App\Controllers\Admin\LmsController())->quizzes()));
$router->get('/admin/lms/quizzes/{id}/questions', admin(fn($id) => (new App\Controllers\Admin\LmsController())->quizQuestions((int)$id)));
$router->post('/admin/lms/quizzes/{id}/questions', admin(fn($id) => (new App\Controllers\Admin\LmsController())->storeQuestion((int)$id)));
$router->post('/admin/lms/quizzes/{id}/questions/{qid}/delete', admin(fn($id, $qid) => (new App\Controllers\Admin\LmsController())->deleteQuestion((int)$id, (int)$qid)));

// Quiz Results
$router->get('/admin/lms/quiz-results', admin(fn() => (new App\Controllers\Admin\LmsController())->quizResults()));
$router->get('/admin/lms/quiz-results/{id}', admin(fn($id) => (new App\Controllers\Admin\LmsController())->quizResultDetail((int)$id)));

// Users & Certificates
$router->get('/admin/lms/students', admin(fn() => (new App\Controllers\Admin\LmsController())->students()));
$router->get('/admin/lms/instructors', admin(fn() => (new App\Controllers\Admin\LmsController())->instructors()));
$router->get('/admin/lms/certificates', admin(fn() => (new App\Controllers\Admin\LmsController())->certificates()));
$router->post('/admin/lms/certificates/{id}/toggle', admin(fn($id) => (new App\Controllers\Admin\LmsController())->toggleCertificate((int)$id)));
$router->post('/admin/lms/certificates/issue', admin(fn() => (new App\Controllers\Admin\LmsController())->issueCertificateManual()));


