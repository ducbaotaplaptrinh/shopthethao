<?php

if (!isset($sale_products) || !is_array($sale_products)) {

    $sale_products = [];

    for ($i = 1; $i <= 10; $i++) {

        $oldPrice = 1500000 + ($i * 100000);

        $salePrice = $oldPrice - rand(100000, 500000);

        $sale_products[] = [

            'id' => $i,

            'name' => "Vợt Cầu Lông Yonex $i",

            'price_old' => $oldPrice,

            'price_new' => $salePrice,

            'discount_percent' =>
            round(
                (($oldPrice - $salePrice) / $oldPrice) * 100
            ),

            'image' =>
            'assets/images/products/racket-' .
                (($i % 5) + 1) .
                '.webp'
        ];
    }
}
?>
<section class="product-sale py-4">

    <div class="container-xl">

        <div class="d-flex align-items-center justify-content-between mb-4">

            <h2 class="product-sale__title">
                Giảm giá
            </h2>

            <a
                href="#"
                class="sale-view-all">

                Xem tất cả
            </a>

        </div>

        <div class="sale-frame p-3 ">

            <div class="swiper sale-product-swiper">

                <div class="swiper-wrapper">

                    <?php foreach ($sale_products as $p): ?>

                        <a href="#!"
                            class="swiper-slide sale-product-item">

                            <div class="card sale-card border-0">

                                <!-- BADGE -->
                                <div class="sale-badge">

                                    -<?= $p['discount_percent'] ?>%

                                </div>

                                <!-- IMAGE -->
                                <div
                                    class="sale-thumb bg-white">

                                    <img
                                        src="<?= htmlspecialchars($p['image']) ?>"
                                        alt="<?= htmlspecialchars($p['name']) ?>">

                                </div>

                                <!-- INFO -->
                                <div class="sale-info p-3">

                                    <h6 class="sale-name">

                                        <?= htmlspecialchars($p['name']) ?>

                                    </h6>

                                    <div class="sale-prices">

                                        <span class="sale-price-new">

                                            <?= number_format($p['price_new'], 0, ',', '.') ?>đ

                                        </span>

                                        <span class="sale-price-old">

                                            <?= number_format($p['price_old'], 0, ',', '.') ?>đ

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </a>

                    <?php endforeach; ?>

                </div>

                <div class="swiper-button-prev sale-prev"></div>

                <div class="swiper-button-next sale-next"></div>

                <div class="swiper-pagination"></div>

            </div>

        </div>

    </div>

</section>