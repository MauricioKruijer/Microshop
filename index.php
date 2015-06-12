<?php
require_once __DIR__ . '/vendor/autoload.php';

define("APP_ROOT", "/Users/Mauricio/Sites/microshop/app/");

require_once __DIR__ . '/app/config/config.php';

use \Aura\Sql\ExtendedPdo;
use \Microshop\Services\ProductService;

$klein = new \Klein\Klein();

$klein->respond(function ($request, $response, $service, $app) {
    $service->layout('./app/layout/default.php');
    $app->register("db", function() {
        return new ExtendedPdo(
            'mysql:host=' . MYSQL_HOST . ';charset=utf8;dbname=' . MYSQL_DATABASE .';port=' . MYSQL_PORT,
            MYSQL_USER,
            MYSQL_PASSWORD,
            array(),
            array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
    });
});

$klein->respond('GET', '/hello-world', function () {
    return 'Hello World!';
});

$klein->respond('GET', '/', function ($request, $response, $service) {
	$service->pageTitle = 'Hello world';
	$service->render('./app/views/home.php');
    // return 'Hello World!!';
});

$klein->with("/product", function() use ($klein) {
    $klein->respond("/?", function($req, $res) {
        $res->redirect("/");
    });

    $klein->respond("/[i:id]", function($req, $res, $service, $app) {
        $productService = new ProductService($app->db);
        $service->strMessage = "Product shizzle";
        $service->render("./app/views/users/add.php");
        var_dump($productService->findByProductId(2));
        var_dump($productService->findByProductId(1));
    });
});

$klein->with("/users", __DIR__. '/app/routes/users.php');
//$klein->with("/users", function() use ($klein) {
//    $klein->respond("/", function() {
//        return "YO USERS";
//    });
//});

$klein->onHttpError(function ($code, $router) {
    switch ($code) {
        case 404:
            $router->response()->body(
                'Y U so lost?!'
            );
            break;
        case 405:
            $router->response()->body(
                'You can\'t do that!'
            );
            break;
        default:
            $router->response()->body(
                'Oh no, a bad error happened that caused a '. $code
            );
    }
});

$klein->dispatch();

?>