<div class="container">
    <section class="register mx-auto card shadow border-0 px-5" style="max-width: 500px;">
        <h2 class="register-title text-center mb-4">
            Đăng ký
        </h2>

        <form action="" method="post" class="register-form">

            <div class="register-form__group mb-3">

                <input
                    class="register-form__input form-control"
                    type="text"
                    name="fullname"
                    id="fullname"
                    required>
                <label class="register-form__label form-label" for="fullname">
                    Họ và tên
                </label>
            </div>

            <div class="register-form__group mb-3">

                <input
                    class="register-form__input form-control"
                    type="tel"
                    name="phone"
                    id="phone"
                    required>
                <label class="register-form__label form-label" for="phone">
                    Số điện thoại
                </label>
            </div>

            <div class="register-form__group mb-3">

                <input
                    class="register-form__input form-control"
                    type="email"
                    name="email"
                    id="email"
                    required>
                <label class="register-form__label form-label" for="email">
                    Email
                </label>
            </div>

            <div class="register-form__group mb-3">

                <input
                    class="register-form__input form-control"
                    type="password"
                    name="password"
                    id="password"
                    required>
                <label class="register-form__label form-label" for="password">
                    Mật khẩu
                </label>
            </div>

            <div class="register-form__group mb-4">

                <input
                    class="register-form__input form-control"
                    type="password"
                    name="confirm_password"
                    id="confirm_password"
                    required>
                <label class="register-form__label form-label" for="confirm_password">
                    Nhập lại mật khẩu
                </label>
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
    </section>
</div>