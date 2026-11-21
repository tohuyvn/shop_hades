<div class="container-fluid py-4" style="max-width: 1000px;">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="/admin/lms/dashboard">LMS</a></li>
                    <li class="breadcrumb-item"><a href="/admin/lms/courses">Khóa học</a></li>
                    <li class="breadcrumb-item active"><?= e($course['title']) ?></li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-0">Quản Lý Giáo Trình & Bài Học</h1>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalAddSection">
                <i class="bi bi-folder-plus me-1"></i> Thêm Chương Mới
            </button>
            <a href="/courses/<?= e($course['slug'] ?: $course['id']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-box-arrow-up-right me-1"></i> Xem Trên Web
            </a>
        </div>
    </div>

    <!-- Curriculum Tree -->
    <?php if (empty($curriculum)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <i class="bi bi-diagram-3 display-3 text-muted mb-3"></i>
            <h5 class="fw-bold text-dark">Chưa có chương học nào</h5>
            <p class="text-muted small">Bắt đầu bằng việc thêm một chương (Section) mới cho khóa học.</p>
            <div>
                <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalAddSection">
                    <i class="bi bi-plus-lg me-1"></i> Thêm Chương Học
                </button>
            </div>
        </div>
    <?php else: ?>
        <div class="d-flex flex-column gap-4">
            <?php foreach ($curriculum as $sIdx => $section): ?>
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-pill"><?= $sIdx + 1 ?></span>
                            <h5 class="fw-bold text-dark mb-0"><?= e($section['title']) ?></h5>
                        </div>
                        <button class="btn btn-outline-primary btn-sm rounded-pill" onclick="openAddLessonModal(<?= $section['id'] ?>, '<?= addslashes(e($section['title'])) ?>')">
                            <i class="bi bi-plus-circle me-1"></i> Thêm bài học
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <?php if (empty($section['lessons'])): ?>
                            <div class="p-4 text-center text-muted small">Chưa có bài học trong chương này.</div>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($section['lessons'] as $les): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <?php if ($les['lesson_type'] === 'video'): ?>
                                                <i class="bi bi-play-circle-fill text-primary fs-4"></i>
                                            <?php elseif ($les['lesson_type'] === 'quiz'): ?>
                                                <i class="bi bi-patch-question-fill text-warning fs-4"></i>
                                            <?php else: ?>
                                                <i class="bi bi-file-text-fill text-secondary fs-4"></i>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-bold text-dark"><?= e($les['title']) ?></div>
                                                <div class="small text-muted">
                                                    Loại: <span class="badge bg-light text-dark border"><?= e($les['lesson_type']) ?></span>
                                                    <?php if (!empty($les['is_preview'])): ?>
                                                        <span class="badge bg-success-subtle text-success">Xem thử</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-muted small">
                                            <?= $les['duration_seconds'] ? gmdate("i:s", $les['duration_seconds']) : '' ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Add Section -->
<div class="modal fade" id="modalAddSection" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="/admin/lms/courses/<?= $course['id'] ?>/sections">
            <?= csrf_field() ?>
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Thêm Chương Học Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-4">
                    <label class="form-label fw-bold">Tên chương / Module</label>
                    <input type="text" name="title" class="form-control" placeholder="VD: Chương 2: Cấu trúc điều khiển và Vòng lặp" required>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Lưu chương</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Add Lesson -->
<div class="modal fade" id="modalAddLesson" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="/admin/lms/courses/<?= $course['id'] ?>/lessons">
            <?= csrf_field() ?>
            <input type="hidden" name="section_id" id="inputSectionId" value="">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Thêm Bài Học Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Thuộc chương:</label>
                        <div id="displaySectionName" class="text-primary fw-semibold"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu đề bài học <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="VD: Bài 1: Cài đặt Composer và PHP" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Loại bài học</label>
                        <select name="lesson_type" class="form-select" id="selectLessonType">
                            <option value="video">Video bài giảng</option>
                            <option value="document">Bài đọc tài liệu</option>
                            <option value="quiz">Bài thi trắc nghiệm (Quiz)</option>
                        </select>
                    </div>
                    <div class="mb-3" id="groupVideoUrl">
                        <label class="form-label fw-bold">Link Video (Youtube / Vimeo / Direct URL)</label>
                        <input type="text" name="video_url" class="form-control" placeholder="https://www.youtube.com/embed/...">
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_preview" value="1" id="checkIsPreview">
                        <label class="form-check-label fw-semibold" for="checkIsPreview">Cho phép học viên xem thử miễn phí</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Lưu bài học</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openAddLessonModal(sectionId, sectionName) {
    document.getElementById('inputSectionId').value = sectionId;
    document.getElementById('displaySectionName').textContent = sectionName;
    const modal = new bootstrap.Modal(document.getElementById('modalAddLesson'));
    modal.show();
}
</script>
