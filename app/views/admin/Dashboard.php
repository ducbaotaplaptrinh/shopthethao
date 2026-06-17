<div class="page-title">Tổng quan hệ thống</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="admin-card text-center mb-0" style="border-top: 4px solid var(--primary);">
            <div class="text-muted fw-bold mb-2 text-uppercase" style="font-size: 12px;">Tổng doanh thu</div>
            <h3 class="fw-bold mb-0" style="color: var(--primary);"><?= number_format($stats['total_revenue'], 0, ',', '.') ?> đ</h3>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="admin-card text-center mb-0" style="border-top: 4px solid #f59e0b;">
            <div class="text-muted fw-bold mb-2 text-uppercase" style="font-size: 12px;">Đơn chờ xác nhận</div>
            <h3 class="fw-bold mb-0 text-dark"><?= number_format($stats['pending_orders']) ?></h3>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="admin-card text-center mb-0" style="border-top: 4px solid #ef4444;">
            <div class="text-muted fw-bold mb-2 text-uppercase" style="font-size: 12px;">Cảnh báo hết kho (SKU)</div>
            <h3 class="fw-bold mb-0 text-danger"><?= number_format($stats['low_stock_items']) ?></h3>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="admin-card text-center mb-0" style="border-top: 4px solid #3b82f6;">
            <div class="text-muted fw-bold mb-2 text-uppercase" style="font-size: 12px;">Khách hàng</div>
            <h3 class="fw-bold mb-0 text-primary"><?= number_format($stats['total_users']) ?></h3>
        </div>
    </div>
</div>

<div class="row g-4">

    <div class="col-12 col-lg-8">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <h4 class="admin-card-title">Doanh thu 7 ngày gần nhất</h4>
            </div>
            <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <h4 class="admin-card-title">Hành động nhanh</h4>
            </div>
            <div class="d-grid gap-3">
                <a href="?page=admin-product-create" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-2">
                    <i class="bi bi-plus-circle"></i> Thêm Sản phẩm
                </a>
                <a href="?page=admin-orders" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 py-2">
                    <i class="bi bi-receipt"></i> Quản lý Đơn hàng
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');

        // Gradient fill for chart
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(255, 107, 0, 0.5)');
        gradient.addColorStop(1, 'rgba(255, 107, 0, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= $chartLabels ?>,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: <?= $chartData ?>,
                    borderColor: '#ff6b00',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#ff6b00',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + ' đ';
                            }
                        }
                    }
                }
            }
        });
    });
</script>