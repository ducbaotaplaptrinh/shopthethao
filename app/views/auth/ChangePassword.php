<?php
// Determine which form layout to display based on login status and steps
$isLoggedIn = $is_logged_in ?? false;
$currentStep = $step ?? 1;
?>
<div class="container">
    <section class="register mx-auto card shadow border-0 px-5" style="max-width: 500px; margin: 70px auto;">
        <div class="card-body p-4">
            
            <?php if ($isLoggedIn): ?>
                <!-- ================== LOGGED IN FLOW ================== -->
                <h2 class="register-title text-center mb-4">
                    Đổi mật khẩu
                </h2>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger rounded-3 text-center mb-3" style="font-size: 1.4rem;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success rounded-3 text-center mb-3" style="font-size: 1.4rem;">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <form action="" method="post" class="register-form" id="form-change-password">
                    <div class="register-form__group mb-3">
                        <input
                            class="register-form__input form-control"
                            type="password"
                            name="current_password"
                            id="current_password"
                            placeholder=" "
                            required>
                        <label class="register-form__label form-label" for="current_password">
                            Mật khẩu hiện tại
                        </label>
                        <span class="form-message"></span>
                    </div>

                    <div class="register-form__group mb-3">
                        <input
                            class="register-form__input form-control"
                            type="password"
                            name="new_password"
                            id="new_password"
                            placeholder=" "
                            required>
                        <label class="register-form__label form-label" for="new_password">
                            Mật khẩu mới
                        </label>
                        <span class="form-message"></span>
                    </div>

                    <div class="register-form__group mb-4">
                        <input
                            class="register-form__input form-control"
                            type="password"
                            name="confirm_new_password"
                            id="confirm_new_password"
                            placeholder=" "
                            required>
                        <label class="register-form__label form-label" for="confirm_new_password">
                            Nhập lại mật khẩu mới
                        </label>
                        <span class="form-message"></span>
                    </div>

                    <button
                        type="submit"
                        class="register-btn btn btn-primary w-100">
                        CẬP NHẬT MẬT KHẨU
                    </button>
                </form>

                <div class="register-desc text-center mt-4">
                    <a href="?page=home" class="register-link text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i>Quay lại trang chủ
                    </a>
                </div>

            <?php else: ?>
                <!-- ================== GUEST FORGOT PASSWORD FLOW ================== -->
                
                <?php if ($currentStep == 1): ?>
                    <!-- STEP 1: Enter Email -->
                    <h2 class="register-title text-center mb-2">
                        Quên mật khẩu
                    </h2>
                    <p class="text-center text-muted mb-4" style="font-size: 1.4rem;">
                        Nhập địa chỉ email của bạn để nhận mã xác thực OTP khôi phục mật khẩu.
                    </p>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger rounded-3 text-center mb-3" style="font-size: 1.4rem;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post" class="register-form" id="form-forgot-email">
                        <div class="register-form__group mb-4">
                            <input
                                class="register-form__input form-control"
                                type="email"
                                name="email"
                                id="email"
                                placeholder=" "
                                required>
                            <label class="register-form__label form-label" for="email">
                                Nhập địa chỉ email
                            </label>
                            <span class="form-message"></span>
                        </div>

                        <button
                            type="submit"
                            class="register-btn btn btn-primary w-100">
                            GỬI MÃ OTP
                        </button>
                    </form>

                    <div class="register-desc text-center mt-4">
                        <a href="?page=login" class="register-link text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i>Quay lại đăng nhập
                        </a>
                    </div>

                <?php elseif ($currentStep == 2): ?>
                    <!-- STEP 2: Enter OTP -->
                    <h2 class="register-title text-center mb-2">
                        Xác thực OTP
                    </h2>
                    <p class="text-center text-muted mb-4" style="font-size: 1.4rem;">
                        Mã xác thực OTP đã được gửi đến email: <strong class="text-dark"><?= htmlspecialchars($email ?? '') ?></strong>. Vui lòng kiểm tra hộp thư của bạn.
                    </p>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger rounded-3 text-center mb-3" style="font-size: 1.4rem;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success rounded-3 text-center mb-3" style="font-size: 1.4rem;">
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post" class="register-form" id="form-forgot-otp">
                        <div class="register-form__group mb-4">
                            <input
                                class="register-form__input form-control text-center fw-bold"
                                type="text"
                                name="otp"
                                id="otp"
                                placeholder=" "
                                maxlength="6"
                                required
                                style="letter-spacing: 8px; font-size: 2rem;">
                            <label class="register-form__label form-label" for="otp" style="left: 50%; transform: translate(-50%, 50%); width: auto;">
                                Nhập mã OTP (6 chữ số)
                            </label>
                            <span class="form-message w-100 text-center"></span>
                        </div>

                        <button
                            type="submit"
                            class="register-btn btn btn-primary w-100">
                            XÁC THỰC OTP
                        </button>
                    </form>

                    <div class="d-flex justify-content-between align-items-center mt-4" style="font-size: 1.4rem;">
                        <span class="text-muted" id="otp-timer-text">
                            Mã hiệu lực trong: <strong id="otp-countdown" class="text-danger">05:00</strong>
                        </span>
                        <form action="" method="post" id="resend-form" class="d-inline">
                            <input type="hidden" name="action" value="resend">
                            <button type="submit" id="btn-resend-otp" class="btn btn-link text-decoration-none p-0 text-danger fw-bold" disabled>
                                Gửi lại mã
                            </button>
                        </form>
                    </div>

                    <?php if (isset($_SESSION['last_sent_otp'])): ?>
                        <div class="alert alert-info rounded-3 text-center mt-4" style="font-size: 1.2rem; border: 1px dashed #0dcaf0; background-color: #f0faff;">
                            <i class="bi bi-info-circle me-1"></i><strong>Chế độ Localhost:</strong><br>
                            OTP hiện tại: <span class="badge bg-primary fs-6 mt-1"><?= $_SESSION['last_sent_otp'] ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="register-desc text-center mt-4">
                        <a href="?page=change-password&reset_forgot_password=1" class="register-link text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i>Thay đổi email khác
                        </a>
                    </div>

                <?php elseif ($currentStep == 3): ?>
                    <!-- STEP 3: Enter New Password -->
                    <h2 class="register-title text-center mb-2">
                        Đặt lại mật khẩu
                    </h2>
                    <p class="text-center text-muted mb-4" style="font-size: 1.4rem;">
                        Tạo mật khẩu mới cho email: <strong class="text-dark"><?= htmlspecialchars($email ?? '') ?></strong>
                    </p>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger rounded-3 text-center mb-3" style="font-size: 1.4rem;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post" class="register-form" id="form-forgot-reset">
                        <div class="register-form__group mb-3">
                            <input
                                class="register-form__input form-control"
                                type="password"
                                name="new_password"
                                id="new_password"
                                placeholder=" "
                                required>
                            <label class="register-form__label form-label" for="new_password">
                                Mật khẩu mới
                            </label>
                            <span class="form-message"></span>
                        </div>

                        <div class="register-form__group mb-4">
                            <input
                                class="register-form__input form-control"
                                type="password"
                                name="confirm_new_password"
                                id="confirm_new_password"
                                placeholder=" "
                                required>
                            <label class="register-form__label form-label" for="confirm_new_password">
                                Nhập lại mật khẩu mới
                            </label>
                            <span class="form-message"></span>
                        </div>

                        <button
                            type="submit"
                            class="register-btn btn btn-primary w-100">
                            XÁC NHẬN MẬT KHẨU MỚI
                        </button>
                    </form>

                    <div class="register-desc text-center mt-4">
                        <a href="?page=change-password&reset_forgot_password=1" class="register-link text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i>Bắt đầu lại quá trình
                        </a>
                    </div>

                <?php elseif ($currentStep == 4): ?>
                    <!-- STEP 4: Success Screen -->
                    <div class="text-center py-3">
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4.8rem;"></i>
                        </div>
                        <h2 class="register-title mb-2">
                            Thành công!
                        </h2>
                        <p class="text-muted mb-4" style="font-size: 1.5rem;">
                            Mật khẩu của bạn đã được đặt lại thành công. Bạn hiện có thể đăng nhập bằng mật khẩu mới này.
                        </p>

                        <a href="?page=login" class="register-btn btn btn-primary w-100 py-3 fw-bold text-decoration-none d-block">
                            ĐĂNG NHẬP NGAY
                        </a>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

        </div>
    </section>
