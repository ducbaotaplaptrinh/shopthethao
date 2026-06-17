document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const newsItems = document.querySelectorAll('.news-card-item');

    // Chỉ chạy khi tìm thấy ô tìm kiếm trên trang
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            // Chuyển từ khóa gõ vào thành chữ thường và xóa khoảng trắng dư thừa
            const filterValue = searchInput.value.toLowerCase().trim();

            newsItems.forEach(function(item) {
                // Tìm kiếm tiêu đề và mô tả ngắn bên trong từng bài viết
                const titleElement = item.querySelector('.card-title');
                const descElement = item.querySelector('.card-desc');
                
                const titleText = titleElement ? (titleElement.textContent || titleElement.innerText) : '';
                const descText = descElement ? (descElement.textContent || descElement.innerText) : '';

                // So sánh xem từ khóa có xuất hiện trong tiêu đề hoặc mô tả không
                if (titleText.toLowerCase().indexOf(filterValue) > -1 || 
                    descText.toLowerCase().indexOf(filterValue) > -1) {
                    item.classList.remove('d-none'); // Hiển thị bài viết nếu khớp
                    // Giữ lại hiệu ứng hiển thị mượt mà
                    item.style.opacity = '1';
                    item.style.transform = 'scale(1)';
                } else {
                    item.classList.add('d-none'); // Ẩn bài viết đi nếu không khớp
                }
            });
        });
    }
});