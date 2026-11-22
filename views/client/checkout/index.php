<?php
$title = 'Thanh toán & Đặt hàng';
$isMember = $isMember ?? (authUser() !== null);
$oldPayment = old('payment_method', $_GET['payment'] ?? 'cash');
$oldDelivery = old('delivery_method', 'shipping');
?>

<!-- Leaflet Map CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
.checkout-header-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: 20px;
    padding: 24px 32px;
    color: #fff;
    margin-bottom: 24px;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
}
.checkout-steps-bar {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-bottom: 28px;
}
.checkout-step-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: 50px;
    background: #f1f5f9;
    color: #64748b;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s ease;
}
.checkout-step-pill.active {
    background: #ef4444;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(239, 68, 68, 0.25);
}
.checkout-step-pill.done {
    background: #e2e8f0;
    color: #0f172a;
}
.checkout-step-num {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: grid;
    place-items: center;
    font-size: 11px;
    font-weight: 800;
}

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
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
}
.choice-list-option.selected {
    border-color: #ef4444 !important;
    background: #fff5f5 !important;
    box-shadow: 0 4px 20px rgba(239, 68, 68, 0.08) !important;
}
.choice-list-option .form-check-input {
    width: 1.25rem;
    height: 1.25rem;
    margin-top: 2px;
    cursor: pointer;
}
.choice-list-option .form-check-input:checked {
    background-color: #ef4444;
    border-color: #ef4444;
}

