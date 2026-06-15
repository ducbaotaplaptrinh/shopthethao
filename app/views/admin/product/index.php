<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">Quản lý Sản phẩm</h2>
    <a href="?page=admin-product-create" class="btn btn-primary px-4">
        <i class="bi bi-plus-lg me-2"></i>Thêm Sản phẩm
    </a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên Sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <th>Tồn kho</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $p): ?>
                <tr>
                    <td class="text-muted fw-bold">#<?= $p['id'] ?></td>
                    <td>
                        <div style="width: 45px; height: 45px; border-radius: 8px; background: #f4f6fa; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-image text-muted"></i>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark"><?= htmlspecialchars($p['ten_san_pham']) ?></div>
                        <small class="text-muted"><?= number_format($p['gia_goc'], 0, ',', '.') ?> đ</small>
                    </td>
                    <td><?= htmlspecialchars($p['ten_danh_muc']) ?></td>
                    <td><?= htmlspecialchars($p['ten_thuong_hieu'] ?? 'N/A') ?></td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-bold <?= $p['tong_ton_kho'] < 10 ? 'text-danger' : 'text-success' ?>">
                                <?= $p['tong_ton_kho'] ?? 0 ?> sản phẩm
                            </span>
                            <?php if ($p['so_bien_the_het_hang'] > 0): ?>
                            <span class="badge bg-danger mt-1" style="font-size: 0.7rem;">
                                <?= $p['so_bien_the_het_hang'] ?> SKU sắp hết
                            </span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <?php if ($p['trang_thai'] == 1): ?>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Đang bán</span>
                        <?php else: ?>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">Đã ẩn</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="?page=admin-product-edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-light text-primary me-1">
                            <i class="bi bi-pencil-square"></i> Sửa
                        </a>
                        <button class="btn btn-sm btn-light text-danger">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($products)): ?>
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-box-seam fs-1 d-block mb-3"></i>
                        Chưa có sản phẩm nào
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
