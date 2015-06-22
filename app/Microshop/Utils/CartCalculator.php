<?php
namespace Microshop\Utils;


use Microshop\Models\Cart;
use Microshop\Models\Product;
use Microshop\Services\CartService;
use Microshop\Services\ProductService;

/**
 * Class CartCalculator
 *
 * Used to link CartService and Product service to fill shopping cart with detailed product info
 *
 * @package Microshop\Utils
 */
class CartCalculator {
    /**
     * @param CartService $cartService
     * @param ProductService $productService
     * @param $sessionKey
     */
    public function __construct(CartService $cartService, ProductService $productService, $sessionKey) {
        $this->cartService = $cartService;
        $this->productService = $productService;
        $this->sessionKey = $sessionKey;
    }

    /**
     * @return Cart
     */
    public function filledCart() {
        // Create new empty shopping cart
        $cart = new Cart();
        // Get cart items by session id
        if($cartItems = $this->cartService->findCartItemsBySessionKey($this->sessionKey)) {
            // Loop through each cart item to find more detailed product information
            foreach ($cartItems as $cartItem) {
                // Save found product
                $foundProduct = $this->productService->findByProductId($cartItem['product_id']);
                if(empty($foundProduct)) {
                    // product disappeared from database, let purge it from cart haha
                    Session::remove("cart_products", "id_".$cartItem['product_id'] );
                    // Remove product if has been removed during session
                    $this->cartService->removeProductFromCart($cartItem['product_id'], $this->sessionKey);
                } else {
                    // Create new product object from found product data
                    $product = new Product($foundProduct);
                    // Add product to cart
                    $cart->addProduct($product, $cartItem['quantity']);
                    // Update total cart value (in cents)
                    $cart->setTotalCartValue( ($cart->getTotalCartValue() + ($product->getPrice() * $cartItem['quantity'])) );
                }
            }
            // Get total amount of products in shopping cart
            $cartTotalItemCount = $this->cartService->countTotalCartItemsBySessionKey($this->sessionKey);
            // Set total amount of product in cart
            $cart->setTotalCartItems($cartTotalItemCount['total_items']);
        }
        // Return filled or empty cart
        return $cart;
    }
}