<?php

namespace App\Controllers;

use App\Models\Order;
use App\PaymentService;
use App\Vnpay;

class VnpayController
{
    public function pay(): void
    {
        if (!authUser()) {
            flash('error', 'Thanh toán VNPay chỉ dành cho thành viên đã đăng nhập.');
            redirect('/login');
        }

        $orderId = (int) ($_SESSION['pending_vnpay_order_id'] ?? 0);
        $orderModel = new Order();
        $order = $orderId > 0 ? $orderModel->find($orderId) : null;

        if (!$order || ($order['payment_method'] ?? '') !== 'vnpay' || ($order['payment_status'] ?? '') === 'paid') {
            flash('error', 'Không tìm thấy đơn hàng cần thanh toán.');
            redirect(!empty($_SESSION['lms_pending_course_id']) ? '/courses/' . (int)$_SESSION['lms_pending_course_id'] . '/checkout' : '/cart');
        }

        $card = $_SESSION['vnpay_card'] ?? config('vnpay.test_card');

        view('client.payment.vnpay_pay', [
            'order' => $order,
            'card' => $card,
            'items' => $orderModel->itemsWithProduct($orderId),
        ]);
    }

    public function process(): void
    {
        if (!authUser()) {
            flash('error', 'Thanh toán VNPay chỉ dành cho thành viên đã đăng nhập.');
            redirect('/login');
        }

        $orderId = (int) ($_SESSION['pending_vnpay_order_id'] ?? 0);
        $orderModel = new Order();
        $order = $orderId > 0 ? $orderModel->find($orderId) : null;

        if (!$order || ($order['payment_method'] ?? '') !== 'vnpay') {
            flash('error', 'Đơn hàng không hợp lệ.');
            redirect(!empty($_SESSION['lms_pending_course_id']) ? '/courses/' . (int)$_SESSION['lms_pending_course_id'] . '/checkout' : '/cart');
        }

        $test = config('vnpay.test_card');
        $cardNumber = preg_replace('/\D+/', '', $_POST['card_number'] ?? '');
        $cardHolder = strtoupper(trim($_POST['card_holder'] ?? ''));
        $issueDate = trim($_POST['issue_date'] ?? '');
        $otp = trim($_POST['otp'] ?? '');

        $testCardNum = $test['card_number'] ?? '9704198526191432198';
        $validNumbers = [$testCardNum, '9704198526191432', '9704198526191432198'];
        if (!in_array($cardNumber, $validNumbers, true)) {
            flash('error', 'Số thẻ không đúng. Dùng thẻ test NCB: ' . $testCardNum);
            redirect('/payment/vnpay/pay');
        }

        if ($cardHolder !== strtoupper($test['cardholder'])) {
            flash('error', 'Tên chủ thẻ không đúng. Dùng: ' . $test['cardholder']);
            redirect('/payment/vnpay/pay');
        }

        if ($issueDate !== $test['issue_date']) {
            flash('error', 'Ngày phát hành không đúng. Dùng: ' . $test['issue_date']);
            redirect('/payment/vnpay/pay');
        }

        if ($otp !== $test['otp']) {
            flash('error', 'Mã OTP không đúng. Dùng OTP test: ' . $test['otp']);
            redirect('/payment/vnpay/pay');
        }

        $txnRef = $orderId . '_' . time();
        $payDate = date('YmdHis');

        $result = PaymentService::markPaid($orderId, [
            'vnpay_txn_ref' => $txnRef,
            'vnpay_transaction_no' => (string) random_int(10000000, 99999999),
            'vnpay_bank_code' => $test['bank'],
            'vnpay_pay_date' => $payDate,
        ]);

        if (!$result['ok']) {
            flash('error', 'Không thể xác nhận thanh toán.');
            redirect('/payment/vnpay/pay');
        }

        unset($_SESSION['cart'], $_SESSION['pending_vnpay_order_id'], $_SESSION['vnpay_card']);
        $_SESSION['last_order_id'] = $orderId;
        $_SESSION['vnpay_response'] = [
            'txn_ref' => $txnRef,
            'amount' => (int) $order['total_amount'] * 100,
            'order_info' => 'Thanh toan don hang #' . $orderId,
            'response_code' => '00',
            'transaction_no' => (string) random_int(10000000, 99999999),
            'bank_code' => $test['bank'],
            'pay_date' => $payDate,
            'secure_hash_valid' => true,
        ];

        flash('success', $result['already_paid'] ? 'Đơn hàng đã được thanh toán.' : 'Thanh toán VNPay thành công!');
        redirect('/orders/success');
    }

