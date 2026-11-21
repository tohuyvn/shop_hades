<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf" content="<?= e(csrf_token()) ?>">
    <title>Refract Admin - <?= e(config('app_name', 'Hades Academy')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/css/glassy-theme.css" rel="stylesheet">
</head>
<body class="refract-dashboard-body">

<div class="refract-app-container">
    <!-- LEFT GLASS SIDEBAR -->
    <aside class="refract-sidebar">
        <!-- Brand -->
        <div class="refract-brand d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-prism-icon">
                    <i class="bi bi-triangle-half"></i>
                </div>
                <div class="brand-text">
                    <span class="brand-title">Refract</span>
                    <span class="badge-crm">LMS</span>
                </div>
            </div>
            <a href="/" class="btn-home-link" title="Xem trang chủ học viên">
                <i class="bi bi-box-arrow-up-right"></i>
            </a>
        </div>

        <?php
        $uri = $_SERVER['REQUEST_URI'];
        $isActive = fn($path) => str_starts_with($uri, $path) ? 'active' : '';
        ?>

        <!-- Navigation Sections -->
        <div class="sidebar-section">
            <div class="section-label">WORKSPACE</div>
            <a href="/admin/lms/dashboard" class="sidebar-nav-item <?= ($uri === '/admin' || str_starts_with($uri, '/admin/lms/dashboard')) ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2"></i>
                <span>Overview</span>
            </a>
            <a href="/admin/lms/courses" class="sidebar-nav-item <?= $isActive('/admin/lms/courses') ?>">
                <i class="bi bi-collection-play"></i>
                <span>Khóa học & Bài học</span>
            </a>
            <a href="/admin/lms/quizzes" class="sidebar-nav-item <?= $isActive('/admin/lms/quizzes') ?>">
                <i class="bi bi-patch-question"></i>
                <span>Bài kiểm tra</span>
            </a>
            <a href="/admin/orders" class="sidebar-nav-item <?= $isActive('/admin/orders') ?>">
                <i class="bi bi-receipt"></i>
                <span>Đơn hàng LMS</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="section-label">PEOPLE & ROLES</div>
            <a href="/admin/lms/students" class="sidebar-nav-item <?= $isActive('/admin/lms/students') ?>">
                <i class="bi bi-mortarboard"></i>
                <span>Học viên</span>
            </a>
            <a href="/admin/lms/instructors" class="sidebar-nav-item <?= $isActive('/admin/lms/instructors') ?>">
                <i class="bi bi-person-video3"></i>
                <span>Giảng viên</span>
            </a>
            <a href="/admin/users" class="sidebar-nav-item <?= $isActive('/admin/users') ?>">
                <i class="bi bi-people"></i>
                <span>Tài khoản Users</span>
            </a>
            <a href="/admin/lms/certificates" class="sidebar-nav-item <?= $isActive('/admin/lms/certificates') ?>">
                <i class="bi bi-award"></i>
                <span>Chứng chỉ QR</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="section-label">SYSTEM</div>
            <a href="/admin/audit-logs" class="sidebar-nav-item <?= $isActive('/admin/audit-logs') ?>">
                <i class="bi bi-shield-check"></i>
                <span>Audit Logs</span>
            </a>
            <a href="/admin/profile" class="sidebar-nav-item <?= $isActive('/admin/profile') ?>">
                <i class="bi bi-gear"></i>
                <span>Cài đặt Admin</span>
            </a>
        </div>

        <!-- User Profile Pill at Bottom -->
        <div class="sidebar-user-pill mt-auto pt-3">
            <div class="d-flex align-items-center gap-2">
                <div class="user-avatar-circle">
                    <?= strtoupper(substr(authUser()['name'] ?? 'AD', 0, 2)) ?>
                </div>
                <div class="user-info flex-grow-1 overflow-hidden">
                    <div class="user-name text-truncate"><?= e(authUser()['name'] ?? 'Leah Moreno') ?></div>
                    <div class="user-role text-truncate">Administrator</div>
                </div>
                <form method="post" action="/logout" class="m-0">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-logout-icon" title="Đăng xuất">
                        <i class="bi bi-power"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN DASHBOARD CONTENT AREA -->
    <main class="refract-main-content">
        <!-- Top Refract Navigation Bar -->
        <header class="refract-topbar d-flex align-items-center justify-content-between mb-4">
            <div class="topbar-greeting">
                <h4 class="mb-0 fw-bold">Good evening, <?= e(authUser()['name'] ?? 'Admin') ?></h4>
                <div class="greeting-date"><?= date('l, F j') ?></div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Search Refract Pill -->
                <div class="refract-search-pill">
                    <i class="bi bi-search text-muted me-2"></i>
                    <input type="text" placeholder="Search or jump to..." class="border-0 bg-transparent text-white" style="outline: none; font-size: 0.85rem;">
                    <span class="badge-shortcut">⌘K</span>
                </div>

                <!-- Notification Bell -->
                <button class="btn-icon-pill" title="Thông báo">
                    <i class="bi bi-bell"></i>
                </button>

                <!-- New Action Button (Refract Gradient Orange Pill) -->
                <a href="/admin/lms/courses/create" class="btn-refract-action">
                    <i class="bi bi-plus-lg me-1"></i> New course
                </a>

                <div class="avatar-refract-pill">
                    <?= strtoupper(substr(authUser()['name'] ?? 'LM', 0, 2)) ?>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
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

        <!-- Dynamic Body Page Content -->
        <div class="refract-body-wrapper">
            <?= $content ?? '' ?>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/refract-interactive.js"></script>
</body>
</html>
