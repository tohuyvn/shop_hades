<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? config('app_name')) ?> - Điện thoại, Laptop, Phụ kiện</title>
    <meta name="description" content="<?= e($metaDescription ?? 'Hades Shop - Hệ thống bán lẻ điện thoại, laptop, phụ kiện chính hãng giá tốt nhất.') ?>">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <meta name="csrf" content="<?= e(csrf_token()) ?>">
    <meta name="auth-user" content="<?= authUser() ? '1' : '0' ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e('http://' . ($_SERVER['HTTP_HOST'] ?? '127.0.0.1:8000') . ($_SERVER['REQUEST_URI'] ?? '/')) ?>">
    <meta property="og:title" content="<?= e($ogTitle ?? ($title ?? config('app_name'))) ?>">
    <meta property="og:description" content="<?= e($ogDescription ?? ($metaDescription ?? 'Hades Shop - Hệ thống bán lẻ điện thoại, laptop, phụ kiện chính hãng giá tốt nhất.')) ?>">
    <meta property="og:image" content="<?= e($ogImage ?? 'http://' . ($_SERVER['HTTP_HOST'] ?? '127.0.0.1:8000') . '/image/logo.webp') ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= e('http://' . ($_SERVER['HTTP_HOST'] ?? '127.0.0.1:8000') . ($_SERVER['REQUEST_URI'] ?? '/')) ?>">
    <meta property="twitter:title" content="<?= e($ogTitle ?? ($title ?? config('app_name'))) ?>">
    <meta property="twitter:description" content="<?= e($ogDescription ?? ($metaDescription ?? 'Hades Shop - Hệ thống bán lẻ điện thoại, laptop, phụ kiện chính hãng giá tốt nhất.')) ?>">
    <meta property="twitter:image" content="<?= e($ogImage ?? 'http://' . ($_SERVER['HTTP_HOST'] ?? '127.0.0.1:8000') . '/image/logo.webp') ?>">

    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="manifest" href="/manifest.json">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/css/shop.min.css" rel="stylesheet">
    <link href="/css/animation.min.css" rel="stylesheet">
    <link href="/css/glassy-theme.css" rel="stylesheet">
    <!-- Leaflet — chỉ load khi có bản đồ footer -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" media="print" onload="this.media='all'">
    <script>
        // Inline theme selector to prevent flashing
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <?php
    $ga4Id = trim((string) config('ga4_measurement_id', ''));
    $pixelId = trim((string) config('meta_pixel_id', ''));
    ?>
    <?php if ($ga4Id !== ''): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($ga4Id) ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', <?= json_encode($ga4Id) ?>);
    </script>
    <?php endif; ?>
    <?php if ($pixelId !== ''): ?>
    <script>
      !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
      n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', <?= json_encode($pixelId) ?>);
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?= e($pixelId) ?>&ev=PageView&noscript=1"/></noscript>
    <?php endif; ?>
</head>
<body>
<!-- Live Animated Video Background -->
<div class="video-bg-container" id="videoBgContainer" aria-hidden="true">
    <video id="bgVideoPlayer" class="video-bg-player" autoplay muted loop playsinline preload="auto">
        <source id="bgVideoSource" src="/video/phone_devices_loop.mp4" type="video/mp4">
    </video>
    <div class="video-bg-overlay"></div>
</div>
<div class="top-promo-banner" role="banner">
    <div class="container promo-banner-inner">
        <span class="promo-banner-label"><i class="bi bi-stars"></i> Ưu đãi tuần này</span>
        <a href="/?q=sale">Giảm đến 50% sản phẩm công nghệ chọn lọc <i class="bi bi-arrow-right"></i></a>
        <span class="promo-banner-note d-none d-md-inline">Giao nhanh toàn quốc · Đổi trả dễ dàng</span>
    </div>
</div>
<div class="top-bar">
    <div class="container d-flex justify-content-between flex-wrap gap-2">
        <span class="top-bar-item">
            <i class="bi bi-telephone-fill"></i>
            Hotline: <a href="tel:<?= e(config('hotline', '18001234')) ?>"><?= e(config('hotline', '1800 1234')) ?></a>
        </span>
        <span class="top-bar-item d-none d-md-inline">
            <i class="bi bi-truck"></i> Miễn phí giao hàng đơn từ 500.000đ
        </span>
        <span class="top-bar-item">
            <a href="/warranty" class="text-white text-decoration-none"><i class="bi bi-shield-check text-warning"></i> Tra cứu Bảo hành (IMEI)</a>
        </span>
        <span class="top-bar-item">
            <a href="/trade-in" class="text-white text-decoration-none"><i class="bi bi-arrow-repeat text-info"></i> Thu cũ Đổi mới</a>
        </span>
        <span class="top-bar-item d-none d-lg-inline">
            <a href="/pages/dia-chi-cua-hang" class="text-white text-decoration-none"><i class="bi bi-geo-alt text-danger"></i> Hệ thống Showroom</a>
        </span>
    </div>
</div>

<header class="site-header">
    <div class="container header-inner">
        <button class="btn-menu-toggle d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-label="Menu">
            <i class="bi bi-list"></i>
        </button>
        <a href="/" class="logo">
            <img src="/image/logo.webp" alt="Logo" class="logo-img">
            <span><?= e(config('app_name')) ?></span>
        </a>
        <form class="search-box" action="/" method="get">
            <input type="text" id="searchInput" name="q" placeholder="Tìm sản phẩm, thương hiệu..." value="<?= e($search ?? '') ?>" autocomplete="off" aria-label="Tìm kiếm">
            <button type="submit" aria-label="Tìm kiếm"><i class="bi bi-search"></i></button>
            <div id="searchSuggestions" class="search-suggestions-box d-none"></div>
        </form>
        <div class="header-actions">
            <button type="button" id="btnInstallApp" class="btn-install-app" title="Cài ứng dụng Hades Shop" aria-label="Tải ứng dụng">
                <i class="bi bi-download"></i>
                <span class="d-none d-lg-inline">Tải app</span>
            </button>
            <button id="theme-toggle" class="btn-theme-toggle" aria-label="Đổi giao diện">
                <i class="bi bi-sun-fill" id="theme-toggle-icon"></i>
            </button>
            <button type="button" id="btnVideoToggle" class="btn-theme-toggle btn-video-ctrl" title="Tạm dừng video nền" aria-label="Tạm dừng video">
                <i class="bi bi-pause-fill"></i>
            </button>
            <div class="dropdown d-inline-block">
                <button class="btn-theme-toggle btn-wallpaper-picker" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Đổi hình nền / video nền" aria-label="Đổi hình nền">
                    <i class="bi bi-camera-video"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end wallpaper-switcher-menu shadow" style="min-width: 290px;">
                    <div class="px-2 py-1 text-muted small fw-bold text-uppercase d-flex align-items-center justify-content-between" style="letter-spacing: 0.05em; font-size: 11px;">
                        <span>Video Nền Động (Live)</span>
                        <span class="badge bg-danger bg-opacity-75" style="font-size: 9px;">Motion</span>
                    </div>
                    <button type="button" class="wallpaper-opt-btn active" data-bg="video-phones">
                        <span class="wp-thumb-preview d-flex align-items-center justify-content-center bg-dark text-warning">
                            <i class="bi bi-phone"></i>
                        </span>
                        <div class="flex-grow-1">
                            <div class="fw-semibold text-white d-flex align-items-center justify-content-between">
                                <span>Smartphone 3D</span>
                                <span class="badge bg-primary bg-opacity-50 text-info" style="font-size: 9px;">Live Loop</span>
                            </div>
                            <div class="text-muted" style="font-size: 10px;">Thiết bị & điện thoại 3D chuyển động</div>
                        </div>
                    </button>
                    <button type="button" class="wallpaper-opt-btn" data-bg="video-particles">
                        <span class="wp-thumb-preview d-flex align-items-center justify-content-center bg-dark text-info">
                            <i class="bi bi-stars"></i>
                        </span>
                        <div class="flex-grow-1">
                            <div class="fw-semibold text-white d-flex align-items-center justify-content-between">
                                <span>Cyber Neon Particles</span>
                                <span class="badge bg-primary bg-opacity-50 text-info" style="font-size: 9px;">Live Loop</span>
                            </div>
                            <div class="text-muted" style="font-size: 10px;">Hạt ánh sáng bay trong bóng tối</div>
                        </div>
                    </button>
                    <button type="button" class="wallpaper-opt-btn" data-bg="video-chipset">
                        <span class="wp-thumb-preview d-flex align-items-center justify-content-center bg-dark text-success">
                            <i class="bi bi-cpu"></i>
                        </span>
                        <div class="flex-grow-1">
                            <div class="fw-semibold text-white d-flex align-items-center justify-content-between">
                                <span>Chipset & Circuit</span>
                                <span class="badge bg-primary bg-opacity-50 text-info" style="font-size: 9px;">Live Loop</span>
                            </div>
                            <div class="text-muted" style="font-size: 10px;">Bo mạch vi xử lý công nghệ</div>
                        </div>
                    </button>

                    <div class="dropdown-divider my-2 border-secondary border-opacity-25"></div>
                    <div class="px-2 py-1 text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 11px;">Hình Nền Tĩnh (Static)</div>
                    <button type="button" class="wallpaper-opt-btn" data-bg="titanium">
                        <span class="wp-thumb-preview" style="background-image: url('/image/phone_glassy_bg.jpg');"></span>
                        <div>
                            <div class="fw-semibold text-white">Titanium Neon Ambient</div>
                            <div class="text-muted" style="font-size: 10px;">Dải titanium phản quang</div>
                        </div>
                    </button>
                    <button type="button" class="wallpaper-opt-btn" data-bg="showroom">
                        <span class="wp-thumb-preview" style="background-image: url('/image/phone_showroom_bg.jpg');"></span>
                        <div>
                            <div class="fw-semibold text-white">Cyber Showroom</div>
                            <div class="text-muted" style="font-size: 10px;">Sân khấu công nghệ</div>
                        </div>
                    </button>
                    <button type="button" class="wallpaper-opt-btn" data-bg="futuristic">
                        <span class="wp-thumb-preview" style="background-image: url('/image/phone_futuristic_bg.jpg');"></span>
                        <div>
                            <div class="fw-semibold text-white">Obsidian Flagship</div>
                            <div class="text-muted" style="font-size: 10px;">Phòng trưng bày tương lai</div>
                        </div>
                    </button>
                </div>
            </div>
            <?php if ($user = authUser()): ?>
                <?php if (isAdmin()): ?>
                    <a href="/admin/products" class="admin-only-link d-none d-md-inline-flex"><i class="bi bi-gear"></i> Quản trị</a>
                <?php endif; ?>
                <a href="/profile" class="user-greet d-none d-md-inline-flex">
                    <i class="bi bi-person-circle"></i> 
                    <?= e($user['name']) ?>
                    <?php if (!empty($user['loyalty_tier'])): ?>
                        <?php 
                            $tier = $user['loyalty_tier'];
                            $badgeStyle = '';
                            if (strcasecmp($tier, 'Silver') === 0 || strcasecmp($tier, 'Bạc') === 0) {
                                $badgeStyle = 'background: linear-gradient(135deg, #e0e0e0, #909090); color: #333;';
                            } elseif (strcasecmp($tier, 'Gold') === 0 || strcasecmp($tier, 'Vàng') === 0) {
                                $badgeStyle = 'background: linear-gradient(135deg, #ffe066, #f5a623); color: #fff; text-shadow: 0 1px 1px rgba(0,0,0,0.3);';
                            } elseif (strcasecmp($tier, 'Diamond') === 0 || strcasecmp($tier, 'Kim Cương') === 0) {
                                $badgeStyle = 'background: linear-gradient(135deg, #b3f0ff, #0099ff); color: #fff; box-shadow: 0 0 8px rgba(0, 153, 255, 0.4);';
                            } else {
                                $badgeStyle = 'background: linear-gradient(135deg, #a8a8a8, #7a7a7a); color: white;';
                            }
                        ?>
                        <span class="badge ms-2" style="<?= $badgeStyle ?> font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 4px; font-weight: 600; text-transform: uppercase;"><?= e($tier) ?></span>
                    <?php endif; ?>
                </a>
                <a href="/orders" class="d-none d-lg-inline-flex"><i class="bi bi-box-seam"></i> Đơn hàng</a>
                <a href="/wallet" class="d-none d-lg-inline-flex"><i class="bi bi-wallet2"></i> Ví</a>
                <form method="post" action="/logout" class="d-none d-md-inline-flex m-0">
                    <?= csrf_field() ?>
                    <button type="submit" class="border-0 bg-transparent p-0">Đăng xuất</button>
                </form>
            <?php else: ?>
                <a href="/login" class="d-none d-md-inline-flex"><i class="bi bi-box-arrow-in-right"></i> Đăng nhập</a>
                <a href="/register" class="d-none d-md-inline-flex">Đăng ký</a>
            <?php endif; ?>
            <?php
            $headerWishlistCount = 0;
            if ($uHeader = authUser()) {
                $headerWishlistCount = (new \App\Models\Wishlist())->count((int)$uHeader['id']);
            }
            ?>
            <a href="/wishlist" id="headerWishlistLink" class="btn-cart me-1 position-relative" title="Sản phẩm yêu thích" aria-label="Sản phẩm yêu thích">
                <i class="bi bi-heart-fill text-danger"></i>
                <span id="headerWishlistBadge" class="badge bg-danger rounded-circle position-absolute top-0 start-100 translate-middle p-1 <?= $headerWishlistCount > 0 ? '' : 'd-none' ?>" style="font-size:0.65rem; min-width:18px;">
                    <?= $headerWishlistCount ?>
                </span>
            </a>
            <a href="/cart" id="cart-icon" class="btn-cart" data-count="<?= count($_SESSION['cart'] ?? []) ?>"><i class="bi bi-bag"></i> Giỏ hàng</a>
        </div>
    </div>
