<?php $title = 'Thu Cũ Đổi Mới – Lên Đời Điện Thoại'; ?>

<style>
.tradein-banner-header {
    background: var(--refract-card-bg, rgba(20, 24, 36, 0.65)) !important;
    backdrop-filter: var(--refract-blur, blur(28px)) saturate(190%) !important;
    -webkit-backdrop-filter: var(--refract-blur, blur(28px)) saturate(190%) !important;
    border-radius: 22px !important;
    padding: 32px 36px;
    color: #fff;
    margin-bottom: 28px;
    border: 1px solid var(--refract-card-border, rgba(255, 255, 255, 0.18)) !important;
    box-shadow: var(--refract-chromatic) !important;
    position: relative;
    overflow: hidden;
}
.tradein-banner-header::after {
    content: '';
    position: absolute;
    right: -30px;
    top: -30px;
    width: 200px;
    height: 200px;
    background: rgba(34, 197, 94, 0.2);
    border-radius: 50%;
    filter: blur(45px);
}
.tradein-main-card {
    background: var(--refract-card-bg, rgba(20, 24, 36, 0.65)) !important;
    backdrop-filter: var(--refract-blur, blur(28px)) saturate(190%) !important;
    -webkit-backdrop-filter: var(--refract-blur, blur(28px)) saturate(190%) !important;
    border-radius: 22px !important;
    border: 1px solid var(--refract-card-border, rgba(255, 255, 255, 0.18)) !important;
    box-shadow: var(--refract-chromatic) !important;
    padding: 32px;
    color: var(--refract-text-main, #f8fafc) !important;
}
.device-card-item {
    border: 1px solid rgba(255, 255, 255, 0.16) !important;
    border-radius: 16px !important;
    padding: 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.22s ease !important;
    background: rgba(255, 255, 255, 0.06) !important;
    backdrop-filter: blur(12px) !important;
    color: #f8fafc !important;
}
.device-card-item:hover {
    border-color: rgba(255, 106, 0, 0.65) !important;
    background: rgba(255, 255, 255, 0.14) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(255, 106, 0, 0.25) !important;
}
.device-card-item.active {
    border-color: #ff6a00 !important;
    background: rgba(255, 106, 0, 0.2) !important;
    box-shadow: 0 0 0 2px rgba(255, 106, 0, 0.45), 0 8px 24px rgba(255, 106, 0, 0.35) !important;
}
.device-card-item .text-dark {
    color: #ffffff !important;
}
#valuationPreview {
    background: linear-gradient(135deg, rgba(255, 106, 0, 0.2) 0%, rgba(0, 242, 254, 0.15) 100%) !important;
    border: 1px solid rgba(255, 106, 0, 0.45) !important;
    backdrop-filter: blur(18px) !important;
    color: #ffffff !important;
}
</style>

