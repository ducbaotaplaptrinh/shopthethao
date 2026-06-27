<section class="container-fluid slider-section" style="
    overflow: hidden;
">
    <div class="row align-items-center g-4 g-lg-5 ">
        <?php require __DIR__ . '/../components/home/BannerHome.php'; ?>
    </div>
</section>
<div class="container-xl">
    <section class="quick-info py-5 my-16-mobile  py-0-mobile">
        <div class="row g-3">
            <div class="col-6 col-lg-3 quick-info__card " data-aos="zoom-in">
                <div class="info-card">
                    <div class="info-card__icon">
                        <img
                            class="icon"
                            src="assets/images/favicons/img-vanchuyen.svg"
                            alt="" />
                    </div>
                    <div>
                        <h3>Vận chuyển toàn quốc</h3>
                        <p>Thanh toán khi nhận hàng</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 quick-info__card" data-aos="zoom-in">
                <div class="info-card">
                    <div class="info-card__icon">
                        <img
                            class="icon"
                            src="assets/images/favicons/img-chatluong.svg"
                            alt="" />
                    </div>
                    <div>
                        <h3>Bảo đảm chất lượng</h3>
                        <p>Sản phẩm đảm bảo chất lượng</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 quick-info__card" data-aos="zoom-in">
                <div class="info-card">
                    <div class="info-card__icon">
                        <img
                            class="icon"
                            src="assets/images/favicons/img-thanhtoan.svg"
                            alt="" />
                    </div>
                    <div>
                        <h3>Tiến hành thanh toán</h3>
                        <p>Với nhiều phương thức</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 quick-info__card" data-aos="zoom-in">
                <div class="info-card">
                    <div class="info-card__icon">
                        <img
                            class="icon"
                            src="assets/images/favicons/img-doitra.svg"
                            alt="" />
                    </div>
                    <div>
                        <h3>Đổi sản phẩm mới</h3>
                        <p>Nếu sản phẩm lỗi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php require __DIR__ . "/../components/home/ProductFeaturedHome.php" ?>
    <?php require __DIR__ . '/../components/home/ProductSaleHome.php'; ?>
    <?php require __DIR__ . '/../components/home/BrandHome.php'; ?>
    <?php require __DIR__ . '/../components/home/CategoryHome.php'; ?>
    <?php require __DIR__ . '/../components/home/tuVan.php'; ?>
    <div class="contact-buttons">
        <a href="tel:0123456789" class="contact-item phone" title="Gọi điện">
            <div class="phone-glow"></div>
            <i class="fas fa-phone-alt"></i>
        </a>

        <a href="https://zalo.me/your_number" target="_blank" class="contact-item zalo" title="Chat Zalo">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg" alt="Zalo" />
        </a>

        <a href="https://m.me/your_username" target="_blank" class="contact-item messenger" title="Chat Messenger">
            <i class="fab fa-facebook-messenger"></i>
        </a>
    </div>
</div>