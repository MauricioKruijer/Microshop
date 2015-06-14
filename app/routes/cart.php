<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 14/06/15
 * Time: 00:33
 */
$cart = new \Microshop\Models\Cart();

$this->respond("/?", function($req, $res) use($cart) {
//    $res->send("h")

    var_dump($cart->getProducts());
    return "Hey hallo cart";
});
// No JS fallback
$this->respond('GET', "/[i:id]/add", function($req, $res) use($cart) {
    $cart->addProductById($req->id);
    $res->redirect("/cart");
});
$this->respond('POST', "/add", function($req, $res) use($cart) {
    $cart->addProductById($req->id);
    // TODO handle $req->amount n product item(s)
    $res->json(['success'=> ['message'=> 'Item added to cart!']]);
});