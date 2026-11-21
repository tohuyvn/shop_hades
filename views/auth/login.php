<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Hades Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #030712;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            overflow-x: hidden;
            position: relative;
            user-select: none;
            transition: background 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Light state background */
        body.lamp-on {
            background-color: #080d1a;
        }

        /* Canvas Bụi sáng & Đom đóm lơ lửng */
        #firefliesCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        /* LAMP STRUCTURE */
        .lamp-scene {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 650px;
            height: 380px;
            pointer-events: none;
            z-index: 15;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .lamp-top-bar {
            width: 80px;
            height: 6px;
            background: #1f2937;
            border-radius: 0 0 4px 4px;
        }

        .lamp-wire {
            width: 3px;
            height: 55px;
            background: #374151;
        }

        .lamp-shade {
            width: 140px;
            height: 48px;
            background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
            clip-path: polygon(25% 0%, 75% 0%, 100% 100%, 0% 100%);
            border-radius: 6px 6px 0 0;
            position: relative;
            box-shadow: 0 4px 15px rgba(0,0,0,0.6);
        }

        .lamp-bulb {
            width: 32px;
            height: 28px;
            background: #27272a;
            border-radius: 50%;
            margin-top: -12px;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 0 rgba(251, 191, 36, 0);
        }

        /* PULL CORD (DÂY GIẬT CÔNG TẮC BẬT ĐÈN) */
        .lamp-cord-wrapper {
            position: absolute;
            top: 100px;
            right: calc(50% - 90px);
            pointer-events: auto;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 40;
            transform-origin: top center;
            transition: transform 0.4s ease;
        }
        .lamp-cord-wrapper:hover {
            transform: scale(1.08);
        }

        .lamp-cord-string {
            width: 2.5px;
            height: 130px;
            background: linear-gradient(to bottom, #4b5563, #cbd5e1);
            transition: height 0.15s ease-out;
            box-shadow: 0 0 6px rgba(255,255,255,0.2);
        }

        .lamp-cord-handle {
            width: 18px;
            height: 26px;
            background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
            border-radius: 9px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.6), 0 0 12px rgba(245, 158, 11, 0.6);
            border: 1.5px solid #fef3c7;
            position: relative;
        }

        /* HINT BAN ĐẦU HƯỚNG DẪN HỌC VIÊN GIẬT DÂY BẬT ĐÈN */
        .lamp-cord-tooltip {
            position: absolute;
            left: 30px;
            top: 130px;
            background: rgba(245, 158, 11, 0.15);
            color: #fef08a;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 30px;
            border: 1px solid rgba(250, 204, 21, 0.5);
            white-space: nowrap;
            pointer-events: none;
            backdrop-filter: blur(8px);
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
            animation: pulseHint 1.8s infinite ease-in-out;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        @keyframes pulseHint {
            0%, 100% { opacity: 0.85; transform: translateX(0) scale(1); }
            50% { opacity: 1; transform: translateX(6px) scale(1.05); }
        }

        /* MÀN ĐÊM TỐI / HƯỚNG DẪN KHI ĐÈN ĐANG TẮT */
        .dark-room-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 10;
            pointer-events: none;
            transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dark-room-hint {
            text-align: center;
            color: #64748b;
            margin-top: 180px;
            pointer-events: auto;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .dark-room-hint:hover {
            color: #fef08a;
        }
        .dark-room-hint i {
            font-size: 32px;
            display: block;
            margin-bottom: 8px;
            color: #f59e0b;
            animation: bounceUpDown 2s infinite ease-in-out;
        }

        @keyframes bounceUpDown {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        body.lamp-on .dark-room-overlay {
            opacity: 0;
            visibility: hidden;
        }

        /* LIGHT CONE EMISSION */
        .light-cone {
            position: absolute;
            top: 105px;
            left: 50%;
            transform: translateX(-50%);
            width: 680px;
            height: 800px;
            clip-path: polygon(38% 0%, 62% 0%, 100% 100%, 0% 100%);
            background: radial-gradient(ellipse at 50% 0%, rgba(251, 191, 36, 0.45) 0%, rgba(245, 158, 11, 0.18) 45%, rgba(0, 0, 0, 0) 78%);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 5;
        }

        /* Bulb ON state */
        body.lamp-on .lamp-bulb {
            background: #fffbeb;
            box-shadow: 0 0 45px 18px rgba(251, 191, 36, 0.85), 0 0 90px 40px rgba(245, 158, 11, 0.45);
        }

        body.lamp-on .light-cone {
            opacity: 1;
        }

        /* MAIN LOGIN FORM (MẶC ĐỊNH ẨN KHI TẮT ĐÈN, HIỆN LÊN KHI BẬT ĐÈN) */
        .login-wrapper {
            position: relative;
            z-index: 20;
            width: 100%;
            max-width: 440px;
            padding: 24px;
            margin-top: 110px;
            transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), transform 0.8s cubic-bezier(0.4, 0, 0.2, 1), filter 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            
            /* TRẠNG THÁI TẮT ĐÈN BAN ĐẦU: ẨN HOÀN TOÀN */
            opacity: 0;
            pointer-events: none;
            transform: translateY(30px) scale(0.96);
            filter: blur(12px) brightness(0.2);
        }

        /* TRẠNG THÁI BẬT ĐÈN: HIỆN FORM SÁNG RÕ CHUẨN LAMP LOGIN V5 */
        body.lamp-on .login-wrapper {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0) scale(1);
            filter: blur(0px) brightness(1);
        }

        .login-card {
            background: rgba(24, 25, 32, 0.58);
            backdrop-filter: blur(28px) saturate(190%);
            -webkit-backdrop-filter: blur(28px) saturate(190%);
            border-radius: 26px;
            padding: 38px 34px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            box-shadow: 
                0 0 0 1px rgba(255, 255, 255, 0.18),
                inset 0 1.5px 2px rgba(255, 255, 255, 0.45),
                inset 0 -1.5px 3px rgba(0, 0, 0, 0.4),
                0 25px 60px -10px rgba(0, 0, 0, 0.75),
                0 0 45px rgba(255, 106, 0, 0.16);
            position: relative;
            overflow: hidden;
        }

        /* Rainbow / Prismatic Edge Refraction Simulation (Đặc trưng Glassy của hệ thống) */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2.5px;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255, 106, 0, 0.8) 20%, 
                rgba(255, 255, 255, 0.95) 50%, 
                rgba(0, 229, 255, 0.8) 80%, 
                transparent 100%
            );
            opacity: 0.85;
            pointer-events: none;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            background: rgba(245, 158, 11, 0.12);
            border: 1px solid rgba(245, 158, 11, 0.35);
            border-radius: 9999px;
            color: #fbbf24;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .brand-title {
            color: #ffffff;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }

        .brand-subtitle {
            color: #94a3b8;
            font-size: 13px;
        }

        .form-floating-custom {
            position: relative;
            margin-bottom: 18px;
        }

        .form-control-lamp {
            width: 100%;
            background: rgba(15, 17, 24, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 14px;
            padding: 14px 16px 14px 44px;
            color: #ffffff;
            font-size: 14px;
            backdrop-filter: blur(14px);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.35);
            transition: all 0.25s ease;
            outline: none;
        }

        .form-control-lamp:focus {
            background: rgba(18, 20, 30, 0.85);
            border-color: #ff6a00;
            box-shadow: 0 0 0 3px rgba(255, 106, 0, 0.25), inset 0 1px 2px rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .form-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
            pointer-events: none;
            transition: color 0.2s ease;
        }

        .form-control-lamp:focus ~ .form-icon {
            color: #ff6a00;
        }

        .btn-lamp-primary {
            width: 100%;
            padding: 13px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 14px;
            color: #ffffff;
            background: linear-gradient(135deg, #ff6a00 0%, #ee0979 100%);
            border: none;
            box-shadow: 0 6px 20px rgba(255, 106, 0, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.3);
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
        }

        .btn-lamp-primary:hover {
            background: linear-gradient(135deg, #ff7a1a 0%, #f41e8a 100%);
            box-shadow: 0 8px 25px rgba(255, 106, 0, 0.55), inset 0 1px 1px rgba(255, 255, 255, 0.4);
            transform: translateY(-1px);
            color: #ffffff;
        }

        .btn-google-lamp {
            width: 100%;
            padding: 11px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: #f1f5f9;
            font-weight: 600;
            font-size: 13px;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-google-lamp:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.3);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.35);
        }

        .lamp-switcher-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 50;
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 9999px;
            padding: 9px 18px;
            color: #e2e8f0;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            backdrop-filter: blur(8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.6);
            transition: all 0.2s ease;
        }
        .lamp-switcher-btn:hover {
            background: rgba(30, 41, 59, 0.95);
            border-color: #f59e0b;
            color: #fef08a;
        }
    </style>