</header>

<?php
if (!function_exists('getCategoryIcon')) {
    function getCategoryIcon(string $name): string {
        $nameLower = strtolower($name);
        if (str_contains($nameLower, 'thoại') || str_contains($nameLower, 'phone')) return 'bi-phone';
        if (str_contains($nameLower, 'laptop') || str_contains($nameLower, 'máy tính')) return 'bi-laptop';
        if (str_contains($nameLower, 'phụ kiện') || str_contains($nameLower, 'tai nghe') || str_contains($nameLower, 'sạc') || str_contains($nameLower, 'dây')) return 'bi-headphones';
        if (str_contains($nameLower, 'watch') || str_contains($nameLower, 'đồng hồ thông minh')) return 'bi-smartwatch';
        if (str_contains($nameLower, 'đồng hồ')) return 'bi-watch';
        if (str_contains($nameLower, 'tablet') || str_contains($nameLower, 'ipad')) return 'bi-tablet';
        return 'bi-plug';
    }
}
?>

<nav class="category-nav" aria-label="Danh mục sản phẩm">
    <div class="container">
        <a href="/" class="<?= empty($selectedCategory) ? 'active' : '' ?>">
            <i class="bi bi-grid"></i> Tất cả
        </a>
        <?php foreach (($categories ?? []) as $cat): ?>
            <a href="/?category=<?= (int) $cat['id'] ?>" class="<?= (int) ($selectedCategory ?? 0) === (int) $cat['id'] ? 'active' : '' ?>">
                <i class="bi <?= getCategoryIcon($cat['name']) ?>"></i>
                <?= e($cat['name']) ?>
            </a>
        <?php endforeach; ?>
        <span class="category-nav-spacer"></span>
        <a href="/trade-in" class="category-nav-highlight"><i class="bi bi-arrow-repeat"></i> Thu cũ đổi mới</a>
        <a href="/orders/track"><i class="bi bi-box-seam"></i> Tra cứu đơn</a>
    </div>
</nav>

<main class="container py-4">
    <?php
    // Chuyển flash messages sang toast (không cần render trong main)
    $__toastSuccess = flash('success');
    $__toastError   = flash('error');
    ?>
    <?= $content ?? '' ?>
</main>

<!-- Toast container -->
<div id="toast-container" aria-live="polite" aria-atomic="true"></div>
<?php if ($__toastSuccess): ?>
<script>window.__initToast = {type:'success',msg:<?= json_encode($__toastSuccess) ?>};</script>
<?php endif; ?>
<?php if ($__toastError): ?>
<script>window.__initToast = {type:'error',msg:<?= json_encode($__toastError) ?>};</script>
<?php endif; ?>


