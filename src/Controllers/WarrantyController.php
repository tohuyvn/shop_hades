<?php

namespace App\Controllers;

use App\DB;

class WarrantyController
{
    public function index(): void
    {
        $query = trim($_GET['q'] ?? '');
        $results = null;
 
        if ($query !== '') {
            $db = DB::connection();
            $stmt = $db->prepare("
                SELECT w.*, COALESCE(p.name, 'Điện thoại chính hãng Hades') AS product_name, p.image AS product_image, o.order_code
                FROM warranties w 
                LEFT JOIN products p ON p.id = w.product_id
                LEFT JOIN orders o ON o.id = w.order_id
                WHERE w.customer_phone = ? OR w.warranty_code = ? OR w.imei = ? OR o.order_code = ?
                ORDER BY w.created_at DESC
            ");
            $stmt->execute([$query, $query, $query, $query]);
            $warranties = $stmt->fetchAll(\PDO::FETCH_ASSOC);
 
            $results = [];
            foreach ($warranties as $w) {
                $claimStmt = $db->prepare("SELECT * FROM warranty_claims WHERE warranty_id = ? ORDER BY created_at DESC");
                $claimStmt->execute([$w['id']]);
                $w['claims'] = $claimStmt->fetchAll(\PDO::FETCH_ASSOC);
                $results[] = $w;
            }
        }
 
        view('client.warranty.index', [
            'title' => 'Tra cứu Bảo hành Điện tử',
            'results' => $results,
            'query' => $query
        ]);
    }
 
    public function lookup(): void
    {
        $query = trim($_POST['query'] ?? $_GET['q'] ?? '');

        if ($query === '') {
            flash('error', 'Vui lòng nhập số điện thoại, mã đơn, mã bảo hành hoặc IMEI.');
            redirect('/warranty');
        }

        // Chuẩn hóa về GET để bookmark / chia sẻ kết quả
        redirect('/warranty?q=' . urlencode($query));
    }

    public function claim(): void
    {
        $warrantyId = (int)($_POST['warranty_id'] ?? 0);
        $customerNote = trim($_POST['customer_note'] ?? '');
        $query = trim($_POST['query'] ?? '');

        if ($warrantyId <= 0 || $customerNote === '') {
            flash('error', 'Vui lòng nhập đầy đủ mô tả lỗi sản phẩm.');
            redirect('/warranty');
        }

        $db = DB::connection();
        $stmt = $db->prepare("SELECT * FROM warranties WHERE id = ?");
        $stmt->execute([$warrantyId]);
        $warranty = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$warranty) {
            flash('error', 'Không tìm thấy thông tin bảo hành.');
            redirect('/warranty');
        }

        if ($warranty['status'] !== 'active') {
            flash('error', 'Sản phẩm đã hết hạn bảo hành hoặc bảo hành đã bị hủy.');
            redirect('/warranty');
        }

        $claimStmt = $db->prepare("INSERT INTO warranty_claims (warranty_id, customer_note, status) VALUES (?, ?, 'pending')");
        $claimStmt->execute([$warrantyId, $customerNote]);

        flash('success', 'Gửi yêu cầu bảo hành thành công! Chúng tôi sẽ liên hệ lại sớm nhất.');
        
        if ($query !== '') {
            redirect('/warranty?q=' . urlencode($query));
        } else {
            redirect('/warranty');
        }
    }
}
