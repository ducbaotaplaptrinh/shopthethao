<?php
// Rank definition dynamically loaded from database
$tongChiTieu = $user->getTong_chi_tieu();

$rankName = $rankInfo['ten_hang'] ?? 'Đồng';
$rankColor = $rankInfo['mau_sac'] ?? '#cd7f32';
$rankIcon = $rankInfo['bieu_tuong'] ?? 'bi-star-half';

$rankClass = 'rank-bronze';
if ($rankName === 'Kim Cương') {
    $rankClass = 'rank-diamond';
} elseif ($rankName === 'Vàng') {
    $rankClass = 'rank-gold';
} elseif ($rankName === 'Bạc') {
    $rankClass = 'rank-silver';
}

function getAvatarImage($avatarPath) {
    if (empty($avatarPath)) {
        return 'assets/images/avatars/avt.jpg';
    }
    if (file_exists(BASE_PATH . '/public/' . $avatarPath)) {
        return $avatarPath;
    }
    return 'assets/images/avatars/avt.jpg';
}
?>

<!-- ============ HERO HEADER ============ -->
<div class="profile-hero py-4 mb-4">
    <div class="container-xl">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width:52px;height:52px;background:rgba(255,255,255,.15);">
                <i class="bi bi-person-fill-gear" style="font-size:1.6rem; color: #fff;"></i>
            </div>
            <div>
                <h1 class="mb-0 text-white fw-bold h3">Trang cá nhân</h1>
                <p class="mb-0 text-white-50 small">Quản lý thông tin tài khoản và danh sách địa chỉ nhận hàng của bạn.</p>
            </div>
        </div>
    </div>
</div>

