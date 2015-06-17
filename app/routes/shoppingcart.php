<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 17/06/15
 * Time: 03:57
 */
use Microshop\Services\PhotoService;
use \Microshop\Utils\Session;
use \Microshop\Services\CartService;
use \Microshop\Models\Cart;
use \Microshop\Services\ProductService;
use \Microshop\Models\Product;

Session::start();
if(Session::read("cart_session_key")) {
    $sessionKey = Session::read("cart_session_key");
} else {
    $length = 64;
    $bytes = openssl_random_pseudo_bytes($length * 2);

    $sessionKey = substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
    Session::write("cart_session_key", $sessionKey);
}
$cart = new Cart($sessionKey);

$this->respond("GET", "/?", function($req, $res, $service, $app) use ($cart) {
    $sessionKey = Session::read("cart_session_key");
    $cartService = new CartService($app->db);
    $productService = new ProductService($app->db);
    $photoService = new PhotoService($app->db);

    $productsInCart = [];

    if($cartItems = $cartService->findCartItemsBySessionKey($sessionKey)) {
        foreach ($cartItems as $cartItem) {
            $foundProduct = $productService->findByProductId($cartItem['product_id']);
            if(empty($foundProduct)) {
                // product dissapeared from database, let purge it from cart haha
                Session::remove("cart_products", "id_".$cartItem['product_id'] );
                $cartService->removeProductFromCart($cartItem['product_id'], $sessionKey);
            } else {
                $product = new Product($foundProduct);
                $cart->addProduct($product, $cartItem['quantity']);
                $productPhoto = $photoService->findById($product->getPhotoId());

                if(!empty($productPhoto)) {
                    $photoUrl = '/' . $productPhoto['path'] . '/' . $productPhoto['name'];
                } else {
                    $photoUrl = "//placehold.it/600x600";
                }

                $productsInCart[] = [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'short_description' => $product->getShortDescription(),
                    'price' => $product->getPrice(),
                    'image' => $photoUrl,
                    'quantity' => $cartItem['quantity']
                ];
            }
        }
        $cartTotalItemCount = $cartService->countTotalCartItemsBySessionKey($sessionKey);
        $cart->setTotalCartItems($cartTotalItemCount['total_items']);
    }


    $res->json($productsInCart);

});
$this->respond("GET", "/totals", function($req, $res, $service, $app) use ($cart) {
    $sessionKey = Session::read("cart_session_key");
    $cartService = new CartService($app->db);

    $res->json($cartService->countTotalCartItemsBySessionKey($sessionKey));
});