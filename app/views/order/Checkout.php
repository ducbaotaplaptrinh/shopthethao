<div class="container-xl py-5 checkout-page">
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

                    <?php if (!empty($addresses)): ?>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary mb-2"><i class="bi bi-geo-alt me-1 text-primary"></i>Chọn địa chỉ giao hàng đã lưu</label>
                            <div class="row g-2">
                                <?php foreach ($addresses as $addr): 
                                    $fullAddress = $addr['dia_chi_chi_tiet'] . ', ' . $addr['phuong_xa'] . ', ' . $addr['quan_huyen'] . ', ' . $addr['tinh_thanh_pho'];
                                ?>
                                    <div class="col-12">
                                        <div class="card p-3 border rounded-3 address-select-card <?= $addr['la_mac_dinh'] ? 'border-primary bg-primary-subtle' : '' ?>" 
                                             style="cursor: pointer; transition: all 0.2s;"
                                             data-name="<?= htmlspecialchars($addr['ho_ten_nguoi_nhan']) ?>"
                                             data-phone="<?= htmlspecialchars($addr['so_dien_thoai']) ?>"
                                             data-address="<?= htmlspecialchars($fullAddress) ?>">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="fw-bold text-dark"><?= htmlspecialchars($addr['ho_ten_nguoi_nhan']) ?></span>
                                                    <span class="text-muted ms-2">(<?= htmlspecialchars($addr['so_dien_thoai']) ?>)</span>
                                                    <div class="text-muted mt-1"><?= htmlspecialchars($fullAddress) ?></div>
                                                </div>
                                                <?php if ($addr['la_mac_dinh']): ?>
                                                    <span class="badge bg-primary">Mặc định</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

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
                            <div class="text-muted ms-4 mt-1">Khách hàng kiểm tra hàng và thanh toán tiền mặt trực tiếp cho nhân viên giao hàng.</div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="phuong_thuc_thanh_toan" id="payment_bank" value="chuyen_khoan">
                            <label class="form-check-label fw-semibold text-dark" for="payment_bank">
                                <i class="bi bi-bank text-primary me-2"></i>Chuyển khoản ngân hàng (Qua mã QR)
                            </label>
                            <div class="text-muted ms-4 mt-1">Hệ thống sẽ hiển thị mã QR kèm thông tin số tài khoản ở bước sau để chuyển khoản.</div>
                        </div>
                    </div>

                    <input type="hidden" name="ma_code_su_dung" id="input-coupon-code" value="">
                    
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
                    <?php foreach ($cartItems as $item):
                        // var_dump($cartItems);
                        // die();
                    ?>

                        <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                            <img src="<?= htmlspecialchars(getProductImage($item['image'])) ?>" alt="" style="width: 55px; height: 55px; object-fit: contain; border-radius: 8px; border: 1px solid #eee; padding: 2px;">
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="text-truncate fw-semibold text-dark"><?= htmlspecialchars($item['name']) ?></div>
                                <?php if (!empty($item['attributes'])): ?>
                                    <div class="text-muted"><?= htmlspecialchars($item['attributes']) ?></div>
                                <?php endif; ?>
                                <div class="text-muted mt-1">Số lượng: <?= htmlspecialchars($item['qty']) ?></div>
                            </div>
                            <div class="fw-bold text-end text-dark" style="min-width: 90px;"><?= htmlspecialchars(formatVND($item['price'] * $item['qty'])) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="card mb-3 border-info">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-info"><i class="fas fa-ticket-alt"></i> Mã Giảm Giá</h6>
                            <?php if (!empty($availableCoupons)): ?>
                                <small class="text-success">Bạn có <strong><?= count($availableCoupons) ?></strong> mã khả dụng</small>
                            <?php else: ?>
                                <small class="text-muted">Không có mã nào khả dụng cho đơn hàng này.</small>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($availableCoupons)): ?>
                            <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#couponModal">
                                Chọn Mã
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Tạm tính</span>
                        <strong><?= number_format($totalPayment) ?>đ</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Phí giao hàng</span>
                        <strong class="text-success">Miễn phí</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between text-success">
                        <span>Giảm giá (<span id="applied-coupon-code">Chưa áp dụng</span>)</span>
                        <strong>- <span id="discount-amount">0</span>đ</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <span>Tổng cộng</span>
                        <strong class="text-danger fs-5"><span id="final-total"><?= number_format($totalPayment) ?></span>đ</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chọn Mã Giảm Giá -->
