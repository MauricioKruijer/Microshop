<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 17/06/15
 * Time: 05:28
 */
use Microshop\Utils\Session;
Session::start();

$this->respond("GET", "/?", function($req, $res, $service, $app) {
    if(isset($_COOKIE['user_id'])) {
        $service->render("./app/views/checkout/overview.php");
    } else {
        $service->render("./app/views/checkout/signup_or_login.php");
    }

});