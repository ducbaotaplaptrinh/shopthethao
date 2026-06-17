<?php
$statusLabels = [
    'cho_xac_nhan' => ['label' => 'Chờ xác nhận', 'class' => 'bg-warning text-dark'],
    'dang_xu_ly'   => ['label' => 'Đang xử lý',  'class' => 'bg-info text-white'],
    'dang_giao'    => ['label' => 'Đang giao',    'class' => 'bg-primary text-white'],
    'hoan_thanh'   => ['label' => 'Hoàn thành',   'class' => 'bg-success text-white'],
    'da_huy'       => ['label' => 'Đã hủy',       'class' => 'bg-danger text-white'],
];
$statusFilter = $_GET['status'] ?? 'all';
?>

<?php
// Hiển thị thông báo thành công / lỗi từ query param
$successMsg = $_GET['success'] ?? '';
$errorMsg = $_GET['error'] ?? '';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">Quản lý Đơn hàng</h2>
    <div class="text-muted" style="font-size: 13px;">Tổng: <strong><?= count($orders) ?></strong> đơn hàng</div>
</div>

<?php if ($successMsg === 'deleted'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> Xóa đơn hàng thành công!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php elseif ($errorMsg === 'cannot_delete'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> Không thể xóa đơn hàng đang xử lý, đang giao hoặc đã hoàn thành!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Tab lọc trạng thái -->
<div class="d-flex gap-2 flex-wrap mb-4">
    <a href="?page=admin-orders" class="btn btn-sm <?= $statusFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary' ?>">Tất cả</a>
    <?php foreach($statusLabels as $key => $info): ?>
        <a href="?page=admin-orders&status=<?= $key ?>" class="btn btn-sm <?= $statusFilter === $key ? 'btn-primary' : 'btn-outline-secondary' ?>">
            <?= $info['label'] ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>SĐT</th>
                    <th>Tổng tiền</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $filtered = array_filter($orders, function($o) use ($statusFilter) {
                    return $statusFilter === 'all' || $o['trang_thai_don_hang'] === $statusFilter;
                });
                foreach($filtered as $o): 
                    $s = $statusLabels[$o['trang_thai_don_hang']] ?? ['label' => $o['trang_thai_don_hang'], 'class' => 'bg-secondary text-white'];
                ?>
                <tr>
                    <td class="fw-bold text-primary"><?= htmlspecialchars($o['ma_don_hang']) ?></td>
                    <td>
                        <div class="fw-bold text-dark"><?= htmlspecialchars($o['ho_ten_nguoi_nhan'] ?? $o['ho_ten'] ?? 'Khách vãng lai') ?></div>
                        <small class="text-muted"><?= htmlspecialchars($o['email'] ?? '') ?></small>
                    </td>
                    <td><?= htmlspecialchars($o['so_dien_thoai_nguoi_nhan'] ?? '') ?></td>
                    <td class="fw-bold"><?= number_format($o['tong_thanh_toan'], 0, ',', '.') ?> đ</td>
                    <td><?= date('d/m/Y H:i', strtotime($o['ngay_tao'])) ?></td>
                    <td><span class="badge <?= $s['class'] ?>"><?= $s['label'] ?></span></td>
                    <td class="text-end">
                        <a href="?page=admin-order-detail&id=<?= $o['id'] ?>" class="btn btn-sm btn-light text-primary me-1">
                            <i class="bi bi-eye"></i> Xem
                        </a>
                        <?php if (in_array($o['trang_thai_don_hang'], ['da_huy', 'cho_xac_nhan'])): ?>
                            <a href="?page=admin-order-delete&id=<?= $o['id'] ?>" 
                               class="btn btn-sm btn-light text-danger"
                               onclick="return confirm('Xóa đơn hàng này? Hành động này không thể hoàn tác!')">
                                <i class="bi bi-trash"></i> Xóa
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($filtered)): ?>
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-receipt fs-1 d-block mb-3"></i>
                        Không có đơn hàng nào trong mục này
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
