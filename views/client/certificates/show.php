<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chứng Chỉ Hoàn Thành — <?= e($cert['student_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Times New Roman', Times, serif;
            color: #1e293b;
        }
        .cert-container {
            max-width: 900px;
            margin: 40px auto;
            background: #ffffff;
            padding: 50px 60px;
            border: 12px solid #1e3a8a;
            position: relative;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            background-image: radial-gradient(circle at center, #ffffff 0%, #f8fafc 100%);
        }
        .cert-inner-border {
            border: 2px dashed #d97706;
            padding: 40px;
            text-align: center;
        }
        .cert-title {
            font-size: 38px;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .cert-subtitle {
            font-size: 18px;
            color: #64748b;
            font-style: italic;
            margin-bottom: 30px;
        }
        .student-name {
            font-size: 36px;
            font-weight: bold;
            color: #b45309;
            text-decoration: underline;
            text-decoration-color: #fbbf24;
            margin: 20px 0;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .course-title {
            font-size: 26px;
            font-weight: bold;
            color: #0f172a;
            margin: 15px 0 25px 0;
            font-family: system-ui, -apple-system, sans-serif;
        }
        @media print {
            body { background: white; margin: 0; }
            .no-print { display: none !important; }
            .cert-container { box-shadow: none; border-width: 8px; margin: 0; max-width: 100%; }
        }
    </style>
</head>
<body>
    <div class="text-center my-4 no-print d-flex justify-content-center align-items-center gap-2 flex-wrap">
        <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-printer me-2"></i> In / Xuất File PDF
        </button>
        <a href="https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME&name=<?= urlencode($cert['course_title']) ?>&organizationName=<?= urlencode('Hades Academy LMS') ?>&issueYear=<?= date('Y', strtotime($cert['issued_at'])) ?>&issueMonth=<?= date('n', strtotime($cert['issued_at'])) ?>&certUrl=<?= urlencode('http://127.0.0.1:8000/verify-certificate?code=' . $cert['certificate_code']) ?>&certId=<?= urlencode($cert['certificate_code']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-linkedin me-2 text-primary"></i> Thêm vào hồ sơ LinkedIn
        </a>
        <a href="/courses" class="btn btn-outline-secondary rounded-pill px-4">
            Quay lại trang chủ
        </a>
    </div>

    <div class="cert-container">
        <div class="cert-inner-border">
            <div class="mb-3">
                <i class="bi bi-award-fill display-3 text-warning"></i>
            </div>
            <div class="cert-title">CHỨNG CHỈ HOÀN THÀNH</div>
            <div class="cert-subtitle">Certificate of Course Completion</div>

            <p class="fs-5 text-muted mb-1">Chứng nhận học viên:</p>
            <div class="student-name"><?= e($cert['student_name']) ?></div>

            <p class="fs-6 text-muted mb-1">Đã hoàn thành xuất sắc 100% nội dung & bài kiểm tra của khóa học trực tuyến:</p>
            <div class="course-title"><?= e($cert['course_title']) ?></div>

            <div class="row align-items-end mt-5 pt-3">
                <div class="col-4 text-start">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= urlencode('http://127.0.0.1:8000/verify-certificate?code=' . $cert['certificate_code']) ?>" alt="QR Code" class="img-thumbnail" style="width: 90px; height: 90px;">
                    <div class="small text-muted mt-2 font-monospace">Mã: <?= e($cert['certificate_code']) ?></div>
                    <div class="small text-muted">Ngày cấp: <?= date('d/m/Y', strtotime($cert['issued_at'])) ?></div>
                </div>
                <div class="col-4 text-center">
                    <div class="badge bg-success bg-opacity-75 px-3 py-2 text-uppercase fs-6">
                        <i class="bi bi-patch-check"></i> Xác thực chuẩn LMS
                    </div>
                </div>
                <div class="col-4 text-end">
                    <div class="fw-bold fs-5 text-dark" style="font-family: system-ui;"><?= e($cert['instructor_name'] ?? 'Ban Giảng Huấn') ?></div>
                    <div class="small text-muted border-top border-dark pt-1 mt-1">Giảng viên / Giám đốc Đào tạo</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
