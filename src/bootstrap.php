<?php

use App\Env;

define('BASE_PATH', dirname(__DIR__));
define('STORAGE_PATH', BASE_PATH . '/storage/data');

require BASE_PATH . '/src/Env.php';
Env::load(BASE_PATH . '/.env');

$sessionPath = BASE_PATH . '/storage/sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0775, true);
}
session_save_path($sessionPath);
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');

if (PHP_SAPI !== 'cli' && !headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(self), camera=(), microphone=()');
}

if (empty($_SESSION['_csrf'])) {
    $_SESSION['_csrf'] = bin2hex(random_bytes(32));
}

$GLOBALS['config'] = require BASE_PATH . '/config/config.php';
$config = $GLOBALS['config'];

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = BASE_PATH . '/src/' . $relative . '.php';
    if (is_file($file)) {
        require $file;
    }
});

function config(string $key, mixed $default = null): mixed
{
    global $config;
    $keys = explode('.', $key);
    $value = $config;
    foreach ($keys as $k) {
        if (!is_array($value) || !array_key_exists($k, $value)) {
            return $default;
        }
        $value = $value[$k];
    }
    return $value;
}

function view(string $name, array $data = [], ?string $layout = null): void
{
    extract($data);
    ob_start();
    $viewFile = BASE_PATH . '/views/' . str_replace('.', '/', $name) . '.php';
    if (!is_file($viewFile)) {
        http_response_code(500);
        echo "View not found: {$name}";
        return;
    }
    require $viewFile;
    $content = ob_get_clean();

    if ($layout === null && !str_starts_with($name, 'auth.')) {
        $layout = str_starts_with($name, 'admin.') ? 'layouts.admin' : 'layouts.client';
    }

    if ($layout) {
        $layoutFile = BASE_PATH . '/views/' . str_replace('.', '/', $layout) . '.php';
        require $layoutFile;
    } else {
        echo $content;
    }
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function flash(string $key, ?string $value = null): ?string
{
    if ($value !== null) {
        $_SESSION['_flash'][$key] = $value;
        return null;
    }
    $msg = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $msg;
}

function old(string $key, string $default = ''): string
{
    return htmlspecialchars($_SESSION['_old'][$key] ?? $default, ENT_QUOTES, 'UTF-8');
}

function setOld(array $data): void
{
    $_SESSION['_old'] = $data;
}

function clearOld(): void
{
    unset($_SESSION['_old']);
}

function authUser(): ?array
{
    if (isset($_SESSION['user']['id'])) {
        try {
            $db = \App\DB::connection();
            $stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute([$_SESSION['user']['id']]);
            $fresh = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($fresh) {
                $_SESSION['user'] = $fresh;
                return $fresh;
            }
        } catch (\Throwable) {
            // Fallback to session if DB is not available
        }
    }
    return $_SESSION['user'] ?? null;
}

function isAdmin(): bool
{
    $user = authUser();
    return $user && ($user['role'] ?? '') === 'admin';
}

function requireAdmin(): void
{
    if (!isAdmin()) {
        flash('error', 'Vui lòng đăng nhập quản trị.');
        redirect('/admin/login');
    }

    // Admin session timeout check (30 minutes = 1800 seconds)
    $lastActivity = $_SESSION['admin_last_activity'] ?? 0;
    $timeoutDuration = 1800;
    if ($lastActivity > 0 && (time() - $lastActivity) > $timeoutDuration) {
        unset($_SESSION['user'], $_SESSION['admin_last_activity']);
        flash('error', 'Phiên làm việc admin đã hết hạn do không hoạt động quá 30 phút.');
        redirect('/admin/login');
    }
    $_SESSION['admin_last_activity'] = time();
}

/** @param callable(mixed...): mixed $handler */
function admin(callable $handler): callable
{
    return function (...$args) use ($handler) {
        requireAdmin();
        return $handler(...$args);
    };
}

function logAdminAction(string $action, string $details): void
{
    $user = authUser();
    $adminId = $user ? (int)($user['id'] ?? 0) : null;
    $adminName = $user ? ($user['name'] ?? 'Admin') : 'Hệ thống';
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';

    try {
        $db = \App\DB::connection();
        $stmt = $db->prepare('INSERT INTO admin_audit_logs (admin_id, admin_name, action, details, ip_address) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$adminId, $adminName, $action, $details, $ipAddress]);
    } catch (\Throwable $e) {
        // Fail silently
    }
}

function csrf_token(): string
{
    return $_SESSION['_csrf'] ?? '';
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): bool
{
    $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    return $token !== '' && hash_equals(csrf_token(), $token);
}

function authenticate_session(array $user): void
{
    session_regenerate_id(true);
    unset($user['password']);
    $_SESSION['user'] = $user;
}

function formatMoney(int|float $amount): string
{
    return number_format((float) $amount, 0, ',', '.') . ' đ';
}

function formatVideoEmbedUrl(?string $url, int $startSec = 0): string
{
    $url = trim((string) $url);
    if ($url === '') {
        return 'https://www.youtube.com/embed/f02mOEt11OQ?enablejsapi=1' . ($startSec > 0 ? "&start={$startSec}" : '');
    }

    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $matches)) {
        $ytId = $matches[1];
        if ($ytId === 'dQw4w9WgXcQ') {
            $ytId = 'f02mOEt11OQ';
        }
        $embed = "https://www.youtube.com/embed/{$ytId}?enablejsapi=1";
        if ($startSec > 0) {
            $embed .= "&start={$startSec}";
        }
        return $embed;
    }

    return $url;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function is_gmail(string $email): bool
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    $email = strtolower($email);
    return str_ends_with($email, '@gmail.com') || str_ends_with($email, '@shop.test');
}

