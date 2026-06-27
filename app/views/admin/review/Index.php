<?php
// Giao diện danh sách đánh giá của Admin
?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Quản lý Đánh giá Sản phẩm</h1>
            <p class="text-muted mb-0 small">Xem, lọc, duyệt ẩn hiện hoặc chỉnh sửa/xóa nhận xét đánh giá của người mua hàng.</p>
        </div>
        <a href="?page=admin-review-create" class="btn btn-primary rounded-3 fw-semibold">
            <i class="bi bi-plus-lg me-1"></i>Thêm Đánh Giá Mẫu
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert" style="font-size: 1.3rem;">
            <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert" style="font-size: 1.3rem;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Filter Card -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="page" value="admin-reviews">
                
                <!-- Keyword search -->
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-semibold">Tìm kiếm</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="keyword" class="form-control border-start-0 ps-0" placeholder="Tên khách, email, sản phẩm, nội dung..." value="<?= htmlspecialchars($keyword ?? '') ?>">
                    </div>
                </div>

                <!-- Star Filter -->
                <div class="col-md-3 col-sm-6">
                    <label class="form-label text-muted small fw-semibold">Lọc theo số sao</label>
                    <select name="star" class="form-select">
                        <option value="">-- Tất cả số sao --</option>
                        <?php for ($s = 5; $s >= 1; $s--): ?>
                            <option value="<?= $s ?>" <?= (string)$star === (string)$s ? 'selected' : '' ?>><?= $s ?> sao</option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="col-md-3 col-sm-6">
                    <label class="form-label text-muted small fw-semibold">Lọc trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="1" <?= (string)$status === '1' ? 'selected' : '' ?>>Hiển thị (Active)</option>
                        <option value="0" <?= (string)$status === '0' ? 'selected' : '' ?>>Đang ẩn (Hidden)</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100 rounded-3 fw-semibold">Lọc</button>
                    <a href="?page=admin-reviews" class="btn btn-outline-secondary w-100 rounded-3"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews Table Card -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 1.3rem;">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-3" style="width: 180px;">Khách hàng</th>
                            <th style="width: 200px;">Sản phẩm</th>
                            <th style="width: 110px;">Đánh giá</th>
                            <th>Nội dung bình luận</th>
                            <th style="width: 140px;">Ngày gửi</th>
                            <th style="width: 100px;">Trạng thái</th>
                            <th class="text-end pe-3" style="width: 150px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reviews)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-chat-square-text text-muted fs-1 d-block mb-3"></i>
                                    Không tìm thấy nhận xét đánh giá nào thỏa mãn điều kiện lọc.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reviews as $rv): ?>
                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-warning text-white fw-bold d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px; font-size: 1.1rem; flex-shrink: 0; <?= !empty($rv['reviewer_avatar']) ? "background-image: url('assets/images/" . htmlspecialchars($rv['reviewer_avatar']) . "'); background-size: cover; background-position: center;" : '' ?>">
                                                <?= empty($rv['reviewer_avatar']) ? htmlspecialchars(mb_substr($rv['reviewer_name'], 0, 1)) : '' ?>
                                            </div>
                                            <div style="min-width: 0; line-height: 1.2;">
                                                <div class="fw-bold text-dark text-truncate" title="<?= htmlspecialchars($rv['reviewer_name']) ?>"><?= htmlspecialchars($rv['reviewer_name']) ?></div>
                                                <small class="text-muted text-truncate d-block" style="font-size: 1rem;" title="<?= htmlspecialchars($rv['reviewer_email']) ?>"><?= htmlspecialchars($rv['reviewer_email']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="<?= htmlspecialchars(getProductImage($rv['product_image'])) ?>" alt="" class="rounded-2 border p-1 bg-white" style="width: 38px; height: 38px; object-fit: contain; flex-shrink: 0;">
                                            <div class="text-dark text-truncate fw-semibold" style="max-width: 150px;" title="<?= htmlspecialchars($rv['ten_san_pham']) ?>">
                                                <?= htmlspecialchars($rv['ten_san_pham']) ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="rating-stars text-nowrap">
                                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                                <i class="bi <?= ($s <= $rv['diem_so']) ? 'bi-star-fill text-warning' : 'bi-star text-muted' ?>" style="font-size: 1.1rem;"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-secondary text-wrap" style="max-width: 300px; max-height: 50px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                            <?= nl2br(htmlspecialchars($rv['binh_luan'] ?? '')) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-muted" style="font-size: 1.1rem;">
                                            <?= date('d/m/Y H:i', strtotime($rv['ngay_tao'])) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ((int)$rv['trang_thai'] === 1): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">Hiện</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">Ẩn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex gap-1">
                                            <!-- Toggle Status Button -->
                                            <a href="?page=admin-review-toggle&id=<?= $rv['id'] ?>" 
                                               class="btn btn-sm btn-outline-secondary rounded-3" 
                                               title="<?= (int)$rv['trang_thai'] === 1 ? 'Ẩn đánh giá này' : 'Hiện đánh giá này' ?>">
                                                <i class="bi <?= (int)$rv['trang_thai'] === 1 ? 'bi-eye-slash-fill' : 'bi-eye-fill' ?>"></i>
                                            </a>
                                            <!-- Edit Button -->
                                            <a href="?page=admin-review-edit&id=<?= $rv['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary rounded-3" 
                                               title="Sửa đánh giá">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <!-- Delete Button -->
                                            <a href="?page=admin-review-delete&id=<?= $rv['id'] ?>" 
                                               class="btn btn-sm btn-outline-danger rounded-3" 
                                               title="Xóa đánh giá"
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này không? Hành động này không thể khôi phục.');">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
