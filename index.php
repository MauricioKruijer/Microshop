<?php
require_once __DIR__ . '/vendor/autoload.php';

define("APP_ROOT", "/Users/Mauricio/Sites/microshop/app/");
define("PROJECT_ROOT", "/Users/Mauricio/Sites/microshop/");

ini_set('always_populate_raw_post_data' , -1);

require_once __DIR__ . '/app/config/config.php';

use \Aura\Sql\ExtendedPdo;


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
$klein->respond('GET', '/test', function($request, $response, $service) {
    $service->flash("yo me boy");
    $service->flash("yo ma boy");
    $service->back();
    $service->render('./app/views/home.php');

});

$klein->respond('GET', '/hello-world', function () {
    return 'Hello World!';
});

// index!
$klein->respond('GET', '/', function ($request, $response, $service, $app) {
    $productService = new \Microshop\Services\ProductService($app->db);
//    var_dump($productService->getOverViewItems());
	$service->pageTitle = 'Hello world';
	$service->render('./app/views/index/overview.php', ['products' => $productService->getOverViewItems()]);
    // return 'Hello World!!';
});

$klein->with('/cart', __DIR__ . '/app/routes/cart.php');
$klein->with('/shoppingcart', __DIR__ . '/app/routes/shoppingcart.php');
$klein->with('/product', __DIR__ . '/app/routes/product.php');
$klein->with('/products', __DIR__ . '/app/routes/products.php');
$klein->with("/user", __DIR__. '/app/routes/user.php');
$klein->with("/photos", __DIR__. '/app/routes/photos.php');
//$klein->with("/user", function() use ($klein) {
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