<div class="container py-4">
    <!-- Header Banner -->
    <div class="tradein-banner-header text-center">
        <span class="badge bg-success rounded-pill px-3 py-1.5 fw-bold text-uppercase mb-2" style="font-size: 11px; letter-spacing: 1px;">Chương Trình Trợ Giá Lên Đời</span>
        <h1 class="fw-bold mb-2 text-white" style="font-size: 2.2rem;">Thu Cũ Đổi Mới – Lên Đời Siêu Phẩm</h1>
        <p class="text-slate-300 mx-auto mb-0 small" style="max-width: 600px; color: #cbd5e1;">
            Định giá máy cũ tự động trong 3 giây. Nhận ngay Voucher giảm giá trực tiếp mua điện thoại mới tại Hades Shop!
        </p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="tradein-main-card">

                <?php if (isset($_SESSION['trade_in_success'])): 
                    $success = $_SESSION['trade_in_success'];
                    unset($_SESSION['trade_in_success']);
                ?>
                    <div class="card border-0 rounded-4 p-4 text-center mb-4" style="background: #f0fdf4; border: 1px solid #bbf7d0 !important;">
                        <div class="text-success mb-2" style="font-size: 56px;">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-1">Định Giá Thành Công!</h3>
                        <p class="text-muted small mb-4">Mã Voucher trợ giá đã được khởi tạo thành công dành riêng cho bạn.</p>

                        <div class="bg-white p-4 rounded-4 shadow-sm mx-auto mb-4 border" style="max-width: 440px;">
                            <div class="text-muted small uppercase fw-bold mb-1" style="font-size: 11px;">MÃ VOUCHER THU CŨ CỦA BẠN</div>
                            <div class="fs-2 fw-extrabold text-danger mb-3 font-monospace tracking-wide" id="voucherCodeText"><?= e($success['voucher_code']) ?></div>
                            <button class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1.5 fw-bold" onclick="copyVoucherCode()">
                                <i class="bi bi-clipboard me-1"></i> Sao chép mã Voucher
                            </button>
                        </div>

                        <div class="row g-2 text-start mx-auto mb-4 p-3 bg-white rounded-3 border" style="max-width: 440px; font-size: 14px;">
                            <div class="col-6 text-muted">Thiết bị thu cũ:</div>
                            <div class="col-6 fw-bold text-end text-dark"><?= e($success['device_name']) ?></div>

                            <div class="col-6 text-muted">Tình trạng máy:</div>
                            <div class="col-6 fw-bold text-end text-dark"><?= e($success['condition_name']) ?></div>

                            <div class="col-6 text-muted">Giá trị thu hồi:</div>
                            <div class="col-6 fw-bold text-end text-success fs-5"><?= formatMoney($success['estimated_value']) ?></div>
                        </div>

                        <div class="d-flex justify-content-center gap-3">
                            <a href="/" class="btn btn-dark px-4 py-2.5 rounded-3 fw-bold">Mua Sắm Ngay</a>
                            <a href="/trade-in" class="btn btn-outline-secondary px-4 py-2.5 rounded-3">Định giá máy khác</a>
                        </div>
                    </div>

                    <script>
                        function copyVoucherCode() {
                            const codeText = document.getElementById('voucherCodeText').innerText;
                            navigator.clipboard.writeText(codeText).then(() => {
                                alert('Đã sao chép mã voucher: ' + codeText);
                            });
                        }
                    </script>
                <?php else: ?>
                    <form action="/trade-in/submit" method="POST" id="tradeInForm">
                        <?= csrf_field() ?>
                        
                        <!-- Step 1: Chọn máy -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-3"><i class="bi bi-phone me-2 text-danger"></i>1. Chọn dòng máy cũ của bạn</label>
                            <div class="row g-3">
                                <?php foreach ($devices as $key => $device): ?>
                                    <div class="col-6 col-md-4">
                                        <div class="device-card-item h-100 device-card" 
                                             data-key="<?= e($key) ?>" 
                                             data-val="<?= $device['base_value'] ?>"
                                             onclick="selectDevice(this)">
                                            <div class="fw-bold text-dark small mb-1"><?= e($device['name']) ?></div>
                                            <div class="text-muted small" style="font-size: 11px;">Thu hồi tối đa:</div>
                                            <div class="text-danger fw-bold small"><?= formatMoney($device['base_value']) ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <input type="hidden" name="device_key" id="selectedDeviceKey" required>
                        </div>

                        <!-- Step 2: Chọn tình trạng -->
                        <div class="mb-4 d-none" id="conditionSection">
                            <label class="form-label fw-bold text-dark mb-3"><i class="bi bi-activity me-2 text-danger"></i>2. Chọn tình trạng thực tế của máy</label>
                            <div class="row g-3">
                                <?php foreach ($conditions as $key => $cond): ?>
                                    <div class="col-md-6">
                                        <div class="device-card-item text-start h-100 condition-card" 
                                             data-key="<?= e($key) ?>" 
                                             data-ratio="<?= $cond['ratio'] ?>"
                                             onclick="selectCondition(this)">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold text-dark small"><?= e($cond['name']) ?></span>
                                                <span class="badge bg-secondary-subtle text-secondary small"><?= (int)($cond['ratio'] * 100) ?>% giá trị</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <input type="hidden" name="condition_key" id="selectedConditionKey" required>
                        </div>

                        <!-- Live Valuation Preview -->
                        <div class="card border-0 rounded-4 p-4 mb-4 text-center d-none shadow-sm" id="valuationPreview" style="background: #fffcf5; border: 1px solid #fde68a !important;">
                            <div class="text-warning-emphasis small uppercase fw-bold mb-1" style="font-size: 11px;">GIÁ TRỊ THU HỒI DỰ KIẾN</div>
                            <div class="display-6 fw-extrabold text-success mb-1" id="liveEstimatedValue">0đ</div>
                            <p class="text-muted small mb-0">Voucher giảm giá tương ứng sẽ được áp dụng trực tiếp khi bạn đặt đơn máy mới.</p>
                        </div>

                        <!-- Step 3: Thông tin liên hệ -->
                        <div class="mb-4 d-none" id="contactSection">
                            <label class="form-label fw-bold text-dark mb-3"><i class="bi bi-person me-2 text-danger"></i>3. Thông tin nhận Voucher trợ giá</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="customer_name" class="form-label small text-muted">Họ và tên *</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" required 
                                           value="<?= authUser() ? e(authUser()['name']) : '' ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_phone" class="form-label small text-muted">Số điện thoại *</label>
                                    <input type="text" class="form-control" id="customer_phone" name="customer_phone" required 
                                           value="<?= authUser() ? e(authUser()['phone']) : '' ?>">
                                </div>
                            </div>
                        </div>

                        <div class="text-center d-none" id="submitSection">
                            <button type="submit" class="btn btn-danger px-5 py-3 rounded-3 fw-bold shadow-sm" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none;">
                                Nhận Voucher & Lên Đời Ngay <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
let selectedBaseValue = 0;
let selectedRatio = 0;

function selectDevice(el) {
    document.querySelectorAll('.device-card').forEach(card => card.classList.remove('active'));
    el.classList.add('active');
    
    document.getElementById('selectedDeviceKey').value = el.dataset.key;
    selectedBaseValue = parseInt(el.dataset.val);
    
    // Show step 2
    document.getElementById('conditionSection').classList.remove('d-none');
    
    // Reset step 2 & live calculation if active
    document.querySelectorAll('.condition-card').forEach(card => card.classList.remove('active'));
    document.getElementById('selectedConditionKey').value = '';
    selectedRatio = 0;
    
    updateValuation();
}

function selectCondition(el) {
    document.querySelectorAll('.condition-card').forEach(card => card.classList.remove('active'));
    el.classList.add('active');
    
    document.getElementById('selectedConditionKey').value = el.dataset.key;
    selectedRatio = parseFloat(el.dataset.ratio);
    
    // Show live evaluation & contact section
    document.getElementById('valuationPreview').classList.remove('d-none');
    document.getElementById('contactSection').classList.remove('d-none');
    document.getElementById('submitSection').classList.remove('d-none');
    
    updateValuation();
}

function updateValuation() {
    if (selectedBaseValue > 0 && selectedRatio > 0) {
        const est = Math.round(selectedBaseValue * selectedRatio);
        document.getElementById('liveEstimatedValue').innerText = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(est);
    } else {
        document.getElementById('valuationPreview').classList.add('d-none');
    }
}
</script>
