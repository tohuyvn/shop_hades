<div class="container py-4" style="max-width: 860px;">
    <!-- Result Header Card -->
    <div class="card border-0 shadow-sm rounded-4 p-5 text-center mb-4 <?= $result['is_passed'] ? 'bg-success-subtle' : 'bg-danger-subtle' ?>">
        <div class="mb-3">
            <?php if ($result['is_passed']): ?>
                <i class="bi bi-check-circle-fill display-1 text-success"></i>
            <?php else: ?>
                <i class="bi bi-x-circle-fill display-1 text-danger"></i>
            <?php endif; ?>
        </div>
        <h2 class="fw-bold text-dark mb-1">
            <?= $result['is_passed'] ? 'Chúc mừng! Bạn đã ĐẠT bài kiểm tra!' : 'Rất tiếc! Bạn chưa đạt điểm yêu cầu.' ?>
        </h2>
        <p class="text-secondary mb-4">
            <?= $result['is_passed'] ? 'Tiến độ học tập đã được tự động cập nhật.' : 'Bạn có thể ôn lại kiến thức và thử lại sau.' ?>
        </p>

        <div class="d-flex justify-content-center gap-4 flex-wrap mb-4">
            <div class="bg-white rounded-4 px-4 py-3 shadow-sm">
                <div class="small text-muted">Điểm số đạt được</div>
                <div class="h3 fw-bold text-primary mb-0"><?= $result['earned_points'] ?> / <?= $result['total_points'] ?></div>
            </div>
            <div class="bg-white rounded-4 px-4 py-3 shadow-sm">
                <div class="small text-muted">Tỷ lệ chính xác</div>
                <div class="h3 fw-bold <?= $result['is_passed'] ? 'text-success' : 'text-danger' ?> mb-0"><?= $result['score_percentage'] ?>%</div>
            </div>
            <div class="bg-white rounded-4 px-4 py-3 shadow-sm">
                <div class="small text-muted">Điểm chuẩn qua môn</div>
                <div class="h3 fw-bold text-dark mb-0"><?= $result['passing_score'] ?>%</div>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3">
            <a href="/learn/<?= $quiz['course_id'] ?>" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="bi bi-arrow-left"></i> Quay lại phòng học
            </a>
            <?php if (!$result['is_passed']): ?>
                <a href="/quiz/<?= $quiz['id'] ?>" class="btn btn-outline-danger rounded-pill px-4 fw-bold">
                    <i class="bi bi-arrow-clockwise"></i> Thử làm lại
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Review Section -->
    <h4 class="fw-bold text-dark mb-3"><i class="bi bi-list-check me-2"></i>Xem lại chi tiết bài làm</h4>
    <?php foreach ($result['details'] as $idx => $d): ?>
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-3 border-start border-4 <?= $d['is_correct'] ? 'border-success' : 'border-danger' ?>">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-dark">Câu hỏi <?= $idx + 1 ?>:</span>
                <?php if ($d['is_correct']): ?>
                    <span class="badge bg-success"><i class="bi bi-check"></i> Trả lời đúng</span>
                <?php else: ?>
                    <span class="badge bg-danger"><i class="bi bi-x"></i> Trả lời sai</span>
                <?php endif; ?>
            </div>
            <p class="fs-6 text-dark mb-3"><?= nl2br(e($d['question_text'])) ?></p>

            <?php if (!empty($d['explanation'])): ?>
                <div class="p-3 bg-light rounded-3 small text-secondary">
                    <strong class="text-primary"><i class="bi bi-lightbulb"></i> Giải thích:</strong>
                    <?= e($d['explanation']) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
