<?php
namespace app\models\admin;
use app\core\Model;

class AdminNewsModel extends Model
{

    /**
     * Thêm bài viết mới vào Database
     */
    public function insertNews($tieuDe, $slug, $anhDaiDien, $tomTat, $noiDung, $maTacGia, $trangThai)
    {
        $sql = "INSERT INTO tin_tuc (tieu_de, duong_dan_slug, anh_dai_dien, tom_tat, noi_dung, ma_tac_gia, trang_thai) 
                VALUES (:tieu_de, :slug, :anh_dai_dien, :tom_tat, :noi_dung, :ma_tac_gia, :trang_thai)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'tieu_de' => $tieuDe,
            'slug' => $slug,
            'anh_dai_dien' => $anhDaiDien,
            'tom_tat' => $tomTat,
            'noi_dung' => $noiDung,
            'ma_tac_gia' => $maTacGia,
            'trang_thai' => $trangThai
        ]);
    }
}