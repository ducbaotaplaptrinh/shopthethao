<?php
// Giao diện danh sách tin tức
// Các biến đã được Controller trích xuất và truyền sang: $newsData, $searchQuery
?>

<div class="news-page-wrapper py-5">
    <div class="container-xl">
        
        <!-- Tiêu đề & Thanh tìm kiếm tin tức -->
        <div class="news-header-section text-center mb-5">
            <span class="section-kicker">📣 BẢN TIN BẢO ĐẠT SPORT</span>
            <h1 class="display-5 fw-bold mt-2 mb-3">Góc Tin Tức & Kinh Nghiệm Thể Thao</h1>
            <p class="text-muted mx-auto mb-4" style="max-width: 600px;">
                Cập nhật kiến thức hữu ích về kỹ thuật chơi cầu lông, hướng dẫn chọn dụng cụ thể thao phù hợp và các tin tức sự kiện nóng hổi.
            </p>
            
            <!-- Thanh tìm kiếm -->
            <div class="search-container mx-auto">
                <form action="" method="GET" class="d-flex position-relative">
                    <input type="hidden" name="page" value="new">
                    <input type="text" 
                           name="search" 
                           id="searchInput" 
                           class="form-control form-control-lg rounded-pill ps-4 pe-5 shadow-sm border-2" 
                           placeholder="Tìm kiếm bài viết theo từ khóa..."
                           value="<?php echo htmlspecialchars($searchQuery ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" class="btn btn-common rounded-pill position-absolute end-0 top-0 bottom-0 h-100 px-4 text-white">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Lưới danh sách các bài viết -->
        <div class="row g-4" id="newsContainer">
            <?php if (!empty($newsData)): ?>
                <?php foreach ($newsData as $news): ?>
                    <div class="col-md-6 col-lg-4 news-card-item">
                        <article class="news-card h-100 d-flex flex-column border-0 rounded-4 overflow-hidden shadow-sm position-relative">
                            <div class="position-relative overflow-hidden card-img-container">
                                <!-- Ảnh đại diện bài viết (Dùng cột anh_dai_dien) -->
                                <img src="<?php echo htmlspecialchars($news['anh_dai_dien'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                                     alt="<?php echo htmlspecialchars($news['tieu_de'], ENT_QUOTES, 'UTF-8'); ?>" 
                                     class="card-img-top news-image"
                                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1560089000-7433a4ebbd64?q=80&w=600&auto=format&fit=crop';">
                                
                                <!-- Nhãn tác giả (Lấy từ tên nguoi_dung.ho_ten qua câu SQL) -->
                                <span class="badge position-absolute top-0 start-0 m-3 rounded-pill bg-dark bg-opacity-75 px-3 py-2">
                                    <i class="bi bi-person-fill me-1 text-warning"></i><?php echo htmlspecialchars($news['tac_gia'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </div>
                            
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <!-- Ngày đăng bài và lượt xem (Dùng cột ngay_tao) -->
                                <div class="d-flex align-items-center justify-content-between text-muted small mb-3">
                                    <span>
                                        <i class="bi bi-calendar3 me-1 text-warning"></i>
                                        <?php echo date('d/m/Y', strtotime($news['ngay_tao'])); ?>
                                    </span>
                                    <span>
                                        <i class="bi bi-eye-fill me-1 text-warning"></i>
                                        <?php echo number_format($news['luot_xem'] ?? 0); ?> lượt xem
                                    </span>
                                </div>
                                
                                <!-- Tiêu đề bài viết (Dùng cột duong_dan_slug làm liên kết) -->
                                <h3 class="card-title h5 mb-3 fw-bold line-clamp-2">
                                    <a href="?page=new-detail&slug=<?php echo $news['duong_dan_slug']; ?>" class="text-dark text-decoration-none hover-orange stretched-link">
                                        <?php echo htmlspecialchars($news['tieu_de'], ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                </h3>
                                
                                <!-- Tóm tắt (Dùng cột tom_tat) -->
                                <p class="card-desc text-muted mb-0 flex-grow-1 line-clamp-3">
                                    <?php echo htmlspecialchars($news['tom_tat'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Thông báo khi không tìm thấy kết quả tìm kiếm hoặc bảng chưa có tin -->
                <div class="col-12 text-center py-5">
                    <div class="empty-state p-5 bg-white rounded-4 shadow-sm border border-light-subtle">
                        <i class="bi bi-file-earmark-x text-muted" style="font-size: 4.5rem;"></i>
                        <p class="text-muted mt-3 fs-5">Chưa có bài viết nào trong cơ sở dữ liệu hoặc không tìm thấy bài phù hợp.</p>
                        <a href="?page=new" class="btn btn-common text-white rounded-pill px-4 mt-2">Xem tất cả bài viết</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Đính kèm tệp JavaScript -->
<script src="assets/js/news.js"></script>