</div>

<script src="assets/js/auth.js"></script>
<script>
    // Inline Validators based on active forms
    <?php if ($isLoggedIn): ?>
        validator({
            form: "#form-change-password",
            formGroupSelector: ".register-form__group",
            errorMessage: ".form-message",
            rules: [
                validator.isRequired("#current_password", "Vui lòng nhập mật khẩu hiện tại"),
                validator.isRequired("#new_password", "Vui lòng nhập mật khẩu mới"),
                validator.minLength("#new_password", 6, "Mật khẩu mới phải từ 6 ký tự trở lên"),
                validator.isRequired("#confirm_new_password", "Vui lòng xác nhận mật khẩu mới"),
                validator.isConfirmed(
                    "#confirm_new_password",
                    function() {
                        return document.querySelector("#form-change-password #new_password").value;
                    },
                    "Mật khẩu xác nhận chưa khớp với mật khẩu mới"
                )
            ]
        });
    <?php else: ?>
        <?php if ($currentStep == 1): ?>
            validator({
                form: "#form-forgot-email",
                formGroupSelector: ".register-form__group",
                errorMessage: ".form-message",
                rules: [
                    validator.isRequired("#email", "Vui lòng nhập email"),
                    validator.isEmail("#email", "Địa chỉ email không hợp lệ")
                ]
            });
        <?php elseif ($currentStep == 2): ?>
            validator({
                form: "#form-forgot-otp",
                formGroupSelector: ".register-form__group",
                errorMessage: ".form-message",
                rules: [
                    validator.isRequired("#otp", "Vui lòng nhập mã OTP"),
                    {
                        selector: "#otp",
                        test: function(value) {
                            return /^[0-9]{6}$/.test(value) ? undefined : "Mã OTP phải gồm 6 chữ số";
                        }
                    }
                ]
            });

            // OTP Countdown Timer Logic
            (function() {
                let timeLimit = 300; // 5 minutes
                const countdownEl = document.getElementById("otp-countdown");
                const resendBtn = document.getElementById("btn-resend-otp");

                if (countdownEl && resendBtn) {
                    function startTimer(duration) {
                        let timer = duration, minutes, seconds;
                        let interval = setInterval(function () {
                            minutes = parseInt(timer / 60, 10);
                            seconds = parseInt(timer % 60, 10);

                            minutes = minutes < 10 ? "0" + minutes : minutes;
                            seconds = seconds < 10 ? "0" + seconds : seconds;

                            countdownEl.textContent = minutes + ":" + seconds;

                            if (--timer < 0) {
                                clearInterval(interval);
                                countdownEl.textContent = "Hết hạn";
                                resendBtn.removeAttribute("disabled");
                            }
                        }, 1000);
                    }
                    startTimer(timeLimit);
                }
            })();
        <?php elseif ($currentStep == 3): ?>
            validator({
                form: "#form-forgot-reset",
                formGroupSelector: ".register-form__group",
                errorMessage: ".form-message",
                rules: [
                    validator.isRequired("#new_password", "Vui lòng nhập mật khẩu mới"),
                    validator.minLength("#new_password", 6, "Mật khẩu mới phải từ 6 ký tự trở lên"),
                    validator.isRequired("#confirm_new_password", "Vui lòng xác nhận mật khẩu mới"),
                    validator.isConfirmed(
                        "#confirm_new_password",
                        function() {
                            return document.querySelector("#form-forgot-reset #new_password").value;
                        },
                        "Mật khẩu xác nhận chưa khớp với mật khẩu mới"
                    )
                ]
            });
        <?php endif; ?>
    <?php endif; ?>
</script>