<div class="container my-4">
    <div class="search-keywords-card p-4">
        <h5 class="mb-3 fw-bold" style="font-size: 15px;">Mọi người cũng tìm kiếm</h5>
        <div class="d-flex flex-wrap gap-2 keyword-list">
            <?php
            $keywords = [
                'iphone 15' => '/?q=iphone+15',
                'iphone 16' => '/?q=iphone+16',
                'iphone 17' => '/?q=iphone+17',
                'macbook' => '/?q=macbook',
                'dell xps' => '/?q=dell+xps',
                'thinkpad' => '/?q=thinkpad',
                'tai nghe' => '/?q=tai+nghe',
                'sạc nhanh' => '/?q=s%E1%BA%A1c+nhanh',
                'apple watch' => '/?q=apple+watch',
                'casio' => '/?q=casio',
                'ipad pro' => '/?q=ipad+pro',
                'ipad air' => '/?q=ipad+air',
                'máy cũ' => '/?q=m%C3%A1y+c%C5%A9',
                'phụ kiện' => '/?q=ph%E1%BB%A5+ki%E1%BB%87n',
                'smartwatch' => '/?q=smartwatch',
                'laptop gaming' => '/?q=laptop+gaming',
                'chuột gaming' => '/?q=chu%E1%BB%99t+gaming',
                'đồng hồ đôi' => '/?q=%C4%91%E1%BB%93ng+h%E1%BB%93+%C4%91%C3%B4i',
                'laptop đồ họa' => '/?q=laptop+%C4%91%E1%BB%93+h%E1%BB%8Da',
                'samsung galaxy' => '/?q=samsung+galaxy',
                'trả góp 0%' => '/?q=tr%E1%BA%A3+g%C3%B3p',
            ];
            foreach ($keywords as $kw => $link):
            ?>
                <a href="<?= $link ?>" class="keyword-tag"><?= e($kw) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold mb-3 text-uppercase footer-heading">Về Hades Shop</h5>
                <p class="small footer-text" style="line-height: 1.6;">
                    Hades Shop là hệ thống bán lẻ công nghệ hàng đầu, chuyên cung cấp các dòng điện thoại, laptop, iPad và phụ kiện chính hãng với ưu đãi đặc biệt và hỗ trợ khách hàng 24/7.
                </p>
                <div class="mt-3">
                    <p class="small mb-1"><i class="bi bi-envelope-fill footer-icon me-2"></i>Email: <a href="mailto:huy28072k5@gmail.com" class="text-decoration-none">huy28072k5@gmail.com</a></p>
                    <p class="small mb-1"><i class="bi bi-telephone-fill footer-icon me-2"></i>Hotline: <a href="tel:<?= e(config('hotline', '1800 1234')) ?>" class="text-decoration-none"><?= e(config('hotline', '1800 1234')) ?></a></p>
                    <p class="small mb-0"><i class="bi bi-chat-left-text-fill footer-icon me-2"></i><a href="javascript:void(0)" onclick="openHadesChat('support')" class="text-decoration-none">Hỗ trợ trực tuyến</a></p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold mb-3 text-uppercase footer-heading">Chính sách & Hỗ trợ</h5>
                <ul class="list-unstyled small" style="line-height: 2.2; margin-bottom: 0;">
                    <li><a href="/pages/ve-chung-toi" class="text-decoration-none footer-link"><i class="bi bi-info-circle footer-icon me-2"></i>Về chúng tôi</a></li>
                    <li><a href="/pages/chinh-sach-doi-tra" class="text-decoration-none footer-link"><i class="bi bi-arrow-left-right footer-icon me-2"></i>Chính sách đổi trả</a></li>
                    <li><a href="/pages/bao-hanh" class="text-decoration-none footer-link"><i class="bi bi-shield-check footer-icon me-2"></i>Chính sách bảo hành</a></li>
                    <li><a href="/pages/huong-dan-mua-hang" class="text-decoration-none footer-link"><i class="bi bi-book footer-icon me-2"></i>Hướng dẫn mua hàng</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold mb-3 text-uppercase footer-heading">Hệ thống cơ sở</h5>
                <div class="list-group list-group-flush branch-list bg-transparent">
                    <a href="javascript:void(0)" onclick="focusBranch(0)" class="list-group-item bg-transparent border-0 px-0 py-1 small branch-link active-branch" id="branch-0">
                        <i class="bi bi-geo-alt-fill text-danger me-2"></i><strong>Trụ sở chính:</strong> Võng Thị, Tây Hồ, HN
                    </a>
                    <a href="javascript:void(0)" onclick="focusBranch(1)" class="list-group-item bg-transparent border-0 px-0 py-1 small branch-link" id="branch-1">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i><strong>Cơ sở 2:</strong> Số 3 Duy Tân, Cầu Giấy, HN
                    </a>
                    <a href="javascript:void(0)" onclick="focusBranch(2)" class="list-group-item bg-transparent border-0 px-0 py-1 small branch-link" id="branch-2">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i><strong>Cơ sở 3:</strong> Lê Duẩn, Hải Châu, Đà Nẵng
                    </a>
                    <a href="javascript:void(0)" onclick="focusBranch(3)" class="list-group-item bg-transparent border-0 px-0 py-1 small branch-link" id="branch-3">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i><strong>Cơ sở 4:</strong> Số 99 Nguyễn Huệ, Quận 1, HCM
                    </a>
                    <a href="javascript:void(0)" onclick="focusBranch(4)" class="list-group-item bg-transparent border-0 px-0 py-1 small branch-link" id="branch-4">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i><strong>Cơ sở 5:</strong> Số 250 CMT8, Q.3, Sài Gòn
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-12 mb-4">
                <h5 class="fw-bold mb-3 text-uppercase footer-heading">Bản đồ cửa hàng</h5>
                <div id="footer-map" style="height: 180px; width: 100%; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); overflow: hidden;"></div>
            </div>
        </div>
        <hr class="footer-divider" style="border-top: 1px solid rgba(255,255,255,0.1); margin: 20px 0;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <p class="mb-0 small footer-copy">© <?= date('Y') ?> <?= e(config('app_name')) ?>. All rights reserved.</p>
            <div class="small footer-copy">
                <a href="/" class="text-decoration-none me-3">Trang chủ</a>
                <a href="/orders/track" class="text-decoration-none me-3">Tra cứu đơn hàng</a>
                <a href="/cart" class="text-decoration-none me-3">Giỏ hàng</a>
                <a href="/checkout" class="text-decoration-none">Thanh toán</a>
            </div>
        </div>
    </div>
</footer>

<script>
    // Leaflet map initialization for Hades Shop Branches
    const branches = [
        {
            name: "Trụ sở chính: Võng Thị, Tây Hồ, Hà Nội",
            coords: [21.0544, 105.8118],
            popup: "<strong>Hades Shop - Trụ sở chính</strong><br>Võng Thị, Tây Hồ, Hà Nội<br>📞 Hotline: 1800 1234"
        },
        {
            name: "Cơ sở 2: Số 3 Duy Tân, Cầu Giấy, Hà Nội",
            coords: [21.0294, 105.7825],
            popup: "<strong>Hades Shop - Cơ sở 2</strong><br>Số 3 Duy Tân, Cầu Giấy, Hà Nội<br>📞 Hotline: 1800 1234"
        },
        {
            name: "Cơ sở 3: Số 145 Lê Duẩn, Hải Châu, Đà Nẵng",
            coords: [16.0594, 108.2208],
            popup: "<strong>Hades Shop - Cơ sở 3</strong><br>Số 145 Lê Duẩn, Hải Châu, Đà Nẵng<br>📞 Hotline: 1800 1234"
        },
        {
            name: "Cơ sở 4: Số 99 Nguyễn Huệ, Q.1, TP. HCM",
            coords: [10.7756, 106.7019],
            popup: "<strong>Hades Shop - Cơ sở 4</strong><br>Số 99 Nguyễn Huệ, Quận 1, TP. HCM<br>📞 Hotline: 1800 1234"
        },
        {
            name: "Cơ sở 5: Số 250 CMT8, Q.3, Sài Gòn",
            coords: [10.7818, 106.6798],
            popup: "<strong>Hades Shop - Cơ sở 5</strong><br>Số 250 Cách Mạng Tháng 8, Quận 3, Sài Gòn<br>📞 Hotline: 1800 1234"
        }
    ];

    let footerMap;
    let markers = [];
    let footerMapReady = false;

    function initFooterMap() {
        if (footerMapReady || !document.getElementById('footer-map') || typeof L === 'undefined') {
            return;
        }
        footerMapReady = true;
        footerMap = L.map('footer-map').setView(branches[0].coords, 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(footerMap);
        branches.forEach(function (b, idx) {
            const marker = L.marker(b.coords).addTo(footerMap).bindPopup(b.popup);
            markers.push(marker);
        });
        markers[0].openPopup();
    }

    function loadLeafletThenInit() {
        if (footerMapReady || !document.getElementById('footer-map')) return;
        if (typeof L !== 'undefined') {
            initFooterMap();
            return;
        }
        const s = document.createElement('script');
        s.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
        s.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
        s.crossOrigin = '';
        s.onload = initFooterMap;
        document.body.appendChild(s);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const mapEl = document.getElementById('footer-map');
        if (!mapEl) return;
        if ('IntersectionObserver' in window) {
            const obs = new IntersectionObserver(function (entries) {
                if (entries[0].isIntersecting) {
                    loadLeafletThenInit();
                    obs.disconnect();
                }
            }, { rootMargin: '200px' });
            obs.observe(mapEl);
        } else {
            loadLeafletThenInit();
        }
    });

    function focusBranch(idx) {
        if (!footerMap) {
            loadLeafletThenInit();
            return;
        }
        footerMap.setView(branches[idx].coords, 15);
        markers[idx].openPopup();

        // Update active class in list UI
        document.querySelectorAll('.branch-link').forEach(el => {
            el.classList.remove('active-branch');
            el.style.backgroundColor = 'transparent';
        });

        const activeEl = document.getElementById('branch-' + idx);
        if (activeEl) {
            activeEl.classList.add('active-branch');
        }
    }
</script>

<!-- Mobile Bottom Navigation -->
<nav class="mobile-bottom-nav d-md-none">
    <a href="/" class="nav-item">
        <i class="bi bi-house-door"></i>
        <span>Trang chủ</span>
    </a>
    <a href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" class="nav-item">
        <i class="bi bi-grid"></i>
        <span>Danh mục</span>
    </a>
    <a href="/cart" class="nav-item">
        <i class="bi bi-bag"></i>
        <span>Giỏ hàng</span>
    </a>
    <a href="javascript:void(0)" onclick="openHadesChat('support')" class="nav-item">
        <i class="bi bi-chat-left-text"></i>
        <span>Chat</span>
    </a>
    <a href="/profile" class="nav-item">
        <i class="bi bi-person"></i>
        <span>Tài khoản</span>
    </a>
</nav>

