<?php
if (!isset($featuredProducts)) {
    $featuredProducts = [];
}
?>

<section class="hero">
    <div>
        <span class="eyebrow">Giao diện </span>
        <h1>Xây dựng website bán dụng cụ cầu lông theo từng khối nhỏ</h1>
        <p>
            Đây là trang home đầu tiên để bạn nhìn rõ luồng: router chọn view,
            view được bọc bởi layout, rồi layout ghép header, sidebar, footer.
        </p>

        <div class="hero-actions">
            <a class="btn btn-primary" href="?page=product">Xem sản phẩm</a>
            <a class="btn btn-secondary" href="#">Tìm hiểu thêm</a>
        </div>
    </div>
</section>

<section class="product-grid">
    <?php foreach ($featuredProducts as $product): ?>
        <article class="product-card">
            <span class="product-badge"><?php echo htmlspecialchars($product['tag'], ENT_QUOTES, 'UTF-8'); ?></span>
            <div class="product-image">Ảnh</div>
            <h3><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p class="product-price"><?php echo htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?></p>
            <button type="button">Thêm vào giỏ</button>
        </article>
    <?php endforeach; ?>
</section>