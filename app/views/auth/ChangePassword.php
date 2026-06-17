<div class="container">
    <section class="register mx-auto card shadow border-0 px-5" style="max-width: 500px; margin: 70px auto;">
        <div class="card-body p-4">
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
        </div>
    </section>
</div>

<script src="assets/js/auth.js"></script>
<script>
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
</script>
