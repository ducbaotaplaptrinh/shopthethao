<?php
// Determine what page we're on for building category links
$currentPageSlug = $_GET['page'] ?? 'product-index';
$isFlashSale = ($currentPageSlug === 'flash-sale');
$basePageParam = $isFlashSale ? 'flash-sale' : 'product-index';
?>
<div class="sidebar-filter">
    <div class="sidebar-header">
        <h3><i class="fas fa-filter"></i> Bộ lọc sản phẩm</h3>
    </div>

    <!-- ========= CATEGORY LIST ========= -->
    <?php if (!empty($sidebarCategories)): ?>
        <div class="filter-group">
            <h4 class="filter-title">
                <?php

                $parentName = 'Danh mục sản phẩm';
                if (!empty($tenDanhMucMD)) {
                    $parentId = $tenDanhMucMD->getMa_danh_muc_cha();
                    if ($parentId !== null) {
                        // We're in a sub-category, find the parent from the full list
                        foreach (($dsDanhMuc ?? []) as $dm) {
                            if ($dm->getId() === $parentId) {
                                $parentName = htmlspecialchars($dm->getTen_danh_muc());
                                break;
                            }
                        }
                    }
                }
                echo $parentName;
                ?>
            </h4>
            <div class="filter-content">
                <?php foreach ($sidebarCategories as $cat): ?>
                    <?php
                    $catSlug = $cat->getDuong_dan_slug();
                    $catName = $cat->getTen_danh_muc();
                    $isActive = ($slugDM ?? '') === $catSlug;
                    // Build href: keep brand filter, set category, reset page to 1
                    $href = '?page=' . $basePageParam . '&category=' . urlencode($catSlug);
                    if (!empty($slugTH)) {
                        $href .= '&brand=' . urlencode($slugTH);
                    }
                    ?>
                    <a href="<?= $href ?>"
                        class="filter-item d-flex align-items-center gap-2 text-decoration-none <?= $isActive ? 'active-category' : '' ?>"
                        style="padding: 6px 4px; border-radius:6px; transition: background .15s;
                          <?= $isActive ? 'background:#fff3ec; color:#ff6800; font-weight:700;' : 'color:#444;' ?>">
                        <?php if ($isActive): ?>
                            <i class="fas fa-chevron-right" style="font-size:10px; color:#ff6800;"></i>
                        <?php else: ?>
                            <i class="fas fa-tag" style="font-size:10px; color:#bbb;"></i>
                        <?php endif; ?>
                        <span><?= htmlspecialchars($catName) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- ========= BRAND FILTER ========= -->
    <div class="filter-group">
        <h4 class="filter-title">Thương hiệu</h4>
        <div class="filter-content">
            <?php if (isset($dsThuongHieu)): ?>
                <label class="filter-item">
                    <input type="radio" name="brand" value="" <?= empty($slugTH) ? 'checked' : '' ?>>
                    <span>Tất cả</span>
                </label>
                <?php foreach ($dsThuongHieu as $item): ?>
                    <label class="filter-item">
                        <input type="radio" name="brand" value="<?= htmlspecialchars($item->getDuong_dan_slug()) ?>" <?= ($slugTH ?? '') === $item->getDuong_dan_slug() ? 'checked' : '' ?>>
                        <span><?= htmlspecialchars($item->getTen_thuong_hieu()) ?></span>
                    </label>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- ========= ATTRIBUTE FILTERS ========= -->
    <?php if (!empty($danhSachGiaTri)):
        $result = [];
        foreach ($danhSachGiaTri as $gt) {
            if (!isset($result[$gt['tenThuocTinh']])) {
                $result[$gt['tenThuocTinh']] = [];
            }
            $result[$gt['tenThuocTinh']][] = ['gt' => $gt['giaTri'], 'id' => $gt['id']];
        }
    ?>
        <?php foreach ($result as $i => $item): ?>
            <div class="filter-group">
                <h4 class="filter-title"><?= htmlspecialchars($i) ?></h4>
                <div class="filter-content">
                    <?php foreach ($item as $tt): ?>
                        <label class="filter-item">
                            <input type="checkbox" class="attr-checkbox" name="attrs[]" value="<?= htmlspecialchars($tt['id']) ?>" <?= in_array($tt['id'], $selectedAttrs ?? []) ? 'checked' : '' ?>>
                            <span><?= htmlspecialchars($tt['gt']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <button type="submit" id="btn-apply-filter" class="btn-filter">Áp dụng bộ lọc</button>
</div>