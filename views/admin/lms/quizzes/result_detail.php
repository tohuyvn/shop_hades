<div class="container py-4" style="max-width: 900px;">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="/admin/lms/dashboard">LMS</a></li>
                <li class="breadcrumb-item"><a href="/admin/lms/quiz-results">Kết quả thi</a></li>
                <li class="breadcrumb-item active">Bài nộp #<?= $attempt['id'] ?></li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold mb-0">Chi Tiết Bài Nộp Của Học Viên</h1>
    </div>

    <!-- Student and Score Summary Card -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="small text-muted">Học viên:</div>
                <h5 class="fw-bold text-dark mb-1"><?= e($attempt['student_name']) ?> (<?= e($attempt['student_email']) ?>)</h5>
                <div class="small text-muted">Bài thi: <strong><?= e($attempt['quiz_title']) ?></strong></div>
                <div class="small text-muted">Khóa học: <strong><?= e($attempt['course_title']) ?></strong></div>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="small text-muted">Kết quả chấm điểm:</div>
                <div class="display-6 fw-bold <?= $attempt['is_passed'] ? 'text-success' : 'text-danger' ?>">
                    <?= (float)$attempt['score_achieved'] ?> / <?= (float)$attempt['total_score'] ?> (<?= (float)$attempt['score_percentage'] ?>%)
                </div>
                <span class="badge <?= $attempt['is_passed'] ? 'bg-success' : 'bg-danger' ?> px-3 py-2 rounded-pill fs-6 mt-1">
                    <?= $attempt['is_passed'] ? 'ĐẠT YÊU CẦU' : 'CHƯA ĐẠT' ?>
                </span>
                <div class="small text-muted mt-2">Nộp bài lúc: <?= date('d/m/Y H:i:s', strtotime($attempt['submitted_at'])) ?></div>
            </div>
        </div>
    </div>

    <!-- Questions Breakdown -->
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-list-check text-primary me-2"></i>Chi Tiết Từng Câu Hỏi Học Viên Đã Chọn</h5>
    <?php if (empty($details)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted">
            Không có dữ liệu chi tiết cho bài thi này.
        </div>
    <?php else: ?>
        <div class="d-flex flex-column gap-3 mb-4">
            <?php foreach ($details as $idx => $d): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-4 <?= $d['is_correct'] ? 'border-success' : 'border-danger' ?>">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-dark">Câu <?= $idx + 1 ?></span>
                        <?php if ($d['is_correct']): ?>
                            <span class="badge bg-success"><i class="bi bi-check-lg"></i> Chính xác</span>
                        <?php else: ?>
                            <span class="badge bg-danger"><i class="bi bi-x-lg"></i> Sai</span>
                        <?php endif; ?>
                    </div>
                    <p class="fs-6 text-dark mb-3"><?= nl2br(e($d['question_text'])) ?></p>

                    <div class="p-3 bg-light rounded-3 mb-2 small">
                        <div><strong>Học viên chọn:</strong> <span class="<?= $d['is_correct'] ? 'text-success fw-bold' : 'text-danger fw-bold' ?>"><?= e($d['chosen_answer']) ?></span></div>
                        <?php if (!$d['is_correct'] && !empty($d['correct_answer'])): ?>
                            <div class="mt-1"><strong>Đáp án đúng:</strong> <span class="text-success fw-bold"><?= e($d['correct_answer']) ?></span></div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($d['explanation'])): ?>
                        <div class="small text-muted">
                            <strong>Giải thích:</strong> <?= e($d['explanation']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="text-end">
        <a href="/admin/lms/quiz-results" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách kết quả
        </a>
    </div>
</div>
