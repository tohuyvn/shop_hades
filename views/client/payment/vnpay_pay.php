<?php
$title = 'Thanh toán VNPay';
$test = config('vnpay.test_card');
$bankCode = $card['bank'] ?? ($test['bank'] ?? 'NCB');
$bankName = $card['bank_name'] ?? vnpayBankName($bankCode);
$cardNumber = $card['card_number'] ?? ($test['card_number'] ?? '9704198526191432');
$cardHolder = $card['cardholder'] ?? ($test['cardholder'] ?? 'NGUYEN VAN A');
$issueDate = $card['issue_date'] ?? ($test['issue_date'] ?? '07/15');
$otpTest = $test['otp'] ?? '123456';
?>

<style>
/* VNPay Glassmorphism Refract Styles */
.vnpay-container {
    max-width: 820px;
    margin: 0 auto;
}

.vnpay-card-wrapper {
    background: rgba(22, 24, 34, 0.65) !important;
    backdrop-filter: blur(28px) saturate(180%);
    -webkit-backdrop-filter: blur(28px) saturate(180%);
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.16);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.45), inset 0 1px 1px rgba(255, 255, 255, 0.3);
    padding: 32px;
    position: relative;
    overflow: hidden;
}

.vnpay-card-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #005baa 0%, #00a5eb 35%, #ff5b00 70%, #ff8c00 100%);
}

/* Realistic Bank Card Visual */
.virtual-atm-card {
    background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 18px;
    padding: 24px;
    color: #ffffff;
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.4);
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}

