<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - <?= e(config('app_name')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/css/shop.min.css" rel="stylesheet">
    <link href="/css/glassy-theme.css" rel="stylesheet">
    <style>
        body {
            background-color: #08090d !important;
            background-image: 
                linear-gradient(rgba(8, 10, 16, 0.48), rgba(8, 10, 16, 0.68)),
                var(--current-wallpaper, url('/image/phone_glassy_bg.jpg')) !important;
            background-size: cover !important;
            background-position: center center !important;
            background-attachment: fixed !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, sans-serif;
            color: #f8fafc;
        }
        .auth-container {
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }
        .auth-card-modern {
            background: rgba(20, 24, 36, 0.65) !important;
            backdrop-filter: blur(28px) saturate(190%) !important;
            -webkit-backdrop-filter: blur(28px) saturate(190%) !important;
            border-radius: 22px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.6), inset 0 1.5px 2px rgba(255, 255, 255, 0.45);
            padding: 36px 32px;
            position: relative;
            overflow: hidden;
            color: #f8fafc;
        }
        .auth-card-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #ff4b2b 0%, #ff6a00 50%, #00f2fe 100%);
        }
        .form-control-modern {
            background: rgba(14, 16, 24, 0.6) !important;
            border-radius: 12px;
            padding: 12px 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff !important;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        .form-control-modern:focus {
            background: rgba(18, 20, 32, 0.85) !important;
            border-color: #ff6a00 !important;
            box-shadow: 0 0 0 3px rgba(255, 106, 0, 0.25) !important;
            color: #ffffff !important;
        }
        .btn-login-main {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-weight: 700;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.2);
        }
        .btn-login-main:hover {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
            color: #ffffff;
            transform: translateY(-1px);
        }
        .btn-google-auth {
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #ffffff;
            color: #1e293b;
            font-weight: 600;
            font-size: 14px;
            padding: 12px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none !important;
            transition: all 0.2s ease;
        }
        .btn-google-auth:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            color: #0f172a;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-card-modern">
        <!-- Logo & Header -->
        <div class="text-center mb-4">
            <a href="/">
                <img src="/image/logo.webp" alt="Logo" style="height: 64px; object-fit: contain;">
            </a>
            <h4 class="fw-bold mt-3 text-dark mb-1">Đăng Nhập Tài Khoản</h4>
            <p class="text-muted small mb-0">Chào mừng bạn quay lại với Hades Shop</p>
        </div>

        <?php if ($msg = flash('success')): ?>
            <div class="alert alert-success border-0 rounded-3 small py-2.5 px-3 mb-3 fw-medium">
                <i class="bi bi-check-circle-fill me-1"></i><?= e($msg) ?>
            </div>
        <?php endif; ?>

        <?php if ($msg = flash('error')): ?>
            <div class="alert alert-danger border-0 rounded-3 small py-2.5 px-3 mb-3 fw-medium">
                <i class="bi bi-exclamation-triangle-fill me-1"></i><?= e($msg) ?>
            </div>
        <?php endif; ?>

        <!-- Form đăng nhập bằng Email / Mật khẩu -->
        <form method="post" action="/login">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label small fw-bold text-dark">Email đăng nhập</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" name="email" class="form-control form-control-modern border-start-0" value="<?= old('email') ?>" required placeholder="name@example.com" style="border-radius: 0 12px 12px 0;">
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label small fw-bold text-dark mb-0">Mật khẩu</label>
                    <a href="/forgot-password" class="small text-decoration-none" style="color: #ef4444; font-size: 12px; font-weight: 600;">Quên mật khẩu?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" name="password" class="form-control form-control-modern border-start-0" required placeholder="••••••••" style="border-radius: 0 12px 12px 0;">
                </div>
            </div>

            <button type="submit" class="btn-login-main mt-2">
                Đăng Nhập <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </form>

        <div class="d-flex align-items-center my-4">
            <hr class="flex-grow-1 text-muted opacity-25 m-0">
            <span class="mx-3 text-muted small fw-semibold" style="font-size: 11px; letter-spacing: 0.05em; text-transform: uppercase;">Xác thực qua Google Gmail</span>
            <hr class="flex-grow-1 text-muted opacity-25 m-0">
        </div>

        <!-- Form Nhập Gmail để Nhận Mã OTP Xác Minh -->
        <form method="get" action="/login/google" class="mb-3">
            <label class="form-label small fw-semibold text-muted mb-1" style="font-size: 12px;">Nhập địa chỉ Gmail để nhận mã OTP:</label>
            <div class="input-group">
                <input type="email" name="email" class="form-control form-control-sm border-end-0" placeholder="user.google@gmail.com" required style="border-radius: 10px 0 0 10px; font-size: 13px;">
                <button type="submit" class="btn btn-danger btn-sm fw-bold px-3" style="border-radius: 0 10px 10px 0; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none;">
                    Gửi OTP <i class="bi bi-send-fill ms-1"></i>
                </button>
            </div>
        </form>

        <!-- Official Google OAuth Button -->
        <a href="/login/google" class="btn-google-auth">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
            </svg>
            <span>Đăng nhập nhanh bằng Google</span>
        </a>

        <div class="text-center mt-4 pt-2">
            <p class="small text-muted mb-0">
                Chưa có tài khoản? <a href="/register" class="fw-bold text-decoration-none" style="color: #ef4444;">Đăng ký ngay</a>
            </p>
            <div class="mt-2" style="font-size: 12px;">
                <a href="/admin/login" class="text-muted text-decoration-none me-2">Quản trị Admin</a> ·
                <a href="/" class="text-muted text-decoration-none ms-2">Trang chủ</a>
            </div>
        </div>
    </div>
</div>

<script src="/js/refract-interactive.js"></script>
</body>
</html>
