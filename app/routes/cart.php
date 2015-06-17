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

$cartOverview = function($req, $res, $service, $app) use($cart) {
    $sessionKey = Session::read("cart_session_key");
    $cartService = new CartService($app->db);
    $productService = new ProductService($app->db);

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
            }
        }
        $cartTotalItemCount = $cartService->countTotalCartItemsBySessionKey($sessionKey);
        $cart->setTotalCartItems($cartTotalItemCount['total_items']);
    }

    if($req->format == 'json') {
        $toJson = [
            'total_items' => $cart->getTotalCartItems(),
            'products' => $cart->getProducts()
        ];

        $res->json($toJson);
    } else {
        $service->cart = $cart;
        Session::dump();
        $service->render('./app/views/cart/overview.php');
    }

};

$this->respond("/?", $cartOverview);
$this->respond("/cart.[json:format]", $cartOverview);
$this->respond('GET', "/[i:id]/add.[json:format]?", function($req, $res, $service, $app) use($cart) {
    $sessionKey = Session::read("cart_session_key");

    $cartService = new CartService($app->db);
    $productService = new ProductService($app->db);

    $product = $productService->findByProductId($req->id);

    if($product) {
        $product = new Product($product);
        $cart->addProduct($product, 1); // todo set quantity from param

        $cartService->saveProductToCart($product->getId(), $sessionKey);
        if($req->format == 'json') {
            $res->json(['success' => ['message' => 'Product successfully added to cart!']]);
        } else {
            $res->redirect("/cart");
        }

    } else {
        $res->json(['error' => ['message' => 'Product not found']]);
    }
});

$this->respond('GET', "/[i:id]/delete.[json:format]?", function($req, $res, $service, $app) use($cart) {
    $sessionKey = Session::read("cart_session_key");

    $cartService = new CartService($app->db);

    try {
        $itemsLeftInCart = $cart->removeProductById($req->id);
    }catch (\Exception $e) {
        return $res->json(['error' => ['message' => $e->getMessage() ]] );
    }

    if($itemsLeftInCart === 0) {
        Session::remove("cart_products", "id_".$req->id);
        $cartService->removeProductFromCart($req->id, $sessionKey);
    }
    if($itemsLeftInCart > 0) {
        Session::add("cart_products", ["id_".$req->id => $itemsLeftInCart]);
        $cartService->substractProductFromCart($req->id, $sessionKey);
    }
    if($req->format == 'json') {
        $res->json(['success' => ['message' => 'Product successfully removed from to cart!', 'quantity' => $itemsLeftInCart]]);
    } else {
        $res->redirect('/cart');
    }
});
$this->respond('POST', "/add", function($req, $res) use($cart) {
    $cart->addProductById($req->id); // TODO handle $req->amount n product item(s)
    $res->json(['success'=> ['message'=> 'Item added to cart!']]);
});
$this->respond('GET', "/bye", function($req, $res) use($cart) {
    Session::destroy();

    $res->json(['success'=> ['message'=> 'Session destroyed, cart cleared']]);
});