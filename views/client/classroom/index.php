<div class="d-flex flex-column vh-100 bg-white">
    <!-- Top Classroom Navbar -->
    <header class="navbar navbar-dark bg-dark px-3 py-2 border-bottom border-secondary d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="/courses/<?= $course['id'] ?>" class="btn btn-outline-light btn-sm rounded-pill" title="Trở về trang khóa học">
                <i class="bi bi-arrow-left"></i> <span class="d-none d-sm-inline">Rời khỏi phòng học</span>
            </a>
            <div class="vr bg-secondary d-none d-md-block"></div>
            <h6 class="text-white mb-0 text-truncate fw-semibold" style="max-width: 450px;">
                <?= e($course['title']) ?>
            </h6>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2 text-white small">
                <span class="d-none d-md-inline text-secondary">Tiến độ:</span>
                <div class="progress" style="width: 120px; height: 8px;">
                    <div id="courseProgressBar" class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= (float)($enrollment['progress_percent'] ?? 0) ?>%;"></div>
                </div>
                <strong id="courseProgressText" class="text-success"><?= (float)($enrollment['progress_percent'] ?? 0) ?>%</strong>
            </div>

            <!-- Certificate Button (Visible if 100%) -->
            <a id="btnCertificate" href="/verify-certificate?code=CERT" class="btn btn-warning btn-sm fw-bold rounded-pill <?= ((float)($enrollment['progress_percent'] ?? 0) >= 100) ? '' : 'd-none' ?>">
                <i class="bi bi-award-fill"></i> Nhận chứng chỉ
            </a>
        </div>
    </header>

    <!-- Main Workspace (2 Columns) -->
    <div class="d-flex flex-grow-1 overflow-hidden">
        <!-- Left Column: Video Player & Content -->
        <main class="flex-grow-1 d-flex flex-column overflow-y-auto bg-light">
            <!-- Media / Video Container -->
            <div class="bg-black d-flex justify-content-center align-items-center position-relative" style="min-height: 480px;">
                <?php if ($currentLesson['lesson_type'] === 'video' && !empty($currentLesson['video_url'])): ?>
                    <?php
                        $startSec = (int) ($currentLesson['last_watch_second'] ?? 0);
                        $videoSrc = formatVideoEmbedUrl($currentLesson['video_url'], $startSec);
                    ?>
                    <div class="ratio ratio-16x9 w-100" style="max-height: 600px;">
                        <iframe id="videoIframe" src="<?= e($videoSrc) ?>" title="<?= e($currentLesson['title']) ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                <?php elseif ($currentLesson['lesson_type'] === 'quiz'): ?>
                    <div class="text-center text-white p-5">
                        <i class="bi bi-patch-question display-1 text-warning mb-3"></i>
                        <h3 class="fw-bold mb-2"><?= e($currentLesson['title']) ?></h3>
                        <p class="text-secondary max-w-lg mx-auto">Bài kiểm tra đánh giá kiến thức bài học. Đạt điểm quy định để vượt qua bài.</p>
                        <a href="/quiz/<?= (int)($currentLesson['quiz_id'] ?? 1) ?>" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold mt-3">
                            <i class="bi bi-pencil-square"></i> Bắt đầu làm bài thi
                        </a>
                    </div>
                <?php else: ?>
                    <div class="container py-5 text-dark bg-white m-4 rounded-4 shadow-sm" style="max-width: 800px;">
                        <h2 class="fw-bold mb-4"><?= e($currentLesson['title']) ?></h2>
                        <div class="reading-content fs-5 line-height-lg text-secondary">
                            <?= $currentLesson['content'] ?: '<p>Vui lòng đọc kỹ nội dung tài liệu và mã nguồn đính kèm để nắm vững kiến thức.</p>' ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Lesson Control & Details Toolbar -->
            <div class="container-fluid px-4 py-3 bg-white border-bottom shadow-sm">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark"><?= e($currentLesson['title']) ?></h4>
                        <div class="small text-muted d-flex align-items-center gap-2 flex-wrap">
                            <span><i class="bi bi-clock me-1"></i> Thời lượng: <?= $currentLesson['duration_seconds'] ? gmdate("i:s", $currentLesson['duration_seconds']) : 'N/A' ?></span>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0" id="classroomWatchTimeBadge">
                                <i class="bi bi-play-circle-fill me-1"></i> Đã xem: <span id="classroomWatchSecText"><?= gmdate("i:s", (int)($currentLesson['last_watch_second'] ?? 0)) ?></span> / <?= $currentLesson['duration_seconds'] ? gmdate("i:s", $currentLesson['duration_seconds']) : 'N/A' ?>
                            </span>
                            <span class="badge bg-light text-secondary border rounded-pill px-2 py-0">
                                <i class="bi bi-person-check text-success me-1"></i> Tài khoản: <strong><?= e($user['name'] ?? 'Học viên') ?></strong>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <?php if ($prevLesson): ?>
                            <a href="/learn/<?= $course['id'] ?>/lesson/<?= $prevLesson['id'] ?>" class="btn btn-outline-secondary rounded-pill px-3 btn-sm" title="Bài học trước">
                                <i class="bi bi-chevron-left me-1"></i> Bài trước
                            </a>
                        <?php endif; ?>

                        <button id="btnMarkCompleted" type="button" class="btn <?= !empty($currentLesson['is_completed']) ? 'btn-success' : 'btn-outline-success' ?> rounded-pill px-4 fw-semibold btn-sm" onclick="markLessonComplete()">
                            <i class="bi <?= !empty($currentLesson['is_completed']) ? 'bi-check2-circle' : 'bi-circle' ?>"></i>
                            <span id="btnCompletedText"><?= !empty($currentLesson['is_completed']) ? 'Đã hoàn thành' : 'Đánh dấu hoàn thành' ?></span>
                        </button>

                        <?php if ($nextLesson): ?>
                            <a href="/learn/<?= $course['id'] ?>/lesson/<?= $nextLesson['id'] ?>" class="btn btn-primary rounded-pill px-3 btn-sm fw-semibold" title="Bài học tiếp theo">
                                Bài tiếp theo <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Tabbed Content: Materials, Q&A Discussion, Notes -->
            <div class="container-fluid px-4 py-4 flex-grow-1">
                <ul class="nav nav-pills mb-3 gap-2" id="classroomTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill fw-semibold px-4" id="materials-tab" data-bs-toggle="pill" data-bs-target="#tabMaterials" type="button">
                            <i class="bi bi-paperclip me-1"></i> Tài liệu đính kèm (<?= count($currentLesson['materials'] ?? []) ?>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill fw-semibold px-4" id="discuss-tab" data-bs-toggle="pill" data-bs-target="#tabDiscussion" type="button">
                            <i class="bi bi-chat-dots me-1"></i> Hỏi đáp & Thảo luận (<?= count($discussions ?? []) ?>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill fw-semibold px-4" id="notes-tab" data-bs-toggle="pill" data-bs-target="#tabNotes" type="button">
                            <i class="bi bi-journal-text me-1"></i> Ghi chú của tôi
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="classroomTabsContent">
                    <!-- Tab 1: Materials -->
                    <div class="tab-pane fade show active" id="tabMaterials">
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <h6 class="fw-bold mb-3">Tài liệu học tập & Mã nguồn</h6>
                            <?php if (empty($currentLesson['materials'])): ?>
                                <p class="text-muted small mb-0">Bài học này không có file đính kèm.</p>
                            <?php else: ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($currentLesson['materials'] as $mat): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <i class="bi bi-file-earmark-arrow-down text-primary fs-3"></i>
                                                <div>
                                                    <div class="fw-semibold text-dark"><?= e($mat['title']) ?></div>
                                                    <div class="small text-muted text-uppercase"><?= e($mat['file_type'] ?? 'file') ?> • <?= round(($mat['file_size_bytes'] ?? 0) / 1024, 1) ?> KB</div>
                                                </div>
                                            </div>
                                            <a href="/learn/<?= $course['id'] ?>/download/<?= $mat['id'] ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                <i class="bi bi-download"></i> Tải về máy
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Tab 2: Discussion Q&A -->
                    <div class="tab-pane fade" id="tabDiscussion">
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <h6 class="fw-bold mb-3">Thảo luận cùng Giảng viên & Học viên khác</h6>

                            <!-- Post Discussion Form -->
                            <form method="post" action="/learn/<?= $course['id'] ?>/lesson/<?= $currentLesson['id'] ?>/discussion" class="mb-4">
                                <?= csrf_field() ?>
                                <div class="mb-2">
                                    <textarea name="content" class="form-control" rows="2" placeholder="Đặt câu hỏi hoặc chia sẻ thắc mắc về bài học này..." required></textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">
                                        <i class="bi bi-send me-1"></i> Đăng câu hỏi
                                    </button>
                                </div>
                            </form>

                            <!-- Discussion Items -->
                            <div class="d-flex flex-column gap-3">
                                <?php if (empty($discussions)): ?>
                                    <p class="text-muted small text-center my-3">Chưa có bình luận nào cho bài học này. Hãy đặt câu hỏi đầu tiên!</p>
                                <?php else: ?>
                                    <?php foreach ($discussions as $disc): ?>
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <div class="d-flex align-items-center gap-2">
                                                    <strong class="text-dark"><?= e($disc['user_name']) ?></strong>
                                                    <?php if ($disc['user_role'] === 'instructor'): ?>
                                                        <span class="badge bg-primary">Giảng viên</span>
                                                    <?php endif; ?>
                                                </div>
                                                <small class="text-muted"><?= date('H:i d/m/Y', strtotime($disc['created_at'])) ?></small>
                                            </div>
                                            <p class="mb-0 text-secondary small"><?= nl2br(e($disc['content'])) ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Personal Notes -->
                    <div class="tab-pane fade" id="tabNotes">
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-1">Ghi chú theo thời điểm video</h6>
                                    <small class="text-muted">Ghi chú gắn liền với mốc thời gian bài học, được đồng bộ bảo mật lên tài khoản.</small>
                                </div>
                                <span id="noteSavedBadge" class="badge bg-success-subtle text-success small d-none">Đã lưu</span>
                            </div>

                            <!-- Add Note Form -->
                            <div class="p-3 bg-light rounded-3 mb-4 border">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-danger px-2 py-1"><i class="bi bi-clock-history me-1"></i> Tại: <strong id="noteCurrentTimestampText">00:00</strong></span>
                                    <span class="text-muted small">Thời điểm hiện tại trên video</span>
                                </div>
                                <textarea id="newNoteInput" class="form-control" rows="2" placeholder="Nhập ghi chú quan trọng tại mốc thời gian này..."></textarea>
                                <div class="text-end mt-2">
                                    <button type="button" class="btn btn-dark btn-sm rounded-pill px-4 fw-bold" onclick="addNewNote()">
                                        <i class="bi bi-plus-circle me-1"></i> Lưu ghi chú mốc này
                                    </button>
                                </div>
                            </div>

                            <!-- Notes List -->
                            <div id="notesContainer" class="d-flex flex-column gap-2">
                                <?php if (empty($notes)): ?>
                                    <p id="emptyNoteNotice" class="text-muted small text-center my-3">Bạn chưa có ghi chú nào cho bài học này. Hãy ghi lại kiến thức trọng tâm!</p>
                                <?php else: ?>
                                    <?php foreach ($notes as $n): ?>
                                        <div class="p-3 bg-white border rounded-3 d-flex justify-content-between align-items-start note-item-row" id="note-item-<?= $n['id'] ?>">
                                            <div class="d-flex gap-3">
                                                <button type="button" class="btn btn-sm btn-outline-danger font-monospace px-2 py-1 rounded-pill" onclick="jumpToVideoTime(<?= (int)$n['timestamp_seconds'] ?>)" title="Bấm để nhảy tới mốc video này">
                                                    <i class="bi bi-play-fill"></i> <?= gmdate("i:s", (int)$n['timestamp_seconds']) ?>
                                                </button>
                                                <div class="small text-dark pt-1">
                                                    <?= nl2br(e($n['content'])) ?>
                                                    <div class="text-muted mt-1" style="font-size: 11px;"><?= date('H:i d/m/Y', strtotime($n['created_at'])) ?></div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" onclick="deleteNoteItem(<?= $n['id'] ?>)" title="Xóa ghi chú">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Right Column: Curriculum Accordion -->
        <aside class="bg-white border-start overflow-y-auto" style="width: 380px; min-width: 320px;">
            <div class="p-3 border-bottom bg-light">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-list-nested me-2"></i>Mục lục khóa học</h6>
            </div>
            <div class="accordion accordion-flush" id="classroomAccordion">
                <?php foreach ($curriculum as $sIdx => $sec): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button py-3 bg-light bg-opacity-50 fw-semibold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#panel-sec-<?= $sec['id'] ?>">
                                <?= e($sec['title']) ?>
                            </button>
                        </h2>
                        <div id="panel-sec-<?= $sec['id'] ?>" class="accordion-collapse collapse show">
                            <div class="accordion-body p-0">
                                <div class="list-group list-group-flush">
                                    <?php foreach ($sec['lessons'] as $les): ?>
                                        <?php
                                            $isActive = ((int)$les['id'] === (int)$currentLesson['id']);
                                            $dur = (int) ($les['duration_seconds'] ?? 0);
                                            $wat = (int) ($les['last_watch_second'] ?? 0);
                                            $isComp = !empty($les['is_completed']);
                                            $pct = ($dur > 0) ? min(100, (int) round(($wat / $dur) * 100)) : ($isComp ? 100 : 0);
                                        ?>
                                        <a href="/learn/<?= $course['id'] ?>/lesson/<?= $les['id'] ?>"
                                           class="list-group-item list-group-item-action py-3 px-3 d-flex align-items-start gap-3 classroom-sidebar-item <?= $isActive ? 'active border-primary' : '' ?>"
                                           data-sidebar-lesson-id="<?= $les['id'] ?>"
                                           data-duration="<?= $dur ?>"
                                           data-watch="<?= $wat ?>"
                                           data-completed="<?= $isComp ? '1' : '0' ?>">
                                            <div class="pt-1">
                                                <?php if ($isComp): ?>
                                                    <i class="bi bi-check-circle-fill text-success fs-5 sidebar-status-icon"></i>
                                                <?php elseif ($isActive): ?>
                                                    <i class="bi bi-play-circle-fill text-primary fs-5 sidebar-status-icon"></i>
                                                <?php elseif ($wat > 0): ?>
                                                    <i class="bi bi-play-circle text-primary fs-5 sidebar-status-icon"></i>
                                                <?php else: ?>
                                                    <i class="bi bi-circle text-muted fs-5 sidebar-status-icon"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-medium <?= $isActive ? 'text-white' : 'text-dark' ?>"><?= e($les['title']) ?></div>
                                                <div class="small <?= $isActive ? 'text-white-50' : 'text-muted' ?> mt-1 d-flex align-items-center gap-1 flex-wrap">
                                                    <?php if ($les['lesson_type'] === 'quiz'): ?>
                                                        <span class="badge bg-warning text-dark">Kiểm tra trắc nghiệm</span>
                                                    <?php elseif ($isComp): ?>
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0">
                                                            <i class="bi bi-check-circle-fill me-1"></i> Đã hoàn thành (<?= $dur ? gmdate("i:s", $dur) : '' ?>)
                                                        </span>
                                                    <?php elseif ($wat > 0): ?>
                                                        <span class="badge <?= $isActive ? 'bg-light text-dark' : 'bg-primary-subtle text-primary border border-primary-subtle' ?> rounded-pill px-2 py-0 sidebar-watch-badge">
                                                            <i class="bi bi-play-circle-fill me-1"></i> Đã xem: <?= gmdate("i:s", $wat) ?> / <?= gmdate("i:s", $dur) ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span><i class="bi bi-clock me-1"></i> <?= $dur ? gmdate("i:s", $dur) : '' ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ($dur > 0 && !$isActive): ?>
                                                    <div class="progress mt-2" style="height: 3px; background-color: rgba(0,0,0,0.06); border-radius: 2px;">
                                                        <div class="progress-bar <?= $isComp ? 'bg-success' : 'bg-primary' ?> sidebar-progress-bar" style="width: <?= $pct ?>%;"></div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </aside>
    </div>
</div>

<!-- Modal Certificate Alert -->
<div class="modal fade" id="congratsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow p-4 text-center">
            <div class="mb-3">
                <i class="bi bi-award display-1 text-warning animate-bounce"></i>
            </div>
            <h3 class="fw-bold text-dark">Xuất sắc! Chúc mừng bạn!</h3>
            <p class="text-muted">Bạn đã hoàn thành 100% nội dung khóa học và chính thức được cấp Chứng chỉ LMS!</p>
            <div class="my-3">
                <a id="modalCertLink" href="#" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold">
                    <i class="bi bi-patch-check"></i> Xem chứng chỉ của bạn
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nhắc Nhở Kiểm Tra 15 Phút (15-Minute Study Checkpoint Test) -->
<div class="modal fade" id="checkpointQuizModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg p-4 text-center" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(251, 191, 36, 0.35); color: #ffffff;">
            <div class="mb-3">
                <div class="badge bg-warning bg-opacity-25 text-warning px-3 py-2 rounded-pill fw-bold fs-6 mb-2">
                    <i class="bi bi-stopwatch-fill me-1"></i> Đã học 15 phút liên tục
                </div>
                <h3 class="fw-bold text-white mb-2">Đã Đến Giờ Kiểm Tra Kiến Thức!</h3>
                <p class="text-secondary small mb-0">Hệ thống áp dụng phương pháp ngắt quãng (Spaced Repetition). Bạn đã tích lũy 15 phút học, hãy làm bài test nhanh để củng cố và ghi nhớ kiến thức nhé!</p>
            </div>
            <div class="card bg-dark bg-opacity-50 border border-secondary border-opacity-50 rounded-3 p-3 my-3 text-start">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-patch-question-fill text-warning fs-1"></i>
                    <div>
                        <div class="fw-bold text-white" id="checkpointQuizTitle">Bài kiểm tra trắc nghiệm 15 phút</div>
                        <div class="small text-secondary">Thời gian: 15 phút • Cần đạt 80% để vượt qua</div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center gap-3 mt-2">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    Để sau
                </button>
                <a id="btnGoToQuiz" href="/quiz/1" class="btn btn-warning rounded-pill px-4 fw-bold shadow">
                    <i class="bi bi-pencil-square me-1"></i> Làm bài kiểm tra ngay
                </a>
            </div>
        </div>
    </div>
</div>

<script>
const courseId = <?= (int) $course['id'] ?>;
const lessonId = <?= (int) $currentLesson['id'] ?>;
const userId = <?= (int) ($user['id'] ?? 0) ?>;
const lessonDuration = <?= (int) ($currentLesson['duration_seconds'] ?? 0) ?>;
let serverLastWatch = <?= (int) ($currentLesson['last_watch_second'] ?? 0) ?>;
let isCompleted = <?= !empty($currentLesson['is_completed']) ? 'true' : 'false' ?>;

const formatTime = (sec) => {
    sec = Math.max(0, Math.round(sec || 0));
    const h = Math.floor(sec / 3600);
    const m = Math.floor((sec % 3600) / 60);
    const s = sec % 60;
    return (h > 0) ? `${h < 10 ? '0' : ''}${h}:${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}` : `${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
};

// Storage key isolated per account
const watchStorageKey = `lms_watch_u${userId}_c${courseId}_l${lessonId}`;

let cachedData = null;
try {
    const raw = localStorage.getItem(watchStorageKey);
    if (raw) cachedData = JSON.parse(raw);
} catch (e) {}

let currentWatchSecond = (cachedData && cachedData.seconds > serverLastWatch) ? cachedData.seconds : serverLastWatch;
if (isCompleted && currentWatchSecond === 0 && lessonDuration > 0) {
    currentWatchSecond = lessonDuration;
}

// Update Watch Sec Text in Toolbar
const watchTxtEl = document.getElementById('classroomWatchSecText');
if (watchTxtEl) watchTxtEl.innerText = formatTime(currentWatchSecond);

// Auto-save Personal Notes in LocalStorage
const noteStorageKey = 'lms_notes_' + courseId + '_' + lessonId;
const noteArea = document.getElementById('personalNoteArea');
const noteBadge = document.getElementById('noteSavedBadge');

if (noteArea) {
    noteArea.value = localStorage.getItem(noteStorageKey) || '';
    noteArea.addEventListener('input', function () {
        localStorage.setItem(noteStorageKey, noteArea.value);
        if (noteBadge) {
            noteBadge.classList.remove('d-none');
            setTimeout(() => noteBadge.classList.add('d-none'), 2000);
        }
    });
}

function saveWatchProgress(sec, completed = isCompleted) {
    currentWatchSecond = Math.max(currentWatchSecond, Math.round(sec || 0));
    if (lessonDuration > 0 && currentWatchSecond >= lessonDuration * 0.9) {
        completed = true;
        isCompleted = true;
    }

    const data = {
        userId: userId,
        courseId: courseId,
        lessonId: lessonId,
        seconds: currentWatchSecond,
        duration: lessonDuration,
        isCompleted: completed,
        updatedAt: Date.now()
    };

    try {
        localStorage.setItem(watchStorageKey, JSON.stringify(data));
    } catch (e) {}

    if (watchTxtEl) watchTxtEl.innerText = formatTime(currentWatchSecond);
}

// Track playback time every second
let playTrackingInterval = setInterval(() => {
    if (lessonDuration > 0 && currentWatchSecond < lessonDuration) {
        saveWatchProgress(currentWatchSecond + 1);
    }
    const noteTimeEl = document.getElementById('noteCurrentTimestampText');
    if (noteTimeEl) {
        noteTimeEl.innerText = formatTime(currentWatchSecond);
    }
}, 1000);

function jumpToVideoTime(seconds) {
    currentWatchSecond = seconds;
    const iframe = document.getElementById('videoIframe');
    if (iframe && iframe.src) {
        let baseSrc = iframe.src.split('?')[0];
        iframe.src = baseSrc + '?start=' + seconds + '&autoplay=1';
    }
    saveWatchProgress(seconds);
}

function addNewNote() {
    const input = document.getElementById('newNoteInput');
    const content = input ? input.value.trim() : '';
    if (!content) {
        alert('Vui lòng nhập nội dung ghi chú.');
        return;
    }

    fetch('/api/lessons/' + lessonId + '/notes', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?= csrf_token() ?>'
        },
        body: JSON.stringify({
            content: content,
            timestamp_seconds: currentWatchSecond
        })
    })
    .then(r => r.json())
    .then(res => {
        if (res && res.ok && res.note) {
            input.value = '';
            const emptyNotice = document.getElementById('emptyNoteNotice');
            if (emptyNotice) emptyNotice.remove();

            const container = document.getElementById('notesContainer');
            const row = document.createElement('div');
            row.className = 'p-3 bg-white border rounded-3 d-flex justify-content-between align-items-start note-item-row';
            row.id = 'note-item-' + res.note.id;
            row.innerHTML = `
                <div class="d-flex gap-3">
                    <button type="button" class="btn btn-sm btn-outline-danger font-monospace px-2 py-1 rounded-pill" onclick="jumpToVideoTime(${res.note.timestamp_seconds})" title="Bấm để nhảy tới mốc video này">
                        <i class="bi bi-play-fill"></i> ${res.note.time_formatted}
                    </button>
                    <div class="small text-dark pt-1">
                        ${res.note.content.replace(/\n/g, '<br>')}
                        <div class="text-muted mt-1" style="font-size: 11px;">Vừa xong</div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" onclick="deleteNoteItem(${res.note.id})" title="Xóa ghi chú">
                    <i class="bi bi-trash"></i>
                </button>
            `;
            container.appendChild(row);

            const badge = document.getElementById('noteSavedBadge');
            if (badge) {
                badge.classList.remove('d-none');
                setTimeout(() => badge.classList.add('d-none'), 2500);
            }
        } else {
            alert(res.error || 'Không thể lưu ghi chú');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Có lỗi xảy ra khi lưu ghi chú');
    });
}

function deleteNoteItem(noteId) {
    if (!confirm('Bạn có chắc muốn xóa ghi chú này?')) return;

    fetch('/api/notes/' + noteId + '/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?= csrf_token() ?>'
        }
    })
    .then(r => r.json())
    .then(res => {
        if (res && res.ok) {
            const el = document.getElementById('note-item-' + noteId);
            if (el) el.remove();
        }
    })
    .catch(err => console.error(err));
}

// Periodically sync progress to server every 5 seconds
setInterval(() => {
    if (currentWatchSecond > 0 || isCompleted) {
        updateProgressOnServer(isCompleted);
    }
}, 5000);

// Send beacon when leaving page
window.addEventListener('beforeunload', () => {
    if (navigator.sendBeacon) {
        const payload = JSON.stringify({
            course_id: courseId,
            lesson_id: lessonId,
            last_watch_second: currentWatchSecond,
            is_completed: isCompleted ? 1 : 0
        });
        navigator.sendBeacon('/api/progress/update', new Blob([payload], { type: 'application/json' }));
    }
});

// Theo dõi tổng thời gian học tích lũy (Cứ 15 phút học xong là hiển thị bài test)
const studyTimeStorageKey = `lms_study_seconds_u${userId}_c${courseId}`;
let totalStudySeconds = parseInt(localStorage.getItem(studyTimeStorageKey) || '0', 10);
let studyInterval = setInterval(() => {
    // Chỉ đếm thời gian khi đang học (tab đang mở và đang phát video)
    totalStudySeconds++;
    localStorage.setItem(studyTimeStorageKey, totalStudySeconds);

    // 15 phút = 900 giây
    if (totalStudySeconds > 0 && totalStudySeconds % 900 === 0) {
        checkAndTrigger15MinQuiz();
    }
}, 1000);

function checkAndTrigger15MinQuiz() {
    // Tìm bài quiz tiếp theo hoặc bài quiz gần nhất của khóa học
    const quizLinks = document.querySelectorAll('a[href^="/quiz/"]');
    if (quizLinks.length > 0) {
        const nextQuizHref = quizLinks[0].getAttribute('href');
        const nextQuizTitle = quizLinks[0].innerText.trim() || 'Bài kiểm tra trắc nghiệm 15 phút';
        
        const titleEl = document.getElementById('checkpointQuizTitle');
        const btnGo = document.getElementById('btnGoToQuiz');
        if (titleEl) titleEl.innerText = nextQuizTitle;
        if (btnGo) btnGo.href = nextQuizHref;

        const modalEl = document.getElementById('checkpointQuizModal');
        if (modalEl && !modalEl.classList.contains('show')) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }
}

function markLessonComplete() {
    isCompleted = true;
    saveWatchProgress(lessonDuration || currentWatchSecond, true);
    updateProgressOnServer(true);
}

function updateProgressOnServer(completed) {
    fetch('/api/progress/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?= csrf_token() ?>'
        },
        body: JSON.stringify({
            course_id: courseId,
            lesson_id: lessonId,
            last_watch_second: currentWatchSecond,
            is_completed: completed
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data && data.ok) {
            // Cập nhật UI
            const bar = document.getElementById('courseProgressBar');
            const txt = document.getElementById('courseProgressText');
            if (bar) bar.style.width = data.progress_percent + '%';
            if (txt) txt.innerText = data.progress_percent + '%';

            const btn = document.getElementById('btnMarkCompleted');
            if (btn && completed) {
                btn.className = 'btn btn-success rounded-pill px-4 fw-semibold';
                btn.innerHTML = '<i class="bi bi-check2-circle"></i> Đã hoàn thành';
            }

            if (data.progress_percent >= 100) {
                const btnCert = document.getElementById('btnCertificate');
                if (btnCert) {
                    btnCert.classList.remove('d-none');
                    if (data.certificate_code) {
                        btnCert.href = '/certificates/' + data.certificate_code;
                        document.getElementById('modalCertLink').href = '/certificates/' + data.certificate_code;
                    }
                }
                const congratsModalEl = document.getElementById('congratsModal');
                if (congratsModalEl && !congratsModalEl.classList.contains('show')) {
                    const modal = new bootstrap.Modal(congratsModalEl);
                    modal.show();
                }
            }
        }
    })
    .catch(err => console.error(err));
}
</script>
