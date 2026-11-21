<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="/admin/lms/dashboard">LMS</a></li>
                    <li class="breadcrumb-item active">Kết quả thi</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-0">Quản Lý Kết Quả Thi Của Học Viên</h1>
            <p class="text-muted mb-0">Lịch sử toàn bộ lượt làm bài kiểm tra trắc nghiệm trên hệ thống</p>
        </div>
        <a href="/admin/lms/quizzes" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-list-task me-1"></i> Danh Sách Bài Thi
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Học viên</th>
                        <th>Bài kiểm tra</th>
                        <th>Khóa học</th>
                        <th class="text-center">Điểm số</th>
                        <th class="text-center">Tỷ lệ %</th>
                        <th class="text-center">Trạng thái</th>
                        <th>Thời gian nộp</th>
                        <th class="text-end pe-4">Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($results)): ?>
                        <tr><td colspan="8" class="text-center py-5 text-muted">Chưa có lượt nộp bài nào của học viên.</td></tr>
                    <?php else: ?>
                        <?php foreach ($results as $res): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark"><?= e($res['student_name']) ?></div>
                                    <small class="text-muted"><?= e($res['student_email']) ?></small>
                                </td>
                                <td><?= e($res['quiz_title']) ?></td>
                                <td><span class="badge bg-light text-dark border"><?= e($res['course_title']) ?></span></td>
                                <td class="text-center fw-bold text-primary"><?= (float)$res['score_achieved'] ?> / <?= (float)$res['total_score'] ?></td>
                                <td class="text-center fw-bold"><?= (float)$res['score_percentage'] ?>%</td>
                                <td class="text-center">
                                    <?php if ($res['is_passed']): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
                                            <i class="bi bi-check-circle"></i> Đạt
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill">
                                            <i class="bi bi-x-circle"></i> Chưa đạt
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($res['submitted_at'])) ?></td>
                                <td class="text-end pe-4">
                                    <a href="/admin/lms/quiz-results/<?= $res['id'] ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i> Xem bài nộp
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
