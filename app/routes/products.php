<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 15/06/15
 * Time: 17:36
 */
//$this->respond("/?", function($req, $res) {
////    $res->redirect("/");
//});
use Microshop\Models\Product;
use Microshop\Services\PhotoService;
use Microshop\Services\ProductPhotoService;
use Microshop\Services\ProductService;

$this->respond('GET', "/?", function($req, $res, $service, $app) {

    $productService = new ProductService($app->db);
    $photoService = new PhotoService($app->db);
    $overviewProducts = [];

    foreach($productService->getOverViewItems() as $product) {
        $productImage = $photoService->findById($product['photo_id']);
        if(!empty($productImage)) {
            $product['image'] = '/'.$productImage['path'] . '/' . $productImage['name'];
        } else {
            $product['image'] = "//placehold.it/600x600"; // default thumbnail fallback
        }
        $overviewProducts[] = $product;
    }

    $res->json($overviewProducts);
});

$this->respond('GET', "/[i:id]", function($req, $res, $service, $app) {
    $productService = new ProductService($app->db);
    $product = $productService->findByProductId($req->id);
    if(!empty($product)) {
        $productPhotoService = new ProductPhotoService($app->db);
        $productPhotos = $productPhotoService->getPhotosByProductId($product['id']);
        if(!empty($productPhotos)) {
            $product['image'] = '/'.$productPhotos[0]['path'] . '/' . $productPhotos[0]['name'];
            $product['images'] = array_map(function($item){
                return '/' . $item['path'] . '/' . $item['name'];
            }, $productPhotos);

        } else {
            $product['image'] = "//placehold.it/600x600"; // default thumbnail fallback
            $product['images'] = ["//placehold.it/600x600"]; // default thumbnail fallback
        }
        $res->json($product);
    } else {
        $res->json(['error' => ['message' => 'Product not found']]);
    }
//    $res->json([
//        "id" => 1,
//        "sku" => 'SEN-1337',
//        "name" => "Product Name",
//        "short_description" => "This is a 5V Arduino running the 16MHz bootloader. Arduino Pro Mini does not come with connectors populated so that you can solder in any connector or wire with any orientation you need. ",
//        "description" => "It's blue! It's thin! It's the Arduino Pro Mini! SparkFun's minimal design approach to Arduino. This is a 5V Arduino running the 16MHz bootloader. Arduino Pro Mini does not come with connectors populated so that you can solder in any connector or wire with any orientation you need. We recommend first time Arduino users start with the Uno R3. It's a great board that will get you up and running quickly. The Arduino Pro series is meant for users that understand the limitations of system voltage (5V), lack of connectors, and USB off board. \n We really wanted to minimize the cost of an Arduino. In order to accomplish this we used all SMD components, made it two layer, etc. This board connects directly to the FTDI Basic Breakout board and supports auto-reset. The Arduino Pro Mini also works with the FTDI cable but the FTDI cable does not bring out the DTR pin so the auto-reset feature will not work. There is a voltage regulator on board so it can accept voltage up to 12VDC. If you’re supplying unregulated power to the board, be sure to connect to the \"RAW\" pin and not VCC. \n The latest and greatest version of this board breaks out the ADC6 and ADC7 pins as well as adds footprints for optional I2C pull-up resistors!",
//        "price" => 995,
//        "image" => "/webroot/images/product1/1.jpg",
//        "images" => [
//            "/webroot/images/product1/1.jpg",
//            "/webroot/images/product1/2.jpg",
//            "/webroot/images/product1/3.jpg"
//        ]
//    ]);
});
$this->respond(array("GET","POST"), "/hi", function($req) {
    \Microshop\Utils\Session::start();
    \Microshop\Utils\Session::start();
    var_dump($req->params(), $req->body());
    return "pong";
});
$this->respond('POST', '/?', function($req, $res, $service, $app) {
    $productService = new ProductService($app->db);
    // TODO catch all ValidatorExceptions separately to provide all errors at once instead of one at the time
    try {

        $productJson = $req->body();
        if('' === $productJson ) throw new \Exception("No product received");

        $productArray = json_decode($productJson, true);
        if(empty($productArray)) throw new \Exception("Empty product received");
        foreach($productArray as $param => $paramValue) {
            $req->$param = $paramValue;
        }

        $service->validateParam('name', 'Please provide a valid product name')->isLen(1, 255);

        $product = new Product($productArray);

        $productId = $productService->persist($product);
        if($productId > 0) {
            $productPhotoService = new ProductPhotoService($app->db);

            if($productPhotoService->bulkLinkPhotoToProduct($productArray['images'], $productId)) { //todo success saved linked images to product
                $res->json(['success' => [
                    'message' => "Product successfully added!",
                    'id'=> $productId
                ]]);
            } else {
                $res->json(['success' => [
                    'message' => "Product successfully added!",
                    'warning' => 'Images could not be saved',
                    'id'=> $productId
                ]]);
            }
        }

    } catch (\Klein\Exceptions\ValidationException $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'validation']]);
    } catch(PDOException $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'database']]);
    } catch(Exception $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'unknown']]);
    }
});