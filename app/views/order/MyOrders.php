<?php
// Định nghĩa bộ trạng thái đơn hàng — đồng bộ với admin
$statusMap = [
    'cho_xac_nhan' => ['label' => 'Chờ xác nhận',  'icon' => 'bi-hourglass-split',  'class' => 'status-cho_xac_nhan', 'step' => 1],
    'dang_xu_ly'   => ['label' => 'Đang xử lý',    'icon' => 'bi-gear-fill',        'class' => 'status-dang_xu_ly',   'step' => 2],
    'dang_giao'    => ['label' => 'Đang giao hàng', 'icon' => 'bi-truck',            'class' => 'status-dang_giao',    'step' => 3],
    'hoan_thanh'   => ['label' => 'Giao thành công','icon' => 'bi-patch-check-fill', 'class' => 'status-hoan_thanh',   'step' => 4],
    'da_huy'       => ['label' => 'Đã hủy',         'icon' => 'bi-x-circle-fill',   'class' => 'status-da_huy',       'step' => 0],
];

// Dòng thời gian hiển thị tiến trình đơn hàng
$timelineSteps = [
    ['key' => 'cho_xac_nhan', 'icon' => 'bi-hourglass-split',  'label' => 'Xác nhận'],
    ['key' => 'dang_xu_ly',   'icon' => 'bi-gear-fill',        'label' => 'Xử lý'],
    ['key' => 'dang_giao',    'icon' => 'bi-truck',            'label' => 'Vận chuyển'],
    ['key' => 'hoan_thanh',   'icon' => 'bi-patch-check-fill', 'label' => 'Đã giao'],
];

// Tabs lọc đơn hàng
$tabs = [
    'all'          => ['label' => 'Tất cả',        'icon' => 'bi-grid'],
    'cho_xac_nhan' => ['label' => 'Chờ xác nhận', 'icon' => 'bi-hourglass-split'],
    'dang_xu_ly'   => ['label' => 'Đang xử lý',   'icon' => 'bi-gear-fill'],
    'dang_giao'    => ['label' => 'Đang giao',     'icon' => 'bi-truck'],
    'hoan_thanh'   => ['label' => 'Thành công',    'icon' => 'bi-patch-check-fill'],
    'da_huy'       => ['label' => 'Đã hủy',       'icon' => 'bi-x-circle'],
];

$totalAll = array_sum($statusCounts ?? []);
?>

<!-- ============ HERO HEADER ============ -->
<div class="my-orders-hero">
    <div class="container-xl">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                style="width:52px;height:52px;background:rgba(255,255,255,.15);">
                <i class="bi bi-bag-heart-fill" style="font-size:1.5rem;"></i>
            </div>
            <div>
                <h1 class="mb-0">Đơn hàng của tôi</h1>
                <p class="mb-0">Xin chào, <strong><?= htmlspecialchars($_SESSION['user']['ho_ten'] ?? 'Bạn') ?></strong> — theo dõi toàn bộ đơn hàng của bạn tại đây.</p>
            </div>
        </div>
    </div>
</div>