<div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="couponModalLabel">Chọn Mã Giảm Giá</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (!empty($availableCoupons)): foreach ($availableCoupons as $coupon): ?>
                    <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-primary mb-1"><?= htmlspecialchars($coupon['ma_code']) ?></h6>
                            <small><?= htmlspecialchars($coupon['tieu_de']) ?></small><br>
                            <small class="text-danger">Giảm <?= number_format($coupon['gia_tri_giam']) ?>đ</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary apply-coupon-btn" 
                                data-code="<?= htmlspecialchars($coupon['ma_code']) ?>" 
                                data-discount="<?= htmlspecialchars($coupon['gia_tri_giam']) ?>" 
                                data-type="<?= htmlspecialchars($coupon['loai_giam_gia']) ?>"
                                data-bs-dismiss="modal">
                            Áp dụng
                        </button>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Logic Xử Lý Chọn Địa Chỉ ---
    const addressCards = document.querySelectorAll('.address-select-card');
    const inputHoTen = document.getElementById('ho_ten');
    const inputSDT = document.getElementById('so_dien_thoai');
    const textareaDiaChi = document.getElementById('dia_chi');

    addressCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove active style from all cards
            addressCards.forEach(c => {
                c.classList.remove('border-primary', 'bg-primary-subtle');
                c.style.borderColor = '#ececec';
            });
            // Add active style to selected card
            this.classList.add('border-primary', 'bg-primary-subtle');
            this.style.borderColor = '#ff7b00';

            // Fill inputs
            inputHoTen.value = this.getAttribute('data-name');
            inputSDT.value = this.getAttribute('data-phone');
            textareaDiaChi.value = this.getAttribute('data-address');
        });
    });

    // Auto-fill default address on load
    const defaultCard = document.querySelector('.address-select-card.border-primary');
    if (defaultCard) {
        inputHoTen.value = defaultCard.getAttribute('data-name');
        inputSDT.value = defaultCard.getAttribute('data-phone');
        textareaDiaChi.value = defaultCard.getAttribute('data-address');
    }

    // --- Logic Xử Lý Voucher ---
    // Nhận dữ liệu từ PHP
    const baseTotal = <?= $totalPayment ?>;
    
    // JS object của mã ngon nhất (nếu có)
    let currentCoupon = <?= $bestCoupon ? json_encode([
        'code' => $bestCoupon['ma_code'],
        'discount' => $bestCoupon['gia_tri_giam'],
        'type' => $bestCoupon['loai_giam_gia']
    ]) : 'null' ?>;

    const discountEl = document.getElementById('discount-amount');
    const finalTotalEl = document.getElementById('final-total');
    const couponCodeEl = document.getElementById('applied-coupon-code');
    const inputCoupon = document.getElementById('input-coupon-code');

    // Hàm cập nhật giao diện tính tiền
    function calculateTotal() {
        let discountValue = 0;
        if (currentCoupon) {
            discountValue = parseFloat(currentCoupon.discount);
            
            couponCodeEl.innerText = currentCoupon.code;
            if (inputCoupon) inputCoupon.value = currentCoupon.code;
        } else {
            couponCodeEl.innerText = "Chưa áp dụng";
            if (inputCoupon) inputCoupon.value = "";
        }

        let finalPrice = baseTotal - discountValue;
        if (finalPrice < 0) finalPrice = 0; // Đảm bảo không bị âm tiền

        // Format số tiền kiểu Việt Nam (VD: 100.000)
        discountEl.innerText = discountValue.toLocaleString('vi-VN');
        finalTotalEl.innerText = finalPrice.toLocaleString('vi-VN');
    }

    // Chạy mặc định lần đầu khi load trang (Auto-apply voucher hời nhất)
    calculateTotal();

    // Bắt sự kiện khi khách bấm "Áp dụng" mã khác trong Modal
    const applyButtons = document.querySelectorAll('.apply-coupon-btn');
    applyButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            currentCoupon = {
                code: this.getAttribute('data-code'),
                discount: this.getAttribute('data-discount'),
                type: this.getAttribute('data-type')
            };
            calculateTotal();
        });
    });
});
</script>