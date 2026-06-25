<?php
$isEdit = $isEdit ?? false;
$actionUrl = $isEdit ? '?page=admin-banner-update' : '?page=admin-banner-store';
$banner = $banner ?? null;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0"><?= $isEdit ? 'Chỉnh Sửa Banner' : 'Thêm Banner Mới' ?></h2>
    <a href="?page=admin-banners" class="btn btn-outline-secondary px-4">
        <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="admin-card p-4">
            <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($banner['id']) ?>">
                <?php endif; ?>

                <!-- Tiêu đề -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tiêu đề Banner</label>
                    <input type="text" name="tieu_de" class="form-control" 
                           placeholder="Nhập tiêu đề hoặc tên gọi cho banner..." 
                           value="<?= htmlspecialchars($banner['tieu_de'] ?? '') ?>">
                </div>

                <!-- Đường dẫn liên kết -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Đường dẫn liên kết (Link URL)</label>
                    <input type="url" name="duong_dan_lien_ket" class="form-control" 
                           placeholder="Ví dụ: ?page=flash-sale hoặc link đầy đủ..." 
                           value="<?= htmlspecialchars($banner['duong_dan_lien_ket'] ?? '') ?>">
                    <div class="form-text text-muted">Liên kết sẽ được kích hoạt khi khách hàng click vào banner.</div>
                </div>

                <!-- Vị trí hiển thị -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Vị trí hiển thị <span class="text-danger">*</span></label>
                    <select name="vi_tri_hien_thi" class="form-select" required>
                        <option value="slide_chinh" <?= ($banner['vi_tri_hien_thi'] ?? 'slide_chinh') === 'slide_chinh' ? 'selected' : '' ?>>
                            Slide chính (Home Slider)
                        </option>
                    </select>
                </div>

                <!-- Tải ảnh lên -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Hình ảnh Banner <span class="text-danger">*</span></label>
                    <input type="file" name="duong_dan_anh" id="bannerImageInput" class="form-control mb-2" 
                           accept="image/*" <?= $isEdit ? '' : 'required' ?>>
                    <div class="form-text text-muted">Chấp nhận JPG, JPEG, PNG, WEBP, GIF. Kích thước đề xuất: 1920x600px cho banner slider rộng.</div>
                    
                    <!-- Preview ảnh -->
                    <div class="mt-3 text-center border rounded p-3 bg-light" style="min-height: 150px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <span class="text-muted d-block mb-2 small fw-bold">Xem trước hình ảnh:</span>
                        <img id="imagePreview" 
                             src="<?= $isEdit && !empty($banner['duong_dan_anh']) ? htmlspecialchars($banner['duong_dan_anh']) : '' ?>" 
                             alt="Xem trước ảnh banner" 
                             class="img-fluid rounded" 
                             style="max-height: 180px; <?= $isEdit && !empty($banner['duong_dan_anh']) ? '' : 'display: none;' ?>">
                        <div id="previewPlaceholder" style="<?= $isEdit && !empty($banner['duong_dan_anh']) ? 'display: none;' : '' ?>">
                            <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                            <p class="mb-0 text-muted small">Chưa chọn ảnh nào</p>
                        </div>
                    </div>
                </div>

                <!-- Trạng thái -->
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" role="switch" name="trang_thai" id="trangThaiSwitch" 
                           value="1" <?= ($banner['trang_thai'] ?? 1) == 1 ? 'checked' : '' ?>>
                    <label class="form-check-label fw-semibold" for="trangThaiSwitch">Kích hoạt hiển thị ngay</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill py-2">
                        <i class="bi bi-check-lg me-1"></i> Lưu Banner
                    </button>
                    <a href="?page=admin-banners" class="btn btn-outline-secondary px-4 py-2">Hủy bỏ</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preview Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bannerImageInput = document.getElementById('bannerImageInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewPlaceholder = document.getElementById('previewPlaceholder');

    if (bannerImageInput) {
        bannerImageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    previewPlaceholder.style.display = 'none';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '';
                imagePreview.style.display = 'none';
                previewPlaceholder.style.display = 'block';
            }
        });
    }
});
</script>
