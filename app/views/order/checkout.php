<div class="container-xl py-5">
    <div class="breadcrumb-wrapper mb-4">
        <a href="?page=home">Trang chủ ></a>
        <a href="?page=cart">Giỏ hàng ></a>
        <a href="#!" class="text-dark fw-bold">Thanh toán đơn hàng</a>
    </div>

    <h2 class="section-title mb-4">Thanh Toán</h2>

    <?php
    if (isset($_SESSION['order_error'])) {
        echo '<div class="alert alert-danger rounded-3">' . htmlspecialchars($_SESSION['order_error']) . '</div>';
        unset($_SESSION['order_error']);
    }
    ?>

    <div class="row g-4">
        <!-- Left: Checkout Form -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <h4 class="mb-4">Thông tin giao hàng</h4>

                <form action="?page=order-place" method="POST" id="checkoutForm">
                    <?php
                    $user = $_SESSION['user'] ?? null;
                    ?>
                    <div class="mb-3">
                        <label for="ho_ten" class="form-label fw-semibold">Họ và tên người nhận <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" id="ho_ten" name="ho_ten" required placeholder="Nhập họ tên đầy đủ" value="<?= $user ? htmlspecialchars($user['ho_ten']) : '' ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="so_dien_thoai" class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control rounded-3" id="so_dien_thoai" name="so_dien_thoai" required placeholder="Nhập số điện thoại nhận hàng" value="<?= $user ? htmlspecialchars($user['so_dien_thoai'] ?? '') : '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold">Địa chỉ Email</label>
                            <input type="email" class="form-control rounded-3" id="email" name="email" placeholder="Nhập email (không bắt buộc)" value="<?= $user ? htmlspecialchars($user['email']) : '' ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="dia_chi" class="form-label fw-semibold">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                        <textarea class="form-control rounded-3" id="dia_chi" name="dia_chi" rows="3" required placeholder="Số nhà, tên đường, xã/phường, quận/huyện, tỉnh/thành phố"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="ghi_chu" class="form-label fw-semibold">Ghi chú đơn hàng</label>
                        <textarea class="form-control rounded-3" id="ghi_chu" name="ghi_chu" rows="2" placeholder="Ghi chú về thời gian giao hàng, chỉ dẫn địa chỉ..."></textarea>
                    </div>

                    <h4 class="mb-3">Phương thức thanh toán</h4>
                    <div class="card bg-light border-0 rounded-3 p-3 mb-4">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="phuong_thuc_thanh_toan" id="payment_cod" value="cod" checked>
                            <label class="form-check-label fw-semibold text-dark" for="payment_cod">
                                <i class="bi bi-cash-stack text-success me-2"></i>Thanh toán khi nhận hàng (COD)
                            </label>
                            <div class="text-muted small ms-4 mt-1">Khách hàng kiểm tra hàng và thanh toán tiền mặt trực tiếp cho nhân viên giao hàng.</div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="phuong_thuc_thanh_toan" id="payment_bank" value="chuyen_khoan">
                            <label class="form-check-label fw-semibold text-dark" for="payment_bank">
                                <i class="bi bi-bank text-primary me-2"></i>Chuyển khoản ngân hàng (Qua mã QR)
                            </label>
                            <div class="text-muted small ms-4 mt-1">Hệ thống sẽ hiển thị mã QR kèm thông tin số tài khoản ở bước sau để chuyển khoản.</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold border-0 py-3 rounded-3" style="background: linear-gradient(135deg, #ff7b00, #ff9500);">
                        Xác nhận đặt hàng
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Order Summary -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <h4 class="mb-4">Đơn hàng của bạn</h4>

                <div class="checkout-items-list mb-3" style="max-height: 280px; overflow-y: auto;">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                            <img src="<?= htmlspecialchars(getProductImage($item['image'])) ?>" alt="" style="width: 55px; height: 55px; object-fit: contain; border-radius: 8px; border: 1px solid #eee; padding: 2px;">
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="text-truncate fw-semibold text-dark small"><?= htmlspecialchars($item['name']) ?></div>
                                <?php if (!empty($item['attributes'])): ?>
                                    <div class="text-muted small" style="font-size: 11px;"><?= htmlspecialchars($item['attributes']) ?></div>
                                <?php endif; ?>
                                <div class="small text-muted mt-1">Số lượng: <?= htmlspecialchars($item['qty']) ?></div>
                            </div>
                            <div class="fw-bold text-end text-dark" style="min-width: 90px;"><?= htmlspecialchars(formatVND($item['price'] * $item['qty'])) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Tạm tính:</span>
                    <span class="fw-semibold text-dark"><?= htmlspecialchars(formatVND($totalPayment)) ?></span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Phí giao hàng:</span>
                    <span class="text-success fw-semibold">Miễn phí</span>
                </div>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center mb-0">
                    <span class="fs-5 fw-bold">Tổng thanh toán:</span>
                    <span class="fs-4 fw-bold text-danger"><?= htmlspecialchars(formatVND($totalPayment)) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>