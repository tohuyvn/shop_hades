<style>
.warranty-banner-header {
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
.warranty-banner-header::after {
    content: '';
    position: absolute;
    right: -40px;
    top: -40px;
    width: 220px;
    height: 220px;
    background: rgba(0, 242, 254, 0.2);
    border-radius: 50%;
    filter: blur(45px);
}
.warranty-search-card {
    background: var(--refract-card-bg, rgba(20, 24, 36, 0.65)) !important;
    backdrop-filter: var(--refract-blur, blur(28px)) saturate(190%) !important;
    -webkit-backdrop-filter: var(--refract-blur, blur(28px)) saturate(190%) !important;
    border-radius: 22px !important;
    border: 1px solid var(--refract-card-border, rgba(255, 255, 255, 0.18)) !important;
    box-shadow: var(--refract-chromatic) !important;
    padding: 24px;
    color: #f8fafc !important;
}
</style>

<div class="container py-4">
    <!-- Header Banner -->
    <div class="warranty-banner-header text-center">
        <span class="badge bg-primary rounded-pill px-3 py-1.5 fw-bold text-uppercase mb-2" style="font-size: 11px; letter-spacing: 1px;">Bảo Hành Điện Tử Hades</span>
        <h1 class="fw-bold mb-2 text-white" style="font-size: 2.2rem;">Hệ Thống Tra Cứu & Yêu Cầu Bảo Hành</h1>
        <p class="text-slate-300 mx-auto mb-0 small" style="max-width: 620px; color: #cbd5e1;">
            Nhập số điện thoại mua hàng, mã đơn hàng, mã bảo hành hoặc số IMEI thiết bị để kiểm tra thời hạn và kích hoạt yêu cầu sửa chữa.
        </p>
    </div>

    <!-- Search Box Card -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-9 col-lg-7">
            <div class="warranty-search-card">
                <form action="/warranty" method="get">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;">
                            <i class="bi bi-search text-primary"></i>
                        </span>
                        <input type="text" name="q" class="form-control border-start-0 border-end-0 bg-light fs-6" 
                               placeholder="Số điện thoại, mã đơn, mã BH hoặc IMEI..." 
                               value="<?= htmlspecialchars($query ?? '') ?>" required style="font-size: 15px;">
                        <button type="submit" class="btn btn-dark px-4 fw-bold" style="border-radius: 0 12px 12px 0;">
                            Tra Cứu <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if ($results !== null): ?>
        <div class="mt-4">
            <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-3 rounded-4 border shadow-sm">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-card-checklist text-primary me-2"></i>Kết quả tìm kiếm cho: 
                    <span class="text-primary font-monospace">"<?= htmlspecialchars($query) ?>"</span>
                </h5>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold">
                    <?= count($results) ?> kết quả
                </span>
            </div>

            <?php if (empty($results)): ?>
                <div class="text-center py-5 border rounded-4 bg-white shadow-sm">
                    <div class="text-muted mb-3" style="font-size: 3rem;"><i class="bi bi-shield-slash text-secondary opacity-50"></i></div>
                    <h5 class="fw-bold text-dark">Không tìm thấy thông tin bảo hành</h5>
                    <p class="text-muted mb-0 small" style="max-width: 450px; margin: 0 auto;">
                        Vui lòng kiểm tra lại số điện thoại hoặc mã IMEI. Nếu đơn hàng chưa giao thành công, bảo hành chưa được kích hoạt.
                    </p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($results as $w): ?>
                        <div class="col-12">
                            <div class="card border rounded-4 shadow-sm overflow-hidden bg-white">
                                <div class="card-header bg-light-subtle border-bottom p-4">
                                    <div class="row align-items-center g-3">
                                        <div class="col-md-7 d-flex align-items-center gap-3">
                                            <img src="<?= e(imageUrl($w['product_image'] ?? '', 'thumb')) ?>"
                                                 alt="<?= e($w['product_name'] ?? '') ?>"
                                                 class="rounded-3 border bg-white shadow-sm"
                                                 style="width: 68px; height: 68px; object-fit: contain; padding: 4px;"
                                                 loading="lazy"
                                                 onerror="this.onerror=null;this.src='/image/logo.webp';">
                                            <div>
                                                <h5 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($w['product_name']) ?></h5>
                                                <div class="d-flex flex-wrap gap-2 text-muted small mt-1">
                                                    <span>Mã BH: <strong class="font-monospace text-dark"><?= htmlspecialchars($w['warranty_code']) ?></strong></span>
                                                    <?php if (!empty($w['imei'])): ?>
                                                        <span>· IMEI: <strong class="font-monospace text-primary bg-primary-subtle px-1.5 py-0.5 rounded border border-primary-subtle"><?= htmlspecialchars($w['imei']) ?></strong></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 text-md-end">
                                            <?php if ($w['status'] === 'active'): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fw-bold" style="font-size: 13px;">
                                                    <i class="bi bi-shield-fill-check me-1"></i>Còn bảo hành
                                                </span>
                                            <?php elseif ($w['status'] === 'expired'): ?>
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 fw-bold" style="font-size: 13px;">
                                                    <i class="bi bi-shield-fill-x me-1"></i>Hết hạn bảo hành
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 fw-bold" style="font-size: 13px;">
                                                    <i class="bi bi-shield-fill-exclamation me-1"></i>Bị hủy (Void)
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body p-4">
                                    <div class="row g-4">
                                        <!-- Chi tiết thời hạn -->
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size: 11px; letter-spacing: 0.5px;">Thông tin thời hạn</h6>
                                            <div class="p-3 bg-light border rounded-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted small">Khách hàng:</span>
                                                    <span class="fw-semibold text-dark"><?= htmlspecialchars($w['customer_name']) ?></span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted small">Số điện thoại:</span>
                                                    <span class="fw-semibold text-dark"><?= htmlspecialchars($w['customer_phone']) ?></span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted small">Ngày kích hoạt:</span>
                                                    <span class="fw-semibold text-dark"><?= date('d/m/Y H:i', strtotime($w['started_at'])) ?></span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small">Ngày hết hạn:</span>
                                                    <span class="fw-semibold text-dark"><?= date('d/m/Y H:i', strtotime($w['expired_at'])) ?></span>
                                                </div>
                                            </div>

                                            <!-- Form yêu cầu bảo hành nếu còn hạn -->
                                            <?php if ($w['status'] === 'active'): ?>
                                                <div class="mt-4">
                                                    <button class="btn btn-outline-danger w-100 fw-bold py-2.5 rounded-3" 
                                                            type="button" 
                                                            data-bs-toggle="collapse" 
                                                            data-bs-target="#claimForm-<?= $w['id'] ?>" 
                                                            aria-expanded="false">
                                                        <i class="bi bi-wrench-adjustable me-2"></i>Gửi Yêu Cầu Sửa Chữa / Bảo Hành
                                                    </button>
                                                    
                                                    <div class="collapse mt-3" id="claimForm-<?= $w['id'] ?>">
                                                        <form action="/warranty/claim" method="POST" class="p-3 bg-white border rounded-3 shadow-sm">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="warranty_id" value="<?= $w['id'] ?>">
                                                            <input type="hidden" name="query" value="<?= htmlspecialchars($query) ?>">
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold text-dark small">Mô tả chi tiết sự cố sản phẩm</label>
                                                                <textarea name="customer_note" class="form-control" rows="3" 
                                                                          placeholder="Ví dụ: Máy không nhận sạc, màn hình bị chớp, pin chai nhanh..." required></textarea>
                                                            </div>
                                                            <button type="submit" class="btn btn-danger w-100 fw-bold py-2 rounded-3">Gửi Yêu Cầu Bảo Hành</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Lịch sử sửa chữa/bảo hành -->
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size: 11px; letter-spacing: 0.5px;">Lịch sử tiếp nhận sửa chữa</h6>
                                            
                                            <?php if (empty($w['claims'])): ?>
                                                <div class="text-center py-4 bg-light border rounded-3">
                                                    <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Thiết bị chưa từng có lịch sử sửa chữa.</span>
                                                </div>
                                            <?php else: ?>
                                                <div class="position-relative ps-3 border-start ms-2" style="border-color: #cbd5e1 !important;">
                                                    <?php foreach ($w['claims'] as $c): ?>
                                                        <div class="mb-4 position-relative">
                                                            <div class="position-absolute bg-white rounded-circle border border-primary d-flex align-items-center justify-content-center" 
                                                                 style="width: 14px; height: 14px; left: -21px; top: 4px;">
                                                                <div class="bg-primary rounded-circle" style="width: 6px; height: 6px;"></div>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                                <span class="text-muted small fw-semibold"><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></span>
                                                                
                                                                <?php if ($c['status'] === 'pending'): ?>
                                                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Chờ tiếp nhận</span>
                                                                <?php elseif ($c['status'] === 'receiving'): ?>
                                                                    <span class="badge bg-info-subtle text-info border border-info-subtle">Đang kiểm tra</span>
                                                                <?php elseif ($c['status'] === 'repairing'): ?>
                                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Đang sửa chữa</span>
                                                                <?php elseif ($c['status'] === 'completed'): ?>
                                                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Hoàn thành</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Từ chối</span>
                                                                <?php endif; ?>
                                                            </div>
                                                            
                                                            <div class="p-3 bg-light border rounded-3 shadow-none">
                                                                <p class="mb-2 text-dark small"><strong>Lỗi mô tả:</strong> <?= nl2br(htmlspecialchars($c['customer_note'])) ?></p>
                                                                <?php if (!empty($c['admin_note'])): ?>
                                                                    <div class="p-2 bg-white border-start border-3 border-primary rounded-1 mt-2">
                                                                        <p class="mb-0 text-muted small"><strong>Kỹ thuật viên:</strong> <?= nl2br(htmlspecialchars($c['admin_note'])) ?></p>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
