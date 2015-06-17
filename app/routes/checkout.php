<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 17/06/15
 * Time: 05:28
 */
use Microshop\Models\Checkout;
use Microshop\Models\User;
use Microshop\Services\BillingService;
use Microshop\Services\CartService;
use Microshop\Services\CheckoutService;
use Microshop\Services\ProductService;
use Microshop\Services\UserService;
use Microshop\Utils\CartCalculator;
use Microshop\Utils\Session;
Session::start();

$this->respond("GET", "/?", function($req, $res, $service, $app) {
    if(isset($_COOKIE['user_id'])) {
        $sessionKey = Session::read('cart_session_key');
        if(!$sessionKey) throw new \Exception("No session key found!");

        $userService = new UserService($app->db);
        $userData = $userService->findByUserId($_COOKIE['user_id']);
        $user = new User($userData);

        $checkoutService = new CheckoutService($app->db);
        if( ($checkoutData = $checkoutService->findByCartSessionKey($sessionKey)) === false ) {
            $checkoutData = [
                'user_id' => $user->getId(),
                'billing_id' => $user->getBillingId(),
                'shipping_id' => $user->getShippingId(),
                'cart_session_key' => $sessionKey,
                'type' => 1
            ];
        }

        $checkout = new Checkout($checkoutData);

        $checkoutService->persist($checkout);

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
$this->respond("GET", "/shipping", function($req, $res, $service, $app) {
    if(!isset($_COOKIE['user_id'])) return $res->redirect("/");

    $sessionKey = Session::read('cart_session_key');
    if(!$sessionKey) throw new \Exception("No session key found!");

    $userService = new UserService($app->db);
    $userData = $userService->findByUserId($_COOKIE['user_id']);
    $user = new User($userData);

    $billingService = new BillingService($app->db);

    $service->billingAddress = $billingService->findForUserById($user->getBillingId(), $user->getId());
    $service->shippingAddress = $billingService->findForUserById($user->getShippingId(), $user->getId());

    $service->user = $user;


    $checkoutService = new CheckoutService($app->db);
    if( ($checkoutData = $checkoutService->findByCartSessionKey($sessionKey)) === false ) {
        throw new \Exception("Something went wrong at checkout");
    }

    $checkout = new Checkout($checkoutData);

//    $checkoutService->persist($checkout);

    $cartService = new CartService($app->db);
    $productService = new ProductService($app->db);
    $cartCalculator = new CartCalculator($cartService, $productService, $sessionKey);

    $service->cart = $cartCalculator->filledCart();

    $service->render("./app/views/checkout/shipping.php");
});