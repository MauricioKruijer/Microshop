<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 14/06/15
 * Time: 00:33
 */
use \Microshop\Services\ProductService;
use \Microshop\Models\Product;
use \Microshop\Utils\Session;
use \Microshop\Services\CartService;
use \Microshop\Models\Cart;



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

$this->respond("/?", function($req, $res, $service, $app) use($cart) {
    $sessionKey = Session::read("cart_session_key");
//    $res->send("h")

//    $productService = new ProductService($app->db);

//    var_dump($cart->getProducts());
//    var_dump($cart->getProductsList());
//    \Microshop\Utils\Session::dump();
//    return "Hey hallo cart";
//    foreach($cart->getProducts() as $productId => $productQuantity) {
//        var_dump($productId, $productQuantity);
//        $product = new Product($productService->findByProductId($productId));
//        $cart->addProduct($product);
//    }
    $cartService = new CartService($app->db);
    $productService = new ProductService($app->db);

//    $cart = new Cart();
    if($cartItems = $cartService->findCartItemsBySessionKey($sessionKey)) {
        foreach ($cartItems as $cartItem) {
            $producttmp = $productService->findByProductId($cartItem['product_id']);
            $product = new Product($producttmp);

            $cart->addProduct($product);
        }
        $cartTotalItemCount = $cartService->countTotalCartItemsBySessionKey($sessionKey);
        $cart->setTotalCartItems($cartTotalItemCount['total_items']);
    }
    $service->cart = $cart;


//    $service->products = [];
    Session::dump();
//    Session::destroy();
    $service->render('./app/views/cart/overview.php');
});
// No JS fallback
$this->respond('GET', "/[i:id]/add", function($req, $res, $service, $app) use($cart) {
    $sessionKey = Session::read("cart_session_key");
//    $cart->addProductById($req->id);
    $cartService = new CartService($app->db);
    $productService = new ProductService($app->db);
    $product = $productService->findByProductId($req->id);
    if($product) {
        $product = new Product($product);
        $cart->addProduct($product, true);

        $cartService->saveProductToCart($product->getId(), $sessionKey);
        $res->json(['success' => ['message' => 'Product successfully added to cart!']]);
    }

//    $res->redirect("/cart");
});
$this->respond('POST', "/add", function($req, $res) use($cart) {
    $cart->addProductById($req->id);
    // TODO handle $req->amount n product item(s)
    $res->json(['success'=> ['message'=> 'Item added to cart!']]);
});
$this->respond('GET', "/bye", function($req, $res) use($cart) {
    Session::destroy();
    $res->json(['success'=> ['message'=> 'Item added to cart!']]);
});