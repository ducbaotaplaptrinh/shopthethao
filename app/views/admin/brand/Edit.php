<?php
$errorMsg = $_GET['error'] ?? '';
$brand = $brand ?? [];
?>

<?php if ($errorMsg === 'duplicate_name'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> Tên thương hiệu đã tồn tại! Vui lòng chọn tên khác.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php elseif ($errorMsg === 'duplicate_slug'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> Đường dẫn (slug) đã tồn tại!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php elseif ($errorMsg === 'empty_fields'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> Vui lòng nhập đầy đủ thông tin!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="?page=admin-brands" class="text-muted text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Thương hiệu</a>
        <h2 class="page-title mb-0 mt-1">Chỉnh sửa Thương hiệu</h2>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-6">
        <div class="admin-card">
            <h4 class="admin-card-title mb-4">Thông tin Thương hiệu</h4>
            <form action="?page=admin-brand-update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($brand['id'] ?? '') ?>">

                <div class="mb-3">
                    <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                    <input type="text" name="ten_thuong_hieu" id="editBrandName" class="form-control" required
                        value="<?= htmlspecialchars($brand['ten_thuong_hieu'] ?? '') ?>"
                        onkeyup="generateEditSlug()">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Đường dẫn (Slug) <span class="text-danger">*</span></label>
                    <input type="text" name="duong_dan_slug" id="editBrandSlug" class="form-control" required readonly
                        style="background-color: #f8f9fa;"
                        value="<?= htmlspecialchars($brand['duong_dan_slug'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="mo_ta" class="form-control" rows="4" placeholder="Nhập mô tả..."><?= htmlspecialchars($brand['mo_ta'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Logo thương hiệu</label>
                    <input type="file" name="anh_logo" id="editBrandLogo" class="form-control mb-2" accept="image/*">
                    <?php
                    $currentImg = getProductImage("assets/images/brands/" . ($brand['anh_logo'] ?? ''));
                    ?>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small">Logo hiện tại:</span>
                        <img src="<?= htmlspecialchars($currentImg) ?>" alt="" style="height: 50px; width: 100px; object-fit: contain; border-radius: 6px; border: 1px solid #eee; padding: 2px; background: #fff;">
                    </div>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="trang_thai" id="editBrandStatus"
                        <?= ($brand['trang_thai'] ?? 1) == 1 ? 'checked' : '' ?>>
                    <label class="form-check-label" for="editBrandStatus">Hiển thị</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-save me-1"></i> Cập nhật
                    </button>
                    <a href="?page=admin-brands" class="btn btn-outline-secondary flex-fill">
                        <i class="bi bi-x-lg me-1"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function generateEditSlug() {
        let title = document.getElementById('editBrandName').value;
        let slug = title.toLowerCase();

        slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
        slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
        slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
        slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
        slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
        slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
        slug = slug.replace(/đ/gi, 'd');

        slug = slug.replace(/[`~!@#$%^&*()+=_,\./?><:'";\\|]/g, ' ');

        slug = slug.replace(/\s+/g, '-');

        slug = slug.replace(/-+/g, '-');

        slug = slug.replace(/^-+|-+$/g, '');

        document.getElementById('editBrandSlug').value = slug;
    }
</script>
