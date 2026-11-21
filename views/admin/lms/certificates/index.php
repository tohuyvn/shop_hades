<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1">Quản Lý Chứng Chỉ Cấp Phát</h1>
            <p class="text-muted mb-0">Danh sách các chứng chỉ hoàn thành khóa học đã được hệ thống công nhận</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalManualIssue">
                <i class="bi bi-award me-1"></i> Cấp Chứng Chỉ Thủ Công
            </button>
            <a href="/verify-certificate" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                <i class="bi bi-patch-check me-1"></i> Trang Tra Cứu Công Khai
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã chứng chỉ</th>
                        <th>Học viên</th>
                        <th>Khóa học hoàn thành</th>
                        <th>Ngày cấp</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($certificates)): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có chứng chỉ nào được cấp.</td></tr>
                    <?php else: ?>
                        <?php foreach ($certificates as $cert): ?>
                            <tr>
                                <td>
                                    <span class="font-monospace fw-bold text-primary"><?= e($cert['certificate_code']) ?></span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= e($cert['student_name']) ?></div>
                                    <small class="text-muted"><?= e($cert['student_email']) ?></small>
                                </td>
                                <td><?= e($cert['course_title']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($cert['issued_at'])) ?></td>
                                <td>
                                    <?php if ($cert['status'] === 'valid'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
                                            <i class="bi bi-check-circle"></i> Hợp lệ
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill">
                                            <i class="bi bi-x-circle"></i> Đã thu hồi
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="/certificates/<?= e($cert['certificate_code']) ?>" target="_blank" class="btn btn-outline-primary" title="Xem / In chứng chỉ">
                                            <i class="bi bi-printer me-1"></i> Xem / In
                                        </a>
                                        <form method="post" action="/admin/lms/certificates/<?= $cert['id'] ?>/toggle" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn đổi trạng thái chứng chỉ này?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn <?= $cert['status'] === 'valid' ? 'btn-outline-danger' : 'btn-outline-success' ?> btn-sm" title="<?= $cert['status'] === 'valid' ? 'Thu hồi chứng chỉ' : 'Khôi phục chứng chỉ' ?>">
                                                <?= $cert['status'] === 'valid' ? 'Thu hồi' : 'Khôi phục' ?>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Manual Issue -->
<div class="modal fade" id="modalManualIssue" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="/admin/lms/certificates/issue">
            <?= csrf_field() ?>
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Cấp Chứng Chỉ Thủ Công</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Chọn Học viên nhận chứng chỉ</label>
                        <select name="user_id" class="form-select" required>
                            <?php foreach ($students as $stu): ?>
                                <option value="<?= $stu['id'] ?>"><?= e($stu['name']) ?> (<?= e($stu['email']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Chọn Khóa học hoàn thành</label>
                        <select name="course_id" class="form-select" required>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= e($c['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <p class="small text-muted mb-0">Hệ thống sẽ tự động sinh mã chứng chỉ chuẩn và kích hoạt trạng thái 100% hoàn thành cho học viên.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Cấp chứng chỉ ngay</button>
                </div>
            </div>
        </form>
    </div>
</div>
