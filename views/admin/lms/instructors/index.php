<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1">Quản Lý Giảng Viên</h1>
            <p class="text-muted mb-0">Danh sách đội ngũ giảng viên và chuyên gia giảng dạy trên nền tảng</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Giảng viên</th>
                        <th>Chức danh / Chuyên môn</th>
                        <th>Kinh nghiệm</th>
                        <th class="text-center">Số khóa học phụ trách</th>
                        <th>Email liên hệ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($instructors)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">Chưa có giảng viên nào</td></tr>
                    <?php else: ?>
                        <?php foreach ($instructors as $ins): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                                            <i class="bi bi-person-video3"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= e($ins['name']) ?></div>
                                            <span class="badge bg-primary-subtle text-primary">Instructor</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-secondary"><?= e($ins['headline'] ?? 'Chuyên gia đào tạo') ?></td>
                                <td><span class="badge bg-light text-dark border"><?= (int)($ins['experience_years'] ?? 0) ?> năm</span></td>
                                <td class="text-center fw-bold text-primary"><?= (int)$ins['total_courses'] ?> khóa</td>
                                <td><?= e($ins['email']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