/* Expandable Form Container */
.expandable-form-box {
    border-radius: 16px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 24px;
    margin-top: 16px;
    margin-bottom: 24px;
    animation: fadeInSlideDown 0.3s ease-out;
}
@keyframes fadeInSlideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* VAT Invoice Card */
.vat-auto-card {
    position: relative;
    overflow: hidden;
    margin-top: 1.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
    transition: all 0.2s ease;
}
.vat-auto-card.is-selected {
    border-color: #3b82f6;
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.12);
}
.vat-auto-head { display: flex; align-items: center; gap: 14px; padding: 18px 20px; }
.vat-auto-icon {
    width: 44px;
    height: 44px;
    flex: 0 0 44px;
    display: grid;
    place-items: center;
    border-radius: 12px;
    color: #3b82f6;
    background: #eff6ff;
    font-size: 20px;
}
.vat-auto-copy { min-width: 0; flex: 1; }
.vat-auto-title { display: block; margin: 0 0 2px; color: #0f172a; font-weight: 700; cursor: pointer; }
.vat-auto-copy p { margin: 0; color: #64748b; font-size: 12px; }
.vat-auto-details {
    display: none;
    margin: 0 20px 18px;
    padding: 15px 16px;
    border: 1px solid #dbeafe;
    border-radius: 12px;
    background: #eff6ff;
}
.vat-preview-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px 18px; }
.vat-preview-item small { display: block; margin-bottom: 2px; color: #64748b; font-size: 10px; font-weight: 700; text-transform: uppercase; }
.vat-preview-item strong { display: block; overflow: hidden; color: #0f172a; font-size: 12px; text-overflow: ellipsis; white-space: nowrap; }

/* Sticky Order Summary */
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

<div class="container py-4">
    <!-- Header Banner -->
    <div class="checkout-header-banner d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-extrabold mb-1 text-white"><i class="bi bi-credit-card-2-back text-danger me-2"></i>Thanh Toán & Xác Nhận Đơn Hàng</h1>
            <p class="text-slate-300 small mb-0" style="color: #cbd5e1;">Hoàn tất thông tin giao hàng và chọn phương thức thanh toán phù hợp.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/cart" class="btn btn-outline-light btn-sm rounded-pill px-3 py-1.5 fw-bold" style="font-size: 12px;">← Quay lại Giỏ hàng</a>
        </div>
    </div>

    <!-- Steps Indicator Bar -->
    <div class="checkout-steps-bar">
        <div class="checkout-step-pill done">
            <span class="checkout-step-num"><i class="bi bi-check-lg"></i></span>
            <span>1. Giỏ hàng</span>
        </div>
        <div class="checkout-step-pill active">
            <span class="checkout-step-num">2</span>
            <span>2. Thông tin & Thanh toán</span>
        </div>
        <div class="checkout-step-pill">
            <span class="checkout-step-num">3</span>
            <span>3. Hoàn tất đơn</span>
        </div>
    </div>

    <!-- Pre-order Alert -->
    <?php if (!empty($isPreorderOrder)): ?>
        <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4 p-3 d-flex align-items-center gap-3">
            <i class="bi bi-info-circle-fill fs-3 text-warning"></i>
            <div>
                <strong class="d-block text-dark">ĐƠN HÀNG ĐẶT TRƯỚC (PRE-ORDER)</strong>
                <span class="small text-muted">
                    <?php if ($totalRequiredDeposit > 0): ?>
                        Cần đặt cọc tối thiểu: <strong class="text-danger fs-6"><?= formatMoney($totalRequiredDeposit) ?></strong>. Vui lòng thanh toán trực tuyến hoặc liên hệ cửa hàng.
                    <?php else: ?>
                        Sản phẩm này hỗ trợ <strong>Đặt trước không cần cọc</strong>!
                    <?php endif; ?>
                </span>
            </div>
        </div>
    <?php endif; ?>

    <form method="post" action="/checkout" id="checkout-form">
        <?= csrf_field() ?>
        <input type="hidden" name="coupon_code" id="hidden-coupon-code" value="">
        <input type="hidden" name="shipping_lat" id="shipping_lat" value="<?= old('shipping_lat') ?>">
        <input type="hidden" name="shipping_lng" id="shipping_lng" value="<?= old('shipping_lng') ?>">
        <input type="hidden" name="address_province" id="address_province" value="<?= old('address_province') ?>">
        <input type="hidden" name="address_district" id="address_district" value="<?= old('address_district') ?>">
        <input type="hidden" name="address_ward" id="address_ward" value="<?= old('address_ward') ?>">
        <input type="hidden" name="address_street" id="address_street" value="<?= old('address_street') ?>">

        <div class="row g-4">
            <!-- Left Column: Receive Method & Payment Choices Form -->
            <div class="col-lg-7">
                
                <!-- SECTION 1: HÌNH THỨC NHẬN HÀNG -->
                <div class="mb-4">
                    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2" style="font-size: 17px;">
                        <span class="badge bg-danger rounded-circle p-1 d-inline-grid place-items-center" style="width: 26px; height: 26px; font-size: 13px;">1</span>
                        Chọn Hình Thức Nhận Hàng
                    </h5>

                    <!-- Option A: Giao tận nơi -->
                    <div class="choice-list-option <?= $oldDelivery === 'shipping' ? 'selected' : '' ?>" id="delivery-option-shipping" onclick="selectDeliveryMethod('shipping')">
                        <div class="d-flex align-items-start gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery_method" id="delivery_shipping" value="shipping" <?= $oldDelivery === 'shipping' ? 'checked' : '' ?>>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="fw-bold text-dark mb-0 cursor-pointer" for="delivery_shipping">
                                        🚚 Giao Hàng Tận Nơi (Home Delivery)
                                    </label>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5" style="font-size: 11px;">Giao Nhanh 2H</span>
                                </div>
                                <div class="text-muted small mt-1">Giao đến địa chỉ cá nhân của bạn. Áp dụng tính phí vận chuyển theo khoảng cách.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Option B: Nhận tại cửa hàng -->
                    <div class="choice-list-option <?= $oldDelivery === 'pickup' ? 'selected' : '' ?>" id="delivery-option-pickup" onclick="selectDeliveryMethod('pickup')">
                        <div class="d-flex align-items-start gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery_method" id="delivery_pickup" value="pickup" <?= $oldDelivery === 'pickup' ? 'checked' : '' ?>>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="fw-bold text-dark mb-0 cursor-pointer" for="delivery_pickup">
                                        🏪 Nhận Tại Showroom / Cửa Hàng (Store Pickup)
                                    </label>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 11px;">Miễn Phí Giao Hàng</span>
                                </div>
                                <div class="text-muted small mt-1">Đến trải nghiệm sản phẩm trực tiếp và nhận máy tại chi nhánh Hades gần nhất.</div>
                            </div>
                        </div>
                    </div>

                    <!-- EXPANDABLE FORM: Thông tin giao hàng tận nơi -->
                    <div class="expandable-form-box" id="shipping-form-box">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Địa Chỉ Giao Hàng Tận Nơi</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark" for="customer_name">Họ và tên người nhận *</label>
                                <input type="text" name="customer_name" id="customer_name" class="form-control shipping-required"
                                       value="<?= old('customer_name', $user['name'] ?? '') ?>" placeholder="Nguyễn Văn A">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark" for="phone">Số điện thoại *</label>
                                <input type="text" name="phone" id="phone" class="form-control shipping-required"
                                       value="<?= old('phone', $user['phone'] ?? '') ?>" placeholder="09xxxxxxxx">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark" for="email">Email nhận thông báo *</label>
                                <input type="email" name="email" id="email" class="form-control shipping-required"
                                       value="<?= old('email', $user['email'] ?? '') ?>" placeholder="name@gmail.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark" for="preferred_time_slot">Khung giờ nhận hàng mong muốn</label>
                                <select name="preferred_time_slot" id="preferred_time_slot" class="form-select">
                                    <option value="any" <?= old('preferred_time_slot', 'any') === 'any' ? 'selected' : '' ?>>Linh hoạt trong ngày</option>
                                    <option value="morning" <?= old('preferred_time_slot') === 'morning' ? 'selected' : '' ?>>Sáng (08:00 - 11:30)</option>
                                    <option value="afternoon" <?= old('preferred_time_slot') === 'afternoon' ? 'selected' : '' ?>>Chiều (13:30 - 17:30)</option>
                                    <option value="evening" <?= old('preferred_time_slot') === 'evening' ? 'selected' : '' ?>>Tối (18:00 - 21:00)</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label small fw-bold text-dark mb-0" for="shipping_address">Địa chỉ chi tiết *</label>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="use-current-location-btn" style="font-size: 11px; padding: 2px 8px;">
                                            🎯 Vị trí hiện tại
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="open-map-btn" style="font-size: 11px; padding: 2px 8px;">
                                            📍 Chọn bản đồ
                                        </button>
                                    </div>
                                </div>
                                <div class="position-relative">
                                    <input type="text" name="shipping_address" id="shipping_address" class="form-control shipping-required"
                                           value="<?= old('shipping_address') ?>" placeholder="Nhập địa chỉ giao hàng (Số nhà, đường, phường/xã)..." autocomplete="off">
                                    <div id="address-suggestions" class="list-group position-absolute w-100 mt-1 shadow-sm" style="display: none; z-index: 1050; max-height: 250px; overflow-y: auto;"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div id="shipping-estimate-box" class="alert alert-info border-0 rounded-3 small mb-0" style="display:none"></div>
                            </div>
                        </div>
                    </div>

                    <!-- EXPANDABLE FORM: Chọn cửa hàng nhận máy -->
                    <div class="expandable-form-box" id="pickup-form-box" style="display: none;">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shop text-success me-1"></i>Chọn Showroom Nhận Hàng</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark" for="store_branch">Chi nhánh Showroom Hades *</label>
                                <select name="store_branch" id="store_branch" class="form-select" onchange="updatePickupStoreAddress(this)">
                                    <option value="Trụ sở chính: Võng Thị, Tây Hồ, Hà Nội">HÀ NỘI - Trụ sở chính: Võng Thị, Tây Hồ</option>
                                    <option value="Cơ sở 2: Số 3 Duy Tân, Cầu Giấy, Hà Nội">HÀ NỘI - Cơ sở 2: Số 3 Duy Tân, Cầu Giấy</option>
                                    <option value="Cơ sở 3: Lê Duẩn, Hải Châu, Đà Nẵng">ĐÀ NẴNG - Cơ sở 3: Lê Duẩn, Hải Châu</option>
                                    <option value="Cơ sở 4: Số 99 Nguyễn Huệ, Quận 1, TP. HCM">TP. HCM - Cơ sở 4: Số 99 Nguyễn Huệ, Q.1</option>
                                    <option value="Cơ sở 5: Số 250 CMT8, Q.3, TP. HCM">TP. HCM - Cơ sở 5: Số 250 Cách Mạng Tháng 8, Q.3</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark" for="pickup_customer_name">Họ tên người nhận *</label>
                                <input type="text" id="pickup_customer_name" class="form-control"
                                       value="<?= old('customer_name', $user['name'] ?? '') ?>" placeholder="Nguyễn Văn A" oninput="syncPickupToShipping()">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark" for="pickup_phone">Số điện thoại *</label>
                                <input type="text" id="pickup_phone" class="form-control"
                                       value="<?= old('phone', $user['phone'] ?? '') ?>" placeholder="09xxxxxxxx" oninput="syncPickupToShipping()">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- SECTION 2: PHƯƠNG THỨC THANH TOÁN -->
                <div class="mb-4">
                    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2" style="font-size: 17px;">
                        <span class="badge bg-danger rounded-circle p-1 d-inline-grid place-items-center" style="width: 26px; height: 26px; font-size: 13px;">2</span>
                        Chọn Phương Thức Thanh Toán
                    </h5>

                    <!-- Option 1: COD -->
                    <div class="choice-list-option <?= !in_array($oldPayment, ['vnpay', 'momo', 'installment', 'bank_transfer'], true) ? 'selected' : '' ?>" id="pay-option-cash" onclick="selectPaymentMethod('cash')">
                        <div class="d-flex align-items-start gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash" <?= !in_array($oldPayment, ['vnpay', 'momo', 'installment', 'bank_transfer'], true) ? 'checked' : '' ?>>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="fw-bold text-dark mb-0 cursor-pointer" for="cash">
                                        💵 Thanh Toán Khi Nhận Hàng (COD)
                                    </label>
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-0.5" style="font-size: 11px;">Tiền Mặt</span>
                                </div>
                                <div class="text-muted small mt-1">Khách hàng thanh toán tiền mặt trực tiếp cho nhân viên giao hàng hoặc thu ngân cửa hàng.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Option 2: VietQR -->
                    <div class="choice-list-option <?= $oldPayment === 'bank_transfer' ? 'selected' : '' ?>" id="pay-option-bank_transfer" onclick="selectPaymentMethod('bank_transfer')">
                        <div class="d-flex align-items-start gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer" <?= $oldPayment === 'bank_transfer' ? 'checked' : '' ?>>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="fw-bold text-dark mb-0 cursor-pointer" for="bank_transfer">
                                        🏦 Chuyển Khoản Ngân Hàng (VietQR)
                                    </label>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5" style="font-size: 11px;">Khuyên Dùng</span>
                                </div>
                                <div class="text-muted small mt-1">Mở app Ngân hàng quét mã VietQR tự động điền đúng số tiền và nội dung chuyển khoản.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Expandable VietQR Card Preview -->
                    <div id="bank-transfer-preview" class="expandable-form-box py-3" style="display:none">
                        <div class="row align-items-center g-3">
                            <div class="col-auto">
                                <img src="/image/vietqr_mb_toquanghuy.png" alt="VietQR MB" style="width:110px;height:auto;border-radius:12px;border:1px solid #cbd5e1; background:#fff; padding: 4px;">
                            </div>
                            <div class="col small">
                                <div class="fw-bold text-dark mb-1">Tài khoản thụ hưởng MB Bank:</div>
                                <div>Số TK: <code class="fs-6 text-primary fw-bold">0788415516</code></div>
                                <div>Chủ TK: <strong class="text-dark">TÔ QUANG HUY</strong></div>
                                <div class="text-muted mt-1" style="font-size: 11px;">Mã QR chuyển khoản tự động kèm nội dung đơn hàng sẽ hiển thị sau khi bấm Đặt hàng.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Option 3: VNPay -->
                    <div class="choice-list-option <?= $oldPayment === 'vnpay' ? 'selected' : '' ?> <?= !$isMember ? 'opacity-75' : '' ?>" id="pay-option-vnpay" onclick="selectPaymentMethod('vnpay')">
                        <div class="d-flex align-items-start gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="vnpay" value="vnpay" <?= $oldPayment === 'vnpay' ? 'checked' : '' ?> <?= !$isMember ? 'disabled' : '' ?>>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="fw-bold text-dark mb-0 cursor-pointer" for="vnpay">
                                        💳 Thẻ ATM / QR Ngân Hàng Qua VNPay
                                    </label>
                                    <span class="badge bg-primary px-2 py-0.5" style="font-size: 11px;">VNPAY Gateway</span>
                                </div>
                                <div class="text-muted small mt-1">Thanh toán qua thẻ ATM nội địa, Visa/Mastercard hoặc cổng quét VNPAY-QR.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Expandable VNPay Form -->
                    <div id="vnpay-options" class="expandable-form-box" style="display:none">
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-credit-card"></i> Thông Tin Thẻ ATM Ngân Hàng Test</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark" for="bank">Ngân hàng phát hành *</label>
                                <select name="bank" id="bank" class="form-select form-select-sm">
                                    <option value="">-- Chọn ngân hàng --</option>
                                    <?php foreach ($banks as $bank): ?>
                                        <option value="<?= e($bank['code']) ?>" <?= old('bank') === $bank['code'] ? 'selected' : '' ?>>
                                            <?= e($bank['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark" for="card_number">Số thẻ ATM *</label>
                                <input type="text" name="card_number" id="card_number" class="form-control form-control-sm font-monospace"
                                       placeholder="9704198526191432 (Test NCB)"
                                       value="<?= old('card_number') ?>" inputmode="numeric" autocomplete="off">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark" for="card_holder">Tên chủ thẻ *</label>
                                <input type="text" name="card_holder" id="card_holder" class="form-control form-control-sm"
                                       placeholder="NGUYEN VAN A"
                                       value="<?= old('card_holder') ?>" autocomplete="off"
                                       style="text-transform:uppercase">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark" for="issue_date">Ngày phát hành (MM/YY) *</label>
                                <input type="text" name="issue_date" id="issue_date" class="form-control form-control-sm"
                                       placeholder="07/15" value="<?= old('issue_date') ?>"
                                       maxlength="5" inputmode="numeric">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted" for="issue_date_picker">Chọn nhanh từ lịch</label>
                                <input type="month" id="issue_date_picker" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="alert alert-light border rounded-3 p-2 text-muted small mt-3 mb-0">
                            Thẻ test NCB Sandbox: <code><?= e(config('vnpay.test_card.card_number')) ?></code> · Mã OTP test: <code><?= e(config('vnpay.test_card.otp')) ?></code>
                        </div>
                    </div>

                    <!-- Option 4: MoMo -->
                    <div class="choice-list-option <?= $oldPayment === 'momo' ? 'selected' : '' ?> <?= !$isMember ? 'opacity-75' : '' ?>" id="pay-option-momo" onclick="selectPaymentMethod('momo')">
                        <div class="d-flex align-items-start gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="momo" value="momo" <?= $oldPayment === 'momo' ? 'checked' : '' ?> <?= !$isMember ? 'disabled' : '' ?>>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="fw-bold text-dark mb-0 cursor-pointer" for="momo">
                                        🟣 Ví Điện Tử MoMo
                                    </label>
                                    <span class="badge bg-danger px-2 py-0.5" style="font-size: 11px; background:#a50064 !important;">MoMo Wallet</span>
                                </div>
                                <div class="text-muted small mt-1">Thanh toán trực tiếp qua ứng dụng Ví MoMo (Sandbox test limit 20trđ/lần).</div>
                            </div>
                        </div>
                    </div>

                    <!-- Option 5: Trả Góp -->
                    <div class="choice-list-option <?= $oldPayment === 'installment' ? 'selected' : '' ?> <?= !$isMember ? 'opacity-75' : '' ?>" id="pay-option-installment" onclick="selectPaymentMethod('installment')">
                        <div class="d-flex align-items-start gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="installment" value="installment" <?= $oldPayment === 'installment' ? 'checked' : '' ?> <?= !$isMember ? 'disabled' : '' ?>>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="fw-bold text-dark mb-0 cursor-pointer" for="installment">
                                        📅 Trả Góp Lãi Suất Ưu Đãi (Duyệt Hồ Sơ Nội Bộ)
                                    </label>
                                    <span class="badge bg-warning text-dark px-2 py-0.5" style="font-size: 11px;">Trả Góp 0%</span>
                                </div>
                                <div class="text-muted small mt-1">Đặt cọc trước 20%, chia nhỏ thanh toán theo kỳ hạn 3/6/9/12 tháng.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Expandable Installment Form -->
                    <div id="installment-options" class="expandable-form-box" style="display:none">
                        <label class="form-label small fw-bold text-dark">Kỳ hạn trả góp mong muốn *</label>
                        <select name="installment_months" class="form-select form-select-sm">
                            <?php foreach ([3,6,9,12] as $m): ?>
                                <option value="<?= $m ?>" <?= old('installment_months', $_GET['months'] ?? '6') === (string)$m ? 'selected' : '' ?>><?= $m ?> Tháng</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php if (!$isMember): ?>
                        <div class="alert alert-warning border-0 rounded-3 mt-3 mb-0 small">
                            ⚠️ <strong>Thanh toán Online & Trả góp dành riêng cho thành viên.</strong> 
                            Vui lòng <a href="/login" class="fw-bold text-dark text-decoration-underline">Đăng nhập</a> hoặc <a href="/register" class="fw-bold text-dark text-decoration-underline">Đăng ký</a> để sử dụng.
                        </div>
                    <?php endif; ?>
                </div>

                <!-- SECTION 3: XUẤT HÓA ĐƠN VAT -->
                <section class="vat-auto-card mb-4" id="vat-auto-card">
                    <div class="vat-auto-head">
                        <div class="vat-auto-icon" aria-hidden="true"><i class="bi bi-receipt-cutoff"></i></div>
                        <div class="vat-auto-copy">
                            <label class="vat-auto-title" for="vat_required">Xuất Hóa Đơn Điện Tử (VAT)</label>
                            <p>Tự động lấy thông tin từ người đặt hàng để xuất hóa đơn VAT điện tử.</p>
                        </div>
                        <div class="form-check form-switch vat-auto-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="vat_required" id="vat_required" value="1"
                                   aria-controls="vat-details" aria-expanded="false"
                                   <?= !empty($_SESSION['_old']['vat_required']) ? 'checked' : '' ?>>
                        </div>
                    </div>
                    <div id="vat-details" class="vat-auto-details">
                        <div class="text-success small fw-bold mb-2"><i class="bi bi-check-circle-fill me-1"></i> Tự động phát hành hóa đơn khi đơn giao thành công</div>
                        <div class="vat-preview-grid">
                            <div class="vat-preview-item">
                                <small>Người nhận hóa đơn</small>
                                <strong id="vat-preview-name"><?= e(old('customer_name', $user['name'] ?? 'Chưa có thông tin')) ?></strong>
                            </div>
                            <div class="vat-preview-item">
                                <small>Email nhận hóa đơn</small>
                                <strong id="vat-preview-email"><?= e(old('email', $user['email'] ?? 'Chưa có thông tin')) ?></strong>
                            </div>
                            <div class="vat-preview-item">
                                <small>Địa chỉ xuất hóa đơn</small>
                                <strong id="vat-preview-address"><?= e(old('shipping_address', 'Chưa có thông tin')) ?></strong>
                            </div>
                        </div>
                    </div>
                </section>

                <button type="submit" class="btn btn-danger btn-lg w-100 fw-extrabold rounded-3 shadow-sm py-3 mb-4" id="submit-btn" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none;">
                    ✅ Xác Nhận Đặt Hàng (COD)
                </button>
            </div>

            <!-- Right Column: Order Summary Sidebar -->
            <div class="col-lg-5">
                <div class="checkout-summary-card">
                    <h5 class="fw-bold mb-3 text-dark d-flex justify-content-between align-items-center">
                        <span>Đơn Hàng Của Bạn</span>
                        <span class="badge bg-dark rounded-pill px-2.5 py-1" style="font-size: 12px;"><?= count($cart) ?> sản phẩm</span>
                    </h5>

                    <!-- Cart Item List -->
                    <div class="cart-summary-items mb-3" style="max-height: 280px; overflow-y: auto;">
                        <?php foreach ($cart as $item): ?>
                            <div class="d-flex justify-content-between align-items-center py-2.5 border-bottom gap-2">
                                <div class="d-flex align-items-center gap-2.5 min-w-0">
                                    <img src="<?= e(imageUrl($item['image'] ?? '', 'thumb')) ?>"
                                         alt="<?= e($item['name'] ?? '') ?>"
                                         style="width:48px;height:48px;object-fit:contain;border-radius:10px;border:1px solid #e2e8f0;background:#fff;padding:2px;flex-shrink:0"
                                         loading="lazy"
                                         onerror="this.onerror=null;this.src='/image/logo.webp';">
                                    <div>
                                        <div class="small fw-bold text-dark text-truncate" style="max-width: 170px;"><?= e($item['name']) ?></div>
                                        <div class="text-muted small">Số lượng: <?= (int) $item['quantity'] ?></div>
                                    </div>
                                </div>
                                <strong class="text-dark small text-nowrap"><?= formatMoney($item['price'] * $item['quantity']) ?></strong>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Price Calculations Breakdown -->
                    <div class="d-flex justify-content-between py-2 small">
                        <span class="text-muted">Tạm tính:</span>
                        <span id="subtotal-label" class="fw-bold text-dark"><?= formatMoney($total) ?></span>
                    </div>

                    <div class="d-flex justify-content-between py-2 small text-danger" id="discount-row" style="display:none !important">
                        <span>Mã giảm giá (<span id="discount-percent-label">0</span>%):</span>
                        <strong id="discount-amount-label">-0 đ</strong>
                    </div>

                    <div class="d-flex justify-content-between py-2 small text-danger" id="voucher-discount-row" style="display:none !important">
                        <span>Voucher cá nhân (<span id="voucher-percent-label">0</span>%):</span>
                        <strong id="voucher-amount-label">-0 đ</strong>
                    </div>

                    <div class="d-flex justify-content-between py-2 small text-danger" id="loyalty-discount-row" style="display:none !important">
                        <span>Ưu đãi thành viên (<?= e($isMember ? (authUser()['loyalty_tier'] ?? 'Standard') : 'Standard') ?>: <span id="loyalty-percent-label">0</span>%):</span>
                        <strong id="loyalty-amount-label">-0 đ</strong>
                    </div>

                    <div class="d-flex justify-content-between py-2 small" id="shipping-fee-row" style="display:none !important">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <strong id="shipping-fee-label" class="text-primary">0 đ</strong>
                    </div>

                    <div class="d-flex justify-content-between pt-3 border-top mt-2 align-items-center">
                        <span class="fw-bold text-dark fs-6">Tổng Thanh Toán:</span>
                        <span class="fw-extrabold text-danger fs-4" id="final-total-label"><?= formatMoney($total) ?></span>
                    </div>

                    <!-- Coupon Input Box -->
                    <div class="mt-4 pt-3 border-top">
                        <label class="form-label fw-bold small text-dark mb-1"><i class="bi bi-ticket-perforated text-danger me-1"></i>Nhập mã giảm giá (Coupon)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="coupon-code-input" class="form-control" placeholder="Mã giảm giá (VD: HADES50)">
                            <button type="button" id="apply-coupon-btn" class="btn btn-dark fw-bold px-3">Áp dụng</button>
                        </div>
                        <div id="coupon-message" class="small mt-2" style="display:none"></div>
                    </div>

                    <!-- Personal Vouchers Dropdown -->
                    <?php if ($isMember && !empty($userVouchers)): ?>
                        <div class="mt-3 pt-3 border-top">
                            <label class="form-label fw-bold small text-dark mb-1"><i class="bi bi-gift text-danger me-1"></i>Voucher trợ giá cá nhân</label>
                            <select name="user_voucher_code" class="form-select form-select-sm" form="checkout-form">
                                <option value="">-- Chọn voucher trợ giá --</option>
                                <?php foreach ($userVouchers as $v): ?>
                                    <option value="<?= e($v['code']) ?>">
                                        <?= e($v['code']) ?> — giảm <?= (int) $v['discount_percent'] ?>%
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <!-- Wallet Integration -->
                    <?php if ($isMember): ?>
                        <div class="mt-3 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold small text-dark mb-0"><i class="bi bi-wallet2 text-primary me-1"></i>Ví Điện Tử Hades</label>
                                <a href="/wallet" class="small text-decoration-none text-primary fw-semibold" style="font-size: 11px;">Xem số dư →</a>
                            </div>
                            <?php if (($walletBalance ?? 0) > 0): ?>
                                <div class="form-check mt-1">
                                    <input class="form-check-input" type="checkbox" name="use_wallet" value="1" id="use_wallet" form="checkout-form">
                                    <label class="form-check-label small text-dark" for="use_wallet">
                                        Trừ số dư ví (Khả dụng: <strong><?= formatMoney($walletBalance) ?></strong>)
                                    </label>
                                </div>
                            <?php else: ?>
                                <p class="small text-muted mb-0" style="font-size: 11px;">Số dư ví hiện tại: 0đ</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </form>
</div>

<!-- Map Selection Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fs-6 fw-bold" id="mapModalLabel">📍 Chọn vị trí giao hàng trên bản đồ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="input-group mb-3">
                    <input type="text" id="map-search-input" class="form-control" placeholder="Nhập địa chỉ cần tìm (VD: Landmark 81, Tây Hồ, Hà Nội)...">
                    <button class="btn btn-danger text-white px-4 fw-bold" type="button" id="map-search-btn">Tìm kiếm</button>
                </div>
                <div id="map-container" style="height: 380px; width: 100%; border-radius: 12px; border: 1px solid #cbd5e1; z-index: 1;"></div>
                <div class="mt-3">
                    <label class="form-label fw-bold small text-muted mb-1">Địa điểm chọn:</label>
                    <input type="text" id="selected-address-preview" class="form-control bg-light fw-semibold" readonly placeholder="Click chọn điểm trên bản đồ...">
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger px-4 fw-bold" id="confirm-address-btn" disabled>Xác nhận địa chỉ này</button>
            </div>
        </div>
    </div>
</div>

<script>
// Receive Method Switcher
function selectDeliveryMethod(method) {
    document.getElementById('delivery_shipping').checked = (method === 'shipping');
    document.getElementById('delivery_pickup').checked = (method === 'pickup');
    
    document.getElementById('delivery-option-shipping').classList.toggle('selected', method === 'shipping');
    document.getElementById('delivery-option-pickup').classList.toggle('selected', method === 'pickup');
    
    const shippingBox = document.getElementById('shipping-form-box');
    const pickupBox = document.getElementById('pickup-form-box');
    
    if (method === 'shipping') {
        shippingBox.style.display = 'block';
        pickupBox.style.display = 'none';
        document.querySelectorAll('.shipping-required').forEach(el => el.required = true);
    } else {
        shippingBox.style.display = 'none';
        pickupBox.style.display = 'block';
        document.querySelectorAll('.shipping-required').forEach(el => el.required = false);
        
        // Zero shipping fee for store pickup
        if (window.hadesSetShippingFee) window.hadesSetShippingFee(0);
        updatePickupStoreAddress(document.getElementById('store_branch'));
    }
}

function updatePickupStoreAddress(selectEl) {
    const storeAddress = selectEl.value;
    document.getElementById('shipping_address').value = storeAddress;
}

function syncPickupToShipping() {
    const pName = document.getElementById('pickup_customer_name').value;
    const pPhone = document.getElementById('pickup_phone').value;
    document.getElementById('customer_name').value = pName;
    document.getElementById('phone').value = pPhone;
}

// Payment Method Switcher
function selectPaymentMethod(method) {
    const radio = document.getElementById(method);
    if (radio && !radio.disabled) {
        radio.checked = true;
        document.querySelectorAll('[id^="pay-option-"]').forEach(card => card.classList.remove('selected'));
        const activeCard = document.getElementById('pay-option-' + method);
        if (activeCard) activeCard.classList.add('selected');
        
        // Trigger event
        const event = new Event('change');
        radio.dispatchEvent(event);
    }
}

(function () {
    const cashRadio = document.getElementById('cash');
    const vnpayRadio = document.getElementById('vnpay');
    const momoRadio = document.getElementById('momo');
    const installmentRadio = document.getElementById('installment');
    const bankTransferRadio = document.getElementById('bank_transfer');
    
    const vnpayBox = document.getElementById('vnpay-options');
    const installmentBox = document.getElementById('installment-options');
    const bankPreview = document.getElementById('bank-transfer-preview');
    const submitBtn = document.getElementById('submit-btn');
    const vnpayFields = vnpayBox.querySelectorAll('input, select');

    function isVnpay() { return vnpayRadio && vnpayRadio.checked && !vnpayRadio.disabled; }
    function isMomo() { return momoRadio && momoRadio.checked && !momoRadio.disabled; }
    function isInstallment() { return installmentRadio && installmentRadio.checked && !installmentRadio.disabled; }
    function isBankTransfer() { return bankTransferRadio && bankTransferRadio.checked; }

    function togglePaymentFields() {
        const vnpay = isVnpay();
        const momo = isMomo();
        const installment = isInstallment();
        const bank = isBankTransfer();

        vnpayBox.style.display = vnpay ? 'block' : 'none';
        if (installmentBox) installmentBox.style.display = installment ? 'block' : 'none';
        if (bankPreview) bankPreview.style.display = bank ? 'block' : 'none';

        if (momo) {
            submitBtn.textContent = '🟣 Thanh Toán Qua Ví MoMo';
        } else if (vnpay) {
            submitBtn.textContent = '💳 Thanh Toán Trực Tuyến VNPay';
        } else if (installment) {
            submitBtn.textContent = '📅 Gửi Hồ Sơ Trả Góp';
        } else if (bank) {
            submitBtn.textContent = '🏦 Đặt Hàng & Quét Mã VietQR';
        } else {
            submitBtn.textContent = '✅ Xác Nhận Đặt Hàng (COD)';
        }

        vnpayFields.forEach(function (el) {
            if (el.id === 'issue_date_picker') { el.disabled = !vnpay; return; }
            el.disabled = !vnpay;
            el.required = vnpay;
        });
    }

    document.querySelectorAll('input[name="payment_method"]').forEach(function (radio) {
        radio.addEventListener('change', togglePaymentFields);
    });

    const issueDateInput = document.getElementById('issue_date');
    const cardHolderInput = document.getElementById('card_holder');

    if (issueDateInput) {
        issueDateInput.addEventListener('input', function () {
            const digits = this.value.replace(/\D/g, '').slice(0, 4);
            if (digits.length <= 2) this.value = digits;
            else this.value = digits.slice(0, 2) + '/' + digits.slice(2);
        });
    }
    if (cardHolderInput) {
        cardHolderInput.addEventListener('input', function () {
            this.value = this.value.toUpperCase();
        });
    }

    // Coupon & Calculation Handlers
    const couponInput = document.getElementById('coupon-code-input');
    const applyCouponBtn = document.getElementById('apply-coupon-btn');
    const couponMsg = document.getElementById('coupon-message');
    const hiddenCoupon = document.getElementById('hidden-coupon-code');
    const discountRow = document.getElementById('discount-row');
    const discountPercentLabel = document.getElementById('discount-percent-label');
    const discountAmountLabel = document.getElementById('discount-amount-label');
    const finalTotalLabel = document.getElementById('final-total-label');
    const shippingFeeRow = document.getElementById('shipping-fee-row');
    const shippingFeeLabel = document.getElementById('shipping-fee-label');
    const baseTotal = <?= (int) $total ?>;
    let activeShippingFee = 0;
    let activeDiscountPercent = 0;

    const loyaltyPercent = <?= $isMember ? \App\Services\LoyaltyService::getDiscountPercent(authUser()['loyalty_tier'] ?? 'Standard') : 0 ?>;
    const loyaltyDiscountRow = document.getElementById('loyalty-discount-row');
    const loyaltyPercentLabel = document.getElementById('loyalty-percent-label');
    const loyaltyAmountLabel = document.getElementById('loyalty-amount-label');

    const voucherDiscountRow = document.getElementById('voucher-discount-row');
    const voucherPercentLabel = document.getElementById('voucher-percent-label');
    const voucherAmountLabel = document.getElementById('voucher-amount-label');
    const userVoucherSelect = document.querySelector('select[name="user_voucher_code"]');

    function formatVND(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount).replace(/₫/g, 'đ');
    }

    window.hadesSetShippingFee = function (fee) {
        const deliveryMethod = document.querySelector('input[name="delivery_method"]:checked')?.value || 'shipping';
        if (deliveryMethod === 'pickup') {
            activeShippingFee = 0;
        } else {
            activeShippingFee = Math.max(0, parseInt(fee || 0, 10));
        }

        if (activeShippingFee > 0) {
            shippingFeeLabel.textContent = formatVND(activeShippingFee);
            shippingFeeRow.style.setProperty('display', 'flex', 'important');
        } else {
            shippingFeeRow.style.setProperty('display', 'none', 'important');
        }
        recalculateCheckoutTotal();
    };

    function recalculateCheckoutTotal() {
        let currentTotal = baseTotal;

        if (activeDiscountPercent > 0) {
            let couponDiscountVal = Math.round((currentTotal * activeDiscountPercent) / 100);
            currentTotal -= couponDiscountVal;
            discountPercentLabel.textContent = activeDiscountPercent;
            discountAmountLabel.textContent = '-' + formatVND(couponDiscountVal);
            discountRow.style.setProperty('display', 'flex', 'important');
        } else {
            discountRow.style.setProperty('display', 'none', 'important');
        }

        let voucherPercent = 0;
        if (userVoucherSelect && userVoucherSelect.value !== '') {
            const selectedOpt = userVoucherSelect.options[userVoucherSelect.selectedIndex];
            if (selectedOpt) {
                const match = selectedOpt.textContent.match(/giảm\s*(\d+)%/i);
                if (match) voucherPercent = parseInt(match[1], 10);
            }
        }
        if (voucherPercent > 0) {
            let voucherDiscountVal = Math.round((currentTotal * voucherPercent) / 100);
            currentTotal -= voucherDiscountVal;
            voucherPercentLabel.textContent = voucherPercent;
            voucherAmountLabel.textContent = '-' + formatVND(voucherDiscountVal);
            voucherDiscountRow.style.setProperty('display', 'flex', 'important');
        } else {
            voucherDiscountRow.style.setProperty('display', 'none', 'important');
        }

        if (loyaltyPercent > 0) {
            let loyaltyDiscountVal = Math.round((currentTotal * loyaltyPercent) / 100);
            currentTotal -= loyaltyDiscountVal;
            loyaltyPercentLabel.textContent = loyaltyPercent;
            loyaltyAmountLabel.textContent = '-' + formatVND(loyaltyDiscountVal);
            loyaltyDiscountRow.style.setProperty('display', 'flex', 'important');
        } else {
            loyaltyDiscountRow.style.setProperty('display', 'none', 'important');
        }

        const finalTotal = Math.max(0, currentTotal + activeShippingFee);
        finalTotalLabel.textContent = formatVND(finalTotal);
    }

    if (userVoucherSelect) {
        userVoucherSelect.addEventListener('change', recalculateCheckoutTotal);
    }

    if (applyCouponBtn && couponInput) {
        applyCouponBtn.addEventListener('click', function () {
            const code = couponInput.value.trim();
            if (!code) return;
            applyCouponBtn.disabled = true;
            fetch('/api/promotions/check?code=' + encodeURIComponent(code))
                .then(res => res.json())
                .then(data => {
                    applyCouponBtn.disabled = false;
                    couponMsg.style.display = 'block';
                    if (data.valid) {
                        couponMsg.className = 'small mt-2 text-success fw-bold';
                        couponMsg.textContent = data.message;
                        activeDiscountPercent = data.discount_percent;
                        hiddenCoupon.value = data.code;
                    } else {
                        couponMsg.className = 'small mt-2 text-danger';
                        couponMsg.textContent = data.message;
                        hiddenCoupon.value = '';
                        activeDiscountPercent = 0;
                    }
                    recalculateCheckoutTotal();
                })
                .catch(() => { applyCouponBtn.disabled = false; });
        });
    }

    // VAT checkbox toggle
    const vatCheckbox = document.getElementById('vat_required');
    const vatDetails = document.getElementById('vat-details');
    const vatCard = document.getElementById('vat-auto-card');
    if (vatCheckbox && vatDetails) {
        vatCheckbox.addEventListener('change', function() {
            vatDetails.style.display = this.checked ? 'block' : 'none';
            if (vatCard) vatCard.classList.toggle('is-selected', this.checked);
        });
    }

    togglePaymentFields();
    recalculateCheckoutTotal();
})();
</script>

<script>
// LEAFLET MAP & GEOLOCATION SCRIPT
(function() {
    let map = null, marker = null, selectedLat = null, selectedLng = null, selectedAddr = "";
    const stores = [
        { name: 'Trụ sở chính', address: 'Võng Thị, Tây Hồ, Hà Nội', lat: 21.05440, lng: 105.81180, radius: 25 },
        { name: 'Cơ sở 2', address: 'Số 3 Duy Tân, Cầu Giấy, Hà Nội', lat: 21.02940, lng: 105.78250, radius: 25 },
        { name: 'Cơ sở 3', address: 'Lê Duẩn, Hải Châu, Đà Nẵng', lat: 16.05940, lng: 108.22080, radius: 25 },
        { name: 'Cơ sở 4', address: 'Số 99 Nguyễn Huệ, Quận 1, TP. HCM', lat: 10.77560, lng: 106.70190, radius: 25 },
        { name: 'Cơ sở 5', address: 'Số 250 CMT8, Q.3, Sài Gòn', lat: 10.78180, lng: 106.67980, radius: 25 }
    ];

    const openMapBtn = document.getElementById('open-map-btn');
    const currentLocationBtn = document.getElementById('use-current-location-btn');
    const mapModalEl = document.getElementById('mapModal');

    if (openMapBtn) {
        openMapBtn.addEventListener('click', function() {
            const modal = new bootstrap.Modal(mapModalEl);
            modal.show();
        });
    }

    if (mapModalEl) {
        mapModalEl.addEventListener('shown.bs.modal', function () {
            if (!map) {
                map = L.map('map-container').setView([21.028511, 105.804817], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                map.on('click', function (e) {
                    updateMarker(e.latlng.lat, e.latlng.lng);
                });
            } else {
                map.invalidateSize();
            }

            const currentAddr = document.getElementById('shipping_address').value.trim();
            if (currentAddr !== "" && !selectedLat) {
                document.getElementById('map-search-input').value = currentAddr;
                performSearch(true);
            }
        });
    }

    if (currentLocationBtn) {
        currentLocationBtn.addEventListener('click', function() {
            if (navigator.geolocation) {
                currentLocationBtn.disabled = true;
                currentLocationBtn.innerHTML = '⏳ Đang định vị...';
                navigator.geolocation.getCurrentPosition(
                    position => {
                        currentLocationBtn.disabled = false;
                        currentLocationBtn.innerHTML = '🎯 Vị trí hiện tại';
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        updateMarker(lat, lng);
                        if (map) map.setView([lat, lng], 16);
                    },
                    error => {
                        currentLocationBtn.disabled = false;
                        currentLocationBtn.innerHTML = '🎯 Vị trí hiện tại';
                        alert('Không thể lấy vị trí hiện tại. Vui lòng cho phép quyền truy cập vị trí trên trình duyệt.');
                    }
                );
            } else {
                alert('Trình duyệt của bạn không hỗ trợ Geolocation.');
            }
        });
    }

    function updateMarker(lat, lng) {
        selectedLat = lat;
        selectedLng = lng;
        document.getElementById('shipping_lat').value = lat.toFixed(8);
        document.getElementById('shipping_lng').value = lng.toFixed(8);
        updateShippingPreview(lat, lng);
        
        if (map) {
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                marker.on('dragend', function (e) {
                    const pos = marker.getLatLng();
                    updateMarker(pos.lat, pos.lng);
                });
            }
            map.panTo([lat, lng]);
        }

        const previewInput = document.getElementById('selected-address-preview');
        const confirmBtn = document.getElementById('confirm-address-btn');
        if (previewInput) previewInput.value = "Đang xác thực tọa độ...";
        if (confirmBtn) confirmBtn.disabled = true;

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=vi`)
            .then(res => res.json())
            .then(data => {
                if (data && data.display_name) {
                    selectedAddr = data.display_name;
                    if (previewInput) previewInput.value = selectedAddr;
                    if (document.getElementById('shipping_address').value.trim() === '') {
                        document.getElementById('shipping_address').value = selectedAddr;
                    }
                    if (confirmBtn) confirmBtn.disabled = false;
                } else {
                    selectedAddr = `Tọa độ: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
                    if (previewInput) previewInput.value = selectedAddr;
                    if (confirmBtn) confirmBtn.disabled = false;
                }
            })
            .catch(() => {
                selectedAddr = `Tọa độ: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
                if (previewInput) previewInput.value = selectedAddr;
                if (confirmBtn) confirmBtn.disabled = false;
            });
    }

    function performSearch(quiet = false) {
        const query = document.getElementById('map-search-input').value.trim();
        if (!query) return;

        const searchBtn = document.getElementById('map-search-btn');
        if (searchBtn) {
            searchBtn.disabled = true;
            searchBtn.textContent = "Đang tìm...";
        }

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&accept-language=vi`)
            .then(res => res.json())
            .then(results => {
                if (searchBtn) {
                    searchBtn.disabled = false;
                    searchBtn.textContent = "Tìm kiếm";
                }
                if (results && results.length > 0) {
                    const lat = parseFloat(results[0].lat);
                    const lon = parseFloat(results[0].lon);
                    updateMarker(lat, lon);
                    if (map) map.setView([lat, lon], 16);
                } else if (!quiet) {
                    alert("Không tìm thấy địa điểm phù hợp.");
                }
            })
            .catch(() => {
                if (searchBtn) {
                    searchBtn.disabled = false;
                    searchBtn.textContent = "Tìm kiếm";
                }
            });
    }

    const searchBtn = document.getElementById('map-search-btn');
    if (searchBtn) searchBtn.addEventListener('click', () => performSearch(false));
    
    const searchInput = document.getElementById('map-search-input');
    if (searchInput) {
        searchInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); performSearch(false); }
        });
    }

    const confirmBtn = document.getElementById('confirm-address-btn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            if (selectedAddr) {
                document.getElementById('shipping_address').value = selectedAddr;
                const modalInstance = bootstrap.Modal.getInstance(mapModalEl);
                if (modalInstance) modalInstance.hide();
            }
        });
    }

    function distanceKm(lat1, lng1, lat2, lng2) {
        const r = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) ** 2
            + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
            * Math.sin(dLng / 2) ** 2;
        return r * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }

    function shippingFee(distance) {
        if (distance <= 3) return 0;
        if (distance <= 10) return 25000;
        return 25000 + Math.ceil(distance - 10) * 5000;
    }

    function etaLabel(distance) {
        if (distance > 100) return 'mất 3 ngày';
        if (distance > 50) return 'mất 2 ngày';
        if (distance > 20) return 'mất 1 ngày';
        if (distance < 10) return 'mất 1h';
        return 'mất 1 ngày';
    }

    function updateShippingPreview(lat, lng) {
        const box = document.getElementById('shipping-estimate-box');
        if (!box) return;

        const nearest = stores
            .map(store => ({ ...store, distance: distanceKm(lat, lng, store.lat, store.lng) }))
            .sort((a, b) => a.distance - b.distance)[0];

        const fee = shippingFee(nearest.distance);
        if (window.hadesSetShippingFee) window.hadesSetShippingFee(fee);

        box.style.display = 'block';
        box.innerHTML = '<strong>Giao từ ' + nearest.name + '</strong> — cách bạn khoảng '
            + nearest.distance.toFixed(1) + ' km. Phí ship: <strong>' + formatMoneyClient(fee)
            + '</strong>. Giao ' + etaLabel(nearest.distance) + '.<br><span class="small text-muted">'
            + nearest.address + '</span>';
    }

    function formatMoneyClient(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount).replace(/₫/g, 'đ');
    }
})();
</script>
