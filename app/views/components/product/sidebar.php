<?php

?>
<div class="sidebar-filter">
    <div class="sidebar-header">
        <h3><i class="fas fa-filter"></i> Bộ lọc sản phẩm</h3>
    </div>

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
    <?php if (!empty($danhSachGiaTri)):
        $result = []; ?>
        <?php foreach ($danhSachGiaTri as $gt) {
            if (!isset($result[$gt['tenThuocTinh']])) {
                $result[$gt['tenThuocTinh']] = [];
            }
            $result[$gt['tenThuocTinh']][] = ['gt' => $gt['giaTri'], 'id' => $gt['id']];
        }
        ?>

        <?php foreach ($result as $i => $item):
        ?>
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