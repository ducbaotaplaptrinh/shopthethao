<div class="container">
    <div class="login row justify-content-center min-vh-100 ">

        <div class="col-12 col-md-8 col-lg-5">
            <div class="card shadow border-0">

                <div class="card-body p-4">

                    <h2 class="login-title text-center mb-4">
                        Đăng nhập
                    </h2>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger rounded-3 text-center mb-3" style="font-size: 1.4rem;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post" class="login-form" id="form-login">
                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect ?? '') ?>">

                        <div class="mb-3 login-form__group">

                            <input
                                id="email"
                                type="email"
                                class="form-control login-form__input"
                                name="email" 
                                placeholder=" "
                                value="<?= htmlspecialchars($email ?? '') ?>">

                            <label class="login-form__label">
                                Email
                            </label>

                            <span class="form-message"></span>
                        </div>

                        <div class="mb-3 login-form__group">

                            <input
                                id="password"
                                type="password"
                                class="form-control login-form__input"
                                name="password" placeholder=" ">

                            <label class="login-form__label">
                                Mật khẩu
                            </label>

                            <span class="form-message"></span>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100 form-login__btn form-submit">
                            Đăng nhập
                        </button>

                    </form>

                    <div class="text-center mt-3">
                        Chưa có tài khoản?
                        <a href="?page=register<?= !empty($redirect) ? '&redirect=' . urlencode($redirect) : '' ?>" class="login-form__link">
                            Đăng ký
                        </a>
                    </div>

                </div>

            </div>
        </div>

    </div>
    <script src="assets/js/auth.js"></script>
    <script>
        validator({
            form: "#form-login",
            formGroupSelector: ".login-form__group",
            errorMessage: ".form-message",
            rules: [
                validator.isRequired("#email"),
                validator.isEmail("#email"),
                validator.minLength("#password", 6),
            ]
        });
    </script>