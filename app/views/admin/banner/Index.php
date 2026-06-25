<?php if (!empty($successMsg)): ?>
    <?php if ($successMsg === 'created'): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Thêm banner mới thành công!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($successMsg === 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Cập nhật banner thành công!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($successMsg === 'deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Xóa banner thành công!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if (!empty($errorMsg)): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> Đã xảy ra lỗi: <?= htmlspecialchars($errorMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">Quản lý Banners Quảng Cáo</h2>
    <a href="?page=admin-banner-create" class="btn btn-primary px-4">
        <i class="bi bi-plus-lg me-2"></i>Thêm Banner
    </a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th style="width: 250px;">Hình ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Đường dẫn liên kết</th>
                    <th>Vị trí hiển thị</th>
                    <th>Trạng thái</th>
                    <th class="text-end" style="width: 150px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($banners as $b): ?>
                    <tr>
                        <td class="text-muted fw-bold">#<?= $b['id'] ?></td>
                        <td>
                            <?php if (!empty($b['duong_dan_anh'])): ?>
                                <img src="<?= htmlspecialchars($b['duong_dan_anh']) ?>" 
                                     alt="<?= htmlspecialchars($b['tieu_de'] ?? '') ?>" 
                                     class="img-thumbnail rounded"
                                     style="max-width: 200px; max-height: 80px; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 200px; height: 80px; border-radius: 8px; background: #f4f6fa; display:flex; align-items:center; justify-content:center;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($b['tieu_de'] ?: 'Chưa đặt tiêu đề') ?></div>
                        </td>
                        <td>
                            <?php if (!empty($b['duong_dan_lien_ket'])): ?>
                                <a href="<?= htmlspecialchars($b['duong_dan_lien_ket']) ?>" target="_blank" class="text-truncate d-inline-block" style="max-width: 250px;">
                                    <?= htmlspecialchars($b['duong_dan_lien_ket']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted small">Không liên kết</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($b['vi_tri_hien_thi'] === 'slide_chinh'): ?>
                                <span class="badge bg-primary">Slide chính (Home)</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($b['vi_tri_hien_thi']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($b['trang_thai'] == 1): ?>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Hiển thị</span>
                            <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">Ẩn</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="?page=admin-banner-edit&id=<?= $b['id'] ?>" class="btn btn-sm btn-light text-primary me-1">
                                <i class="bi bi-pencil-square"></i> Sửa
                            </a>
                            <a href="?page=admin-banner-delete&id=<?= $b['id'] ?>" 
                               class="btn btn-sm btn-light text-danger" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa banner này?')">
                                <i class="bi bi-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($banners)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-images fs-1 d-block mb-3"></i>
                            Chưa có banner nào được tạo. Nhấp "Thêm Banner" để bắt đầu!
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
