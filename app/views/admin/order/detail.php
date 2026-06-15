<?php
$statusLabels = [
    'cho_xac_nhan' => ['label' => 'Chờ xác nhận', 'class' => 'bg-warning text-dark', 'icon' => 'bi-clock'],
    'dang_xu_ly'   => ['label' => 'Đang xử lý',   'class' => 'bg-info text-white',  'icon' => 'bi-gear'],
    'dang_giao'    => ['label' => 'Đang giao',     'class' => 'bg-primary text-white', 'icon' => 'bi-truck'],
    'hoan_thanh'   => ['label' => 'Hoàn thành',    'class' => 'bg-success text-white', 'icon' => 'bi-check-circle'],
    'da_huy'       => ['label' => 'Đã hủy',        'class' => 'bg-danger text-white', 'icon' => 'bi-x-circle'],
];
$currentStatus = $order['trang_thai_don_hang'];
$s = $statusLabels[$currentStatus] ?? ['label' => $currentStatus, 'class' => 'bg-secondary', 'icon' => 'bi-circle'];
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <a href="?page=admin-orders" class="text-muted text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Đơn hàng</a>
        <h2 class="page-title mb-0 mt-1">Đơn hàng: <span style="color: var(--primary);"><?= htmlspecialchars($order['ma_don_hang']) ?></span></h2>
    </div>
    <div class="d-flex gap-2">
        <!-- In hóa đơn -->
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="bi bi-printer me-1"></i> In hóa đơn
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Thông tin đơn + Sản phẩm -->
    <div class="col-12 col-lg-8">
        <!-- Sản phẩm trong đơn -->
        <div class="admin-card mb-4">
            <h4 class="admin-card-title mb-4"><i class="bi bi-box-seam me-2"></i>Sản phẩm đã đặt</h4>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-center">SL</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item):
                        ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($item['ten_san_pham'] ?? 'Sản phẩm') ?></div>
                                    <small class="text-muted">SKU: <?= htmlspecialchars($item['ma_vach_sku'] ?? 'N/A') ?></small>
                                </td>
                                <td class="text-center"><?= $item['so_luong'] ?></td>
                                <td class="text-end"><?= number_format($item['gia_mua'], 0, ',', '.') ?> đ</td>
                                <td class="text-end fw-bold"><?= number_format($item['so_luong'] * $item['gia_mua'], 0, ',', '.') ?> đ</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                            <td class="text-end fw-bold fs-5" style="color: var(--primary);"><?= number_format($order['tong_thanh_toan'], 0, ',', '.') ?> đ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Thông tin giao hàng -->
        <div class="admin-card">
            <h4 class="admin-card-title mb-4"><i class="bi bi-geo-alt me-2"></i>Thông tin giao hàng</h4>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted mb-1">Người nhận</label>
                    <div class="fw-bold"><?= htmlspecialchars($order['ho_ten_nguoi_nhan'] ?? 'N/A') ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted mb-1">Số điện thoại</label>
                    <div class="fw-bold"><?= htmlspecialchars($order['so_dien_thoai_nguoi_nhan'] ?? 'N/A') ?></div>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label text-muted mb-1">Địa chỉ giao hàng</label>
                    <div class="fw-bold"><?= htmlspecialchars($order['dia_chi_giao_hang'] ?? 'N/A') ?></div>
                </div>
                <?php if (!empty($order['ghi_chu'])): ?>
                    <div class="col-12">
                        <label class="form-label text-muted mb-1">Ghi chú</label>
                        <div class="fst-italic"><?= htmlspecialchars($order['ghi_chu']) ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar cập nhật trạng thái -->
    <div class="col-12 col-lg-4">
        <!-- Trạng thái hiện tại -->
        <div class="admin-card mb-4">
            <h4 class="admin-card-title mb-4"><i class="bi bi-activity me-2"></i>Trạng thái đơn hàng</h4>
            <div class="text-center mb-4">
                <span class="badge <?= $s['class'] ?> px-4 py-2 fs-6">
                    <i class="bi <?= $s['icon'] ?> me-1"></i>
                    <?= $s['label'] ?>
                </span>
            </div>

            <!-- Dòng thời gian trạng thái -->
            <div class="d-flex flex-column gap-2 mb-4">
                <?php
                $allStatuses = ['cho_xac_nhan', 'dang_xu_ly', 'dang_giao', 'hoan_thanh'];
                $currentIndex = array_search($currentStatus, $allStatuses);
                foreach ($allStatuses as $i => $st):
                    $passed = ($i <= $currentIndex && $currentStatus !== 'da_huy');
                    $info = $statusLabels[$st];
                ?>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width: 30px; height: 30px; background: <?= $passed ? 'var(--primary-gradient)' : '#e5e7eb' ?>; color: <?= $passed ? '#fff' : '#9ca3af' ?>;">
                            <i class="bi <?= $info['icon'] ?>" style="font-size: 0.8rem;"></i>
                        </div>
                        <span class="<?= $passed ? 'fw-bold text-dark' : 'text-muted' ?>"><?= $info['label'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Form cập nhật -->
            <?php if ($currentStatus !== 'hoan_thanh' && $currentStatus !== 'da_huy'): ?>
                <form action="?page=admin-order-update-status" method="POST">
                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Chuyển sang trạng thái</label>
                        <select name="trang_thai_don_hang" class="form-select">
                            <?php foreach ($statusLabels as $key => $info): ?>
                                <option value="<?= $key ?>" <?= $key === $currentStatus ? 'selected' : '' ?>>
                                    <?= $info['label'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save me-1"></i> Cập nhật trạng thái
                    </button>
                </form>
            <?php else: ?>
                <div class="text-center text-muted py-2">
                    <i class="bi bi-lock me-1"></i> Đơn hàng đã kết thúc
                </div>
            <?php endif; ?>
        </div>

        <!-- Thông tin thanh toán -->
        <div class="admin-card">
            <h4 class="admin-card-title mb-3"><i class="bi bi-credit-card me-2"></i>Thanh toán</h4>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Phương thức:</span>
                <span class="fw-bold"><?= htmlspecialchars($order['phuong_thuc_thanh_toan'] ?? 'COD') ?></span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Mã giảm giá:</span>
                <span class="fw-bold"><?= htmlspecialchars($order['ma_giam_gia'] ?? 'Không có') ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Khu vực In hóa đơn (ẩn màn hình, hiện khi in) -->
<div id="printInvoiceArea" style="display:none;">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #printInvoiceArea,
            #printInvoiceArea * {
                visibility: visible !important;
                display: block !important;
            }

            #printInvoiceArea {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
            }
        }
    </style>
    <div style="font-family: 'Inter', sans-serif; max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #ddd;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #ff6b00; margin: 0;">Bảo Đạt Sport</h2>
            <p style="margin: 5px 0; color: #666;">HÓA ĐƠN BÁN HÀNG</p>
            <p style="margin: 0; font-size: 13px;">Mã đơn: <strong><?= $order['ma_don_hang'] ?></strong></p>
            <p style="margin: 0; font-size: 13px;">Ngày: <?= date('d/m/Y H:i', strtotime($order['ngay_tao'])) ?></p>
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex: 1; background: #f9f9f9; padding: 12px; border-radius: 6px;">
                <p style="font-weight: bold; margin: 0 0 8px;">Thông tin người nhận:</p>
                <p style="margin: 3px 0;"><?= htmlspecialchars($order['ho_ten_nguoi_nhan'] ?? '') ?></p>
                <p style="margin: 3px 0;"><?= htmlspecialchars($order['so_dien_thoai_nguoi_nhan'] ?? '') ?></p>
                <p style="margin: 3px 0;"><?= htmlspecialchars($order['dia_chi_giao_hang'] ?? '') ?></p>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #ff6b00;">
                    <th style="text-align: left; padding: 8px 0;">Sản phẩm</th>
                    <th style="text-align: center; padding: 8px 0;">SL</th>
                    <th style="text-align: right; padding: 8px 0;">Đơn giá</th>
                    <th style="text-align: right; padding: 8px 0;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 8px 0;">
                            <?= htmlspecialchars($item['ten_san_pham'] ?? '') ?>
                            <br><small style="color: #888;">SKU: <?= htmlspecialchars($item['ma_vach_sku'] ?? '') ?></small>
                        </td>
                        <td style="text-align: center; padding: 8px 0;"><?= $item['so_luong'] ?></td>
                        <td style="text-align: right; padding: 8px 0;"><?= number_format($item['don_gia'], 0, ',', '.') ?> đ</td>
                        <td style="text-align: right; padding: 8px 0; font-weight: bold;"><?= number_format($item['so_luong'] * $item['don_gia'], 0, ',', '.') ?> đ</td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold; padding: 12px 0;">Tổng cộng:</td>
                    <td style="text-align: right; font-weight: bold; font-size: 18px; color: #ff6b00; padding: 12px 0;">
                        <?= number_format($order['tong_thanh_toan'], 0, ',', '.') ?> đ
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 30px; color: #888; font-size: 13px; border-top: 1px solid #eee; padding-top: 15px;">
            Cảm ơn quý khách đã mua hàng tại Bảo Đạt Sport! ❤️
        </div>
    </div>
</div>

<script>
    window.print = (function(originalPrint) {
        return function() {
            const printArea = document.getElementById('printInvoiceArea');
            if (printArea) printArea.style.display = 'block';
            originalPrint();
            setTimeout(() => {
                if (printArea) printArea.style.display = 'none';
            }, 500);
        };
    })(window.print);
</script>