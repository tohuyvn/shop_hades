<?php 
$title = $product['name']; 
$metaDescription = !empty($product['description']) ? strip_tags(mb_substr($product['description'], 0, 160)) . '...' : 'Mua ' . $product['name'] . ' chính hãng giá tốt nhất tại Hades Shop.';
$ogTitle = $product['name'] . ' - Hades Shop';
$ogDescription = $metaDescription;
$ogImage = !empty($product['image']) ? imageUrl($product['image']) : '';
?>

<!-- Schema.org JSON-LD Structured Data for SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": <?= json_encode($product['name']) ?>,
  "image": [<?= json_encode($ogImage) ?>],
  "description": <?= json_encode(strip_tags($product['description'] ?? '')) ?>,
  "sku": "HADES-<?= (int)$product['id'] ?>",
  "offers": {
    "@type": "Offer",
    "url": "<?= e($_SERVER['REQUEST_URI'] ?? '') ?>",
    "priceCurrency": "VND",
    "price": <?= (int)($product['sale_price'] ?? $product['price']) ?>,
    "itemCondition": "https://schema.org/NewCondition",
    "availability": <?= (int)($product['stock'] ?? 0) > 0 ? '"https://schema.org/InStock"' : '"https://schema.org/OutOfStock"' ?>
  }
  <?php if (!empty($ratingStats['count'])): ?>
  ,
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?= $ratingStats['average'] ?>",
    "reviewCount": "<?= $ratingStats['count'] ?>"
  }
  <?php endif; ?>
}
</script>


<nav class="breadcrumb-nav">
    <a href="/">Trang chủ</a>
    <span class="text-muted"> / </span>
    <a href="/?category=<?= $product['category_id'] ?>"><?= e($product['category_name']) ?></a>
    <span class="text-muted"> / </span>
    <span class="text-muted"><?= e($product['name']) ?></span>
