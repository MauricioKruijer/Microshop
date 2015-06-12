<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 12/06/15
 * Time: 17:43
 */
$this->respond('GET', '/?', function ($request, $response) {
    return "Wat?";
});
$this->respond('GET', '/add', function ($request, $response, $service) {
    $service->strMessage = "asdf <script>alert('a')</script>  troelaahalah ";
    $service->render('./app/views/users/add.php');
});
