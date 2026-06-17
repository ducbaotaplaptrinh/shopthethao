<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">Thêm Sản phẩm mới</h2>
    <a href="?page=admin-products" class="btn btn-outline-secondary px-4">
        <i class="bi bi-arrow-left me-2"></i>Quay lại
    </a>
</div>

<form action="?page=admin-product-store" method="POST" id="productForm" enctype="multipart/form-data">
    <div class="row g-4">
        <!-- Thông tin cơ bản -->
        <div class="col-12 col-xl-8">
            <div class="admin-card">
                <h4 class="admin-card-title mb-4">Thông tin cơ bản</h4>
                
                <div class="mb-3">
                    <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="ten_san_pham" class="form-control" required placeholder="Ví dụ: Vợt cầu lông Yonex Astrox 99 Pro">
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select name="ma_danh_muc" class="form-select" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach($categories as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['ten_danh_muc']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Thương hiệu</label>
                        <select name="ma_thuong_hieu" class="form-select">
                            <option value="">-- Chọn thương hiệu --</option>
                            <?php foreach($brands as $b): ?>
                                <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['ten_thuong_hieu']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giá bán chung (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="gia_ban" class="form-control" required placeholder="Nhập giá bán chung" min="0">
                        <small class="text-muted">Đây là giá áp dụng nếu biến thể không set giá riêng.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số lượng tồn kho (Sản phẩm gốc)</label>
                        <input type="number" name="so_luong_ton" class="form-control" placeholder="Nhập số lượng" min="0" value="0">
                        <small class="text-muted">Dùng cho sản phẩm không có biến thể.</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả sản phẩm</label>
                    <textarea name="mo_ta_chi_tiet" class="form-control" rows="5" placeholder="Nhập mô tả chi tiết..."></textarea>
                </div>
            </div>

            <!-- Variant Generator Section -->
            <div class="admin-card">
                <h4 class="admin-card-title mb-4">Tạo Biến Thể (Variant Generator)</h4>
                <p class="text-muted mb-4">Chọn các thuộc tính bên dưới. Hệ thống sẽ tự động sinh ra danh sách các biến thể tương ứng.</p>

                <div class="mb-4 p-3 bg-light rounded border">
                    <div class="row">
                        <?php foreach($attributes as $attr): ?>
                        <div class="col-md-4 mb-3">
                            <label class="fw-bold mb-2"><?= htmlspecialchars($attr['ten_thuoc_tinh']) ?></label>
                            <div class="d-flex flex-column gap-2" style="max-height: 150px; overflow-y: auto;">
                                <?php foreach($attr['values'] as $val): ?>
                                <div class="form-check">
                                    <input class="form-check-input variant-checkbox" type="checkbox" 
                                           value="<?= $val['id'] ?>" 
                                           data-attr-id="<?= $attr['id'] ?>" 
                                           data-attr-name="<?= htmlspecialchars($attr['ten_thuoc_tinh']) ?>"
                                           data-val-name="<?= htmlspecialchars($val['gia_tri']) ?>"
                                           id="attr_<?= $val['id'] ?>">
                                    <label class="form-check-label" for="attr_<?= $val['id'] ?>">
                                        <?= htmlspecialchars($val['gia_tri']) ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary mt-2" onclick="generateVariants()">
                        <i class="bi bi-magic me-1"></i> Sinh biến thể tự động
                    </button>
                </div>

                <!-- Bảng biến thể sinh tự động -->
                <div class="table-responsive" id="variantTableContainer" style="display: none;">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light" id="variantTableHeader">
                            <!-- JS will inject header here -->
                        </thead>
                        <tbody id="variantTableBody">
                            <!-- JS will inject rows here -->
                        </tbody>
                    </table>
                </div>
                
                <input type="hidden" name="variants" id="variantsJsonPayload">
            </div>
        </div>

        <!-- Trạng thái & Hình ảnh -->
        <div class="col-12 col-xl-4">
            <div class="admin-card">
                <h4 class="admin-card-title mb-3">Trạng thái</h4>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="trang_thai" id="statusSwitch" checked>
                    <label class="form-check-label" for="statusSwitch">Kích hoạt bán</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="la_noi_bat" id="hotSwitch">
                    <label class="form-check-label" for="hotSwitch">Sản phẩm nổi bật</label>
                </div>
                
                <hr class="my-4">
                
                <h4 class="admin-card-title mb-3">Hình ảnh đại diện</h4>
                <input type="file" name="anh_dai_dien" class="form-control mb-3" accept="image/*">
                
                <hr class="my-4">
                
                <h4 class="admin-card-title mb-3">Thư viện ảnh phụ (Nhiều ảnh)</h4>
                <input type="file" name="anh_thu_vien[]" class="form-control mb-1" accept="image/*" multiple>
                <small class="text-muted d-block">Chọn nhiều ảnh mô tả bổ sung cho sản phẩm.</small>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg" onclick="return prepareSubmission()">
                    <i class="bi bi-save me-2"></i>Lưu Sản Phẩm
                </button>
            </div>
        </div>
    </div>
</form>

<script>
let generatedVariants = [];

function generateVariants() {
    const checkboxes = document.querySelectorAll('.variant-checkbox:checked');
    const grouped = {};
    
    // Nhóm các giá trị đã chọn theo thuộc tính
    checkboxes.forEach(cb => {
        const attrId = cb.dataset.attrId;
        const attrName = cb.dataset.attrName;
        const valId = cb.value;
        const valName = cb.dataset.valName;
        
        if (!grouped[attrId]) {
            grouped[attrId] = {
                name: attrName,
                values: []
            };
        }
        grouped[attrId].values.push({ id: valId, name: valName });
    });

    const attrIds = Object.keys(grouped);
    if (attrIds.length === 0) {
        alert("Vui lòng chọn ít nhất 1 thuộc tính để sinh biến thể!");
        document.getElementById('variantTableContainer').style.display = 'none';
        generatedVariants = [];
        return;
    }

    // Tính toán tổ hợp (Cartesian Product)
    const combinations = cartesianProduct(attrIds.map(id => grouped[id].values));
    
    // Render Table
    const thead = document.getElementById('variantTableHeader');
    const tbody = document.getElementById('variantTableBody');
    
    // Build Header
    let theadHtml = '<tr>';
    attrIds.forEach(id => {
        theadHtml += `<th>${grouped[id].name}</th>`;
    });
    theadHtml += `<th>Mã SKU</th><th>Giá riêng (Bỏ trống = Giá chung)</th><th>Số lượng kho <span class="text-danger">*</span></th></tr>`;
    thead.innerHTML = theadHtml;

    // Build Body
    let tbodyHtml = '';
    generatedVariants = [];
    
    combinations.forEach((combo, index) => {
        let rowHtml = '<tr>';
        let attributesObj = {};
        let comboNames = [];
        
        combo.forEach((val, i) => {
            rowHtml += `<td><span class="badge bg-secondary">${val.name}</span></td>`;
            attributesObj[attrIds[i]] = val.id;
            comboNames.push(val.name);
        });
        
        const defaultSku = 'SP-' + Date.now().toString().slice(-4) + '-' + index;
        
        rowHtml += `
            <td><input type="text" class="form-control form-control-sm var-sku" value="${defaultSku}"></td>
            <td><input type="number" class="form-control form-control-sm var-price" placeholder="Theo giá gốc"></td>
            <td><input type="number" class="form-control form-control-sm var-stock" value="10" required></td>
        </tr>`;
        
        tbodyHtml += rowHtml;
        
        generatedVariants.push({
            attributes: attributesObj,
            index: index
        });
    });
    
    tbody.innerHTML = tbodyHtml;
    document.getElementById('variantTableContainer').style.display = 'block';
}

// Hàm tính tích Đề các
function cartesianProduct(arr) {
    return arr.reduce((a, b) => {
        return a.map(x => b.map(y => x.concat([y]))).reduce((c, d) => c.concat(d), []);
    }, [[]]);
}

// Đóng gói dữ liệu trước khi submit form
function prepareSubmission() {
    const tableBody = document.getElementById('variantTableBody');
    if (!tableBody) return true;
    
    const rows = tableBody.querySelectorAll('tr');
    
    if (rows.length > 0 && generatedVariants.length > 0) {
        rows.forEach((row, idx) => {
            generatedVariants[idx].sku = row.querySelector('.var-sku').value;
            generatedVariants[idx].price = row.querySelector('.var-price').value || 0;
            generatedVariants[idx].stock = row.querySelector('.var-stock').value || 0;
        });
        
        document.getElementById('variantsJsonPayload').value = JSON.stringify(generatedVariants);
    }
    
    return true;
}
</script>
