<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? config('app_name', 'Hades Academy')) ?> - Hệ Thống Đào Tạo & Khóa Học Trực Tuyến</title>
    <meta name="description" content="<?= e($metaDescription ?? 'Hades Academy - Nền tảng đào tạo trực tuyến chuẩn LMS, khóa học Lập trình, AI, Data và Cloud chất lượng cao.') ?>">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <meta name="csrf" content="<?= e(csrf_token()) ?>">
    <meta name="auth-user" content="<?= authUser() ? '1' : '0' ?>">
    
    <!-- Open Graph / Twitter -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e('http://' . ($_SERVER['HTTP_HOST'] ?? '127.0.0.1:8000') . ($_SERVER['REQUEST_URI'] ?? '/')) ?>">
    <meta property="og:title" content="<?= e($ogTitle ?? ($title ?? config('app_name'))) ?>">
    <meta property="og:description" content="<?= e($ogDescription ?? ($metaDescription ?? 'Hades Academy - Nền tảng đào tạo trực tuyến chuẩn LMS.')) ?>">
    <meta property="og:image" content="<?= e($ogImage ?? 'http://' . ($_SERVER['HTTP_HOST'] ?? '127.0.0.1:8000') . '/image/logo.webp') ?>">

    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/css/glassy-theme.css" rel="stylesheet">
</head>
<body class="refract-dashboard-body">

<?php
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$isActive = fn($path) => str_starts_with($uri, $path) ? 'active' : '';

if (!function_exists('getCategoryIcon')) {
    function getCategoryIcon(string $name): string {
        $nameLower = strtolower($name);
        if (str_contains($nameLower, 'lập trình') || str_contains($nameLower, 'phần mềm') || str_contains($nameLower, 'code')) return 'bi-code-slash';
        if (str_contains($nameLower, 'dữ liệu') || str_contains($nameLower, 'ai') || str_contains($nameLower, 'data')) return 'bi-cpu';
        if (str_contains($nameLower, 'thiết kế') || str_contains($nameLower, 'ui/ux') || str_contains($nameLower, 'đồ họa')) return 'bi-palette';
        if (str_contains($nameLower, 'devops') || str_contains($nameLower, 'cloud') || str_contains($nameLower, 'hệ thống')) return 'bi-cloud-check';
        if (str_contains($nameLower, 'marketing') || str_contains($nameLower, 'kinh doanh')) return 'bi-graph-up-arrow';
        if (str_contains($nameLower, 'ngoại ngữ') || str_contains($nameLower, 'kỹ năng')) return 'bi-translate';
        return 'bi-mortarboard';
    }
}