<!-- Mobile Offcanvas Menu -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-bold" id="mobileMenuLabel"><?= e(config('app_name')) ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="nav-section-title">Danh mục</div>
        <a href="/" class="nav-link"><i class="bi bi-grid"></i> Tất cả sản phẩm</a>
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $cat): ?>
                <a href="/?category=<?= $cat['id'] ?>" class="nav-link">
                    <i class="bi <?= getCategoryIcon($cat['name']) ?>"></i> <?= e($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="nav-section-title">Tài khoản</div>
        <?php if ($user = authUser()): ?>
            <a href="/profile" class="nav-link"><i class="bi bi-person-circle"></i> <?= e($user['name']) ?></a>
            <a href="/orders" class="nav-link"><i class="bi bi-box-seam"></i> Đơn hàng của tôi</a>
            <a href="/wishlist" class="nav-link"><i class="bi bi-heart"></i> Sản phẩm yêu thích</a>
            <form method="post" action="/logout" class="m-0">
                <?= csrf_field() ?>
                <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start"><i class="bi bi-box-arrow-right"></i> Đăng xuất</button>
            </form>
        <?php else: ?>
            <a href="/login" class="nav-link"><i class="bi bi-box-arrow-in-right"></i> Đăng nhập</a>
            <a href="/register" class="nav-link"><i class="bi bi-person-plus"></i> Đăng ký</a>
            <a href="/wishlist" class="nav-link"><i class="bi bi-heart"></i> Yêu thích trên thiết bị</a>
            <a href="/orders/track" class="nav-link"><i class="bi bi-search"></i> Tra cứu đơn hàng</a>
        <?php endif; ?>

        <div class="nav-section-title">Hỗ trợ</div>
        <button type="button" class="nav-link border-0 bg-transparent w-100 text-start" id="btnInstallAppMobile">
            <i class="bi bi-download"></i> Tải ứng dụng
        </button>
        <a href="tel:<?= e(config('hotline', '1800 1234')) ?>" class="nav-link"><i class="bi bi-telephone"></i> Hotline: <?= e(config('hotline', '1800 1234')) ?></a>
        <a href="javascript:void(0)" onclick="openHadesChat('support')" class="nav-link"><i class="bi bi-chat-left-text"></i> Chat trực tuyến</a>
    </div>
</div>

<!-- PWA install help (iOS / trình duyệt không hỗ trợ prompt) -->
<div class="modal fade" id="pwaHelpModal" tabindex="-1" aria-labelledby="pwaHelpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="pwaHelpModalLabel"><i class="bi bi-phone"></i> Cài Hades Shop</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3" id="pwaHelpIntro">Thêm Shop Hades ra màn hình chính để mua sắm nhanh hơn.</p>
                <ol class="small mb-0 ps-3" id="pwaHelpSteps">
                    <li class="mb-2">Mở menu trình duyệt (⋮ hoặc Chia sẻ).</li>
                    <li class="mb-2">Chọn <strong>「Cài đặt ứng dụng」</strong> hoặc <strong>「Thêm vào Màn hình chính」</strong>.</li>
                    <li>Xác nhận — biểu tượng Hades sẽ xuất hiện trên màn hình.</li>
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary-hades" data-bs-dismiss="modal" style="width:auto;padding:10px 24px;">Đã hiểu</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const suggestionsBox = document.getElementById('searchSuggestions');
    let debounceTimer;

    if (searchInput && suggestionsBox) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            if (query.length === 0) {
                suggestionsBox.innerHTML = '';
                suggestionsBox.classList.add('d-none');
                return;
            }

            debounceTimer = setTimeout(() => {
                suggestionsBox.innerHTML = '<div class="skeleton-suggestions">' +
                    '<div class="skeleton-line"></div>'.repeat(4) +
                    '</div>';
                suggestionsBox.classList.remove('d-none');

                fetch('/api/products/search?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        if (data.length === 0) {
                            suggestionsBox.innerHTML = '<div class="p-3 text-muted text-center" style="font-size: 13px;">Không tìm thấy sản phẩm phù hợp</div>';
                            suggestionsBox.classList.remove('d-none');
                            return;
                        }

                        let html = '';
                        data.forEach(item => {
                            html += `
                                <a href="/products/${item.id}" class="suggestion-item">
                                    <img src="${item.image_url}" alt="${item.name}" class="suggestion-img">
                                    <div class="suggestion-info">
                                        <span class="suggestion-name">${item.name}</span>
                                        <span class="suggestion-price">${item.formatted_price}</span>
                                    </div>
                                </a>
                            `;
                        });
                        suggestionsBox.innerHTML = html;
                        suggestionsBox.classList.remove('d-none');
                    })
                    .catch(err => {
                        console.error('Lỗi tìm kiếm:', err);
                    });
            }, 250);
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.classList.add('d-none');
            }
        });

        searchInput.addEventListener('focus', function() {
            if (suggestionsBox.innerHTML.trim() !== '' && searchInput.value.trim() !== '') {
                suggestionsBox.classList.remove('d-none');
            }
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                suggestionsBox.classList.add('d-none');
            }
        });
    }
});
</script>

<!-- Floating Compare Drawer -->
<style>
    #compareFloatingDrawer {
        box-shadow: 0 -5px 25px rgba(0, 0, 0, 0.3) !important;
        border-top-left-radius: 16px !important;
        border-top-right-radius: 16px !important;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-bottom: none;
        background: #1e1e1e !important;
        color: #ffffff !important;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
        padding: 12px 24px !important;
        z-index: 1050 !important;
        height: auto;
    }
    #compareFloatingDrawer.d-none {
        transform: translate(-50%, 100%) !important;
        opacity: 0;
        display: none !important;
    }
    #compareFloatingDrawer:not(.d-none) {
        display: flex !important;
        transform: translate(-50%, 0) !important;
        opacity: 1;
    }
</style>
<div id="compareFloatingDrawer" class="position-fixed bottom-0 start-50 translate-middle-x d-none align-items-center justify-content-between gap-4" style="width: 90%; max-width: 600px;">
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-primary" id="compareBadgeCount">0</span>
        <span class="small fw-bold">Sản phẩm so sánh</span>
    </div>
    <div class="d-flex gap-2 align-items-center" id="compareThumbnails"></div>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-primary fw-bold px-3 py-2 rounded-pill" onclick="goToComparePage()">So sánh ngay</button>
        <button class="btn btn-sm btn-outline-light px-2 py-2 rounded-pill" onclick="clearCompareList()"><i class="bi bi-trash"></i></button>
    </div>
</div>

<script>
function getCompareList() {
    try {
        const stored = sessionStorage.getItem('hades_compare_list');
        return stored ? JSON.parse(stored) : [];
    } catch (e) {
        return [];
    }
}

function saveCompareList(list) {
    sessionStorage.setItem('hades_compare_list', JSON.stringify(list));
    updateCompareDrawer();
}

function toggleCompareDetail(id, name, image) {
    let list = getCompareList();
    const idx = list.findIndex(item => item.id === id);
    if (idx !== -1) {
        list.splice(idx, 1);
        if (window.showToast) showToast('info', 'Đã xóa sản phẩm khỏi danh sách so sánh');
    } else {
        if (list.length >= 3) {
            if (window.showToast) showToast('warning', 'Chỉ so sánh tối đa 3 sản phẩm cùng lúc.');
            else alert('Chỉ so sánh tối đa 3 sản phẩm cùng lúc.');
            return;
        }
        list.push({ id, name, image });
        if (window.showToast) showToast('success', 'Đã thêm sản phẩm vào danh sách so sánh');
    }
    saveCompareList(list);
    syncCompareCheckboxes();
}

function toggleCompareProduct(checkbox) {
    const id = parseInt(checkbox.dataset.productId);
    const name = checkbox.dataset.productName;
    const image = checkbox.dataset.productImage;
    
    let list = getCompareList();
    const idx = list.findIndex(item => item.id === id);
    
    if (checkbox.checked) {
        if (idx === -1) {
            if (list.length >= 3) {
                checkbox.checked = false;
                if (window.showToast) showToast('warning', 'Chỉ so sánh tối đa 3 sản phẩm cùng lúc.');
                else alert('Chỉ so sánh tối đa 3 sản phẩm cùng lúc.');
                return;
            }
            list.push({ id, name, image });
            if (window.showToast) showToast('success', 'Đã thêm sản phẩm vào danh sách so sánh');
        }
    } else {
        if (idx !== -1) {
            list.splice(idx, 1);
            if (window.showToast) showToast('info', 'Đã xóa sản phẩm khỏi danh sách so sánh');
        }
    }
    saveCompareList(list);
}

function updateCompareDrawer() {
    const drawer = document.getElementById('compareFloatingDrawer');
    const badge = document.getElementById('compareBadgeCount');
    const thumbs = document.getElementById('compareThumbnails');
    if (!drawer) return;
    
    if (window.location.pathname === '/compare') {
        drawer.classList.add('d-none');
        return;
    }
    
    const list = getCompareList();
    if (list.length === 0) {
        drawer.classList.add('d-none');
        return;
    }
    
    badge.textContent = list.length;
    thumbs.innerHTML = '';
    list.forEach(item => {
        const div = document.createElement('div');
        div.className = 'position-relative';
        div.style.width = '40px';
        div.style.height = '40px';
        div.innerHTML = `
            <img src="${item.image}" style="width:100%;height:100%;object-fit:cover;border-radius:8px;border:1px solid rgba(255,255,255,0.2);">
            <button class="position-absolute top-0 start-100 translate-middle bg-danger text-white border-0 rounded-circle d-flex align-items-center justify-content-center" style="width:16px;height:16px;font-size:10px;padding:0;line-height:1;" onclick="removeCompareItem(${item.id})">×</button>
        `;
        thumbs.appendChild(div);
    });
    
    drawer.classList.remove('d-none');
}

function removeCompareItem(id) {
    let list = getCompareList();
    list = list.filter(item => item.id !== id);
    saveCompareList(list);
    syncCompareCheckboxes();
}

function clearCompareList() {
    saveCompareList([]);
    syncCompareCheckboxes();
}

function goToComparePage() {
    const list = getCompareList();
    if (list.length === 0) return;
    const ids = list.map(item => item.id).join(',');
    window.location.href = '/compare?ids=' + ids;
}

