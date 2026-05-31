<?php
if (!isset($categories) || !is_array($categories)) {
    $categories = [
        ['id' => 0, 'name' => 'Tất cả'],
        ['id' => 1, 'name' => 'Vợt Cầu Lông'],
        ['id' => 2, 'name' => 'Giày Cầu Lông'],
        ['id' => 3, 'name' => 'Áo Cầu Lông'],
        ['id' => 4, 'name' => 'Váy cầu lông'],
        ['id' => 5, 'name' => 'Quần Cầu Lông'],
        ['id' => 0, 'name' => 'Tất cả'],
        ['id' => 1, 'name' => 'Vợt Cầu Lông'],
        ['id' => 2, 'name' => 'Giày Cầu Lông'],
        ['id' => 3, 'name' => 'Áo Cầu Lông'],
        ['id' => 4, 'name' => 'Váy cầu lông'],
        ['id' => 5, 'name' => 'Quần Cầu Lông'],

    ];
}

if (!isset($products) || !is_array($products)) {

    $products = [];
    for ($i = 1; $i <= 8; $i++) {
        $products[] = [
            'id' => $i,
            'category_id' => ($i % 5) + 1,
            'name' => "Vợt Cầu Lông Mẫu $i",
            'price' => 529000 + ($i % 5) * 50000,
            'image' => 'assets/images/products/racket-' . ($i % 5 + 1) . '.webp'
        ];
    }
}
?>

<section class="product-new py-4">
    <div class="container-xl ">
        <h2 class="product-new__title ">
            Sản phẩm mới
        </h2>
        <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
            <div class="swiper product-cats-swiper flex-grow-1">
                <div class="swiper-wrapper">
                    <?php foreach ($categories as $idx => $cat): ?>
                        <div class="swiper-slide">
                            <div
                                class="product-cat-btn <?php echo $idx === 0 ? 'active' : '' ?>"
                                role="button"
                                tabindex="0"
                                data-category-id="<?php echo htmlspecialchars($cat['id']) ?>">
                                <?php echo htmlspecialchars($cat['name']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="product-frame p-3  rounded">
            <div class=" swiper product-swiper ">
                <div class="swiper-wrapper">
                    <?php foreach ($products as $p): ?>
                        <a href="#!" class="swiper-slide product-item" data-category-id="<?php echo htmlspecialchars($p['category_id'] ?? 0) ?>">
                            <div class="card product-card">
                                <div class="card-body p-0">
                                    <div class="product-thumb bg-white d-flex align-items-center justify-content-center" style="height:220px;">
                                        <img src="<?php echo htmlspecialchars($p['image']) ?>" alt="<?php echo htmlspecialchars($p['name']) ?>" style="max-height:200px; width:auto; object-fit:contain;">
                                    </div>
                                    <div class="p-3  product-item__info">
                                        <h6 class="product-name mb-2" style="min-height:48px;"><?php echo htmlspecialchars($p['name']) ?></h6>
                                        <div class="product-price text-danger fw-bold"><?php echo number_format($p['price'], 0, ',', '.') ?> đ</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <!-- Add Arrows -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
</section>