<div class="container py-5" style="max-width: 760px;">
    <div class="text-center mb-5">
        <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex p-3 mb-3">
            <i class="bi bi-patch-check-fill display-5"></i>
        </div>
        <h1 class="h2 fw-bold text-dark">Tra Cứu & Xác Thực Chứng Chỉ LMS</h1>
        <p class="text-muted">Nhập mã chứng chỉ in trên văn bằng hoặc quét mã QR để kiểm tra tính hợp lệ và chống làm giả.</p>
    </div>

    <!-- Search Form -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <form method="get" action="/verify-certificate">
            <div class="input-group input-group-lg">
                <input type="text" name="code" class="form-control font-monospace text-uppercase" placeholder="VD: CERT-2026-X8B9K2..." value="<?= e($code) ?>" required>
                <button type="submit" class="btn btn-primary px-4 fw-bold">
                    <i class="bi bi-search"></i> Kiểm tra
                </button>
            </div>
        </form>
    </div>

    <!-- Search Result -->
    <?php if ($searched): ?>
        <?php if ($cert): ?>
            <div class="card border-0 shadow rounded-4 p-4 border-top border-5 border-success">
                <div class="d-flex align-items-center gap-2 text-success fw-bold mb-3">
                    <i class="bi bi-check-circle-fill fs-4"></i>
                    <span>Chứng chỉ HỢP LỆ & ĐƯỢC CÔNG NHẬN</span>
                </div>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="small text-muted">Học viên:</div>
                        <div class="fs-5 fw-bold text-dark"><?= e($cert['student_name']) ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="small text-muted">Mã chứng chỉ:</div>
                        <div class="fs-5 fw-bold font-monospace text-primary"><?= e($cert['certificate_code']) ?></div>
                    </div>
                    <div class="col-12">
                        <div class="small text-muted">Khóa học hoàn thành:</div>
                        <div class="fs-5 fw-semibold text-dark"><?= e($cert['course_title']) ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="small text-muted">Giảng viên hướng dẫn:</div>
                        <div class="fw-semibold text-dark"><?= e($cert['instructor_name'] ?? 'Giảng viên LMS') ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="small text-muted">Ngày cấp:</div>
                        <div class="fw-semibold text-dark"><?= date('d/m/Y H:i', strtotime($cert['issued_at'])) ?></div>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-top text-end">
                    <a href="/certificates/<?= e($cert['certificate_code']) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                        <i class="bi bi-eye"></i> Xem bản in chứng chỉ
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger rounded-4 p-4 text-center">
                <i class="bi bi-exclamation-triangle-fill fs-3 mb-2 d-block"></i>
                <h5 class="fw-bold mb-1">Không tìm thấy chứng chỉ</h5>
                <p class="mb-0 small">Mã chứng chỉ "<strong><?= e($code) ?></strong>" không tồn tại trong hệ thống hoặc đã bị thu hồi.</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
