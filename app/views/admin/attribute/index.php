<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">Quản lý Thuộc tính</h2>
</div>

<div class="row g-4">
    <!-- Cột thêm Nhóm thuộc tính -->
    <div class="col-12 col-xl-4">
        <div class="admin-card mb-4">
            <h4 class="admin-card-title mb-4">Thêm Nhóm thuộc tính</h4>
            <form action="?page=admin-attribute-store-group" method="POST">
                <div class="mb-3">
                    <label class="form-label">Tên nhóm (VD: Kích cỡ, Màu sắc) <span class="text-danger">*</span></label>
                    <input type="text" name="ten_thuoc_tinh" class="form-control" required>
                </div>
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="la_bien_the" id="attrVariant" checked>
                    <label class="form-check-label" for="attrVariant">Dùng làm biến thể (ảnh hưởng đến giá/kho)</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Thêm Nhóm</button>
            </form>
        </div>

        <div class="admin-card">
            <h4 class="admin-card-title mb-4">Thêm Giá trị thuộc tính</h4>
            <form action="?page=admin-attribute-store-value" method="POST">
                <div class="mb-3">
                    <label class="form-label">Chọn Nhóm <span class="text-danger">*</span></label>
                    <select name="id_thuoc_tinh" class="form-select" required>
                        <option value="">-- Chọn nhóm --</option>
                        <?php foreach($attributes as $a): ?>
                            <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['ten_thuoc_tinh']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label">Giá trị (VD: 39, XL, Đỏ) <span class="text-danger">*</span></label>
                    <input type="text" name="gia_tri" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-outline-primary w-100">Thêm Giá Trị</button>
            </form>
        </div>
    </div>

    <!-- Cột danh sách Nhóm & Giá trị -->
    <div class="col-12 col-xl-8">
        <div class="row g-4">
            <?php foreach($attributes as $attr): ?>
            <div class="col-12 col-md-6">
                <div class="admin-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($attr['ten_thuoc_tinh']) ?></h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-variant" type="checkbox" 
                                   data-id="<?= $attr['id'] ?>" 
                                   <?= $attr['la_bien_the'] ? 'checked' : '' ?>
                                   title="Bật/tắt trạng thái Biến thể">
                        </div>
                    </div>
                    
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <?php foreach($attr['values'] as $val): ?>
                            <span class="badge bg-light text-dark border px-3 py-2">
                                <?= htmlspecialchars($val['gia_tri']) ?>
                                <a href="#" class="text-danger ms-2 text-decoration-none"><i class="bi bi-x"></i></a>
                            </span>
                        <?php endforeach; ?>
                        <?php if(empty($attr['values'])): ?>
                            <span class="text-muted" style="font-size: 13px;">Chưa có giá trị nào</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="text-end">
                        <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i> Xóa nhóm</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if(empty($attributes)): ?>
                <div class="col-12">
                    <div class="admin-card text-center py-5 text-muted">
                        Chưa có nhóm thuộc tính nào được tạo
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('.toggle-variant');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;
            const status = this.checked ? 1 : 0;
            
            fetch('?page=admin-attribute-toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id, status: status })
            })
            .then(response => response.json())
            .then(data => {
                if(!data.success) {
                    alert('Lỗi cập nhật trạng thái!');
                    this.checked = !this.checked;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Lỗi kết nối!');
                this.checked = !this.checked;
            });
        });
    });
});
</script>
