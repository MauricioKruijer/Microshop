<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 13/06/15
 * Time: 12:28
 */
use Microshop\Models\Product;
use \Microshop\Services\ProductService;

$this->respond("/?", function($req, $res) {
    $res->redirect("/");
});
// Test with:
// curl --data "name=a" http://microshop.dev:8888/product/create
$this->respond('POST', '/create.[json:format]?', function($req, $res, $service, $app) {
    $productService = new ProductService($app->db);
    // TODO catch all ValidatorExceptions separately to provide all errors at once instead of one at the time
    try {
        $service->validateParam('name', 'Please provide a valid product name')->isLen(1, 255);

        $product = new Product($req->params());

        $productId = $productService->persist($product);
        if($req->format == 'json') {
            $res->json(['success' => ['message' => "Product successfully added!", 'id'=> $productId]]);
        } else {
            $service->flash("Sucessfull added productId " . $productId, "success");
            $res->redirect("/product/create");
        }

    } catch (\Klein\Exceptions\ValidationException $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'validation']]);
    } catch(PDOException $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'database']]);
    } catch(Exception $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'unknown']]);
    }
});
$this->respond("GET", '/create', function($req, $res, $service, $app) {
    $service->render("./app/views/product/create.php");
});
// Test with:
// curl --data "name=NewName" http://microshop.dev:8888/product/1/edit
$this->respond('POST', '/[i:id]/edit', function($req, $res, $service, $app) {
    $productService = new ProductService($app->db);
    try {
        $service->validateParam('name', 'Please provide a valid product name')->isLen(1, 255);
        if($fondProduct = $productService->findByProductId($req->id)) {
            $product = new Product($fondProduct);
            $product->setName($req->name);
            $productService->persist($product);

            $res->json(['success' => ['message' => 'Product successfully updated!']]);
        } else {
            $res->json(['error' => ['message' => 'Product not found!', 'type' => 'product']]);
        }
    } catch (\Klein\Exceptions\ValidationException $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'validation']]);
    } catch(PDOException $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'database']]);
//        $res->send();
    } catch(Exception $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'unknown']]);
    }

});

// Test with:
// curl -X DELETE http://microshop.dev:8888/product/1/delete
$this->respond('DELETE', '/[i:id]/delete', function($req, $res, $service, $app) {
    $productService = new ProductService($app->db);
    try {
        if($fondProduct = $productService->findByProductId($req->id)) {
            $product = new Product($fondProduct);

            $product->setIsDeleted(1);
            $productService->persist($product);

            $res->json(['success' => ['message' => 'Product successfully deleted!']]);

        } else {
            $res->json(['error' => ['message' => 'Product not found!', 'type' => 'product']]);
        }
    } catch(PDOException $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'database']]);
    } catch(Exception $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'unknown']]);
    }

});


$viewProduct = function($req, $res, $service, $app) {
    $productService = new ProductService($app->db);

    $service->strMessage = "Product shizzle";
    $service->render("./app/views/user/add.php");

    var_dump($productService->findByProductId(2));
    var_dump($productService->findByProductId(1));

    if($req->title) {
        var_dump($req->title);
    }
};

$this->respond('GET','/[i:id]/', function($req, $res) { $res->redirect("/product/" . $req->id); });
$this->respond('GET',"/[i:id]", $viewProduct);
$this->respond('GET', "/[i:id]/[:title]", $viewProduct);