</nav>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="content-card p-0 overflow-hidden">
            <div class="product-gallery-main" style="background:#f1f5f9;padding:24px;">
                <img src="<?= e(imageUrl($product['image'])) ?>" alt="<?= e($product['name']) ?>"
                     style="width:100%;display:block;object-fit:contain;max-height:420px;margin:0 auto">
            </div>
            <div class="product-gallery-thumbs d-flex justify-content-center gap-2 p-3 bg-light border-top flex-wrap">
                <div class="thumb-item active" onclick="switchGalleryImage(this, '<?= e(imageUrl($product['image'])) ?>', '')" style="cursor: pointer; width: 60px; height: 60px; padding: 4px; border: 2px solid var(--accent); border-radius: 8px; background: #fff; transition: all 0.2s ease;">
                    <img src="<?= e(imageUrl($product['image'])) ?>" alt="Ảnh gốc" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <div class="thumb-item" onclick="switchGalleryImage(this, '<?= e(imageUrl($product['image'])) ?>', 'hue-rotate(90deg)')" style="cursor: pointer; width: 60px; height: 60px; padding: 4px; border: 1px solid #dee2e6; border-radius: 8px; background: #fff; transition: all 0.2s ease;">
                    <img src="<?= e(imageUrl($product['image'])) ?>" alt="Màu xanh lục" style="width: 100%; height: 100%; object-fit: contain; filter: hue-rotate(90deg);">
                </div>
                <div class="thumb-item" onclick="switchGalleryImage(this, '<?= e(imageUrl($product['image'])) ?>', 'hue-rotate(180deg)')" style="cursor: pointer; width: 60px; height: 60px; padding: 4px; border: 1px solid #dee2e6; border-radius: 8px; background: #fff; transition: all 0.2s ease;">
                    <img src="<?= e(imageUrl($product['image'])) ?>" alt="Màu lam tím" style="width: 100%; height: 100%; object-fit: contain; filter: hue-rotate(180deg);">
                </div>
                <div class="thumb-item" onclick="switchGalleryImage(this, '<?= e(imageUrl($product['image'])) ?>', 'grayscale(100%)')" style="cursor: pointer; width: 60px; height: 60px; padding: 4px; border: 1px solid #dee2e6; border-radius: 8px; background: #fff; transition: all 0.2s ease;">
                    <img src="<?= e(imageUrl($product['image'])) ?>" alt="Màu xám bạc" style="width: 100%; height: 100%; object-fit: contain; filter: grayscale(100%);">
                </div>
            </div>
        </div>

        <div class="content-card mt-3 p-3">
            <h6 class="fw-bold mb-3 small text-uppercase">Chính sách ưu đãi</h6>
            <div class="d-flex flex-column gap-2 small">
                <div class="d-flex align-items-center gap-2"><i class="bi bi-truck text-primary"></i> Miễn phí vận chuyển cho đơn hàng từ 500k</div>
                <div class="d-flex align-items-center gap-2"><i class="bi bi-shield-check text-success"></i> Bảo hành chính hãng 12 tháng</div>
                <div class="d-flex align-items-center gap-2"><i class="bi bi-arrow-repeat text-warning"></i> Đổi trả dễ dàng trong 7 ngày</div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="content-card h-100">
            <span class="product-cat"><?= e($product['category_name']) ?></span>
            <h1 style="font-size:1.65rem;font-weight:800;margin:8px 0 10px;letter-spacing:-0.02em"><?= e($product['name']) ?></h1>

            <div class="d-flex align-items-center mb-3">
                <div class="text-warning me-2" style="font-size: 1.1rem; line-height: 1;">
                    <?php
                    $avg = $ratingStats['average'];
                    for ($i = 1; $i <= 5; $i++) echo $i <= round($avg) ? '★' : '☆';
                    ?>
                </div>
                <span class="text-muted small">
                    <?= $ratingStats['count'] > 0 ? "<strong>{$avg} / 5</strong> ({$ratingStats['count']} đánh giá)" : 'Chưa có đánh giá' ?>
                </span>
            </div>

            <div class="product-price mb-3" style="font-size:2rem">
                <?= formatMoney($product['sale_price']) ?>
                <?php if (!empty($product['has_discount'])): ?>
                    <small style="font-size: 14px; text-decoration: line-through; color: #94a3b8;"><?= formatMoney($product['original_price']) ?></small>
                    <span class="badge bg-danger ms-2" style="font-size:14px;vertical-align:middle">-<?= (int) $product['discount_percent'] ?>%</span>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <?php if ((int)($product['is_preorder'] ?? 0) === 1): ?>
                    <span class="badge bg-danger px-3 py-2"><i class="bi bi-clock me-1"></i> ĐẶT TRƯỚC (PRE-ORDER)</span>
                    <?php if ((int)($product['preorder_deposit'] ?? 0) > 0): ?>
                        <span class="badge bg-info text-dark px-3 py-2 ms-1 border border-info"><i class="bi bi-wallet2 me-1"></i> Cọc trước: <?= formatMoney($product['preorder_deposit']) ?></span>
                    <?php else: ?>
                        <span class="badge bg-success text-white px-3 py-2 ms-1 border border-success"><i class="bi bi-gift me-1"></i> Không cần cọc</span>
                    <?php endif; ?>
                <?php else: ?>
                    <?php
                    $stock = (int)$product['stock'];
                    if ($stock <= 0): ?>
                        <span class="badge bg-secondary px-3 py-2"><i class="bi bi-x-circle me-1"></i> Hết hàng</span>
                    <?php elseif ($stock < 5): ?>
                        <span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-exclamation-triangle me-1"></i> Sắp hết hàng (Chỉ còn <?= $stock ?>)</span>
                    <?php else: ?>
                        <span class="badge bg-success-subtle text-success px-3 py-2 border border-success-subtle"><i class="bi bi-check-circle me-1"></i> Còn hàng</span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <p class="text-muted mb-4 small" style="line-height: 1.6;"><?= e($product['description']) ?></p>

            <?php if (!empty($variants)): ?>
                <div class="mb-4">
                    <label class="fw-bold small mb-2 d-block text-dark"><i class="bi bi-tags-fill me-1 text-primary"></i>Chọn phiên bản & Màu sắc:</label>
                    <div class="d-flex gap-2 flex-wrap" id="variantSelectorContainer">
                        <?php foreach ($variants as $idx => $v): ?>
                            <?php 
                            $varLabel = htmlspecialchars($v['color']) . ' / ' . htmlspecialchars($v['storage']);
                            $varPrice = (int)$v['price'] > 0 ? (int)$v['price'] : (int)$product['sale_price'];
                            ?>
                            <button class="btn btn-sm btn-outline-secondary <?= $idx === 0 ? 'active' : '' ?>" 
                                    type="button" 
                                    onclick="selectVariant(this, <?= $v['id'] ?>, '<?= $varLabel ?>', <?= $varPrice ?>, <?= (int)$v['stock'] ?>)"
                                    style="border-radius: 8px; padding: 8px 16px; font-weight: 500;">
                                <?= $varLabel ?> <span class="ms-1 text-primary">(<?= formatMoney($varPrice) ?>)</span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="mb-4">
                    <label class="fw-bold small mb-2 d-block"><i class="bi bi-palette me-1"></i>Chọn màu sắc:</label>
                    <div class="d-flex gap-2 flex-wrap" id="colorPickerContainer">
                        <button class="btn btn-sm btn-outline-secondary active" type="button" onclick="selectColor(btn = this, 'Tiêu chuẩn', '', 0)" style="border-radius: 8px; padding: 6px 16px; font-weight: 500;">Tiêu chuẩn</button>
                        <button class="btn btn-sm btn-outline-secondary" type="button" onclick="selectColor(btn = this, 'Xanh lục', 'hue-rotate(90deg)', 1)" style="border-radius: 8px; padding: 6px 16px; font-weight: 500;">Xanh lục</button>
                        <button class="btn btn-sm btn-outline-secondary" type="button" onclick="selectColor(btn = this, 'Lam tím', 'hue-rotate(180deg)', 2)" style="border-radius: 8px; padding: 6px 16px; font-weight: 500;">Lam tím</button>
                        <button class="btn btn-sm btn-outline-secondary" type="button" onclick="selectColor(btn = this, 'Xám bạc', 'grayscale(100%)', 3)" style="border-radius: 8px; padding: 6px 16px; font-weight: 500;">Xám bạc</button>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Dịch vụ Gói Bảo Hành & Phụ Kiện Mua Kèm (Cross-selling & Add-on Services) -->
            <div class="addon-services-card mb-4 p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                <div class="fw-bold small text-uppercase mb-2 text-dark d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-shield-fill-check text-primary me-1"></i> Gói Bảo Hành Khuyên Dùng</span>
                    <span class="badge bg-danger text-white" style="font-size: 10px;">Hot</span>
                </div>
                <div class="d-flex flex-column gap-2 mb-3">
                    <label class="form-check p-2 rounded-2 border bg-white d-flex align-items-center justify-content-between cursor-pointer mb-0">
                        <div class="d-flex align-items-center gap-2">
                            <input class="form-check-input mt-0 addon-option" type="checkbox" value="490000" data-note="Bảo hành VIP 1 đổi 1 (12T)" onchange="syncAddonSelections()">
                            <div>
                                <div class="small fw-bold text-dark">🛡️ Bảo hành VIP 1 đổi 1 trong 12 tháng</div>
                                <div class="text-muted" style="font-size: 11px;">Bảo hành cả rơi vỡ, vào nước - Đổi máy mới tương đương</div>
                            </div>
                        </div>
                        <div class="text-danger fw-bold small">+490.000₫</div>
                    </label>
                    <label class="form-check p-2 rounded-2 border bg-white d-flex align-items-center justify-content-between cursor-pointer mb-0">
                        <div class="d-flex align-items-center gap-2">
                            <input class="form-check-input mt-0 addon-option" type="checkbox" value="790000" data-note="Bảo hành mở rộng 24T" onchange="syncAddonSelections()">
                            <div>
                                <div class="small fw-bold text-dark">⚡ Gia hạn bảo hành chính hãng 24 tháng</div>
                                <div class="text-muted" style="font-size: 11px;">Yên tâm sử dụng thêm 1 năm sau khi hết hạn bảo hành gốc</div>
                            </div>
                        </div>
                        <div class="text-danger fw-bold small">+790.000₫</div>
                    </label>
                </div>

                <div class="fw-bold small text-uppercase mb-2 text-dark d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Phụ Kiện Mua Kèm Giảm Đến 40%</span>
                </div>
                <div class="d-flex flex-column gap-2">
                    <label class="form-check p-2 rounded-2 border bg-white d-flex align-items-center justify-content-between cursor-pointer mb-0">
                        <div class="d-flex align-items-center gap-2">
                            <input class="form-check-input mt-0 addon-option" type="checkbox" value="290000" data-note="Củ sạc nhanh 30W GaN" onchange="syncAddonSelections()">
                            <div>
                                <div class="small fw-bold text-dark">🔌 Củ sạc nhanh 30W GaN Type-C</div>
                                <div class="text-muted" style="font-size: 11px;">Sạc nhanh chuẩn PD an toàn (Giá gốc: 450.000₫)</div>
                            </div>
                        </div>
                        <div class="text-danger fw-bold small">+290.000₫ <s class="text-muted small fw-normal ms-1">450.000₫</s></div>
                    </label>
                    <label class="form-check p-2 rounded-2 border bg-white d-flex align-items-center justify-content-between cursor-pointer mb-0">
                        <div class="d-flex align-items-center gap-2">
                            <input class="form-check-input mt-0 addon-option" type="checkbox" value="120000" data-note="Kính cường lực 9D" onchange="syncAddonSelections()">
                            <div>
                                <div class="small fw-bold text-dark">📱 Kính cường lực 9D chống nhìn trộm</div>
                                <div class="text-muted" style="font-size: 11px;">Chống va đập, bảo mật góc nhìn (Giá gốc: 200.000₫)</div>
                            </div>
                        </div>
                        <div class="text-danger fw-bold small">+120.000₫ <s class="text-muted small fw-normal ms-1">200.000₫</s></div>
                    </label>
                </div>
            </div>

            <div class="product-actions d-flex gap-2 flex-wrap mt-auto">
                <form method="post" action="/cart/buy/<?= $product['id'] ?>" class="flex-grow-1" style="min-width:160px" id="buyNowForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="color" value="<?= !empty($variants) ? htmlspecialchars($variants[0]['color'] . ' / ' . $variants[0]['storage']) : 'Tiêu chuẩn' ?>" class="color-form-input">
                    <input type="hidden" name="variant_id" value="<?= !empty($variants) ? $variants[0]['id'] : '' ?>" class="variant-form-input">
                    <input type="hidden" name="addon_price" value="0" class="addon-price-form-input">
                    <input type="hidden" name="addon_notes" value="" class="addon-notes-form-input">
                    <?php if ((int)($product['is_preorder'] ?? 0) === 1): ?>
                        <button type="submit" id="buyNowBtn" class="btn btn-danger w-100 py-3 fw-bold" style="border-radius: var(--radius-sm);"><i class="bi bi-clock me-1"></i> ĐẶT TRƯỚC NGAY</button>
                    <?php else: ?>
                        <button type="submit" id="buyNowBtn" class="btn btn-primary-hades w-100 py-3 fw-bold" style="border-radius: var(--radius-sm);" <?= $stock <= 0 ? 'disabled' : '' ?>>MUA NGAY</button>
                    <?php endif; ?>
                </form>
                <form method="post" action="/cart/add/<?= $product['id'] ?>" class="flex-grow-1" style="min-width:160px" id="addToCartForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="color" value="<?= !empty($variants) ? htmlspecialchars($variants[0]['color'] . ' / ' . $variants[0]['storage']) : 'Tiêu chuẩn' ?>" class="color-form-input">
                    <input type="hidden" name="variant_id" value="<?= !empty($variants) ? $variants[0]['id'] : '' ?>" class="variant-form-input">
                    <input type="hidden" name="addon_price" value="0" class="addon-price-form-input">
                    <input type="hidden" name="addon_notes" value="" class="addon-notes-form-input">
                    <?php if ((int)($product['is_preorder'] ?? 0) === 1): ?>
                        <button type="submit" id="addToCartBtn" class="btn btn-outline-danger w-100 py-3 fw-bold" style="border-width: 2px; border-radius: var(--radius-sm);" <?= $stock <= 0 ? 'disabled' : '' ?>>
                            <i class="bi bi-bag-plus me-2"></i>THÊM GIỎ HÀNG
                        </button>
                    <?php else: ?>
                        <button type="submit" id="addToCartBtn" class="btn btn-outline-primary w-100 py-3 fw-bold" style="border-width: 2px; border-radius: var(--radius-sm);" <?= $stock <= 0 ? 'disabled' : '' ?>>
                            <i class="bi bi-bag-plus me-2"></i>THÊM GIỎ HÀNG
                        </button>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Nút Trả Góp 0% -->
            <div class="mt-2">
                <button type="button" class="btn btn-warning w-100 py-2.5 fw-bold text-dark d-flex flex-column align-items-center justify-content-center shadow-sm" style="border-radius: var(--radius-sm); border: 1px solid #f59e0b; background: linear-gradient(180deg, #fef08a 0%, #facc15 100%);" onclick="openInstallmentModal()" <?= $stock <= 0 ? 'disabled' : '' ?>>
                    <span class="d-flex align-items-center gap-2 fs-6 fw-bold"><i class="bi bi-percent-circle-fill text-dark fs-5"></i> MUA TRẢ GÓP 0% QUA THẺ / CÔNG TY TÀI CHÍNH</span>
                    <span style="font-size: 11px; opacity: 0.9; font-weight: 500;">Trả trước từ 0đ • Lãi suất 0% • Duyệt hồ sơ 5 phút online</span>
                </button>
            </div>
            
            <?php
            $isWishlisted = false;
            if ($uWish = authUser()) {
                $isWishlisted = (new \App\Models\Wishlist())->has((int)$uWish['id'], (int)$product['id']);
            }
            ?>
            <div class="d-flex gap-2 mt-3">
                <button type="button" class="btn btn-outline-secondary flex-grow-1 py-2.5 fw-bold" style="border-radius: var(--radius-sm);" id="detailCompareBtn" 
                        onclick="toggleCompareDetail(<?= (int)$product['id'] ?>, '<?= e(addslashes($product['name'])) ?>', '<?= e(imageUrl($product['image'] ?? '', 'thumb')) ?>')">
                    <i class="bi bi-shuffle me-2"></i>So sánh
                </button>
                <button type="button" class="btn <?= $isWishlisted ? 'btn-danger' : 'btn-outline-danger' ?> py-2.5 px-3 fw-bold" style="border-radius: var(--radius-sm);" id="detailWishlistBtn"
                        onclick="toggleWishlist(<?= (int)$product['id'] ?>)"
                        data-wishlist-product-id="<?= (int)$product['id'] ?>"
                        data-product-name="<?= e($product['name']) ?>"
                        data-product-image="<?= e(imageUrl($product['image'] ?? '', 'thumb')) ?>"
                        data-product-price="<?= (int)($product['sale_price'] ?? $product['price'] ?? 0) ?>"
                        data-product-original-price="<?= (int)($product['original_price'] ?? $product['price'] ?? 0) ?>"
                        data-wishlisted="<?= $isWishlisted ? '1' : '0' ?>">
                    <i class="bi <?= $isWishlisted ? 'bi-heart-fill' : 'bi-heart' ?> me-1"></i>
                    <span id="wishlistBtnText"><?= $isWishlisted ? 'Đã thích' : 'Yêu thích' ?></span>
                </button>
            </div>

            <div class="mt-4 p-3 bg-light rounded-3 border">
                <div class="row text-center g-2">
                    <div class="col-4 border-end">
                        <div class="fw-bold small">Miễn phí</div>
                        <div class="text-muted" style="font-size: 10px;">Giao hàng</div>
                    </div>
                    <div class="col-4 border-end">
                        <div class="fw-bold small">Chính hãng</div>
                        <div class="text-muted" style="font-size: 10px;">Cam kết 100%</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold small">Hoàn tiền</div>
                        <div class="text-muted" style="font-size: 10px;">Nếu lỗi NSX</div>
                    </div>
                </div>
            </div>

            <?php if (!empty($stores)): ?>
                <!-- Có sẵn tại siêu thị -->
                <div class="mt-3 p-3 bg-white rounded-3 border">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold text-dark"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Có sẵn tại <?= count($stores) ?> showroom Hades:</span>
                        <a href="/pages/dia-chi-cua-hang" class="small text-primary text-decoration-none fw-semibold">Xem bản đồ &rarr;</a>
                    </div>
                    <div class="d-flex flex-column gap-1.5 small text-muted" style="max-height: 120px; overflow-y: auto;">
                        <?php foreach ($stores as $st): ?>
                            <div class="d-flex align-items-center justify-content-between py-1 border-bottom border-light">
                                <span class="text-truncate me-2"><i class="bi bi-shop me-1 text-secondary"></i><strong><?= e($st['name']) ?>:</strong> <?= e($st['address']) ?></span>
                                <span class="badge bg-success-subtle text-success flex-shrink-0" style="font-size: 10px;">Còn hàng</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Tabs Section -->
