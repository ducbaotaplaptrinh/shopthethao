<div class="container">
    <section class="register mx-auto card shadow border-0 px-5" style="max-width: 500px;">
        <div class="card-body p-4">
            <h2 class="register-title text-center mb-4">
                Đăng ký
            </h2>

            <form action="" method="post" class="register-form" id="form-register">

                <div class="register-form__group mb-3">

                    <input
                        class="register-form__input form-control"
                        type="text"
                        name="fullname"
                        id="fullname"
                        placeholder=" "
                        required>
                    <label class="register-form__label form-label" for="fullname">
                        Họ và tên
                    </label>
                    <span class="form-message"></span>
                </div>

                <div class="register-form__group mb-3">

                    <input
                        class="register-form__input form-control"
                        type="tel"
                        name="phone"
                        id="phone"
                        placeholder=" "
                        required>
                    <label class="register-form__label form-label" for="phone">
                        Số điện thoại
                    </label>
                    <span class="form-message"></span>
                </div>

                <div class="register-form__group mb-3">

                    <input
                        class="register-form__input form-control"
                        type="email"
                        name="email"
                        id="email"
                        placeholder=" "
                        required>
                    <label class="register-form__label form-label" for="email">
                        Email
                    </label>
                    <span class="form-message"></span>
                </div>

                <div class="register-form__group mb-3">

                    <input
                        class="register-form__input form-control"
                        type="password"
                        name="password"
                        id="password"
                        placeholder=" "
                        required>
                    <label class="register-form__label form-label" for="password">
                        Mật khẩu
                    </label>
                    <span class="form-message"></span>
                </div>

                <div class="register-form__group mb-4">

                    <input
                        class="register-form__input form-control"
                        type="password"
                        name="confirm_password"
                        id="password_confirmation"
                        placeholder=" "
                        required>
                    <label class="register-form__label form-label" for="confirm_password">
                        Nhập lại mật khẩu
                    </label>
                    <span class="form-message"></span>
                </div>

                <button
                    type="submit"
                    class="register-btn btn btn-primary w-100">
                    ĐĂNG KÝ
                </button>

            </form>

            <div class="register-desc text-center mt-3">
                Bạn đã có tài khoản?
                <a href="?page=login" class="register-link text-decoration-none">
                    Đăng nhập ngay
                </a>
            </div>
        </div>

    </section>
</div>
<script src="assets/js/auth.js"></script>
<script>
    validator({
        form: "#form-register",
        formGroupSelector: ".register-form__group",
        errorMessage: ".form-message",
        rules: [
            validator.isRequired("#fullname"),
            validator.isRequired("#email"),
            validator.isEmail("#email"),
            validator.isRequired("#fullname"),
            validator.isRequired("#phone"),
            validator.isPhone("#phone"),
            validator.minLength("#password", 6),
            validator.isRequired("#password_confirmation"),
            validator.isConfirmed(
                "#password_confirmation",
                function() {
                    return document.querySelector("#form-register #password")
                        .value;
                }
            ),
        ],
        onSubmit: function(data) {
            console.log(data);
        },
    });
</script>