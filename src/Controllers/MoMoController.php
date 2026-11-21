<?php

namespace App\Controllers;

use App\Models\Order;
use App\MoMo;
use App\PaymentService;

class MoMoController
{
    public function return(): void
    {
        $input = $_GET;
        if (!empty($input['signature']) && !MoMo::verifyCallback($input)) {
            flash('error', 'Chữ ký MoMo không hợp lệ.');
            redirect('/cart');
        }

        $orderId = MoMo::resolveOrderId($input);
        $resultCode = (int) ($input['resultCode'] ?? -1);
        $success = $resultCode === 0;

        if (!$success || $orderId <= 0) {
            flash('error', 'Thanh toán MoMo không thành công.');
            redirect(!empty($_SESSION['lms_pending_course_id']) ? '/courses/' . (int)$_SESSION['lms_pending_course_id'] . '/checkout' : '/cart');
        }

        $orderModel = new Order();
        $order = $orderModel->find($orderId);
        if (!$order || ($order['payment_method'] ?? '') !== 'momo') {
            flash('error', 'Đơn hàng không hợp lệ.');
            redirect(!empty($_SESSION['lms_pending_course_id']) ? '/courses/' . (int)$_SESSION['lms_pending_course_id'] . '/checkout' : '/cart');
        }

        $result = PaymentService::markPaid($orderId);
        unset($_SESSION['cart'], $_SESSION['pending_momo_order_id']);
        $_SESSION['last_order_id'] = $orderId;
        flash('success', $result['already_paid'] ? 'Đơn hàng đã được thanh toán.' : 'Thanh toán MoMo thành công!');
        redirect('/orders/success');
    }

    public function callback(): void
    {
        $payload = json_decode(file_get_contents('php://input'), true) ?? [];

        if (!MoMo::verifyCallback($payload)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'invalid signature']);
            return;
        }

        $orderId = MoMo::resolveOrderId($payload);
        $success = (int) ($payload['resultCode'] ?? -1) === 0;

        if ($orderId > 0 && $success) {
            $orderModel = new Order();
            $order = $orderModel->find($orderId);
            if ($order && ($order['payment_method'] ?? '') === 'momo') {
                PaymentService::markPaid($orderId);
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['status' => 'ok']);
    }

    public function pay(): void
    {
        if (!authUser()) {
            flash('error', 'Thanh toán MoMo chỉ dành cho thành viên đã đăng nhập.');
            redirect('/login');
        }

        $orderId = (int) ($_SESSION['pending_momo_order_id'] ?? 0);
        $orderModel = new Order();
        $order = $orderId > 0 ? $orderModel->find($orderId) : null;

        if (!$order || ($order['payment_method'] ?? '') !== 'momo' || ($order['payment_status'] ?? '') === 'paid') {
            flash('error', 'Không tìm thấy đơn hàng cần thanh toán.');
            redirect(!empty($_SESSION['lms_pending_course_id']) ? '/courses/' . (int)$_SESSION['lms_pending_course_id'] . '/checkout' : '/cart');
        }

        $momoData = MoMo::createPaymentUrl($order);
        $payUrl = $momoData ? $momoData['payUrl'] : false;

        view('client.payment.momo_pay', [
            'order' => $order,
            'items' => $orderModel->itemsWithProduct($orderId),
            'payUrl' => $payUrl,
            'momoData' => $momoData,
        ]);
    }

    public function process(): void
    {
        if (!authUser()) {
            flash('error', 'Thanh toán MoMo chỉ dành cho thành viên đã đăng nhập.');
            redirect('/login');
        }

        $orderId = (int) ($_SESSION['pending_momo_order_id'] ?? 0);
        $orderModel = new Order();
        $order = $orderId > 0 ? $orderModel->find($orderId) : null;

        if (!$order || ($order['payment_method'] ?? '') !== 'momo') {
            flash('error', 'Đơn hàng không hợp lệ.');
            redirect(!empty($_SESSION['lms_pending_course_id']) ? '/courses/' . (int)$_SESSION['lms_pending_course_id'] . '/checkout' : '/cart');
        }

        $result = PaymentService::markPaid($orderId);
        if (!$result['ok']) {
            flash('error', 'Không thể xác nhận thanh toán.');
            redirect('/payment/momo/pay');
        }

        unset($_SESSION['cart'], $_SESSION['pending_momo_order_id']);
        $_SESSION['last_order_id'] = $orderId;

        flash('success', $result['already_paid'] ? 'Đơn hàng đã được thanh toán.' : 'Thanh toán MoMo thành công!');
        redirect('/orders/success');
    }

    public function gateway(): void
    {
        if (!authUser()) {
            flash('error', 'Thanh toán MoMo chỉ dành cho thành viên đã đăng nhập.');
            redirect('/login');
        }

        $orderId = (int) ($_SESSION['pending_momo_order_id'] ?? 0);
        $orderModel = new Order();
        $order = $orderId > 0 ? $orderModel->find($orderId) : null;

        if (!$order || ($order['payment_method'] ?? '') !== 'momo') {
            flash('error', 'Không tìm thấy đơn hàng cần thanh toán.');
            redirect(!empty($_SESSION['lms_pending_course_id']) ? '/courses/' . (int)$_SESSION['lms_pending_course_id'] . '/checkout' : '/cart');
        }

        $momoData = MoMo::createPaymentUrl($order);
        if (!$momoData || empty($momoData['payUrl'])) {
            flash('error', 'Không tạo được link MoMo. Kiểm tra storage/logs/momo.log hoặc thử đơn dưới 20 triệu.');
            redirect('/payment/momo/pay');
        }

        redirect($momoData['payUrl']);
    }

    public function status(): void
    {
        $orderId = (int) ($_GET['order_id'] ?? 0);
        if ($orderId <= 0) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid order ID']);
            return;
        }

        $orderModel = new Order();
        $order = $orderModel->find($orderId);
        if (!$order) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Order not found']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'payment_status' => $order['payment_status'] ?? 'pending',
            'status' => $order['status'] ?? 'pending',
        ]);
    }
}
