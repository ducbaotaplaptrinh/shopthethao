<div class="container-xl py-5">
    <div class="breadcrumb-wrapper mb-4">
        <a href="?page=home">Trang chủ ></a>
        <a href="#!" class="text-dark fw-bold">Tra cứu đơn hàng</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                <h3 class="text-center mb-4 text-dark"><i class="bi bi-search me-2" style="color: #ff7b00;"></i>Tra Cứu Đơn Hàng</h3>
                <p class="text-muted text-center small mb-4">Nhập mã đơn hàng (ví dụ: DH-XXXXXX) hoặc số điện thoại người nhận để kiểm tra trạng thái và lịch sử mua hàng.</p>
                
                <form action="" method="GET">
                    <input type="hidden" name="page" value="order-track">
                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                        <input type="text" name="term" class="form-control border-end-0 rounded-start-3" placeholder="Nhập mã đơn hàng hoặc SĐT người nhận" value="<?= htmlspecialchars($term ?? '') ?>" required>
                        <button type="submit" class="btn btn-primary px-4 border-start-0 rounded-end-3" style="background: linear-gradient(135deg, #ff7b00, #ff9500); border: none;">
                            Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>

            <?php if (!empty($term)): ?>
                <h4 class="mb-3 text-dark">Kết quả tra cứu cho: "<span class="text-primary"><?= htmlspecialchars($term) ?></span>"</h4>

                <?php if (empty($orders)): ?>
                    <div class="card text-center py-5 shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <i class="bi bi-file-earmark-x text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">Không tìm thấy đơn hàng nào!</h5>
                            <p class="text-muted small">Vui lòng kiểm tra lại chính xác Mã đơn hàng hoặc Số điện thoại đã đặt hàng.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($orders as $order): ?>
                            <?php 
                            // Status badges colors
                            $statusLabel = 'Chờ xác nhận';
                            $statusClass = 'bg-warning text-dark';
                            
                            switch ($order->getTrang_thai_don_hang()) {
                                case 'da_xac_nhan':
                                    $statusLabel = 'Đã xác nhận';
                                    $statusClass = 'bg-info text-white';
                                    break;
                                case 'dang_giao':
                                    $statusLabel = 'Đang giao hàng';
                                    $statusClass = 'bg-primary text-white';
                                    break;
                                case 'hoan_thanh':
                                    $statusLabel = 'Đã giao thành công';
                                    $statusClass = 'bg-success text-white';
                                    break;
                                case 'da_huy':
                                    $statusLabel = 'Đã hủy đơn';
                                    $statusClass = 'bg-danger text-white';
                                    break;
                            }
                            ?>
                            <div class="card shadow-sm border-0 rounded-4 p-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 border-bottom pb-3 mb-3">
                                    <div>
                                        <span class="text-muted small d-block">Mã đơn hàng:</span>
                                        <a href="?page=order-success&code=<?= htmlspecialchars($order->getMa_don_hang()) ?>" class="fw-bold fs-5 text-primary text-decoration-none text-uppercase">
                                            <?= htmlspecialchars($order->getMa_don_hang()) ?> <i class="bi bi-box-arrow-up-right fs-6"></i>
                                        </a>
                                    </div>
                                    <div class="text-md-end">
                                        <span class="text-muted small d-block">Trạng thái đơn hàng:</span>
                                        <span class="badge <?= $statusClass ?> px-3 py-2 rounded-pill fw-semibold"><?= $statusLabel ?></span>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-sm-6 col-md-3">
                                        <span class="text-muted small d-block">Ngày đặt:</span>
                                        <strong class="text-dark"><?= $order->getNgay_tao() ? $order->getNgay_tao()->format('d/m/Y H:i') : '' ?></strong>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <span class="text-muted small d-block">Người nhận:</span>
                                        <strong class="text-dark"><?= htmlspecialchars($order->getHo_ten_nguoi_nhan()) ?></strong>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <span class="text-muted small d-block">Thanh toán:</span>
                                        <strong class="text-dark text-uppercase"><?= htmlspecialchars($order->getPhuong_thuc_thanh_toan() === 'cod' ? 'COD' : 'Chuyển khoản') ?></strong>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <span class="text-muted small d-block">Tổng thanh toán:</span>
                                        <strong class="text-danger fs-5"><?= htmlspecialchars(formatVND($order->getTong_thanh_toan())) ?></strong>
                                    </div>
                                </div>
                                
                                <div class="mt-3 pt-3 border-top d-flex justify-content-end">
                                    <a href="?page=order-success&code=<?= htmlspecialchars($order->getMa_don_hang()) ?>" class="btn btn-sm btn-outline-primary rounded-3 fw-semibold">
                                        Xem chi tiết đơn hàng
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
