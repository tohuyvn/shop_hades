<style>
/* Choice Card Styles */
.choice-list-option {
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px 20px;
    margin-bottom: 12px;
    cursor: pointer;
    transition: all 0.25s ease;
    background: #ffffff;
    position: relative;
}
.choice-list-option:hover {
    border-color: #cbd5e1;
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
}
.choice-list-option.selected {
    border-color: #d70018 !important;
    background: #fff5f5 !important;
    box-shadow: 0 4px 20px rgba(215, 0, 24, 0.08) !important;
}
.choice-list-option .form-check-input {
    width: 1.25rem;
    height: 1.25rem;
    margin-top: 2px;
    cursor: pointer;
}
.choice-list-option .form-check-input:checked {
    background-color: #d70018;
    border-color: #d70018;
}

/* Expandable Box */
.expandable-form-box {
    border-radius: 16px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 20px 24px;
    margin-top: -4px;
    margin-bottom: 16px;
    animation: fadeInSlideDown 0.25s ease-out;
}
@keyframes fadeInSlideDown {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

.checkout-summary-card {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
    padding: 24px;
    position: sticky;
    top: 90px;
}
</style>

<div class="container py-4" style="max-width: 1040px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/courses" class="text-decoration-none text-muted">Khóa học</a></li>
            <li class="breadcrumb-item"><a href="/courses/<?= $course['id'] ?>" class="text-decoration-none text-muted"><?= e($course['title']) ?></a></li>
            <li class="breadcrumb-item active fw-semibold text-danger">Thanh toán</li>
        </ol>
    </nav>

    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between p-4 mb-4 rounded-4 text-white shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <div>
            <h1 class="h3 fw-bold mb-1"><i class="bi bi-shield-check text-success me-2"></i>Thanh toán Khóa học</h1>
            <p class="mb-0 text-white-50 small">Hoàn tất thanh toán để truy cập ngay vào hệ sinh thái bài giảng, bài tập và cấp chứng chỉ.</p>
        </div>
        <div class="d-none d-md-block text-end">
            <span class="badge bg-danger bg-opacity-75 px-3 py-2 fs-6 rounded-pill">Mã đơn: <?= e($orderCode) ?></span>
        </div>
    </div>

    <form method="post" action="/courses/<?= $course['id'] ?>/payment" id="courseCheckoutForm">
        <?= csrf_field() ?>
        <input type="hidden" name="order_code" value="<?= e($orderCode) ?>">

        <div class="row g-4">
            <!-- Left Column: Learner Info & Payment Methods -->
            <div class="col-lg-7">
                <!-- 1. Learner Contact Information -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-person-badge text-danger me-2"></i>Thông tin học viên</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark" for="customer_name">Họ và tên học viên *</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control rounded-3" 
                                   value="<?= e($user['name'] ?? 'Học viên') ?>" required placeholder="VD: Nguyễn Văn A">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark" for="phone">Số điện thoại *</label>
                            <input type="tel" name="phone" id="phone" class="form-control rounded-3" 
                                   value="<?= e(!empty($user['phone']) ? $user['phone'] : '0912345678') ?>" required placeholder="VD: 0912345678">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark" for="email">Địa chỉ Email *</label>
                            <input type="email" name="email" id="email" class="form-control rounded-3" 
                                   value="<?= e($user['email'] ?? 'hocvien@shop.test') ?>" required placeholder="email@example.com">
                        </div>
                    </div>
                    <div class="form-text text-muted small mt-2">
                        <i class="bi bi-info-circle me-1"></i> Chứng chỉ hoàn thành và thông tin tài khoản học tập sẽ được gửi đến email này.
                    </div>
                </div>

                <!-- 2. Payment Method Selection -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-wallet2 text-danger me-2"></i>Chọn phương thức thanh toán</h5>

                    <!-- Option 1: VietQR MB Bank -->
                    <div class="choice-list-option selected" id="opt-vietqr" onclick="selectPaymentMethod('vietqr')">
                        <div class="d-flex align-items-start gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="pay_vietqr" value="vietqr" checked>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="fw-bold text-dark mb-0 cursor-pointer" for="pay_vietqr">
                                        🏦 Chuyển khoản VietQR (MB Bank)
                                    </label>
                                    <span class="badge bg-success px-2 py-1" style="font-size: 11px;">Khuyên dùng · Tự động</span>
                                </div>
                                <div class="text-muted small mt-1">Quét mã VietQR trên app ngân hàng bất kỳ. Đối soát & kích hoạt tự động trong 30 giây.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Expandable VietQR Details Box -->
                    <div id="box-vietqr" class="expandable-form-box">
                        <div class="row align-items-center g-3">
                            <div class="col-auto text-center">
                                <img src="<?= e($vietQrUrl) ?>" alt="VietQR MB" style="width:120px;height:120px;border-radius:12px;border:1px solid #cbd5e1; background:#fff; padding: 4px;" class="shadow-sm">
                            </div>
                            <div class="col small">
                                <div class="fw-bold text-dark mb-1">Tài khoản thụ hưởng LMS:</div>
                                <div>Ngân hàng: <strong class="text-dark"><?= e($vietQrConfig['bank_name'] ?? 'MB Bank (Quân Đội)') ?></strong></div>
                                <div>Số TK: <code class="fs-6 text-danger fw-bold"><?= e($vietQrConfig['account_no'] ?? '0788415516') ?></code></div>
                                <div>Chủ TK: <strong class="text-dark"><?= e($vietQrConfig['account_name_display'] ?? 'TÔ QUANG HUY') ?></strong></div>
                                <div>Nội dung CK: <strong class="text-danger font-monospace">THANH TOAN <?= e($orderCode) ?></strong></div>
                                <div class="text-muted mt-1" style="font-size: 11px;">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>Hệ thống tự động kích hoạt ngay sau khi bấm nút bên dưới.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Option 2: VNPay Sandbox -->
                    <div class="choice-list-option" id="opt-vnpay" onclick="selectPaymentMethod('vnpay')">
                        <div class="d-flex align-items-start gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="pay_vnpay" value="vnpay">
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="fw-bold text-dark mb-0 cursor-pointer" for="pay_vnpay">
                                        💳 Thẻ ATM / QR Ngân hàng (VNPay Gateway)
                                    </label>
                                    <span class="badge bg-primary px-2 py-1" style="font-size: 11px;">VNPay Sandbox</span>
                                </div>
                                <div class="text-muted small mt-1">Cổng thanh toán quốc dân hỗ trợ hơn 40 ngân hàng, Visa/Mastercard và VNPAY-QR.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Expandable VNPay Form -->
                    <div id="box-vnpay" class="expandable-form-box" style="display:none">
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-credit-card me-1"></i> Thông Tin Thẻ Test VNPay Sandbox</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark" for="bank">Ngân hàng phát hành *</label>
                                <select name="bank" id="bank" class="form-select form-select-sm">
                                    <option value="NCB" selected>NCB - Ngân hàng Quốc Dân (Thử nghiệm chuẩn)</option>
                                    <?php foreach ($banks as $b): ?>
                                        <?php if (($b['code'] ?? '') !== 'NCB'): ?>
                                            <option value="<?= e($b['code']) ?>"><?= e($b['name']) ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark" for="card_number">Số thẻ ATM *</label>
                                <input type="text" name="card_number" id="card_number" class="form-control form-control-sm font-monospace"
                                       placeholder="<?= e(config('vnpay.test_card.card_number')) ?>" value="<?= e(config('vnpay.test_card.card_number')) ?>" autocomplete="off">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark" for="card_holder">Tên chủ thẻ *</label>
                                <input type="text" name="card_holder" id="card_holder" class="form-control form-control-sm"
                                       placeholder="NGUYEN VAN A" value="NGUYEN VAN A" style="text-transform:uppercase">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark" for="issue_date">Ngày phát hành (MM/YY) *</label>
                                <input type="text" name="issue_date" id="issue_date" class="form-control form-control-sm font-monospace"
                                       placeholder="07/15" value="07/15">
                            </div>
                        </div>
                        <div class="alert alert-light border rounded-3 p-2 text-muted small mt-3 mb-0">
                            Thẻ test NCB: <code>9704198526191432</code> · Chủ thẻ: <code>NGUYEN VAN A</code> · Ngày: <code>07/15</code> · OTP: <code>123456</code>
                        </div>
                    </div>

                    <!-- Option 3: MoMo Wallet -->
                    <div class="choice-list-option" id="opt-momo" onclick="selectPaymentMethod('momo')">
                        <div class="d-flex align-items-start gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="pay_momo" value="momo">
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="fw-bold text-dark mb-0 cursor-pointer" for="pay_momo">
                                        🟣 Ví Điện Tử MoMo
                                    </label>
                                    <span class="badge text-white px-2 py-1" style="font-size: 11px; background:#a50064 !important;">MoMo QR</span>
                                </div>
                                <div class="text-muted small mt-1">Quét mã QR bằng ứng dụng Ví MoMo trên điện thoại để thanh toán siêu tốc.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Option 4: Thanh toán Tiền mặt Trực tiếp -->
                    <div class="choice-list-option" id="opt-cash" onclick="selectPaymentMethod('cash')">
                        <div class="d-flex align-items-start gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="pay_cash" value="cash">
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="fw-bold text-dark mb-0 cursor-pointer" for="pay_cash">
                                        💵 Thanh toán Tiền mặt Trực tiếp
                                    </label>
                                    <span class="badge bg-secondary px-2 py-1" style="font-size: 11px;">Tại quầy / Văn phòng</span>
                                </div>
                                <div class="text-muted small mt-1">Hoàn tất học phí bằng tiền mặt trực tiếp tại văn phòng tuyển sinh hoặc cơ sở Hades Academy.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Expandable Cash Box -->
                    <div id="box-cash" class="expandable-form-box" style="display:none">
                        <h6 class="fw-bold text-success mb-2"><i class="bi bi-geo-alt-fill me-1"></i> Địa điểm nộp học phí tiền mặt trực tiếp:</h6>
                        <div class="small text-dark mb-2">
                            <div>• <strong>Trụ sở chính:</strong> Tầng 5, Tòa nhà Hades, Số 3 Duy Tân, Cầu Giấy, Hà Nội</div>
                            <div>• <strong>Chi nhánh TP. HCM:</strong> Số 99 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh</div>
                            <div>• <strong>Hotline hỗ trợ & thu ngân:</strong> <code class="fw-bold text-danger">0901.234.567</code> (8:00 - 21:00)</div>
                        </div>
                        <div class="alert alert-light border rounded-3 p-2 text-muted small mb-0">
                            <i class="bi bi-info-circle-fill text-primary me-1"></i> Sau khi thu ngân nhận tiền và xuất biên lai, hệ thống sẽ xác nhận và phòng học sẽ được tự động mở khóa.
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="col-lg-5">
                <div class="checkout-summary-card">
                    <h5 class="fw-bold mb-3 text-dark">Thông tin đơn hàng</h5>

                    <div class="d-flex gap-3 align-items-center pb-3 border-bottom mb-3">
                        <img src="<?= e($course['thumbnail'] ?: '/image/courses/devops.jpg') ?>" class="rounded-3 object-fit-cover shadow-sm" style="width: 84px; height: 64px;" alt="">
                        <div>
                            <h6 class="fw-bold text-dark mb-1 line-clamp-2" style="font-size: 15px;"><?= e($course['title']) ?></h6>
                            <div class="small text-muted"><i class="bi bi-person me-1"></i> <?= e($course['instructor_name'] ?? 'Giảng viên Hades Academy') ?></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between text-secondary mb-2 small">
                        <span>Giá niêm yết:</span>
                        <span><?= formatMoney($course['price']) ?></span>
                    </div>

                    <?php if ($course['sale_price'] !== null && $course['sale_price'] < $course['price']): ?>
                        <div class="d-flex justify-content-between text-success mb-2 small">
                            <span>Ưu đãi học tập:</span>
                            <span>- <?= formatMoney($course['price'] - $course['sale_price']) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top mb-4">
                        <span class="fw-bold text-dark fs-5">Tổng thanh toán:</span>
                        <span class="fw-bold text-danger display-6 fs-3"><?= formatMoney($price) ?></span>
                    </div>

                    <button type="submit" id="btnSubmitPayment" class="btn btn-danger btn-lg rounded-pill fw-bold w-100 py-3 shadow">
                        <i class="bi bi-lock-fill me-2"></i> Tiến hành thanh toán
                    </button>

                    <div class="mt-4 pt-3 border-top text-center text-muted small">
                        <div class="d-flex justify-content-center gap-3 mb-2">
                            <span><i class="bi bi-shield-check text-success me-1"></i> Bảo mật 256-bit</span>
                            <span><i class="bi bi-patch-check-fill text-primary me-1"></i> Chứng chỉ uy tín</span>
                        </div>
                        <p class="mb-0 text-muted" style="font-size: 12px;">Truy cập không giới hạn trọn đời vào toàn bộ bài giảng và tài liệu học tập sau khi hoàn tất.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function selectPaymentMethod(method) {
    const methods = ['vietqr', 'vnpay', 'momo', 'cash'];
    methods.forEach(m => {
        const opt = document.getElementById('opt-' + m);
        const radio = document.getElementById('pay_' + m);
        const box = document.getElementById('box-' + m);

        if (m === method) {
            if (opt) opt.classList.add('selected');
            if (radio) radio.checked = true;
            if (box) box.style.display = 'block';
        } else {
            if (opt) opt.classList.remove('selected');
            if (box) box.style.display = 'none';
        }
    });

    const submitBtn = document.getElementById('btnSubmitPayment');
    if (submitBtn) {
        if (method === 'vietqr') {
            submitBtn.innerHTML = '<i class="bi bi-qr-code-scan me-2"></i> Mở cổng thanh toán VietQR';
        } else if (method === 'vnpay') {
            submitBtn.innerHTML = '<i class="bi bi-credit-card me-2"></i> Chuyển tới cổng thanh toán VNPay';
        } else if (method === 'momo') {
            submitBtn.innerHTML = '<i class="bi bi-wallet2 me-2"></i> Mở cổng thanh toán MoMo';
        } else if (method === 'cash') {
            submitBtn.innerHTML = '<i class="bi bi-cash-coin me-2"></i> Đăng ký & Thanh toán tiền mặt tại quầy';
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    selectPaymentMethod('vietqr');
});
</script>