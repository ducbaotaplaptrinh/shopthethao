<div class="container-xl py-5">
    <div class="breadcrumb-wrapper mb-4">
        <a href="?page=home">Trang chủ ></a>
        <a href="#!" class="text-dark fw-bold">Giỏ hàng của bạn</a>
    </div>

    <h2 class="section-title mb-4">Giỏ Hàng</h2>

    <?php if (isset($_SESSION['cart_warning'])): ?>
        <div class="alert alert-warning alert-dismissible fade show rounded-4 mb-4 shadow-sm border-0" role="alert" style="background-color: #fff3cd; color: #664d03; border-left: 5px solid #ffc107 !important;">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div>
                    <?= $_SESSION['cart_warning'] ?>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['cart_warning']); ?>
    <?php endif; ?>

    <?php if (empty($cartItems)): ?>
        <div class="card text-center py-5 shadow-sm border-0 rounded-4">
            <div class="card-body">
                <div class="mb-4 text-muted">
                    <i class="bi bi-cart-x" style="font-size: 5rem; color: #ff7b00;"></i>
                </div>
                <h4 class="mb-3">Giỏ hàng của bạn đang trống!</h4>
                <p class="text-muted mb-4">Bạn chưa thêm bất kỳ sản phẩm nào vào giỏ hàng.</p>
                <a href="?page=product-index" class="btn btn-primary btn-lg border-0" style="background: linear-gradient(135deg, #ff7b00, #ff9500);">
                    Tiếp tục mua sắm
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <!-- Left: Items list -->
            <div class="col-lg-8">
                <form action="?page=cart-update" method="POST" id="cartForm">
                    <div class="card shadow-sm border-0 rounded-4 p-3 mb-3">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 50px;" class="text-center">
                                            <input type="checkbox" id="selectAllItems" class="form-check-input" checked onchange="toggleSelectAll(this)">
                                        </th>
                                        <th scope="col" style="min-width: 250px;">Sản phẩm</th>
                                        <th scope="col" class="text-center">Đơn giá</th>
                                        <th scope="col" class="text-center" style="width: 120px;">Số lượng</th>
                                        <th scope="col" class="text-end" style="min-width: 120px;">Thành tiền</th>
                                        <th scope="col" class="text-center" style="width: 60px;">Xoá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $key => $item): ?>
                                        <?php
                                        $subtotal = $item['price'] * $item['qty'];
                                        $imgUrl = getProductImage($item['image']);
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" name="selected_items[]" value="<?= htmlspecialchars($key) ?>" checked class="form-check-input cart-item-checkbox" data-price="<?= $item['price'] ?>" data-qty="<?= $item['qty'] ?>" onchange="updateCartSelection()">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="<?= htmlspecialchars($imgUrl) ?>" alt="" style="width: 65px; height: 65px; object-fit: contain; border-radius: 8px; border: 1px solid #eee; padding: 2px;">
                                                    <div style="min-width: 0;">
                                                        <a href="?page=product-detail&slug=<?= htmlspecialchars($item['name']) ?>" class="text-decoration-none text-dark fw-semibold text-truncate d-block" style="max-width: 220px;">
                                                            <?= htmlspecialchars($item['name']) ?>
                                                        </a>
                                                        <?php if (!empty($item['attributes'])): ?>
                                                            <div class="text-muted small mt-1"><?= htmlspecialchars($item['attributes']) ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center fw-medium"><?= htmlspecialchars(formatVND($item['price'])) ?></td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <input type="number" name="qty[<?= htmlspecialchars($key) ?>]" class="form-control form-control-sm text-center fw-semibold qty-input-box" value="<?= htmlspecialchars($item['qty']) ?>" min="1" max="<?= isset($item['so_luong_ton']) ? htmlspecialchars($item['so_luong_ton']) : 100 ?>" style="width: 65px;" onchange="capQuantityAndSubmit(this, <?= isset($item['so_luong_ton']) ? (int)$item['so_luong_ton'] : 100 ?>)">
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold text-danger"><?= htmlspecialchars(formatVND($subtotal)) ?></td>
                                            <td class="text-center">
                                                <a href="?page=cart-delete&key=<?= htmlspecialchars($key) ?>" class="text-muted hover-danger" onclick="return confirm('Bạn có chắc chắn muốn xoá sản phẩm này?')">
                                                    <i class="bi bi-trash-fill fs-5"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="?page=product-index" class="btn btn-outline-dark fw-semibold rounded-3">
                            <i class="bi bi-arrow-left"></i> Tiếp tục mua hàng
                        </a>
                        <button type="submit" class="btn btn-dark fw-semibold rounded-3 d-none">
                            Cập nhật giỏ hàng
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right: Order Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 p-4">
                    <h4 class="mb-4">Tóm tắt đơn hàng</h4>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-semibold" id="cartSubtotal"><?= htmlspecialchars(formatVND($totalPayment)) ?></span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <span class="text-success fw-semibold">Miễn phí</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fs-5 fw-bold">Tổng thanh toán:</span>
                        <span class="fs-4 fw-bold text-danger" id="cartTotal"><?= htmlspecialchars(formatVND($totalPayment)) ?></span>
                    </div>

                    <button type="submit" form="cartForm" formaction="?page=checkout" formmethod="POST" class="btn btn-primary btn-lg w-100 fw-bold border-0 py-3 rounded-3" style="background: linear-gradient(135deg, #ff7b00, #ff9500);">
                        Tiến hành đặt hàng
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Hàm tiếng Việt dễ hiểu để giới hạn số lượng và submit form
function capQuantityAndSubmit(input, maxStock) {
    let val = parseInt(input.value) || 1;
    if (val < 1) {
        input.value = 1;
    } else if (val > maxStock) {
        alert("Số lượng sản phẩm trong giỏ hàng vượt quá tồn kho thực tế! Hệ thống đã điều chỉnh về tối đa là " + maxStock + " sản phẩm.");
        input.value = maxStock;
    }
    document.getElementById('cartForm').submit();
}

// Chặn gõ các ký tự không phải số nguyên dương
document.querySelectorAll('.qty-input-box').forEach(function(input) {
    input.addEventListener("keydown", function(e) {
        if ([".", ",", "-", "+", "e", "E"].includes(e.key)) {
            e.preventDefault();
        }
    });
});

// Chọn tất cả hoặc bỏ chọn tất cả sản phẩm
function toggleSelectAll(master) {
    const checkboxes = document.querySelectorAll('.cart-item-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = master.checked;
    });
    updateCartSelection();
}

// Cập nhật tổng số tiền hiển thị dựa trên các sản phẩm được chọn
function updateCartSelection() {
    const checkboxes = document.querySelectorAll('.cart-item-checkbox');
    let total = 0;
    let checkedCount = 0;

    checkboxes.forEach(cb => {
        if (cb.checked) {
            const price = parseFloat(cb.getAttribute('data-price'));
            const qty = parseInt(cb.getAttribute('data-qty'));
            total += price * qty;
            checkedCount++;
        }
    });

    // Cập nhật trạng thái checkbox "Chọn tất cả"
    const selectAllCb = document.getElementById('selectAllItems');
    if (selectAllCb) {
        selectAllCb.checked = (checkedCount === checkboxes.length);
    }

    // Định dạng VND hiển thị
    const formattedTotal = new Intl.NumberFormat('vi-VN', { style: 'decimal' }).format(total) + ' ₫';
    
    document.getElementById('cartSubtotal').textContent = formattedTotal;
    document.getElementById('cartTotal').textContent = formattedTotal;
}
</script>