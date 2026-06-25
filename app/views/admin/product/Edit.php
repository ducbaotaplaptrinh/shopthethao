<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">Sửa Sản phẩm</h2>
    <a href="?page=admin-products" class="btn btn-outline-secondary px-4">
        <i class="bi bi-arrow-left me-2"></i>Quay lại
    </a>
</div>

<form action="?page=admin-product-update" method="POST" id="productForm" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $product['id'] ?>">

    <div class="row g-4">
        <!-- Thông tin cơ bản -->
        <div class="col-12 col-xl-8">
            <div class="admin-card">
                <h4 class="admin-card-title mb-4">Thông tin cơ bản</h4>

                <div class="mb-3">
                    <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="ten_san_pham" class="form-control" required value="<?= htmlspecialchars($product['ten_san_pham']) ?>">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select name="ma_danh_muc" class="form-select" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $product['ma_danh_muc'] == $c['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['ten_danh_muc']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Thương hiệu</label>
                        <select name="ma_thuong_hieu" class="form-select">
                            <option value="">-- Chọn thương hiệu --</option>
                            <?php foreach ($brands as $b): ?>
                                <option value="<?= $b['id'] ?>" <?= $product['ma_thuong_hieu'] == $b['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($b['ten_thuong_hieu']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giá bán chung (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="gia_ban" class="form-control" required value="<?= htmlspecialchars($product['gia_ban']) ?>" min="0">
                        <small class="text-muted">Đây là giá áp dụng nếu biến thể không set giá riêng.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số lượng tồn kho (Sản phẩm gốc)</label>
                        <input type="number" name="so_luong_ton" class="form-control" value="<?= htmlspecialchars($product['so_luong_ton']) ?>" min="0">
                        <small class="text-muted">Dùng cho sản phẩm không có biến thể.</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả sản phẩm</label>
                    <textarea name="mo_ta_chi_tiet" class="form-control" rows="5"><?= htmlspecialchars($product['mo_ta_chi_tiet'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Variant Generator Section -->
            <div class="admin-card">
                <h4 class="admin-card-title mb-4">Biến Thể Sản Phẩm</h4>
                <p class="text-muted mb-4">Bạn có thể thêm bớt thuộc tính và sinh lại biến thể. Hệ thống sẽ giữ nguyên ID của các biến thể cũ nếu tổ hợp thuộc tính không đổi.</p>

                <div class="mb-4 p-3 bg-light rounded border">
                    <div class="row">
                        <?php
                        // Thu thập tất cả các ID thuộc tính đang được dùng bởi các biến thể hiện tại để đánh dấu checked
                        $usedAttrValues = [];
                        foreach ($variants as $v) {
                            foreach ($v['attributes_array'] as $valId) {
                                $usedAttrValues[] = $valId;
                            }
                        }
                        $usedAttrValues = array_unique($usedAttrValues);

                        foreach ($attributes as $attr):
                        ?>
                            <div class="col-md-4 mb-3">
                                <label class="fw-bold mb-2"><?= htmlspecialchars($attr['ten_thuoc_tinh']) ?></label>
                                <div class="d-flex flex-column gap-2" style="max-height: 150px; overflow-y: auto;">
                                    <?php foreach ($attr['values'] as $val): ?>
                                        <div class="form-check">
                                            <input class="form-check-input variant-checkbox" type="checkbox"
                                                value="<?= $val['id'] ?>"
                                                data-attr-id="<?= $attr['id'] ?>"
                                                data-attr-name="<?= htmlspecialchars($attr['ten_thuoc_tinh']) ?>"
                                                data-val-name="<?= htmlspecialchars($val['gia_tri']) ?>"
                                                id="attr_<?= $val['id'] ?>"
                                                <?= in_array($val['id'], $usedAttrValues) ? 'checked' : '' ?>>
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

                <!-- Bảng biến thể -->
                <div class="table-responsive" id="variantTableContainer">
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
                    <input class="form-check-input" type="checkbox" name="trang_thai" id="statusSwitch" <?= $product['trang_thai'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="statusSwitch">Kích hoạt bán</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="la_noi_bat" id="hotSwitch" <?= $product['la_noi_bat'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="hotSwitch">Sản phẩm nổi bật</label>
                </div>

                <hr class="my-4">

                <h4 class="admin-card-title mb-3">Hình ảnh đại diện</h4>
                <?php if (!empty($product['anh_dai_dien'])): ?>
                    <div class="mb-3 position-relative" style="max-width: 150px;">
                        <img src="<?= getProductImage("assets/images/products/" . $product['anh_dai_dien']) ?>" class="img-thumbnail rounded w-100" alt="Avatar">
                        <span class="badge bg-dark position-absolute bottom-0 end-0 m-1" style="font-size:0.65rem;">Hiện tại</span>
                    </div>
                <?php endif; ?>
                <input type="file" name="anh_dai_dien" class="form-control mb-3" accept="image/*">

                <hr class="my-4">

                <h4 class="admin-card-title mb-3">Thư viện ảnh phụ</h4>
                <?php if (!empty($gallery)): ?>
                    <div class="row g-2 mb-3">
                        <?php foreach ($gallery as $img): ?>
                            <div class="col-4 text-center">
                                <div class="position-relative border rounded p-1 bg-white">
                                    <img src="<?= getProductImage("assets/images/products/" . $img['duong_dan_anh']) ?>" class="img-fluid rounded" style="height: 60px; object-fit: contain;" alt="Gallery">
                                    <div class="form-check mt-1 d-flex justify-content-center">
                                        <input class="form-check-input border-danger" type="checkbox" name="xoa_anh_phu[]" value="<?= $img['id'] ?>" id="del_img_<?= $img['id'] ?>">
                                        <label class="form-check-label text-danger small ms-1" style="font-size:0.75rem;" for="del_img_<?= $img['id'] ?>">Xóa</label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <input type="file" name="anh_thu_vien[]" class="form-control mb-1" accept="image/*" multiple>
                <small class="text-muted d-block">Chọn nhiều ảnh để tải thêm vào thư viện.</small>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-warning btn-lg text-white" onclick="return prepareSubmission()">
                    <i class="bi bi-save me-2"></i>Cập Nhật
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    // Load existing variants from PHP
    const existingVariantsFromDb = <?= json_encode($variants) ?>;

    // Dictionary map to quickly find existing variants by their attribute combination key
    let existingVariantsMap = {};

    existingVariantsFromDb.forEach(v => {
        // Sắp xếp mảng ID thuộc tính để tạo key duy nhất (vd: "1_5" giống "5_1")
        let attrKey = v.attributes_array.slice().sort().join('_');
        existingVariantsMap[attrKey] = {
            id: v.id,
            sku: v.ma_vach_sku,
            price: v.gia_ban_rieng,
            stock: v.so_luong_ton
        };
    });

    let generatedVariants = [];

    function cartesianProduct(arr) {
        return arr.reduce((a, b) => {
            return a.map(x => b.map(y => x.concat([y]))).reduce((c, d) => c.concat(d), []);
        }, [
            []
        ]);
    }

    function generateVariants() {
        const checkboxes = document.querySelectorAll('.variant-checkbox:checked');
        const grouped = {};

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
            grouped[attrId].values.push({
                id: valId,
                name: valName
            });
        });

        const attrIds = Object.keys(grouped);
        if (attrIds.length === 0) {
            document.getElementById('variantTableContainer').style.display = 'none';
            generatedVariants = [];
            return;
        }

        const combinations = cartesianProduct(attrIds.map(id => grouped[id].values));

        const thead = document.getElementById('variantTableHeader');
        const tbody = document.getElementById('variantTableBody');

        let theadHtml = '<tr>';
        attrIds.forEach(id => {
            theadHtml += `<th>${grouped[id].name}</th>`;
        });
        theadHtml += `<th>Mã SKU</th><th>Giá riêng</th><th>Kho <span class="text-danger">*</span></th></tr>`;
        thead.innerHTML = theadHtml;

        let tbodyHtml = '';
        generatedVariants = [];

        combinations.forEach((combo, index) => {
            let rowHtml = '<tr>';
            let attributesObj = {};
            let attrValuesArr = [];

            combo.forEach((val, i) => {
                rowHtml += `<td><span class="badge bg-secondary">${val.name}</span></td>`;
                attributesObj[attrIds[i]] = val.id;
                attrValuesArr.push(val.id);
            });

            // Tạo key tổ hợp hiện tại để đối chiếu
            let currentAttrKey = attrValuesArr.sort().join('_');

            // Check xem tổ hợp này đã tồn tại trong DB chưa
            let existingVar = existingVariantsMap[currentAttrKey];

            let varId = existingVar ? existingVar.id : '';
            let varSku = existingVar ? existingVar.sku : ('SP-' + Date.now().toString().slice(-4) + '-' + index);
            let varPrice = existingVar && existingVar.price ? existingVar.price : '';
            let varStock = existingVar ? existingVar.stock : 10;

            rowHtml += `
            <input type="hidden" class="var-id" value="${varId}">
            <td><input type="text" class="form-control form-control-sm var-sku" value="${varSku}"></td>
            <td><input type="number" class="form-control form-control-sm var-price" placeholder="Theo giá gốc" value="${varPrice}"></td>
            <td><input type="number" class="form-control form-control-sm var-stock" value="${varStock}" required></td>
        </tr>`;

            tbodyHtml += rowHtml;

            generatedVariants.push({
                id: varId,
                attributes: attributesObj,
                index: index
            });
        });

        tbody.innerHTML = tbodyHtml;
        document.getElementById('variantTableContainer').style.display = 'block';
    }

    function prepareSubmission() {
        const tableBody = document.getElementById('variantTableBody');
        if (!tableBody) return true;

        const rows = tableBody.querySelectorAll('tr');

        if (rows.length > 0 && generatedVariants.length > 0) {
            rows.forEach((row, idx) => {
                generatedVariants[idx].id = row.querySelector('.var-id').value;
                generatedVariants[idx].sku = row.querySelector('.var-sku').value;
                generatedVariants[idx].price = row.querySelector('.var-price').value || 0;
                generatedVariants[idx].stock = row.querySelector('.var-stock').value || 0;
            });

            document.getElementById('variantsJsonPayload').value = JSON.stringify(generatedVariants);
        }

        return true;
    }

    // Tự động sinh bảng lần đầu nếu có dữ liệu
    document.addEventListener("DOMContentLoaded", function() {
        if (existingVariantsFromDb.length > 0) {
            generateVariants();
        }
    });
</script>