<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Khóa học của tôi</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-dark mb-0">Khóa Học Đã Đăng Ký</h1>
            <p class="text-muted mb-0">Tiếp tục hành trình học tập và hoàn thành các mục tiêu của bạn</p>
        </div>
        <a href="/courses" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="bi bi-search me-1"></i> Khám phá thêm khóa học
        </a>
    </div>

    <?php if (empty($enrolledCourses)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4">
            <i class="bi bi-mortarboard display-3 text-muted mb-3"></i>
            <h4 class="fw-bold text-dark">Bạn chưa đăng ký khóa học nào</h4>
            <p class="text-muted">Khám phá ngay kho khóa học chất lượng cao để bắt đầu nâng cao kỹ năng.</p>
            <div>
                <a href="/courses" class="btn btn-primary rounded-pill px-5 fw-bold py-2">Xem danh sách khóa học</a>
            </div>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($enrolledCourses as $c): ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden d-flex flex-column">
                        <img src="<?= e($c['thumbnail'] ?: '/image/banner1.png') ?>" class="card-img-top object-fit-cover" style="height: 170px;" alt="<?= e($c['title']) ?>">
                        <div class="card-body p-4 d-flex flex-column flex-grow-1">
                            <h5 class="fw-bold text-dark mb-2 line-clamp-2"><?= e($c['title']) ?></h5>
                            <div class="small text-muted mb-3">
                                <i class="bi bi-person me-1"></i> <?= e($c['instructor_name'] ?? 'Giảng viên') ?>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between small fw-semibold mb-1">
                                    <span class="text-muted"><i class="bi bi-check2-circle text-success me-1"></i>Đã học <?= (int)($c['completed_lessons_count'] ?? 0) ?>/<?= (int)$c['total_lessons'] ?> bài:</span>
                                    <span class="text-primary fw-bold"><?= (float)$c['progress_percent'] ?>%</span>
                                </div>
                                <div class="progress rounded-pill mb-3" style="height: 8px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?= (float)$c['progress_percent'] ?>%;"></div>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="/learn/<?= $c['course_id'] ?>" class="btn btn-primary rounded-pill fw-bold">
                                        <i class="bi bi-play-circle-fill me-1"></i> Tiếp tục học
                                    </a>
                                    <?php if (!empty($c['certificate_code'])): ?>
                                        <a href="/certificates/<?= e($c['certificate_code']) ?>" class="btn btn-outline-warning text-dark btn-sm rounded-pill fw-semibold">
                                            <i class="bi bi-award-fill text-warning me-1"></i> Xem Chứng Chỉ
                                        </a>
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
