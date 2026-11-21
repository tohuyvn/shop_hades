<div class="container-fluid py-4" style="max-width: 1000px;">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="/admin/lms/dashboard">LMS</a></li>
                    <li class="breadcrumb-item"><a href="/admin/lms/quizzes">Bài kiểm tra</a></li>
                    <li class="breadcrumb-item active"><?= e($quiz['title']) ?></li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-0">Ngân Hàng Câu Hỏi: <?= e($quiz['title']) ?></h1>
            <p class="text-muted small mb-0">Khóa học: <strong><?= e($quiz['course_title']) ?></strong> • Điểm đỗ: <strong><?= (float)$quiz['passing_score'] ?>%</strong></p>
        </div>
        <button class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalAddQuestion">
            <i class="bi bi-plus-circle me-1"></i> Thêm Câu Hỏi Mới
        </button>
    </div>

    <!-- Questions List -->
    <?php if (empty($questions)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <i class="bi bi-question-circle display-3 text-muted mb-3"></i>
            <h5 class="fw-bold text-dark">Chưa có câu hỏi nào trong bài kiểm tra này</h5>
            <p class="text-muted small">Hãy thêm các câu hỏi trắc nghiệm và phương án trả lời để học viên làm bài.</p>
            <div>
                <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalAddQuestion">
                    <i class="bi bi-plus-lg me-1"></i> Thêm Câu Hỏi Đầu Tiên
                </button>
            </div>
        </div>
    <?php else: ?>
        <div class="d-flex flex-column gap-3">
            <?php foreach ($questions as $idx => $q): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill">
                                Câu <?= $idx + 1 ?>
                            </span>
                            <span class="badge bg-light text-dark border"><?= (float)$q['points'] ?> điểm</span>
                        </div>
                        <form method="post" action="/admin/lms/quizzes/<?= $quiz['id'] ?>/questions/<?= $q['id'] ?>/delete" onsubmit="return confirm('Bạn có chắc muốn xóa câu hỏi này?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill" title="Xóa câu hỏi">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </form>
                    </div>

                    <h5 class="fw-bold text-dark mb-3"><?= nl2br(e($q['question_text'])) ?></h5>

                    <!-- Answer Options -->
                    <div class="row row-cols-1 row-cols-md-2 g-2 mb-3">
                        <?php foreach ($q['answers'] as $ans): ?>
                            <div class="col">
                                <div class="p-3 rounded-3 border d-flex align-items-center justify-content-between <?= !empty($ans['is_correct']) ? 'bg-success-subtle border-success text-success fw-bold' : 'bg-light text-secondary' ?>">
                                    <span><?= e($ans['answer_text']) ?></span>
                                    <?php if (!empty($ans['is_correct'])): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-lg"></i> Đáp án đúng</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (!empty($q['explanation'])): ?>
                        <div class="p-3 bg-light rounded-3 small text-muted">
                            <strong class="text-primary"><i class="bi bi-lightbulb"></i> Giải thích đáp án:</strong>
                            <?= e($q['explanation']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Add Question -->
<div class="modal fade" id="modalAddQuestion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="post" action="/admin/lms/quizzes/<?= $quiz['id'] ?>/questions">
            <?= csrf_field() ?>
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Thêm Câu Hỏi Trắc Nghiệm Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nội dung câu hỏi <span class="text-danger">*</span></label>
                        <textarea name="question_text" class="form-control" rows="3" placeholder="Nhập câu hỏi trắc nghiệm..." required></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Điểm số cho câu này</label>
                            <input type="number" name="points" class="form-control" value="1.0" min="0.1" step="0.5" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Chọn đáp án ĐÚNG</label>
                            <select name="correct_index" class="form-select" required>
                                <option value="0">Phương án A</option>
                                <option value="1">Phương án B</option>
                                <option value="2">Phương án C</option>
                                <option value="3">Phương án D</option>
                            </select>
                        </div>
                    </div>

                    <label class="form-label fw-bold">4 Phương án trả lời <span class="text-danger">*</span></label>
                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="input-group">
                            <span class="input-group-text fw-bold">A</span>
                            <input type="text" name="answers[]" class="form-control" placeholder="Nội dung phương án A" required>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text fw-bold">B</span>
                            <input type="text" name="answers[]" class="form-control" placeholder="Nội dung phương án B" required>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text fw-bold">C</span>
                            <input type="text" name="answers[]" class="form-control" placeholder="Nội dung phương án C" required>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text fw-bold">D</span>
                            <input type="text" name="answers[]" class="form-control" placeholder="Nội dung phương án D" required>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold">Giải thích chi tiết (Hiển thị sau khi học viên nộp bài)</label>
                        <textarea name="explanation" class="form-control" rows="2" placeholder="Lý do vì sao đáp án đó là đúng..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Lưu câu hỏi</button>
                </div>
            </div>
        </form>
    </div>
</div>