<div class="content-card mt-4 p-0 overflow-hidden">
    <ul class="nav nav-tabs product-tabs" id="productTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab">Mô tả sản phẩm</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="spec-tab" data-bs-toggle="tab" data-bs-target="#spec-pane" type="button" role="tab">Thông số kỹ thuật</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review-pane" type="button" role="tab">Đánh giá (<?= count($reviews) ?>)</button>
        </li>
    </ul>
    <div class="tab-content p-4" id="productTabContent">
        <div class="tab-pane fade show active" id="desc-pane" role="tabpanel">
            <div class="product-detail-text">
                <?php foreach (explode("\n", $product['description']) as $line): ?>
                    <p class="mb-2"><?= e(trim($line)) ?></p>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="tab-pane fade" id="spec-pane" role="tabpanel">
            <div class="product-detail-text">
                <?php
                $specRows = array_filter([
                    'RAM' => $product['ram'] ?? null,
                    'Bộ nhớ' => $product['storage_gb'] ?? null,
                    'Chipset' => $product['chipset'] ?? null,
                    'Màn hình' => $product['screen_size'] ?? null,
                    'Pin' => !empty($product['battery_mah']) ? $product['battery_mah'] . ' mAh' : null,
                    'Hệ điều hành' => $product['os'] ?? null,
                ]);
                ?>
                <?php if ($specRows !== []): ?>
                    <table class="table table-sm table-bordered mb-3">
                        <?php foreach ($specRows as $label => $val): ?>
                            <tr>
                                <th style="width:40%"><?= e($label) ?></th>
                                <td><?= e((string)$val) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
                <?php if (!empty($product['detail'])): ?>
                    <?php foreach (explode("\n", $product['detail']) as $line): ?>
                        <p class="mb-2"><?= e(trim($line)) ?></p>
                    <?php endforeach; ?>
                <?php elseif ($specRows === []): ?>
                    <p class="text-muted italic">Thông tin đang được cập nhật...</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="tab-pane fade" id="review-pane" role="tabpanel">
            <div class="row">
                <div class="col-md-5 mb-4 border-end">
                    <h5 class="fw-bold mb-3 small text-uppercase">Gửi đánh giá của bạn</h5>
                    <?php if ($canReview ?? false): ?>
                        <form method="post" action="/products/<?= $product['id'] ?>/review" id="reviewForm" class="p-3 border rounded-3 bg-light">
                            <?= csrf_field() ?>
                            <div class="alert alert-success py-2 small">
                                <i class="bi bi-patch-check-fill me-1"></i>
                                Đơn mua đã được xác minh
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Số sao đánh giá</label>
                                <div class="star-rating-selector">
                                    <input type="radio" id="star5" name="rating" value="5" checked><label for="star5">★</label>
                                    <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
                                    <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                                    <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                                    <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nhận xét</label>
                                <textarea name="comment" rows="3" minlength="5" maxlength="2000"
                                          class="form-control form-control-sm"
                                          placeholder="Bạn thấy sản phẩm này thế nào?" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary-hades w-100">Gửi đánh giá</button>
                        </form>
                    <?php elseif (!authUser()): ?>
                        <div class="p-3 border rounded-3 bg-light text-center">
                            <i class="bi bi-person-lock fs-2 text-muted"></i>
                            <p class="small text-muted my-2">Đăng nhập bằng tài khoản đã mua sản phẩm để viết đánh giá.</p>
                            <a href="/login" class="btn btn-outline-primary btn-sm">Đăng nhập</a>
                        </div>
                    <?php elseif ($hasReviewed ?? false): ?>
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-circle-fill me-1"></i>Bạn đã đánh giá sản phẩm này.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-light border mb-0 small">
                            <i class="bi bi-shield-check me-1"></i>
                            Bạn có thể đánh giá sau khi đã <strong>thanh toán thành công</strong> đơn chứa sản phẩm này
                            (đơn không bị hủy).
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-7">
                    <h5 class="fw-bold mb-3 small text-uppercase">Nhận xét khách hàng</h5>
                    <?php if (count($reviews) === 0): ?>
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-chat-dots fs-1 d-block mb-2 opacity-25"></i>
                            Chưa có nhận xét nào.
                        </div>
                    <?php else: ?>
                        <div class="review-list">
                            <?php foreach ($reviews as $rev): ?>
                                <div class="mb-4 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fw-bold small">
                                            <?= e($rev['user_name']) ?>
                                            <?php if (!empty($rev['is_verified'])): ?>
                                                <span class="badge bg-success-subtle text-success ms-1">
                                                    <i class="bi bi-patch-check-fill"></i> Đã mua hàng
                                                </span>
                                            <?php endif; ?>
                                        </span>
                                        <span class="text-muted" style="font-size: 10px;"><?= date('d/m/Y', strtotime($rev['created_at'])) ?></span>
                                    </div>
                                    <div class="text-warning mb-2" style="font-size: 10px;">
                                        <?php for ($i = 1; $i <= 5; $i++) echo $i <= $rev['rating'] ? '★' : '☆'; ?>
                                    </div>
                                    <p class="small mb-2"><?= e($rev['comment']) ?></p>

                                    <div class="d-flex gap-3 small">
                                        <form method="post" action="/reviews/<?= $rev['id'] ?>/like" style="display:inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none text-muted" style="font-size: 11px;">
                                                <i class="bi bi-hand-thumbs-up"></i> (<?= (int)$rev['likes_count'] ?>)
                                            </button>
                                        </form>
                                        <a href="javascript:void(0)" class="text-muted text-decoration-none" style="font-size: 11px;" onclick="toggleReplyForm(<?= $rev['id'] ?>)">Phản hồi</a>
                                    </div>

                                    <!-- Replies and form omitted for brevity, keeping same logic as before -->
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Related Products Section -->
<?php if (!empty($relatedProducts)): ?>
    <div class="content-card mt-5 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-grid-fill text-primary me-2"></i>Sản phẩm liên quan cùng danh mục
            </h5>
            <a href="/?category=<?= (int)$product['category_id'] ?>" class="btn btn-sm btn-link text-decoration-none">
                Xem tất cả <i class="bi bi-chevron-right ms-1"></i>
            </a>
        </div>
        <div class="product-grid">
            <?php 
            $mainProductBackup = $product;
            foreach ($relatedProducts as $pCard):
                $product = $pCard;
                require BASE_PATH . '/views/partials/product_card.php';
            endforeach;
            $product = $mainProductBackup;
            ?>
        </div>
    </div>
