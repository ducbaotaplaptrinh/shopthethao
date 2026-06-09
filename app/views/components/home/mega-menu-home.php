<div class="dropdown-menu mega-menu__content w-100">
    <div class="container">
        <div class="row g-4">
            <?php if (isset($megaMenu) && is_array($megaMenu)): ?>
                <?php
                $result = [];
                foreach ($megaMenu as $item) {
                    extract($item);
                    if (!isset($result[$tenDanhMuc])) {
                        $result[$tenDanhMuc] = [];
                    }
                    $result[$tenDanhMuc][] = [
                        'tenTH' => $tenThuongHieu,
                        'slugDanhMuc' => $slugDM,
                        'slugThuongHieu' => $slugTH
                    ];
                }
                ?>

                <?php foreach ($result as $tenDM => $ds): ?>

                    <div class="col-6 col-lg-3">
                        <h4 class="mega-menu__title"><?= htmlspecialchars($tenDM, ENT_QUOTES, "utf-8") ?></h4>

                        <ul class="mega-menu_list">
                            <?php foreach ($ds as $item): ?>
                                <?php
                                extract($item);
                                ?>
                                <li class="mega-menu__item">
                                    <a href="?page=product-index&category=<?= htmlspecialchars($slugDanhMuc, ENT_QUOTES, 'UTF-8') ?>&brand=<?= htmlspecialchars($slugThuongHieu, ENT_QUOTES, 'UTF-8') ?>" class="mega-menu__link"><?= htmlspecialchars($tenDM . " " . $tenTH, ENT_QUOTES, 'UTF-8') ?></a>
                                </li>
                            <?php endforeach; ?>

                        </ul>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>