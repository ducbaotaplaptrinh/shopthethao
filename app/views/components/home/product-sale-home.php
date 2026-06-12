<section class="product-sale py-4">

    <div class="container-xl">

        <div class="d-flex align-items-center justify-content-between mb-4">

            <h2 class="product-sale__title">
                Sản phẩm giảm giá
            </h2>

            <a
                href="?page=product-index"
                class="sale-view-all">
                Xem tất cả
            </a>

        </div>
        <?php if (isset($sanPhamSale) && is_array($sanPhamSale)): ?>

            <div class="sale-frame p-3 ">

                <div class="swiper sale-product-swiper">

                    <div class="swiper-wrapper">
                        <?php foreach ($sanPhamSale as $index => $sps): ?>
                            <a href="#!"
                                class="swiper-slide sale-product-item">

                                <div class="card sale-card border-0">
                                    <div class="sale-badge">
                                        <?php echo htmlspecialchars($sps->getPhanTramGiam(), ENT_QUOTES, 'UTF-8') ?>%
                                    </div>

                                    <div
                                        class="sale-thumb bg-white">

                                        <img
                                            src="<?php echo htmlspecialchars(getProductImage($sps->getAnh_dai_dien()), ENT_QUOTES, 'UTF-8') ?>"
                                            alt="<?php echo htmlspecialchars($sps->getTen_san_pham(), ENT_QUOTES, 'UTF-8') ?>"
                                            onerror="handleImageError(this)">

                                    </div>

                                    <!-- INFO -->
                                    <div class="sale-info p-3">

                                        <h6 class="sale-name">
                                            <?php echo htmlspecialchars($sps->getTen_san_pham(), ENT_QUOTES, 'UTF-8') ?>
                                        </h6>

                                        <div class="sale-prices">

                                            <span class="sale-price-new">

                                                <?php echo htmlspecialchars(formatVND($sps->getGia_khuyen_mai()), ENT_QUOTES, 'UTF-8') ?>

                                            </span>

                                            <span class="sale-price-old">

                                                <?php echo htmlspecialchars(formatVND($sps->getGia_ban()), ENT_QUOTES, 'UTF-8') ?>

                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </a>
                        <?php endforeach; ?>



                    </div>

                    <div class="swiper-button-prev sale-prev"></div>

                    <div class="swiper-button-next sale-next"></div>

                </div>

            </div>
        <?php endif ?>

    </div>

</section>