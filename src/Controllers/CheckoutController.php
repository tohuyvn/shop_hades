<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\UserVoucher;
use App\MoMo;
use App\OrderMailer;
use App\Services\ShippingEstimator;
use App\Services\StoreLocator;
use App\WalletService;

class CheckoutController
{
    public function index(): void
    {
        $cart = CartController::refreshedSessionCart();
        if (empty($cart)) {
            flash('error', 'Giỏ hàng trống.');
            redirect('/');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $preorderInfo = $this->calculatePreorderInfo($cart);

        view('client.checkout.index', [
            'cart' => $cart,
            'total' => $total,
            'user' => authUser(),
            'isMember' => authUser() !== null,
            'banks' => config('vnpay.banks', []),
            'walletBalance' => authUser() ? WalletService::balance((int) authUser()['id']) : 0,
            'userVouchers' => authUser() ? (new UserVoucher())->activeForUser((int) authUser()['id']) : [],
            'isPreorderOrder' => $preorderInfo['isPreorderOrder'],
            'totalRequiredDeposit' => $preorderInfo['totalRequiredDeposit'],
        ]);
    }

    public function placeOrder(): void
    {
        $cart = CartController::refreshedSessionCart();
        if (empty($cart)) {
            flash('error', 'Giỏ hàng trống.');
            redirect('/cart');
        }

        if (empty($_POST['contact_address']) && !empty($_POST['shipping_address'])) {
            $_POST['contact_address'] = $_POST['shipping_address'];
        }

        $paymentMethod = $_POST['payment_method'] ?? 'cash';
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $orderModel = new Order();
        $user = authUser();

        if (in_array($paymentMethod, ['vnpay', 'momo', 'installment'], true)) {
            if (!$user) {
                flash('error', 'Thanh toán online / trả góp chỉ dành cho thành viên. Vui lòng đăng nhập hoặc đăng ký.');
                redirect('/login');
            }
        }

        // Validate basic delivery info for all payment methods
        $requiredBase = ['customer_name', 'phone', 'email', 'contact_address', 'shipping_address'];
        foreach ($requiredBase as $field) {
            if (trim($_POST[$field] ?? '') === '') {
                setOld($_POST);
                flash('error', 'Vui lòng điền đầy đủ thông tin giao hàng.');
                redirect('/checkout');
            }
        }

        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setOld($_POST);
            flash('error', 'Email không hợp lệ.');
            redirect('/checkout');
        }

        $phone = trim($_POST['phone']);
        if (!preg_match('/^0\d{9,10}$/', $phone)) {
            setOld($_POST);
            flash('error', 'Số điện thoại không hợp lệ (10–11 số, bắt đầu bằng 0).');
            redirect('/checkout');
        }

        $shippingAddress = trim($_POST['shipping_address']);
        $shippingLat = $this->nullableFloat($_POST['shipping_lat'] ?? null);
        $shippingLng = $this->nullableFloat($_POST['shipping_lng'] ?? null);
        $userAddressId = null;

        if ($shippingLat === null && $user) {
            $db = \App\DB::connection();
            $stmtAddr = $db->prepare('
                SELECT ua.*
                FROM user_addresses ua
                WHERE ua.user_id = ?
                  AND ua.latitude IS NOT NULL
                  AND ua.longitude IS NOT NULL
                ORDER BY ua.is_default DESC, ua.id DESC
                LIMIT 1
            ');
            $stmtAddr->execute([$user['id']]);
            $closest = $stmtAddr->fetch();
            if ($closest) {
                $shippingLat = $closest['latitude'];
                $shippingLng = $closest['longitude'];
                $userAddressId = $closest['id'];
            }
        }

        if ($shippingLat === null && $shippingAddress !== '') {
            $loc = \App\GoongGeocodeService::geocode($shippingAddress);
            if ($loc) {
                $shippingLat = $loc['lat'];
                $shippingLng = $loc['lng'];
            }
        }

        $store = null;
        $shippingEstimate = (new ShippingEstimator())->estimate(null, $_POST['preferred_time_slot'] ?? null);
        if ($shippingLat !== null && $shippingLng !== null) {
            $store = (new StoreLocator())->nearest((float) $shippingLat, (float) $shippingLng, array_values($cart));
            $shippingEstimate = (new ShippingEstimator())->estimate($store['distance_km'] ?? null, $_POST['preferred_time_slot'] ?? null);
        }

        $totalBeforeDiscount = $subtotal + (int) $shippingEstimate['shipping_fee'];
        $discounts = $this->calculateDiscounts($totalBeforeDiscount, authUser());
        $total = $discounts['total'];
        $couponCode = $discounts['coupon_code'];
        $discountAmount = $discounts['discount_amount'];
        $walletAmount = $discounts['wallet_amount'];
        $userVoucherCode = $discounts['user_voucher_code'];
        $userVoucherId = $discounts['user_voucher_id'];

        $preorderInfo = $this->calculatePreorderInfo($cart);
        $isPreorderOrder = $preorderInfo['isPreorderOrder'];
        $totalRequiredDeposit = $preorderInfo['totalRequiredDeposit'];

        // Pre-order: charge deposit only (keep shipping if any)
        $fullTotal = $total;
        if ($isPreorderOrder && $totalRequiredDeposit > 0) {
            $shipFee = (int) $shippingEstimate['shipping_fee'];
            $total = min($total, $totalRequiredDeposit + $shipFee);
        }

        $vatRequired = !empty($_POST['vat_required']);
        // Hóa đơn được tạo từ thông tin khách đã nhập ở phần giao hàng.
        // Khách không phải khai báo lại một bộ thông tin VAT riêng.
        $vatCompanyName = $vatRequired ? trim($_POST['customer_name']) : null;
        $vatTaxCode = null;
        $vatAddress = $vatRequired ? $shippingAddress : null;
        $vatEmail = $vatRequired ? $email : null;

        $orderBase = [
            'reserve_stock'     => true,
            'user_id'           => $user['id'] ?? null,
            'total_amount'      => $total,
            'coupon_code'       => $couponCode !== '' ? $couponCode : null,
            'discount_amount'   => $discountAmount,
            'wallet_amount'     => $walletAmount,
            'user_voucher_code' => $userVoucherCode !== '' ? $userVoucherCode : null,
            'shipping_lat'      => $shippingLat,
            'shipping_lng'      => $shippingLng,
            'user_address_id'   => $userAddressId,
            'store_id'          => $store['id'] ?? null,
            'address_province'  => trim($_POST['address_province'] ?? '') ?: null,
            'address_district'  => trim($_POST['address_district'] ?? '') ?: null,
            'address_ward'      => trim($_POST['address_ward'] ?? '') ?: null,
            'address_street'    => trim($_POST['address_street'] ?? '') ?: null,
            'preferred_time_slot' => $shippingEstimate['preferred_time_slot'],
            'shipping_fee'      => (int) $shippingEstimate['shipping_fee'],
            'shipping_distance_km' => $shippingEstimate['distance_km'],
            'estimated_delivery_from' => $shippingEstimate['eta_from'],
            'estimated_delivery_to' => $shippingEstimate['eta_to'],
            'loyalty_discount_amount' => $discounts['loyalty_discount_amount'] ?? 0,
            'loyalty_tier'            => $discounts['loyalty_tier'] ?? 'Standard',
            'is_preorder'             => $isPreorderOrder ? 1 : 0,
            'preorder_deposit_paid'   => 0,
            'preorder_status'         => $isPreorderOrder ? ($totalRequiredDeposit > 0 ? 'pending_deposit' : 'confirmed') : null,
            'vat_company_name'        => $vatCompanyName,
            'vat_tax_code'            => $vatTaxCode,
            'vat_address'             => $vatAddress,
            'vat_email'               => $vatEmail,
            'vat_invoice_no'          => null,
            'vat_invoice_issued'      => 0,
        ];

        if ($paymentMethod === 'vnpay') {
            $requiredVnPay = ['bank', 'card_number', 'card_holder', 'issue_date'];
            foreach ($requiredVnPay as $field) {
                if (trim($_POST[$field] ?? '') === '') {
                    setOld($_POST);
                    flash('error', 'Vui lòng điền đầy đủ thông tin giao hàng và thẻ ngân hàng.');
                    redirect('/checkout');
                }
            }

            $issueDate = trim($_POST['issue_date']);
            if (!preg_match('/^\d{2}\/\d{2}$/', $issueDate)) {
                setOld($_POST);
                flash('error', 'Ngày phát hành phải có định dạng MM/YY (ví dụ: 07/15).');
                redirect('/checkout');
            }

            $bankCode = trim($_POST['bank']);
            $validBank = false;
            foreach (config('vnpay.banks', []) as $bank) {
                if (($bank['code'] ?? '') === $bankCode) {
                    $validBank = true;
                    break;
                }
            }
            if (!$validBank) {
                setOld($_POST);
                flash('error', 'Ngân hàng không hợp lệ.');
                redirect('/checkout');
            }

            $order = $this->createOrder($orderModel, array_merge($orderBase, [
                'customer_name' => trim($_POST['customer_name']),
                'phone' => $phone,
                'email' => $email,
                'contact_address' => trim($_POST['contact_address']),
                'shipping_address' => $shippingAddress,
                'payment_method' => 'vnpay',
                'payment_status' => 'pending',
            ]), array_values($cart));

            $this->finalizeWalletAndVoucher($user, $walletAmount, $userVoucherId, (int) $order['id']);
            \App\OrderStateService::logStatus((int)$order['id'], null, 'pending', 'customer', $user ? (int)$user['id'] : null, 'Tạo đơn hàng mới (Thanh toán qua VNPay)');

            $_SESSION['pending_vnpay_order_id'] = $order['id'];
            $_SESSION['vnpay_card'] = [
                'bank' => $bankCode,
                'bank_name' => vnpayBankName($bankCode),
                'card_number' => preg_replace('/\D+/', '', $_POST['card_number']),
                'cardholder' => strtoupper(trim($_POST['card_holder'])),
                'issue_date' => $issueDate,
            ];

            redirect('/payment/vnpay/pay');
        }

        if ($paymentMethod === 'momo') {
            if ($total > MoMo::MAX_AMOUNT) {
                setOld($_POST);
                flash('error', 'MoMo sandbox giới hạn tối đa ' . formatMoney(MoMo::MAX_AMOUNT) . '/giao dịch. Chọn VNPay hoặc sản phẩm giá thấp hơn.');
                redirect('/checkout');
            }

            $order = $this->createOrder($orderModel, array_merge($orderBase, [
                'customer_name' => trim($_POST['customer_name']),
                'phone' => $phone,
                'email' => $email,
                'contact_address' => trim($_POST['contact_address']),
                'shipping_address' => $shippingAddress,
                'payment_method' => 'momo',
                'payment_status' => 'pending',
            ]), array_values($cart));

            $this->finalizeWalletAndVoucher($user, $walletAmount, $userVoucherId, (int) $order['id']);
            \App\OrderStateService::logStatus((int)$order['id'], null, 'pending', 'customer', $user ? (int)$user['id'] : null, 'Tạo đơn hàng mới (Thanh toán qua MoMo)');

            $_SESSION['pending_momo_order_id'] = $order['id'];
            redirect('/payment/momo/pay');
        }

        if ($paymentMethod === 'bank_transfer') {
            $order = $this->createOrder($orderModel, array_merge($orderBase, [
                'customer_name' => trim($_POST['customer_name']),
                'phone' => $phone,
                'email' => $email,
                'contact_address' => trim($_POST['contact_address']),
                'shipping_address' => $shippingAddress,
                'payment_method' => 'bank_transfer',
                'payment_status' => 'pending',
                'status' => 'pending',
            ]), array_values($cart));

            $this->finalizeWalletAndVoucher($user, $walletAmount, $userVoucherId, (int) $order['id']);
            \App\OrderStateService::logStatus((int)$order['id'], null, 'pending', 'customer', $user ? (int)$user['id'] : null, 'Tạo đơn hàng mới (Chuyển khoản VietQR MB Bank)');

            OrderMailer::sendConfirmation(
                array_merge($order, ['email' => $email]),
                array_values($cart)
            );

            CartController::clearCart();
            $_SESSION['pending_vietqr_order_id'] = $order['id'];
            $_SESSION['last_order_id'] = $order['id'];
            redirect('/payment/vietqr/pay');
        }

        if ($paymentMethod === 'installment') {
            $months = (int) ($_POST['installment_months'] ?? 6);
            if (!in_array($months, [3, 6, 9, 12], true)) {
                $months = 6;
            }
            $deposit = (int) round($total * 0.2);

            $order = $this->createOrder($orderModel, array_merge($orderBase, [
                'customer_name' => trim($_POST['customer_name']),
                'phone' => $phone,
                'email' => $email,
                'contact_address' => trim($_POST['contact_address']),
                'shipping_address' => $shippingAddress,
                'payment_method' => 'installment',
                'payment_status' => 'pending',
                'status' => 'pending',
            ]), array_values($cart));

            $orderModel->update((int) $order['id'], [
                'installment_months' => $months,
                'installment_deposit_amount' => $deposit,
                'installment_status' => 'pending_review',
            ]);

            $this->finalizeWalletAndVoucher($user, $walletAmount, $userVoucherId, (int) $order['id']);
            \App\OrderStateService::logStatus((int)$order['id'], null, 'pending', 'customer', $user ? (int)$user['id'] : null, "Hồ sơ trả góp {$months} tháng, cọc " . $deposit);

            OrderMailer::sendConfirmation(
                array_merge($order, ['email' => $email]),
                array_values($cart)
            );

            CartController::clearCart();
            $_SESSION['last_order_id'] = $order['id'];
            flash('success', "Đã gửi hồ sơ trả góp {$months} tháng. Đặt cọc dự kiến: " . formatMoney($deposit) . '. Chờ admin duyệt.');
            redirect('/orders/success');
        }

        // Thanh toán tiền mặt (COD)
        $order = $this->createOrder($orderModel, array_merge($orderBase, [
            'customer_name'    => trim($_POST['customer_name']),
            'phone'            => $phone,
            'email'            => $email,
            'contact_address'  => trim($_POST['contact_address']),
            'shipping_address' => $shippingAddress,
            'payment_method'   => 'cash',
            'payment_status'   => $total <= 0 ? 'paid' : 'pending',
            'status'           => $total <= 0 ? 'confirmed' : 'pending',
        ]), array_values($cart));

        $this->finalizeWalletAndVoucher($user, $walletAmount, $userVoucherId, (int) $order['id']);
        if ($walletAmount > 0 || $total <= 0) {
            $order = $orderModel->find((int) $order['id']);
            if (($order['payment_status'] ?? '') === 'paid' && (int) ($order['is_preorder'] ?? 0) === 1) {
                $orderModel->update((int) $order['id'], [
                    'preorder_deposit_paid' => $order['total_amount'],
                    'preorder_status' => 'deposit_paid'
                ]);
            }
        }
        $initialStatus = $total <= 0 ? 'confirmed' : 'pending';
        \App\OrderStateService::logStatus((int)$order['id'], null, $initialStatus, 'customer', $user ? (int)$user['id'] : null, 'Tạo đơn hàng mới (COD)');

        // Gửi email xác nhận đơn hàng
        OrderMailer::sendConfirmation(
            array_merge($order, ['email' => $email]),
            array_values($cart)
        );

        CartController::clearCart();
        $_SESSION['last_order_id'] = $order['id'];
        redirect('/orders/success');
    }

    private function createOrder(Order $orderModel, array $orderData, array $cart): array
    {
        try {
            $order = $orderModel->create($orderData, $cart);
            if (!empty($order['vat_company_name'])) {
                $order = $orderModel->issueVatInvoice((int) $order['id']) ?? $order;
                $_SESSION['auto_print_invoice_order_id'] = (int) $order['id'];
            }
            return $order;
        } catch (\RuntimeException $e) {
            error_log('Checkout order creation failed: ' . $e->getMessage());
            setOld($_POST);
            $message = str_contains($e->getMessage(), 'Sản phẩm')
                || str_contains($e->getMessage(), 'Biến thể')
                ? $e->getMessage()
                : 'Không thể tạo đơn hàng lúc này. Vui lòng thử lại.';
            flash('error', $message);
            redirect('/checkout');
        }
    }

    private function calculatePreorderInfo(array $cart): array
    {
        if (empty($cart)) {
            return [
                'isPreorderOrder' => false,
                'totalRequiredDeposit' => 0,
            ];
        }

        $productIds = array_column($cart, 'product_id');
        if (empty($productIds)) {
            return [
                'isPreorderOrder' => false,
                'totalRequiredDeposit' => 0,
            ];
        }

        $db = \App\DB::connection();
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $stmtProd = $db->prepare("SELECT id, is_preorder, preorder_deposit FROM products WHERE id IN ($placeholders)");
        $stmtProd->execute($productIds);
        $productsData = $stmtProd->fetchAll(\PDO::FETCH_ASSOC);

        $productsMap = [];
        foreach ($productsData as $p) {
            $productsMap[$p['id']] = $p;
        }

        $isPreorderOrder = false;
        $totalRequiredDeposit = 0;

        foreach ($cart as $item) {
            $pData = $productsMap[$item['product_id']] ?? null;
            if ($pData && (int) ($pData['is_preorder'] ?? 0) === 1) {
                $isPreorderOrder = true;
                $dep = (int) ($pData['preorder_deposit'] ?? 0);
                if ($dep <= 0) {
                    $dep = (int) round(((int) $item['price'] * (int) $item['quantity']) * 0.1);
                } else {
                    $dep = $dep * (int) $item['quantity'];
                }
                $totalRequiredDeposit += $dep;
            }
        }

        return [
            'isPreorderOrder' => $isPreorderOrder,
            'totalRequiredDeposit' => $totalRequiredDeposit,
        ];
    }

    private function calculateDiscounts(int $subtotal, ?array $user): array
    {
        $total = $subtotal;
        $couponCode = trim($_POST['coupon_code'] ?? '');
        $discountAmount = 0;
        $walletAmount = 0;
        $userVoucherCode = '';
        $userVoucherId = null;

        if ($couponCode !== '') {
            $db = \App\DB::connection();
            $stmt = $db->prepare('SELECT * FROM promotions WHERE code = ? LIMIT 1');
            $stmt->execute([$couponCode]);
            $promo = $stmt->fetch();

            if ($promo && ($promo['status'] ?? 'active') === 'active' && $promo['expires_at'] >= date('Y-m-d')) {
                if (($promo['discount_type'] ?? 'percent') === 'fixed') {
                    $couponDiscount = (int) ($promo['discount_amount'] ?? 0);
                    $couponDiscount = min($couponDiscount, $total);
                } else {
                    $discountPercent = (int) $promo['discount_percent'];
                    $couponDiscount = (int) round(($total * $discountPercent) / 100);
                }
                $total -= $couponDiscount;
                $discountAmount += $couponDiscount;
            } else {
                $couponCode = '';
            }
        }

        $voucherInput = trim($_POST['user_voucher_code'] ?? '');
        if ($voucherInput !== '' && $user) {
            $voucherModel = new UserVoucher();
            $voucher = $voucherModel->findByCode($voucherInput);
            if ($voucher
                && (int) $voucher['user_id'] === (int) $user['id']
                && ($voucher['status'] ?? '') === 'active'
                && ($voucher['expires_at'] ?? '') >= date('Y-m-d')
            ) {
                $voucherDiscount = (int) round(($total * (int) $voucher['discount_percent']) / 100);
                if (!empty($voucher['max_discount'])) {
                    $voucherDiscount = min($voucherDiscount, (int) $voucher['max_discount']);
                }
                $total -= $voucherDiscount;
                $discountAmount += $voucherDiscount;
                $userVoucherCode = $voucher['code'];
                $userVoucherId = (int) $voucher['id'];
            }
        }

        $loyaltyDiscount = 0;
        $loyaltyTier = 'Standard';
        if ($user) {
            $db = \App\DB::connection();
            $stmt = $db->prepare('SELECT loyalty_tier FROM users WHERE id = ?');
            $stmt->execute([$user['id']]);
            $loyaltyTier = $stmt->fetchColumn() ?: 'Standard';

            $percent = \App\Services\LoyaltyService::getDiscountPercent($loyaltyTier);
            if ($percent > 0) {
                $loyaltyDiscount = (int) round(($total * $percent) / 100);
                $total -= $loyaltyDiscount;
                $discountAmount += $loyaltyDiscount;
            }
        }

        if (!empty($_POST['use_wallet']) && $user) {
            $balance = WalletService::balance((int) $user['id']);
            $walletAmount = min($balance, max(0, $total));
            $total -= $walletAmount;
        }

        if ($total < 0) {
            $total = 0;
        }

        return [
            'total'                   => $total,
            'coupon_code'             => $couponCode,
            'discount_amount'         => $discountAmount,
            'wallet_amount'           => $walletAmount,
            'user_voucher_code'       => $userVoucherCode,
            'user_voucher_id'         => $userVoucherId,
            'loyalty_discount_amount' => $loyaltyDiscount,
            'loyalty_tier'            => $loyaltyTier,
        ];
    }

    private function finalizeWalletAndVoucher(?array $user, int $walletAmount, ?int $userVoucherId, int $orderId): void
    {
        if ($walletAmount > 0 && $user) {
            if (!WalletService::debit((int) $user['id'], $walletAmount, 'order', $orderId, 'Thanh toán đơn #' . $orderId)) {
                flash('error', 'Không thể trừ Ví Hades. Vui lòng liên hệ hỗ trợ.');
            }
        }

        if ($userVoucherId && $user) {
            (new UserVoucher())->markUsed($userVoucherId, $orderId);
        }
    }

    private function nullableFloat(mixed $value): ?float
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        return is_numeric($value) ? (float) $value : null;
    }
}
