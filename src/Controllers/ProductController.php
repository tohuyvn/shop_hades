<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Product;

class ProductController
{
    public function index(): void
    {
        $productModel = new Product();
        $categoryModel = new Category();
        $categories = $categoryModel->all();

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 12;

        $rawQ = $_GET['q'] ?? null;
        $search = is_scalar($rawQ) ? trim((string) $rawQ) : '';
        $rawCategory = $_GET['category'] ?? null;
        $categoryId = is_scalar($rawCategory) && (int) $rawCategory > 0
            ? (int) $rawCategory
            : null;
        $selectedCategoryId = $categoryId;
        if ($selectedCategoryId === null && $search !== '') {
            $normalizedSearch = mb_strtolower($search, 'UTF-8');
            foreach ($categories as $category) {
                if (mb_strtolower(trim((string) $category['name']), 'UTF-8') === $normalizedSearch) {
                    $selectedCategoryId = (int) $category['id'];
                    break;
                }
            }
        }

        $normalizeList = static function (mixed $value, array $allowed): array {
            $values = is_array($value) ? $value : ($value === null || $value === '' ? [] : [$value]);
            $values = array_filter($values, 'is_scalar');
            $values = array_map(static fn (mixed $item): string => trim((string) $item), $values);
            return array_values(array_unique(array_intersect($allowed, $values)));
        };
        $allowedSorts = ['newest', 'oldest', 'price_asc', 'price_desc', 'bestseller', 'rating'];
        $rawSort = $_GET['sort'] ?? null;
        $sort = is_scalar($rawSort) ? (string) $rawSort : 'newest';
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'newest';
        }

