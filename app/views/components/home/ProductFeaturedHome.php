<?php if (isset($sanPhamMoi) && is_array($sanPhamMoi)):
    $result = [];

    foreach ($sanPhamMoi as $index => $p) {
        if (!isset($result['tenDanhMuc'])) {
            $result[$p['tenDanhMuc']] = [];
        }
        $result[$p['tenDanhMuc']][] = ['sanpham' => $p['item']];
        extract($p);
    }
?>
    <section class="product-new py-4 my-16-mobile py-0-mobile">
        <div class="container-xl ">
            <h2 class="product-new__title ">
                Sản phẩm nổi bật
            </h2>
            <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                <div class="swiper product-cats-swiper flex-grow-1">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div
                                class="product-cat-btn "
                                role="button"
                                tabindex="0"
                                data-category-id="all">
                                Tất cả
                            </div>
                        </div>


                        <?php
                        foreach ($result as $i => $s):
                            extract($s[0]);
                        ?>
                            <div class="swiper-slide">

                                <div
                                    class="product-cat-btn <?php echo $i === 0 ? 'active' : '' ?> "
                                    role="button"
                                    tabindex="0"
                                    data-category-id="<?php echo  htmlspecialchars($sanpham->getMa_danh_muc()) ?>">
                                    <?php echo htmlspecialchars($i) ?>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php endif; ?>
                    </div>
                </div>
            </div>



            <div class="product-frame p-3  rounded">
                <div class=" swiper product-swiper ">
                    <div class="swiper-wrapper">
                        <?php if (isset($sanPhamMoi) && is_array($sanPhamMoi)): ?>
                            <?php foreach ($sanPhamMoi as $p): ?>
                                <a href="?page=product-detail&slug=<?= htmlspecialchars($p['item']->getDuong_dan_slug(), ENT_QUOTES, 'UTF-8') ?>" class="swiper-slide product-item" data-category-id="<?php echo htmlspecialchars($p['item']->getMa_danh_muc() ?? 0) ?>">
                                    <div class="card product-card">
                                        <div class="card-body p-0">
                                            <div class="product-badges">
                                                <?php if ($p['item']->getGia_khuyen_mai() > 0): ?>
                                                    <span class="product-badge bg-danger">-<?= $p['item']->getPhanTramGiam() ?>%</span>
                                                <?php endif; ?>
                                                <?php if ($p['item']->isNew()): ?>
                                                    <span class="product-badge bg-success">NEW</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="product-thumb bg-white">
                                                <img src="<?php echo htmlspecialchars(getProductImage("assets/images/products/" . $p['item']->getAnh_dai_dien())) ?>" alt="<?php echo htmlspecialchars($p['item']->getTen_san_pham()) ?>" onerror="handleImageError(this)">
                                            </div>
                                            <div class="p-3  product-item__info">
                                                <h6 class="product-name mb-2" style="min-height:48px;"><?php echo htmlspecialchars($p['item']->getTen_san_pham()) ?></h6>
                                                <div class="product-price text-danger fw-bold">
                                                    <?php if ($p['item']->getGia_khuyen_mai() > 0): ?>
                                                        <span><?php echo htmlspecialchars(formatVND($p['item']->getGia_khuyen_mai())) ?></span>
                                                        <span class="product-price-old"><?php echo htmlspecialchars(formatVND($p['item']->getGia_ban())) ?></span>
                                                    <?php else: ?>
                                                        <span><?php echo htmlspecialchars(formatVND($p['item']->getGia_ban())) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <!-- Add Arrows -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
        </div>
    </section>