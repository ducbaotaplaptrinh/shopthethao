<?php

// Kiểm tra xem có tồn tại danh sach sp co gia trị ko 
if ((!empty($danhSachSanPham) && is_array($danhSachSanPham))) {
    foreach ($danhSachSanPham as $item) {
    }
}
?>
<div class="container-xl py-4">
    <form id="filterForm" method="GET" action="">
        <input type="hidden" name="page" value="product-index">
        <?php if (!empty($slugDM)): ?>
            <input type="hidden" name="category" value="<?= htmlspecialchars($slugDM) ?>">
        <?php endif; ?>

        <!-- Breadcrumb -->
        <div class="breadcrumb-wrapper mb-4">
            <a href="?page=home">Trang chủ ></a>
            <a href="#!"> <?php echo !empty($tenDanhMucMD) ? htmlspecialchars($tenDanhMucMD->getTen_danh_muc()) : "" ?> </a>
            <?php if (!empty($tenThuongHieuMD)): ?>

                <a href="#!">
                    <?= " > " . htmlspecialchars(
                        $tenDanhMucMD->getTen_danh_muc()
                            . " " .
                            $tenThuongHieuMD->getTen_thuong_hieu()
                    ) ?>
                </a>

            <?php endif; ?>
        </div>

        <div class="row g-4">
            <!-- Sidebar -->
            <aside class="col-lg-3">

                <div id="filterSidebar"

                    class="bg-white rounded shadow-sm p-3">
                    <?php require BASE_PATH . "/app/views/components/product/sidebar.php" ?>
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
                        <?php require BASE_PATH . "/app/views/components/product/listproduct.php" ?>



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

                    <?php require BASE_PATH . "/app/views/components/product/gioithieu.php" ?>

                </section>

            </main>

        </div>
    </form>
</div>