</head>
<!-- BAN ĐẦU MẶC ĐỊNH LÀ TẮT ĐÈN (KHÔNG CÓ CLASS lamp-on) -->
<body>

<!-- Canvas Hiệu ứng Bụi sáng & Đom đóm lơ lửng -->
<canvas id="firefliesCanvas"></canvas>

<!-- Đèn trần nghệ thuật tương tác (Interactive Hanging Lamp) -->
<div class="lamp-scene">
    <div class="lamp-top-bar"></div>
    <div class="lamp-wire"></div>
    <div class="lamp-shade"></div>
    <div class="lamp-bulb"></div>
    
    <!-- Dây giật công tắc bật/tắt (Interactive Cord Switch) -->
    <div id="lampCord" class="lamp-cord-wrapper" title="Bấm hoặc giật dây để Bật / Tắt đèn">
        <div id="cordString" class="lamp-cord-string"></div>
        <div class="lamp-cord-handle"></div>
        <div id="cordHint" class="lamp-cord-tooltip">
            <i class="bi bi-hand-index-thumb"></i> Giật dây để Bật đèn
        </div>
    </div>

    <!-- Luồng ánh sáng hình nón chiếu xuống form -->
    <div class="light-cone"></div>
</div>

<!-- Lời nhắc khi phòng đang tối -->
<div class="dark-room-overlay" id="darkRoomOverlay">
    <div class="dark-room-hint" id="darkRoomHint">
        <i class="bi bi-lightbulb"></i>
        <div class="fw-bold fs-5 text-light mb-1">Căn phòng đang tối...</div>
        <div class="small text-muted">Kéo chiếc dây đèn ở trên để thắp sáng và đăng nhập</div>
    </div>
