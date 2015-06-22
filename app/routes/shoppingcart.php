<?php
use Microshop\Services\PhotoService;
use \Microshop\Utils\Session;
use \Microshop\Services\CartService;
use \Microshop\Models\Cart;
use \Microshop\Services\ProductService;
use \Microshop\Models\Product;

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
 * Respond to /cart
 *
 * Serve json cart data
 */
$this->respond("GET", "/?", function($req, $res, $service, $app) use ($cart) {
    // Get cart session key
    $sessionKey = Session::read("cart_session_key");
    // Create new cart service
    $cartService = new CartService($app->db);
    // Create new product service
    $productService = new ProductService($app->db);
    // Get photo service
    $photoService = new PhotoService($app->db);

    // Return array
    $productsInCart = [];

    // Get cart items by session key
    // @todo should be handled using \Utils\CartCalculator I think with extra photo service option
    if($cartItems = $cartService->findCartItemsBySessionKey($sessionKey)) {
        // Loop through cart items
        foreach ($cartItems as $cartItem) {
            // Save found product by product id
            $foundProduct = $productService->findByProductId($cartItem['product_id']);
            // If product not found
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

                // @todo update this part with CartCalculator object and add product photos optionally
                // Get product photos
                $productPhoto = $photoService->findById($product->getPhotoId());
                // Check if exits
                if(!empty($productPhoto)) {
                    // Save photo url
                    $photoUrl = '/' . $productPhoto['path'] . '/' . $productPhoto['name'];
                } else {
                    // Set placeholder url
                    // @todo create own placeholder image
                    $photoUrl = "//placehold.it/600x600";
                }
                // Save cart product in return array
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
        // Get total amount of products in shopping cart
        $cartTotalItemCount = $cartService->countTotalCartItemsBySessionKey($sessionKey);
        // Set total amount of product in cart
        $cart->setTotalCartItems($cartTotalItemCount['total_items']);
    }

    // Serve cart product as json
    $res->json($productsInCart);
});

/**
 * Serve /cart/totals
 *
 * Respond with json, serve summed total products in cart for menu badge frontend
 */
$this->respond("GET", "/totals", function($req, $res, $service, $app) use ($cart) {
    // Get cart session key
    // @todo handle error if not exists
    $sessionKey = Session::read("cart_session_key");
    // Get cart service
    $cartService = new CartService($app->db);
    // Respond with json
    $res->json($cartService->countTotalCartItemsBySessionKey($sessionKey));
});