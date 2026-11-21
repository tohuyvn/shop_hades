<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1">Quản Lý Khóa Học</h1>
            <p class="text-muted mb-0">Danh sách toàn bộ khóa học trực tuyến trên hệ thống</p>
        </div>
        <a href="/admin/lms/courses/create" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Thêm Khóa Học Mới
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Khóa học</th>
                        <th>Giảng viên</th>
                        <th>Giá bán</th>
                        <th>Cấp độ</th>
                        <th>Trạng thái</th>
                        <th>Số bài học</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($courses)): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">Chưa có khóa học nào được tạo.</td></tr>
                    <?php else: ?>
                        <?php foreach ($courses as $c): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= e($c['thumbnail'] ?: '/image/banner1.png') ?>" class="rounded-3 object-fit-cover" style="width: 55px; height: 40px;" alt="">
                                        <div>
                                            <div class="fw-bold text-dark"><?= e($c['title']) ?></div>
                                            <div class="small text-muted"><?= e($c['category_name'] ?? 'Chưa phân loại') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?= e($c['instructor_name'] ?? 'N/A') ?></td>
                                <td>
                                    <?php if ($c['price'] <= 0): ?>
                                        <span class="badge bg-success">Miễn phí</span>
                                    <?php else: ?>
                                        <span class="fw-semibold text-primary"><?= formatMoney($c['sale_price'] ?? $c['price']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge bg-light text-dark border text-uppercase"><?= e($c['level']) ?></span></td>
                                <td>
                                    <?php if ($c['status'] === 'published'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Công khai</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary">Bản nháp</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= (int)$c['total_lessons'] ?> bài</td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="/admin/lms/courses/<?= $c['id'] ?>/curriculum" class="btn btn-outline-primary" title="Quản lý giáo trình">
                                            <i class="bi bi-diagram-3"></i> Giáo trình
                                        </a>
                                        <a href="/admin/lms/courses/<?= $c['id'] ?>/edit" class="btn btn-outline-secondary" title="Chỉnh sửa thông tin">
                                            <i class="bi bi-pencil"></i> Sửa
                                        </a>
                                        <a href="/courses/<?= e($c['slug'] ?: $c['id']) ?>" target="_blank" class="btn btn-outline-info" title="Xem trên web">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form method="post" action="/admin/lms/courses/<?= $c['id'] ?>/delete" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa khóa học này?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Xóa">
                                                <i class="bi bi-trash"></i>
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