        $rawInStock = $_GET['in_stock'] ?? null;
        $inStock = is_scalar($rawInStock) ? (string) $rawInStock : '';
        $rawMinPrice = $_GET['min_price'] ?? null;
        $rawMaxPrice = $_GET['max_price'] ?? null;
        $filters = [
            'category_id' => $selectedCategoryId,
            'search' => $search,
            'min_price' => is_scalar($rawMinPrice) ? $rawMinPrice : null,
            'max_price' => is_scalar($rawMaxPrice) ? $rawMaxPrice : null,
            'in_stock' => in_array($inStock, ['1', 'true', 'on'], true),
            'sort' => $sort,
            'ram' => $normalizeList($_GET['ram'] ?? [], ['4GB', '6GB', '8GB', '12GB', '16GB']),
            'storage' => $normalizeList($_GET['storage'] ?? [], ['64GB', '128GB', '256GB', '512GB', '1TB']),
            'chipset' => $normalizeList($_GET['chipset'] ?? [], ['Apple', 'Snapdragon', 'Intel', 'AMD']),
        ];

        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat['id']] = $cat['name'];
        }

        $result = $productModel->paginateActive($filters, $page, $perPage);
        $products = enrichProductsForDisplay($result['items'], $categoryMap);

        $activePromo = (new \App\Models\SitePromotion())->getActive();

        view('client.products.index', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $selectedCategoryId,
            'search' => $filters['search'],
            'filters' => $filters,
            'activePromo' => $activePromo,
            'pagination' => [
                'total' => $result['total'],
                'page' => $result['page'],
                'total_pages' => $result['total_pages'],
                'per_page' => $perPage
            ]
        ]);
    }

    public function show(int $id): void
    {
        $productModel = new Product();
        $categoryModel = new Category();
        $reviewModel = new \App\Models\Review();
        $product = $productModel->findWithPricing($id);

        if (!$product || ($product['status'] ?? '') !== 'active') {
            http_response_code(404);
            view('client.404');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $recentlyViewedSession = $_SESSION['recently_viewed'] ?? [];
        $recentlyViewedIds = array_diff($recentlyViewedSession, [$id]);

        $recentlyViewed = array_diff($recentlyViewedSession, [$id]);
        array_unshift($recentlyViewed, $id);
        $_SESSION['recently_viewed'] = array_slice($recentlyViewed, 0, 8);

        $category = $categoryModel->find((int) $product['category_id']);
        $product['category_name'] = $category['name'] ?? 'Khác';
        $categories = $categoryModel->all();

        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat['id']] = $cat['name'];
        }

        // Fetch Related Products (same category, excluding current product)
        $rawRelated = $productModel->getRelatedProducts((int) $product['category_id'], $id, 4);
        $relatedProducts = enrichProductsForDisplay($rawRelated, $categoryMap);

        // Fetch Recently Viewed Products
        $rawRecently = $recentlyViewedIds !== [] ? $productModel->getByIds($recentlyViewedIds) : [];
        $recentlyViewedProducts = enrichProductsForDisplay($rawRecently, $categoryMap);

        $reviews = $reviewModel->byProduct($id);
        $ratingStats = $reviewModel->getAverageRating($id);
        $currentUser = authUser();
        $eligibleReviewOrderId = $currentUser
            ? $reviewModel->findEligibleOrderId((int) $currentUser['id'], $id)
            : null;
        $hasReviewed = $currentUser
            ? $reviewModel->hasReviewed((int) $currentUser['id'], $id)
            : false;

        $variantsModel = new \App\Models\ProductVariant();
        $variants = $variantsModel->getForProduct($id, false);

        view('client.products.show', [
            'product' => $product,
            'categories' => $categories,
            'selectedCategory' => (int) $product['category_id'],
            'reviews' => $reviews,
            'ratingStats' => $ratingStats,
            'variants' => $variants,
            'canReview' => $eligibleReviewOrderId !== null && !$hasReviewed,
            'hasReviewed' => $hasReviewed,
            'relatedProducts' => $relatedProducts,
            'recentlyViewedProducts' => $recentlyViewedProducts,
            'stores' => (new \App\Models\Store())->active(),
        ]);
    }

    public function storeReview(int $id): void
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        $respondError = static function (string $message, int $status) use ($isAjax, $id): void {
            if ($isAjax) {
                http_response_code($status);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $message]);
                exit;
            }
            flash('error', $message);
            redirect($status === 401 ? '/login' : '/products/' . $id);
        };

        $user = authUser();
        if (!$user) {
            $respondError('Vui lòng đăng nhập để gửi đánh giá sản phẩm.', 401);
        }

        $product = (new Product())->find($id);
        if (!$product || ($product['status'] ?? '') !== 'active') {
            $respondError('Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.', 404);
        }

        $rating = (int) ($_POST['rating'] ?? 5);
        $comment = trim($_POST['comment'] ?? '');
        if ($rating < 1 || $rating > 5) {
            $respondError('Số sao đánh giá phải từ 1 đến 5.', 422);
        }
        if (mb_strlen($comment) < 5 || mb_strlen($comment) > 2000) {
            $respondError('Nội dung đánh giá cần từ 5 đến 2.000 ký tự.', 422);
        }

        $reviewModel = new \App\Models\Review();
        $orderId = $reviewModel->findEligibleOrderId((int) $user['id'], $id);
        if ($orderId === null) {
            $respondError('Chỉ khách đã thanh toán đơn chứa sản phẩm này mới có thể đánh giá.', 403);
        }
        if ($reviewModel->hasReviewed((int) $user['id'], $id)) {
            $respondError('Bạn đã đánh giá sản phẩm này rồi.', 409);
        }

        $reviewModel->create([
            'product_id' => $id,
            'user_id' => (int) $user['id'],
            'order_id' => $orderId,
            'user_name' => $user['name'],
            'rating' => $rating,
            'comment' => $comment,
            'is_verified' => true,
        ]);

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => true,
                'message' => '⭐ Đánh giá của bạn đã được gửi thành công!',
                'ratingStats' => $reviewModel->getAverageRating($id),
            ]);
            exit;
        }

        flash('success', 'Đánh giá của bạn đã được gửi thành công!');
        redirect('/products/' . $id);
    }

    public function likeReview(int $id): void
    {
        $reviewModel = new \App\Models\Review();
        $reviewModel->like($id);

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }

        redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    public function replyReview(int $id): void
    {
        $userName = trim($_POST['user_name'] ?? '');
        $replyText = trim($_POST['reply_text'] ?? '');

        if ($userName === '') {
            $user = authUser();
            $userName = $user ? $user['name'] : 'Khách ẩn danh';
        }

        if ($replyText !== '') {
            $reviewModel = new \App\Models\Review();
            $reviewModel->addReply($id, $userName, $replyText);

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Phản hồi đã được gửi.']);
                exit;
            }

            flash('success', 'Phản hồi của bạn đã được gửi thành công!');
        }

        redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    public function searchApi(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $search = trim($_GET['q'] ?? '');
        if ($search === '') {
            echo json_encode([]);
            exit;
        }

        $productModel = new Product();
        $products = $productModel->searchActive($search);
        $products = array_values(array_filter(
            $products,
            fn (array $product): bool => Product::matchesSpecialSearch($search, $product)
        ));
        $products = array_slice($products, 0, 8);

        foreach ($products as &$p) {
            $p['image_url'] = imageUrl($p['image'] ?? '');
            $p['formatted_price'] = formatMoney($p['sale_price']);
        }
        unset($p);

        echo json_encode($products);
        exit;
    }

    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');

        $productModel = new Product();
        $products = $productModel->allActive();

        $categoryModel = new Category();
        $categories = $categoryModel->all();

        $domain = 'http://' . ($_SERVER['HTTP_HOST'] ?? '127.0.0.1:8000');

        echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        // Homepage
        echo '  <url>' . PHP_EOL;
        echo '    <loc>' . $domain . '/</loc>' . PHP_EOL;
        echo '    <priority>1.00</priority>' . PHP_EOL;
        echo '  </url>' . PHP_EOL;

        // Static pages
        $staticPages = ['/pages/ve-chung-toi', '/pages/chinh-sach-doi-tra', '/pages/bao-hanh', '/pages/huong-dan-mua-hang'];
        foreach ($staticPages as $page) {
            echo '  <url>' . PHP_EOL;
            echo '    <loc>' . $domain . $page . '</loc>' . PHP_EOL;
            echo '    <priority>0.50</priority>' . PHP_EOL;
            echo '  </url>' . PHP_EOL;
        }

        // Categories
        foreach ($categories as $cat) {
            echo '  <url>' . PHP_EOL;
            echo '    <loc>' . $domain . '/?category=' . $cat['id'] . '</loc>' . PHP_EOL;
            echo '    <priority>0.80</priority>' . PHP_EOL;
            echo '  </url>' . PHP_EOL;
        }

        // Products
        foreach ($products as $prod) {
            echo '  <url>' . PHP_EOL;
            echo '    <loc>' . $domain . '/products/' . $prod['id'] . '</loc>' . PHP_EOL;
            echo '    <priority>0.80</priority>' . PHP_EOL;
            echo '  </url>' . PHP_EOL;
        }

        echo '</urlset>' . PHP_EOL;
        exit;
    }
}
