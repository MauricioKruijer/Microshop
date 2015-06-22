<?php
use Microshop\Models\Product;
use Microshop\Services\PhotoService;
use Microshop\Services\ProductPhotoService;
use Microshop\Services\ProductService;

/**
 * Respond to /products
 *
 * Serve products overview as json for frontend
 */
$this->respond('GET', "/?", function($req, $res, $service, $app) {
    // Get product service
    $productService = new ProductService($app->db);
    // Get product photo service
    $photoService = new PhotoService($app->db);
    // Set up empty array for returned data
    $overviewProducts = [];
    // Loop through found (overview) products
    // @todo filter out only highlighted products for overview
    foreach($productService->getOverViewItems() as $product) {
        // Get photo data for product
        $productImage = $photoService->findById($product['photo_id']);
        // Check if data exists
        if(!empty($productImage)) {
            // Save product photo
            // @todo use relative public path
            $product['image'] = '/'.$productImage['path'] . '/' . $productImage['name'];
        } else {
            // If image is not found server placeholder
            // @todo create own placeholder image instead of placehold.it
            $product['image'] = "//placehold.it/600x600"; // default thumbnail fallback
        }
        // Add product to return array
        $overviewProducts[] = $product;
    }
    // serve json response
    $res->json($overviewProducts);
});

/**
 * Respond to /products/i
 *
 * Serve product details as json, where i is product id
 */
$this->respond('GET', "/[i:id]", function($req, $res, $service, $app) {
    // Get product service
    $productService = new ProductService($app->db);
    // Find product
    $product = $productService->findByProductId($req->id);
    // Check found data exists
    if(!empty($product)) {
        // Get photo service
        $productPhotoService = new ProductPhotoService($app->db);
        // Find product photo's
        $productPhotos = $productPhotoService->getPhotosByProductId($product['id']);
        // Check found data exists
        if(!empty($productPhotos)) {
            // Save MAIN photo url
            // @todo use relative public path
            $product['image'] = '/'.$productPhotos[0]['path'] . '/' . $productPhotos[0]['name'];
            // Save gallery photos
            // @todo use relative public path
            $product['images'] = array_map(function($item){
                return '/' . $item['path'] . '/' . $item['name'];
            }, $productPhotos);
        } else {
            // Product photo's not found use placeholders
            // @todo creat own placholder images instad of placehold.it
            $product['image'] = "//placehold.it/600x600"; // default thumbnail fallback
            $product['images'] = ["//placehold.it/600x600"]; // default thumbnail fallback
        }
        // Serve json
        $res->json($product);
    } else {
        // Serve error in json
        $res->json(['error' => ['message' => 'Product not found']]);
    }
});

/**
 * Respond to /products/hi
 *
 * Used for debugging (sessions)
 */
$this->respond(array("GET","POST"), "/hi", function($req) {
    \Microshop\Utils\Session::start();
    \Microshop\Utils\Session::start();
    var_dump($req->params(), $req->body());
    return "pong";
});

/**
 * Respond to /products
 *
 * Handle post request for adding new products
 */
$this->respond('POST', '/?', function($req, $res, $service, $app) {
    // Get product service
    $productService = new ProductService($app->db);
    // @todo catch all ValidatorExceptions separately to provide all errors at once instead of one at the time
    try {
        // Get data from frontend
        $productJson = $req->body();
        // Ensure data is not empty
        // @todo create more meaningful exception
        if('' === $productJson ) throw new \Exception("No product received");

        // Decode json data to array
        $productArray = json_decode($productJson, true);
        // If array is empty throw exception
        // @todo create more meaningful exception
        if(empty($productArray)) throw new \Exception("Empty product received");
        // Loop through all values and save as request param, makes it easier to validate
        // @todo debug why this hack is necessary, patch klein.php to be compatible with angular post
        // Maybe this issue is caused by php 5.6 default POST_RAW_DATA setting
        foreach($productArray as $param => $paramValue) {
            $req->$param = $paramValue;
        }
        // Validate mandatory param (name)
        // @todo add validation for sku, description, etc
        $service->validateParam('name', 'Please provide a valid product name')->isLen(1, 255);
        // Create new Product object with product properties
        $product = new Product($productArray);
        // Get saved new product id
        // @todo handle error when product cant be saved
        $productId = $productService->persist($product);
        // Check if product id is saved correctly so we can add product photos to its own table
        if($productId > 0) {
            // Get product photo service
            $productPhotoService = new ProductPhotoService($app->db);
            // Save all product photos as bulk (transaction) to database and serve success message
            if($productPhotoService->bulkLinkPhotoToProduct($productArray['images'], $productId)) { //todo success saved linked images to product
                $res->json(['success' => [
                    'message' => "Product successfully added!",
                    'id'=> $productId
                ]]);
            } else {
                // Something went wrong saving product photos to database but product is successfully added
                // Serve message as json
                $res->json(['success' => [
                    'message' => "Product successfully added!",
                    'warning' => 'Images could not be saved',
                    'id'=> $productId
                ]]);
            }
        }

    } catch (\Klein\Exceptions\ValidationException $e) {
        // Handle invalid validation error
        // @todo move this part up so it is possible to server multiple validation errors at once
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'validation']]);
    } catch(PDOException $e) {
        // Handle databae error
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'database']]);
    } catch(Exception $e) {
        // Handle all other errors
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'unknown']]);
    }
});