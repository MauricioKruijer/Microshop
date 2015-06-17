<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 17/06/15
 * Time: 08:58
 */

namespace Microshop\Utils;


use Microshop\Models\Cart;
use Microshop\Models\Product;
use Microshop\Services\CartService;
use Microshop\Services\ProductService;

class CartCalculator {
    public function __construct(CartService $cartService, ProductService $productService, $sessionKey) {
        $this->cartService = $cartService;
        $this->productService = $productService;
        $this->sessionKey = $sessionKey;
    }

    public function filledCart() {
        $cart = new Cart();
        if($cartItems = $this->cartService->findCartItemsBySessionKey($this->sessionKey)) {
            foreach ($cartItems as $cartItem) {
                $foundProduct = $this->productService->findByProductId($cartItem['product_id']);
                if(empty($foundProduct)) {
                    // product disappeared from database, let purge it from cart haha
                    Session::remove("cart_products", "id_".$cartItem['product_id'] );
                    $this->cartService->removeProductFromCart($cartItem['product_id'], $this->sessionKey);
                } else {
                    $product = new Product($foundProduct);
                    $cart->addProduct($product, $cartItem['quantity']);
                    $cart->setTotalCartValue( ($cart->getTotalCartValue() + ($product->getPrice() * $cartItem['quantity'])) );
                }
            }
            $cartTotalItemCount = $this->cartService->countTotalCartItemsBySessionKey($this->sessionKey);
            $cart->setTotalCartItems($cartTotalItemCount['total_items']);
        }
        return $cart;
    }
}