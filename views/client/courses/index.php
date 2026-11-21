<div class="container py-4">
    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Khóa học</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-0">Khám Phá Khóa Học Trực Tuyến</h1>
            <p class="text-muted mb-0">Học tập thực chiến cùng các chuyên gia hàng đầu</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/verify-certificate" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                <i class="bi bi-patch-check"></i> Tra cứu chứng chỉ
            </a>
            <?php if (authUser()): ?>
                <a href="/my-courses" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="bi bi-journal-bookmark-fill"></i> Khóa học của tôi
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-3 p-md-4">
            <form method="get" action="/courses" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="q" class="form-control border-start-0 ps-0" placeholder="Tìm tên khóa học, kỹ năng..." value="<?= e($filters['q'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Tất cả danh mục</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ((string)($filters['category_id'] ?? '') === (string)$cat['id']) ? 'selected' : '' ?>>
                                <?= e($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="level" class="form-select">
                        <option value="">Tất cả trình độ</option>
                        <option value="beginner" <?= (($filters['level'] ?? '') === 'beginner') ? 'selected' : '' ?>>Cơ bản</option>
                        <option value="intermediate" <?= (($filters['level'] ?? '') === 'intermediate') ? 'selected' : '' ?>>Trung cấp</option>
                        <option value="advanced" <?= (($filters['level'] ?? '') === 'advanced') ? 'selected' : '' ?>>Nâng cao</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="price" class="form-select">
                        <option value="">Tất cả mức giá</option>
                        <option value="free" <?= (($filters['price_type'] ?? '') === 'free') ? 'selected' : '' ?>>Miễn phí</option>
                        <option value="paid" <?= (($filters['price_type'] ?? '') === 'paid') ? 'selected' : '' ?>>Có phí</option>
                    </select>
                </div>
                <div class="col-md-1 d-grid">
                    <button type="submit" class="btn btn-primary rounded-3">Lọc</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Course Grid -->
    <?php if (empty($courses)): ?>
        <div class="text-center py-5">
            <i class="bi bi-journal-x display-1 text-muted"></i>
            <h4 class="mt-3 text-muted">Không tìm thấy khóa học nào phù hợp</h4>
            <a href="/courses" class="btn btn-outline-primary btn-sm mt-2">Đặt lại bộ lọc</a>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($courses as $c): ?>
                <?php
                    $isFree = ($c['price'] <= 0 || ($c['sale_price'] !== null && $c['sale_price'] <= 0));
                    $displayPrice = ($c['sale_price'] !== null && $c['sale_price'] >= 0) ? $c['sale_price'] : $c['price'];
                ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden d-flex flex-column transition-hover">
                        <div class="position-relative">
                            <img src="<?= e($c['thumbnail'] ?: '/image/banner1.png') ?>" class="card-img-top object-fit-cover" style="height: 190px;" alt="<?= e($c['title']) ?>">
                            <span class="badge position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill bg-dark bg-opacity-75 text-white">
                                <?= e($c['category_name'] ?? 'Công nghệ') ?>
                            </span>
                            <?php if ($isFree): ?>
                                <span class="badge position-absolute bottom-0 end-0 m-3 px-3 py-2 rounded-pill bg-success">
                                    Miễn phí
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex align-items-center gap-2 mb-2 text-muted small">
                                <span><i class="bi bi-person"></i> <?= e($c['instructor_name'] ?? 'Giảng viên') ?></span>
                                <span>•</span>
                                <span><i class="bi bi-play-circle"></i> <?= (int)$c['total_lessons'] ?> bài</span>
                                <span>•</span>
                                <span><i class="bi bi-clock"></i> <?= (int)$c['total_duration_minutes'] ?> phút</span>
                            </div>
                            <h5 class="card-title fw-bold text-dark mb-2 line-clamp-2">
                                <a href="/courses/<?= e($c['slug'] ?: $c['id']) ?>" class="text-decoration-none text-dark stretched-link">
                                    <?= e($c['title']) ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small flex-grow-1 line-clamp-2">
                                <?= e($c['summary'] ?: 'Lộ trình bài bản giúp bạn làm chủ kỹ năng từ cơ bản đến nâng cao.') ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                                <div>
                                    <?php if ($isFree): ?>
                                        <span class="h5 fw-bold text-success mb-0">Miễn phí</span>
                                    <?php else: ?>
                                        <span class="h5 fw-bold text-primary mb-0"><?= formatMoney($displayPrice) ?></span>
                                        <?php if ($c['sale_price'] !== null && $c['sale_price'] < $c['price']): ?>
                                            <span class="text-decoration-line-through text-muted small ms-1"><?= formatMoney($c['price']) ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <span class="badge bg-light text-secondary border rounded-pill px-3 py-2">
                                    <i class="bi bi-people"></i> <?= (int)($c['student_count'] ?? 0) ?> học viên
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.transition-hover {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.transition-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08) !important;
}
</style>
