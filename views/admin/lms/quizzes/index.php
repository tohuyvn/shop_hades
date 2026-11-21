<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1">Quản Lý Bài Kiểm Tra & Câu Hỏi</h1>
            <p class="text-muted mb-0">Quản lý ngân hàng đề thi, bài trắc nghiệm và thang điểm các khóa học</p>
        </div>
        <a href="/admin/lms/quiz-results" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="bi bi-file-earmark-check me-1"></i> Xem Kết Quả Thi Của Học Viên
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Bài kiểm tra</th>
                        <th>Thuộc khóa học</th>
                        <th>Thời gian làm bài</th>
                        <th>Điểm chuẩn qua môn</th>
                        <th class="text-center">Số câu hỏi</th>
                        <th class="text-center">Lượt học viên thi</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($quizzes)): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">Chưa có bài kiểm tra nào trong hệ thống.</td></tr>
                    <?php else: ?>
                        <?php foreach ($quizzes as $quiz): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark"><?= e($quiz['title']) ?></div>
                                    <small class="text-muted"><?= e($quiz['lesson_title'] ?? 'Bài kiểm tra cuối khóa') ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= e($quiz['course_title']) ?></span>
                                </td>
                                <td>
                                    <i class="bi bi-clock me-1 text-muted"></i> <?= (int)$quiz['duration_minutes'] ?> phút
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark"><?= (float)$quiz['passing_score'] ?>%</span>
                                </td>
                                <td class="text-center fw-bold text-primary"><?= (int)$quiz['question_count'] ?> câu</td>
                                <td class="text-center fw-bold text-secondary"><?= (int)$quiz['attempt_count'] ?> lượt</td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="/admin/lms/quizzes/<?= $quiz['id'] ?>/questions" class="btn btn-outline-primary" title="Ngân hàng câu hỏi & đáp án">
                                            <i class="bi bi-pencil-square me-1"></i> Soạn câu hỏi
                                        </a>
                                        <a href="/quiz/<?= $quiz['id'] ?>" target="_blank" class="btn btn-outline-secondary" title="Thi thử">
                                            <i class="bi bi-play-circle"></i> Thi thử
                                        </a>
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