function syncCompareCheckboxes() {
    const list = getCompareList();
    const ids = list.map(item => item.id);
    
    document.querySelectorAll('.compare-checkbox').forEach(cb => {
        cb.checked = ids.includes(parseInt(cb.dataset.productId));
    });
    
    const detailBtn = document.getElementById('detailCompareBtn');
    if (detailBtn) {
        const match = detailBtn.getAttribute('onclick').match(/toggleCompareDetail\((\d+)/);
        if (match) {
            const detailId = parseInt(match[1]);
            if (ids.includes(detailId)) {
                detailBtn.classList.remove('btn-outline-secondary');
                detailBtn.classList.add('btn-primary-hades');
                detailBtn.innerHTML = '<i class="bi bi-check-lg me-2"></i>Đang so sánh';
            } else {
                detailBtn.classList.remove('btn-primary-hades');
                detailBtn.classList.add('btn-outline-secondary');
                detailBtn.innerHTML = '<i class="bi bi-shuffle me-2"></i>So sánh sản phẩm này';
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    updateCompareDrawer();
    syncCompareCheckboxes();
});
</script>

<style>
.hades-float-box {
    position: fixed;
    z-index: 1035;
    user-select: none;
    touch-action: none;
}
.hades-float-box.is-dragging { opacity: 0.92; cursor: grabbing !important; }
.hades-float-close {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 20px;
    height: 20px;
    border: 2px solid #fff;
    border-radius: 50%;
    background: #0f172a;
    color: #fff;
    font-size: 12px;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 2;
    padding: 0;
}
.float-service-box {
    left: 24px;
    bottom: 210px;
    width: 168px;
    background: var(--bg-card, #fff);
    border: 1px solid var(--border, #e2e8f0);
    border-radius: 14px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
    padding: 10px 10px 8px;
    cursor: grab;
}
.float-service-title {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted, #64748b);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 4px;
    letter-spacing: 0.02em;
}
.float-service-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 10px;
    border-radius: 10px;
    text-decoration: none !important;
    font-size: 12.5px;
    font-weight: 700;
    margin-bottom: 6px;
    transition: transform 0.15s ease, background 0.15s ease;
}
.float-service-link:last-child { margin-bottom: 0; }
.float-service-link.trade {
    background: rgba(215, 0, 24, 0.08);
    color: #d70018;
}
.float-service-link.warranty {
    background: rgba(30, 64, 175, 0.08);
    color: #1e40af;
}
.float-service-link:hover { transform: translateY(-1px); }
.float-service-link i { font-size: 15px; }
.floating-promo-badge,
.chatbot-launcher,
.pwa-install-banner {
    touch-action: none;
}
.floating-promo-badge { cursor: grab; }
@media (max-width: 576px) {
    .float-service-box {
        left: 12px;
        bottom: 180px;
        width: 150px;
    }
}
</style>

<script>
window.HadesDrag = (function () {
    function clamp(el, left, top) {
        var maxL = Math.max(0, window.innerWidth - el.offsetWidth);
        var maxT = Math.max(0, window.innerHeight - el.offsetHeight);
        return {
            left: Math.min(maxL, Math.max(0, left)),
            top: Math.min(maxT, Math.max(0, top))
        };
    }

    function restore(el, key) {
        if (!key) return;
        try {
            var raw = localStorage.getItem(key);
            if (!raw) return;
            var pos = JSON.parse(raw);
            if (typeof pos.left !== 'number' || typeof pos.top !== 'number') return;
            var p = clamp(el, pos.left, pos.top);
            el.style.right = 'auto';
            el.style.bottom = 'auto';
            el.style.left = p.left + 'px';
            el.style.top = p.top + 'px';
        } catch (e) {}
    }

    function make(el, opts) {
        if (!el || el.getAttribute('data-hades-drag') === '1') return;
        opts = opts || {};
        var handle = opts.handle || el;
        var key = opts.storageKey || '';
        var ignore = opts.ignoreSelector || 'a,button,input,textarea,select,label,.hades-float-close,.close-promo-btn,.btn-pwa-close,.btn-pwa-install,#chatbotClose';
        el.setAttribute('data-hades-drag', '1');
        restore(el, key);

        var dragging = false;
        var moved = false;
        var startX = 0, startY = 0, offX = 0, offY = 0;

        function onStart(e) {
            if (e.target.closest && e.target.closest(ignore)) return;
            var point = e.touches ? e.touches[0] : e;
            startX = point.clientX;
            startY = point.clientY;
            var rect = el.getBoundingClientRect();
            offX = point.clientX - rect.left;
            offY = point.clientY - rect.top;
            dragging = true;
            moved = false;
            el.classList.add('is-dragging');
        }

        function onMove(e) {
            if (!dragging) return;
            var point = e.touches ? e.touches[0] : e;
            if (Math.abs(point.clientX - startX) > 4 || Math.abs(point.clientY - startY) > 4) {
                moved = true;
            }
            if (e.cancelable) e.preventDefault();
            var p = clamp(el, point.clientX - offX, point.clientY - offY);
            el.style.right = 'auto';
            el.style.bottom = 'auto';
            el.style.left = p.left + 'px';
            el.style.top = p.top + 'px';
        }

        function onEnd() {
            if (!dragging) return;
            dragging = false;
            el.classList.remove('is-dragging');
            if (moved && key) {
                var rect = el.getBoundingClientRect();
                localStorage.setItem(key, JSON.stringify({ left: rect.left, top: rect.top }));
            }
            if (moved) {
                el.setAttribute('data-dragged', 'true');
                setTimeout(function () { el.removeAttribute('data-dragged'); }, 80);
            }
        }

        handle.addEventListener('mousedown', onStart);
        handle.addEventListener('touchstart', onStart, { passive: false });
        document.addEventListener('mousemove', onMove);
        document.addEventListener('touchmove', onMove, { passive: false });
        document.addEventListener('mouseup', onEnd);
        document.addEventListener('touchend', onEnd);

        window.addEventListener('resize', function () {
            var rect = el.getBoundingClientRect();
            var p = clamp(el, rect.left, rect.top);
            if (el.style.left) {
                el.style.left = p.left + 'px';
                el.style.top = p.top + 'px';
            }
        });
    }

    return { make: make, restore: restore };
})();

document.addEventListener('DOMContentLoaded', function () {
    var serviceBox = document.getElementById('floatServiceBox');
    if (serviceBox) {
        if (localStorage.getItem('hades_float_service_hidden') === '1') {
            serviceBox.style.display = 'none';
        }
        HadesDrag.make(serviceBox, { storageKey: 'hades_float_service_pos' });
        serviceBox.querySelectorAll('a.float-service-link').forEach(function (link) {
            link.addEventListener('click', function (e) {
                if (serviceBox.getAttribute('data-dragged') === 'true') {
                    e.preventDefault();
                }
            });
        });
        var svcClose = document.getElementById('floatServiceClose');
        if (svcClose) {
            svcClose.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                serviceBox.style.display = 'none';
                localStorage.setItem('hades_float_service_hidden', '1');
            });
        }
    }

    var promo = document.getElementById('floatingPromo');
    if (promo) {
        HadesDrag.make(promo, { storageKey: 'hades_float_promo_pos' });
    }
});
</script>

<!-- Floating: Thu cũ + Tra cứu BH -->
<div id="floatServiceBox" class="hades-float-box float-service-box" title="Kéo để di chuyển">
    <button type="button" class="hades-float-close" id="floatServiceClose" aria-label="Đóng">×</button>
    <div class="float-service-title"><i class="bi bi-grip-vertical"></i> Hỗ trợ nhanh</div>
    <a href="/trade-in" class="float-service-link trade">
        <i class="bi bi-arrow-repeat"></i>
        <span>Thu cũ đổi mới</span>
    </a>
    <a href="/warranty" class="float-service-link warranty">
        <i class="bi bi-patch-check-fill"></i>
        <span>Tra cứu bảo hành</span>
    </a>
</div>

<!-- Hades AI Chatbot Widget (3 tabs) -->
<div id="chatbotLauncher" class="chatbot-launcher" title="Hỗ trợ & Chat — kéo để di chuyển">
    <i class="bi bi-chat-dots-fill"></i>
    <span id="chatbotSupportBadge" class="chatbot-support-badge"></span>
</div>

<!-- Tabs Panel -->
<div id="chatbotWindow" class="chatbot-window">
    <div id="chatbotHeader" class="chatbot-header">
        <div class="chatbot-header-info">
            <div class="chatbot-avatar">H</div>
            <div class="chatbot-title-container">
                <span class="chatbot-title">Hades AI</span>
                <span class="chatbot-status"><span class="chatbot-status-dot"></span><span id="chatbotAiStatusText">Trợ lý AI đang hoạt động</span></span>
            </div>
        </div>
        <button id="chatbotClose" class="chatbot-close" title="Đóng">&times;</button>
    </div>

    <div class="chatbot-tabs">
        <button id="tabChatbot" class="chatbot-tab-btn active" data-tab="ai">Trợ lý AI</button>
        <button id="tabSupport" class="chatbot-tab-btn" data-tab="support">Chat trực tuyến</button>
    </div>

    <div id="chatbotBodyAI" class="chatbot-tab-panel" style="display:flex;flex-direction:column;min-height:0;flex:1;">
        <div id="chatbotMessages" class="chatbot-messages">
            <div class="chat-bubble bot">Xin chào! Mình là <strong>Hades AI</strong>. Hỏi về sản phẩm, giá, khuyến mãi hoặc chính sách shop nhé.</div>
        </div>
        <div class="chatbot-input-area">
            <input type="text" id="chatbotInput" placeholder="Ví dụ: iPhone dưới 20 triệu..." autocomplete="off">
            <button type="button" id="chatbotClear" class="chatbot-clear-btn" title="Xóa lịch sử">↺</button>
            <button id="chatbotSend" class="chatbot-send-btn" title="Gửi"><i class="bi bi-send-fill"></i></button>
        </div>
    </div>

    <div id="chatbotBodySupport" class="chatbot-tab-panel" style="display: none;">
        <div id="sc-info-form">
            <p>💬 Chat trực tiếp với nhân viên hỗ trợ. Vui lòng nhập thông tin để bắt đầu.</p>
            <input type="text" id="sc-name-input" class="form-control form-control-sm"
                   placeholder="Họ tên của bạn *"
                   value="<?= e(authUser()['name'] ?? '') ?>">
            <input type="email" id="sc-email-input" class="form-control form-control-sm"
                   placeholder="Email của bạn"
                   value="<?= e(authUser()['email'] ?? '') ?>">
            <button type="button" class="btn btn-primary btn-sm w-100 mt-1" id="sc-start-btn">Bắt đầu chat</button>
        </div>
        <div id="sc-header-status" style="display: none; padding: 6px 12px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; font-size: 11px; width: 100%;">
            <span class="text-muted">Nhân viên hỗ trợ:</span>
            <span id="sc-admin-status"><span class="badge bg-secondary"><i class="bi bi-circle-fill me-1"></i>Đang kiểm tra...</span></span>
        </div>
        <div id="sc-messages" style="display: none;"></div>
        <div id="sc-input-area" style="display: none;">
            <input type="text" id="sc-msg-input" placeholder="Nhập tin nhắn... (Enter để gửi)">
            <button type="button" id="sc-send-btn"><i class="bi bi-send-fill" style="font-size:14px;"></i></button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const launcher = document.getElementById('chatbotLauncher');
    const windowEl = document.getElementById('chatbotWindow');
    const closeBtn = document.getElementById('chatbotClose');
    const header = document.getElementById('chatbotHeader');
    const supportBadge = document.getElementById('chatbotSupportBadge');

    const tabChatbot = document.getElementById('tabChatbot');
    const tabSupport = document.getElementById('tabSupport');
    const bodyAI = document.getElementById('chatbotBodyAI');
    const bodySupport = document.getElementById('chatbotBodySupport');

    const sendBtn = document.getElementById('chatbotSend');
    const clearBtn = document.getElementById('chatbotClear');
    const chatInput = document.getElementById('chatbotInput');
    const messagesContainer = document.getElementById('chatbotMessages');
    const scInfoForm = document.getElementById('sc-info-form');
    const scHeaderStatus = document.getElementById('sc-header-status');
    const scMessages = document.getElementById('sc-messages');
    const scInputArea = document.getElementById('sc-input-area');
    const scMsgInput = document.getElementById('sc-msg-input');
    const scStartBtn = document.getElementById('sc-start-btn');
    const scSendBtn = document.getElementById('sc-send-btn');

    // Restore positions from localStorage
    try {
        const savedLauncherPos = localStorage.getItem('hades_launcher_position');
        if (savedLauncherPos) {
            const pos = JSON.parse(savedLauncherPos);
            launcher.style.right = 'auto';
            launcher.style.bottom = 'auto';
            launcher.style.left = pos.left + 'px';
            launcher.style.top = pos.top + 'px';
        }
        const savedWindowPos = localStorage.getItem('hades_window_position');
        if (savedWindowPos) {
            const pos = JSON.parse(savedWindowPos);
            windowEl.style.right = 'auto';
            windowEl.style.bottom = 'auto';
            windowEl.style.left = pos.left + 'px';
            windowEl.style.top = pos.top + 'px';
        }
    } catch (e) {
        console.error('Failed to restore chat coordinates', e);
    }

    // Nếu nút chat bị kéo ra ngoài màn hình → reset
    try {
        const rect = launcher.getBoundingClientRect();
        if (rect.right < 40 || rect.bottom < 40 || rect.left > window.innerWidth - 20 || rect.top > window.innerHeight - 20) {
            localStorage.removeItem('hades_launcher_position');
            launcher.style.left = '';
            launcher.style.top = '';
            launcher.style.right = '24px';
            launcher.style.bottom = '24px';
        }
    } catch (e) {}

    const HISTORY_KEY = 'hades_chat_history';
    const SC_STARTED_KEY = 'hades_support_started';
    const SC_LAST_ID_KEY = 'hades_support_last_id';

    let chatHistory = [];
    try {
        const saved = localStorage.getItem(HISTORY_KEY);
        if (saved) chatHistory = JSON.parse(saved) || [];
    } catch (e) {
        chatHistory = [];
    }

    let scStarted = localStorage.getItem(SC_STARTED_KEY) === '1';
    let scLastId = parseInt(localStorage.getItem(SC_LAST_ID_KEY) || '0', 10);
    let scEventSource = null;
    let scBadgeCount = 0;
    let activeTab = 'ai';

    let isDragging = false;
    let initialX, initialY;

    function openChatWindow(tab) {
        windowEl.classList.add('active');
        launcher.classList.add('hidden');
        switchTab(tab || 'ai');
        scBadgeCount = 0;
        if (supportBadge) supportBadge.style.display = 'none';
    }

    window.openHadesChat = function(tab) {
        openChatWindow(tab || 'support');
    };

    function switchTab(tab) {
        activeTab = tab;
        if (tab !== 'support' && scEventSource) {
            scEventSource.close();
            scEventSource = null;
        }
        [tabChatbot, tabSupport].forEach(function (t) {
            if (t) t.classList.toggle('active', t.dataset.tab === tab);
        });
        bodyAI.style.display = tab === 'ai' ? 'flex' : 'none';
        bodySupport.style.display = tab === 'support' ? 'flex' : 'none';

        if (tab === 'ai') {
            setTimeout(function () { chatInput.focus(); scrollToBottom(); }, 50);
        } else if (tab === 'support') {
            if (scStarted) {
                scShowChatUI();
                scLoadMessages(true, true);
                setTimeout(function () { scMsgInput.focus(); scScrollEnd(); }, 50);
            } else {
                scShowInfoForm();
            }
        }
    }

    launcher.addEventListener('click', function (e) {
        if (launcher.getAttribute('data-dragged') === 'true') {
            return;
        }
        openChatWindow('ai');
    });

    closeBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        windowEl.classList.remove('active');
        launcher.classList.remove('hidden');
        if (scEventSource) {
            scEventSource.close();
            scEventSource = null;
        }
    });

    tabChatbot.addEventListener('click', function () { switchTab('ai'); });
    tabSupport.addEventListener('click', function () { switchTab('support'); });

    /* ── Trợ lý AI (Gemini + lịch sử) ── */
    const DEFAULT_AI_GREETING = 'Xin chào! Mình là <strong>Hades AI</strong>. Hỏi về sản phẩm, giá, khuyến mãi hoặc chính sách shop nhé.';

    function updateAiStatus(source) {
        const el = document.getElementById('chatbotAiStatusText');
        if (!el) return;
        el.textContent = 'Trợ lý AI đang hoạt động';
    }

    function renderChatHistory() {
        if (!messagesContainer) return;
        messagesContainer.innerHTML = '';
        if (!chatHistory.length) {
            appendSearchMessage(DEFAULT_AI_GREETING, 'bot');
            return;
        }
        chatHistory.forEach(function (item) {
            appendSearchMessage(
                (item.text || '').replace(/\n/g, '<br>'),
                item.role === 'user' ? 'user' : 'bot'
            );
        });
        scrollToBottom();
    }
    renderChatHistory();

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            chatHistory = [];
            localStorage.removeItem(HISTORY_KEY);
            messagesContainer.innerHTML = '';
            appendSearchMessage(DEFAULT_AI_GREETING, 'bot');
            updateAiStatus('');
            fetch('/api/chatbot', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ clearHistory: true })
            });
            chatInput.focus();
        });
    }

    function sendSearchMessage() {
        const text = chatInput.value.trim();
        if (text === '') return;
        appendSearchMessage(text, 'user');
        chatInput.value = '';
        const typingId = showTypingIndicator();
        scrollToBottom();
        fetch('/api/chatbot', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: text, history: chatHistory })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            removeTypingIndicator(typingId);
            appendSearchMessage((data.reply || '').replace(/\n/g, '<br>'), 'bot');
            updateAiStatus(data.source || '');
            if (Array.isArray(data.history)) {
                chatHistory = data.history;
                localStorage.setItem(HISTORY_KEY, JSON.stringify(chatHistory));
            }
            scrollToBottom();
        })
        .catch(function () {
            removeTypingIndicator(typingId);
            appendSearchMessage('Xin lỗi, kết nối đang gián đoạn. Bạn thử lại sau nhé!', 'bot');
            scrollToBottom();
        });
    }

    sendBtn.addEventListener('click', sendSearchMessage);
    chatInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') sendSearchMessage();
    });

    function appendSearchMessage(text, sender) {
        const bubble = document.createElement('div');
        bubble.classList.add('chat-bubble', sender);
        bubble.innerHTML = text ? text.replace(/\n/g, '<br>') : '';
        messagesContainer.appendChild(bubble);
    }

    function showTypingIndicator() {
        const id = 'typing-' + Date.now();
        const bubble = document.createElement('div');
        bubble.classList.add('chat-bubble', 'bot', 'typing-bubble');
        bubble.id = id;
        bubble.innerHTML = '<div class="typing-indicator"><span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span></div>';
        messagesContainer.appendChild(bubble);
        return id;
    }

    function removeTypingIndicator(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    /* ── Chat trực tuyến (support) ── */
    function scShowInfoForm() {
        scInfoForm.style.display = 'flex';
        scHeaderStatus.style.display = 'none';
        scMessages.style.display = 'none';
        scInputArea.style.display = 'none';
    }

    function scShowChatUI() {
        scInfoForm.style.display = 'none';
        scHeaderStatus.style.display = 'flex';
        scMessages.style.display = 'flex';
        scInputArea.style.display = 'flex';
    }

    function scStartChat() {
        const name = document.getElementById('sc-name-input').value.trim();
        if (!name) { alert('Vui lòng nhập họ tên.'); return; }
        const email = document.getElementById('sc-email-input').value.trim();
        localStorage.setItem('sc_name', name);
        localStorage.setItem('sc_email', email);
        scStarted = true;
        localStorage.setItem(SC_STARTED_KEY, '1');
        scShowChatUI();
        scLoadMessages(true, true);
        scMsgInput.focus();
    }

    if (scStartBtn) scStartBtn.addEventListener('click', scStartChat);

    function scSend() {
        const message = scMsgInput.value.trim();
        if (!message) return;
        scMsgInput.value = '';
        reportUserTyping(false);
        const name = localStorage.getItem('sc_name') || 'Khách';
        const email = localStorage.getItem('sc_email') || '';
        scAppend({ sender: 'user', message: message, created_at: null });
        scScrollEnd();
        const fd = new FormData();
        fd.append('message', message);
        fd.append('name', name);
        fd.append('email', email);
        fetch('/api/support/send', {
            method: 'POST',
            headers: { 'X-CSRF-Token': <?= json_encode(csrf_token()) ?> },
            body: fd
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.id) {
                scLastId = Math.max(scLastId, parseInt(data.id, 10));
                localStorage.setItem(SC_LAST_ID_KEY, String(scLastId));
            }
        })
        .catch(function () {});
    }

    if (scSendBtn) scSendBtn.addEventListener('click', scSend);
    if (scMsgInput) {
        scMsgInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); scSend(); }
        });
    }

    function scLoadMessages(full, realtime) {
        const url = full ? '/api/support/messages' : '/api/support/messages?after=' + scLastId;
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (full) scMessages.innerHTML = '';
            (data.messages || []).forEach(function (msg) {
                const id = parseInt(msg.id, 10);
                if (full || id > scLastId) {
                    scAppend(msg);
                }
                if (id > scLastId) scLastId = id;
            });
            localStorage.setItem(SC_LAST_ID_KEY, String(scLastId));
            scScrollEnd();
            if (realtime) {
                scStartSSE();
            }
        })
        .catch(function () {});
    }

    function scStartSSE() {
        if (!scStarted || activeTab !== 'support' || !windowEl.classList.contains('active')) return;
        if (scEventSource) {
            scEventSource.close();
        }
        scEventSource = new EventSource('/api/support/sse?last_id=' + scLastId, { withCredentials: true });
        
        scEventSource.addEventListener('message', function (e) {
            var msg = JSON.parse(e.data);
            var id = parseInt(msg.id, 10);
            if (id > scLastId) {
                if (msg.sender === 'admin') {
                    scAppend(msg);
                    if (activeTab !== 'support' || !windowEl.classList.contains('active')) {
                        scBadgeCount++;
                        if (supportBadge) {
                            supportBadge.textContent = scBadgeCount;
                            supportBadge.style.display = 'flex';
                        }
                    }
                }
                scLastId = id;
                localStorage.setItem(SC_LAST_ID_KEY, String(scLastId));
                scScrollEnd();
            }
        });
        
        scEventSource.addEventListener('typing', function (e) {
            var data = JSON.parse(e.data);
            var typingIndicator = document.getElementById('sc-typing-indicator');
            if (data.typing) {
                if (!typingIndicator) {
                    typingIndicator = document.createElement('div');
                    typingIndicator.id = 'sc-typing-indicator';
                    typingIndicator.className = 'sc-bubble-wrap admin';
                    typingIndicator.innerHTML = '<div class="sc-bubble admin" style="background:#e5e7eb; color:#374151; padding: 6px 12px; font-size:12px; opacity:0.8; width: 100%; border-radius: 12px;"><div class="typing-indicator" style="display:flex; gap:3px; margin-bottom: 2px;"><span class="typing-dot" style="width:4px; height:4px; background:#4b5563; border-radius:50%; animation: bounce 1.4s infinite both;"></span><span class="typing-dot" style="width:4px; height:4px; background:#4b5563; border-radius:50%; animation: bounce 1.4s infinite both 0.2s;"></span><span class="typing-dot" style="width:4px; height:4px; background:#4b5563; border-radius:50%; animation: bounce 1.4s infinite both 0.4s;"></span></div>Admin đang nhập...</div>';
                    scMessages.appendChild(typingIndicator);
                    scScrollEnd();
                }
            } else {
                if (typingIndicator) {
                    typingIndicator.remove();
                }
            }
        });
        
        scEventSource.addEventListener('status', function (e) {
            var data = JSON.parse(e.data);
            var statusBadge = document.getElementById('sc-admin-status');
            if (statusBadge) {
                if (data.online) {
                    statusBadge.innerHTML = '<span class="badge bg-success" style="font-size: 9px;"><i class="bi bi-circle-fill me-1"></i>Admin Trực tuyến</span>';
                } else {
                    statusBadge.innerHTML = '<span class="badge bg-secondary" style="font-size: 9px;"><i class="bi bi-circle-fill me-1"></i>Admin Ngoại tuyến</span>';
                }
            }
        });
    }

    let userTypingTimeout = null;
    let isUserTypingState = false;

    function reportUserTyping(typing) {
        if (isUserTypingState === typing) return;
        isUserTypingState = typing;
        
        const fd = new FormData();
        fd.append('typing', typing ? '1' : '0');
        fetch('/api/support/typing', {
            method: 'POST',
            headers: { 'X-CSRF-Token': <?= json_encode(csrf_token()) ?> },
            body: fd
        }).catch(function() {});
    }

    if (scMsgInput) {
        scMsgInput.addEventListener('input', function () {
            reportUserTyping(true);
            if (userTypingTimeout) clearTimeout(userTypingTimeout);
            userTypingTimeout = setTimeout(function () {
                reportUserTyping(false);
            }, 2500);
        });
        
        scMsgInput.addEventListener('blur', function () {
            reportUserTyping(false);
        });
    }

    function scAppend(msg) {
        const wrap = document.createElement('div');
        wrap.className = 'sc-bubble-wrap ' + msg.sender;
        const bubble = document.createElement('div');
        bubble.className = 'sc-bubble ' + msg.sender;
        const time = msg.created_at ? msg.created_at.substr(11, 5) : new Date().toTimeString().substr(0, 5);
        bubble.innerHTML = msg.message.replace(/\n/g, '<br>') + '<div class="sc-time">' + time + '</div>';
        wrap.appendChild(bubble);
        
        var typingIndicator = document.getElementById('sc-typing-indicator');
        if (typingIndicator) {
            scMessages.insertBefore(wrap, typingIndicator);
        } else {
            scMessages.appendChild(wrap);
        }
    }

    function scScrollEnd() {
        scMessages.scrollTop = scMessages.scrollHeight;
    }

    /* Khôi phục session support khi reload trang */
    if (scStarted) {
        var savedName = localStorage.getItem('sc_name');
        if (savedName) {
            document.getElementById('sc-name-input').value = savedName;
            document.getElementById('sc-email-input').value = localStorage.getItem('sc_email') || '';
        }
    }

    /* Badge unread khi chat đóng — poll nhẹ, tạm dừng khi tab ẩn */
    var unreadTimer = null;
    function pollUnread() {
        if (windowEl.classList.contains('active')) return;
        if (document.hidden) return;
        fetch('/api/support/unread')
        .then(function (r) { return r.json(); })
        .then(function (data) {
            var c = parseInt(data.count || 0, 10);
            if (c > 0 && supportBadge) {
                supportBadge.textContent = c;
                supportBadge.style.display = 'flex';
            } else if (supportBadge && c === 0) {
                supportBadge.style.display = 'none';
            }
        })
        .catch(function () {});
    }
    function startUnreadPoll() {
        if (unreadTimer) return;
        pollUnread();
        unreadTimer = setInterval(pollUnread, 30000);
    }
    function stopUnreadPoll() {
        if (unreadTimer) {
            clearInterval(unreadTimer);
            unreadTimer = null;
        }
    }
    // Trì hoãn poll unread — không tranh bandwidth lúc trang vừa mở
    setTimeout(startUnreadPoll, 5000);
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) {
            stopUnreadPoll();
            if (scEventSource) {
                scEventSource.close();
                scEventSource = null;
            }
        } else {
            startUnreadPoll();
            if (activeTab === 'support' && windowEl.classList.contains('active') && scStarted) {
                scLoadMessages(true, true);
            }
        }
    });

    /* Draggable */
    header.addEventListener('mousedown', dragStart);
    document.addEventListener('mousemove', drag);
    document.addEventListener('mouseup', dragEnd);
    header.addEventListener('touchstart', dragStart, { passive: false });
    document.addEventListener('touchmove', drag, { passive: false });
    document.addEventListener('touchend', dragEnd);

    function dragStart(e) {
        if (e.target === closeBtn || e.target.closest('#chatbotClose')) return;
        var clientX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
        var clientY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;
        var rect = windowEl.getBoundingClientRect();
        initialX = clientX - rect.left;
        initialY = clientY - rect.top;
        isDragging = true;
    }

    function drag(e) {
        if (!isDragging) return;
        if (e.cancelable) e.preventDefault();
        var clientX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
        var clientY = e.type === 'touchmove' ? e.touches[0].clientY : e.clientY;
        var newLeft = clientX - initialX;
        var newTop = clientY - initialY;
        if (newLeft < 0) newLeft = 0;
        if (newTop < 0) newTop = 0;
        if (newLeft + windowEl.offsetWidth > window.innerWidth) newLeft = window.innerWidth - windowEl.offsetWidth;
        if (newTop + windowEl.offsetHeight > window.innerHeight) newTop = window.innerHeight - windowEl.offsetHeight;
        windowEl.style.bottom = 'auto';
        windowEl.style.right = 'auto';
        windowEl.style.left = newLeft + 'px';
        windowEl.style.top = newTop + 'px';
    }

    function dragEnd() {
        if (isDragging) {
            isDragging = false;
            var rect = windowEl.getBoundingClientRect();
            localStorage.setItem('hades_window_position', JSON.stringify({
                left: rect.left,
                top: rect.top
            }));
        }
    }

    // Launcher Draggable
    let launcherDragging = false;
    let launcherInitialX, launcherInitialY;
    let launcherStartX, launcherStartY;

    launcher.addEventListener('mousedown', launcherDragStart);
    launcher.addEventListener('touchstart', launcherDragStart, { passive: false });

    function launcherDragStart(e) {
        var clientX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
        var clientY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;
        launcherStartX = clientX;
        launcherStartY = clientY;
        var rect = launcher.getBoundingClientRect();
        launcherInitialX = clientX - rect.left;
        launcherInitialY = clientY - rect.top;
        launcherDragging = true;
    }

    function launcherDrag(e) {
        if (!launcherDragging) return;
        if (e.cancelable) e.preventDefault();
        var clientX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
        var clientY = e.type === 'touchmove' ? e.touches[0].clientY : e.clientY;
        var newLeft = clientX - launcherInitialX;
        var newTop = clientY - launcherInitialY;
        if (newLeft < 0) newLeft = 0;
        if (newTop < 0) newTop = 0;
        if (newLeft + launcher.offsetWidth > window.innerWidth) newLeft = window.innerWidth - launcher.offsetWidth;
        if (newTop + launcher.offsetHeight > window.innerHeight) newTop = window.innerHeight - launcher.offsetHeight;
        launcher.style.bottom = 'auto';
        launcher.style.right = 'auto';
        launcher.style.left = newLeft + 'px';
        launcher.style.top = newTop + 'px';
    }

    function launcherDragEnd(e) {
        if (!launcherDragging) return;
        launcherDragging = false;
        var clientX = e.type === 'touchend' ? (e.changedTouches[0] ? e.changedTouches[0].clientX : launcherStartX) : e.clientX;
        var clientY = e.type === 'touchend' ? (e.changedTouches[0] ? e.changedTouches[0].clientY : launcherStartY) : e.clientY;
        var distance = Math.sqrt(Math.pow(clientX - launcherStartX, 2) + Math.pow(clientY - launcherStartY, 2));
        if (distance > 5) {
            launcher.setAttribute('data-dragged', 'true');
            setTimeout(function() {
                launcher.removeAttribute('data-dragged');
            }, 50);
            var rect = launcher.getBoundingClientRect();
            localStorage.setItem('hades_launcher_position', JSON.stringify({
                left: rect.left,
                top: rect.top
            }));
        }
    }

    document.addEventListener('mousemove', launcherDrag);
    document.addEventListener('touchmove', launcherDrag, { passive: false });
    document.addEventListener('mouseup', launcherDragEnd);
    document.addEventListener('touchend', launcherDragEnd);

    // Keep elements inside window boundaries on resize
    window.addEventListener('resize', function() {
        var launcherRect = launcher.getBoundingClientRect();
        if (launcherRect.left + launcher.offsetWidth > window.innerWidth) {
            launcher.style.left = (window.innerWidth - launcher.offsetWidth) + 'px';
        }
        if (launcherRect.top + launcher.offsetHeight > window.innerHeight) {
            launcher.style.top = (window.innerHeight - launcher.offsetHeight) + 'px';
        }
        var windowRect = windowEl.getBoundingClientRect();
        if (windowRect.left + windowEl.offsetWidth > window.innerWidth) {
            windowEl.style.left = (window.innerWidth - windowEl.offsetWidth) + 'px';
        }
        if (windowRect.top + windowEl.offsetHeight > window.innerHeight) {
            windowEl.style.top = (window.innerHeight - windowEl.offsetHeight) + 'px';
        }
    });
});

