<?php
// Hiển thị thông báo từ query param
$successMsg = $_GET['success'] ?? '';
$errorMsg   = $_GET['error']   ?? '';
$count      = $_GET['count']   ?? 0;
$brands     = $brands ?? [];
?>

<?php if ($successMsg === 'created'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> Thêm thương hiệu thành công!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php elseif ($successMsg === 'updated'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> Cập nhật thương hiệu thành công!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php elseif ($successMsg === 'deleted'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> Xóa thương hiệu thành công!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php elseif ($errorMsg === 'duplicate_name'): ?>
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
<?php elseif ($errorMsg === 'has_products'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> Không thể xóa thương hiệu này vì còn <strong><?= intval($count) ?></strong> sản phẩm đang sử dụng!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php elseif ($errorMsg === 'not_found'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> Không tìm thấy thương hiệu!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Cột thêm mới -->
    <div class="col-12 col-lg-4">
        <div class="admin-card">
            <h4 class="admin-card-title mb-4">Thêm Thương hiệu mới</h4>
            <form action="?page=admin-brand-store" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                    <input type="text" name="ten_thuong_hieu" class="form-control" required placeholder="Ví dụ: Yonex, Lining...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả ngắn</label>
                    <textarea name="mo_ta" class="form-control" rows="3" placeholder="Nhập mô tả thương hiệu..."></textarea>
                </div>
                <div class="mb-4">
                    <label class="form-label">Logo thương hiệu</label>
                    <input type="file" name="anh_logo" class="form-control" accept="image/*">
                    <div class="form-text">Định dạng: JPG, PNG, WEBP.</div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Lưu Thương Hiệu</button>
            </form>
        </div>
    </div>

    <!-- Cột danh sách -->
    <div class="col-12 col-lg-8">
        <div class="admin-card">
            <h4 class="admin-card-title mb-4">Danh sách Thương hiệu</h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Logo</th>
                            <th>Tên Thương Hiệu</th>
                            <th>Đường dẫn (Slug)</th>
                            <th>Số SP</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($brands as $b): ?>
                        <?php 
                            $brandImg = getProductImage("assets/images/brands/" . ($b['anh_logo'] ?? ''));
                        ?>
                        <tr>
                            <td class="text-muted fw-bold">#<?= $b['id'] ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($brandImg) ?>" alt="" style="width: 60px; height: 35px; object-fit: contain; border-radius: 4px; border: 1px solid #eee; padding: 2px; background: #fff;">
                            </td>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($b['ten_thuong_hieu']) ?></td>
                            <td class="text-muted">/<?= htmlspecialchars($b['duong_dan_slug'] ?? '') ?></td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                    <?= intval($b['so_san_pham'] ?? 0) ?> SP
                                </span>
                            </td>
                            <td>
                                <?php if (($b['trang_thai'] ?? 1) == 1): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Hiện</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">Ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="?page=admin-brand-edit&id=<?= $b['id'] ?>" class="btn btn-sm btn-light text-primary me-1" title="Sửa">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="?page=admin-brand-delete&id=<?= $b['id'] ?>" 
                                   class="btn btn-sm btn-light text-danger" 
                                   title="Xóa"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa thương hiệu \'<?= htmlspecialchars($b['ten_thuong_hieu'], ENT_QUOTES) ?>\'?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($brands)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-tag fs-1 d-block mb-3"></i>
                                Chưa có thương hiệu nào
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
