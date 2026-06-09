<?php
// Nhúng thư viện bóc tách HTML từ thư mục helpers
require_once __DIR__ . '/../helpers/simple_html_dom.php';

class NewsController {

    public function getNewsData() {
        // 1. Link chuyên đề cầu lông cần lấy dữ liệu
        $url = 'https://vnexpress.net/chu-de/cau-long-429';
        
        // Khởi tạo mảng rỗng để chứa danh sách tin tức
        $newsData = [];

        // 2. Tải mã HTML từ trang web
        $html = file_get_html($url);

        if ($html) {
            // 3. Tìm tất cả các thẻ chứa bài báo
            $articles = $html->find('article.item-news');

            foreach ($articles as $article) {
                // Trích xuất các thành phần bên trong bài báo
                $titleNode = $article->find('h3.title-news a', 0);
                $descNode  = $article->find('p.description a', 0);
                $imgNode   = $article->find('div.thumb-art picture img', 0);

                if ($titleNode) {
                    // Xử lý ảnh (ưu tiên lấy từ thuộc tính data-src nếu web dùng lazyload)
                    $image = 'https://via.placeholder.com/400x250?text=No+Image'; // Ảnh mặc định
                    if ($imgNode) {
                        $image = $imgNode->hasAttribute('data-src') ? $imgNode->getAttribute('data-src') : $imgNode->src;
                    }

                    // Đưa dữ liệu đã bóc tách vào mảng
                    $newsData[] = [
                        'title'       => trim($titleNode->plaintext),
                        'link'        => $titleNode->href,
                        'description' => $descNode ? trim($descNode->plaintext) : 'Đang cập nhật nội dung...',
                        'image'       => $image,
                        'source'      => 'VNExpress'
                    ];
                }
            }
            
            // Giải phóng bộ nhớ cho thư viện (Rất quan trọng để tránh nặng server)
            $html->clear();
            unset($html);
        }

        // 4. Gọi file View để hiển thị giao diện và truyền biến $newsData sang
        // Lưu ý: Nếu file Core/Controller của bạn có hàm render riêng (ví dụ: $this->render('news/index', $data)), hãy thay thế dòng dưới đây.
        return $newsData;
    }
}
?>