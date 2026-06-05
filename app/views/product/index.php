<?php
$pageStyles = ['assets/css/category.css'];
$pageScripts = ['assets/js/category.js'];

$categoryName = trim((string) ($_GET['category'] ?? 'Vợt cầu lông'));
$brandName = trim((string) ($_GET['brand'] ?? 'Yonex'));
$pageLeaf = trim((string) ($_GET['title'] ?? ($categoryName . ' ' . $brandName)));

$priceRanges = [
    ['label' => 'Giá dưới 500.000đ', 'value' => 'under500'],
    ['label' => '500.000đ - 1 triệu', 'value' => '500-1000'],
    ['label' => '1 - 2 triệu', 'value' => '1000-2000'],
    ['label' => '2 - 3 triệu', 'value' => '2000-3000'],
    ['label' => 'Giá trên 3 triệu', 'value' => 'over3000'],
];

$weightFilters = [
    ['label' => '4U - 5U', 'value' => '4u-5u'],
    ['label' => '3U', 'value' => '3u'],
    ['label' => '2U', 'value' => '2u'],
    ['label' => 'Siêu nhẹ', 'value' => 'super-light'],
];

$brandFilters = ['Yonex', 'Victor', 'Lining', 'Mizuno', 'Kawasaki', 'Apacs'];

$styleFilters = ['Tấn công', 'Công thủ toàn diện', 'Phòng thủ', 'Cân bằng'];

$techFilters = ['Namd', 'Isometric', 'AERO+BOX', 'Energy Boost CAP', 'EX HMG', 'Rotational Generator'];

$seriesTiles = [
    ['label' => 'ArcSaber', 'subtitle' => 'Cân bằng - kiểm soát', 'tone' => 'blue'],
    ['label' => 'Nanoray', 'subtitle' => 'Tốc độ - phản tạt', 'tone' => 'slate'],
    ['label' => 'Astrox', 'subtitle' => 'Đập cầu - đầu nặng', 'tone' => 'orange'],
    ['label' => 'Nanoflare', 'subtitle' => 'Nhẹ - linh hoạt', 'tone' => 'teal'],
    ['label' => 'Duora', 'subtitle' => 'Hai mặt lối đánh', 'tone' => 'red'],
    ['label' => 'Voltric', 'subtitle' => 'Uy lực - smash', 'tone' => 'indigo'],
];

$sortOptions = [
    'featured' => 'Nổi bật',
    'newest' => 'Mới nhất',
    'price-asc' => 'Giá tăng dần',
    'price-desc' => 'Giá giảm dần',
    'rating' => 'Đánh giá cao',
];

$products = [
    [
        'name' => 'Vợt Cầu Lông Yonex Astrox Lite 37i',
        'price' => 709000,
        'old_price' => 799000,
        'badge' => 'Premium',
        'discount' => '-11%',
        'rating' => 4.9,
        'reviews' => 124,
        'weight' => '4U',
        'brand' => 'Yonex',
        'style' => 'Tấn công',
        'tech' => ['Namd', 'AERO+BOX'],
        'series' => 'Astrox',
        'accent' => '#ff6b35',
    ],
    [
        'id' => 2,
        'name' => 'Vợt Cầu Lông Yonex Astrox 22 Lite (BK/RD) Chính Hãng',
        'slug' => 'yonex-astrox-22-lite',
        'price' => 2349000,
        'old_price' => 2749000,
        'badge' => 'Premium',
        'discount' => '-15%',
        'rating' => 4.8,
        'reviews' => 88,
        'weight' => '3U',
        'brand' => 'Yonex',
        'style' => 'Công thủ toàn diện',
        'tech' => ['Rotational Generator', 'Isometric'],
        'series' => 'Astrox',
        'accent' => '#de3c4b',
    ],
    [
        'id' => 3,
        'name' => 'Vợt Cầu Lông Yonex Astrox 99 Tour 2025',
        'slug' => 'yonex-astrox-99-tour',
        'price' => 4890000,
        'old_price' => 5159000,
        'badge' => 'Premium',
        'discount' => '-5%',
        'rating' => 5.0,
        'reviews' => 53,
        'weight' => '3U',
        'brand' => 'Yonex',
        'style' => 'Tấn công',
        'tech' => ['Namd', 'EX HMG'],
        'series' => 'Astrox',
        'accent' => '#7c3aed',
    ],
    [
        'id' => 4,
        'name' => 'Vợt Cầu Lông Yonex Nanoflare Junior',
        'slug' => 'yonex-nanoflare-junior',
        'price' => 1719000,
        'old_price' => null,
        'badge' => 'New',
        'discount' => null,
        'rating' => 4.7,
        'reviews' => 41,
        'weight' => '4U',
        'brand' => 'Yonex',
        'style' => 'Cân bằng',
        'tech' => ['Isometric', 'AERO+BOX'],
        'series' => 'Nanoflare',
        'accent' => '#0ea5e9',
    ],
    [
        'id' => 5,
        'name' => 'Vợt Cầu Lông Yonex ArcSaber 11 Pro',
        'slug' => 'yonex-arcsaber-11-pro',
        'price' => 1259000,
        'old_price' => 1350000,
        'badge' => 'Bán chạy',
        'discount' => '-7%',
        'rating' => 4.8,
        'reviews' => 78,
        'weight' => '4U',
        'brand' => 'Yonex',
        'style' => 'Công thủ toàn diện',
        'tech' => ['Isometric', 'Namd'],
        'series' => 'ArcSaber',
        'accent' => '#0f766e',
    ],
    [
        'id' => 6,
        'name' => 'Vợt Cầu Lông Yonex Voltric 70 Neo',
        'slug' => 'yonex-voltric-70-neo',
        'price' => 1259000,
        'old_price' => null,
        'badge' => 'Premium',
        'discount' => null,
        'rating' => 4.6,
        'reviews' => 36,
        'weight' => '3U',
        'brand' => 'Yonex',
        'style' => 'Tấn công',
        'tech' => ['Rotational Generator', 'EX HMG'],
        'series' => 'Voltric',
        'accent' => '#ea580c',
    ],
];
?>