<div class="container-xl pb-5">

    <!-- ============ STATUS TABS ============ -->
    <div class="order-tabs">
        <?php foreach ($tabs as $key => $tab): ?>
            <?php
            $count = ($key === 'all') ? $totalAll : ($statusCounts[$key] ?? 0);
            $isActive = ($activeTab === $key);
            ?>
            <a href="?page=my-orders&status=<?= $key ?>"
                class="order-tab <?= $isActive ? 'active' : '' ?>"
                id="tab-<?= $key ?>">
                <i class="bi <?= $tab['icon'] ?>"></i>
                <?= $tab['label'] ?>
                <?php if ($count > 0): ?>
                    <span class="tab-badge"><?= $count ?></span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- ============ ORDER LIST ============ -->
    <?php if (empty($orders)): ?>
        <div class="empty-orders">
            <div class="empty-orders-icon">📦</div>
            <h4>Chưa có đơn hàng nào</h4>
            <p>Bạn chưa có đơn hàng nào trong mục này. Hãy khám phá các sản phẩm thể thao của chúng tôi!</p>
            <a href="?page=product-index" class="btn btn-primary mt-3 px-4 rounded-3 fw-semibold">
                <i class="bi bi-bag me-2"></i>Mua sắm ngay
            </a>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <?php
            $status    = $order->getTrang_thai_don_hang();
            $statusInfo = $statusMap[$status] ?? ['label' => $status, 'icon' => 'bi-question-circle', 'class' => '', 'step' => 0];
            $isCancelled = ($status === 'da_huy');
            $currentStep = $statusInfo['step'];
            ?>
            <div class="order-card">

                <!-- Card Header -->
                <div class="order-card-header">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div>
                            <a class="order-code" href="?page=order-success&code=<?= htmlspecialchars($order->getMa_don_hang()) ?>">
                                <i class="bi bi-receipt me-1"></i><?= htmlspecialchars($order->getMa_don_hang()) ?>
                            </a>
                            <div class="order-date mt-1">
                                <i class="bi bi-clock me-1"></i>
                                <?= $order->getNgay_tao() ? $order->getNgay_tao()->format('H:i — d/m/Y') : '' ?>
                            </div>
                        </div>
                    </div>
                    <span class="status-badge <?= $statusInfo['class'] ?>">
                        <i class="bi <?= $statusInfo['icon'] ?>"></i>
                        <?= $statusInfo['label'] ?>
                    </span>
                </div>




                <!-- Card Body — danh sách sản phẩm (lấy từ DB) -->
                <?php
                // Lấy chi tiết đơn hàng để hiển thị sản phẩm
                $orderDetails = (new \app\models\OrderModel())->getOrderDetails($order->getMa_don_hang());
                $items = $orderDetails['items'] ?? [];
                $showMax = 2; // chỉ hiển thị tối đa 2 sản phẩm trong card
                ?>
                <div class="order-card-body">
                    <?php foreach (array_slice($items, 0, $showMax) as $item): ?>
                        <div class="order-item">
                            <img src="<?= htmlspecialchars(getProductImage($item->getAnh_dai_dien())) ?>"
                                alt="<?= htmlspecialchars($item->getTen_san_pham()) ?>"
                                class="order-item-img">
                            <div class="flex-grow-1" style="min-width:0;">
                                <div class="order-item-name text-truncate"><?= htmlspecialchars($item->getTen_san_pham()) ?></div>
                                <?php if ($item->getThong_tin_bien_the()): ?>
                                    <div class="order-item-meta"><?= htmlspecialchars($item->getThong_tin_bien_the()) ?></div>
                                <?php endif; ?>
                                <div class="order-item-meta">Số lượng: <?= $item->getSo_luong() ?></div>
                            </div>
                            <div class="order-item-price"><?= formatVND($item->getThanh_tien()) ?></div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (count($items) > $showMax): ?>
                        <div class="order-more-items">
                            <i class="bi bi-three-dots"></i>
                            và <?= count($items) - $showMax ?> sản phẩm khác
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Card Footer -->
                <div class="order-card-footer">
                    <div>
                        <div class="order-total-label">Tổng thanh toán</div>
                        <div class="order-total-amount"><?= formatVND($order->getTong_thanh_toan()) ?></div>
                    </div>
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <?php if ($status === 'hoan_thanh'): ?>
                            <a href="?page=product-index"
                                class="btn btn-sm btn-outline-success rounded-3 fw-semibold">
                                <i class="bi bi-arrow-repeat me-1"></i>Mua lại
                            </a>
                        <?php endif; ?>
                        <a href="?page=order-success&code=<?= htmlspecialchars($order->getMa_don_hang()) ?>"
                            class="btn-view-order">
                            <i class="bi bi-eye"></i>Xem chi tiết
                        </a>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>