<?php
// PHP helpers
$basePrice = $sanpham->getGia_ban();
$promoPrice = $sanpham->getGia_khuyen_mai();
$isSale = $promoPrice > 0;
$mainImage = getProductImage("assets/images/products/" . $sanpham->getAnh_dai_dien());
?>

<div class="container-xl py-4">
    <!-- Breadcrumb -->
    <div class="breadcrumb-wrapper mb-4">
        <a href="?page=home">Trang chủ ></a>
        <a href="?page=product-index&category=<?= htmlspecialchars($sanpham->getMa_danh_muc()) ?>"> <?= htmlspecialchars($tenDanhMuc ?? 'Danh mục') ?> ></a>
        <a href="#!"> <?= htmlspecialchars($tenThuongHieu ?? 'Thương hiệu') ?> ></a>
        <a href="#!" class="text-dark fw-bold"> <?= htmlspecialchars($sanpham->getTen_san_pham()) ?> </a>
    </div>

    <!-- Main Detail Row -->
    <div class="product-detail-container">
        <div class="row g-5">
            <!-- Left: Gallery -->
            <div class="col-lg-5">
                <div class="detail-gallery">
                    <!-- Main Image Frame -->
                    <div class="detail-gallery__main" id="mainImageFrame">
                        <?php if ($isSale): ?>
                            <span class="product-badge">-<?= $sanpham->getPhanTramGiam() ?>%</span>
                        <?php endif; ?>
                        <img id="productMainImg" src="<?= htmlspecialchars($mainImage) ?>" alt="<?= htmlspecialchars("assets/images/products/" . $sanpham->getTen_san_pham()) ?>" onerror="handleImageError(this)">
                    </div>

                    <!-- Thumbnails List -->
                    <div class="detail-gallery__thumbs">
                        <!-- Main Image as First Thumbnail -->
                        <div class="detail-gallery__thumb active" onclick="switchMainImage(this, '<?= htmlspecialchars($mainImage) ?>')">
                            <img src="<?= htmlspecialchars($mainImage) ?>" alt="Main Thumbnail" onerror="handleImageError(this)">
                        </div>

                        <!-- Gallery Images from DB -->
                        <?php if (!empty($gallery)): ?>
                            <?php foreach ($gallery as $img): ?>
                                <?php $galImg = getProductImage("assets/images/products/" . $img['duong_dan_anh']); ?>
                                <div class="detail-gallery__thumb" onclick="switchMainImage(this, '<?= htmlspecialchars($galImg) ?>')">
                                    <img src="<?= htmlspecialchars($galImg) ?>" alt="Gallery Image" onerror="handleImageError(this)">
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right: Info Panel -->
            <div class="col-lg-7">
                <h1 class="product-title d-flex align-items-center gap-2">
                    <?= htmlspecialchars(($tenDanhMuc === 'Vợt cầu lông') ? 'Vợt cầu lông ' . $sanpham->getTen_san_pham() : $sanpham->getTen_san_pham()) ?>
                    <?php if ($sanpham->isNew()): ?>
                        <span class="tagnew p-2">
                            new
                        </span>
                    <?php endif; ?>
                </h1>

                <div class="product-meta">
                    <span>Thương hiệu: <strong><?= htmlspecialchars($tenThuongHieu ?? 'Chính hãng') ?></strong></span>
                    <span>Danh mục: <strong><?= htmlspecialchars($tenDanhMuc ?? 'Thể thao') ?></strong></span>
                    <span>Mã sản phẩm: <strong id="displaySKU"><?= htmlspecialchars($sanpham->getMa_vach_sku() ?? 'Đang cập nhật') ?></strong></span>
                    <span>Lượt xem: <strong><?= htmlspecialchars($sanpham->getLuot_xem()) ?></strong></span>
                </div>

                <!-- Price Box -->
                <div class="detail-price-box">
                    <?php if ($isSale): ?>
                        <span class="detail-price-new" id="displayPriceNew"><?= htmlspecialchars(formatVND($promoPrice)) ?></span>
                        <span class="detail-price-old" id="displayPriceOld"><?= htmlspecialchars(formatVND($basePrice)) ?></span>
                        <span class="detail-price-badge" id="displayDiscountBadge">-<?= $sanpham->getPhanTramGiam() ?>%</span>
                    <?php else: ?>
                        <span class="detail-price-new" id="displayPriceNew"><?= htmlspecialchars(formatVND($basePrice)) ?></span>
                        <span class="detail-price-old d-none" id="displayPriceOld"></span>
                        <span class="detail-price-badge d-none" id="displayDiscountBadge"></span>
                    <?php endif; ?>
                </div>

                <!-- Short Desc -->
                <div class="product-short-desc">
                    <?= !empty($sanpham->getMo_na_ngan()) ? nl2br(htmlspecialchars($sanpham->getMo_na_ngan())) : 'Sản phẩm cầu lông chính hãng phân phối chất lượng cao, bảo đảm độ bền kéo lực căng tối ưu cho vận động viên.' ?>
                </div>

                <!-- Variations Form -->
                <div class="variant-section">
                    <?php if (!empty($uniqueAttributes)): ?>
                        <?php foreach ($uniqueAttributes as $name => $values): ?>
                            <div class="variant-group" data-attr-name="<?= htmlspecialchars($name) ?>">
                                <div class="variant-label"><?= htmlspecialchars($name) ?>:</div>
                                <div class="variant-options">
                                    <?php $isFirst = true; ?>
                                    <?php foreach ($values as $valId => $val): ?>
                                        <button type="button" class="variant-btn <?= $isFirst ? 'active' : '' ?>" data-val-id="<?= htmlspecialchars($valId) ?>" onclick="selectVariantOption(this, '<?= htmlspecialchars($name) ?>', '<?= htmlspecialchars($valId) ?>')">
                                            <?= htmlspecialchars($val) ?>
                                        </button>
                                        <?php $isFirst = false; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Buy Actions -->
                <div class="buy-action-box flex-column align-items-start">
                    <div class="d-flex align-items-center gap-3 flex-wrap w-100">
                        <div class="quantity-control">
                            <button type="button" class="quantity-btn" onclick="updateQuantity(-1)">-</button>
                            <input type="number" class="quantity-input" id="qtyInput" value="1" min="1" style="width: 65px; border: none; text-align: center; font-weight: 600; outline: none;">
                            <button type="button" class="quantity-btn" onclick="updateQuantity(1)">+</button>
                        </div>

                        <span class="stock-status-text" id="displayStockText">
                            Còn lại: <strong id="displayStockCount"><?= htmlspecialchars($sanpham->getSo_luong_ton()) ?></strong> sản phẩm
                        </span>

                        <div class="w-100 d-block d-md-none my-2"></div>

                        <button type="button" class="btn-add-cart" id="btnAddToCart" onclick="addToCartClick()">
                            <i class="bi bi-cart3"></i> Thêm vào giỏ hàng
                        </button>

                        <button type="button" class="btn-buy-now" id="btnBuyNow" onclick="buyNowClick()">
                            Mua ngay
                        </button>
                    </div>
                    <div id="qtyError" class="text-danger small mt-2 fw-semibold" style="display: none;"></div>

                    <!-- Form thông báo hết hàng -->
                    <div id="outOfStockFormBox" style="display: none;" class="mt-3 p-3 border rounded bg-light w-100">
                        <h6 class="text-danger fw-bold mb-1"><i class="bi bi-bell-fill"></i> Đăng ký nhận thông báo khi có hàng</h6>
                        <p class="small text-muted mb-2">Sản phẩm này hiện đang hết hàng. Hãy để lại email để chúng tôi thông báo cho bạn ngay khi có hàng trở lại.</p>
                        <form id="frmNotifyStock" onsubmit="submitNotifyStock(event)">
                            <div class="input-group">
                                <input type="email" class="form-control" id="notifyEmail" placeholder="Nhập email của bạn..." required style="height: 38px;">
                                <button class="btn btn-danger text-white px-3" type="submit" id="btnNotifySubmit" style="font-weight: 600;">Đăng ký</button>
                            </div>
                            <div id="notifyMessage" class="small mt-2 fw-semibold" style="display: none;"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Description & Spec & Reviews Tabs -->
    <div class="tabs-container">
        <div class="detail-tabs-nav">
            <button class="detail-tab-btn active" onclick="switchTab(this, 'tab-desc')">Mô tả chi tiết</button>
            <button class="detail-tab-btn" onclick="switchTab(this, 'tab-specs')">Thông số kỹ thuật</button>
            <button class="detail-tab-btn" onclick="switchTab(this, 'tab-reviews')">Đánh giá khách hàng (<?= count($reviews) ?>)</button>
        </div>

        <!-- Tab contents -->
        <div class="detail-tabs-content">
            <!-- Description Tab -->
            <div class="tab-pane active" id="tab-desc">
                <?php if (!empty($sanpham->getMo_ta_chi_tiet())): ?>
                    <div class="rich-text-content">
                        <?= $sanpham->getMo_ta_chi_tiet() ?>
                    </div>
                <?php else: ?>
                    <p>Sản phẩm thể thao cao cấp chính hãng, được hoàn thiện tỉ mỉ bằng các sợi carbon cao cấp, mang lại hiệu năng thi đấu chuyên nghiệp, bền bỉ và kiểm soát đường cầu tối đa.</p>
                <?php endif; ?>
            </div>

            <!-- Specs Tab -->
            <div class="tab-pane" id="tab-specs">
                <table>
                    <tbody>
                        <tr>
                            <th>Thương hiệu</th>
                            <td><?= htmlspecialchars($tenThuongHieu ?? 'Đang cập nhật') ?></td>
                        </tr>
                        <tr>
                            <th>Danh mục</th>
                            <td><?= htmlspecialchars($tenDanhMuc ?? 'Đang cập nhật') ?></td>
                        </tr>
                        <tr>
                            <th>Mã SKU sản phẩm</th>
                            <td><?= htmlspecialchars($sanpham->getMa_vach_sku() ?? 'Đang cập nhật') ?></td>
                        </tr>
                        <tr>
                            <th>Trọng lượng</th>
                            <td><?= htmlspecialchars($sanpham->getTrong_luong() > 0 ? $sanpham->getTrong_luong() . ' g' : 'Đang cập nhật') ?></td>
                        </tr>
                        <tr>
                            <th>Trạng thái tồn kho</th>
                            <td id="specStockText"><?= $sanpham->getSo_luong_ton() > 0 ? 'Còn hàng (' . $sanpham->getSo_luong_ton() . ' sản phẩm)' : 'Hết hàng' ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane" id="tab-reviews">
                <div class="reviews-section">
                    <?php if (!empty($reviews)): ?>
                        <?php
                        $totalScore = 0;
                        foreach ($reviews as $rv) {
                            $totalScore += $rv['diem_so'];
                        }
                        $avgScore = round($totalScore / count($reviews), 1);
                        ?>
                        <div class="reviews-summary">
                            <div class="reviews-summary__score">
                                <h3><?= $avgScore ?></h3>
                                <div class="rating-stars">
                                    <?php for ($s = 1; $s <= 5; $s++): ?>
                                        <i class="bi <?= ($s <= round($avgScore)) ? 'bi-star-fill' : 'bi-star' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <div class="text-muted small mt-1">Đánh giá trung bình</div>
                            </div>
                            <div>
                                <h5 class="mb-1">Cộng đồng Bảo Đạt Sport</h5>
                                <p class="text-muted mb-0 small">Nhận xét thực tế từ khách hàng đã mua và trải nghiệm sản phẩm này.</p>
                            </div>
                        </div>

                        <!-- Reviews list -->
                        <div class="reviews-list mt-4">
                            <?php foreach ($reviews as $rv): ?>
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="review-user">
                                            <div class="review-avatar" style="<?= !empty($rv['anh_dai_dien']) ? "background-image: url('assets/images/" . htmlspecialchars($rv['anh_dai_dien']) . "')" : 'background-color: #ff7b00;' ?>">
                                                <?= empty($rv['anh_dai_dien']) ? htmlspecialchars(mb_substr($rv['ho_ten'], 0, 1)) : '' ?>
                                            </div>
                                            <div>
                                                <div class="review-name"><?= htmlspecialchars($rv['ho_ten']) ?></div>
                                                <div class="rating-stars">
                                                    <?php for ($s = 1; $s <= 5; $s++): ?>
                                                        <i class="bi <?= ($s <= $rv['diem_so']) ? 'bi-star-fill' : 'bi-star' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="review-date"><?= date('d/m/Y H:i', strtotime($rv['ngay_tao'])) ?></span>
                                    </div>
                                    <div class="review-comment mt-2">
                                        <?= nl2br(htmlspecialchars($rv['binh_luan'])) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-chat-left-text text-muted" style="font-size: 2.5rem;"></i>
                            <h5 class="text-muted mt-3">Chưa có đánh giá nào cho sản phẩm này</h5>
                            <p class="text-muted small">Hãy là người mua hàng đầu tiên và đưa ra nhận xét trải nghiệm sản phẩm nhé.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
        <section class="related-products mt-5">
            <h3 class="related-section-title">Sản phẩm liên quan</h3>
            <div class="row g-3 mt-3">
                <?php
                // Temporarily inject $relatedProducts into listproduct.php context
                $danhSachSanPham = $relatedProducts;
                require BASE_PATH . "/app/views/components/product/ListProduct.php";
                ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<!-- Variation Selection Frontend logic -->
