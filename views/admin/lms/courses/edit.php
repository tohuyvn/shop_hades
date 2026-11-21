<div class="container py-4" style="max-width: 900px;">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/lms/dashboard">LMS</a></li>
                <li class="breadcrumb-item"><a href="/admin/lms/courses">Khóa học</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-dark">Chỉnh Sửa Khóa Học</h1>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4">
        <form method="post" action="/admin/lms/courses/<?= $course['id'] ?>/update">
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Tiêu đề khóa học <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= e($course['title']) ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Đường dẫn thân thiện (Slug)</label>
                    <input type="text" name="slug" class="form-control" value="<?= e($course['slug']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Giảng viên phụ trách</label>
                    <select name="instructor_id" class="form-select" required>
                        <?php foreach ($instructors as $ins): ?>
                            <option value="<?= $ins['id'] ?>" <?= ((int)$course['instructor_id'] === (int)$ins['id']) ? 'selected' : '' ?>>
                                <?= e($ins['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Danh mục</label>
                    <select name="category_id" class="form-select">
                        <option value="">Chọn danh mục</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ((int)($course['category_id'] ?? 0) === (int)$cat['id']) ? 'selected' : '' ?>>
                                <?= e($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Giá gốc (VNĐ)</label>
                    <input type="number" name="price" class="form-control" value="<?= (float)$course['price'] ?>" min="0" step="1000">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Giá khuyến mãi (VNĐ)</label>
                    <input type="number" name="sale_price" class="form-control" value="<?= ($course['sale_price'] !== null) ? (float)$course['sale_price'] : '' ?>" placeholder="Để trống nếu không giảm giá" min="0" step="1000">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Cấp độ</label>
                    <select name="level" class="form-select">
                        <option value="all" <?= ($course['level'] === 'all') ? 'selected' : '' ?>>Mọi đối tượng</option>
                        <option value="beginner" <?= ($course['level'] === 'beginner') ? 'selected' : '' ?>>Người mới bắt đầu</option>
                        <option value="intermediate" <?= ($course['level'] === 'intermediate') ? 'selected' : '' ?>>Trung cấp</option>
                        <option value="advanced" <?= ($course['level'] === 'advanced') ? 'selected' : '' ?>>Nâng cao</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Ảnh Thumbnail URL</label>
                    <input type="text" name="thumbnail" class="form-control" value="<?= e($course['thumbnail']) ?>">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Mô tả ngắn (Summary)</label>
                    <textarea name="summary" class="form-control" rows="2"><?= e($course['summary']) ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Nội dung chi tiết</label>
                    <textarea name="description" class="form-control" rows="6"><?= e($course['description']) ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Trạng thái xuất bản</label>
                    <select name="status" class="form-select">
                        <option value="published" <?= ($course['status'] === 'published') ? 'selected' : '' ?>>Công khai (Published)</option>
                        <option value="draft" <?= ($course['status'] === 'draft') ? 'selected' : '' ?>>Bản nháp (Draft)</option>
                    </select>
                </div>

                <div class="col-12 text-end pt-3 border-top mt-4">
                    <a href="/admin/lms/courses" class="btn btn-outline-secondary rounded-pill px-4 me-2">Quay lại</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">Lưu thay đổi</button>
                </div>
            </div>
        </form>
    </div>
</div>
