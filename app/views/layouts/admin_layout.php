<?php
// Ensure user is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'quan_tri') {
    header('Location: ?page=home');
    exit;
}
$currentPage = $_GET['page'] ?? 'admin-dashboard';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin Panel | Bảo Đạt Sport') ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?php if (isset($pageStyles)): ?>
        <?php foreach ($pageStyles as $style): ?>
            <link rel="stylesheet" href="<?= $style ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>

    <div class="admin-wrapper">

        <!-- SIDEBAR -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <i class="fa-solid fa-bolt"></i>
                <span>Bảo Đạt Sport</span>
            </div>

            <div class="sidebar-menu">
                <div class="text-uppercase text-muted fw-bold mb-2 px-3 mt-2" style="font-size: 0.75rem; letter-spacing: 1px;">Main</div>
                <a href="?page=admin-dashboard" class="sidebar-menu-item <?= $currentPage === 'admin-dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2"></i>
                    <span>Dashboard</span>
                </a>

                <div class="text-uppercase text-muted fw-bold mb-2 px-3 mt-4" style="font-size: 0.75rem; letter-spacing: 1px;">Sản phẩm</div>
                <a href="?page=admin-products" class="sidebar-menu-item <?= strpos($currentPage, 'admin-product') === 0 ? 'active' : '' ?>">
                    <i class="bi bi-box-seam"></i>
                    <span>Quản lý sản phẩm</span>
                </a>
                <a href="?page=admin-attributes" class="sidebar-menu-item <?= strpos($currentPage, 'admin-attribute') === 0 ? 'active' : '' ?>">
                    <i class="bi bi-tags"></i>
                    <span>Thuộc tính động</span>
                </a>
                <a href="?page=admin-categories" class="sidebar-menu-item <?= strpos($currentPage, 'admin-categor') === 0 ? 'active' : '' ?>">
                    <i class="bi bi-list-nested"></i>
                    <span>Danh mục</span>
                </a>
                <a href="?page=admin-brands" class="sidebar-menu-item <?= strpos($currentPage, 'admin-brand') === 0 ? 'active' : '' ?>">
                    <i class="bi bi-star"></i>
                    <span>Thương hiệu</span>
                </a>

                <div class="text-uppercase text-muted fw-bold mb-2 px-3 mt-4" style="font-size: 0.75rem; letter-spacing: 1px;">Giao dịch</div>
                <a href="?page=admin-orders" class="sidebar-menu-item <?= strpos($currentPage, 'admin-order') === 0 ? 'active' : '' ?>">
                    <i class="bi bi-receipt"></i>
                    <span>Đơn hàng</span>
                </a>
                <a href="?page=admin-customers" class="sidebar-menu-item <?= strpos($currentPage, 'admin-customer') === 0 ? 'active' : '' ?>">
                    <i class="bi bi-people"></i>
                    <span>Khách hàng & Hạng</span>
                </a>

                <div class="text-uppercase text-muted fw-bold mb-2 px-3 mt-4" style="font-size: 0.75rem; letter-spacing: 1px;">Hệ thống</div>
                <a href="?page=home" class="sidebar-menu-item text-primary bg-light mt-2 border border-primary-subtle">
                    <i class="bi bi-shop"></i>
                    <span>Ra trang cửa hàng</span>
                </a>
                <a href="?page=logout" class="sidebar-menu-item text-danger">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Đăng xuất</span>
                </a>
            </div>
        </aside>

        <!-- MAIN AREA -->
        <main class="admin-main">
            <!-- HEADER -->
            <header class="admin-header">
                <button class="mobile-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>

                <div class="header-search">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Tìm kiếm nhanh mã đơn hàng...">
                </div>

                <div class="header-actions">
                    <div class="dropdown">
                        <button class="btn btn-light position-relative rounded-circle p-2" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                3
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                            <li>
                                <h6 class="dropdown-header">Thông báo mới</h6>
                            </li>
                            <li><a class="dropdown-item py-2" href="#">Đơn hàng DH-123 vừa được đặt</a></li>
                            <li><a class="dropdown-item py-2" href="#">Vợt Yonex Astrox 99 sắp hết hàng</a></li>
                        </ul>
                    </div>

                    <div class="user-profile dropdown" style="cursor: pointer;" data-bs-toggle="dropdown">
                        <div class="text-end d-none d-md-block">
                            <div class="text-dark mb-0 lh-1"><?= htmlspecialchars($_SESSION['user']['ho_ten'] ?? 'Admin') ?></div>
                            <small class="text-muted" style="font-size: 12px;">Quản trị viên</small>
                        </div>
                        <div class="user-avatar">
                            <?= strtoupper(substr($_SESSION['user']['ho_ten'] ?? 'A', 0, 1)) ?>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                            <li><a class="dropdown-item" href="?page=home"><i class="bi bi-shop me-2"></i>Xem cửa hàng</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="?page=logout"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <div class="admin-content">
                <?php echo $content; ?>
            </div>
        </main>

    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Mobile Sidebar Toggle JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('adminSidebar');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
        });
    </script>

    <?php if (isset($pageScripts)): ?>
        <?php foreach ($pageScripts as $script): ?>
            <script src="public/<?= $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

</body>

</html>