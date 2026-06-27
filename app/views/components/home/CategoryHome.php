<?php

use app\models\entities\DanhMuc;

$danhmuc = [];
if (isset($danhSachDanhMuc) && is_array($danhSachDanhMuc)) {
    //Lấy thông tin danh mục cha 
    foreach ($danhSachDanhMuc as  $dm) {
        if ($dm->getMa_danh_muc_cha() === null) {
            $danhmuc[$dm->GetId()] = [
                'info' => $dm,
                'child' => []
            ];
        }
    }
    //cho thông tin con vào mảng chile dựa vào key là id 
    foreach ($danhSachDanhMuc as  $dm) {
        if ($dm->getMa_danh_muc_cha() !== null) {
            $danhmuc[$dm->getMa_danh_muc_cha()]['child'][] = $dm;
        }
    }
}
?>
<div class="container-xl">
    <section class="categories my-16-mobile py-0-mobile">

        <?php foreach ($danhmuc as $d):
            extract($d);
        ?>
            <div class="categories-content">
                <h2 class="categories-content__title">Sản phẩm
                    <?= htmlspecialchars($info->getTen_danh_muc()) ?>
                </h2>

                <div class="row g-3">
                    <?php foreach (array_slice($child, 0, 8)  as $index => $dmc): ?>

                        <div class="col-6 col-md-3 h-100" data-aos="zoom-out">

                            <a href="?page=product-index&category=<?php echo htmlspecialchars($dmc->GetDuong_dan_slug()) ?>" class="categories-content__card card">
                                <div class="categories-content__thumb h-100">
                                    <img
                                        src="<?php echo getProductImage("assets/images/categories/" . $dmc->getHinh_anh()) ?>"
                                        alt="<?php echo htmlspecialchars($dmc->getTen_danh_muc()) ?>"
                                        class="categories-content__img h-100">
                                </div>

                                <div class="categories-content__info">
                                    <h4 class="categories-content__info-title">
                                        <?php echo htmlspecialchars($dmc->getTen_danh_muc()) ?>
                                    </h4>
                                </div>
                            </a>

                        </div>

                    <?php endforeach; ?>
                </div>
            </div>

        <?php endforeach; ?>
    </section>
</div>