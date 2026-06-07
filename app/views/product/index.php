<div class="category-page container-xl py-4 py-lg-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb custom-breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="?page=home">Trang chủ</a></li>
            <li class="breadcrumb-item">
                <a href="#">Cầu lông</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Vợt cầu lông Yonex</li>
        </ol>
    </nav>

    <section class="category-hero mb-4 mb-lg-5">
        <div class="row g-4 align-items-center">
            <div class="col-lg-7">
                <span class="section-kicker mb-3"><i class="fa-solid fa-bolt"></i> Danh mục chọn lọc</span>
                <h1 class="category-title mb-3">Vợt cầu lông Yonex</h1>
                <p class="category-summary mb-4">
                    Danh mục mẫu này mô phỏng trang liệt kê sản phẩm thực tế: breadcrumb động, bộ lọc bên trái, thanh sắp xếp,
                    phân trang, và phần mô tả dài ở cuối trang. Đây là nền để bạn thay dữ liệu PHP sau này mà vẫn giữ bố cục hiện tại.
                </p>

                <div class="d-flex flex-wrap gap-2">
                    <span class="hero-chip"><i class="fa-solid fa-check"></i> Hàng chính hãng</span>
                    <span class="hero-chip"><i class="fa-solid fa-bolt"></i> Có giảm giá</span>
                    <span class="hero-chip"><i class="fa-solid fa-layer-group"></i> Lọc theo biến thể</span>
                    <span class="hero-chip"><i class="fa-solid fa-truck-fast"></i> Gợi ý giao nhanh</span>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="hero-panel p-4 p-xl-5">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <p class="hero-panel__label mb-1">Thương hiệu tiêu biểu</p>
                            <h2 class="hero-panel__title mb-2">Yonex</h2>
                            <p class="hero-panel__text mb-0">
                                Màn hình này đã sẵn sàng nhận dữ liệu động từ PHP, còn hiện tại đang dùng dữ liệu giả để bạn học cách nối DB sau.
                            </p>
                        </div>
                        <div class="hero-panel__badge"><i class="fa-solid fa-shield-heart"></i></div>
                    </div>

                    <div class="hero-panel__stats mt-4">
                        <div>
                            <strong>12</strong>
                            <span>Sản phẩm mẫu</span>
                        </div>
                        <div>
                            <strong>6</strong>
                            <span>Bộ lọc chính</span>
                        </div>
                        <div>
                            <strong>3</strong>
                            <span>Trang demo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="series-strip mb-4 mb-lg-5">
        <div class="row g-3">
            <div class="col-6 col-lg-2">
                <div class="series-card series-card--blue">
                    <span>Astrox</span>
                    <small>Tấn công mạnh mẽ</small>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="series-card series-card--orange">
                    <span>Nanoflare</span>
                    <small>Tốc độ vượt trội</small>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="series-card series-card--teal">
                    <span>ArcSaber</span>
                    <small>Kiểm soát cầu</small>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="series-card series-card--red">
                    <span>Duora</span>
                    <small>Hai mặt công thủ</small>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="series-card series-card--slate">
                    <span>Voltric</span>
                    <small>Sức mạnh smash</small>
                </div>
            </div>
        </div>
    </section>

    <section class="category-shell row g-4 align-items-start">
        <aside class="col-lg-3">
            <div class="filter-toggle d-lg-none mb-3">
                <button type="button" class="btn btn-filter w-100 js-open-filters">
                    <i class="fa-solid fa-sliders me-2"></i>Mở bộ lọc
                </button>
            </div>

            <div class="filter-panel js-filter-panel">
                <div class="filter-panel__head d-flex justify-content-between align-items-center d-lg-none">
                    <strong>Bộ lọc</strong>
                    <button type="button" class="btn-close js-close-filters" aria-label="Đóng bộ lọc"></button>
                </div>

                <div class="filter-card">
                    <h3>Chọn mức giá</h3>
                    <div class="filter-list">
                        <label class="filter-check">
                            <input type="checkbox" data-filter="price" value="under500">
                            <span>Giá dưới 500.000đ</span>
                        </label>
                        <label class="filter-check">
                            <input type="checkbox" data-filter="price" value="500-1000">
                            <span>500.000đ - 1 triệu</span>
                        </label>
                        <label class="filter-check">
                            <input type="checkbox" data-filter="price" value="1000-2000">
                            <span>1 - 2 triệu</span>
                        </label>
                        <label class="filter-check">
                            <input type="checkbox" data-filter="price" value="over3000">
                            <span>Giá trên 3 triệu</span>
                        </label>
                    </div>
                </div>

                <div class="filter-card">
                    <h3>Trọng lượng</h3>
                    <div class="filter-list">
                        <label class="filter-check">
                            <input type="checkbox" data-filter="weight" value="4u-5u">
                            <span>4U - 5U</span>
                        </label>
                        <label class="filter-check">
                            <input type="checkbox" data-filter="weight" value="3u">
                            <span>3U</span>
                        </label>
                        <label class="filter-check">
                            <input type="checkbox" data-filter="weight" value="2u">
                            <span>2U</span>
                        </label>
                    </div>
                </div>

                <div class="filter-card">
                    <h3>Thương hiệu</h3>
                    <div class="filter-list filter-list--inline">
                        <label class="filter-check filter-check--pill is-active">
                            <input type="checkbox" data-filter="brand" value="Yonex" checked>
                            <span>Yonex</span>
                        </label>
                        <label class="filter-check filter-check--pill">
                            <input type="checkbox" data-filter="brand" value="Victor">
                            <span>Victor</span>
                        </label>
                        <label class="filter-check filter-check--pill">
                            <input type="checkbox" data-filter="brand" value="Lining">
                            <span>Lining</span>
                        </label>
                    </div>
                </div>

                <div class="filter-card">
                    <h3>Phong cách chơi</h3>
                    <div class="filter-list">
                        <label class="filter-check">
                            <input type="checkbox" data-filter="style" value="Tấn công">
                            <span>Tấn công</span>
                        </label>
                        <label class="filter-check">
                            <input type="checkbox" data-filter="style" value="Công thủ toàn diện">
                            <span>Công thủ toàn diện</span>
                        </label>
                    </div>
                </div>

                <div class="filter-card mb-0">
                    <h3>Công nghệ</h3>
                    <div class="filter-search input-group mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="form-control" placeholder="Tìm công nghệ" data-filter-search>
                    </div>
                    <div class="filter-list filter-list--scroll js-tech-list">
                        <label class="filter-check">
                            <input type="checkbox" data-filter="tech" value="Namd">
                            <span>Namd</span>
                        </label>
                        <label class="filter-check">
                            <input type="checkbox" data-filter="tech" value="Isometric">
                            <span>Isometric</span>
                        </label>
                        <label class="filter-check">
                            <input type="checkbox" data-filter="tech" value="AERO+BOX">
                            <span>AERO+BOX</span>
                        </label>
                    </div>
                </div>
            </div>
        </aside>

        <div class="col-lg-9">
            <div class="category-toolbar mb-4">
                <div>
                    <h2 class="category-toolbar__title mb-1">Vợt cầu lông Yonex</h2>
                    <p class="category-toolbar__meta mb-0">
                        Hiển thị <span data-result-count>12</span> sản phẩm mẫu
                    </p>
                </div>

                <div class="category-toolbar__actions">
                    <div class="dropdown sort-dropdown">
                        <button class="btn sort-button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-sort me-2"></i>Sắp xếp: <span data-sort-label>Nổi bật</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-2 shadow-lg">
                            <li>
                                <button type="button" class="dropdown-item sort-item active" data-sort-value="featured">Nổi bật</button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item sort-item" data-sort-value="price-asc">Giá thấp đến cao</button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item sort-item" data-sort-value="price-desc">Giá cao đến thấp</button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item sort-item" data-sort-value="newest">Mới nhất</button>
                            </li>
                        </ul>
                    </div>

                    <button type="button" class="btn btn-filter d-lg-none js-open-filters">
                        <i class="fa-solid fa-filter me-2"></i>Lọc
                    </button>
                </div>
            </div>

            <div class="product-grid row g-4 js-product-grid">
                <!-- Sản phẩm 1 -->
                <div class="col-sm-6 col-xl-4 js-product-item"
                    data-product-card
                    data-price="709000"
                    data-weight="4u"
                    data-brand="Yonex"
                    data-style="Tấn công"
                    data-tech="Namd,Isometric"
                    data-rating="4.8"
                    data-series="Astrox">
                    <article class="product-card h-100">
                        <div class="product-card__art" style="--accent: #ff6b35;">
                            <span class="product-card__discount">-12%</span>
                            <span class="product-card__badge">Hot</span>
                            <div class="racket-fake">
                                <span class="racket-fake__head"></span>
                                <span class="racket-fake__shaft"></span>
                                <span class="racket-fake__grip"></span>
                            </div>
                            <div class="product-card__series">Astrox</div>
                        </div>
                        <div class="product-card__body">
                            <div class="product-card__rating">
                                <span><i class="fa-solid fa-star"></i> 4.8</span>
                                <small>(128 đánh giá)</small>
                            </div>
                            <h3 class="product-card__title">Vợt Cầu Lông Yonex Astrox Lite 37i</h3>
                            <div class="product-card__tags">
                                <span>4U</span>
                                <span>Tấn công</span>
                            </div>
                            <div class="product-card__tech">
                                <span>Namd</span>
                                <span>Isometric</span>
                            </div>
                            <div class="product-card__price">
                                <strong>709.000 đ</strong>
                                <del>799.000 đ</del>
                            </div>
                            <div class="product-card__actions">
                                <a href="#" class="btn btn-buy flex-grow-1">Xem chi tiết</a>
                                <button type="button" class="btn btn-outline-secondary btn-icon"><i class="fa-regular fa-heart"></i></button>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Sản phẩm 2 -->
                <div class="col-sm-6 col-xl-4 js-product-item"
                    data-product-card
                    data-price="2349000"
                    data-weight="3u"
                    data-brand="Yonex"
                    data-style="Công thủ toàn diện"
                    data-tech="Namd,AERO+BOX"
                    data-rating="4.9"
                    data-series="ArcSaber">
                    <article class="product-card h-100">
                        <div class="product-card__art" style="--accent: #de3c4b;">
                            <span class="product-card__badge">Premium</span>
                            <div class="racket-fake">
                                <span class="racket-fake__head"></span>
                                <span class="racket-fake__shaft"></span>
                                <span class="racket-fake__grip"></span>
                            </div>
                            <div class="product-card__series">ArcSaber</div>
                        </div>
                        <div class="product-card__body">
                            <div class="product-card__rating">
                                <span><i class="fa-solid fa-star"></i> 4.9</span>
                                <small>(85 đánh giá)</small>
                            </div>
                            <h3 class="product-card__title">Vợt Cầu Lông Yonex Astrox 22 Lite</h3>
                            <div class="product-card__tags">
                                <span>3U</span>
                                <span>Công thủ toàn diện</span>
                            </div>
                            <div class="product-card__tech">
                                <span>Namd</span>
                                <span>AERO+BOX</span>
                            </div>
                            <div class="product-card__price">
                                <strong>2.349.000 đ</strong>
                            </div>
                            <div class="product-card__actions">
                                <a href="#" class="btn btn-buy flex-grow-1">Xem chi tiết</a>
                                <button type="button" class="btn btn-outline-secondary btn-icon"><i class="fa-regular fa-heart"></i></button>
                            </div>
                        </div>
                    </article>
                </div>
            </div>

            <div class="pagination-wrap mt-4 mt-lg-5">
                <nav aria-label="Phân trang danh mục">
                    <ul class="pagination justify-content-center js-pagination mb-0"></ul>
                </nav>
            </div>

            <section class="category-description mt-4 mt-lg-5">
                <div class="section-heading mb-3">
                    <span class="section-kicker"><i class="fa-solid fa-pen-nib"></i> Mô tả danh mục</span>
                    <h2 class="mb-0">Vợt cầu lông Yonex</h2>
                </div>

                <div class="description-card">
                    <p>
                        Yonex là thương hiệu vợt được nhiều người chơi yêu thích nhờ khả năng cân bằng giữa độ bền, cảm giác cầu và
                        công nghệ khung vợt. Ở trang mẫu này, phần mô tả dài ở cuối trang đóng vai trò như nội dung SEO và giải thích
                        giúp người dùng hiểu nhanh dòng vợt phù hợp với lối đánh nào.
                    </p>
                    <p>
                        Khi bạn thay dữ liệu thật bằng PHP, phần này có thể lấy từ bảng danh mục hoặc từ một trường mô tả riêng của
                        category. Cấu trúc hiện tại đã có breadcrumb, bộ lọc theo giá, trọng lượng, thương hiệu, phong cách chơi, công nghệ
                        và phân trang để khớp với cách hiển thị trong ảnh tham chiếu của bạn.
                    </p>
                    <p class="mb-0">
                        Nếu sau này bạn muốn nối DB, chỉ cần thay các mảng giả bằng dữ liệu từ `categories`, `brands`, `products`,
                        `product_variants` và `variant_attribute_values`, còn layout và JS vẫn dùng lại nguyên trạng.
                    </p>
                </div>
            </section>
        </div>
    </section>
</div>