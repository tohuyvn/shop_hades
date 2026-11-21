<div class="container py-4" style="max-width: 900px;">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/lms/dashboard">LMS</a></li>
                <li class="breadcrumb-item"><a href="/admin/lms/courses">Khóa học</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-dark">Thêm Khóa Học Mới</h1>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4">
        <form method="post" action="/admin/lms/courses/store">
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Tiêu đề khóa học <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="VD: Lập trình PHP & MySQL Chuyên Sâu" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Đường dẫn thân thiện (Slug)</label>
                    <input type="text" name="slug" class="form-control" placeholder="Tự động tạo nếu để trống">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Giảng viên phụ trách</label>
                    <select name="instructor_id" class="form-select" required>
                        <?php foreach ($instructors as $ins): ?>
                            <option value="<?= $ins['id'] ?>"><?= e($ins['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Danh mục</label>
                    <select name="category_id" class="form-select">
                        <option value="">Chọn danh mục</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Giá gốc (VNĐ)</label>
                    <input type="number" name="price" class="form-control" value="0" min="0" step="1000">
                    <small class="text-muted">Nhập 0 nếu là khóa học miễn phí</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Giá khuyến mãi (VNĐ)</label>
                    <input type="number" name="sale_price" class="form-control" placeholder="Để trống nếu không giảm giá" min="0" step="1000">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Cấp độ</label>
                    <select name="level" class="form-select">
                        <option value="all">Mọi đối tượng</option>
                        <option value="beginner">Người mới bắt đầu</option>
                        <option value="intermediate">Trung cấp</option>
                        <option value="advanced">Nâng cao</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Ảnh Thumbnail URL</label>
                    <input type="text" name="thumbnail" class="form-control" value="/image/banner1.png">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Mô tả ngắn (Summary)</label>
                    <textarea name="summary" class="form-control" rows="2" placeholder="Tóm tắt nội dung chính hiển thị trên card khóa học"></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Nội dung chi tiết (HTML / Markdown)</label>
                    <textarea name="description" class="form-control" rows="6" placeholder="Mục tiêu khóa học, kiến thức thu được..."></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Trạng thái xuất bản</label>
                    <select name="status" class="form-select">
                        <option value="published">Công khai ngay (Published)</option>
                        <option value="draft">Bản nháp (Draft)</option>
                    </select>
                </div>

                <div class="col-12 text-end pt-3 border-top mt-4">
                    <a href="/admin/lms/courses" class="btn btn-outline-secondary rounded-pill px-4 me-2">Hủy bỏ</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">Tạo khóa học & Soạn giáo trình</button>
                </div>
            </div>
        </form>
    </div>
</div>
