<?php
$sportCategories = [
    [
        'name' => 'Cầu lông',
        'slug' => 'cau-long',
        'description' => 'Bộ sưu tập sản phẩm cho người chơi từ cơ bản đến thi đấu.',
        'items' => [
            'Vợt cầu lông',
            'Quần áo cầu lông',
            'Giày cầu lông',
            'Phụ kiện cầu lông'
        ],
        'image' => "assets/images/categories/categories-",
        'accent' => 'badge-badminton',
    ],
    [
        'name' => 'Pickleball',
        'slug' => 'pickleball',
        'description' => 'Danh mục dành cho bộ môn đang phát triển rất nhanh.',
        'items' => [
            'Vợt pickleball',
            'Quần áo pickleball',
            'Giày pickleball',
            'Phụ kiện pickleball',
        ],
        'image' => "assets/images/categories/categories-",
        'accent' => 'badge-pickleball',
    ],
    [
        'name' => 'Tennis',
        'slug' => 'tennis',
        'description' => 'Nhóm sản phẩm cho người chơi tennis phong trào và bán chuyên.',
        'items' => [
            'Vợt tennis',
            'Quần áo tennis',
            'Giày tennis',
            'Phụ kiện tennis',
        ],
        'image' => "assets/images/categories/categories-",
        'accent' => 'badge-tennis',
    ],
];
?>
<div class="container-xl">
    <section class="categories">

        <?php foreach ($sportCategories as $cate): ?>

            <div class="categories-content">

                <h2 class="categories-content__title">Sản phẩm
                    <?= htmlspecialchars($cate['name']) ?>
                </h2>

                <div class="row g-3">

                    <?php foreach ($cate['items'] as $index => $item): ?>

                        <div class="col-6 col-md-3">

                            <div class="categories-content__card card">

                                <div class="categories-content__thumb">
                                    <img
                                        src="<?= htmlspecialchars($cate['image'] . ($index + 1) . '.webp') ?>"
                                        alt="<?= htmlspecialchars($item) ?>"
                                        class="categories-content__img">
                                </div>

                                <div class="categories-content__info">
                                    <h4 class="categories-content__info-title">
                                        <?= htmlspecialchars($item) ?>
                                    </h4>
                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        <?php endforeach; ?>
    </section>
</div>