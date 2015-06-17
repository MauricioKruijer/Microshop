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