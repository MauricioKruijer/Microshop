<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 13/06/15
 * Time: 12:28
 */
use \Microshop\Services\ProductService;

$this->respond("/?", function($req, $res) {
    $res->redirect("/");
});

$this->respond('POST', '/create', function($req, $res, $service, $app) {
    $productService = new ProductService($app->db);
    // TODO catch all ValidatorExceptions separately to provide all errors at once instead of one at the time
    try {
        $service->validateParam('name', 'Please provide a valid product name')->isLen(1, 225);

        $product = new \Microshop\Models\Product(array('name'=>$req->params()['name']));

        $productId = $productService->persist($product);

        $res->json(['success' => ['message' => "Product successfully added!", 'id'=> $productId]]);
    } catch (\Klein\Exceptions\ValidationException $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'validation']]);
    } catch(PDOException $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'database']]);
    } catch(Exception $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'unknown']]);
    }
});


$viewProduct = function($req, $res, $service, $app) {
    $productService = new ProductService($app->db);

    $service->strMessage = "Product shizzle";
    $service->render("./app/views/users/add.php");

    var_dump($productService->findByProductId(2));
    var_dump($productService->findByProductId(1));

    if($req->title) {
        var_dump($req->title);
    }
};

$this->respond('/[i:id]/', function($req, $res) { $res->redirect("/product/" . $req->id); });
$this->respond("/[i:id]", $viewProduct);
$this->respond("/[i:id]/[:title]", $viewProduct);

