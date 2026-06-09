document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const phpSuccess = document.getElementById('phpSuccessMsg');

    // Nếu có thông báo thành công từ PHP, tự động ẩn sau 5 giây
    if(phpSuccess) {
        setTimeout(() => {
            phpSuccess.style.display = 'none';
        }, 5000);
    }

    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Ẩn tất cả thông báo lỗi cũ
        document.querySelectorAll('.error-message').forEach(el => {
            el.style.display = 'none';
        });
        
        // Kiểm tra họ tên
        const name = document.getElementById('name').value;
        if (name.trim() === '') {
            document.getElementById('nameError').style.display = 'block';
            isValid = false;
        }

        // Kiểm tra email (sử dụng regex cơ bản)
        const email = document.getElementById('email').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            document.getElementById('emailError').style.display = 'block';
            isValid = false;
        }

        // Kiểm tra nội dung tin nhắn
        const message = document.getElementById('message').value;
        if (message.trim() === '') {
            document.getElementById('messageError').style.display = 'block';
            isValid = false;
        }

        // Nếu thông tin k hợp lệ, chặn nộp form
        if (!isValid) {
            e.preventDefault(); 
        }
        // Còn nếu hợp lệ, để yên trình duyệt sẽ gửi POST đi tới controller PHP
    });
});
