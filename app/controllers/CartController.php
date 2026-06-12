<?php

namespace app\controllers;

use app\models\SanPhamModel;

class CartController
{
    private $sanPhamModel;

    public function __construct()
    {
        $this->sanPhamModel = new SanPhamModel();
    }

    public function index(): array
    {
        $cartItems = $_SESSION['cart'] ?? [];
        $totalPayment = 0;
        foreach ($cartItems as $item) {
            $totalPayment += $item['price'] * $item['qty'];
        }

        return [
            'title' => 'Giỏ hàng | Bảo Đạt Sport',
            'cartItems' => $cartItems,
            'totalPayment' => $totalPayment
        ];
    }

    public function add(): void
    {
        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $variationId = isset($_POST['variation_id']) && !empty($_POST['variation_id']) ? (int)$_POST['variation_id'] : null;
        $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
        if ($qty < 1) {
            $qty = 1;
        }

        $success = false;
        $message = 'Không thể thêm sản phẩm vào giỏ hàng!';

        if ($productId > 0) {
            // Fetch product detail for cart
            $details = $this->sanPhamModel->getCartItemDetails($productId, $variationId);
            
            if ($details) {
                $key = $productId . ($variationId ? '_' . $variationId : '_0');
                
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                if (isset($_SESSION['cart'][$key])) {
                    $_SESSION['cart'][$key]['qty'] += $qty;
                } else {
                    $_SESSION['cart'][$key] = [
                        'product_id' => $details['product_id'],
                        'variation_id' => $details['variation_id'],
                        'name' => $details['name'],
                        'image' => $details['image'],
                        'price' => $details['price'],
                        'qty' => $qty,
                        'attributes' => $details['attributes']
                    ];
                }
                $success = true;
                $message = 'Đã thêm "' . $details['name'] . '" vào giỏ hàng thành công!';
            }
        }

        // Check for AJAX parameter
        $isAjax = (isset($_POST['ajax']) && $_POST['ajax'] == 1) || (isset($_GET['ajax']) && $_GET['ajax'] == 1);

        if ($isAjax) {
            $cartItems = $_SESSION['cart'] ?? [];
            $totalCartItems = 0;
            foreach ($cartItems as $item) {
                $totalCartItems += $item['qty'];
            }

            ob_start();
            if (empty($cartItems)) {
                ?>
                <div class="list-card mb-3 text-center py-3">
                    <i class="fa-solid fa-bag-shopping fa-3x text-muted mb-2"></i>
                    <div class="text-muted">
                        Chưa có sản phẩm
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="mini-cart-list mb-3" style="max-height: 250px; overflow-y: auto;">
                    <?php 
                    $count = 0;
                    foreach ($cartItems as $item): 
                        if ($count++ >= 3) break;
                    ?>
                        <div class="d-flex align-items-center gap-2 mb-2 pb-2 border-bottom">
                            <img src="<?= htmlspecialchars(getProductImage($item['image'] ?? '')) ?>" alt="" style="width: 50px; height: 50px; object-fit: contain;">
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="text-truncate small fw-bold text-dark" style="display:block; max-width: 200px;"><?= htmlspecialchars($item['name']) ?></div>
                                <div class="text-muted small" style="font-size: 11px;"><?= htmlspecialchars($item['attributes'] ?? '') ?></div>
                                <div class="small text-danger"><?= htmlspecialchars($item['qty']) ?> x <?= htmlspecialchars(formatVND($item['price'])) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (count($cartItems) > 3): ?>
                        <div class="text-center text-muted small py-1">và <?= count($cartItems) - 3 ?> sản phẩm khác...</div>
                    <?php endif; ?>
                </div>
                <div class="d-flex gap-2">
                    <a href="?page=cart" class="btn btn-outline-dark btn-sm flex-grow-1 fw-semibold">Giỏ hàng</a>
                    <a href="?page=checkout" class="btn btn-dark btn-sm flex-grow-1 fw-semibold">Thanh toán</a>
                </div>
                <?php
            }
            $miniCartHtml = ob_get_clean();

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => $success,
                'message' => $message,
                'cart_count' => $totalCartItems,
                'mini_cart_html' => $miniCartHtml
            ]);
            exit;
        }

        // Redirect back to cart page
        header("Location: ?page=cart");
        exit;
    }

    public function update(): void
    {
        if (isset($_POST['qty']) && is_array($_POST['qty'])) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            foreach ($_POST['qty'] as $key => $qty) {
                $qty = (int)$qty;
                if (isset($_SESSION['cart'][$key])) {
                    if ($qty <= 0) {
                        unset($_SESSION['cart'][$key]);
                    } else {
                        $_SESSION['cart'][$key]['qty'] = $qty;
                    }
                }
            }
        }

        header("Location: ?page=cart");
        exit;
    }

    public function delete(): void
    {
        $key = $_GET['key'] ?? '';
        if (!empty($key) && isset($_SESSION['cart'][$key])) {
            unset($_SESSION['cart'][$key]);
        }

        header("Location: ?page=cart");
        exit;
    }
}