</div>

<!-- Nút chuyển đổi nhanh ở góc dưới màn hình -->
<button type="button" id="toggleLampBtn" class="lamp-switcher-btn">
    <i class="bi bi-lightbulb text-secondary"></i>
    <span id="lampStatusText">Bật đèn</span>
</button>

<!-- KHUNG ĐĂNG NHẬP CHÍNH (CHỈ HIỂN THỊ KHI ĐÈN ĐƯỢC BẬT) -->
<div class="login-wrapper" id="loginWrapper">
    <div class="login-card">
        <!-- Logo & Header -->
        <div class="text-center mb-4">
            <a href="/" class="d-inline-block text-decoration-none">
                <div class="brand-badge">
                    <i class="bi bi-mortarboard-fill"></i> Hades Academy
                </div>
                <h1 class="brand-title">Đăng Nhập Học Viên</h1>
                <p class="brand-subtitle">Chào mừng bạn gia nhập nền tảng đào tạo công nghệ</p>
            </a>
        </div>

        <?php if ($msg = flash('success')): ?>
            <div class="alert alert-success border-0 rounded-4 small py-2 px-3 mb-3 fw-medium bg-success bg-opacity-25 text-white">
                <i class="bi bi-check-circle-fill me-1 text-success"></i><?= e($msg) ?>
            </div>
        <?php endif; ?>

        <?php if ($msg = flash('error')): ?>
            <div class="alert alert-danger border-0 rounded-4 small py-2 px-3 mb-3 fw-medium bg-danger bg-opacity-25 text-white">
                <i class="bi bi-exclamation-triangle-fill me-1 text-danger"></i><?= e($msg) ?>
            </div>
        <?php endif; ?>

        <!-- Form Email & Password -->
        <form method="post" action="/login">
            <?= csrf_field() ?>

            <div class="form-floating-custom">
                <input type="email" name="email" class="form-control-lamp" value="<?= old('email') ?>" required placeholder="Email học viên (name@example.com)">
                <i class="bi bi-envelope form-icon"></i>
            </div>

            <div class="form-floating-custom">
                <input type="password" name="password" class="form-control-lamp" required placeholder="Mật khẩu tài khoản">
                <i class="bi bi-lock form-icon"></i>
                <div class="d-flex justify-content-end mt-1">
                    <a href="/forgot-password" class="text-decoration-none small" style="color: #f59e0b; font-size: 12px; font-weight: 600;">
                        Quên mật khẩu?
                    </a>
                </div>
            </div>

            <button type="submit" class="btn-lamp-primary mt-2">
                <span>Vào Lớp Học Ngay</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <div class="d-flex align-items-center my-3">
            <hr class="flex-grow-1 border-secondary opacity-25 m-0">
            <span class="mx-3 text-secondary small fw-semibold" style="font-size: 11px; letter-spacing: 0.05em; text-transform: uppercase;">Hoặc tiếp tục với</span>
            <hr class="flex-grow-1 border-secondary opacity-25 m-0">
        </div>

        <!-- Official Google OAuth Button -->
        <a href="/login/google" class="btn-google-lamp mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 48 48">
                <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
            </svg>
            <span>Đăng nhập nhanh với Google</span>
        </a>

        <!-- Form Nhập Gmail để Nhận Mã OTP Xác Minh -->
        <form method="get" action="/login/google">
            <div class="input-group">
                <input type="email" name="email" class="form-control form-control-sm bg-dark border-secondary text-white" placeholder="user.google@gmail.com" required style="border-radius: 10px 0 0 10px; font-size: 13px;">
                <button type="submit" class="btn btn-warning btn-sm fw-bold px-3" style="border-radius: 0 10px 10px 0;">
                    Nhận OTP <i class="bi bi-send-fill ms-1"></i>
                </button>
            </div>
        </form>

        <div class="text-center mt-4 pt-1">
            <p class="small text-secondary mb-0">
                Chưa có tài khoản? <a href="/register" class="fw-bold text-decoration-none" style="color: #fbbf24;">Đăng ký học viên</a>
            </p>
            <div class="mt-2" style="font-size: 12px;">
                <a href="/courses" class="text-secondary text-decoration-none me-2"><i class="bi bi-compass"></i> Khám phá khóa học</a> ·
                <a href="/admin/login" class="text-secondary text-decoration-none ms-2"><i class="bi bi-shield-lock"></i> Quản trị viên</a>
            </div>
        </div>
    </div>
