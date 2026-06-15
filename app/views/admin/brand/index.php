<div class="row g-4">
    <!-- Cột thêm mới -->
    <div class="col-12 col-lg-4">
        <div class="admin-card">
            <h4 class="admin-card-title mb-4">Thêm Thương hiệu mới</h4>
            <form action="?page=admin-brand-store" method="POST">
                <div class="mb-3">
                    <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                    <input type="text" name="ten_thuong_hieu" class="form-control" required placeholder="Ví dụ: Yonex, Lining...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả ngắn</label>
                    <textarea name="mo_ta" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-4">
                    <label class="form-label">Logo thương hiệu</label>
                    <div class="border rounded p-3 text-center bg-light" style="border-style: dashed !important; cursor: pointer;">
                        <i class="bi bi-cloud-arrow-up fs-2 text-primary mb-2"></i>
                        <p class="mb-0 text-muted" style="font-size: 0.8rem;">Nhấp để tải logo lên</p>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Lưu Thương Hiệu</button>
            </form>
        </div>
    </div>

    <!-- Cột danh sách -->
    <div class="col-12 col-lg-8">
        <div class="admin-card">
            <h4 class="admin-card-title mb-4">Danh sách Thương hiệu</h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Logo</th>
                            <th>Tên Thương Hiệu</th>
                            <th>Mô tả</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($brands as $b): ?>
                        <tr>
                            <td class="text-muted fw-bold">#<?= $b['id'] ?></td>
                            <td>
                                <div style="width: 50px; height: 30px; border-radius: 4px; background: #f4f6fa; display:flex; align-items:center; justify-content:center;">
                                    <i class="bi bi-image text-muted" style="font-size: 0.8rem;"></i>
                                </div>
                            </td>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($b['ten_thuong_hieu']) ?></td>
                            <td class="text-muted" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars($b['mo_ta'] ?? '') ?>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-light text-primary me-1"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($brands)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Chưa có thương hiệu nào</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
