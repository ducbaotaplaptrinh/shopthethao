<?php
// Helper to build page URLs with filters
if (!function_exists('buildPageUrl')) {
    function buildPageUrl($pageNo, $filters)
    {
        $params = array_merge($filters, ['page_no' => $pageNo, 'page' => 'admin-products']);
        // Filter out empty params
        $params = array_filter($params, function ($value) {
            return $value !== '';
        });
        return '?' . http_build_query($params);
    }
}
?>

<!-- Alerts for Action Statuses -->
<?php if (!empty($successMsg)): ?>
    <?php if ($successMsg === 'created'): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Thêm sản phẩm thành công!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($successMsg === 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Cập nhật sản phẩm thành công!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($successMsg === 'deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Xóa sản phẩm thành công!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($successMsg === 'restored'): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Khôi phục sản phẩm thành công!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if (!empty($errorMsg)): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($errorMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">Quản lý Sản phẩm</h2>
    <a href="?page=admin-product-create" class="btn btn-primary px-4">
        <i class="bi bi-plus-lg me-2"></i>Thêm Sản phẩm
    </a>
</div>

<!-- Filter Bar -->
<form method="GET" action="" class="mb-4">
    <input type="hidden" name="page" value="admin-products">
    <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 12px;">
        <div class="row g-3">
            <div class="col-md-3 col-sm-6">
                <label class="form-label fw-bold text-secondary small">Tìm kiếm</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="keyword" class="form-control bg-light border-start-0" placeholder="Tên, ID, SKU..." value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <label class="form-label fw-bold text-secondary small">Danh mục</label>
                <select name="ma_danh_muc" class="form-select bg-light">
                    <option value="">-- Tất cả danh mục --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (isset($filters['ma_danh_muc']) && $filters['ma_danh_muc'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['ten_danh_muc']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3 col-sm-6">
                <label class="form-label fw-bold text-secondary small">Thương hiệu</label>
                <select name="ma_thuong_hieu" class="form-select bg-light">
                    <option value="">-- Tất cả thương hiệu --</option>
                    <?php foreach ($brands as $brand): ?>
                        <option value="<?= $brand['id'] ?>" <?= (isset($filters['ma_thuong_hieu']) && $filters['ma_thuong_hieu'] == $brand['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($brand['ten_thuong_hieu']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3 col-sm-6">
                <label class="form-label fw-bold text-secondary small">Tình trạng kho</label>
                <select name="kho" class="form-select bg-light">
                    <option value="">-- Tất cả kho --</option>
                    <option value="con_hang" <?= (isset($filters['kho']) && $filters['kho'] === 'con_hang') ? 'selected' : '' ?>>Còn hàng (> 5)</option>
                    <option value="sap_het_hang" <?= (isset($filters['kho']) && $filters['kho'] === 'sap_het_hang') ? 'selected' : '' ?>>Sắp hết hàng (0 &lt; x &le; 5)</option>
                    <option value="het_hang" <?= (isset($filters['kho']) && $filters['kho'] === 'het_hang') ? 'selected' : '' ?>>Hết hàng (= 0)</option>
                </select>
            </div>

            <div class="col-md-3 col-sm-6">
                <label class="form-label fw-bold text-secondary small">Trạng thái hiển thị</label>
                <select name="trang_thai" class="form-select bg-light">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="1" <?= (isset($filters['trang_thai']) && $filters['trang_thai'] === '1') ? 'selected' : '' ?>>Đang bán (Hiển thị)</option>
                    <option value="0" <?= (isset($filters['trang_thai']) && $filters['trang_thai'] === '0') ? 'selected' : '' ?>>Đang ẩn (Tạm khóa)</option>
                </select>
            </div>

            <div class="col-md-3 col-sm-6">
                <label class="form-label fw-bold text-secondary small">Thùng rác</label>
                <select name="da_xoa" class="form-select bg-light">
                    <option value="0" <?= (isset($filters['da_xoa']) && $filters['da_xoa'] !== '1') ? 'selected' : '' ?>>Đang hoạt động</option>
                    <option value="1" <?= (isset($filters['da_xoa']) && $filters['da_xoa'] === '1') ? 'selected' : '' ?>>Đã xóa tạm (Thùng rác)</option>
                </select>
            </div>

            <div class="col-md-3 col-sm-6">
                <label class="form-label fw-bold text-secondary small">Khuyến mãi</label>
                <select name="khuyen_mai" class="form-select bg-light">
                    <option value="">-- Tất cả sản phẩm --</option>
                    <option value="1" <?= (isset($filters['khuyen_mai']) && $filters['khuyen_mai'] === '1') ? 'selected' : '' ?>>Đang khuyến mãi</option>
                </select>
            </div>

            <div class="col-md-3 col-sm-6">
                <label class="form-label fw-bold text-secondary small">Doanh số</label>
                <select name="doanh_so" class="form-select bg-light">
                    <option value="">-- Bộ lọc doanh số --</option>
                    <option value="ban_chay" <?= (isset($filters['doanh_so']) && $filters['doanh_so'] === 'ban_chay') ? 'selected' : '' ?>>Sản phẩm bán chạy</option>
                    <option value="ban_cham" <?= (isset($filters['doanh_so']) && $filters['doanh_so'] === 'ban_cham') ? 'selected' : '' ?>>Sản phẩm bán chậm</option>
                </select>
            </div>

            <div class="col-md-3 col-sm-12 d-flex align-items-end gap-2 ms-auto">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-funnel-fill me-1"></i> Lọc dữ liệu
                </button>
                <a href="?page=admin-products" class="btn btn-outline-secondary flex-fill">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Đặt lại
                </a>
            </div>
        </div>
    </div>
</form>

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
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td class="text-muted fw-bold">
                            <?php if (!empty($p['so_bien_the']) && $p['so_bien_the'] > 0): ?>
                                <span class="toggle-variants-btn me-1" data-product-id="<?= $p['id'] ?>" style="cursor: pointer;" title="Xem các biến thể">
                                    <i class="bi bi-plus-square text-primary toggle-icon-<?= $p['id'] ?>"></i>
                                </span>
                            <?php endif; ?>
                            #<?= $p['id'] ?>
                        </td>
                        <td>
                            <?php if (!empty($p['anh_dai_dien'])): ?>
                                <img src="<?= getProductImage('assets/images/products/' . $p['anh_dai_dien']) ?>" style="width: 45px; height: 45px; object-fit: cover; border-radius: 8px;">
                            <?php else: ?>
                                <div style="width: 45px; height: 45px; border-radius: 8px; background: #f4f6fa; display:flex; align-items:center; justify-content:center;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($p['ten_san_pham']) ?></div>
                            <?php if (!empty($p['gia_khuyen_mai']) && $p['gia_khuyen_mai'] < $p['gia_ban']): ?>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="text-danger fw-bold small"><?= number_format($p['gia_khuyen_mai'], 0, ',', '.') ?> đ</span>
                                    <span class="text-muted text-decoration-line-through small" style="font-size: 0.75rem;"><?= number_format($p['gia_ban'], 0, ',', '.') ?> đ</span>
                                    <span class="badge bg-danger" style="font-size: 0.65rem;">Khuyến mãi</span>
                                </div>
                            <?php else: ?>
                                <small class="text-muted"><?= number_format($p['gia_ban'], 0, ',', '.') ?> đ</small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p['ten_danh_muc'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($p['ten_thuong_hieu'] ?? 'N/A') ?></td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold <?= ($p['tong_ton_kho'] ?? 0) <= 5 ? 'text-danger' : 'text-success' ?>">
                                    <?= $p['tong_ton_kho'] ?? 0 ?> sản phẩm
                                </span>
                                <?php if (($p['so_bien_the_het_hang'] ?? 0) > 0): ?>
                                    <span class="badge bg-warning text-dark mt-1 align-self-start" style="font-size: 0.7rem;">
                                        <?= $p['so_bien_the_het_hang'] ?> SKU sắp hết
                                    </span>
                                <?php endif; ?>
                                <span class="text-muted small mt-1">
                                    <i class="bi bi-cart-check me-1"></i>Đã bán: <strong><?= $p['da_ban'] ?? 0 ?></strong>
                                </span>
                            </div>
                        </td>
                        <td>
                            <?php if (!empty($p['ngay_xoa'])): ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Đã xóa tạm</span>
                            <?php elseif ($p['trang_thai'] == 1): ?>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Đang bán</span>
                            <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">Đang ẩn</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <?php if (empty($p['ngay_xoa'])): ?>
                                <a href="?page=admin-product-edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-light text-primary me-1">
                                    <i class="bi bi-pencil-square"></i> Sửa
                                </a>
                                <a href="?page=admin-product-delete&id=<?= $p['id'] ?>" class="btn btn-sm btn-light text-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                    <i class="bi bi-trash"></i> Xóa
                                </a>
                            <?php else: ?>
                                <div class="d-flex flex-column align-items-end gap-1">
                                    <span class="text-muted small" style="font-size: 0.75rem;">
                                        <i class="bi bi-calendar2-x me-1"></i><?= date('d/m/Y H:i', strtotime($p['ngay_xoa'])) ?>
                                    </span>
                                    <a href="?page=admin-product-restore&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Bạn có chắc chắn muốn khôi phục sản phẩm này cùng các biến thể?')">
                                        <i class="bi bi-arrow-counterclockwise"></i> Khôi phục
                                    </a>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if (!empty($p['variants'])): ?>
                        <?php foreach ($p['variants'] as $v):
                            $vAttrText = [];
                            foreach ($v['attributes'] as $attr) {
                                $vAttrText[] = htmlspecialchars($attr['ten_thuoc_tinh'] . ': ' . $attr['gia_tri']);
                            }
                            $attrSuffix = !empty($vAttrText) ? ' (' . implode(', ', $vAttrText) . ')' : '';
                        ?>
                            <tr class="variant-row parent-<?= $p['id'] ?> bg-light bg-opacity-50" style="display: none; border-left: 4px solid var(--bs-primary);">
                                <td class="text-muted small ps-3">#<?= $v['id'] ?></td>
                                <td>
                                    <?php if (!empty($v['anh_rieng'])): ?>
                                        <img src="<?= getProductImage('assets/images/products/' . $v['anh_rieng']) ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                                    <?php else: ?>
                                        <?php if (!empty($p['anh_dai_dien'])): ?>
                                            <img src="<?= getProductImage('assets/images/products/' . $p['anh_dai_dien']) ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                                        <?php else: ?>
                                            <div style="width: 40px; height: 40px; border-radius: 6px; background: #f4f6fa; display:flex; align-items:center; justify-content:center;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-semibold text-secondary small">
                                        <?= htmlspecialchars($p['ten_san_pham']) . $attrSuffix ?>
                                    </div>
                                    <div class="text-muted small" style="font-size: 0.75rem;">
                                        SKU: <?= htmlspecialchars($v['ma_vach_sku'] ?? 'N/A') ?>
                                    </div>
                                    <div class="text-dark fw-bold small mt-1">
                                        <?= number_format($v['gia_ban_rieng'], 0, ',', '.') ?> đ
                                    </div>
                                </td>
                                <td><span class="text-muted small"><?= htmlspecialchars($p['ten_danh_muc'] ?? 'N/A') ?></span></td>
                                <td><span class="text-muted small"><?= htmlspecialchars($p['ten_thuong_hieu'] ?? 'N/A') ?></span></td>
                                <td>
                                    <div class="d-flex flex-column small">
                                        <span class="fw-semibold <?= $v['so_luong_ton'] <= 5 ? 'text-danger' : 'text-success' ?>">
                                            <?= $v['so_luong_ton'] ?> sản phẩm
                                        </span>
                                        <span class="text-muted mt-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-cart-check me-1"></i>Đã bán: <strong><?= $v['da_ban'] ?></strong>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($v['trang_thai'] == 1): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25" style="font-size: 0.7rem;">Đang bán</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25" style="font-size: 0.7rem;">Đang ẩn</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end text-muted small italic" style="font-size: 0.75rem;">
                                    Biến thể con
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-box-seam fs-1 d-block mb-3"></i>
                            Không tìm thấy sản phẩm nào khớp với bộ lọc
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Controls -->
    <?php if ($totalPages > 1): ?>
        <hr class="my-0 border-light">
        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-2">
            <div class="text-muted small">
                Hiển thị trang <strong><?= $currentPage ?></strong> / <strong><?= $totalPages ?></strong> (Tổng số <strong><?= $totalProducts ?></strong> sản phẩm)
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <!-- Previous Button -->
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $currentPage <= 1 ? '#' : buildPageUrl($currentPage - 1, $filters) ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    <?php
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $currentPage + 2);

                    if ($startPage > 1): ?>
                        <li class="page-item"><a class="page-link" href="<?= buildPageUrl(1, $filters) ?>">1</a></li>
                        <?php if ($startPage > 2): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?= $currentPage == $i ? 'active' : '' ?>">
                            <a class="page-link" href="<?= buildPageUrl($i, $filters) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($endPage < $totalPages): ?>
                        <?php if ($endPage < $totalPages - 1): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                        <li class="page-item"><a class="page-link" href="<?= buildPageUrl($totalPages, $filters) ?>"><?= $totalPages ?></a></li>
                    <?php endif; ?>

                    <!-- Next Button -->
                    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $currentPage >= $totalPages ? '#' : buildPageUrl($currentPage + 1, $filters) ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-variants-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var productId = this.getAttribute('data-product-id');
                var variantRows = document.querySelectorAll('.parent-' + productId);
                var icon = this.querySelector('i');

                variantRows.forEach(function(row) {
                    if (row.style.display === 'none') {
                        row.style.display = 'table-row';
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (icon.classList.contains('bi-plus-square')) {
                    icon.classList.remove('bi-plus-square');
                    icon.classList.add('bi-dash-square');
                } else {
                    icon.classList.remove('bi-dash-square');
                    icon.classList.add('bi-plus-square');
                }
            });
        });
    });
</script>