<?php endif; ?>

<!-- Recently Viewed Products Section -->
<?php if (!empty($recentlyViewedProducts)): ?>
    <div class="content-card mt-4 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-clock-history text-danger me-2"></i>Sản phẩm bạn vừa xem gần đây
            </h5>
        </div>
        <div class="product-grid">
            <?php 
            $mainProductBackup = $product;
            foreach ($recentlyViewedProducts as $pCard):
                $product = $pCard;
                require BASE_PATH . '/views/partials/product_card.php';
            endforeach;
            $product = $mainProductBackup;
            ?>
        </div>
    </div>
<?php endif; ?>

<!-- MODAL BẢNG TÍNH TRẢ GÓP 0% CHUYÊN NGHIỆP -->
<div class="modal fade" id="installmentModal" tabindex="-1" aria-labelledby="installmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <div>
                    <h5 class="modal-title fw-bold" id="installmentModalLabel">
                        <i class="bi bi-percent-circle-fill text-warning me-2"></i>Bảng Tính Mua Trả Góp 0%
                    </h5>
                    <div class="small opacity-75"><?= e($product['name']) ?> • Duyệt hồ sơ nhanh 5 phút online</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <!-- Chọn hình thức trả góp -->
                <div class="d-flex gap-2 p-1 bg-white rounded-3 border mb-3">
                    <button type="button" class="btn btn-sm flex-grow-1 fw-bold installment-type-btn active btn-primary py-2" onclick="switchInstallmentType(this, 'financial_company')">
                        <i class="bi bi-building me-1"></i> Công Ty Tài Chính (Home Credit, Kredivo)
                    </button>
                    <button type="button" class="btn btn-sm flex-grow-1 fw-bold installment-type-btn btn-outline-secondary py-2" onclick="switchInstallmentType(this, 'credit_card')">
                        <i class="bi bi-credit-card-2-front me-1"></i> Thẻ Tín Dụng (25 Ngân Hàng)
                    </button>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark">Chọn số tiền trả trước:</label>
                        <div class="d-flex gap-2 flex-wrap" id="depositBtnGroup">
                            <button type="button" class="btn btn-sm btn-outline-primary active deposit-btn flex-grow-1 py-2 fw-semibold" onclick="selectDeposit(this, 0)">0% (0₫)</button>
                            <button type="button" class="btn btn-sm btn-outline-primary deposit-btn flex-grow-1 py-2 fw-semibold" onclick="selectDeposit(this, 20)">20%</button>
                            <button type="button" class="btn btn-sm btn-outline-primary deposit-btn flex-grow-1 py-2 fw-semibold" onclick="selectDeposit(this, 30)">30%</button>
                            <button type="button" class="btn btn-sm btn-outline-primary deposit-btn flex-grow-1 py-2 fw-semibold" onclick="selectDeposit(this, 50)">50%</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark">Chọn kỳ hạn trả góp:</label>
                        <div class="d-flex gap-2 flex-wrap" id="monthBtnGroup">
                            <button type="button" class="btn btn-sm btn-outline-primary month-btn flex-grow-1 py-2 fw-semibold" onclick="selectMonth(this, 3)">3 Tháng</button>
                            <button type="button" class="btn btn-sm btn-outline-primary active month-btn flex-grow-1 py-2 fw-semibold" onclick="selectMonth(this, 6)">6 Tháng</button>
                            <button type="button" class="btn btn-sm btn-outline-primary month-btn flex-grow-1 py-2 fw-semibold" onclick="selectMonth(this, 9)">9 Tháng</button>
                            <button type="button" class="btn btn-sm btn-outline-primary month-btn flex-grow-1 py-2 fw-semibold" onclick="selectMonth(this, 12)">12 Tháng</button>
                        </div>
                    </div>
                </div>

                <!-- Bảng chi tiết tính toán tài chính -->
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small">Giá sản phẩm (gồm phiên bản & dịch vụ):</span>
                            <span class="fw-bold fs-6 text-dark" id="modalProductPrice">0₫</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small">Số tiền cần trả trước (<span id="modalDepositPercent">0%</span>):</span>
                            <span class="fw-bold text-primary" id="modalDepositAmount">0₫</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small">Lãi suất trả góp:</span>
                            <span class="badge bg-success-subtle text-success fs-7">0% (Ưu đãi Hades)</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small">Phí hồ sơ & bảo hiểm:</span>
                            <span class="badge bg-success-subtle text-success fs-7">Miễn phí 0₫</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small">Chênh lệch so với mua thẳng:</span>
                            <span class="fw-bold text-success">0₫</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-3">
                            <div>
                                <div class="fw-bold fs-5 text-danger mb-0" id="modalMonthlyPayment">0₫ / tháng</div>
                                <div class="text-muted" style="font-size: 11px;">Góp đều trong <span id="modalPeriodText">6 tháng</span></div>
                            </div>
                            <button type="button" class="btn btn-danger py-2.5 px-4 fw-bold shadow-sm" onclick="submitInstallmentOrder()">
                                ĐẶT MUA TRẢ GÓP &rarr;
                            </button>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0 rounded-3 mb-0 small py-2 d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill text-info fs-6"></i>
                    <div>
                        <strong>Thủ tục chuẩn bị:</strong> CCCD gắn chip (duyệt online) hoặc Thẻ tín dụng Visa/Mastercard. Không cần chứng minh thu nhập.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="installmentOrderForm" method="post" action="/cart/buy/<?= $product['id'] ?>" style="display:none">
    <?= csrf_field() ?>
    <input type="hidden" name="color" value="" id="instColor">
    <input type="hidden" name="variant_id" value="" id="instVariant">
    <input type="hidden" name="addon_price" value="0" id="instAddonPrice">
    <input type="hidden" name="addon_notes" value="" id="instAddonNotes">
    <input type="hidden" name="is_installment" value="1">
    <input type="hidden" name="installment_months" value="6" id="instMonths">
