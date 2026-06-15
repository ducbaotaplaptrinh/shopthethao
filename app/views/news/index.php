<?php
require_once __DIR__ . '/../../controllers/NewsController.php';
$newsController = new NewsController();
$newsData = $newsController->getNewsData();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin Tức Cầu Lông</title>
    <link rel="stylesheet" href="/assets/css/news.css">
</head>

<body>

    <header class="header">
        <div class="container">
            <h1>🏸 Điểm Tin Cầu Lông</h1>
            <p>Cập nhật tin tức nhanh nhất</p>
            <input type="text" id="searchInput" class="search-box" placeholder="Tìm kiếm bài viết...">
        </div>
    </header>

    <main class="container">
        <div class="news-grid" id="newsContainer">
            <?php if (!empty($newsData)): ?>
                <?php foreach ($newsData as $news): ?>
                    <div class="news-card">
                        <a href="<?php echo $news['link']; ?>" target="_blank" class="card-link">
                            <img src="<?php echo $news['image']; ?>" alt="<?php echo $news['title']; ?>" class="card-img">
                            <div class="card-content">
                                <h3 class="card-title"><?php echo $news['title']; ?></h3>
                                <p class="card-desc"><?php echo $news['description']; ?></p>
                                <span class="card-source">Nguồn: <?php echo $news['source']; ?></span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không có dữ liệu tin tức hoặc không thể kết nối đến nguồn báo.</p>
            <?php endif; ?>
        </div>
    </main>

    <script src="/assets/js/news.js"></script>
</body>

</html>