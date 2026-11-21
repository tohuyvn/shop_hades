<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/courses" class="text-decoration-none">Khóa học</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= e($course['title']) ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Main Content (Left) -->
        <div class="col-lg-8">
            <h1 class="h2 fw-bold text-dark mb-3"><?= e($course['title']) ?></h1>
            <p class="lead text-muted mb-4"><?= e($course['summary']) ?></p>

            <div class="d-flex align-items-center flex-wrap gap-3 mb-4 p-3 bg-light rounded-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Giảng viên</div>
                        <div class="fw-semibold text-dark"><?= e($course['instructor_name'] ?? 'Chuyên gia') ?></div>
                    </div>
                </div>
                <div class="vr d-none d-sm-block"></div>
                <div>
                    <div class="small text-muted">Danh mục</div>
                    <div class="fw-semibold text-dark"><?= e($course['category_name'] ?? 'Lập trình') ?></div>
                </div>
                <div class="vr d-none d-sm-block"></div>
                <div>
                    <div class="small text-muted">Trình độ</div>
                    <span class="badge bg-primary-subtle text-primary text-uppercase px-2 py-1">
                        <?= e($course['level']) ?>
                    </span>
                </div>
                <div class="vr d-none d-sm-block"></div>
                <div>
                    <div class="small text-muted">Đánh giá</div>
                    <div class="fw-semibold text-warning">
                        <i class="bi bi-star-fill"></i> <?= (float)($ratingStats['avg_rating'] ?? 5.0) ?> / 5.0
                        <span class="text-muted small fw-normal">(<?= (int)($ratingStats['total_reviews'] ?? 0) ?> nhận xét)</span>
                    </div>
                </div>
            </div>

            <!-- Preview Video if available -->
            <?php
                $previewLesson = null;
                foreach ($curriculum as $s) {
                    foreach ($s['lessons'] as $l) {
                        if (!empty($l['is_preview']) && !empty($l['video_url'])) {
                            $previewLesson = $l;
                            break 2;
                        }
                    }
                }
            ?>
            <?php if ($previewLesson): ?>
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-2 px-3">
                        <span class="small"><i class="bi bi-play-circle-fill text-danger me-1"></i> Video học thử: <strong><?= e($previewLesson['title']) ?></strong></span>
                        <span class="badge bg-success">Học thử miễn phí</span>
                    </div>
                    <div class="ratio ratio-16x9 bg-black">
                        <iframe src="<?= e(formatVideoEmbedUrl($previewLesson['video_url'])) ?>" title="Preview Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Description -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h4 class="fw-bold mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Giới thiệu khóa học</h4>
                <div class="course-description text-secondary">
                    <?= $course['description'] ?: '<p>Khóa học cung cấp đầy đủ kiến thức lý thuyết lẫn bài tập thực hành sát với dự án thực tế.</p>' ?>
                </div>
            </div>

            <!-- Curriculum Syllabus -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="bi bi-collection-play text-primary me-2"></i>Nội dung khóa học</h4>
                        <span class="text-muted small">
                            <?= count($curriculum) ?> chương • <?= (int)$course['total_lessons'] ?> bài học • <?= (int)$course['total_duration_minutes'] ?> phút bài giảng
                        </span>
                    </div>
                    <?php if ($currentUser): ?>
                        <div class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 small d-flex align-items-center gap-1">
                            <i class="bi bi-person-check-fill"></i>
                            <span>Tài khoản: <strong><?= e($currentUser['name']) ?></strong></span>
                        </div>
                    <?php else: ?>
                        <a href="/login?redirect=<?= urlencode($_SERVER['REQUEST_URI'] ?? '/courses/' . $course['slug']) ?>" class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-2 small text-decoration-none">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập để tự động lưu tiến độ xem
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Account Watch Progress Overview Card -->
                <?php if ($currentUser): ?>
                    <div class="bg-light p-3 rounded-4 mb-4 border">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold text-dark"><i class="bi bi-stopwatch-fill text-primary"></i> Tiến độ theo dõi video của bạn:</span>
                                <span class="badge bg-white text-secondary border rounded-pill px-2 py-1 small">
                                    <?= (int)($watchStats['completed_lessons'] ?? 0) ?>/<?= (int)($watchStats['total_lessons'] ?? 0) ?> bài đã hoàn thành
                                </span>
                            </div>
                            <div class="fw-bold text-primary font-monospace fs-6">
                                Đã xem: <span id="courseWatchedTimeText"><?= e($watchStats['formatted_watched'] ?? '00:00') ?></span> / <?= e($watchStats['formatted_total'] ?? '00:00') ?> (<span id="courseWatchPercentText"><?= (int)($watchStats['watch_percent'] ?? 0) ?>%</span>)
                            </div>
                        </div>
                        <div class="progress" style="height: 8px; border-radius: 6px; background-color: #dee2e6;">
                            <div id="courseOverviewProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: <?= (int)($watchStats['watch_percent'] ?? 0) ?>%;"></div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php
                $fmtTime = function ($sec) {
                    $sec = (int) $sec;
                    $h = floor($sec / 3600);
                    $m = floor(($sec % 3600) / 60);
                    $s = $sec % 60;
                    return ($h > 0) ? sprintf("%02d:%02d:%02d", $h, $m, $s) : sprintf("%02d:%02d", $m, $s);
                };
                ?>

                <div class="accordion" id="curriculumAccordion">
                    <?php foreach ($curriculum as $idx => $section): ?>
                        <div class="accordion-item border rounded-3 mb-2 overflow-hidden shadow-none">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?= $idx > 0 ? 'collapsed' : '' ?> bg-light fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#sec-<?= $section['id'] ?>">
                                    <div class="d-flex justify-content-between align-items-center w-100 pe-3 flex-wrap gap-2">
                                        <span><?= e($section['title']) ?></span>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-secondary-subtle text-secondary small fw-normal">
                                                <?= count($section['lessons']) ?> bài
                                            </span>
                                            <?php if (!empty($section['total_duration_seconds'])): ?>
                                                <span class="badge bg-light text-muted border small fw-normal">
                                                    <i class="bi bi-clock me-1"></i><?= $fmtTime($section['total_duration_seconds']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <div id="sec-<?= $section['id'] ?>" class="accordion-collapse collapse <?= $idx === 0 ? 'show' : '' ?>" data-bs-parent="#curriculumAccordion">
                                <div class="accordion-body p-0">
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($section['lessons'] as $lesson): ?>
                                            <?php
                                                $dur = (int) ($lesson['duration_seconds'] ?? 0);
                                                $wat = (int) ($lesson['last_watch_second'] ?? 0);
                                                $isComp = !empty($lesson['is_completed']);
                                                $pct = ($dur > 0) ? min(100, (int) round(($wat / $dur) * 100)) : ($isComp ? 100 : 0);
                                            ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3 lesson-row"
                                                data-lesson-id="<?= (int)$lesson['id'] ?>"
                                                data-duration="<?= $dur ?>"
                                                data-watch="<?= $wat ?>"
                                                data-completed="<?= $isComp ? '1' : '0' ?>"
                                                data-title="<?= e($lesson['title']) ?>"
                                                data-video-url="<?= e($lesson['video_url'] ?? '') ?>">
                                                
                                                <div class="d-flex align-items-start gap-3 flex-grow-1 me-3">
                                                    <div class="pt-1">
                                                        <?php if ($isComp): ?>
                                                            <i class="bi bi-check-circle-fill text-success fs-5 lesson-status-icon"></i>
                                                        <?php elseif ($wat > 0): ?>
                                                            <i class="bi bi-play-circle-fill text-primary fs-5 lesson-status-icon"></i>
                                                        <?php elseif ($lesson['lesson_type'] === 'quiz'): ?>
                                                            <i class="bi bi-patch-question text-warning fs-5 lesson-status-icon"></i>
                                                        <?php else: ?>
                                                            <i class="bi bi-play-circle text-muted fs-5 lesson-status-icon"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-medium text-dark mb-1">
                                                            <?php if ($isEnrolled): ?>
                                                                <a href="/learn/<?= $course['id'] ?>/lesson/<?= $lesson['id'] ?>" class="text-dark text-decoration-none hover-primary">
                                                                    <?= e($lesson['title']) ?>
                                                                </a>
                                                            <?php else: ?>
                                                                <span><?= e($lesson['title']) ?></span>
                                                            <?php endif; ?>
                                                        </div>

                                                        <!-- Watched Duration Info & Progress Bar -->
                                                        <div class="lesson-meta-wrapper d-flex align-items-center gap-2 flex-wrap">
                                                            <?php if ($isComp): ?>
                                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0 lesson-watch-badge" style="font-size: 0.75rem;">
                                                                    <i class="bi bi-check-circle-fill me-1"></i> Đã hoàn thành (<?= $fmtTime($dur ?: $wat) ?>)
                                                                </span>
                                                                <div class="progress" style="height: 5px; width: 130px; background-color: #e9ecef; border-radius: 4px;">
                                                                    <div class="progress-bar bg-success lesson-progress-bar" role="progressbar" style="width: 100%;"></div>
                                                                </div>
                                                            <?php elseif ($wat > 0): ?>
                                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0 lesson-watch-badge" style="font-size: 0.75rem;">
                                                                    <i class="bi bi-play-circle-fill me-1"></i> Đã xem: <strong><?= $fmtTime($wat) ?></strong> / <?= $fmtTime($dur) ?> (<?= $pct ?>%)
                                                                </span>
                                                                <div class="progress" style="height: 5px; width: 130px; background-color: #e9ecef; border-radius: 4px;">
                                                                    <div class="progress-bar bg-primary lesson-progress-bar" role="progressbar" style="width: <?= $pct ?>%;"></div>
                                                                </div>
                                                            <?php else: ?>
                                                                <?php if ($dur > 0): ?>
                                                                    <span class="small text-muted"><i class="bi bi-clock me-1"></i> <?= $fmtTime($dur) ?></span>
                                                                <?php endif; ?>
                                                                <?php if ($currentUser): ?>
                                                                    <span class="badge bg-light text-secondary border rounded-pill px-2 py-0 lesson-watch-badge" style="font-size: 0.75rem;">Chưa xem</span>
                                                                    <div class="progress" style="height: 5px; width: 130px; background-color: #e9ecef; border-radius: 4px;">
                                                                        <div class="progress-bar bg-secondary lesson-progress-bar" role="progressbar" style="width: 0%;"></div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Action Buttons: Preview or Classroom -->
                                                <div class="d-flex align-items-center gap-2">
                                                    <?php if (!empty($lesson['is_preview'])): ?>
                                                        <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1 shadow-sm"
                                                            onclick="openPreviewModal(<?= htmlspecialchars(json_encode([
                                                                'id' => (int) $lesson['id'],
                                                                'title' => $lesson['title'],
                                                                'video_url' => $lesson['video_url'] ?? '',
                                                                'duration' => $dur,
                                                                'last_watch' => $wat,
                                                                'is_completed' => $isComp
                                                            ]), ENT_QUOTES, 'UTF-8') ?>)">
                                                            <i class="bi bi-play-circle-fill"></i> Xem thử
                                                        </button>
                                                    <?php elseif ($isEnrolled): ?>
                                                        <a href="/learn/<?= $course['id'] ?>/lesson/<?= $lesson['id'] ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1 shadow-sm">
                                                            <i class="bi bi-box-arrow-in-right"></i> Vào học
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-muted border rounded-pill px-2 py-1">
                                                            <i class="bi bi-lock-fill"></i> Khóa
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Instructor Profile -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h4 class="fw-bold mb-3"><i class="bi bi-person-badge text-primary me-2"></i>Thông tin Giảng viên</h4>
                <div class="d-flex gap-3 align-items-start">
                    <div class="bg-primary-subtle text-primary rounded-4 d-flex align-items-center justify-content-center p-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-person-fill display-6"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1"><?= e($course['instructor_name']) ?></h5>
                        <p class="text-primary small mb-2"><?= e($course['instructor_headline'] ?? 'Giảng viên chuyên môn') ?></p>
                        <p class="text-muted small mb-0"><?= e($course['instructor_bio'] ?? 'Nhiều năm kinh nghiệm giảng dạy và phát triển các hệ thống thực tế.') ?></p>
                    </div>
                </div>
            </div>

            <!-- Student Reviews Section -->
            <div id="reviewsSection" class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h4 class="fw-bold mb-0"><i class="bi bi-chat-heart text-danger me-2"></i>Đánh giá từ Học viên</h4>
                    <div class="text-warning fw-bold fs-5">
                        <i class="bi bi-star-fill"></i> <?= (float)($ratingStats['avg_rating'] ?? 5.0) ?> / 5.0
                        <span class="text-muted small fw-normal">(<?= (int)($ratingStats['total_reviews'] ?? 0) ?> đánh giá)</span>
                    </div>
                </div>

                <!-- Review Form if Enrolled -->
                <?php if ($isEnrolled): ?>
                    <div class="p-3 bg-light rounded-4 mb-4">
                        <h6 class="fw-bold mb-2">Để lại cảm nhận của bạn về khóa học:</h6>
                        <form method="post" action="/courses/<?= $course['id'] ?>/review">
                            <?= csrf_field() ?>
                            <div class="mb-2 d-flex align-items-center gap-2">
                                <span class="small fw-semibold text-muted">Số sao:</span>
                                <select name="rating" class="form-select form-select-sm" style="width: 130px;">
                                    <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                    <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                    <option value="3">⭐⭐⭐ (3/5)</option>
                                    <option value="2">⭐⭐ (2/5)</option>
                                    <option value="1">⭐ (1/5)</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <textarea name="comment" class="form-control" rows="2" placeholder="Chia sẻ trải nghiệm học tập của bạn..." required></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">Gửi đánh giá</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

                <!-- Reviews List -->
                <?php if (empty($reviews)): ?>
                    <p class="text-muted small text-center my-3">Chưa có đánh giá nào cho khóa học này. Hãy là người đầu tiên trải nghiệm và để lại nhận xét!</p>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($reviews as $rev): ?>
                            <div class="p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            <?= mb_substr($rev['user_name'], 0, 1) ?>
                                        </div>
                                        <span class="fw-semibold text-dark"><?= e($rev['user_name']) ?></span>
                                    </div>
                                    <div class="text-warning small">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi <?= $i <= (int)$rev['rating'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="text-secondary small mb-1 ms-4 ps-2"><?= nl2br(e($rev['comment'])) ?></p>
                                <div class="text-muted ms-4 ps-2" style="font-size: 0.75rem;"><?= date('d/m/Y', strtotime($rev['created_at'])) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sticky Sidebar Card (Right) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 24px;">
                <img src="<?= e($course['thumbnail'] ?: '/image/banner1.png') ?>" class="card-img-top object-fit-cover" style="height: 220px;" alt="<?= e($course['title']) ?>">
                <div class="card-body p-4">
                    <?php
                        $isFree = ($course['price'] <= 0 || ($course['sale_price'] !== null && $course['sale_price'] <= 0));
                        $displayPrice = ($course['sale_price'] !== null && $course['sale_price'] >= 0) ? $course['sale_price'] : $course['price'];
                    ?>
                    <div class="mb-3">
                        <?php if ($isFree): ?>
                            <span class="display-6 fw-bold text-success">Miễn phí</span>
                        <?php else: ?>
                            <span class="display-6 fw-bold text-primary"><?= formatMoney($displayPrice) ?></span>
                            <?php if ($course['sale_price'] !== null && $course['sale_price'] < $course['price']): ?>
                                <span class="text-decoration-line-through text-muted ms-2 fs-5"><?= formatMoney($course['price']) ?></span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2 mb-4">
                        <?php if ($isEnrolled): ?>
                            <a href="/learn/<?= $course['id'] ?>" class="btn btn-success btn-lg rounded-pill fw-bold">
                                <i class="bi bi-play-fill"></i> Tiếp tục học ngay
                            </a>
                        <?php else: ?>
                            <form method="post" action="/courses/<?= $course['id'] ?>/enroll">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">
                                    <i class="bi bi-mortarboard-fill"></i> <?= $isFree ? 'Đăng ký học miễn phí' : 'Đăng ký khóa học ngay' ?>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <h6 class="fw-bold mb-3">Khóa học này bao gồm:</h6>
                    <ul class="list-unstyled text-secondary small d-flex flex-column gap-2 mb-0">
                        <li><i class="bi bi-clock-history text-primary me-2"></i> <?= (int)$course['total_duration_minutes'] ?> phút video bài giảng</li>
                        <li><i class="bi bi-journal-text text-primary me-2"></i> <?= (int)$course['total_lessons'] ?> bài học giáo trình chuẩn</li>
                        <li><i class="bi bi-download text-primary me-2"></i> Tải tài liệu và mã nguồn đính kèm</li>
                        <li><i class="bi bi-patch-question text-primary me-2"></i> Bài kiểm tra trắc nghiệm đánh giá</li>
                        <li><i class="bi bi-patch-check-fill text-primary me-2"></i> <strong>Chứng chỉ hoàn thành</strong> khi đạt 100%</li>
                        <li><i class="bi bi-infinity text-primary me-2"></i> Truy cập trọn đời trên mọi thiết bị</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xem Thử Video (Hỗ trợ Cache Thời Lượng Xem Cho Từng Tài Khoản) -->
<div class="modal fade" id="previewVideoModal" tabindex="-1" aria-labelledby="previewVideoModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-dark text-white border-0 py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success rounded-pill px-2 py-1"><i class="bi bi-play-circle-fill"></i> Xem thử</span>
                    <h5 class="modal-title fw-bold text-truncate mb-0" id="previewVideoModalLabel" style="max-width: 500px;">Xem thử bài học</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="closePreviewModal()"></button>
            </div>

            <!-- Video Player Box -->
            <div class="modal-body p-0 bg-black position-relative" style="min-height: 400px;">
                <div id="previewPlayerContainer" class="ratio ratio-16x9 w-100">
                    <!-- Dynamic Video Player Iframe / Video inserted here -->
                </div>
            </div>

            <!-- Playback Meta & Per-Account Cache Indicator -->
            <div class="modal-footer bg-light border-0 py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 font-monospace" id="previewPlaybackBadge">
                        <i class="bi bi-clock-history me-1"></i> <span id="previewCurrentTime">00:00</span> / <span id="previewTotalTime">00:00</span>
                    </span>
                    <span class="badge bg-white text-secondary border rounded-pill px-3 py-1 small">
                        <i class="bi bi-person-check text-success me-1"></i> Lưu tài khoản: <strong><?= e($currentUser['name'] ?? 'Khách') ?></strong>
                    </span>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary rounded-pill px-4 fw-semibold btn-sm" data-bs-dismiss="modal" onclick="closePreviewModal()">
                        Đóng & Lưu tiến độ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ==============================================================
// LMS WATCH TIME CACHE & REAL-TIME SYNC MANAGER (PER-ACCOUNT)
// ==============================================================
const LmsWatchCache = {
    userId: <?= json_encode($currentUser['id'] ?? 'guest') ?>,
    courseId: <?= (int)$course['id'] ?>,
    csrfToken: '<?= csrf_token() ?>',

    formatTime(sec) {
        sec = Math.max(0, Math.round(sec || 0));
        const h = Math.floor(sec / 3600);
        const m = Math.floor((sec % 3600) / 60);
        const s = sec % 60;
        if (h > 0) {
            return `${h < 10 ? '0' : ''}${h}:${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
        }
        return `${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
    },

    getStorageKey(lessonId) {
        return `lms_watch_u${this.userId}_c${this.courseId}_l${lessonId}`;
    },

    getLessonCache(lessonId) {
        try {
            const raw = localStorage.getItem(this.getStorageKey(lessonId));
            return raw ? JSON.parse(raw) : null;
        } catch (e) {
            return null;
        }
    },

    saveLessonCache(lessonId, seconds, duration, isCompleted = false) {
        seconds = Math.max(0, Math.round(seconds || 0));
        duration = Math.max(0, Math.round(duration || 0));
        const pct = duration > 0 ? Math.min(100, Math.round((seconds / duration) * 100)) : 0;
        if (pct >= 90) {
            isCompleted = true;
        }

        const data = {
            userId: this.userId,
            courseId: this.courseId,
            lessonId: lessonId,
            seconds: seconds,
            duration: duration,
            percent: pct,
            isCompleted: isCompleted,
            updatedAt: Date.now()
        };

        try {
            localStorage.setItem(this.getStorageKey(lessonId), JSON.stringify(data));
        } catch (e) {}

        this.updateLessonUI(lessonId, seconds, duration, isCompleted);
        this.recalculateOverview();

        if (this.userId !== 'guest') {
            this.syncToServer(lessonId, seconds, isCompleted);
        }
    },

    syncToServer(lessonId, seconds, isCompleted) {
        if (!this.userId || this.userId === 'guest') return;
        fetch('/api/progress/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            },
            body: JSON.stringify({
                course_id: this.courseId,
                lesson_id: lessonId,
                last_watch_second: seconds,
                is_completed: isCompleted ? 1 : 0
            })
        }).catch(err => console.warn('LMS Progress sync error:', err));
    },

    updateLessonUI(lessonId, seconds, duration, isCompleted) {
        const row = document.querySelector(`.lesson-row[data-lesson-id="${lessonId}"]`);
        if (!row) return;

        // Cập nhật thuộc tính data
        row.setAttribute('data-watch', seconds);
        if (duration > 0) row.setAttribute('data-duration', duration);
        row.setAttribute('data-completed', isCompleted ? '1' : '0');

        const badgeWrapper = row.querySelector('.lesson-meta-wrapper');
        const icon = row.querySelector('.lesson-status-icon');
        const pct = duration > 0 ? Math.min(100, Math.round((seconds / duration) * 100)) : (isCompleted ? 100 : 0);

        if (badgeWrapper) {
            if (isCompleted) {
                badgeWrapper.innerHTML = `
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0 lesson-watch-badge" style="font-size: 0.75rem;">
                        <i class="bi bi-check-circle-fill me-1"></i> Đã hoàn thành (${this.formatTime(duration || seconds)})
                    </span>
                    <div class="progress" style="height: 5px; width: 130px; background-color: #e9ecef; border-radius: 4px;">
                        <div class="progress-bar bg-success lesson-progress-bar" role="progressbar" style="width: 100%;"></div>
                    </div>`;
                if (icon) icon.className = 'bi bi-check-circle-fill text-success fs-5 lesson-status-icon';
            } else if (seconds > 0) {
                badgeWrapper.innerHTML = `
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0 lesson-watch-badge" style="font-size: 0.75rem;">
                        <i class="bi bi-play-circle-fill me-1"></i> Đã xem: <strong>${this.formatTime(seconds)}</strong> / ${this.formatTime(duration)} (${pct}%)
                    </span>
                    <div class="progress" style="height: 5px; width: 130px; background-color: #e9ecef; border-radius: 4px;">
                        <div class="progress-bar bg-primary lesson-progress-bar" role="progressbar" style="width: ${pct}%;"></div>
                    </div>`;
                if (icon) icon.className = 'bi bi-play-circle-fill text-primary fs-5 lesson-status-icon';
            }
        }
    },

    recalculateOverview() {
        let totalSec = 0;
        let watchedSec = 0;
        let completedCount = 0;
        const rows = document.querySelectorAll('.lesson-row');
        if (!rows.length) return;

        rows.forEach(r => {
            const dur = parseInt(r.getAttribute('data-duration') || '0');
            let wat = parseInt(r.getAttribute('data-watch') || '0');
            const comp = r.getAttribute('data-completed') === '1';

            if (comp) {
                completedCount++;
                if (wat === 0 && dur > 0) wat = dur;
            }
            totalSec += dur;
            watchedSec += (dur > 0) ? Math.min(dur, wat) : wat;
        });

        const pct = totalSec > 0 ? Math.min(100, Math.round((watchedSec / totalSec) * 100)) : 0;
        const watchTxt = document.getElementById('courseWatchedTimeText');
        const pctTxt = document.getElementById('courseWatchPercentText');
        const pBar = document.getElementById('courseOverviewProgressBar');

        if (watchTxt) watchTxt.innerText = this.formatTime(watchedSec);
        if (pctTxt) pctTxt.innerText = pct + '%';
        if (pBar) pBar.style.width = pct + '%';
    },

    initPage() {
        // Đồng bộ dữ liệu cache của tài khoản này từ localStorage khi vừa mở trang
        document.querySelectorAll('.lesson-row').forEach(row => {
            const lessonId = parseInt(row.getAttribute('data-lesson-id'));
            const serverWatch = parseInt(row.getAttribute('data-watch') || '0');
            const serverDuration = parseInt(row.getAttribute('data-duration') || '0');
            const serverCompleted = row.getAttribute('data-completed') === '1';

            const cached = this.getLessonCache(lessonId);
            if (cached && (cached.seconds > serverWatch || (cached.isCompleted && !serverCompleted))) {
                // Client cache có thời lượng mới hơn
                this.updateLessonUI(lessonId, cached.seconds, cached.duration || serverDuration, cached.isCompleted);
                if (this.userId !== 'guest') {
                    this.syncToServer(lessonId, cached.seconds, cached.isCompleted);
                }
            } else if (serverWatch > 0 || serverCompleted) {
                // Lưu server state vào local cache của account này
                const initData = {
                    userId: this.userId,
                    courseId: this.courseId,
                    lessonId: lessonId,
                    seconds: serverWatch,
                    duration: serverDuration,
                    percent: serverDuration > 0 ? Math.min(100, Math.round((serverWatch / serverDuration) * 100)) : 0,
                    isCompleted: serverCompleted,
                    updatedAt: Date.now()
                };
                try {
                    localStorage.setItem(this.getStorageKey(lessonId), JSON.stringify(initData));
                } catch(e) {}
            }
        });
        this.recalculateOverview();
    }
};

// ==============================================================
// PREVIEW MODAL PLAYER LOGIC (WITH REAL-TIME TIME TRACKING)
// ==============================================================
let activePreviewLesson = null;
let previewTimerInterval = null;
let ytPreviewPlayer = null;

function openPreviewModal(lesson) {
    activePreviewLesson = lesson;
    const modalEl = document.getElementById('previewVideoModal');
    const modal = new bootstrap.Modal(modalEl);

    document.getElementById('previewVideoModalLabel').innerText = lesson.title + ' (Xem thử)';

    // Kiểm tra cache tài khoản để lấy giây xem dở
    const cached = LmsWatchCache.getLessonCache(lesson.id);
    let startSecond = (cached && cached.seconds > 0) ? cached.seconds : (lesson.last_watch || 0);
    const duration = lesson.duration || (cached ? cached.duration : 0);

    document.getElementById('previewCurrentTime').innerText = LmsWatchCache.formatTime(startSecond);
    document.getElementById('previewTotalTime').innerText = LmsWatchCache.formatTime(duration);

    const container = document.getElementById('previewPlayerContainer');
    container.innerHTML = '';

    const vUrl = lesson.video_url || '';
    const isDirectMp4 = vUrl.endsWith('.mp4');

    if (!isDirectMp4) {
        // Tách YouTube ID
        let ytId = '';
        const match = vUrl.match(/(?:embed\/|v=|vi\/|youtu\.be\/|\/v\/)([a-zA-Z0-9_-]{11})/i);
        if (match && match[1]) {
            ytId = match[1];
        }
        if (!ytId || ytId === 'dQw4w9WgXcQ') {
            ytId = 'f02mOEt11OQ'; // Verified tech lecture fallback
        }

        const embedUrl = `https://www.youtube.com/embed/${ytId}?enablejsapi=1&autoplay=1&start=${startSecond}`;
        container.innerHTML = `<iframe id="ytPreviewFrame" src="${embedUrl}" title="${lesson.title}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-100 h-100 border-0"></iframe>`;

        // Khởi động bộ đếm theo dõi thời lượng thực tế
        let curSec = startSecond;
        clearInterval(previewTimerInterval);
        previewTimerInterval = setInterval(() => {
            curSec += 1;
            if (duration > 0 && curSec >= duration) {
                curSec = duration;
                LmsWatchCache.saveLessonCache(lesson.id, curSec, duration, true);
                clearInterval(previewTimerInterval);
            } else {
                LmsWatchCache.saveLessonCache(lesson.id, curSec, duration, false);
            }
            document.getElementById('previewCurrentTime').innerText = LmsWatchCache.formatTime(curSec);
        }, 1000);

    } else {
        // HTML5 Video Player
        container.innerHTML = `
            <video id="html5PreviewPlayer" controls autoplay class="w-100 h-100" style="object-fit: contain;">
                <source src="${vUrl}" type="video/mp4">
                Trình duyệt của bạn không hỗ trợ thẻ video.
            </video>`;
        const video = document.getElementById('html5PreviewPlayer');
        if (video) {
            video.currentTime = startSecond;
            video.addEventListener('timeupdate', () => {
                const current = Math.round(video.currentTime);
                const dur = Math.round(video.duration || duration);
                document.getElementById('previewCurrentTime').innerText = LmsWatchCache.formatTime(current);
                if (dur > 0) document.getElementById('previewTotalTime').innerText = LmsWatchCache.formatTime(dur);
                LmsWatchCache.saveLessonCache(lesson.id, current, dur, video.ended);
            });
            video.addEventListener('ended', () => {
                LmsWatchCache.saveLessonCache(lesson.id, video.duration, video.duration, true);
            });
        }
    }

    modal.show();
}

function closePreviewModal() {
    clearInterval(previewTimerInterval);
    const container = document.getElementById('previewPlayerContainer');
    if (container) container.innerHTML = '';
}

document.addEventListener('DOMContentLoaded', function () {
    LmsWatchCache.initPage();
});
</script>
