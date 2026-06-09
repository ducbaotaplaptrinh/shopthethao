document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const newsCards = document.querySelectorAll('.news-card');

    searchInput.addEventListener('keyup', function() {
        // Chuyển từ khóa gõ vào thành chữ thường để dễ so sánh
        const filterValue = searchInput.value.toLowerCase();

        newsCards.forEach(function(card) {
            // Lấy tiêu đề bài báo trong thẻ card hiện tại
            const titleElement = card.querySelector('.card-title');
            const titleText = titleElement.textContent || titleElement.innerText;

            // Kiểm tra xem tiêu đề có chứa từ khóa không
            if (titleText.toLowerCase().indexOf(filterValue) > -1) {
                card.style.display = ""; // Hiển thị nếu khớp
            } else {
                card.style.display = "none"; // Ẩn đi nếu không khớp
            }
        });
    });
});