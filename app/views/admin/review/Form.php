<?php
// Form Thêm / Sửa đánh giá sản phẩm của Admin
$formTitle = $isEdit ? 'Chỉnh sửa Đánh giá #' . $review['id'] : 'Thêm Đánh giá Mẫu';
$actionUrl = $isEdit ? '?page=admin-review-edit&id=' . $review['id'] : '?page=admin-review-create';
?>
<div class="container-fluid py-4">
    <!-- Breadcrumb & Title -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="?page=admin-dashboard" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="?page=admin-reviews" class="text-decoration-none">Quản lý Đánh giá</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($formTitle) ?></li>
            </ol>
        </nav>
        <h1 class="h3 mb-0 text-gray-800 fw-bold"><?= htmlspecialchars($formTitle) ?></h1>
    </div>

    <!-- Error Alerts -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger rounded-3 mb-4" role="alert" style="font-size: 1.3rem;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="card shadow-sm border-0 rounded-3" style="max-width: 700px;">
        <div class="card-body p-4">
            <form action="<?= $actionUrl ?>" method="POST" style="font-size: 1.3rem;">
                
                <?php if ($isEdit): ?>
                    <!-- Edit Mode: Read-only info of Product and Customer -->
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Sản phẩm đánh giá</label>
                        <div class="d-flex align-items-center gap-3 p-2 border rounded-3 bg-light">
                            <img src="<?= htmlspecialchars(getProductImage($review['product_image'])) ?>" alt="" class="rounded border bg-white p-1" style="width: 50px; height: 50px; object-fit: contain;">
                            <div class="fw-semibold text-dark"><?= htmlspecialchars($review['ten_san_pham']) ?></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Khách hàng đánh giá</label>
                        <div class="p-2 border rounded-3 bg-light text-dark">
                            <strong><?= htmlspecialchars($review['reviewer_name']) ?></strong> 
                            <span class="text-muted small ms-2">(<?= htmlspecialchars($review['reviewer_email']) ?>)</span>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Create Mode: Product and Customer selectors -->
                    <div class="mb-3">
                        <label for="ma_san_pham" class="form-label text-muted small fw-bold">Chọn sản phẩm (*)</label>
                        <select name="ma_san_pham" id="ma_san_pham" class="form-select" required>
                            <option value="">-- Chọn sản phẩm --</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['ten_san_pham']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="ma_nguoi_dung" class="form-label text-muted small fw-bold">Chọn khách hàng (*)</label>
                        <select name="ma_nguoi_dung" id="ma_nguoi_dung" class="form-select" required>
                            <option value="">-- Chọn khách hàng --</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['ho_ten']) ?> (<?= htmlspecialchars($u['email']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <!-- Rating score -->
                <div class="mb-3">
                    <label for="diem_so" class="form-label text-muted small fw-bold">Số sao đánh giá (*)</label>
                    <select name="diem_so" id="diem_so" class="form-select" required>
                        <?php
                        $selectedStars = $isEdit ? (int)$review['diem_so'] : 5;
                        for ($s = 5; $s >= 1; $s--):
                        ?>
                            <option value="<?= $s ?>" <?= $selectedStars === $s ? 'selected' : '' ?>><?= $s ?> sao (<?= str_repeat('★', $s) ?>)</option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Comment comment -->
                <div class="mb-3">
                    <label for="binh_luan" class="form-label text-muted small fw-bold">Nội dung bình luận nhận xét</label>
                    <textarea name="binh_luan" id="binh_luan" class="form-control rounded-3" rows="4" placeholder="Nhập bình luận nhận xét về sản phẩm..." required><?= htmlspecialchars($isEdit ? ($review['binh_luan'] ?? '') : '') ?></textarea>
                </div>

                <?php if ($isEdit): ?>
                    <!-- Edit Mode: Status -->
                    <div class="mb-4">
                        <label for="trang_thai" class="form-label text-muted small fw-bold">Trạng thái hiển thị</label>
                        <select name="trang_thai" id="trang_thai" class="form-select">
                            <option value="1" <?= (int)$review['trang_thai'] === 1 ? 'selected' : '' ?>>Hiển thị (Active)</option>
                            <option value="0" <?= (int)$review['trang_thai'] === 0 ? 'selected' : '' ?>>Ẩn (Hidden)</option>
                        </select>
                    </div>
                <?php endif; ?>

                <!-- Action buttons -->
                <div class="d-flex gap-2">
                    <a href="?page=admin-reviews" class="btn btn-outline-secondary rounded-3 px-4 fw-semibold">Hủy bỏ</a>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 text-white fw-semibold">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
