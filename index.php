<?php
// Composer autoload
require_once __DIR__ . '/vendor/autoload.php';
// @todo there seems to be an issue with sending application-x-data to php://input since php 5.6 I guess
// Had to set it in php.ini file this does nothing
ini_set('always_populate_raw_post_data' , -1);
// @todo move up
require_once __DIR__ . '/app/config/config.php';

// PDO Wrapper
use \Aura\Sql\ExtendedPdo;
// User service
// @todo add user validation
use Microshop\Services\UserService;
// Router
$klein = new \Klein\Klein();

// Set shared objects and default layout
// Share ExtendedPdo object with connection
// Share UserService (@todo implement user validation)
$klein->respond(function ($request, $response, $service, $app) {
    // Set default layout
    $service->layout('./app/layout/default.php');
    // Save db in $app as shared object
    $app->register("db", function() {
        return new ExtendedPdo(
            'mysql:host=' . MYSQL_HOST . ';charset=utf8;dbname=' . MYSQL_DATABASE .';port=' . MYSQL_PORT,
            MYSQL_USER,
            MYSQL_PASSWORD,
            array(),
            array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
    });
    // UserService globally available in $app for user session validation
    $app->userService = new UserService($app->db);
});

// index!
$klein->respond('GET', '/', function ($request, $response, $service, $app) {
    $productService = new \Microshop\Services\ProductService($app->db);
	$service->pageTitle = 'Hello world';
	$service->render('./app/views/index/overview.php', ['products' => $productService->getOverViewItems()]);
});

// Map route namespace to controller/route file
$routes = [
    'billing',
    'checkout',
    'cart',
    'shoppingcart',
    'product',
    'user',
    'photos'
];
// Bind route with route file
foreach($routes as $controller ) {
    $klein->with('/' . $controller, __DIR__ . '/app/routes/' . $controller . '.php');
}

// @todo clean up error handling
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
// Start router
$klein->dispatch();
?>