<div class="category-page container-xl py-4 py-lg-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb custom-breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="?page=home">Trang chủ</a></li>
            <li class="breadcrumb-item"><?php echo htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'); ?></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($pageLeaf, ENT_QUOTES, 'UTF-8'); ?></li>
        </ol>
    </nav>

    <section class="category-shell row g-4 align-items-start">
        <aside class="col-lg-3">
            <div class="filter-toggle d-lg-none mb-3">
                <button type="button" class="btn btn-filter w-100 js-open-filters">
                    <i class="fa-solid fa-sliders me-2"></i>Mở bộ lọc
                </button>
            </div>

            <div class="filter-panel js-filter-panel">
                <div class="filter-panel__head d-flex justify-content-between align-items-center d-lg-none">
                    <strong>Bộ lọc</strong>
                    <button type="button" class="btn-close js-close-filters" aria-label="Đóng bộ lọc"></button>
                </div>

                <div class="filter-card">
                    <h3>Chọn mức giá</h3>
                    <div class="filter-list">
                        <?php foreach ($priceRanges as $item): ?>
                            <label class="filter-check">
                                <input type="checkbox"
                                    data-filter="price"
                                    data-min="<?php echo $item['min']; ?>"
                                    data-max="<?php echo $item['max']; ?>">
                                <span><?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-card">
                    <h3>Trọng lượng</h3>
                    <div class="filter-list">
                        <?php foreach ($weightFilters as $item): ?>
                            <label class="filter-check">
                                <input type="checkbox" data-filter="weight" value="<?php echo htmlspecialchars($item['value'], ENT_QUOTES, 'UTF-8'); ?>">
                                <span><?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-card">
                    <h3>Thương hiệu</h3>
                    <div class="filter-list filter-list--inline">
                        <?php foreach ($brandFilters as $item): ?>
                            <label class="filter-check filter-check--pill">
                                <input type="checkbox" data-filter="brand" value="<?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $item === 'Yonex' ? 'checked' : ''; ?>>
                                <span><?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-card">
                    <h3>Phong cách chơi</h3>
                    <div class="filter-list">
                        <?php foreach ($styleFilters as $item): ?>
                            <label class="filter-check">
                                <input type="checkbox" data-filter="style" value="<?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>">
                                <span><?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-card mb-0">
                    <h3>Công nghệ</h3>
                    <div class="filter-search input-group mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="form-control" placeholder="Tìm công nghệ" data-filter-search>
                    </div>
                    <div class="filter-list filter-list--scroll js-tech-list">
                        <?php foreach ($techFilters as $item): ?>
                            <label class="filter-check">
                                <input type="checkbox" data-filter="tech" value="<?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>">
                                <span><?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </aside>

        <div class="col-lg-9">
            <div class="category-toolbar mb-4">
                <div>
                    <h1 class="category-toolbar__title mb-1"><?php echo htmlspecialchars($pageLeaf, ENT_QUOTES, 'UTF-8'); ?></h1>
                    <p class="category-toolbar__meta mb-0">
                        <span data-result-count><?php echo count($products); ?></span> sản phẩm mẫu · cập nhật cho màn hình demo
                    </p>
                </div>

                <div class="category-toolbar__actions">
                    <div class="dropdown sort-dropdown">
                        <button class="btn sort-button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-sort me-2"></i>Sắp xếp: <span data-sort-label>Nổi bật</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-2 shadow-lg">
                            <?php foreach ($sortOptions as $key => $label): ?>
                                <li>
                                    <button type="button" class="dropdown-item sort-item <?php echo $key === 'featured' ? 'active' : ''; ?>" data-sort-value="<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <button type="button" class="btn btn-filter d-lg-none js-open-filters">
                        <i class="fa-solid fa-filter me-2"></i>Lọc
                    </button>
                </div>
            </div>

            <div class="product-grid row g-4 js-product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="col-sm-6 col-xl-4 js-product-item"
                        data-product-card
                        data-price="<?php echo htmlspecialchars((string) $product['price'], ENT_QUOTES, 'UTF-8'); ?>"
                        data-old-price="<?php echo htmlspecialchars((string) ($product['old_price'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                        data-weight="<?php echo htmlspecialchars(strtolower((string) $product['weight']), ENT_QUOTES, 'UTF-8'); ?>"
                        data-brand="<?php echo htmlspecialchars($product['brand'], ENT_QUOTES, 'UTF-8'); ?>"
                        data-style="<?php echo htmlspecialchars($product['style'], ENT_QUOTES, 'UTF-8'); ?>"
                        data-tech="<?php echo htmlspecialchars(implode(',', $product['tech']), ENT_QUOTES, 'UTF-8'); ?>"
                        data-rating="<?php echo htmlspecialchars((string) $product['rating'], ENT_QUOTES, 'UTF-8'); ?>"
                        data-series="<?php echo htmlspecialchars($product['series'], ENT_QUOTES, 'UTF-8'); ?>">
                        <article class="product-card h-100">
                            <div class="product-card__art" style="--accent: <?php echo htmlspecialchars($product['accent'], ENT_QUOTES, 'UTF-8'); ?>;">
                                <?php if ($product['discount']): ?>
                                    <span class="product-card__discount"><?php echo htmlspecialchars($product['discount'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php endif; ?>
                                <span class="product-card__badge"><?php echo htmlspecialchars($product['badge'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <div class="racket-fake">
                                    <span class="racket-fake__head"></span>
                                    <span class="racket-fake__shaft"></span>
                                    <span class="racket-fake__grip"></span>
                                </div>
                                <div class="product-card__series"><?php echo htmlspecialchars($product['series'], ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>

                            <div class="product-card__body">
                                <div class="product-card__rating">
                                    <span><i class="fa-solid fa-star"></i> <?php echo number_format((float) $product['rating'], 1); ?></span>
                                    <small>(<?php echo number_format((int) $product['reviews']); ?> đánh giá)</small>
                                </div>

                                <h3 class="product-card__title"><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h3>

                                <div class="product-card__tags">
                                    <span><?php echo htmlspecialchars($product['weight'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span><?php echo htmlspecialchars($product['style'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>

                                <div class="product-card__tech">
                                    <?php foreach ($product['tech'] as $tech): ?>
                                        <span><?php echo htmlspecialchars($tech, ENT_QUOTES, 'UTF-8'); ?></span>
                                    <?php endforeach; ?>
                                </div>

                                <div class="product-card__price">
                                    <strong><?php echo number_format((float) $product['price'], 0, ',', '.'); ?> đ</strong>
                                    <?php if (!empty($product['old_price'])): ?>
                                        <del><?php echo number_format((float) $product['old_price'], 0, ',', '.'); ?> đ</del>
                                    <?php endif; ?>
                                </div>

                                <div class="product-card__actions">
                                    <a href="?page=product&id=<?php echo $product['id']; ?>" class="btn btn-buy flex-grow-1">Xem chi tiết</a>
                                    <button type="button" class="btn btn-outline-secondary btn-icon"><i class="fa-regular fa-heart"></i></button>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="pagination-wrap mt-4 mt-lg-5">
                <nav aria-label="Phân trang danh mục">
                    <ul class="pagination justify-content-center js-pagination mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#"><i class="fa-solid fa-chevron-left"></i></a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#"><i class="fa-solid fa-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>

            <section class="category-description mt-4 mt-lg-5">
                <div class="section-heading mb-3">
                    <span class="section-kicker"><i class="fa-solid fa-pen-nib"></i> Mô tả danh mục</span>
                    <h2 class="mb-0">Vợt cầu lông Yonex</h2>
                </div>

                <div class="description-card">
                    <p>
                        Yonex là thương hiệu vợt được nhiều người chơi yêu thích nhờ khả năng cân bằng giữa độ bền, cảm giác cầu và
                        công nghệ khung vợt. Ở trang mẫu này, phần mô tả dài ở cuối trang đóng vai trò như nội dung SEO và giải thích
                        giúp người dùng hiểu nhanh dòng vợt phù hợp với lối đánh nào.
                    </p>
                    <p>
                        Khi bạn thay dữ liệu thật bằng PHP, phần này có thể lấy từ bảng danh mục hoặc từ một trường mô tả riêng của
                        category. Cấu trúc hiện tại đã có breadcrumb, bộ lọc theo giá, trọng lượng, thương hiệu, phong cách chơi, công nghệ
                        và phân trang để khớp với cách hiển thị trong ảnh tham chiếu của bạn.
                    </p>
                    <p class="mb-0">
                        Nếu sau này bạn muốn nối DB, chỉ cần thay các mảng giả bằng dữ liệu từ `categories`, `brands`, `products`,
                        `product_variants` và `variant_attribute_values`, còn layout và JS vẫn dùng lại nguyên trạng.
                    </p>
                </div>
            </section>
        </div>
    </section>
</div>