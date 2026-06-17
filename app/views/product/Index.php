<?php

// Kiểm tra xem có tồn tại danh sach sp co gia trị ko 
if ((!empty($danhSachSanPham) && is_array($danhSachSanPham))) {
    foreach ($danhSachSanPham as $item) {
    }
}
?>
<div class="container-xl py-4">
    <?php
    // Determine current page slug for form actions
    $currentPageSlug = $_GET['page'] ?? 'product-index';
    ?>
    <form id="filterForm" method="GET" action="">
        <input type="hidden" name="page" value="<?= htmlspecialchars($currentPageSlug) ?>">
        <?php if (!empty($slugDM)): ?>
            <input type="hidden" name="category" value="<?= htmlspecialchars($slugDM) ?>">
        <?php endif; ?>


        <!-- Breadcrumb -->
        <div class="breadcrumb-wrapper mb-4">
            <a href="?page=home">Trang chủ</a>
            <?php if ($currentPageSlug === 'flash-sale'): ?>
                <span> > </span>
                <a href="?page=flash-sale" class="<?= empty($slugDM) && empty($slugTH) ? 'fw-bold text-danger' : '' ?>">🔥 Giảm giá</a>
                <?php if (!empty($tenDanhMucMD)): ?>
                    <span> > </span>
                    <a href="#!"><?= htmlspecialchars($tenDanhMucMD->getTen_danh_muc()) ?></a>
                <?php endif; ?>
                <?php if (!empty($tenThuongHieuMD)): ?>
                    <span> > </span>
                    <a href="#!"><?= htmlspecialchars($tenThuongHieuMD->getTen_thuong_hieu()) ?></a>
                <?php endif; ?>
            <?php else: ?>
                <?php if (!empty($tenDanhMucMD)): ?>
                    <span> > </span>
                    <a href="?page=product-index&category=<?= htmlspecialchars($slugDM) ?>"><?= htmlspecialchars($tenDanhMucMD->getTen_danh_muc()) ?></a>
                <?php endif; ?>
                <?php if (!empty($tenThuongHieuMD)): ?>
                    <span> > </span>
                    <a href="#!"><?= htmlspecialchars($tenThuongHieuMD->getTen_thuong_hieu()) ?></a>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php if ($currentPageSlug === 'flash-sale'): ?>
            <div class="flash-sale-banner mb-4 rounded-4 p-4 d-flex align-items-center gap-3"
                style="background: linear-gradient(135deg, #ff3c00, #ff9000); color:#fff; box-shadow: 0 6px 24px rgba(255,60,0,0.3);">
                <span style="font-size:2.5rem;">🔥</span>
                <div>
                    <h2 class="mb-0 fw-bold" style="font-size:1.5rem; letter-spacing:1px;">FLASH SALE — SẢN PHẨM KHUYẾN MÃI</h2>
                    <p class="mb-0 small opacity-75">Hàng ngàn sản phẩm thể thao đang được giảm giá cực sốc, số lượng có hạn!</p>
                </div>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Sidebar -->
            <aside class="col-lg-3">

                <div id="filterSidebar"

                    class="bg-white rounded shadow-sm p-3">
                    <?php require BASE_PATH . "/app/views/components/product/Sidebar.php" ?>
                </div>

            </aside>

            <!-- Content -->
            <main class="col-lg-9">

                <!-- Toolbar -->
                <div class="toolbar bg-white rounded shadow-sm p-3 mb-4">

                    <div class="row align-items-center">

                        <div class="col-md-6">
                            Hiển thị <?= !empty($danhSachSanPham) ? count($danhSachSanPham) : 0 ?> / <?= $totalProducts ?? 0 ?> sản phẩm
                        </div>

                        <div class="col-md-6 text-md-end">

                            <select name="sort" class="form-select d-inline-block w-auto" onchange="document.getElementById('filterForm').submit()">
                                <option value="newest" <?= ($sort ?? '') === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                                <option value="price-asc" <?= ($sort ?? '') === 'price-asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                                <option value="price-desc" <?= ($sort ?? '') === 'price-desc' ? 'selected' : '' ?>>Giá giảm dần</option>
                            </select>

                        </div>

                    </div>

                </div>

                <!-- Product Grid -->
                <div id="productGrid">


                    <div class="row g-3">
                        <?php require BASE_PATH . "/app/views/components/product/ListProduct.php" ?>



                    </div>

                </div>

                <!-- Pagination -->
                <div id="pagination" class="mt-5 d-flex justify-content-center">
                    <?php if (isset($totalPages) && $totalPages > 1): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0">
                                <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= ($currentPage <= 1) ? '#' : getPaginationUrl($currentPage - 1) ?>" aria-label="Trước">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= ($i === $currentPage) ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= getPaginationUrl($i) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= ($currentPage >= $totalPages) ? '#' : getPaginationUrl($currentPage + 1) ?>" aria-label="Sau">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <section class="category-description bg-white rounded shadow-sm p-4 mt-5">

                    <?php require BASE_PATH . "/app/views/components/product/GioiThieu.php" ?>

                </section>

            </main>

        </div>
    </form>
</div>