// Theme toggle button action script
(function() {
    const toggleBtn = document.getElementById('theme-toggle');
    const toggleIcon = document.getElementById('theme-toggle-icon');

    function updateIcon(theme) {
        if (!toggleIcon) return;
        if (theme === 'dark') {
            toggleIcon.className = 'bi bi-moon-fill';
        } else {
            toggleIcon.className = 'bi bi-sun-fill';
        }
    }

    // Set initial icon
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
    updateIcon(currentTheme);

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const theme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            updateIcon(theme);
        });
    }
})();
</script>

<script>
/* ── Toast System ── */
(function () {
    var container = document.getElementById('toast-container');

    window.showToast = function (type, msg) {
        if (!container || !msg) return;
        var icons = { success: '✅', error: '❌', info: 'ℹ️', warning: '⚠️' };
        var item = document.createElement('div');
        item.className = 'toast-item toast-' + type;
        item.innerHTML =
            '<span class="toast-icon">' + (icons[type] || '📢') + '</span>' +
            '<span class="toast-msg">' + msg + '</span>' +
            '<button class="toast-close" aria-label="Đóng">×</button>';

        item.querySelector('.toast-close').addEventListener('click', function () {
            dismiss(item);
        });
        item.addEventListener('click', function (e) {
            if (!e.target.classList.contains('toast-close')) dismiss(item);
        });

        container.appendChild(item);
        var timer = setTimeout(function () { dismiss(item); }, 3600);
        item._timer = timer;
    };

    function dismiss(item) {
        clearTimeout(item._timer);
        item.classList.add('toast-out');
        setTimeout(function () { item.remove(); }, 320);
    }

    /* Hiện toast từ flash PHP khi trang load */
    document.addEventListener('DOMContentLoaded', function () {
        if (window.__initToast) {
            showToast(window.__initToast.type, window.__initToast.msg);
        }
    });
})();

