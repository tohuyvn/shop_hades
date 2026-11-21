<?php
$title = $title ?? 'Thanh toán VietQR';
$bank = $bank ?? \App\Services\VietQrService::config();
$qrUrl = $qrUrl ?? \App\Services\VietQrService::localFallbackUrl();
$qrFallback = $qrFallback ?? \App\Services\VietQrService::localFallbackUrl();
$transferContent = $transferContent ?? '';
$amount = (int) ($amount ?? ($order['total_amount'] ?? 0));
$autoConfirm = $autoConfirm ?? true;
$orderId = (int) ($order['id'] ?? 0);
?>

<div class="section-title">
    <h2>🏦 Thanh toán chuyển khoản VietQR</h2>
</div>

<div class="row g-4 justify-content-center">
    <div class="col-lg-9">
        <div class="content-card border-top border-4" style="border-top-color:#d70018 !important;">
            <div class="text-center mb-4">
                <div class="fw-bold" style="font-size:22px;color:#d70018;">MB BANK · VIETQR</div>
                <p class="text-muted mb-0">
                    Đơn <span class="font-monospace fw-bold"><?= e($order['order_code'] ?? ('#' . $order['id'])) ?></span>
                    · Số tiền: <strong class="text-danger"><?= formatMoney($amount) ?></strong>
                </p>
            </div>

            <div id="vietqr-watch" class="alert alert-warning d-flex align-items-center gap-3 mb-4 p-3 shadow-sm rounded-3" role="status">
                <span class="spinner-border spinner-border-sm text-warning flex-shrink-0" aria-hidden="true" style="width: 1.5rem; height: 1.5rem;"></span>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-dark"><i class="bi bi-hourglass-split text-warning me-1"></i> Đang chờ thanh toán chuyển khoản...</strong>
                        <span class="badge bg-secondary fs-6 px-3 py-1 rounded-pill"><i class="bi bi-arrow-repeat me-1"></i> Tự động kiểm tra</span>
                    </div>
                    <div class="small text-muted mb-0">Hệ thống liên tục kiểm tra tài khoản MB Bank. Sau khi bạn chuyển khoản thành công, trang sẽ tự động ghi nhận và kích hoạt khóa học.</div>
                </div>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-md-5 text-center">
                    <div class="p-3 border rounded shadow-sm bg-white d-inline-block">
                        <img
                            src="<?= e($qrUrl) ?>"
                            alt="VietQR MB Bank"
                            class="img-fluid"
                            style="max-width:260px;width:100%;display:block;margin:0 auto;"
                            onerror="this.onerror=null;this.src='<?= e($qrFallback) ?>';"
                        >
                    </div>
                    <div class="mt-2 text-muted small">Mở app ngân hàng → Quét VietQR / NAPAS 247</div>
                </div>

                <div class="col-md-7">
                    <h5 class="fw-bold mb-3">Thông tin chuyển khoản</h5>
                    <table class="table table-sm table-borderless mb-3">
                        <tr>
                            <td class="text-muted" width="140">Ngân hàng</td>
                            <td class="fw-semibold"><?= e($bank['bank_name']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Số tài khoản</td>
                            <td>
                                <code class="fs-6" id="stkText"><?= e($bank['account_no']) ?></code>
                                <button type="button" class="btn btn-sm btn-outline-secondary ms-1" onclick="copyText('stkText')">Copy</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Chủ tài khoản</td>
                            <td class="fw-semibold"><?= e($bank['account_name_display']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Số tiền</td>
                            <td class="fw-bold text-danger"><?= formatMoney($amount) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nội dung CK</td>
                            <td>
                                <code id="ndckText"><?= e($transferContent) ?></code>
                                <button type="button" class="btn btn-sm btn-outline-secondary ms-1" onclick="copyText('ndckText')">Copy</button>
                            </td>
                        </tr>
                    </table>

                    <ol class="small text-muted ps-3 mb-4">
                        <li>Quét mã QR bằng app ngân hàng (MB, Vietcombank, Techcombank…).</li>
                        <li>Kiểm tra đúng tên <strong><?= e($bank['account_name_display']) ?></strong> và số tiền.</li>
                        <li>Giữ nguyên nội dung chuyển khoản để hệ thống đối soát tự động.</li>
                        <li><?= $autoConfirm
                            ? 'Sau khi chuyển thành công, trang sẽ tự chuyển hướng — không cần bấm gì thêm.'
                            : 'Bấm nút bên dưới sau khi chuyển thành công — admin sẽ xác nhận.'; ?></li>
                    </ol>

                    <form method="post" action="/payment/vietqr/confirm" class="d-grid gap-2">
                        <?= csrf_field() ?>
                        <input type="hidden" name="order_id" value="<?= $orderId ?>">
                        <button type="submit" class="btn btn-outline-danger btn-lg fw-bold">
                            <?= $autoConfirm ? 'Tôi đã chuyển — xem đơn hàng' : 'Tôi đã chuyển khoản' ?>
                        </button>
                        <a href="/orders/<?= $orderId ?>" class="btn btn-outline-secondary">Xem đơn hàng</a>
                    </form>

                    <?php if (!empty(config('app_debug'))): ?>
                    <button type="button" id="vietqr-simulate" class="btn btn-sm btn-warning mt-3">
                        [DEBUG] Giả lập đã nhận tiền
                    </button>
                    <?php endif; ?>

                    <?php require BASE_PATH . '/views/partials/order_items_thumbs.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyText(id) {
    const el = document.getElementById(id);
    if (!el) return;
    navigator.clipboard.writeText(el.textContent.trim()).then(function () {
        alert('Đã copy: ' + el.textContent.trim());
    });
}

(function () {
    const orderId = <?= $orderId ?>;
    const autoConfirm = <?= $autoConfirm ? 'true' : 'false' ?>;
    if (!orderId || !autoConfirm) return;

    let stopped = false;
    let secondsLeft = 5;
    const watch = document.getElementById('vietqr-watch');
    const countdownEl = document.getElementById('vietqr-countdown');
    const progressEl = document.getElementById('vietqr-progress');

    function goSuccess() {
        if (stopped) return;
        stopped = true;
        if (watch) {
            watch.classList.remove('alert-info');
            watch.classList.add('alert-success');
            watch.innerHTML = '<div class="d-flex align-items-center gap-2 text-success fw-bold fs-5"><i class="bi bi-check-circle-fill fs-3"></i> Thanh toán thành công! Đang chuyển sang đơn hàng...</div>';
        }
        if (typeof showToast === 'function') {
            showToast('Thanh toán chuyển khoản thành công!', 'success');
        }
        setTimeout(function () {
            window.location.href = '/orders/success';
        }, 800);
    }

    async function triggerAutoConfirm() {
        try {
            const body = new FormData();
            body.append('order_id', String(orderId));
            body.append('_csrf', document.querySelector('input[name="_csrf"]')?.value || '');
            const res = await fetch('/payment/vietqr/simulate', {
                method: 'POST',
                body,
                credentials: 'same-origin',
            });
            const data = await res.json();
            if (data && data.ok) {
                goSuccess();
            } else {
                // Fallback to form submit
                document.querySelector('form[action="/payment/vietqr/confirm"]')?.submit();
            }
        } catch (e) {
            document.querySelector('form[action="/payment/vietqr/confirm"]')?.submit();
        }
    }

    async function poll() {
        if (stopped) return;
        try {
            const res = await fetch('/payment/vietqr/status?order_id=' + orderId, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
                cache: 'no-store',
            });
            if (!res.ok) return;
            const data = await res.json();
            if (data && data.paid) {
                goSuccess();
            }
        } catch (e) {
            // ignore network glitch
        }
    }

    // Kiểm tra định kỳ trạng thái thanh toán từ ngân hàng mỗi 2 giây
    const pollTimer = setInterval(function () {
        if (stopped) {
            clearInterval(pollTimer);
            return;
        }
        poll();
    }, 2000);

    const sim = document.getElementById('vietqr-simulate');
    if (sim) {
        sim.addEventListener('click', function () {
            triggerAutoConfirm();
        });
    }
})();
</script>
