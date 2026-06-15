<div class="row g-4">
    <!-- Cột thêm mới -->
    <div class="col-12 col-lg-4">
        <div class="admin-card">
            <h4 class="admin-card-title mb-4">Thêm Danh mục mới</h4>
            <form action="?page=admin-category-store" method="POST">
                <div class="mb-3">
                    <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" name="ten_danh_muc" id="catName" class="form-control" required onkeyup="generateSlug()">
                </div>
                <div class="mb-3">
                    <label class="form-label">Đường dẫn (Slug) <span class="text-danger">*</span></label>
                    <input type="text" name="duong_dan" id="catSlug" class="form-control" required readonly bg-light>
                </div>
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="trang_thai" id="catStatus" checked>
                    <label class="form-check-label" for="catStatus">Hiển thị</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Lưu Danh Mục</button>
            </form>
        </div>
    </div>

    <!-- Cột danh sách -->
    <div class="col-12 col-lg-8">
        <div class="admin-card">
            <h4 class="admin-card-title mb-4">Danh sách Danh mục</h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tên Danh Mục</th>
                            <th>Đường dẫn</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $c): ?>
                        <tr>
                            <td class="text-muted fw-bold">#<?= $c['id'] ?></td>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($c['ten_danh_muc']) ?></td>
                            <td class="text-muted">/<?= htmlspecialchars($c['duong_dan']) ?></td>
                            <td>
                                <?php if ($c['trang_thai'] == 1): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Hiện</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">Ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-light text-primary me-1"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function generateSlug() {
    let title = document.getElementById('catName').value;
    // Chuyển tiếng Việt không dấu, loại bỏ ký tự đặc biệt
    let slug = title.toLowerCase();
    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
    slug = slug.replace(/đ/gi, 'd');
    slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
    slug = slug.replace(/ /gi, "-");
    slug = slug.replace(/\-\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-/gi, '-');
    slug = slug.replace(/\-\-/gi, '-');
    slug = '@' + slug + '@';
    slug = slug.replace(/\@\-|\-\@|\@/gi, '');
    
    document.getElementById('catSlug').value = slug;
}
</script>
