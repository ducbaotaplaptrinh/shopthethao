<div class="container-fluid mt-4">
    <h2 class="mb-4">Thêm Bài Viết Mới</h2>

    <form action="/admin/news/store" method="POST" enctype="multipart/form-data">

        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tiêu đề bài viết</label>
                    <input type="text" name="tieu_de" class="form-control" required placeholder="Nhập tiêu đề...">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tóm tắt (Hiển thị ở trang danh sách)</label>
                    <textarea name="tom_tat" class="form-control" rows="3" required
                        placeholder="Nhập tóm tắt ngắn..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nội dung bài viết</label>
                    <textarea name="noi_dung" id="editor" class="form-control"></textarea>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Trạng thái</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="trang_thai" id="trang_thai" checked>
                        <label class="form-check-label" for="trang_thai">Xuất bản ngay</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Ảnh đại diện (Thumbnail)</label>
                    <input type="file" name="anh_dai_dien" class="form-control" accept="image/*" required>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save"></i> Lưu Bài Viết</button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                editor.editing.view.change(writer => {
                    writer.setStyle('min-height', '400px', editor.editing.view.document.getRoot());
                });
            })
            .catch(error => {
                console.error('Lỗi khởi tạo CKEditor:', error);
            });
    });
</script>