/* ── Badge init khi load trang ── */
document.addEventListener('DOMContentLoaded', function () {
    var cartIcon = document.getElementById('cart-icon');
    if (!cartIcon) return;
    var count = parseInt(cartIcon.dataset.count || '0', 10);
    if (count > 0) {
        var badge = cartIcon.querySelector('.cart-badge');
        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'cart-badge';
            cartIcon.appendChild(badge);
        }
        badge.textContent = count;
        badge.style.display = 'flex';
    }
});
</script>

<script>
/* ── PWA Service Worker & Install ── */
(function () {
    const DISMISS_KEY = 'pwa_banner_dismissed_at';
    const DISMISS_MS = 3 * 24 * 60 * 60 * 1000; // 3 ngày
    let deferredPrompt = null;

    function isStandalone() {
        return window.matchMedia('(display-mode: standalone)').matches
            || window.navigator.standalone === true;
    }

    function isIos() {
        return /iphone|ipad|ipod/i.test(navigator.userAgent);
    }

    function wasBannerDismissed() {
        const t = parseInt(localStorage.getItem(DISMISS_KEY) || '0', 10);
        return t > 0 && (Date.now() - t) < DISMISS_MS;
    }

    function setInstallButtonsVisible(show) {
        document.querySelectorAll('#btnInstallApp, #btnInstallAppMobile').forEach(function (el) {
            if (!el) return;
            el.style.display = show ? '' : 'none';
        });
    }

    function removeBanner() {
        const banner = document.getElementById('pwaInstallBanner');
        if (banner) banner.remove();
    }

    function showHelpModal() {
        const intro = document.getElementById('pwaHelpIntro');
        const steps = document.getElementById('pwaHelpSteps');
        if (intro && steps) {
            if (isIos()) {
                intro.textContent = 'Trên iPhone/iPad: thêm Hades Shop vào Màn hình chính.';
                steps.innerHTML = ''
                    + '<li class="mb-2">Mở Safari, truy cập Shop Hades.</li>'
                    + '<li class="mb-2">Bấm nút <strong>Chia sẻ</strong> (biểu tượng vuông + mũi tên).</li>'
                    + '<li class="mb-2">Chọn <strong>「Thêm vào Màn hình chính」</strong>.</li>'
                    + '<li>Bấm <strong>Thêm</strong> — xong!</li>';
            } else {
                intro.textContent = 'Thêm Shop Hades ra màn hình chính để mua sắm nhanh hơn.';
                steps.innerHTML = ''
                    + '<li class="mb-2">Dùng Chrome hoặc Edge trên máy tính / Android.</li>'
                    + '<li class="mb-2">Bấm menu (⋮) → <strong>「Cài đặt ứng dụng」</strong> hoặc <strong>「Install app」</strong>.</li>'
                    + '<li>Hoặc bấm biểu tượng <strong>+</strong> / <strong>Tải app</strong> trên thanh địa chỉ.</li>';
            }
        }
        const modalEl = document.getElementById('pwaHelpModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }
    }

    function runInstallPrompt() {
        if (!deferredPrompt) {
            showHelpModal();
            return;
        }
        removeBanner();
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then(function (choice) {
            deferredPrompt = null;
            if (choice.outcome === 'accepted') {
                setInstallButtonsVisible(false);
            }
        });
    }

    function showInstallBanner() {
        if (isStandalone() || document.getElementById('pwaInstallBanner')) {
            return;
        }

        const banner = document.createElement('div');
        banner.className = 'pwa-install-banner';
        banner.id = 'pwaInstallBanner';
        banner.innerHTML = ''
            + '<div class="pwa-info">'
            + '  <img src="/image/logo.webp" alt="Shop Hades" class="pwa-icon">'
            + '  <div class="pwa-text">'
            + '    <h6>Tải ứng dụng Hades Shop</h6>'
            + '    <p>Mua sắm cực nhanh, ưu đãi độc quyền!</p>'
            + '  </div>'
            + '</div>'
            + '<div class="pwa-buttons">'
            + '  <button type="button" class="btn-pwa-install" id="btnPwaInstall">Cài đặt</button>'
            + '  <button type="button" class="btn-pwa-close" id="btnPwaClose" aria-label="Đóng">&times;</button>'
            + '</div>';

        document.body.appendChild(banner);

        if (window.HadesDrag) {
            HadesDrag.make(banner, { storageKey: 'hades_float_pwa_pos' });
        }

        document.getElementById('btnPwaInstall').addEventListener('click', runInstallPrompt);
        document.getElementById('btnPwaClose').addEventListener('click', function () {
            removeBanner();
            localStorage.setItem(DISMISS_KEY, String(Date.now()));
        });
    }

    function onInstallClick() {
        if (deferredPrompt) {
            runInstallPrompt();
        } else {
            showHelpModal();
        }
    }

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/sw.js').catch(function () {});
        });
    }

    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        deferredPrompt = e;
        if (!isStandalone()) {
            setInstallButtonsVisible(true);
            if (!wasBannerDismissed()) {
                showInstallBanner();
            }
        }
    });

    window.addEventListener('appinstalled', function () {
        deferredPrompt = null;
        removeBanner();
        setInstallButtonsVisible(false);
    });

    document.addEventListener('DOMContentLoaded', function () {
        if (isStandalone()) {
            setInstallButtonsVisible(false);
            return;
        }
        setInstallButtonsVisible(true);

        const btnHeader = document.getElementById('btnInstallApp');
        const btnMobile = document.getElementById('btnInstallAppMobile');
        if (btnHeader) btnHeader.addEventListener('click', onInstallClick);
        if (btnMobile) {
            btnMobile.addEventListener('click', function () {
                const offcanvas = document.getElementById('mobileMenu');
                if (offcanvas && typeof bootstrap !== 'undefined') {
                    const instance = bootstrap.Offcanvas.getInstance(offcanvas);
                    if (instance) instance.hide();
                }
                onInstallClick();
            });
        }

        // Trình duyệt không bắn beforeinstallprompt (Firefox, đã cài, v.v.)
        setTimeout(function () {
            if (!deferredPrompt && !wasBannerDismissed() && !isStandalone()) {
                // Chỉ auto-banner trên mobile để không làm phiền desktop
                if (window.innerWidth < 768) {
                    showInstallBanner();
                }
            }
        }, 4000);
    });
})();
</script>

<script src="/js/cart-anim.js"></script>
<script src="/js/refract-interactive.js"></script>

</body>
</html>
