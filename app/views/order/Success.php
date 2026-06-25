<div class="container-xl py-5">
    <div class="card text-center py-5 shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <div class="mb-4 text-success">
                <i class="bi bi-check-circle-fill" style="font-size: 5rem; color: #28a745;"></i>
            </div>
            <h2 class="mb-3">Đặt Hàng Thành Công!</h2>
            <p class="text-muted mb-4 fs-5">Cảm ơn bạn đã mua hàng tại <strong>Bảo Đạt Sport</strong>. Đơn hàng của bạn đang được xử lý.</p>
            <div class="d-inline-block bg-light px-4 py-3 rounded-4 mb-3">
                <span class="text-muted d-block small">Mã đơn hàng của bạn:</span>
                <strong class="fs-4 text-primary text-uppercase"><?= htmlspecialchars($order->getMa_don_hang()) ?></strong>
            </div>
            <p class="text-muted small">Vui lòng lưu lại mã đơn hàng này để có thể <a href="?page=order-track&term=<?= htmlspecialchars($order->getMa_don_hang()) ?>">Tra cứu hành trình đơn hàng</a>.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left: Order Summary details -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 p-4 h-100">
                <h4 class="mb-4 text-dark"><i class="bi bi-file-earmark-text me-2"></i>Chi tiết người nhận</h4>

                <table class="table table-borderless align-middle mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted py-2" style="width: 160px;">Họ tên người nhận:</td>
                            <td class="fw-semibold text-dark py-2"><?= htmlspecialchars($order->getHo_ten_nguoi_nhan()) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Số điện thoại:</td>
                            <td class="fw-semibold text-dark py-2"><?= htmlspecialchars($order->getSo_dien_thoai()) ?></td>
                        </tr>
                        <?php if (!empty($order->getEmail())): ?>
                            <tr>
                                <td class="text-muted py-2">Email:</td>
                                <td class="text-dark py-2"><?= htmlspecialchars($order->getEmail()) ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="text-muted py-2">Địa chỉ giao hàng:</td>
                            <td class="text-dark py-2"><?= nl2br(htmlspecialchars($order->getDia_chi_giao_hang())) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Phương thức:</td>
                            <td class="fw-semibold text-primary py-2 text-uppercase"><?= htmlspecialchars($order->getPhuong_thuc_thanh_toan() === 'cod' ? 'Thanh toán COD' : 'Chuyển khoản') ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Tổng thanh toán:</td>
                            <td class="fw-bold text-danger fs-5 py-2"><?= htmlspecialchars(formatVND($order->getTong_thanh_toan())) ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4">
                    <?php if ($order->getTrang_thai_don_hang() === 'cho_xac_nhan'): ?>
                        <div class="d-flex gap-2">
                            <a href="?page=home" class="btn btn-outline-dark w-50 fw-semibold rounded-3 py-2">
                                Quay lại trang chủ
                            </a>
                            <a href="?page=order-cancel&code=<?= htmlspecialchars($order->getMa_don_hang()) ?>" 
                               class="btn btn-danger w-50 fw-semibold rounded-3 py-2"
                               onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                                <i class="bi bi-x-circle me-1"></i>Hủy đơn hàng
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="?page=home" class="btn btn-outline-dark w-100 fw-semibold rounded-3 py-2">
                            Quay lại trang chủ
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right: Payment Transfer QR info if bank transfer is selected -->
        <div class="col-lg-6">
            <?php if ($order->getPhuong_thuc_thanh_toan() === 'chuyen_khoan'): ?>
                <div class="card shadow-sm border-0 rounded-4 p-4 h-100 text-center">
                    <h4 class="mb-3 text-dark text-lg-start"><i class="bi bi-qr-code-scan me-2"></i>Chuyển khoản thanh toán</h4>
                    <p class="text-muted text-lg-start small mb-4">Vui lòng quét mã QR dưới đây hoặc chuyển khoản theo thông tin tài khoản ngân hàng để hoàn thành thanh toán.</p>

                    <div class="row g-3 align-items-center">
                        <div class="col-sm-6 text-center">
                            <?php
                            $qrAmount = (int)$order->getTong_thanh_toan();
                            $qrCode = htmlspecialchars($order->getMa_don_hang());
                            $qrUrl = "https://img.vietqr.io/image/vietinbank-102873928192-compact2.png?amount={$qrAmount}&addInfo={$qrCode}&accountName=CONG%20TY%20THE%20THAO%20BAO%20DAT";
                            ?>
                            <img src="<?= $qrUrl ?>" alt="Mã QR VietQR" class="img-fluid border rounded-3 p-2 shadow-sm" style="max-height: 250px;">
                        </div>
                        <div class="col-sm-6 text-start">
                            <div class="mb-2">
                                <span class="text-muted small d-block">Ngân hàng:</span>
                                <strong class="text-dark">VietinBank</strong>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Số tài khoản:</span>
                                <strong class="text-primary fs-5">1028 7392 8192</strong>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Chủ tài khoản:</span>
                                <strong class="text-dark">CONG TY TNHH THE THAO BAO DAT</strong>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Số tiền chuyển:</span>
                                <strong class="text-danger fs-5"><?= htmlspecialchars(formatVND($order->getTong_thanh_toan())) ?></strong>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Nội dung chuyển khoản:</span>
                                <strong class="text-primary fs-5 text-uppercase"><?= htmlspecialchars($order->getMa_don_hang()) ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card shadow-sm border-0 rounded-4 p-4 h-100">
                    <h4 class="mb-4 text-dark"><i class="bi bi-box-seam me-2"></i>Thông tin đơn hàng đã đặt</h4>

                    <div class="ordered-items-list" style="max-height: 300px; overflow-y: auto;">
                        <?php foreach ($items as $item): ?>
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                                <img src="<?= htmlspecialchars(getProductImage($item->getAnh_dai_dien())) ?>" alt="" style="width: 50px; height: 50px; object-fit: contain; border-radius: 8px; border: 1px solid #eee; padding: 2px;">
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <div class="text-truncate fw-semibold text-dark small"><?= htmlspecialchars($item->getTen_san_pham()) ?></div>
                                    <?php if (!empty($item->getThong_tin_bien_the())): ?>
                                        <div class="text-muted small" style="font-size: 11px;"><?= htmlspecialchars($item->getThong_tin_bien_the()) ?></div>
                                    <?php endif; ?>
                                    <div class="small text-muted mt-1">Số lượng: <?= htmlspecialchars($item->getSo_luong()) ?></div>
                                </div>
                                <div class="fw-bold text-end text-dark"><?= htmlspecialchars(formatVND($item->getThanh_tien())) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>