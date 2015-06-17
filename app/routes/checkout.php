<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 17/06/15
 * Time: 05:28
 */
use Microshop\Services\CartService;
use Microshop\Services\ProductService;
use Microshop\Utils\CartCalculator;
use Microshop\Utils\Session;
Session::start();

$this->respond("GET", "/?", function($req, $res, $service, $app) {
    if(isset($_COOKIE['user_id'])) {
        $sessionKey = Session::read('cart_session_key');

        if(!$sessionKey) throw new \Exception("No session key found!");

        $cartService = new CartService($app->db);
        $productService = new ProductService($app->db);
        $cartCalculator = new CartCalculator($cartService, $productService, $sessionKey);

        $cart = $cartCalculator->filledCart();
        $service->cart = $cart;
        $service->render("./app/views/checkout/overview.php");
    } else {
        $service->render("./app/views/checkout/signup_or_login.php");
    }

});