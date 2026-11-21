<?php

namespace App\Models;

use App\DB;
use PDO;

class CartItem
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DB::connection();
        $this->ensureTableExists();
    }

    private function ensureTableExists(): void
    {
        try {
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS `cart_items` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `session_id` varchar(128) NOT NULL,
                  `user_id` int(10) unsigned DEFAULT NULL,
                  `cart_key` varchar(64) NOT NULL,
                  `product_id` int(10) unsigned NOT NULL,
                  `variant_id` int(10) unsigned DEFAULT 0,
                  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
                  `color` varchar(100) DEFAULT 'Tiêu chuẩn',
                  `price` bigint(20) unsigned NOT NULL DEFAULT 0,
                  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  KEY `idx_session_id` (`session_id`),
                  KEY `idx_user_id` (`user_id`),
                  KEY `idx_product_id` (`product_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        } catch (\Throwable $e) {
            // Table might already exist or permission error
        }
    }

    public function getItems(string $sessionId, ?int $userId = null): array
    {
        try {
            if ($userId) {
                $stmt = $this->db->prepare(
                    'SELECT * FROM cart_items WHERE session_id = ? OR user_id = ? ORDER BY id ASC'
                );
                $stmt->execute([$sessionId, $userId]);
            } else {
                $stmt = $this->db->prepare(
                    'SELECT * FROM cart_items WHERE session_id = ? ORDER BY id ASC'
                );
                $stmt->execute([$sessionId]);
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            error_log('CartItem::getItems error: ' . $e->getMessage());
            return [];
        }
    }

    public function syncCart(string $sessionId, ?int $userId, array $cart): void
    {
        try {
            if ($userId) {
                $stmtDel = $this->db->prepare('DELETE FROM cart_items WHERE session_id = ? OR user_id = ?');
                $stmtDel->execute([$sessionId, $userId]);
            } else {
                $stmtDel = $this->db->prepare('DELETE FROM cart_items WHERE session_id = ?');
                $stmtDel->execute([$sessionId]);
            }

            if (empty($cart)) {
                return;
            }

            $stmtIns = $this->db->prepare(
                'INSERT INTO cart_items (session_id, user_id, cart_key, product_id, variant_id, quantity, color, price)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
            );

            foreach ($cart as $key => $item) {
                $stmtIns->execute([
                    $sessionId,
                    $userId,
                    (string) $key,
                    (int) ($item['product_id'] ?? 0),
                    (int) ($item['variant_id'] ?? 0),
                    max(1, (int) ($item['quantity'] ?? 1)),
                    $item['color'] ?? 'Tiêu chuẩn',
                    (int) ($item['price'] ?? 0),
                ]);
            }
        } catch (\Throwable $e) {
            error_log('CartItem::syncCart error: ' . $e->getMessage());
        }
    }

    public function clear(string $sessionId, ?int $userId = null): void
    {
        try {
            if ($userId) {
                $stmt = $this->db->prepare('DELETE FROM cart_items WHERE session_id = ? OR user_id = ?');
                $stmt->execute([$sessionId, $userId]);
            } else {
                $stmt = $this->db->prepare('DELETE FROM cart_items WHERE session_id = ?');
                $stmt->execute([$sessionId]);
            }
        } catch (\Throwable $e) {
            error_log('CartItem::clear error: ' . $e->getMessage());
        }
    }
}