<script>
    // Injected variables from PHP
    const productVariations = <?php echo json_encode($variations); ?>;
    const defaultProductPrice = <?= $basePrice ?>;
    const defaultProductPromoPrice = <?= $promoPrice ?>;
    const defaultProductStock = <?= $sanpham->getSo_luong_ton() ?>;
    const defaultProductSKU = "<?= htmlspecialchars($sanpham->getMa_vach_sku() ?? '') ?>";
    const defaultProductImage = "<?= htmlspecialchars($mainImage) ?>";

    // Track active selection
    let selectedOptions = {};
    let activeVariation = null;

    document.addEventListener("DOMContentLoaded", function() {
        // Initialize active selections
        document.querySelectorAll(".variant-group").forEach(group => {
            const attrName = group.getAttribute("data-attr-name");
            const activeBtn = group.querySelector(".variant-btn.active");
            if (activeBtn) {
                selectedOptions[attrName] = activeBtn.getAttribute("data-val-id");
            }
        });

        checkCurrentVariation();

        // Ràng buộc nhập số lượng và validate
        const qtyInput = document.getElementById("qtyInput");
        if (qtyInput) {
            qtyInput.addEventListener("input", kiemTraSoLuong);
            qtyInput.addEventListener("change", kiemTraSoLuong);
            qtyInput.addEventListener("keydown", function(e) {
                if ([".", ",", "-", "+", "e", "E"].includes(e.key)) {
                    e.preventDefault();
                }
            });
        }
    });

    function selectVariantOption(btn, attrName, valId) {
        // Remove active class from sibling buttons in this group
        const group = btn.closest(".variant-group");
        group.querySelectorAll(".variant-btn").forEach(b => b.classList.remove("active"));

        // Make this active
        btn.classList.add("active");
        selectedOptions[attrName] = valId;

        // Check variation match
        checkCurrentVariation();
    }

    function checkCurrentVariation() {
        const priceNewEl = document.getElementById("displayPriceNew");
        const priceOldEl = document.getElementById("displayPriceOld");
        const badgeEl = document.getElementById("displayDiscountBadge");
        const stockCountEl = document.getElementById("displayStockCount");
        const specStockEl = document.getElementById("specStockText");
        const skuEl = document.getElementById("displaySKU");
        const mainImgEl = document.getElementById("productMainImg");
        const btnCart = document.getElementById("btnAddToCart");
        const btnBuy = document.getElementById("btnBuyNow");
        const formBox = document.getElementById("outOfStockFormBox");

        if (productVariations.length > 0) {
            // Find variation where all selected options match
            activeVariation = productVariations.find(bt => {
                return bt.attributes.every(attr => {
                    return selectedOptions[attr.ten_thuoc_tinh] && selectedOptions[attr.ten_thuoc_tinh] == attr.gia_tri_id;
                });
            });

            if (activeVariation) {
                // Variation match found!
                skuEl.textContent = activeVariation.ma_vach_sku ? activeVariation.ma_vach_sku : defaultProductSKU;

                // Stock
                const stock = parseInt(activeVariation.so_luong_ton);
                stockCountEl.textContent = stock;
                specStockEl.textContent = stock > 0 ? "Còn hàng (" + stock + " sản phẩm)" : "Hết hàng";

                if (stock <= 0) {
                    btnCart.disabled = true;
                    btnCart.innerHTML = "Hết hàng";
                    btnBuy.disabled = true;
                    if (formBox) formBox.style.display = "block";
                } else {
                    btnCart.disabled = false;
                    btnCart.innerHTML = '<i class="bi bi-cart3"></i> Thêm vào giỏ hàng';
                    btnBuy.disabled = false;
                    if (formBox) formBox.style.display = "none";
                }

                // Price
                let currentPrice = activeVariation.gia_ban_rieng ? parseFloat(activeVariation.gia_ban_rieng) : defaultProductPrice;
                // Calculate promotional price if default product is on sale
                if (defaultProductPromoPrice > 0) {
                    let discountRate = (defaultProductPrice - defaultProductPromoPrice) / defaultProductPrice;
                    let currentPromoPrice = Math.round(currentPrice * (1 - discountRate));
                    priceNewEl.textContent = formatVND(currentPromoPrice);
                    priceOldEl.textContent = formatVND(currentPrice);
                    priceOldEl.classList.remove("d-none");
                    badgeEl.classList.remove("d-none");
                } else {
                    priceNewEl.textContent = formatVND(currentPrice);
                    priceOldEl.classList.add("d-none");
                    badgeEl.classList.add("d-none");
                }

                // Image
                if (activeVariation.anh_rieng) {
                    let varImg = activeVariation.anh_rieng;
                    if (!varImg.startsWith("assets/")) {
                        varImg = "assets/images/" + varImg;
                    }
                    mainImgEl.src = varImg;
                } else {
                    mainImgEl.src = defaultProductImage;
                }

                // Reset quantity to 1 if it exceeds stock
                const qtyInput = document.getElementById("qtyInput");
                if (parseInt(qtyInput.value) > stock && stock > 0) {
                    qtyInput.value = 1;
                }
            } else {
                // No matching variation found
                skuEl.textContent = defaultProductSKU;
                stockCountEl.textContent = defaultProductStock;
                specStockEl.textContent = defaultProductStock > 0 ? "Còn hàng" : "Hết hàng";
                mainImgEl.src = defaultProductImage;

                if (defaultProductPromoPrice > 0) {
                    priceNewEl.textContent = formatVND(defaultProductPromoPrice);
                    priceOldEl.textContent = formatVND(defaultProductPrice);
                    priceOldEl.classList.remove("d-none");
                    badgeEl.classList.remove("d-none");
                } else {
                    priceNewEl.textContent = formatVND(defaultProductPrice);
                    priceOldEl.classList.add("d-none");
                    badgeEl.classList.add("d-none");
                }

                if (defaultProductStock <= 0) {
                    btnCart.disabled = true;
                    btnCart.innerHTML = "Hết hàng";
                    btnBuy.disabled = true;
                    if (formBox) formBox.style.display = "block";
                } else {
                    btnCart.disabled = false;
                    btnCart.innerHTML = '<i class="bi bi-cart3"></i> Thêm vào giỏ hàng';
                    btnBuy.disabled = false;
                    if (formBox) formBox.style.display = "none";
                }
            }
        } else {
            // No variations at all
            skuEl.textContent = defaultProductSKU;
            stockCountEl.textContent = defaultProductStock;
            specStockEl.textContent = defaultProductStock > 0 ? "Còn hàng (" + defaultProductStock + " sản phẩm)" : "Hết hàng";

            if (defaultProductStock <= 0) {
                btnCart.disabled = true;
                btnCart.innerHTML = "Hết hàng";
                btnBuy.disabled = true;
                if (formBox) formBox.style.display = "block";
            } else {
                btnCart.disabled = false;
                btnCart.innerHTML = '<i class="bi bi-cart3"></i> Thêm vào giỏ hàng';
                btnBuy.disabled = false;
                if (formBox) formBox.style.display = "none";
            }
        }
        kiemTraSoLuong();
    }

    // Helper VND formatter
    function formatVND(price) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'decimal'
        }).format(price) + ' ₫';
    }

    // switch image main
    function switchMainImage(thumb, url) {
        // Update main image src
        document.getElementById("productMainImg").src = url;

        // Toggle thumbnail active class
        document.querySelectorAll(".detail-gallery__thumb").forEach(t => t.classList.remove("active"));
        thumb.classList.add("active");
    }

    // Switch Description tabs
    function switchTab(btn, tabId) {
        // Toggle tab navigation active
        document.querySelectorAll(".detail-tab-btn").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");

        // Toggle tab content active
        document.querySelectorAll(".tab-pane").forEach(tp => tp.classList.remove("active"));
        document.getElementById(tabId).classList.add("active");
    }

    // Quantity update
    function updateQuantity(amount) {
        const qtyInput = document.getElementById("qtyInput");
        let currentQty = parseInt(qtyInput.value) || 0;
        let maxStock = activeVariation ? parseInt(activeVariation.so_luong_ton) : defaultProductStock;

        let newQty = currentQty + amount;
        if (newQty < 1) newQty = 1;
        if (newQty > maxStock && maxStock > 0) newQty = maxStock;

        qtyInput.value = newQty;
        kiemTraSoLuong();
    }

    // Hàm kiểm tra số lượng mua bằng tiếng Việt dễ hiểu
    function kiemTraSoLuong() {
        const qtyInput = document.getElementById("qtyInput");
        const qtyError = document.getElementById("qtyError");
        const btnCart = document.getElementById("btnAddToCart");
        const btnBuy = document.getElementById("btnBuyNow");

        if (!qtyInput) return;

        let maxStock = activeVariation ? parseInt(activeVariation.so_luong_ton) : defaultProductStock;
        let valStr = qtyInput.value;

        // Nếu sản phẩm hết hàng
        if (maxStock <= 0) {
            qtyError.textContent = "Sản phẩm hiện đang hết hàng!";
            qtyError.style.display = "block";
            btnCart.disabled = true;
            btnCart.innerHTML = "Hết hàng";
            btnBuy.disabled = true;
            return;
        }

        // Khôi phục chữ mặc định của nút thêm giỏ hàng
        if (btnCart.innerHTML === "Hết hàng") {
            btnCart.innerHTML = '<i class="bi bi-cart3"></i> Thêm vào giỏ hàng';
        }

        if (valStr.trim() === "") {
            qtyError.textContent = "Vui lòng nhập số lượng!";
            qtyError.style.display = "block";
            btnCart.disabled = true;
            btnBuy.disabled = true;
            return;
        }

        let qty = parseInt(valStr);
        if (isNaN(qty) || qty <= 0) {
            qtyError.textContent = "Số lượng mua phải là số nguyên dương!";
            qtyError.style.display = "block";
            btnCart.disabled = true;
            btnBuy.disabled = true;
            return;
        }

        if (qty > maxStock) {
            qtyError.textContent = "Số lượng vượt quá tồn kho (Tối đa: " + maxStock + ")!";
            qtyError.style.display = "block";
            btnCart.disabled = true;
            btnBuy.disabled = true;
            return;
        }

        // Hợp lệ
        qtyError.style.display = "none";
        btnCart.disabled = false;
        btnBuy.disabled = false;
    }

    // Add To Cart and Buy actions
    function addToCartClick() {
        const qty = document.getElementById("qtyInput").value;
        const variationId = activeVariation ? activeVariation.id : '';
        const productId = <?= $sanpham->getId() ?>;

        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('variation_id', variationId);
        formData.append('qty', qty);
        formData.append('ajax', 1);

        const btnCart = document.getElementById("btnAddToCart");
        const originalHtml = btnCart.innerHTML;
        btnCart.disabled = true;
        btnCart.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...';

        fetch('?page=cart-add', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btnCart.disabled = false;
                btnCart.innerHTML = originalHtml;

                if (data.success) {
                    // Update header cart count badge
                    const cartCountBadge = document.getElementById('cartCount');
                    if (cartCountBadge) {
                        cartCountBadge.textContent = data.cart_count;
                    }

                    // Update mini-cart dropdown html
                    const miniCartDropdown = document.querySelector('.dropdown-menu.cart');
                    if (miniCartDropdown && data.mini_cart_html) {
                        miniCartDropdown.innerHTML = data.mini_cart_html;
                    }

                    // Show Toast Notification
                    const toastMessageEl = document.getElementById('cartToastMessage');
                    if (toastMessageEl) {
                        toastMessageEl.textContent = data.message;
                    }

                    const toastEl = document.getElementById('cartToast');
                    if (toastEl) {
                        const toast = new bootstrap.Toast(toastEl);
                        toast.show();
                    }
                } else {
                    alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại.');
                }
            })
            .catch(err => {
                btnCart.disabled = false;
                btnCart.innerHTML = originalHtml;
                console.error('Error adding to cart:', err);
                alert('Không thể kết nối đến máy chủ.');
            });
    }

    function buyNowClick() {
        const qty = document.getElementById("qtyInput").value;
        const variationId = activeVariation ? activeVariation.id : '';
        const productId = <?= $sanpham->getId() ?>;

        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('variation_id', variationId);
        formData.append('qty', qty);
        formData.append('ajax', 1);

        const btnBuy = document.getElementById("btnBuyNow");
        const originalText = btnBuy.innerText;
        btnBuy.disabled = true;
        btnBuy.innerText = 'Đang xử lý...';

        fetch('?page=cart-add', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btnBuy.disabled = false;
                btnBuy.innerText = originalText;

                if (data.success) {
                    // Redirect immediately to checkout with the buy_now param
                    window.location.href = '?page=checkout&buy_now=' + encodeURIComponent(data.key);
                } else {
                    alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại.');
                }
            })
            .catch(err => {
                btnBuy.disabled = false;
                btnBuy.innerText = originalText;
                console.error('Error buying now:', err);
                alert('Không thể kết nối đến máy chủ.');
            });
    }

    function submitNotifyStock(event) {
        event.preventDefault();
        const email = document.getElementById("notifyEmail").value;
        const productId = <?= $sanpham->getId() ?>;
        const variationId = activeVariation ? activeVariation.id : '';

        const btnSubmit = document.getElementById("btnNotifySubmit");
        const msgEl = document.getElementById("notifyMessage");

        btnSubmit.disabled = true;
        btnSubmit.innerText = "Đang xử lý...";
        msgEl.style.display = "none";

        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('variation_id', variationId);
        formData.append('email', email);

        fetch('?page=notify-out-of-stock', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btnSubmit.disabled = false;
                btnSubmit.innerText = "Đăng ký";
                msgEl.textContent = data.message;
                msgEl.style.display = "block";
                if (data.success) {
                    msgEl.className = "small mt-2 fw-semibold text-success";
                    document.getElementById("notifyEmail").value = "";
                } else {
                    msgEl.className = "small mt-2 fw-semibold text-danger";
                }
            })
            .catch(err => {
                btnSubmit.disabled = false;
                btnSubmit.innerText = "Đăng ký";
                msgEl.textContent = "Không thể kết nối đến máy chủ.";
                msgEl.className = "small mt-2 fw-semibold text-danger";
                msgEl.style.display = "block";
                console.error('Error:', err);
            });
    }
</script>