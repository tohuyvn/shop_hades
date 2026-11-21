<?php

namespace App\Controllers;

use App\Models\Certificate;

class CertificateController
{
    private Certificate $certModel;

    public function __construct()
    {
        $this->certModel = new Certificate();
    }

    public function show(string $code): void
    {
        $cert = $this->certModel->findByCode($code);
        if (!$cert) {
            http_response_code(404);
            flash('error', 'Chứng chỉ không tồn tại hoặc mã tra cứu không hợp lệ.');
            redirect('/verify-certificate');
        }

        view('client.certificates.show', [
            'cert' => $cert,
            'title' => 'Chứng chỉ hoàn thành: ' . $cert['course_title'],
        ], 'layouts.blank');
    }

    public function verify(): void
    {
        $code = trim($_GET['code'] ?? ($_POST['code'] ?? ''));
        $cert = null;
        $searched = false;

        if ($code !== '') {
            $searched = true;
            $cert = $this->certModel->findByCode($code);
        }

        view('client.certificates.verify', [
            'code' => $code,
            'cert' => $cert,
            'searched' => $searched,
            'title' => 'Tra cứu Xác thực Chứng chỉ LMS',
        ]);
    }
}
