<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1">Quản Lý Học Viên</h1>
            <p class="text-muted mb-0">Danh sách tài khoản học viên và tình trạng tham gia học tập</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Học viên</th>
                        <th>Email / Số điện thoại</th>
                        <th class="text-center">Số khóa học tham gia</th>
                        <th class="text-center">Chứng chỉ đạt được</th>
                        <th>Trạng thái</th>
                        <th>Ngày đăng ký</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có học viên nào</td></tr>
                    <?php else: ?>
                        <?php foreach ($students as $stu): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                            <?= mb_substr($stu['name'], 0, 1) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= e($stu['name']) ?></div>
                                            <div class="small text-muted">ID: #<?= $stu['id'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div><?= e($stu['email']) ?></div>
                                    <small class="text-muted"><?= e($stu['phone'] ?: 'Chưa cập nhật') ?></small>
                                </td>
                                <td class="text-center fw-bold text-primary"><?= (int)$stu['enrolled_courses_count'] ?> khóa</td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">
                                        <i class="bi bi-award-fill"></i> <?= (int)$stu['certificates_count'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Hoạt động</span>
                                </td>
                                <td class="text-muted small"><?= date('d/m/Y', strtotime($stu['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
