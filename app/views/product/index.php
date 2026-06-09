<div class="container-xl py-4">

    <!-- Breadcrumb -->
    <div class="breadcrumb-wrapper mb-4">
        Trang chủ > Cầu lông > Vợt cầu lông
    </div>

    <div class="row g-4">

        <!-- Sidebar -->
        <aside class="col-lg-3">

            <div id="filterSidebar"

                class="bg-white rounded shadow-sm p-3">
                <?php require BASE_PATH . "/app/views/components/product/sidebar.php" ?>
                <h5 class="mb-3">
                    Bộ lọc sản phẩm
                </h5>

                <!-- Danh mục -->
                <div class="mb-4">
                    <h6>Danh mục</h6>
                </div>

                <!-- Thương hiệu -->
                <div class="mb-4">
                    <h6>Thương hiệu</h6>
                </div>

                <!-- Giá -->
                <div class="mb-4">
                    <h6>Giá bán</h6>
                </div>

            </div>

        </aside>

        <!-- Content -->
        <main class="col-lg-9">

            <!-- Toolbar -->
            <div class="toolbar bg-white rounded shadow-sm p-3 mb-4">

                <div class="row align-items-center">

                    <div class="col-md-6">
                        Hiển thị 120 sản phẩm
                    </div>

                    <div class="col-md-6 text-md-end">

                        <select class="form-select d-inline-block w-auto">

                            <option>Mới nhất</option>
                            <option>Giá tăng dần</option>
                            <option>Giá giảm dần</option>

                        </select>

                    </div>

                </div>

            </div>

            <!-- Product Grid -->
            <div id="productGrid">

                <?php require BASE_PATH . "/app/views/components/product/listproduct.php" ?>
                <div class="row g-3">

                    <div class="col-6 col-md-4 col-xl-3">
                        Product Card
                    </div>

                    <div class="col-6 col-md-4 col-xl-3">
                        Product Card
                    </div>

                    <div class="col-6 col-md-4 col-xl-3">
                        Product Card
                    </div>

                </div>

            </div>

            <!-- Pagination -->
            <div id="pagination"
                class="mt-5 d-flex justify-content-center">

                Pagination

            </div>

            <!-- Description -->
            <section class="category-description bg-white rounded shadow-sm p-4 mt-5">

                <?php require BASE_PATH . "/app/views/components/product/gioithieu.php" ?>
                <h2>
                    Vợt cầu lông Yonex
                </h2>

                <p>
                    Nội dung giới thiệu danh mục hoặc thương hiệu...
                </p>

            </section>

        </main>

    </div>

</div>