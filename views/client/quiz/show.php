<div class="container py-4" style="max-width: 860px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/courses/<?= $quiz['course_id'] ?>">Khóa học</a></li>
            <li class="breadcrumb-item"><a href="/learn/<?= $quiz['course_id'] ?>">Phòng học</a></li>
            <li class="breadcrumb-item active"><?= e($quiz['title']) ?></li>
        </ol>
    </nav>

    <!-- Quiz Header with Timer -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="bi bi-clock-history"></i> Thời gian: <?= (int)$quiz['duration_minutes'] ?> phút
                </span>
                <h2 class="h3 fw-bold text-dark mb-1"><?= e($quiz['title']) ?></h2>
                <p class="text-muted small mb-0">Điểm tối thiểu cần đạt: <strong><?= (float)$quiz['passing_score'] ?>%</strong> • Tổng số câu: <strong><?= count($questions) ?> câu</strong></p>
            </div>
            <!-- Sticky Countdown Timer Box -->
            <div class="bg-dark text-white rounded-4 px-4 py-3 text-center shadow-sm">
                <div class="small text-secondary text-uppercase fw-semibold">Thời gian còn lại</div>
                <div id="countdownTimer" class="display-6 fw-bold text-warning font-monospace">10:00</div>
            </div>
        </div>
    </div>

    <!-- Quiz Form -->
    <form id="quizForm" method="post" action="/quiz/<?= $quiz['id'] ?>/submit">
        <?= csrf_field() ?>

        <?php foreach ($questions as $idx => $q): ?>
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill">
                        Câu <?= $idx + 1 ?> / <?= count($questions) ?>
                    </span>
                    <span class="text-muted small"><?= (float)$q['points'] ?> điểm</span>
                </div>
                <h5 class="fw-bold text-dark mb-4"><?= nl2br(e($q['question_text'])) ?></h5>

                <div class="d-flex flex-column gap-3">
                    <?php foreach ($q['answers'] as $ans): ?>
                        <label class="form-check p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer transition-hover">
                            <input class="form-check-input ms-0 mt-0" type="radio" name="answers[<?= $q['id'] ?>]" value="<?= $ans['id'] ?>" required>
                            <span class="form-check-label text-dark fw-medium"><?= e($ans['answer_text']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="d-grid gap-2 mb-5">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold py-3">
                <i class="bi bi-send-check-fill me-2"></i> Nộp bài kiểm tra
            </button>
        </div>
    </form>
</div>

<script>
// Timer Countdown in JavaScript
let timeLeft = <?= (int)$quiz['duration_minutes'] ?> * 60;
const timerEl = document.getElementById('countdownTimer');
const quizForm = document.getElementById('quizForm');
const quizStorageKey = 'lms_quiz_draft_' + <?= (int)$quiz['id'] ?>;

// Restore saved answers from localStorage if available
try {
    const savedDraft = JSON.parse(localStorage.getItem(quizStorageKey) || '{}');
    for (const [qId, aId] of Object.entries(savedDraft)) {
        const input = quizForm.querySelector(`input[name="answers[${qId}]"][value="${aId}"]`);
        if (input) input.checked = true;
    }
} catch(e) {}

// Auto-save on radio change
quizForm.addEventListener('change', function(e) {
    if (e.target && e.target.name && e.target.name.startsWith('answers[')) {
        const match = e.target.name.match(/answers\[(\d+)\]/);
        if (match) {
            const qId = match[1];
            try {
                const draft = JSON.parse(localStorage.getItem(quizStorageKey) || '{}');
                draft[qId] = e.target.value;
                localStorage.setItem(quizStorageKey, JSON.stringify(draft));
            } catch(e) {}
        }
    }
});

// Clear draft on submit
quizForm.addEventListener('submit', function() {
    localStorage.removeItem(quizStorageKey);
});

const timerInterval = setInterval(function () {
    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        alert('Hết thời gian làm bài! Hệ thống sẽ tự động nộp bài của bạn.');
        quizForm.submit();
        return;
    }
    timeLeft--;
    const m = Math.floor(timeLeft / 60);
    const s = timeLeft % 60;
    timerEl.textContent = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
}, 1000);
</script>