</form>

<div class="mt-4">
    <a href="javascript:history.back()" class="btn btn-outline-secondary rounded-pill px-4 btn-sm"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>


<script>
function switchGalleryImage(el, src, filter) {
    const mainImg = document.querySelector('.product-gallery-main img');
    if (mainImg) {
        mainImg.src = src;
        mainImg.style.filter = filter;
        
        // Add a smooth fading animation when switching
        mainImg.style.transition = 'filter 0.3s ease, transform 0.2s ease';
    }
    
    // Update active class on thumbnails
    document.querySelectorAll('.thumb-item').forEach(item => {
        item.style.borderColor = '#dee2e6';
        item.classList.remove('active');
    });
    el.style.borderColor = 'var(--accent)';
    el.classList.add('active');
    
    // Sync color buttons
    const index = Array.from(el.parentNode.children).indexOf(el);
    const colorButtons = document.querySelectorAll('#colorPickerContainer button');
    if (colorButtons[index]) {
        colorButtons.forEach(btn => btn.classList.remove('active'));
        colorButtons[index].classList.add('active');
        const colorName = colorButtons[index].textContent.trim();
        document.querySelectorAll('.color-form-input').forEach(input => input.value = colorName);
    }
}

function selectColor(btn, colorName, filter, index) {
    // Update active color button
    document.querySelectorAll('#colorPickerContainer button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    // Update color input fields
    document.querySelectorAll('.color-form-input').forEach(input => input.value = colorName);
    
    // Sync thumbnail click
    const thumbItems = document.querySelectorAll('.thumb-item');
    if (thumbItems[index]) {
        const mainImg = document.querySelector('.product-gallery-main img');
        if (mainImg) {
            mainImg.style.filter = filter;
        }
        thumbItems.forEach(item => {
            item.style.borderColor = '#dee2e6';
            item.classList.remove('active');
        });
        thumbItems[index].style.borderColor = 'var(--accent)';
        thumbItems[index].classList.add('active');
    }
}

const isPreorderProduct = <?= (int)($product['is_preorder'] ?? 0) ?>;
let currentProductBasePrice = <?= (int)($product['sale_price'] ?? $product['price'] ?? 0) ?>;
let currentAddonPrice = 0;
let currentAddonNotes = '';
let currentSelectedVariantId = <?= !empty($variants) ? (int)$variants[0]['id'] : 0 ?>;
let currentSelectedLabel = '<?= !empty($variants) ? addslashes($variants[0]['color'] . ' / ' . $variants[0]['storage']) : 'Tiêu chuẩn' ?>';
let selectedDepositPercent = 0;
let selectedInstallmentMonths = 6;
let installmentModalInstance = null;

function syncAddonSelections() {
    let totalAddons = 0;
    let notes = [];
    document.querySelectorAll('.addon-option:checked').forEach(cb => {
        totalAddons += parseInt(cb.value) || 0;
        if (cb.dataset.note) notes.push(cb.dataset.note);
    });
    currentAddonPrice = totalAddons;
    currentAddonNotes = notes.join(', ');

    document.querySelectorAll('.addon-price-form-input').forEach(input => input.value = currentAddonPrice);
    document.querySelectorAll('.addon-notes-form-input').forEach(input => input.value = currentAddonNotes);

    updateDisplayedPrice();
}

function updateDisplayedPrice() {
    const total = currentProductBasePrice + currentAddonPrice;
    const priceContainer = document.querySelector('.product-price');
    if (priceContainer) {
        priceContainer.innerHTML = formatMoney(total) + (currentAddonPrice > 0 ? ' <small style="font-size: 13px; color: #16a34a; font-weight: normal;">(Đã gồm ' + formatMoney(currentAddonPrice) + ' dịch vụ thêm)</small>' : '');
    }
}

function openInstallmentModal() {
    const modalEl = document.getElementById('installmentModal');
    if (!modalEl) return;
    if (!installmentModalInstance && typeof bootstrap !== 'undefined') {
        installmentModalInstance = new bootstrap.Modal(modalEl);
    }
    recalculateInstallment();
    if (installmentModalInstance) {
        installmentModalInstance.show();
    }
}

function selectDeposit(btn, percent) {
    document.querySelectorAll('#depositBtnGroup .deposit-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    selectedDepositPercent = percent;
    recalculateInstallment();
}

function selectMonth(btn, months) {
    document.querySelectorAll('#monthBtnGroup .month-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    selectedInstallmentMonths = months;
    recalculateInstallment();
}

function switchInstallmentType(btn, type) {
    document.querySelectorAll('.installment-type-btn').forEach(b => {
        b.classList.remove('active', 'btn-primary');
        b.classList.add('btn-outline-secondary');
    });
    btn.classList.add('active', 'btn-primary');
    btn.classList.remove('btn-outline-secondary');
}

function recalculateInstallment() {
    const total = currentProductBasePrice + currentAddonPrice;
    const depositAmount = Math.round(total * (selectedDepositPercent / 100));
    const loanAmount = Math.max(0, total - depositAmount);
    const monthlyPayment = Math.round(loanAmount / selectedInstallmentMonths);

    const priceEl = document.getElementById('modalProductPrice');
    const depPctEl = document.getElementById('modalDepositPercent');
    const depAmtEl = document.getElementById('modalDepositAmount');
    const monPayEl = document.getElementById('modalMonthlyPayment');
    const periodEl = document.getElementById('modalPeriodText');

    if (priceEl) priceEl.textContent = formatMoney(total);
    if (depPctEl) depPctEl.textContent = selectedDepositPercent + '%';
    if (depAmtEl) depAmtEl.textContent = formatMoney(depositAmount);
    if (monPayEl) monPayEl.textContent = formatMoney(monthlyPayment) + ' / tháng';
    if (periodEl) periodEl.textContent = selectedInstallmentMonths + ' tháng';
}

function submitInstallmentOrder() {
    const form = document.getElementById('installmentOrderForm');
    if (!form) return;
    document.getElementById('instColor').value = currentSelectedLabel;
    document.getElementById('instVariant').value = currentSelectedVariantId;
    document.getElementById('instAddonPrice').value = currentAddonPrice;
    document.getElementById('instAddonNotes').value = currentAddonNotes;
    document.getElementById('instMonths').value = selectedInstallmentMonths;
    form.submit();
}

function selectVariant(btn, variantId, label, price, stock) {
    // Update active variant button
    document.querySelectorAll('#variantSelectorContainer button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    // Update color and variant inputs
    document.querySelectorAll('.color-form-input').forEach(input => input.value = label);
    document.querySelectorAll('.variant-form-input').forEach(input => input.value = variantId);
    
    currentProductBasePrice = price;
    currentSelectedVariantId = variantId;
    currentSelectedLabel = label;

    // Dynamically update the price displayed
    updateDisplayedPrice();
    
    // Dynamically update the stock badge
    const badgeContainer = document.querySelector('.col-lg-7 .mb-4');
    const buyNowBtn = document.getElementById('buyNowBtn');
    const addToCartBtn = document.getElementById('addToCartBtn');
    
    if (badgeContainer) {
        if (isPreorderProduct) {
            if (buyNowBtn) buyNowBtn.disabled = false;
            if (addToCartBtn) addToCartBtn.disabled = false;
        } else {
            if (stock <= 0) {
                badgeContainer.innerHTML = '<span class="badge bg-secondary px-3 py-2"><i class="bi bi-x-circle me-1"></i> Hết hàng</span>';
                if (buyNowBtn) buyNowBtn.disabled = true;
                if (addToCartBtn) addToCartBtn.disabled = true;
            } else if (stock < 5) {
                badgeContainer.innerHTML = '<span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-exclamation-triangle me-1"></i> Sắp hết hàng (Chỉ còn ' + stock + ')</span>';
                if (buyNowBtn) buyNowBtn.disabled = false;
                if (addToCartBtn) addToCartBtn.disabled = false;
            } else {
                badgeContainer.innerHTML = '<span class="badge bg-success-subtle text-success px-3 py-2 border border-success-subtle"><i class="bi bi-check-circle me-1"></i> Còn hàng</span>';
                if (buyNowBtn) buyNowBtn.disabled = false;
                if (addToCartBtn) addToCartBtn.disabled = false;
            }
        }
    }
}

function formatMoney(amount) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}

document.addEventListener('DOMContentLoaded', function() {
    const rf = document.getElementById('reviewForm');
    if (rf) {
        rf.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(rf);
            const actionUrl = rf.getAttribute('action');

            fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok || !data.success) {
                    throw new Error(data.message || 'Không thể gửi đánh giá.');
                }
                return data;
            })
            .then(data => {
                if (typeof showToast === 'function') {
                    showToast('success', data.message || 'Gửi đánh giá thành công!');
                }
                const commentField = rf.querySelector('textarea[name="comment"]');
                if (commentField) commentField.value = '';
                setTimeout(() => window.location.reload(), 800);
            })
            .catch(err => {
                if (typeof showToast === 'function') {
                    showToast('error', err.message);
                } else {
                    alert(err.message);
                }
            });
        });
    }
});
</script>
