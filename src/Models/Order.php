<?php

namespace App\Models;

use App\DB;
use PDO;

class Order
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DB::connection();
    }

    public function findByUserId(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
        $stmt->execute([$userId]);

        return array_map([$this, 'cast'], $stmt->fetchAll());
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ? $this->cast($row) : null;
    }

    public function findByCode(string $code): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE order_code = ? LIMIT 1');
        $stmt->execute([$code]);
        $row = $stmt->fetch();

        return $row ? $this->cast($row) : null;
    }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM orders ORDER BY created_at DESC');

        return array_map([$this, 'cast'], $stmt->fetchAll());
    }

    public function paidOrders(): array
    {
        $stmt = $this->db->query("SELECT * FROM orders WHERE payment_status = 'paid' ORDER BY created_at DESC");

        return array_map([$this, 'cast'], $stmt->fetchAll());
    }

    public function paymentStats(): array
    {
        $orders = $this->all();
        $paid = $this->paidOrders();
        $pending = array_values(array_filter(
            $orders,
            fn($order) => ($order['payment_status'] ?? '') === 'pending'
        ));

        $totalPaid = array_sum(array_map(fn($o) => (int) ($o['total_amount'] ?? 0), $paid));
        $totalPending = array_sum(array_map(fn($o) => (int) ($o['total_amount'] ?? 0), $pending));

        $byMethod = ['vnpay' => 0, 'momo' => 0, 'cash' => 0];
        $countByMethod = ['vnpay' => 0, 'momo' => 0, 'cash' => 0];
        foreach ($paid as $order) {
            $method = $order['payment_method'] ?? 'cash';
            if (!isset($byMethod[$method])) {
                $method = 'cash';
            }
            $byMethod[$method] += (int) ($order['total_amount'] ?? 0);
            $countByMethod[$method]++;
        }

        $byDay = [];
        $dayLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $byDay[$date] = 0;
            $dayLabels[] = date('d/m', strtotime($date));
        }
        foreach ($paid as $order) {
            $date = substr($order['created_at'] ?? '', 0, 10);
            if (isset($byDay[$date])) {
                $byDay[$date] += (int) ($order['total_amount'] ?? 0);
            }
        }

        $byBank = [];
        foreach ($paid as $order) {
            if (($order['payment_method'] ?? '') !== 'vnpay') {
                continue;
            }
            $bank = vnpayBankName($order['vnpay_bank_code'] ?? 'Khác');
            $byBank[$bank] = ($byBank[$bank] ?? 0) + 1;
        }

        return [
            'total_paid' => $totalPaid,
            'total_pending' => $totalPending,
            'paid_count' => count($paid),
            'pending_count' => count($pending),
            'total_orders' => count($orders),
            'by_method_amount' => $byMethod,
            'by_method_count' => $countByMethod,
            'by_day_labels' => $dayLabels,
            'by_day_values' => array_values($byDay),
            'by_bank_labels' => array_keys($byBank),
            'by_bank_values' => array_values($byBank),
        ];
    }

    public static function generateCode(): string
    {
        $dateStr = date('ymd');
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomChars = '';
        for ($i = 0; $i < 5; $i++) {
            $randomChars .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return "HD{$dateStr}-{$randomChars}";
    }

    public function generateUniqueCode(): string
    {
        do {
            $code = self::generateCode();
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM orders WHERE order_code = ?');
            $stmt->execute([$code]);
            $exists = (int) $stmt->fetchColumn() > 0;
        } while ($exists);

        return $code;
    }

    public function create(array $orderData, array $cartItems): array
    {
        $this->db->beginTransaction();

        try {
            if (!empty($orderData['reserve_stock'])) {
                $this->reserveStock($cartItems);
            }

            $orderCode = $orderData['order_code'] ?? $this->generateUniqueCode();
            $stmt = $this->db->prepare(
                'INSERT INTO orders (user_id, customer_name, phone, email, contact_address, shipping_address,
                 payment_method, payment_status, total_amount, status, coupon_code, discount_amount,
                 order_code, wallet_amount, user_voucher_code, created_at, shipping_lat, shipping_lng, user_address_id,
                 store_id, address_province, address_district, address_ward, address_street, preferred_time_slot,
                 shipping_fee, shipping_distance_km, estimated_delivery_from, estimated_delivery_to,
                  loyalty_discount_amount, loyalty_tier, earned_points, points_awarded,
                  is_preorder, preorder_deposit_paid, preorder_status,
                  vat_company_name, vat_tax_code, vat_address, vat_email, vat_invoice_no, vat_invoice_issued,
                  stock_reserved)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $createdAt = date('Y-m-d H:i:s');
            $stmt->execute([
                $orderData['user_id'] ?? null,
                $orderData['customer_name'],
                $orderData['phone'] ?? '',
                $orderData['email'],
                $orderData['contact_address'] ?? '',
                $orderData['shipping_address'] ?? '',
                $orderData['payment_method'] ?? 'cash',
                $orderData['payment_status'] ?? 'pending',
                (int) $orderData['total_amount'],
                $orderData['status'] ?? 'pending',
                $orderData['coupon_code'] ?? null,
                (int) ($orderData['discount_amount'] ?? 0),
                $orderCode,
                (int) ($orderData['wallet_amount'] ?? 0),
                $orderData['user_voucher_code'] ?? null,
                $createdAt,
                isset($orderData['shipping_lat']) ? (float)$orderData['shipping_lat'] : null,
                isset($orderData['shipping_lng']) ? (float)$orderData['shipping_lng'] : null,
                isset($orderData['user_address_id']) ? (int)$orderData['user_address_id'] : null,
                isset($orderData['store_id']) ? (int)$orderData['store_id'] : null,
                $orderData['address_province'] ?? null,
                $orderData['address_district'] ?? null,
                $orderData['address_ward'] ?? null,
                $orderData['address_street'] ?? null,
                $orderData['preferred_time_slot'] ?? null,
                (int) ($orderData['shipping_fee'] ?? 0),
                isset($orderData['shipping_distance_km']) ? (float)$orderData['shipping_distance_km'] : null,
                $orderData['estimated_delivery_from'] ?? null,
                $orderData['estimated_delivery_to'] ?? null,
                (int) ($orderData['loyalty_discount_amount'] ?? 0),
                $orderData['loyalty_tier'] ?? 'Standard',
                (int) ($orderData['earned_points'] ?? 0),
                (int) ($orderData['points_awarded'] ?? 0),
                (int) ($orderData['is_preorder'] ?? 0),
                (int) ($orderData['preorder_deposit_paid'] ?? 0),
                $orderData['preorder_status'] ?? null,
                $orderData['vat_company_name'] ?? null,
                $orderData['vat_tax_code'] ?? null,
                $orderData['vat_address'] ?? null,
                $orderData['vat_email'] ?? null,
                $orderData['vat_invoice_no'] ?? null,
                (int) ($orderData['vat_invoice_issued'] ?? 0),
                !empty($orderData['reserve_stock']) ? 1 : 0,
            ]);

            $orderId = (int) $this->db->lastInsertId();

            $hasVariantCol = false;
            try {
                $colCheck = $this->db->query("SHOW COLUMNS FROM order_items LIKE 'variant_id'");
                $hasVariantCol = (bool) $colCheck->fetch();
            } catch (\Throwable) {
            }

            if ($hasVariantCol) {
                $itemStmt = $this->db->prepare(
                    'INSERT INTO order_items (order_id, product_id, product_name, quantity, price, variant_id) VALUES (?, ?, ?, ?, ?, ?)'
                );
                foreach ($cartItems as $item) {
                    $itemStmt->execute([
                        $orderId,
                        (int) $item['product_id'],
                        $item['name'],
                        (int) $item['quantity'],
                        (int) $item['price'],
                        !empty($item['variant_id']) ? (int) $item['variant_id'] : null,
                    ]);
                }
            } else {
                $itemStmt = $this->db->prepare(
                    'INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)'
                );
                foreach ($cartItems as $item) {
                    $itemStmt->execute([
                        $orderId,
                        (int) $item['product_id'],
                        $item['name'],
                        (int) $item['quantity'],
                        (int) $item['price'],
                    ]);
                }
            }

            $this->db->commit();

            $order = $this->find($orderId);
            if ($order && ($order['payment_status'] === 'paid' || $order['status'] === 'completed')) {
                \App\Services\LoyaltyService::awardPointsForOrder($orderId);
                $order = $this->find($orderId);
            }

            return $order;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function reserveStock(array $cartItems): void
    {
        $productQuantities = [];
        $variantQuantities = [];

        foreach ($cartItems as $item) {
            $productId = (int) ($item['product_id'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 0);
            if ($productId <= 0 || $quantity <= 0) {
                throw new \RuntimeException('Sản phẩm hoặc số lượng không hợp lệ.');
            }

            $productQuantities[$productId] = ($productQuantities[$productId] ?? 0) + $quantity;
            $variantId = (int) ($item['variant_id'] ?? 0);
            if ($variantId > 0) {
                $variantQuantities[$variantId] ??= ['product_id' => $productId, 'quantity' => 0];
                if ($variantQuantities[$variantId]['product_id'] !== $productId) {
                    throw new \RuntimeException('Biến thể sản phẩm không hợp lệ.');
                }
                $variantQuantities[$variantId]['quantity'] += $quantity;
            }
        }

        $productLockStmt = $this->db->prepare(
            'SELECT stock, status, is_preorder FROM products WHERE id = ? FOR UPDATE'
        );
        $productUpdateStmt = $this->db->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
        $preorderProducts = [];
        foreach ($productQuantities as $productId => $quantity) {
            $productLockStmt->execute([$productId]);
            $product = $productLockStmt->fetch();
            if (!$product || ($product['status'] ?? '') !== 'active') {
                throw new \RuntimeException('Sản phẩm không còn hoạt động.');
            }

            $preorderProducts[$productId] = (int) ($product['is_preorder'] ?? 0) === 1;
            if ($preorderProducts[$productId]) {
                continue;
            }
            if ((int) $product['stock'] < $quantity) {
                throw new \RuntimeException('Sản phẩm đã hết hàng hoặc không đủ số lượng.');
            }
            $productUpdateStmt->execute([$quantity, $productId]);
        }

        if ($variantQuantities !== []) {
            $variantLockStmt = $this->db->prepare(
                'SELECT product_id, stock, status FROM product_variants WHERE id = ? FOR UPDATE'
            );
            $variantUpdateStmt = $this->db->prepare('UPDATE product_variants SET stock = stock - ? WHERE id = ?');
            foreach ($variantQuantities as $variantId => $item) {
                if ($preorderProducts[$item['product_id']] ?? false) {
                    continue;
                }
                $variantLockStmt->execute([$variantId]);
                $variant = $variantLockStmt->fetch();
                if (!$variant
                    || (int) $variant['product_id'] !== $item['product_id']
                    || ($variant['status'] ?? '') !== 'active'
                    || (int) $variant['stock'] < $item['quantity']
                ) {
                    throw new \RuntimeException('Biến thể sản phẩm đã hết hàng hoặc không đủ số lượng.');
                }
                $variantUpdateStmt->execute([$item['quantity'], $variantId]);
            }
        }
    }

    public function update(int $id, array $fields): ?array
    {
        $allowed = [
            'payment_status', 'status', 'vnpay_txn_ref', 'vnpay_transaction_no',
            'vnpay_bank_code', 'vnpay_pay_date', 'customer_name', 'phone', 'email',
            'contact_address', 'shipping_address', 'wallet_amount', 'user_voucher_code',
            'shipper_id', 'shipping_lat', 'shipping_lng', 'user_address_id',
            'store_id', 'address_province', 'address_district', 'address_ward',
            'address_street', 'preferred_time_slot', 'shipping_fee', 'shipping_distance_km',
            'estimated_delivery_from', 'estimated_delivery_to',
            'loyalty_discount_amount', 'loyalty_tier', 'earned_points', 'points_awarded',
            'delivery_proof_path', 'shipper_assigned_at',
            'installment_months', 'installment_deposit_amount', 'installment_status',
            'is_preorder', 'preorder_deposit_paid', 'preorder_status',
            'vat_company_name', 'vat_tax_code', 'vat_address', 'vat_email', 'vat_invoice_no', 'vat_invoice_issued', 'vat_invoice_issued_at',
        ];

        $sets = [];
        $values = [];
        foreach ($fields as $key => $value) {
            if (in_array($key, $allowed, true)) {
                $sets[] = "{$key} = ?";
                $values[] = $value;
            }
        }

        if ($sets === []) {
            return $this->find($id);
        }

        $values[] = $id;
        $sql = 'UPDATE orders SET ' . implode(', ', $sets) . ' WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);

        $order = $this->find($id);
        if ($order) {
            if (($order['payment_status'] === 'paid' || $order['status'] === 'completed') && (int)$order['points_awarded'] === 0) {
                \App\Services\LoyaltyService::awardPointsForOrder($id);
                $order = $this->find($id);
            } elseif (in_array($order['status'], ['cancelled', 'refunded']) && (int)$order['points_awarded'] === 1) {
                \App\Services\LoyaltyService::revertPointsForOrder($id);
                $order = $this->find($id);
            }
        }

        return $order;
    }

    public function issueVatInvoice(int $id): ?array
    {
        $order = $this->find($id);
        if (!$order || empty($order['vat_company_name'])) {
            return $order;
        }
        if (!empty($order['vat_invoice_issued'])) {
            return $order;
        }

        $invoiceNo = 'HD-VAT-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);
        $stmt = $this->db->prepare(
            'UPDATE orders
             SET vat_invoice_issued = 1, vat_invoice_no = ?, vat_invoice_issued_at = ?
             WHERE id = ? AND vat_invoice_issued = 0'
        );
        $stmt->execute([$invoiceNo, date('Y-m-d H:i:s'), $id]);

        return $this->find($id);
    }


    public function itemsWithProduct(int $orderId): array
    {
        $stmt = $this->db->prepare(
            'SELECT oi.*, p.image AS product_image, p.stock AS product_stock, c.name AS category_name,
                    co.thumbnail AS course_thumbnail, co.title AS course_title
             FROM order_items oi
             LEFT JOIN products p ON p.id = oi.product_id
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN courses co ON co.id = oi.course_id
             WHERE oi.order_id = ?
             ORDER BY oi.id ASC'
        );
        $stmt->execute([$orderId]);

        return array_map(function (array $row): array {
            $row['id'] = (int) $row['id'];
            $row['order_id'] = (int) $row['order_id'];
            $row['product_id'] = (int) ($row['product_id'] ?? 0);
            $row['course_id'] = isset($row['course_id']) ? (int) $row['course_id'] : null;
            $row['quantity'] = (int) $row['quantity'];
            $row['price'] = (int) $row['price'];
            $row['product_stock'] = isset($row['product_stock']) ? (int) $row['product_stock'] : null;
            $row['image'] = $row['product_image'] ?? $row['course_thumbnail'] ?? null;
            $row['name'] = $row['product_name'] ?? $row['course_title'] ?? ($row['name'] ?? 'Khóa học');

            return $row;
        }, $stmt->fetchAll());
    }

    /**
     * @param array<int, array> $orders
     * @return array<int, array>
     */
    public function withItemPreviews(array $orders): array
    {
        if ($orders === []) {
            return [];
        }

        $ids = array_values(array_filter(array_map(static fn ($o) => (int) ($o['id'] ?? 0), $orders)));
        if ($ids === []) {
            return $orders;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("
            SELECT oi.order_id, oi.product_name, oi.quantity, p.image AS product_image
            FROM order_items oi
            LEFT JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id IN ({$placeholders})
            ORDER BY oi.id ASC
        ");
        $stmt->execute($ids);

        $byOrder = [];
        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $oid = (int) $row['order_id'];
            $byOrder[$oid][] = $row;
        }

        foreach ($orders as &$order) {
            $oid = (int) ($order['id'] ?? 0);
            $preview = $byOrder[$oid] ?? [];
            $order['item_previews'] = array_slice($preview, 0, 3);
            $order['item_count'] = count($preview);
        }
        unset($order);

        return $orders;
    }

    public function summaryList(): array
    {
        $stmt = $this->db->query('SELECT * FROM v_order_summary ORDER BY order_id DESC');

        return $stmt->fetchAll() ?: [];
    }

    private function cast(array $row): array
    {
        $row['id'] = (int) $row['id'];
        $row['user_id'] = isset($row['user_id']) ? (int) $row['user_id'] : null;
        $row['total_amount'] = (int) $row['total_amount'];

        return $row;
    }
}
