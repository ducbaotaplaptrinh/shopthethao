<?php
$titleIntro = "";
$descIntro = "";

if (!empty($tenThuongHieuMD)) {
    $titleIntro = "Giới thiệu thương hiệu " . htmlspecialchars($tenThuongHieuMD->getTen_thuong_hieu());
    $descIntro = $tenThuongHieuMD->getMo_ta() ? nl2br(htmlspecialchars($tenThuongHieuMD->getMo_ta())) : "Đang cập nhật giới thiệu cho thương hiệu này...";
} elseif (!empty($tenDanhMucMD)) {
    $titleIntro = "Giới thiệu danh mục " . htmlspecialchars($tenDanhMucMD->getTen_danh_muc());
    $descIntro = $tenDanhMucMD->getMo_ta() ? nl2br(htmlspecialchars($tenDanhMucMD->getMo_ta())) : "Đang cập nhật giới thiệu cho danh mục này...";
}
?>
<?php if (!empty($titleIntro)): ?>
    <h2><?= $titleIntro ?></h2>
    <p><?= $descIntro ?></p>
<?php else: ?>
    <h2>Giới thiệu</h2>
    <p>Chào mừng bạn đến với Bảo Đạt Sport. Chúng tôi cung cấp các sản phẩm thể thao chính hãng chất lượng cao.</p>
<?php endif; ?>