<div class="container">
    <div class="login row justify-content-center">

        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow border-0">

                <div class="card-body p-4">

                    <h2 class="login-title text-center mb-4">
                        Yêu cầu tư vấn
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

                    <form action="" method="post" class="login-form" id="form-query">

                        <div class="mb-3 login-form__group">
                            <input
                                id="ho_ten"
                                type="text"
                                class="form-control login-form__input"
                                name="ho_ten"
                                placeholder=" "
                                value="<?= htmlspecialchars($ho_ten ?? '') ?>">
                            <label class="login-form__label">Họ và tên</label>
                            <span class="form-message"></span>
                        </div>

                        <div class="mb-3 login-form__group">
                            <input
                                id="so_dien_thoai"
                                type="tel"
                                class="form-control login-form__input"
                                name="so_dien_thoai"
                                placeholder=" "
                                value="<?= htmlspecialchars($so_dien_thoai ?? '') ?>">
                            <label class="login-form__label">Số điện thoại</label>
                            <span class="form-message"></span>
                        </div>

                        <div class="mb-3 login-form__group">
                            <input
                                id="email"
                                type="email"
                                class="form-control login-form__input"
                                name="email"
                                placeholder=" "
                                value="<?= htmlspecialchars($email ?? '') ?>">
                            <label class="login-form__label">Email</label>
                            <span class="form-message"></span>
                        </div>

                        <div class="mb-3 login-form__group " style="height: 90px;">
                            <textarea
                                id="noi_dung"
                                class="form-control login-form__input"
                                name="noi_dung"
                                rows="4"
                                style="height: 100%; resize: none;"
                                placeholder=" "><?= htmlspecialchars($noi_dung ?? '') ?></textarea>
                            <label class=" login-form__label">Nội dung yêu cầu</label>
                            <span class="form-message"></span>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100 form-login__btn form-submit">
                            Gửi yêu cầu
                        </button>

                    </form>

                </div>

            </div>
        </div>

    </div>
</div>

<script src="assets/js/auth.js"></script>
<script>
    // Cấu hình validator quét theo các trường dữ liệu mới
    validator({
        form: "#form-query",
        formGroupSelector: ".login-form__group",
        errorMessage: ".form-message",
        rules: [
            validator.isRequired("#ho_ten"),
            validator.isRequired("#so_dien_thoai"),
            validator.isPhone("#so_dien_thoai"),
            validator.isRequired("#email"),
            validator.isEmail("#email"),
        ]
    });
</script>