function imageUrl(?string $path, string $size = 'full'): string
{
    $path = trim((string) $path);
    if ($path === '') {
        return '/image/logo.webp';
    }
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }

    $path = ltrim($path, '/');
    if (!str_contains($path, '/')) {
        $path = 'image/' . $path;
    }

    if ($size !== 'full') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if ($ext !== '') {
            $base = substr($path, 0, -(strlen($ext) + 1));
            $resizedPath = $base . '_' . $size . '.' . $ext;
            if (file_exists(BASE_PATH . '/public/' . $resizedPath) || file_exists(BASE_PATH . '/' . $resizedPath)) {
                $path = $resizedPath;
            }
        }
    }

    // Prefer public/ folder when present
    if (file_exists(BASE_PATH . '/public/' . $path)) {
        // ok
    } elseif (!file_exists(BASE_PATH . '/' . $path) && !file_exists(BASE_PATH . '/public/' . $path)) {
        return '/image/logo.webp';
    }

    $segments = explode('/', $path);

    return '/' . implode('/', array_map('rawurlencode', $segments));
}

function vnpayBankName(string $code): string
{
    foreach (config('vnpay.banks', []) as $bank) {
        if (($bank['code'] ?? '') === $code) {
            return $bank['name'] ?? $code;
        }
    }

    return $code;
}

function enrichProductsForDisplay(array $products, array $categoryMap): array
{
    $reviewModel = new \App\Models\Review();
    $ratingsMap = $reviewModel->getAllAverageRatings();

    foreach ($products as &$product) {
        $product['category_name'] = $categoryMap[$product['category_id']] ?? ($product['category_name'] ?? 'Khác');
        $pId = $product['id'];
        if (isset($ratingsMap[$pId])) {
            $product['rating_avg'] = $ratingsMap[$pId]['average'];
            $product['rating_count'] = $ratingsMap[$pId]['count'];
        } else {
            $product['rating_avg'] = 5.0;
            $product['rating_count'] = 0;
        }
    }
    unset($product);

    return $products;
}

function updateQueryString(string $key, mixed $value): string
{
    $params = $_GET;
    if ($value === null || $value === "") {
        unset($params[$key]);
    } else {
        $params[$key] = $value;
    }
    if ($key === 'category') {
        unset($params['page']);
    }
    $queryString = http_build_query($params);
    return '/' . ($queryString !== '' ? '?' . $queryString : '');
}