</div>

<script>
// --- HIỆU ỨNG GIẬT DÂY BẬT ĐÈN (LAMP LOGIN V5 INTERACTION) ---
const body = document.body;
const lampCord = document.getElementById('lampCord');
const cordString = document.getElementById('cordString');
const toggleLampBtn = document.getElementById('toggleLampBtn');
const lampStatusText = document.getElementById('lampStatusText');
const cordHint = document.getElementById('cordHint');
const darkRoomHint = document.getElementById('darkRoomHint');

// Mặc định ban đầu: TẮT ĐÈN (false)
let isLampOn = false;

// Nếu trang có flash lỗi hoặc thành công từ lần submit trước, tự động bật đèn để học viên thấy thông báo
<?php if (flash('error') || flash('success') || !empty(old('email'))): ?>
isLampOn = true;
body.classList.add('lamp-on');
lampStatusText.textContent = 'Tắt đèn';
toggleLampBtn.innerHTML = '<i class="bi bi-lightbulb-fill text-warning"></i><span>Tắt đèn</span>';
if (cordHint) cordHint.innerHTML = '<i class="bi bi-hand-index-thumb"></i> Giật dây để Tắt đèn';
<?php endif; ?>

function toggleLamp() {
    isLampOn = !isLampOn;
    if (isLampOn) {
        body.classList.add('lamp-on');
        lampStatusText.textContent = 'Tắt đèn';
        toggleLampBtn.innerHTML = '<i class="bi bi-lightbulb-fill text-warning"></i><span>Tắt đèn</span>';
        if (cordHint) cordHint.innerHTML = '<i class="bi bi-hand-index-thumb"></i> Giật dây để Tắt đèn';
    } else {
        body.classList.remove('lamp-on');
        lampStatusText.textContent = 'Bật đèn';
        toggleLampBtn.innerHTML = '<i class="bi bi-lightbulb text-secondary"></i><span>Bật đèn</span>';
        if (cordHint) cordHint.innerHTML = '<i class="bi bi-hand-index-thumb"></i> Giật dây để Bật đèn';
    }
}

