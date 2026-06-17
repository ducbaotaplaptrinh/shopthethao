<div class="container">
    <section class="register mx-auto card shadow border-0 px-5" style="max-width: 500px; margin: 70px auto;">
        <div class="card-body p-4">
            <h2 class="register-title text-center mb-2">
                Xác thực OTP
            </h2>
            <p class="text-center text-muted mb-4" style="font-size: 1.4rem;">
                Mã xác thực OTP đã được gửi đến email đăng ký của bạn. Vui lòng kiểm tra hộp thư.
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

            <form action="" method="post" class="register-form" id="form-verify-otp">
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
                    XÁC THỰC
                </button>
            </form>

            <div class="d-flex justify-content-between align-items-center mt-4" style="font-size: 1.4rem;">
                <span class="text-muted" id="otp-timer-text">
                    Mã hiệu lực trong: <strong id="otp-countdown" class="text-danger">05:00</strong>
                </span>
                <form action="?page=verify-otp" method="post" id="resend-form" class="d-inline">
                    <input type="hidden" name="action" value="resend">
                    <button type="submit" id="btn-resend-otp" class="btn btn-link text-decoration-none p-0 text-danger fw-bold" disabled>
                        Gửi lại mã
                    </button>
                </form>
            </div>

            <?php if (isset($_SESSION['last_sent_otp'])): ?>
                <div class="alert alert-info rounded-3 text-center mt-4" style="font-size: 1.2rem; border: 1px dashed #0dcaf0; background-color: #f0faff;">
                    <i class="bi bi-info-circle me-1"></i><strong>Chế độ Localhost:</strong><br>
                    OTP hiện tại: <span class="badge bg-primary fs-6 mt-1"><?= $_SESSION['last_sent_otp'] ?></span><br>
                    <small class="text-muted">(Mã cũng được ghi tại <code>logs/email_logs.log</code>)</small>
                </div>
            <?php endif; ?>

            <div class="register-desc text-center mt-4">
                <a href="?page=register" class="register-link text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i>Quay lại đăng ký
                </a>
            </div>
        </div>
    </section>
</div>

<script src="assets/js/auth.js"></script>
<script>
    validator({
        form: "#form-verify-otp",
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

    // Code countdown timer
    let timeLimit = 300; // 5 mins in seconds
    const countdownEl = document.getElementById("otp-countdown");
    const resendBtn = document.getElementById("btn-resend-otp");

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
</script>
