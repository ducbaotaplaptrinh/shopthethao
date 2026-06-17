<?php
// Giao diện chi tiết bài viết tin tức
// Các biến đã được Controller trích xuất và truyền sang: $article, $relatedNews
?>

<div class="news-detail-wrapper py-5">
    <div class="container-xl">
        
        <!-- Đường dẫn Breadcrumbs -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb mb-0 px-0 bg-transparent">
                <li class="breadcrumb-item"><a href="?page=home" class="text-muted text-decoration-none hover-orange">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="?page=new" class="text-muted text-decoration-none hover-orange">Tin tức</a></li>
                <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Chi tiết bài viết</li>
            </ol>
        </nav>

        <?php if (!$article): ?>
            <!-- Trường hợp bài viết không tồn tại -->
            <div class="text-center py-5 bg-white rounded-4 shadow-sm border my-5">
                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                <h2 class="mt-3 fw-bold">Bài Viết Không Tồn Tại</h2>
                <p class="text-muted">Bài viết bạn đang tìm kiếm có thể đã bị xóa hoặc đường dẫn không chính xác.</p>
                <a href="?page=new" class="btn btn-common text-white rounded-pill px-4 mt-3">
                    <i class="bi bi-arrow-left me-2"></i> Quay lại danh sách tin tức
                </a>
            </div>
        <?php else: ?>
            
            <!-- Phần chính của bài viết -->
            <div class="row g-4 justify-content-center">
                <div class="col-lg-9">
                    <article class="article-content bg-white p-4 p-md-5 rounded-4 shadow-sm border border-light">
                        
                        <!-- Tiêu đề lớn -->
                        <h1 class="article-title display-6 fw-bold text-dark mb-4" style="line-height: 1.3;">
                            <?php echo htmlspecialchars($article['tieu_de'], ENT_QUOTES, 'UTF-8'); ?>
                        </h1>

                        <!-- Thông tin metadata bài viết (Dùng cột ngay_tao) -->
                        <div class="d-flex flex-wrap align-items-center gap-3 pb-4 mb-4 border-bottom text-muted small">
                            <span class="d-flex align-items-center">
                                <i class="bi bi-person-fill text-warning me-1" style="font-size: 1.1rem;"></i>
                                Đăng bởi: <strong class="text-dark ms-1"><?php echo htmlspecialchars($article['tac_gia'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            </span>
                            <span class="text-secondary">|</span>
                            <span class="d-flex align-items-center">
                                <i class="bi bi-calendar3 text-warning me-1"></i>
                                <?php echo date('d/m/Y - H:i', strtotime($article['ngay_tao'])); ?>
                            </span>
                            <span class="text-secondary">|</span>
                            <span class="d-flex align-items-center">
                                <i class="bi bi-eye-fill text-warning me-1"></i>
                                <?php echo number_format($article['luot_xem'] ?? 0); ?> lượt xem
                            </span>
                        </div>

                        <!-- Ảnh bìa lớn của bài viết (Dùng cột anh_dai_dien) -->
                        <div class="article-image-wrapper mb-5 rounded-4 overflow-hidden shadow-sm">
                            <img src="<?php echo htmlspecialchars($article['anh_dai_dien'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                                 alt="<?php echo htmlspecialchars($article['tieu_de'], ENT_QUOTES, 'UTF-8'); ?>" 
                                 class="w-100 img-fluid"
                                 style="max-height: 480px; object-fit: cover;"
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1560089000-7433a4ebbd64?q=80&w=1000&auto=format&fit=crop';">
                        </div>

                        <!-- Nội dung chi tiết bài viết (Dạng HTML từ cơ sở dữ liệu) -->
                        <div class="article-body-text text-dark fs-5 mb-5" style="line-height: 1.8;">
                            <?php 
                            // Nội dung được biên soạn trong database có định dạng HTML, an toàn hiển thị nội dung của hệ thống
                            echo $article['noi_dung']; 
                            ?>
                        </div>

                        <!-- Nút quay lại -->
                        <div class="pt-4 border-top text-center text-md-start">
                            <a href="?page=new" class="btn btn-outline-dark rounded-pill px-4">
                                <i class="bi bi-arrow-left me-2"></i> Quay lại trang tin tức
                            </a>
                        </div>
                    </article>
                </div>
            </div>

            <!-- Khối đề xuất tin tức liên quan -->
            <?php if (!empty($relatedNews)): ?>
                <div class="related-news-section mt-5 pt-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h2 class="h3 fw-bold mb-0 border-start border-4 border-warning ps-3">Các tin tức liên quan</h2>
                        <a href="?page=new" class="text-orange text-decoration-none fw-bold d-none d-sm-inline-block">
                            Xem tất cả <i class="bi bi-chevron-right small"></i>
                        </a>
                    </div>
                    
                    <div class="row g-4">
                        <?php foreach ($relatedNews as $item): ?>
                            <div class="col-md-4">
                                <div class="news-card h-100 d-flex flex-column border-0 rounded-4 overflow-hidden shadow-sm bg-white position-relative">
                                    <div class="position-relative overflow-hidden card-img-container" style="height: 160px;">
                                        <!-- Ảnh đại diện tin liên quan (Dùng cột anh_dai_dien) -->
                                        <img src="<?php echo htmlspecialchars($item['anh_dai_dien'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                                             alt="<?php echo htmlspecialchars($item['tieu_de'], ENT_QUOTES, 'UTF-8'); ?>" 
                                             class="w-100 h-100 object-fit-cover news-image"
                                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1560089000-7433a4ebbd64?q=80&w=600&auto=format&fit=crop';">
                                    </div>
                                    <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                        <!-- Ngày đăng tin liên quan (Dùng cột ngay_tao) -->
                                        <span class="text-muted small mb-2 d-block">
                                            <i class="bi bi-calendar3 me-1 text-warning"></i>
                                            <?php echo date('d/m/Y', strtotime($item['ngay_tao'])); ?>
                                        </span>
                                        <!-- Tiêu đề tin liên quan (Dùng cột duong_dan_slug) -->
                                        <h4 class="card-title h6 mb-3 fw-bold line-clamp-2">
                                            <a href="?page=new-detail&slug=<?php echo $item['duong_dan_slug']; ?>" class="text-dark text-decoration-none hover-orange stretched-link">
                                                <?php echo htmlspecialchars($item['tieu_de'], ENT_QUOTES, 'UTF-8'); ?>
                                            </a>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php endif; ?>

    </div>
</div>
