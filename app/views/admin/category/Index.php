<?php
// Hiển thị thông báo từ query param
$successMsg = $_GET['success'] ?? '';
$errorMsg   = $_GET['error']   ?? '';
$count      = $_GET['count']   ?? 0;
$categories = $categories ?? [];
?>

<?php if ($successMsg === 'created'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> Thêm danh mục thành công!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php elseif ($successMsg === 'updated'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> Cập nhật danh mục thành công!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php elseif ($successMsg === 'deleted'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> Xóa danh mục thành công!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php elseif ($errorMsg === 'duplicate_name'): ?>
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
<?php elseif ($errorMsg === 'has_products'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> Không thể xóa danh mục này vì còn <strong><?= intval($count) ?></strong> sản phẩm đang sử dụng!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php elseif ($errorMsg === 'not_found'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> Không tìm thấy danh mục!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Cột thêm mới -->
    <div class="col-12 col-lg-4">
        <div class="admin-card">
            <h4 class="admin-card-title mb-4">Thêm Danh mục mới</h4>
            <form action="?page=admin-category-store" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" name="ten_danh_muc" id="catName" class="form-control" required onkeyup="generateSlug()">
                </div>
                <div class="mb-3">
                    <label class="form-label">Đường dẫn (Slug) <span class="text-danger">*</span></label>
                    <input type="text" name="duong_dan_slug" id="catSlug" class="form-control" required readonly style="background-color: #f8f9fa;">
                </div>
                <div class="mb-3">
                    <label class="form-label">Hình ảnh danh mục</label>
                    <input type="file" name="hinh_anh" id="catImage" class="form-control" accept="image/*">
                    <div class="form-text">Định dạng: JPG, PNG, WEBP.</div>
                </div>
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="trang_thai" id="catStatus" checked>
                    <label class="form-check-label" for="catStatus">Hiển thị</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Lưu Danh Mục</button>
            </form>
        </div>
    </div>

    <!-- Cột danh sách -->
    <div class="col-12 col-lg-8">
        <div class="admin-card">
            <h4 class="admin-card-title mb-4">Danh sách Danh mục</h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tên Danh Mục</th>
                            <th>Đường dẫn</th>
                            <th>Số SP</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($categories as $c): ?>
                            <?php
                            $catImg = getProductImage("assets/images/categories/" . $c->getHinh_anh() ?? '');
                            ?>
                            <tr>
                                <td class="text-muted fw-bold">#<?= $c->getId() ?></td>
                                <td>
                                    <img src="<?= htmlspecialchars($catImg) ?>" alt="" style="width: 45px; height: 45px; object-fit: contain; border-radius: 6px; border: 1px solid #eee; padding: 2px;">
                                </td>
                                <td class="fw-bold text-dark"><?= htmlspecialchars($c->getTen_danh_muc()) ?></td>
                                <td class="text-muted">/<?= htmlspecialchars($c->getDuong_dan_slug()) ?></td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        <?= $c->getSo_san_pham() ?? 0 ?> SP
                                    </span>
                                </td>
                                <td>
                                    <?php if ($c->getTrang_thai() == 1): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Hiện</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">Ẩn</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <a href="?page=admin-category-edit&id=<?= $c->getId() ?>" class="btn btn-sm btn-light text-primary me-1" title="Sửa">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="?page=admin-category-delete&id=<?= $c->getId() ?>"
                                        class="btn btn-sm btn-light text-danger"
                                        title="Xóa"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục \'<?= htmlspecialchars($c->getTen_danh_muc(), ENT_QUOTES) ?>\'?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder2-open fs-1 d-block mb-3"></i>
                                    Chưa có danh mục nào
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function generateSlug() {
        let title = document.getElementById('catName').value;
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

        document.getElementById('catSlug').value = slug;
    }
</script>