<div class="container-xl pb-5">
    <!-- Notifications -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div><?= htmlspecialchars($success) ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" data-bs-content="close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- ============ LEFT SIDEBAR ============ -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4 profile-sidebar-card">
                <!-- Avatar block -->
                <div class="position-relative d-inline-block mx-auto mb-3">
                    <img id="avatar-preview" src="<?= htmlspecialchars(getAvatarImage($user->getAnh_dai_dien())) ?>" 
                         alt="<?= htmlspecialchars($user->getHo_ten()) ?>" 
                         class="rounded-circle border border-4 border-white shadow-sm"
                         style="width:130px; height:130px; object-fit:cover;">
                    <label for="input-avatar" class="avatar-edit-badge rounded-circle shadow d-flex align-items-center justify-content-center cursor-pointer position-absolute bottom-0 end-0 bg-primary text-white" style="width:36px; height:36px; cursor: pointer;">
                        <i class="bi bi-camera-fill"></i>
                    </label>
                </div>

                <h4 class="mb-1 fw-bold text-dark"><?= htmlspecialchars($user->getHo_ten()) ?></h4>
                <p class="text-muted small mb-3"><?= htmlspecialchars($user->getEmail()) ?></p>

                <!-- Rank & Spending Info -->
                <div class="px-3 py-2 bg-light rounded-4 d-flex align-items-center justify-content-between mb-4 shadow-inner">
                    <div class="text-start">
                        <span class="text-muted d-block small" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Hạng thành viên</span>
                        <span class="badge rounded-pill text-white fw-bold d-inline-flex align-items-center gap-1 mt-1 px-3 py-1.5 <?= $rankClass ?>" style="background-color: <?= $rankColor ?>; font-size: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                            <i class="bi <?= $rankIcon ?>"></i>
                            <?= $rankName ?>
                        </span>
                    </div>
                    <div class="text-end">
                        <span class="text-muted d-block small" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Tổng chi tiêu</span>
                        <span class="fw-bold text-danger d-block mt-1 fs-5"><?= formatVND($tongChiTieu) ?></span>
                    </div>
                </div>

                <!-- Navigation Sidebar Menu -->
                <div class="list-group list-group-flush rounded-3 border-0 sidebar-menu">
                    <a href="?page=profile&tab=info" 
                       class="list-group-item list-group-item-action border-0 px-3 py-3 rounded-3 mb-2 d-flex align-items-center justify-content-between <?= $activeTab === 'info' ? 'active' : '' ?>">
                        <span class="d-flex align-items-center gap-3">
                            <i class="bi bi-person-bounding-box fs-5"></i>
                            <span class="fw-semibold">Thông tin tài khoản</span>
                        </span>
                        <i class="bi bi-chevron-right small"></i>
                    </a>
                    <a href="?page=profile&tab=address" 
                       class="list-group-item list-group-item-action border-0 px-3 py-3 rounded-3 mb-2 d-flex align-items-center justify-content-between <?= $activeTab === 'address' ? 'active' : '' ?>">
                        <span class="d-flex align-items-center gap-3">
                            <i class="bi bi-geo-alt fs-5"></i>
                            <span class="fw-semibold">Sổ địa chỉ</span>
                        </span>
                        <i class="bi bi-chevron-right small"></i>
                    </a>
                    <a href="?page=my-orders" 
                       class="list-group-item list-group-item-action border-0 px-3 py-3 rounded-3 mb-2 d-flex align-items-center justify-content-between">
                        <span class="d-flex align-items-center gap-3">
                            <i class="bi bi-bag-check fs-5"></i>
                            <span class="fw-semibold">Đơn hàng của tôi</span>
                        </span>
                        <i class="bi bi-chevron-right small"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- ============ RIGHT CONTENT PANEL ============ -->
        <div class="col-lg-8">
            <!-- TAB 1: THÔNG TIN TÀI KHOẢN -->
            <?php if ($activeTab === 'info'): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 h-100">
                    <div class="border-bottom pb-3 mb-4">
                        <h3 class="mb-1 fw-bold text-dark h4">Thông tin tài khoản</h3>
                        <p class="text-muted small mb-0">Cập nhật thông tin chi tiết và mật khẩu truy cập của bạn.</p>
                    </div>

                    <form id="profile-form" action="?page=profile&tab=info" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="update_profile">
                        <!-- Hidden File input for avatar -->
                        <input type="file" name="anh_dai_dien" id="input-avatar" class="d-none" accept="image/*">

                        <div class="row g-4">
                            <!-- Full name -->
                            <div class="col-md-6">
                                <label for="fullname" class="form-label fw-bold text-secondary">Họ và tên <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                    <input type="text" class="form-control bg-light border-start-0 ps-0" id="fullname" name="fullname" value="<?= htmlspecialchars($user->getHo_ten()) ?>" required placeholder="Nhập họ và tên">
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold text-secondary">Địa chỉ Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                    <input type="email" class="form-control bg-light border-start-0 ps-0" id="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" required placeholder="Nhập email">
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-bold text-secondary">Số điện thoại <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone text-muted"></i></span>
                                    <input type="tel" class="form-control bg-light border-start-0 ps-0" id="phone" name="phone" value="<?= htmlspecialchars($user->getSo_dien_thoai() ?? '') ?>" required placeholder="Nhập số điện thoại">
                                </div>
                            </div>

                            <!-- Role (Read-only) -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Vai trò hệ thống</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock text-muted"></i></span>
                                    <input type="text" class="form-control bg-light border-start-0 ps-0" value="<?= $user->getVai_tro() === 'quan_tri' ? 'Quản trị viên' : 'Khách hàng' ?>" readonly disabled>
                                </div>
                            </div>

                            <!-- PASSWORD UPDATE SECTION -->
                            <div class="col-12 mt-5">
                                <div class="border-top pt-4 mb-3">
                                    <h4 class="mb-1 fw-bold text-dark h5">Thay đổi mật khẩu</h4>
                                    <p class="text-muted small mb-0">Để trống nếu bạn không muốn đổi mật khẩu.</p>
                                </div>
                            </div>

                            <!-- Old Password -->
                            <div class="col-md-6">
                                <label for="old_password" class="form-label fw-bold text-secondary">Mật khẩu hiện tại</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                    <input type="password" class="form-control bg-light border-start-0 ps-0" id="old_password" name="old_password" placeholder="Nhập mật khẩu hiện tại">
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="col-md-6">
                                <label for="new_password" class="form-label fw-bold text-secondary">Mật khẩu mới</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                                    <input type="password" class="form-control bg-light border-start-0 ps-0" id="new_password" name="new_password" placeholder="Nhập mật khẩu mới">
                                </div>
                            </div>

                            <!-- Action button -->
                            <div class="col-12 mt-4 text-end">
                                <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-3 fw-semibold shadow-sm btn-update-profile">
                                    <i class="bi bi-check2-square me-2"></i>Lưu thay đổi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <!-- TAB 2: QUẢN LÝ SỔ ĐỊA CHỈ -->
            <?php if ($activeTab === 'address'): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4 flex-wrap gap-2">
                        <div>
                            <h3 class="mb-1 fw-bold text-dark h4">Sổ địa chỉ</h3>
                            <p class="text-muted small mb-0">Quản lý các địa chỉ nhận hàng của bạn để thanh toán nhanh chóng hơn.</p>
                        </div>
                        <button class="btn btn-primary rounded-3 d-flex align-items-center gap-2 fw-semibold px-4 py-2.5 shadow-sm"
                                data-bs-toggle="modal" data-bs-target="#addAddressModal">
                            <i class="bi bi-plus-circle-fill"></i>Thêm địa chỉ mới
                        </button>
                    </div>

                    <!-- Address List -->
                    <?php if (empty($addresses)): ?>
                        <div class="text-center py-5">
                            <div class="empty-address-icon mb-3">📍</div>
                            <h5 class="fw-bold text-dark">Chưa có địa chỉ nào</h5>
                            <p class="text-muted small">Hãy thêm địa chỉ nhận hàng đầu tiên của bạn để tiện lợi hơn khi đặt hàng.</p>
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($addresses as $addr): ?>
                                <div class="col-12">
                                    <div class="card rounded-4 border-2 p-3.5 transition-all position-relative address-item-card <?= $addr['la_mac_dinh'] ? 'border-primary bg-primary-subtle' : 'border-light bg-light-subtle' ?>">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                            <div>
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <h5 class="mb-0 fw-bold text-dark h6"><?= htmlspecialchars($addr['ho_ten_nguoi_nhan']) ?></h5>
                                                    <?php if ($addr['la_mac_dinh']): ?>
                                                        <span class="badge bg-primary px-2 py-1" style="font-size: 10px; border-radius: 4px;">Mặc định</span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-muted mb-2 small"><i class="bi bi-telephone-fill me-2 text-secondary"></i><?= htmlspecialchars($addr['so_dien_thoai']) ?></p>
                                                <p class="text-dark mb-0 small" style="line-height: 1.5;">
                                                    <i class="bi bi-geo-alt-fill me-2 text-danger"></i>
                                                    <?= htmlspecialchars($addr['dia_chi_chi_tiet']) ?>, 
                                                    <?= htmlspecialchars($addr['phuong_xa']) ?>, 
                                                    <?= htmlspecialchars($addr['quan_huyen']) ?>, 
                                                    <?= htmlspecialchars($addr['tinh_thanh_pho']) ?>
                                                </p>
                                            </div>

                                            <!-- Actions on Address -->
                                            <div class="d-flex gap-2 align-items-center">
                                                <!-- Edit button -->
                                                <button class="btn btn-sm btn-outline-dark rounded-3 px-3 py-1.5 fw-semibold d-flex align-items-center gap-1 btn-edit-address"
                                                        data-bs-toggle="modal" data-bs-target="#editAddressModal" 
                                                        data-id="<?= $addr['id'] ?>"
                                                        data-name="<?= htmlspecialchars($addr['ho_ten_nguoi_nhan']) ?>"
                                                        data-phone="<?= htmlspecialchars($addr['so_dien_thoai']) ?>"
                                                        data-detail="<?= htmlspecialchars($addr['dia_chi_chi_tiet']) ?>"
                                                        data-ward="<?= htmlspecialchars($addr['phuong_xa']) ?>"
                                                        data-district="<?= htmlspecialchars($addr['quan_huyen']) ?>"
                                                        data-province="<?= htmlspecialchars($addr['tinh_thanh_pho']) ?>"
                                                        data-default="<?= $addr['la_mac_dinh'] ?>">
                                                    <i class="bi bi-pencil-square"></i> Sửa
                                                </button>

                                                <!-- Delete button (Don't allow delete default directly if it is the only one, handled in backend) -->
                                                <form action="?page=profile&tab=address" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?');" class="d-inline">
                                                    <input type="hidden" name="action" value="delete_address">
                                                    <input type="hidden" name="address_id" value="<?= $addr['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-3 py-1.5 fw-semibold d-flex align-items-center gap-1">
                                                        <i class="bi bi-trash"></i> Xóa
                                                    </button>
                                                </form>

                                                <!-- Set default button if not default -->
                                                <?php if (!$addr['la_mac_dinh']): ?>
                                                    <form action="?page=profile&tab=address" method="POST" class="d-inline">
                                                        <input type="hidden" name="action" value="set_default_address">
                                                        <input type="hidden" name="address_id" value="<?= $addr['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-3 px-3 py-1.5 fw-semibold">
                                                            Thiết lập mặc định
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ======================================== -->
<!-- MODAL: THÊM ĐỊA CHỈ MỚI -->
<!-- ======================================== -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark" id="addAddressModalLabel">Thêm địa chỉ nhận hàng mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="?page=profile&tab=address" method="POST">
                <input type="hidden" name="action" value="add_address">
                <div class="modal-body py-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="add_recipient" class="form-label fw-bold text-secondary small">Tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light rounded-3" id="add_recipient" name="ho_ten_nguoi_nhan" required placeholder="Nhập tên người nhận">
                        </div>
                        <div class="col-md-6">
                            <label for="add_phone" class="form-label fw-bold text-secondary small">Số điện thoại nhận <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control bg-light rounded-3" id="add_phone" name="so_dien_thoai" required placeholder="Nhập số điện thoại">
                        </div>
                        <div class="col-md-4">
                            <label for="add_province" class="form-label fw-bold text-secondary small">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light rounded-3" id="add_province" name="tinh_thanh_pho" required placeholder="Ví dụ: TP. Hồ Chí Minh">
                        </div>
                        <div class="col-md-4">
                            <label for="add_district" class="form-label fw-bold text-secondary small">Quận / Huyện <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light rounded-3" id="add_district" name="quan_huyen" required placeholder="Ví dụ: Quận 1">
                        </div>
                        <div class="col-md-4">
                            <label for="add_ward" class="form-label fw-bold text-secondary small">Phường / Xã <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light rounded-3" id="add_ward" name="phuong_xa" required placeholder="Ví dụ: Phường Bến Nghé">
                        </div>
                        <div class="col-12">
                            <label for="add_detail" class="form-label fw-bold text-secondary small">Địa chỉ chi tiết (Số nhà, tên đường...) <span class="text-danger">*</span></label>
                            <textarea class="form-control bg-light rounded-3" id="add_detail" name="dia_chi_chi_tiet" rows="2" required placeholder="Ví dụ: 123 Nguyễn Huệ"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="add_default" name="la_mac_dinh" value="1">
                                <label class="form-check-label cursor-pointer text-dark fw-semibold small" for="add_default">Đặt làm địa chỉ mặc định</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Thêm địa chỉ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================== -->
<!-- MODAL: SỬA ĐỊA CHỈ ĐÃ CÓ -->
<!-- ======================================== -->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark" id="editAddressModalLabel">Chỉnh sửa địa chỉ nhận hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="?page=profile&tab=address" method="POST">
                <input type="hidden" name="action" value="edit_address">
                <input type="hidden" name="address_id" id="edit_address_id">
                <div class="modal-body py-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_recipient" class="form-label fw-bold text-secondary small">Tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light rounded-3" id="edit_recipient" name="ho_ten_nguoi_nhan" required placeholder="Nhập tên người nhận">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_phone" class="form-label fw-bold text-secondary small">Số điện thoại nhận <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control bg-light rounded-3" id="edit_phone" name="so_dien_thoai" required placeholder="Nhập số điện thoại">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_province" class="form-label fw-bold text-secondary small">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light rounded-3" id="edit_province" name="tinh_thanh_pho" required placeholder="Ví dụ: TP. Hồ Chí Minh">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_district" class="form-label fw-bold text-secondary small">Quận / Huyện <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light rounded-3" id="edit_district" name="quan_huyen" required placeholder="Ví dụ: Quận 1">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_ward" class="form-label fw-bold text-secondary small">Phường / Xã <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light rounded-3" id="edit_ward" name="phuong_xa" required placeholder="Ví dụ: Phường Bến Nghé">
                        </div>
                        <div class="col-12">
                            <label for="edit_detail" class="form-label fw-bold text-secondary small">Địa chỉ chi tiết (Số nhà, tên đường...) <span class="text-danger">*</span></label>
                            <textarea class="form-control bg-light rounded-3" id="edit_detail" name="dia_chi_chi_tiet" rows="2" required placeholder="Ví dụ: 123 Nguyễn Huệ"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="edit_default" name="la_mac_dinh" value="1">
                                <label class="form-check-label cursor-pointer text-dark fw-semibold small" for="edit_default">Đặt làm địa chỉ mặc định</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script to handle avatar preview and populate edit modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Avatar file change preview
    const inputAvatar = document.getElementById('input-avatar');
    const avatarPreview = document.getElementById('avatar-preview');
    if (inputAvatar && avatarPreview) {
        inputAvatar.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Populate edit address modal with existing data
    const editButtons = document.querySelectorAll('.btn-edit-address');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const phone = this.getAttribute('data-phone');
            const detail = this.getAttribute('data-detail');
            const ward = this.getAttribute('data-ward');
            const district = this.getAttribute('data-district');
            const province = this.getAttribute('data-province');
            const isDefault = this.getAttribute('data-default') == '1';

            document.getElementById('edit_address_id').value = id;
            document.getElementById('edit_recipient').value = name;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_detail').value = detail;
            document.getElementById('edit_ward').value = ward;
            document.getElementById('edit_district').value = district;
            document.getElementById('edit_province').value = province;
            
            const defaultCheckbox = document.getElementById('edit_default');
            defaultCheckbox.checked = isDefault;
            
            // If already default, disable turning it off manually to prevent 0 default addresses
            if (isDefault) {
                defaultCheckbox.disabled = true;
                // Add hidden input so value is still submitted
                if (!document.getElementById('edit_default_hidden')) {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'la_mac_dinh';
                    hidden.value = '1';
                    hidden.id = 'edit_default_hidden';
                    defaultCheckbox.parentNode.appendChild(hidden);
                }
            } else {
                defaultCheckbox.disabled = false;
                const hidden = document.getElementById('edit_default_hidden');
                if (hidden) hidden.remove();
            }
        });
    });
});
</script>
