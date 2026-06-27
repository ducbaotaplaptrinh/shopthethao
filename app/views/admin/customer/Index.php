<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">Khách hàng & Hạng thành viên</h2>
    <div class="text-muted" style="font-size: 13px;">Tổng: <strong><?= count($customers) ?></strong> khách hàng</div>
</div>

<!-- Hạng thành viên Reference -->
<div class="row g-3 mb-4">
    <?php foreach ($tiers as $tier): ?>
        <div class="col-6 col-md-3">
            <div class="admin-card mb-0 text-center py-3" style="border-top: 3px solid <?= htmlspecialchars($tier['mau_sac']) ?>;">
                <i class="bi <?= htmlspecialchars($tier['bieu_tuong']) ?> fs-3 mb-1 d-block" style="color: <?= htmlspecialchars($tier['mau_sac']) ?>"></i>
                <div class="fw-bold"><?= htmlspecialchars($tier['ten_hang']) ?></div>
                <div class="text-muted" style="font-size: 12px;">
                    từ <?= number_format($tier['muc_chi_tieu_toi_thieu'], 0, ',', '.') ?> đ
                </div>
                <div class="mt-1">
                    <span class="badge text-dark" style="background-color: <?= $tier['mau_sac'] ?>22; border: 1px solid <?= $tier['mau_sac'] ?>55; color: <?= $tier['mau_sac'] ?> !important;">
                        Giảm <?= $tier['phan_tram_giam_gia'] ?>%
                    </span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Khách hàng</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Hạng</th>
                    <th>Tổng chi tiêu</th>
                    <th>Đơn hàng</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $c): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar rounded-circle d-flex align-items-center justify-content-center bg-warning text-white fw-bold" 
                                     style="width: 35px; height: 35px; font-size: 0.85rem; flex-shrink: 0; <?= !empty($c['anh_dai_dien']) ? "background-image: url('" . htmlspecialchars($c['anh_dai_dien']) . "'); background-size: cover; background-position: center;" : '' ?>">
                                    <?= empty($c['anh_dai_dien']) ? htmlspecialchars(mb_substr($c['ho_ten'], 0, 1)) : '' ?>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($c['ho_ten']) ?></div>
                                    <small class="text-muted">ID: #<?= $c['id'] ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['so_dien_thoai'] ?? 'N/A') ?></td>
                        <td>
                            <?php if (!empty($c['ten_hang'])): ?>
                                <span class="badge fw-bold px-2 py-1" style="background-color: <?= $c['mau_sac'] ?>22; border: 1px solid <?= $c['mau_sac'] ?>55; color: <?= $c['mau_sac'] ?> !important;">
                                    <i class="bi <?= htmlspecialchars($c['bieu_tuong'] ?? 'bi-star') ?> me-1"></i>
                                    <?= htmlspecialchars($c['ten_hang']) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold" style="color: var(--primary);">
                            <?= number_format($c['tong_chi_tieu'] ?? 0, 0, ',', '.') ?> đ
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border"><?= $c['so_don_hang'] ?> đơn</span>
                        </td>
                        <td>
                            <?php if ($c['trang_thai'] == 1): ?>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Hoạt động</span>
                            <?php else: ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Đã khóa</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <form action="?page=admin-customer-toggle" method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                <input type="hidden" name="trang_thai" value="<?= $c['trang_thai'] == 1 ? 0 : 1 ?>">
                                <button type="submit" class="btn btn-sm btn-light <?= $c['trang_thai'] == 1 ? 'text-danger' : 'text-success' ?>">
                                    <?php if ($c['trang_thai'] == 1): ?>
                                        <i class="bi bi-lock"></i> Khóa
                                    <?php else: ?>
                                        <i class="bi bi-unlock"></i> Mở
                                    <?php endif; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-3"></i>
                            Chưa có khách hàng nào
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>