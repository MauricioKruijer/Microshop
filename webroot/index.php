<?php
require_once __DIR__ . '/../vendor/autoload.php';
define("APP_ROOT", "/Users/Mauricio/Sites/microshop/app/");

$klein = new \Klein\Klein();

$klein->respond(function ($request, $response, $service) {
    $service->layout('../app/layout/default.phtml');
});

$klein->respond('GET', '/hello-world', function () {
    return 'Hello World!';
});

$klein->respond('GET', '/', function ($request, $response, $service) {
	$service->pageTitle = 'Hello world';
	$service->render('../app/views/home.phtml');
    // return 'Hello World!!';
});

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