// Lấy danh mục nếu chưa được truyền từ controller
if (empty($categories)) {
    try {
        $categories = \App\DB::connection()->query("SELECT id, name, slug FROM categories ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $e) {
        $categories = [];
    }
}
?>

<div class="refract-app-container">
    <!-- LEFT GLASS SIDEBAR (Thay thế toàn bộ Header cũ) -->
    <aside class="refract-sidebar">
        <!-- Brand / Logo -->
        <div class="refract-brand d-flex align-items-center justify-content-between mb-4">
            <a href="/" class="d-flex align-items-center gap-2 text-decoration-none">
                <div class="brand-prism-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div class="brand-text">
                    <span class="brand-title"><?= e(config('app_name', 'Hades')) ?></span>
                    <span class="badge-crm">LMS</span>
                </div>
            </a>
            <span class="badge rounded-pill bg-success bg-opacity-25 text-success small px-2 py-1">2026</span>
        </div>

        <!-- Section: Học tập & Khám phá -->
        <div class="sidebar-section">
            <div class="section-label">HỌC TẬP & ĐÀO TẠO</div>
            <a href="/courses" class="sidebar-nav-item <?= ($uri === '/' || $uri === '/courses' || str_starts_with($uri, '/courses')) ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2"></i>
                <span>Tất cả Khóa học</span>
            </a>
            <a href="/my-courses" class="sidebar-nav-item <?= $isActive('/my-courses') ?>">
                <i class="bi bi-journal-bookmark-fill"></i>
                <span>Khóa học của tôi</span>
            </a>
            <a href="/verify-certificate" class="sidebar-nav-item <?= $isActive('/verify-certificate') ?>">
                <i class="bi bi-patch-check"></i>
                <span>Tra cứu chứng chỉ QR</span>
            </a>
        </div>

        <!-- Section: Danh mục ngành nghề -->
        <div class="sidebar-section">
            <div class="section-label">CHUYÊN NGÀNH HỌC</div>
            <?php foreach (($categories ?? []) as $cat): ?>
                <a href="/courses?category=<?= (int)$cat['id'] ?>" class="sidebar-nav-item <?= ((string)($_GET['category'] ?? '') === (string)$cat['id']) ? 'active' : '' ?>">
                    <i class="bi <?= getCategoryIcon($cat['name']) ?>"></i>
                    <span class="text-truncate"><?= e($cat['name']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Section: Liên hệ & Hỗ trợ -->
        <div class="sidebar-section">
            <div class="section-label">TRỢ GIÚP & HỖ TRỢ</div>
            <a href="tel:<?= e(config('hotline', '18001234')) ?>" class="sidebar-nav-item">
                <i class="bi bi-headset"></i>
                <span>Hotline: <?= e(config('hotline', '1800 1234')) ?></span>
            </a>
        </div>

        <!-- User Profile Pill at Sidebar Bottom -->
        <div class="sidebar-user-pill mt-auto pt-3">
            <?php if ($user = authUser()): ?>
                <div class="d-flex align-items-center gap-2">
                    <div class="user-avatar-circle">
                        <?= strtoupper(substr($user['name'] ?? 'US', 0, 2)) ?>
                    </div>
                    <div class="user-info flex-grow-1 overflow-hidden">
                        <div class="user-name text-truncate"><?= e($user['name']) ?></div>
                        <div class="user-role text-truncate"><?= e($user['role'] ?? 'Student') ?></div>
                    </div>
                    <form method="post" action="/logout" class="m-0">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-logout-icon" title="Đăng xuất">
                            <i class="bi bi-power"></i>
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="d-flex gap-2">
                    <a href="/login" class="btn btn-sm btn-outline-primary rounded-pill flex-grow-1">Đăng nhập</a>
                    <a href="/register" class="btn btn-sm btn-primary rounded-pill flex-grow-1">Đăng ký</a>
                </div>
            <?php endif; ?>
        </div>
    </aside>

    <!-- MAIN RIGHT CONTENT AREA (Trang chủ & Các view con hiển thị tại đây) -->
    <main class="refract-main-content">
        <!-- Top Refract Navigation Bar -->
        <header class="refract-topbar d-flex align-items-center justify-content-between mb-4">
            <div class="topbar-greeting">
                <h4 class="mb-0 fw-bold">Xin chào, <?= e(authUser()['name'] ?? 'Học viên') ?> 👋</h4>
                <div class="greeting-date"><?= date('l, d/m/Y') ?> · Chúc bạn học tập hiệu quả hôm nay!</div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Search Refract Pill -->
                <form class="refract-search-pill" action="/courses" method="get">
                    <i class="bi bi-search text-muted me-2"></i>
                    <input type="text" name="q" value="<?= e($_GET['q'] ?? '') ?>" placeholder="Tìm kiếm khóa học..." class="border-0 bg-transparent text-white" style="outline: none; font-size: 0.85rem; width: 170px;">
                    <span class="badge-shortcut">Enter</span>
                </form>

                <?php if ($user = authUser()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="/admin/lms/dashboard" class="btn-refract-action" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;">
                            <i class="bi bi-speedometer2 me-1"></i> Quản trị LMS
                        </a>
                    <?php else: ?>
                        <a href="/my-courses" class="btn-refract-action">
                            <i class="bi bi-play-circle me-1"></i> Vào phòng học
                        </a>
                    <?php endif; ?>

                    <div class="avatar-refract-pill">
                        <?= strtoupper(substr($user['name'] ?? 'US', 0, 2)) ?>
                    </div>
                <?php else: ?>
                    <a href="/register" class="btn-refract-action">
                        <i class="bi bi-person-plus me-1"></i> Bắt đầu ngay
                    </a>
                <?php endif; ?>
            </div>
        </header>

        <!-- Flash Toast/Messages -->
        <?php if ($msg = flash('success')): ?>
            <div class="refract-alert alert-success d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-check-circle-fill"></i>
                <span><?= e($msg) ?></span>
            </div>
        <?php endif; ?>
        <?php if ($msg = flash('error')): ?>
            <div class="refract-alert alert-danger d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span><?= e($msg) ?></span>
            </div>
        <?php endif; ?>

        <!-- Dynamic Page Content -->
        <div class="refract-body-wrapper">
            <?= $content ?? '' ?>
        </div>

        <!-- Refract Glass Footer -->
        <footer class="refract-card p-4 mt-5">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="fw-bold text-white"><?= e(config('app_name', 'Hades Academy')) ?> LMS</div>
                    <div class="small text-muted">Hệ thống đào tạo trực tuyến công nghệ thực chiến chuẩn quốc tế.</div>
                </div>
                <div class="small text-muted d-flex gap-3">
                    <a href="/courses" class="text-decoration-none text-muted hover-underline">Tất cả khóa học</a>
                    <a href="/my-courses" class="text-decoration-none text-muted hover-underline">Khóa học của tôi</a>
                    <a href="/verify-certificate" class="text-decoration-none text-muted hover-underline">Xác thực chứng chỉ QR</a>
                </div>
            </div>
        </footer>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/refract-interactive.js"></script>

</body>
</html>
