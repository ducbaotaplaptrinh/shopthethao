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
                            // Bộ trạng thái — đồng bộ với admin
                            $statusMap = [
                                'cho_xac_nhan' => ['label' => 'Chờ xác nhận',  'icon' => 'bi-hourglass-split',  'class' => 'status-cho_xac_nhan', 'step' => 1],
                                'dang_xu_ly'   => ['label' => 'Đang xử lý',    'icon' => 'bi-gear-fill',        'class' => 'status-dang_xu_ly',   'step' => 2],
                                'dang_giao'    => ['label' => 'Đang giao hàng', 'icon' => 'bi-truck',            'class' => 'status-dang_giao',    'step' => 3],
                                'hoan_thanh'   => ['label' => 'Giao thành công', 'icon' => 'bi-patch-check-fill', 'class' => 'status-hoan_thanh',   'step' => 4],
                                'da_huy'       => ['label' => 'Đã hủy',         'icon' => 'bi-x-circle-fill',   'class' => 'status-da_huy',       'step' => 0],
                            ];
                            $timelineSteps = [
                                ['key' => 'cho_xac_nhan', 'icon' => 'bi-hourglass-split',  'label' => 'Xác nhận'],
                                ['key' => 'dang_xu_ly',   'icon' => 'bi-gear-fill',        'label' => 'Xử lý'],
                                ['key' => 'dang_giao',    'icon' => 'bi-truck',            'label' => 'Vận chuyển'],
                                ['key' => 'hoan_thanh',   'icon' => 'bi-patch-check-fill', 'label' => 'Đã giao'],
                            ];
                            $trangThai = $order->getTrang_thai_don_hang();
                            $si = $statusMap[$trangThai] ?? ['label' => $trangThai, 'icon' => 'bi-question-circle', 'class' => '', 'step' => 0];
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
                                        <span class="status-badge <?= htmlspecialchars($si['class']) ?> px-3 py-1"><?= htmlspecialchars($si['label']) ?></span>
                                    </div>
                                </div>

                                <!-- Progress Timeline inside tracking card -->
                                <?php if ($trangThai !== 'da_huy'): ?>
                                    <div class="order-timeline border-bottom pb-3 mb-3">
                                        <?php foreach ($timelineSteps as $step): ?>
                                            <?php
                                            $stepKey = $step['key'];
                                            $stepLabel = $step['label'];
                                            $stepIcon = $step['icon'];
                                            
                                            $isStepDone = false;
                                            $isStepActive = false;
                                            
                                            $orderStepVal = $statusMap[$trangThai]['step'];
                                            $currentStepVal = $statusMap[$stepKey]['step'];
                                            
                                            if ($orderStepVal >= $currentStepVal) {
                                                $isStepDone = true;
                                            }
                                            if ($trangThai === $stepKey) {
                                                $isStepActive = true;
                                            }
                                            
                                            $stepClass = '';
                                            if ($isStepActive) {
                                                $stepClass = 'active-step';
                                            } elseif ($isStepDone) {
                                                $stepClass = 'done';
                                            }
                                            ?>
                                            <div class="tl-step <?= $stepClass ?>">
                                                <div class="tl-icon">
                                                    <i class="bi <?= $stepIcon ?>"></i>
                                                 </div>
                                                <div class="tl-label"><?= $stepLabel ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="order-timeline border-bottom pb-3 mb-3">
                                        <div class="tl-step cancelled">
                                            <div class="tl-icon">
                                                <i class="bi bi-x-circle-fill"></i>
                                            </div>
                                            <div class="tl-label text-danger">Đã hủy đơn</div>
                                        </div>
                                    </div>
                                <?php endif; ?>

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
