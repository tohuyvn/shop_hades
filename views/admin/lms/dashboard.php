<div class="refract-dashboard-grid">
    <!-- Row 1: KPI Stat Cards with Rainbow Refraction -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Revenue -->
        <div class="col-md-3">
            <div class="refract-card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="refract-metric-title">Doanh thu LMS</span>
                    <span class="badge-growth text-success"><i class="bi bi-arrow-up-short"></i> 18.2%</span>
                </div>
                <div class="refract-metric-value"><?= formatMoney($totalRevenue) ?></div>
                <div class="sparkline-wrapper mt-2">
                    <svg viewBox="0 0 100 25" class="sparkline text-warning" preserveAspectRatio="none">
                        <path d="M0,20 Q25,5 50,15 T100,2" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 2: Students -->
        <div class="col-md-3">
            <div class="refract-card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="refract-metric-title">Học viên ghi danh</span>
                    <span class="badge-growth text-cyan"><i class="bi bi-arrow-up-short"></i> 12.5%</span>
                </div>
                <div class="refract-metric-value"><?= number_format($totalStudents) ?> <span class="fs-6 fw-normal text-muted">học viên</span></div>
                <div class="sparkline-wrapper mt-2">
                    <svg viewBox="0 0 100 25" class="sparkline text-cyan" preserveAspectRatio="none">
                        <path d="M0,15 Q30,22 60,8 T100,5" fill="none" stroke="#00f2fe" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 3: Passing Rate -->
        <div class="col-md-3">
            <div class="refract-card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="refract-metric-title">Tỷ lệ đỗ Quiz</span>
                    <span class="badge-growth text-success"><i class="bi bi-arrow-up-short"></i> 85%</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="refract-circle-progress">
                        <span class="circle-val">85%</span>
                    </div>
                    <div class="small text-muted">
                        Dựa trên toàn bộ lượt nộp bài kiểm tra
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Certificates Issued -->
        <div class="col-md-3">
            <div class="refract-card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="refract-metric-title">Chứng chỉ đã cấp</span>
                    <span class="badge-growth text-warning"><i class="bi bi-award"></i> Valid</span>
                </div>
                <div class="refract-metric-value"><?= number_format($totalCertificates) ?></div>
                <div class="sparkline-wrapper mt-2">
                    <svg viewBox="0 0 100 25" class="sparkline text-purple" preserveAspectRatio="none">
                        <path d="M0,18 Q40,5 70,16 T100,4" fill="none" stroke="#c084fc" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Big Chart & Spotlight Card -->
    <div class="row g-3 mb-4">
        <!-- Big Chart Card -->
        <div class="col-lg-8">
            <div class="refract-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold">Tăng trưởng học tập & Doanh thu</div>
                        <h3 class="fw-bold mb-0 text-white"><?= formatMoney($totalRevenue) ?> <span class="badge-growth text-success fs-6"><i class="bi bi-arrow-up-short"></i> +24%</span></h3>
                    </div>
                    <div class="btn-group btn-group-sm refract-pill-group">
                        <button class="btn btn-sm btn-refract-tab">7D</button>
                        <button class="btn btn-sm btn-refract-tab">30D</button>
                        <button class="btn btn-sm btn-refract-tab active">12M</button>
                    </div>
                </div>

                <!-- Simulated Glowing Curve Chart -->
                <div class="chart-container position-relative py-3" style="height: 220px;">
                    <svg viewBox="0 0 600 200" class="w-100 h-100" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#ff6a00" stop-opacity="0.35"/>
                                <stop offset="100%" stop-color="#ee0979" stop-opacity="0.0"/>
                            </linearGradient>
                            <linearGradient id="strokeGrad" x1="0" y1="0" x2="1" y2="0">
                                <stop offset="0%" stop-color="#ff6a00"/>
                                <stop offset="50%" stop-color="#ee0979"/>
                                <stop offset="100%" stop-color="#ff6a00"/>
                            </linearGradient>
                        </defs>
                        <!-- Grid lines -->
                        <line x1="0" y1="50" x2="600" y2="50" stroke="rgba(255,255,255,0.06)" stroke-dasharray="4"/>
                        <line x1="0" y1="100" x2="600" y2="100" stroke="rgba(255,255,255,0.06)" stroke-dasharray="4"/>
                        <line x1="0" y1="150" x2="600" y2="600" stroke="rgba(255,255,255,0.06)" stroke-dasharray="4"/>

                        <!-- Area & Line -->
                        <path d="M0,170 C100,160 150,110 250,120 C350,130 400,60 500,80 C550,90 580,40 600,30 L600,200 L0,200 Z" fill="url(#chartGrad)"/>
                        <path d="M0,170 C100,160 150,110 250,120 C350,130 400,60 500,80 C550,90 580,40 600,30" fill="none" stroke="url(#strokeGrad)" stroke-width="3" stroke-linecap="round"/>
                        
                        <!-- Pulse Point -->
                        <circle cx="350" cy="130" r="5" fill="#ffffff" stroke="#ff6a00" stroke-width="3"/>
                    </svg>

                    <!-- Popup Tag like Refract -->
                    <div class="refract-chart-tag" style="position: absolute; top: 38%; left: 54%;">
                        <div class="tag-month">Tháng này</div>
                        <div class="tag-val"><?= formatMoney($totalRevenue * 0.45) ?></div>
                    </div>
                </div>

                <div class="d-flex justify-content-between text-muted small px-1 pt-2 border-top border-secondary border-opacity-25">
                    <span>Thg 1</span><span>Thg 3</span><span>Thg 5</span><span>Thg 7</span><span>Thg 9</span><span>Thg 11</span>
                </div>
            </div>
        </div>

        <!-- Spotlight Instructor Card -->
        <div class="col-lg-4">
            <div class="refract-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="instructor-avatar-big">
                            TA
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-white">ThS. Đặng Tuấn Anh</h5>
                            <span class="small text-muted">Senior Backend & Go Specialist</span>
                        </div>
                    </div>

                    <div class="p-3 rounded-4 mb-3" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                        <div class="text-muted small mb-1">Khóa học dẫn đầu doanh thu</div>
                        <div class="fw-semibold text-white">Chuyên Sâu Golang & Microservices</div>
                        <div class="text-warning small mt-1">★★★★★ 4.9/5 (128 đánh giá)</div>
                    </div>
                </div>

                <div class="refract-audio-pill p-2 d-flex align-items-center justify-content-between">
                    <span class="small ps-2 text-muted"><i class="bi bi-circle-fill text-success fs-6 me-1"></i> Trực tuyến</span>
                    <a href="/admin/lms/instructors" class="btn btn-sm btn-primary rounded-pill px-3 py-1">Xem hồ sơ</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Top Courses Table & Recent Quiz Attempts -->
    <div class="row g-3">
        <!-- Top Courses -->
        <div class="col-lg-7">
            <div class="refract-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-white">Khóa học đăng ký nhiều nhất</h5>
                    <a href="/admin/lms/courses" class="btn btn-sm btn-outline-primary rounded-pill px-3">Tất cả</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Khóa học</th>
                                <th>Học viên</th>
                                <th>Tiến độ TB</th>
                                <th>Giá bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topCourses as $tc): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold text-white"><?= e($tc['title']) ?></div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-info bg-opacity-25 text-info px-2 py-1">
                                        <?= (int)$tc['student_count'] ?> học viên
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px; background: rgba(255,255,255,0.1); border-radius: 4px;">
                                            <div class="progress-bar bg-success rounded" style="width: <?= min(100, (float)$tc['avg_progress']) ?>%;"></div>
                                        </div>
                                        <span class="small text-muted"><?= (float)$tc['avg_progress'] ?>%</span>
                                    </div>
                                </td>
                                <td class="fw-bold text-warning">
                                    <?= ((float)$tc['price'] == 0) ? 'Miễn phí' : formatMoney((float)($tc['sale_price'] > 0 ? $tc['sale_price'] : $tc['price'])) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Attempts -->
        <div class="col-lg-5">
            <div class="refract-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-white">Lượt nộp bài Quiz gần đây</h5>
                    <a href="/admin/lms/quizzes" class="btn btn-sm btn-outline-primary rounded-pill px-3">Quản lý</a>
                </div>

                <div class="d-flex flex-column gap-2">
                    <?php foreach ($recentAttempts as $att): ?>
                    <div class="p-3 rounded-4 d-flex align-items-center justify-content-between" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07);">
                        <div>
                            <div class="fw-semibold text-white small"><?= e($att['student_name']) ?></div>
                            <div class="text-muted" style="font-size: 0.78rem;"><?= e($att['quiz_title']) ?></div>
                        </div>
                        <div class="text-end">
                            <span class="badge <?= $att['is_passed'] ? 'bg-success text-white' : 'bg-danger text-white' ?> rounded-pill px-2 py-1">
                                <?= $att['is_passed'] ? 'Đạt' : 'Chưa đạt' ?> (<?= (float)$att['score_percentage'] ?>%)
                            </span>
                            <div class="text-muted" style="font-size: 0.72rem; margin-top: 3px;"><?= date('H:i d/m', strtotime($att['started_at'])) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
