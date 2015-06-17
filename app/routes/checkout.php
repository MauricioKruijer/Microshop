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
    $service->render("./app/views/checkout/step1.php");
});