    public function gateway(): void
    {
        if (!authUser()) {
            flash('error', 'Thanh toán VNPay chỉ dành cho thành viên đã đăng nhập.');
            redirect('/login');
        }

        $orderId = (int) ($_SESSION['pending_vnpay_order_id'] ?? 0);
        $orderModel = new Order();
        $order = $orderId > 0 ? $orderModel->find($orderId) : null;

        if (!$order || ($order['payment_method'] ?? '') !== 'vnpay') {
            flash('error', 'Không tìm thấy đơn hàng cần thanh toán.');
            redirect(!empty($_SESSION['lms_pending_course_id']) ? '/courses/' . (int)$_SESSION['lms_pending_course_id'] . '/checkout' : '/cart');
        }

        $bankCode = config('vnpay.default_bank_code', 'NCB');
        $card = $_SESSION['vnpay_card'] ?? null;
        if (!empty($card['bank'])) {
            $bankCode = $card['bank'];
        }

        redirect(Vnpay::createPaymentUrl($order, $bankCode));
    }

    public function return(): void
    {
        $input = $_GET;
        $orderModel = new Order();

        if (!Vnpay::verifyReturn($input)) {
            flash('error', 'Xác thực thanh toán VNPay thất bại.');
            redirect(!empty($_SESSION['lms_pending_course_id']) ? '/courses/' . (int)$_SESSION['lms_pending_course_id'] . '/checkout' : '/cart');
        }

        if (($input['vnp_ResponseCode'] ?? '') !== '00') {
            flash('error', 'Thanh toán VNPay không thành công.');
            redirect(!empty($_SESSION['lms_pending_course_id']) ? '/courses/' . (int)$_SESSION['lms_pending_course_id'] . '/checkout' : '/cart');
        }

        $txnRef = explode('_', $input['vnp_TxnRef'] ?? '')[0];
        $orderId = (int) $txnRef;
        $order = $orderModel->find($orderId);

        if ($order && ($order['payment_method'] ?? '') === 'vnpay') {
            PaymentService::markPaid($orderId, [
                'vnpay_txn_ref' => $input['vnp_TxnRef'] ?? '',
                'vnpay_transaction_no' => $input['vnp_TransactionNo'] ?? '',
                'vnpay_bank_code' => $input['vnp_BankCode'] ?? '',
                'vnpay_pay_date' => $input['vnp_PayDate'] ?? '',
            ]);
        }

        unset($_SESSION['cart'], $_SESSION['pending_vnpay_order_id'], $_SESSION['vnpay_card']);
        $_SESSION['last_order_id'] = $orderId;
        $_SESSION['vnpay_response'] = [
            'txn_ref' => $input['vnp_TxnRef'] ?? '',
            'amount' => $input['vnp_Amount'] ?? '',
            'order_info' => $input['vnp_OrderInfo'] ?? '',
            'response_code' => $input['vnp_ResponseCode'] ?? '',
            'transaction_no' => $input['vnp_TransactionNo'] ?? '',
            'bank_code' => $input['vnp_BankCode'] ?? '',
            'pay_date' => $input['vnp_PayDate'] ?? '',
            'secure_hash_valid' => true,
        ];
        flash('success', 'Thanh toán VNPay thành công!');
        redirect('/orders/success');
    }
}
