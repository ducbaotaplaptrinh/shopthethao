<?php
$errorMsg = $_GET['error'] ?? '';
?>

<?php if ($errorMsg === 'duplicate_name'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> Tên danh mục đã tồn tại! Vui lòng chọn tên khác.
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
        <a href="?page=admin-categories" class="text-muted text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Danh mục</a>
        <h2 class="page-title mb-0 mt-1">Chỉnh sửa Danh mục</h2>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-6">
        <div class="admin-card">
            <h4 class="admin-card-title mb-4">Thông tin Danh mục</h4>
            <form action="?page=admin-category-update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= isset($category) ? $category->getId() : 'Trống' ?>">

                <div class="mb-3">
                    <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" name="ten_danh_muc" id="editCatName" class="form-control" required
                        value="<?= htmlspecialchars(isset($category) ? $category->getTen_danh_muc() : "Trống") ?>"
                        onkeyup="generateEditSlug()">
                </div>
                <div class="mb-3">
                    <label class="form-label">Đường dẫn (Slug) <span class="text-danger">*</span></label>
                    <input type="text" name="duong_dan_slug" id="editCatSlug" class="form-control" required readonly
                        style="background-color: #f8f9fa;"
                        value="<?= htmlspecialchars(isset($category) ? $category->getDuong_dan_slug() : "Trống") ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Hình ảnh danh mục</label>
                    <input type="file" name="hinh_anh" id="editCatImage" class="form-control mb-2" accept="image/*">
                    <?php
                    $currentImg = getProductImage("assets/images/products/" . $category->getHinh_anh() ?? '');
                    ?>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small">Ảnh hiện tại:</span>
                        <img src="<?= htmlspecialchars($currentImg) ?>" alt="" style="height: 60px; object-fit: contain; border-radius: 6px; border: 1px solid #eee; padding: 2px;">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Thứ tự sắp xếp</label>
                    <input type="number" name="thu_tu_sap_xep" id="editCatOrder" class="form-control"
                        value="<?= htmlspecialchars(isset($category) ? $category->getThu_tu_sap_xep() : 0) ?>" min="0">
                    <div class="form-text">Giá trị nhỏ hơn sẽ được ưu tiên hiển thị trước.</div>
                </div>
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="trang_thai" id="editCatStatus"
                        <?= $category->getTrang_thai() == 1 ? 'checked' : '' ?>>
                    <label class="form-check-label" for="editCatStatus">Hiển thị</label>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-save me-1"></i> Cập nhật
                    </button>
                    <a href="?page=admin-categories" class="btn btn-outline-secondary flex-fill">
                        <i class="bi bi-x-lg me-1"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function generateEditSlug() {
        let title = document.getElementById('editCatName').value;
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

        document.getElementById('editCatSlug').value = slug;
    }
</script>