<?php

namespace app\controllers;

use app\models\NewsModel;

class NewsController
{
    private $newsModel;

    public function __construct()
    {
        // Khởi tạo đối tượng model tin tức để tương tác với CSDL
        $this->newsModel = new NewsModel();
    }

    /**
     * Hiển thị danh sách tin tức
     * Đường dẫn: ?page=new
     */
    public function index(): array
    {
        // Lấy từ khóa tìm kiếm từ thanh URL (ví dụ: ?page=new&search=vot+yonex)
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;
        
        // Truy vấn dữ liệu từ Model
        $newsList = $this->newsModel->getAllNews($search);
        
        return [
            'title' => 'Tin tức Thể thao & Cầu lông | Bảo Đạt Sport',
            'newsData' => $newsList,
            'searchQuery' => $search
        ];
    }

    /**
     * Xem chi tiết bài viết tin tức
     * Đường dẫn: ?page=new-detail&slug=tieu-de-bai-viet
     */
    public function detail(): array
    {
        // Lấy slug từ tham số đường dẫn
        $slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
        
        // Lấy thông tin bài viết từ CSDL
        $article = $this->newsModel->getNewsBySlug($slug);
        
        // Nếu không tìm thấy bài viết, trả về thông tin báo lỗi
        if (!$article) {
            return [
                'title' => 'Bài viết không tồn tại',
                'article' => null,
                'relatedNews' => []
            ];
        }

        // Tăng lượt xem của bài viết
        $this->newsModel->incrementViews($article['id']);
        
        // Lấy danh sách 3 bài viết liên quan mới nhất (loại trừ bài hiện tại)
        $relatedNews = $this->newsModel->getRelatedNews($article['id'], 3);

        return [
            'title' => $article['tieu_de'] . ' | Bảo Đạt Sport',
            'article' => $article,
            'relatedNews' => $relatedNews
        ];
    }
}