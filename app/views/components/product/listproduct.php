<?php if (isset($danhSachSanPham) && is_array($danhSachSanPham) && count($danhSachSanPham) > 0): ?>
    <?php foreach ($danhSachSanPham as $p): ?>
        <div class="col-6 col-md-3 ">
            <a href="?page=product-detail&slug=<?= htmlspecialchars($p['item']->getDuong_dan_slug(), ENT_QUOTES, 'UTF-8') ?>" class="swiper-slide product-item" data-category-id="<?php echo htmlspecialchars($p['item']->getMa_danh_muc() ?? 0) ?>">
                <div class="card product-card">
                    <div class="card-body p-0">
                        <?php if ($p['item']->getGia_khuyen_mai() > 0): ?>
                            <div class="product-badge">-<?= $p['item']->getPhanTramGiam() ?>%</div>
                        <?php endif; ?>
                        <div class="product-thumb bg-white d-flex align-items-center justify-content-center" style="height:220px;">
                            <img src="<?php echo htmlspecialchars(getProductImage($p['item']->getAnh_dai_dien())) ?>" alt="<?php echo htmlspecialchars($p['item']->getTen_san_pham()) ?>" onerror="handleImageError(this)" style="max-height:200px; width:auto; object-fit:contain;">
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
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="col-12 text-center py-5">
        <div class="mb-3">
            <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
        </div>
        <h5 class="text-muted">Không tìm thấy sản phẩm nào phù hợp</h5>
        <p class="text-muted small">Hãy thử điều chỉnh bộ lọc hoặc chọn thương hiệu khác.</p>
    </div>
<?php endif; ?>