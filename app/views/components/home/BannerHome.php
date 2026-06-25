<section class=" swiper col-12">
    <div class="swiper-wrapper">
        <?php if (!empty($banners) && is_array($banners)): ?>
            <?php foreach ($banners as $b): ?>
                <div class="swiper-slide">
                    <a href="<?= htmlspecialchars($b['duong_dan_lien_ket'] ?: '#') ?>" class="slide">
                        <img
                            src="<?= htmlspecialchars($b['duong_dan_anh']) ?>"
                            alt="<?= htmlspecialchars($b['tieu_de'] ?? '') ?>"
                            class="slide-item" />
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="swiper-slide">
                <a href="#" class="slide">
                    <img
                        src="assets/images/banners/1000z-launch-website-banner_1695177885.webp"
                        alt=""
                        class="slide-item" />
                </a>
            </div>
            <div class="swiper-slide">
                <a href="#" class="slide">
                    <img
                        src="assets/images/banners/victor-axelsen_1759089349.webp"
                        alt=""
                        class="slide-item" />
                </a>
            </div>
            <div class="swiper-slide">
                <a href="#" class="slide">
                    <img
                        src="assets/images/banners/vs-long-ma_1774643350.webp"
                        alt=""
                        class="slide-item" />
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>