function triggerCordPull() {
    // Hiệu ứng giật dây đàn hồi
    cordString.style.height = '155px';
    lampCord.style.transform = 'translateY(20px) rotate(5deg)';
    
    // Web Audio API: Giả lập tiếng "click / snap" của công tắc đèn cơ học
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.frequency.value = isLampOn ? 320 : 540;
        gain.gain.setValueAtTime(0.09, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.12);
        osc.start();
        osc.stop(audioCtx.currentTime + 0.12);
    } catch(e) {}

    setTimeout(() => {
        cordString.style.height = '130px';
        lampCord.style.transform = 'translateY(0) rotate(0deg)';
        toggleLamp();
    }, 150);
}

// Click vào dây giật
lampCord.addEventListener('click', triggerCordPull);

// Click vào chữ hướng dẫn ở giữa phòng tối cũng tự giật dây bật đèn
if (darkRoomHint) {
    darkRoomHint.addEventListener('click', triggerCordPull);
}

// Nút góc dưới màn hình
toggleLampBtn.addEventListener('click', toggleLamp);

// --- CANVAS HIỆU ỨNG ĐOM ĐÓM & BỤI SÁNG (FIREFLIES & DUST) ---
const canvas = document.getElementById('firefliesCanvas');
const ctx = canvas.getContext('2d');
let width = canvas.width = window.innerWidth;
let height = canvas.height = window.innerHeight;

window.addEventListener('resize', () => {
    width = canvas.width = window.innerWidth;
    height = canvas.height = window.innerHeight;
});

const particles = [];
const particleCount = 45;

for (let i = 0; i < particleCount; i++) {
    particles.push({
        x: Math.random() * width,
        y: Math.random() * height,
        radius: Math.random() * 2 + 0.5,
        speedX: (Math.random() - 0.5) * 0.6,
        speedY: (Math.random() - 0.5) * 0.6 - 0.2,
        alpha: Math.random() * 0.8 + 0.2,
        pulseSpeed: Math.random() * 0.02 + 0.01,
        pulseVal: Math.random() * Math.PI
    });
}

function animateParticles() {
    ctx.clearRect(0, 0, width, height);

    particles.forEach(p => {
        p.x += p.speedX;
        p.y += p.speedY;
        p.pulseVal += p.pulseSpeed;

        if (p.x < 0) p.x = width;
        if (p.x > width) p.x = 0;
        if (p.y < 0) p.y = height;
        if (p.y > height) p.y = 0;

        const currentAlpha = isLampOn 
            ? (p.alpha * 0.55 + Math.sin(p.pulseVal) * 0.3)
            : (p.alpha * 0.12 + Math.sin(p.pulseVal) * 0.05);

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
        ctx.fillStyle = isLampOn ? `rgba(253, 224, 71, ${Math.max(0.05, currentAlpha)})` : `rgba(148, 163, 184, ${Math.max(0.02, currentAlpha)})`;
        ctx.shadowBlur = isLampOn ? 10 : 2;
        ctx.shadowColor = isLampOn ? '#f59e0b' : '#64748b';
        ctx.fill();
    });

    requestAnimationFrame(animateParticles);
}

animateParticles();
</script>

</body>
</html>
