<?php

namespace App;

use App\Models\Order;

class PaymentService
{
    /**
     * Đánh dấu đơn đã thanh toán (idempotent — không xử lý lại nếu đã paid).
     *
     * @return array{ok:bool, already_paid:bool, order:?array}
     */
    public static function markPaid(int $orderId, array $extraFields = [], bool $sendEmail = true): array
    {
        $orderModel = new Order();
        $order = $orderModel->find($orderId);

        if (!$order) {
            return ['ok' => false, 'already_paid' => false, 'order' => null];
        }

        if (($order['payment_status'] ?? '') === 'paid') {
            return ['ok' => true, 'already_paid' => true, 'order' => $order];
        }

        $fields = array_merge([
            'payment_status' => 'paid',
            'status'         => ($order['status'] ?? 'pending') === 'pending' ? 'confirmed' : $order['status'],
        ], $extraFields);

        if ((int) ($order['is_preorder'] ?? 0) === 1) {
            $fields['preorder_deposit_paid'] = $order['total_amount'];
            $fields['preorder_status'] = 'deposit_paid';
        }

        $updated = $orderModel->update($orderId, $fields);

        // Tự động kích hoạt ghi danh LMS nếu đơn hàng chứa khóa học
        try {
            $db = \App\DB::connection();
            $stmt = $db->prepare('SELECT course_id FROM order_items WHERE order_id = ? AND course_id IS NOT NULL');
            $stmt->execute([$orderId]);
            $courseIds = $stmt->fetchAll(\PDO::FETCH_COLUMN);
            if (!empty($courseIds) && !empty($order['user_id'])) {
                $enrollModel = new \App\Models\Enrollment();
                foreach ($courseIds as $cId) {
                    $enrollModel->enroll((int) $order['user_id'], (int) $cId);
                }
            }
        } catch (\Throwable $e) {
            error_log('LMS Auto-enroll on markPaid: ' . $e->getMessage());
        }

        if ($sendEmail && $updated && !empty($updated['email'])) {
            $items = self::itemsForEmail($orderModel, $orderId);
            OrderMailer::sendConfirmation($updated, $items);
        }

        return ['ok' => true, 'already_paid' => false, 'order' => $updated];
    }

    /** @return array<int, array{name:string, quantity:int, price:int}> */
    private static function itemsForEmail(Order $orderModel, int $orderId): array
    {
        return array_map(
            static fn (array $item): array => [
                'name'     => $item['product_name'] ?? 'Sản phẩm',
                'quantity' => (int) ($item['quantity'] ?? 1),
                'price'    => (int) ($item['price'] ?? 0),
            ],
            $orderModel->itemsWithProduct($orderId)
        );
    }
}
