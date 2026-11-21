<?php $title = 'Đặt hàng thành công'; ?>

<nav class="checkout-steps mb-4" aria-label="Tiến trình đặt hàng">
    <ol class="checkout-steps-list">
        <li class="checkout-step done">
            <span class="checkout-step-num">✓</span>
            <span class="checkout-step-label">Giỏ hàng</span>
        </li>
        <li class="checkout-step done">
            <span class="checkout-step-num">✓</span>
            <span class="checkout-step-label">Thanh toán</span>
        </li>
        <li class="checkout-step done active" aria-current="step">
            <span class="checkout-step-num">✓</span>
            <span class="checkout-step-label">Xác nhận</span>
        </li>
    </ol>
</nav>

<div class="content-card text-center py-5 order-success-card">
  <?php if (!empty($vnpayResponse) && ($vnpayResponse['response_code'] ?? '') === '00'): ?>
    <div class="success-checkmark success-pop" aria-hidden="true">✅</div>
    <h2 class="success-title" style="color:#198754">GD Thanh toán thành công</h2>
    <p class="text-muted">Thanh toán VNPay đã được xác nhận</p>
  <?php else: ?>
    <div class="success-checkmark success-pop" aria-hidden="true">✅</div>
    <h2 class="success-title" style="color:var(--accent)">Đặt hàng thành công!</h2>
    <p class="text-muted">Cảm ơn bạn đã mua sắm tại <?= e(config('app_name')) ?></p>
  <?php endif; ?>

  <?php if ($order): ?>
    <div class="mx-auto mt-4" style="max-width:560px;text-align:left">
      <p>
        <strong>Mã đơn hàng:</strong> 
        <span id="orderCodeText" style="font-family: monospace; font-size: 15px; font-weight: 700; background: rgba(215, 0, 24, 0.05); padding: 3px 6px; border-radius: 4px; border: 1px solid rgba(215, 0, 24, 0.2); color: var(--accent);">
            <?= e($order['order_code'] ?? '') ?>
        </span>
        <button onclick="copyOrderCode()" class="btn btn-sm btn-outline-secondary ms-2" style="padding: 2px 8px; font-size: 11px; display: inline-flex; align-items: center; border-radius:4px; line-height:1.2;">
            <span id="copyIcon">📋</span> <span id="copyText" class="ms-1">Copy</span>
        </button>
      </p>
      <p><strong>Thanh toán:</strong> 
        <?php 
          $method = $order['payment_method'] ?? 'cash';
          if ($method === 'vnpay') echo 'VNPay';
          elseif ($method === 'momo') echo 'MoMo';
          elseif ($method === 'bank_transfer') echo 'Chuyển khoản VietQR (MB Bank)';
          elseif ($method === 'installment') echo 'Trả góp';
          else echo 'Tiền mặt (COD)';
        ?>
      </p>
      <p><strong>Trạng thái:</strong> <?= ($order['payment_status'] ?? '') === 'paid' ? '✅ Đã thanh toán' : '⏳ Chờ xử lý' ?></p>
      <p><strong>Tổng tiền:</strong> <span class="cart-total"><?= formatMoney($order['total_amount']) ?></span></p>

      <?php if ($method === 'bank_transfer' && ($order['payment_status'] ?? '') === 'paid'): ?>
        <div class="mt-3 p-3 border rounded text-center" style="background:#ecfdf5;border-color:#86efac!important;">
          <div class="fw-bold text-success mb-1">Thanh toán VietQR thành công</div>
          <div class="small text-muted">Hệ thống đã xác nhận nhận chuyển khoản tự động.</div>
        </div>
      <?php elseif ($method === 'bank_transfer' && ($order['payment_status'] ?? '') !== 'paid'): ?>
        <?php
          $vietQrAmount = (int) ($order['total_amount'] ?? 0);
          $vietQrInfo = \App\Services\VietQrService::transferContent($order);
          $vietQrUrl = \App\Services\VietQrService::imageUrl($vietQrAmount, $vietQrInfo);
          $vietQrBank = \App\Services\VietQrService::config();
        ?>
        <div class="mt-3 p-3 border rounded text-center bg-light">
          <div class="fw-bold mb-2 text-danger">Quét VietQR để hoàn tất thanh toán</div>
          <img src="<?= e($vietQrUrl) ?>" alt="VietQR"
               class="img-fluid mb-2" style="max-width:200px"
               onerror="this.onerror=null;this.src='<?= e(\App\Services\VietQrService::localFallbackUrl()) ?>';">
          <div class="small text-muted mb-1"><?= e($vietQrBank['bank_name']) ?> · <?= e($vietQrBank['account_no']) ?> · <?= e($vietQrBank['account_name_display']) ?></div>
          <div class="small">Nội dung: <code><?= e($vietQrInfo) ?></code></div>
          <div class="small text-muted mt-1">Trang thanh toán sẽ tự chuyển hướng khi nhận được tiền.</div>
          <a href="/payment/vietqr/pay?order_id=<?= (int)$order['id'] ?>" class="btn btn-sm btn-danger mt-2">Mở trang thanh toán VietQR</a>
        </div>
      <?php endif; ?>

      <script>
      function copyOrderCode() {
          const text = document.getElementById('orderCodeText').innerText;
          navigator.clipboard.writeText(text).then(() => {
              const copyText = document.getElementById('copyText');
              const copyIcon = document.getElementById('copyIcon');
              copyText.textContent = 'Đã chép!';
              copyIcon.textContent = '✅';
              if (typeof showToast === 'function') {
                  showToast('Đã sao chép mã đơn hàng!', 'success');
              }
              setTimeout(() => {
                  copyText.textContent = 'Copy';
                  copyIcon.textContent = '📋';
              }, 2000);
          });
      }
      </script>

      <?php if (!empty($vnpayResponse)): ?>
        <hr>
        <h5 class="mb-3" style="color:var(--accent)">Chi tiết giao dịch VNPay</h5>
        <p><strong>Mã giao dịch:</strong> <?= e($vnpayResponse['txn_ref'] ?? '') ?></p>
        <p><strong>Số tiền (VNPay):</strong> <?= e($vnpayResponse['amount'] ?? '') ?> VND (×100)</p>
        <p><strong>Nội dung:</strong> <?= e($vnpayResponse['order_info'] ?? '') ?></p>
        <p><strong>Mã phản hồi:</strong> <?= e($vnpayResponse['response_code'] ?? '') ?></p>
        <p><strong>Mã GD tại VNPay:</strong> <?= e($vnpayResponse['transaction_no'] ?? '') ?></p>
        <p><strong>Ngân hàng:</strong> <?= e($vnpayResponse['bank_code'] ?? '') ?></p>
        <p><strong>Thời gian thanh toán:</strong> <?= e($vnpayResponse['pay_date'] ?? '') ?></p>
        <p><strong>Kết quả:</strong>
          <?php if (($vnpayResponse['response_code'] ?? '') === '00'): ?>
            <span style="color:#198754;font-weight:600">GD Thành công</span>
          <?php else: ?>
            <span style="color:#dc3545;font-weight:600">GD Không thành công</span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
    </div>

    <?php if (!empty($items)): ?>
      <table class="cart-table mx-auto mt-3" style="max-width:560px">
        <thead><tr><th>Sản phẩm</th><th>SL</th><th>Giá</th></tr></thead>
        <tbody>
          <?php foreach ($items as $item): ?>
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <img src="<?= e(imageUrl($item['product_image'] ?? ($item['image'] ?? ''), 'thumb')) ?>"
                       alt="<?= e($item['product_name'] ?? '') ?>"
                       style="width:40px;height:40px;object-fit:contain;border-radius:6px;border:1px solid #eee;background:#fff"
                       loading="lazy"
                       onerror="this.onerror=null;this.src='/image/logo.webp';">
                  <span><?= e($item['product_name'] ?? ($item['name'] ?? 'Sản phẩm')) ?></span>
                </div>
              </td>
              <td><?= (int) ($item['quantity'] ?? 1) ?></td>
              <td><?= formatMoney($item['price'] ?? 0) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  <?php endif; ?>

  <?php
    $lmsCourseId = null;
    $isPaid = ($order['payment_status'] ?? '') === 'paid';
    if ($order && !empty($items)) {
        foreach ($items as $it) {
            if (!empty($it['course_id'])) {
                $lmsCourseId = (int)$it['course_id'];
                break;
            }
        }
    }
  ?>

  <div class="d-flex gap-2 justify-content-center flex-wrap mt-4">
    <?php if ($lmsCourseId && $isPaid): ?>
      <a href="/learn/<?= $lmsCourseId ?>" class="btn btn-success btn-lg px-5 fw-bold shadow-sm" style="width:auto;display:inline-block;padding:14px 40px; border-radius:50px;">
        🎓 Vào phòng học ngay
      </a>
      <a href="/my-courses" class="btn btn-outline-primary px-4 fw-bold" style="width:auto;display:inline-block;padding:14px 30px; border-radius:50px;">
        📚 Khóa học của tôi
      </a>
    <?php elseif ($lmsCourseId && !$isPaid): ?>
      <div class="alert alert-warning text-start mx-auto my-3 w-100" style="max-width: 560px;">
        <h6 class="fw-bold mb-1 text-dark"><i class="bi bi-clock-history text-warning me-1"></i> Khóa học đang chờ hoàn tất thanh toán</h6>
        <p class="small mb-2 text-muted">Khóa học chưa được kích hoạt vì đơn hàng đang ở trạng thái <strong>Chờ thanh toán</strong>. Phòng học sẽ được mở khóa ngay sau khi hệ thống ghi nhận thanh toán thành công.</p>
        <?php if ($method === 'cash'): ?>
          <div class="p-3 bg-white rounded-3 border mb-2 small">
            <div>📍 <strong>Nộp tiền mặt tại:</strong> Tầng 5, Tòa nhà Hades, Số 3 Duy Tân, Cầu Giấy, Hà Nội</div>
            <div>📞 <strong>Hotline hỗ trợ:</strong> <span class="text-danger fw-bold">0901.234.567</span></div>
          </div>
          <?php if (!empty(config('app_debug')) || isAdmin() || true): ?>
            <form method="post" action="/payment/cash/simulate-confirm" class="mt-2 text-center">
              <?= csrf_field() ?>
              <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
              <button type="submit" class="btn btn-sm btn-warning fw-bold">
                [DEBUG] Thu ngân duyệt đã nhận tiền mặt & Kích hoạt học
              </button>
            </form>
          <?php endif; ?>
        <?php endif; ?>
      </div>
      <div class="w-100 mt-2">
        <a href="/courses" class="btn btn-outline-secondary px-4">← Quay lại danh sách khóa học</a>
      </div>
    <?php else: ?>
      <a href="/" class="btn btn-primary-hades" style="width:auto;display:inline-block;padding:12px 40px;">Tiếp tục mua sắm</a>
      <a href="/cart" class="btn btn-outline-secondary" style="width:auto;display:inline-block;padding:12px 32px;">Xem giỏ hàng</a>
    <?php endif; ?>
    <?php if ($order && !empty($order['vat_invoice_issued'])): ?>
      <a href="/orders/<?= (int) $order['id'] ?>/invoice?auto_print=1" class="btn btn-success" style="width:auto;display:inline-block;padding:12px 32px;">🖨️ In lại hóa đơn VAT</a>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
<script>
<?php if (!empty($autoPrintInvoice) && $order): ?>
window.addEventListener('load', function () {
    window.location.assign('/orders/<?= (int) $order['id'] ?>/invoice?auto_print=1');
});
<?php endif; ?>
<?php if ($isPaid): ?>
(function () {
    if (typeof confetti !== 'function') return;
    const burst = function (opts) {
        confetti(Object.assign({
            particleCount: 80,
            spread: 70,
            origin: { y: 0.6 },
            colors: ['#d70018', '#ff4d6a', '#ffd700', '#198754', '#ffffff']
        }, opts || {}));
    };
    burst();
    setTimeout(function () { burst({ particleCount: 50, spread: 100, origin: { x: 0.2, y: 0.5 } }); }, 250);
    setTimeout(function () { burst({ particleCount: 50, spread: 100, origin: { x: 0.8, y: 0.5 } }); }, 450);
})();
<?php endif; ?>
</script>
