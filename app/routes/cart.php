<?php

use \Microshop\Services\ProductService;
use \Microshop\Models\Product;
use \Microshop\Utils\Session;
use \Microshop\Services\CartService;
use \Microshop\Models\Cart;

// Start session
Session::start();

// Check if cart session already exists
if(Session::read("cart_session_key")) {
    // Get key from session
    $sessionKey = Session::read("cart_session_key");
} else {
    // Create session key
    // @todo move to util or global function to generate random keys
    $length = 64;
    $bytes = openssl_random_pseudo_bytes($length * 2);
    // Sanitize session key and limit to max characters
    $sessionKey = substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
    // Save key in session
    Session::write("cart_session_key", $sessionKey);
}
// Create new cart and pass session key
$cart = new Cart($sessionKey);

/**
 * Respond to /cart and /cart.json
 *
 * Show overview of cart items. Render view and json for frontend
 *
 * @param $req
 * @param $res
 * @param $service
 * @param $app
 */
$cartOverview = function($req, $res, $service, $app) use($cart) {
    // Get cart session key
    $sessionKey = Session::read("cart_session_key");
    // Create new cart service
    $cartService = new CartService($app->db);
    // Create new product service
    $productService = new ProductService($app->db);

    // Get cart items by session key
    // @todo should be handled using \Utils\CartCalculator I think
    if($cartItems = $cartService->findCartItemsBySessionKey($sessionKey)) {
        // Loop through cart items
        foreach ($cartItems as $cartItem) {
            // Save found product by product id
            $foundProduct = $productService->findByProductId($cartItem['product_id']);
            // If product not
            if(empty($foundProduct)) {
                // product disappeared from database, let purge it from cart haha
                Session::remove("cart_products", "id_".$cartItem['product_id'] );
                // Remove product if has been removed during session
                $cartService->removeProductFromCart($cartItem['product_id'], $sessionKey);
            } else {
                // Create new product object from found product data
                $product = new Product($foundProduct);
                // Add product to cart
                $cart->addProduct($product, $cartItem['quantity']);
            }
        }
        // Get total amount of products in shopping cart
        $cartTotalItemCount = $cartService->countTotalCartItemsBySessionKey($sessionKey);
        // Set total amount of product in cart
        $cart->setTotalCartItems($cartTotalItemCount['total_items']);
    }

    // If format (action) is json
    if($req->format == 'json') {
        // Create new array to be returned as json
        $toJson = [
            'total_items' => $cart->getTotalCartItems(),
            'products' => $cart->getProducts()
        ];
        // Serve json representation of cart
        $res->json($toJson);
    } else {
        // Save cart object in $service to pass it to view
        $service->cart = $cart;
        // Render cart overview view
        $service->render('./app/views/cart/overview.php');
    }

};
$this->respond("/?", $cartOverview);
$this->respond("/cart.[json:format]", $cartOverview);

/**
 * Respond to /cart/i/add and /cart/i/add.json where i is product id
 *
 * Serve (success) message for adding new product to cart, and add product to cart.
 */
$this->respond('GET', "/[i:id]/add.[json:format]?", function($req, $res, $service, $app) use($cart) {
    // Get cart session key
    $sessionKey = Session::read("cart_session_key");
    // Create new cart service
    $cartService = new CartService($app->db);
    // Create new product service
    $productService = new ProductService($app->db);

    // Save product by product id
    $product = $productService->findByProductId($req->id);

    if($product) {
        // Create new product object from found product data
        $product = new Product($product);
        // Add 1 product to cart
        // @todo set quantity from param
        $cart->addProduct($product, 1);

        // Save cart changes to db
        // @todo make persist method
        $cartService->saveProductToCart($product->getId(), $sessionKey);

        if($req->format == 'json') {
            // Json message on success
            $res->json(['success' => ['message' => 'Product successfully added to cart!']]);
        } else {
            // Redirect if request wasn't done to .json
            $res->redirect("/cart");
        }

    } else {
        // Return error message when product isn't found or doesn't exists
        $res->json(['error' => ['message' => 'Product not found']]);
    }
});

/**
 * Respond to /cart/i/delete and /cart/i/delete.json, where i is product id
 *
 * Serve (success) message for removing product from cart, and remove product from cart
 */
$this->respond('GET', "/[i:id]/delete.[json:format]?", function($req, $res, $service, $app) use($cart) {
    // Get cart session key
    $sessionKey = Session::read("cart_session_key");
    // Create new cart service
    $cartService = new CartService($app->db);

    try {
        // Remove item from cart by product id
        // Save left items in cart for this prouct in var
        // @todo consider using cart_id
        $itemsLeftInCart = $cart->removeProductById($req->id);
    } catch (\Exception $e) {
        // Exception is thrown when quantity is invalid
        // @todo implement CartException or something more related to this issue
        // Maybe even CartQuantityException. Idk
        // Return error as json resonse
        // @todo wrap with if else block to render nice view if request isn't json
        return $res->json(['error' => ['message' => $e->getMessage() ]] );
    }

    // If the removal was valid check if there are 0 left
    if($itemsLeftInCart === 0) {
        // Remove product from session and from persistent cart
        Session::remove("cart_products", "id_".$req->id);
        $cartService->removeProductFromCart($req->id, $sessionKey);
    }

    // If removal was valid and there are more then 0 items left
    if($itemsLeftInCart > 0) {
        // Update session cart products quantity and persist changes
        Session::add("cart_products", ["id_".$req->id => $itemsLeftInCart]);
        $cartService->substractProductFromCart($req->id, $sessionKey);
    }

    // Render json success message or redirect back to cart
    if($req->format == 'json') {
        $res->json(['success' => ['message' => 'Product successfully removed from to cart!', 'quantity' => $itemsLeftInCart]]);
    } else {
        $res->redirect('/cart');
    }
});

/**
 * Respond to /cart/add (POST)
 *
 * Serve (success) message for adding a product to cart
 */
$this->respond('POST', "/add", function($req, $res) use($cart) {
    $cart->addProductById($req->id); // TODO handle $req->amount n product item(s)
    // Respond with json message
    // @todo wrap in if/else block to render view
    $res->json(['success'=> ['message'=> 'Item added to cart!']]);
});

/**
 * Respond to /cart/bye
 *
 * Used for session debugging, kills all sessions and serves a success message in json
 */
$this->respond('GET', "/bye", function($req, $res) use($cart) {
    Session::destroy();

    $res->json(['success'=> ['message'=> 'Session destroyed, cart cleared']]);
});