.virtual-atm-card::after {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 180px;
    height: 180px;
    background: radial-gradient(circle, rgba(0, 165, 235, 0.25) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.atm-chip {
    width: 44px;
    height: 32px;
    background: linear-gradient(135deg, #ffd700 0%, #cca000 100%);
    border-radius: 6px;
    box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.6);
    position: relative;
}

.atm-chip::after {
    content: '';
    position: absolute;
    inset: 4px;
    border: 1px solid rgba(0, 0, 0, 0.25);
    border-radius: 3px;
}

.atm-card-number {
    font-family: 'SF Mono', 'Courier New', monospace;
    font-size: 1.25rem;
    letter-spacing: 3px;
    color: #ffffff;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    font-weight: 600;
}

.atm-label {
    font-size: 0.72rem;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.6);
    letter-spacing: 1px;
    margin-bottom: 2px;
}

.atm-value {
    font-size: 0.95rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    color: #fff;
}

/* Glassy Order Summary */
.order-summary-box {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 18px 22px;
    margin-bottom: 24px;
}

.otp-input-field {
    background: rgba(15, 17, 24, 0.7) !important;
    border: 1.5px solid rgba(255, 255, 255, 0.22) !important;
    color: #ffffff !important;
    font-family: 'SF Mono', monospace;
    font-size: 1.5rem !important;
    letter-spacing: 8px;
    text-align: center;
    border-radius: 16px !important;
    padding: 14px 20px !important;
    transition: all 0.3s ease;
}

.otp-input-field:focus {
    background: rgba(20, 24, 38, 0.9) !important;
    border-color: #00a5eb !important;
    box-shadow: 0 0 0 4px rgba(0, 165, 235, 0.25) !important;
}

.btn-vnpay-submit {
    background: linear-gradient(135deg, #005baa 0%, #0092e0 100%) !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
    color: #ffffff !important;
    font-size: 1.05rem;
    font-weight: 700;
    padding: 14px 24px;
    border-radius: 16px;
    box-shadow: 0 10px 24px rgba(0, 91, 170, 0.35);
    transition: all 0.25s ease;
}

.btn-vnpay-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(0, 91, 170, 0.5);
    color: #fff !important;
}

.badge-sandbox {
    background: rgba(255, 106, 0, 0.18);
    border: 1px solid rgba(255, 106, 0, 0.4);
    color: #ff9d5c;
    font-size: 0.75rem;
    padding: 4px 10px;
    border-radius: 20px;
    font-weight: 600;
}
</style>

<div class="vnpay-container py-3">
    <!-- Breadcrumb / Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-1 d-flex align-items-center gap-2 text-white">
                <i class="bi bi-shield-check text-info"></i> Cổng Thanh Toán VNPay
            </h3>
            <p class="text-secondary small mb-0">Xác thực giao dịch an toàn qua chuẩn 3D Secure / OTP</p>
        </div>
        <span class="badge-sandbox">
            <i class="bi bi-cpu me-1"></i> SANDBOX TEST
        </span>
    </div>

    <div class="vnpay-card-wrapper">
        <!-- Brand & Amount Banner -->
        <div class="d-flex flex-wrap justify-content-between align-items-center pb-4 mb-4 border-bottom border-secondary border-opacity-25 gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-white rounded-3 px-3 py-2 shadow-sm" style="min-width: 110px; height: 46px;">
                    <span style="font-size: 20px; font-weight: 900; letter-spacing: -0.5px;">
                        <span style="color: #005baa;">VN</span><span style="color: #ed1c24;">PAY</span>
                    </span>
                </div>
                <div>
                    <div class="text-secondary small">Mã đơn hàng</div>
                    <div class="fw-bold text-white fs-6">#<?= (int)$order['id'] ?></div>
                </div>
            </div>

            <div class="text-md-end">
                <div class="text-secondary small">Số tiền cần thanh toán</div>
                <div class="fw-bold text-danger fs-4"><?= formatMoney($order['total_amount']) ?></div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column: Realistic ATM Card Visual -->
            <div class="col-lg-6">
                <div class="virtual-atm-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold fs-5 tracking-wide text-white"><?= e($bankName) ?></span>
                            <span class="badge bg-light text-dark fw-bold px-2 py-1 small">NAPAS 247</span>
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="atm-chip"></div>
                            <i class="bi bi-wifi fs-4 text-white opacity-75"></i>
                        </div>
                        <div class="atm-card-number mb-4">
                            <?= chunk_split(e($cardNumber), 4, ' ') ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-end pt-3 border-top border-white border-opacity-10">
                        <div>
                            <div class="atm-label">Chủ thẻ / Card Holder</div>
                            <div class="atm-value text-uppercase"><?= e($cardHolder) ?></div>
                        </div>
                        <div class="text-end">
                            <div class="atm-label">Hiệu lực / Date</div>
                            <div class="atm-value"><?= e($issueDate) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Verification Form & OTP -->
            <div class="col-lg-6 d-flex flex-column justify-content-between">
                <div>
                    <!-- Order Items summary -->
                    <?php if (!empty($items)): ?>
                    <div class="order-summary-box mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-semibold text-secondary">
                                <i class="bi bi-bag-check me-1"></i> Khóa học / Sản phẩm
                            </span>
                            <span class="badge bg-secondary bg-opacity-25 text-white small"><?= count($items) ?> mục</span>
                        </div>
                        <?php foreach (array_slice($items, 0, 2) as $item): ?>
                            <div class="d-flex align-items-center justify-content-between py-1">
                                <span class="text-truncate text-white small pe-2" style="max-width: 200px;">
                                    <?= e($item['product_name'] ?? ($item['name'] ?? 'Sản phẩm')) ?>
                                </span>
                                <span class="text-white-50 small fw-semibold">
                                    <?= formatMoney($item['price'] ?? 0) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                        <?php if (count($items) > 2): ?>
                            <div class="small text-secondary mt-1">+ <?= count($items) - 2 ?> sản phẩm khác...</div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- OTP Form -->
                    <form method="post" action="/payment/vnpay/process" id="vnpay-form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="bank" value="<?= e($card['bank'] ?? $bankCode) ?>">
                        <input type="hidden" name="card_number" value="<?= e($cardNumber) ?>">
                        <input type="hidden" name="card_holder" value="<?= e($cardHolder) ?>">
                        <input type="hidden" name="issue_date" value="<?= e($issueDate) ?>">

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-semibold text-white mb-0">
                                    <i class="bi bi-key me-1 text-warning"></i> Mã xác thực OTP
                                </label>
                                <span class="small text-secondary">Hết hạn sau: <span class="text-info" id="timer">02:59</span></span>
                            </div>

                            <input type="text" name="otp" class="form-control otp-input-field"
                                   placeholder="••••••" maxlength="6" inputmode="numeric" required autofocus autocomplete="one-time-code">

                            <div class="d-flex align-items-center justify-content-between mt-2 px-1">
                                <span class="small text-secondary">
                                    OTP Test: <strong class="text-warning font-monospace fs-6"><?= e($otpTest) ?></strong>
                                </span>
                                <button type="button" class="btn btn-sm btn-link text-info text-decoration-none p-0" onclick="document.querySelector('[name=otp]').value='<?= e($otpTest) ?>'">
                                    <i class="bi bi-magic me-1"></i> Tự điền OTP
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-vnpay-submit w-100 py-3 mt-3">
                            <i class="bi bi-lock-fill me-2"></i> Xác nhận thanh toán ngay
                        </button>
                    </form>
                </div>

                <div class="d-flex justify-content-center align-items-center gap-3 mt-4 pt-3 border-top border-secondary border-opacity-25 text-center">
                    <a href="/payment/vnpay/gateway" class="small text-secondary text-decoration-none hover-link">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Cổng Sandbox chính thức
                    </a>
                    <span class="text-secondary opacity-50">•</span>
                    <a href="/checkout" class="small text-secondary text-decoration-none hover-link">
                        <i class="bi bi-arrow-left me-1"></i> Hủy & Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Simple countdown timer for UX realism
let timeLeft = 179;
const timerEl = document.getElementById('timer');
if (timerEl) {
    const countdown = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(countdown);
            timerEl.innerText = "00:00";
        } else {
            const m = String(Math.floor(timeLeft / 60)).padStart(2, '0');
            const s = String(timeLeft % 60).padStart(2, '0');
            timerEl.innerText = `${m}:${s}`;
            timeLeft--;
        }
    }, 1000);
}
</script>
