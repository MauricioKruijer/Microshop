<?php
use Microshop\Models\Billing;
use Microshop\Models\User;
use Microshop\Services\BillingService;
use Microshop\Services\UserService;

$this->respond("GET", "/?", function($req, $res, $service, $app) {
    if(!isset($_COOKIE['user_id'])) return $res->redirect("/");
    $userService = new UserService($app->db);
    $userData = $userService->findByUserId($_COOKIE['user_id']);
    $user = new User($userData);
    $service->user = $user;

    $billingService = new BillingService($app->db);
//    var_dump($billingService->getBillingAddresses($_COOKIE['user_id']));
    if($billingAddresses = $billingService->getBillingAddresses($_COOKIE['user_id'])) {
//        $service->billings = [];
        $billingItems = [];
        foreach ($billingAddresses as $billingAddress) {
            $billing = new Billing($billingAddress);
            $billingItems[] = $billing;
        }
        $service->billingItems = $billingItems;
        $service->render("./app/views/billing/overview.php");
    } else {
        $res->redirect("/billing/add");
    }
});
$this->respond("GET", '/[i:id]/makeprimary', function($req, $res, $service, $app) {
    $userService = new UserService($app->db);
    $userData = $userService->findByUserId($_COOKIE['user_id']);
    $user = new User($userData);

    $service->user = $user;

    $userId = $user->getId();

    $billingService = new BillingService($app->db);
    if($billingItem = $billingService->findForUserById($req->id, $userId)) {
        $billingService->ensurePrimaryAddress($billingItem['id'], $userId);

        $user->setBillingId($billingItem['id']);
        $userService->persist($user);

        $service->flash("New primary address selected!");
        $res->redirect("/billing");
    } else{
        throw new \Exception("User permission issue");
    }
});
$this->respond("GET", '/[i:id]/shiptothisaddress', function($req, $res, $service, $app) {
    $userService = new UserService($app->db);
    $userData = $userService->findByUserId($_COOKIE['user_id']);
    $user = new User($userData);

    $service->user = $user;

    $userId = $user->getId();

    $billingService = new BillingService($app->db);
    if($billingItem = $billingService->findForUserById($req->id, $userId)) {
//        $billingService->ensurePrimaryAddress($billingItem['id'], $userId);

        $user->setShippingId($billingItem['id']);
        $userService->persist($user);

        $service->flash("New shipping address selected!");
        $res->redirect("/billing");
    } else{
        throw new \Exception("User permission issue");
    }
});
$this->respond("POST", '/[i:id]/edit', function($req, $res, $service, $app) {
    if(!isset($_COOKIE['user_id'])) return $res->redirect("/");
    $userId = $_COOKIE['user_id'];

    $billingService = new BillingService($app->db);
    if($billingItem = $billingService->findForUserById($req->id, $userId)) {
        $billing = new Billing($billingItem);
        $billing->setFullAddress($req->full_address);
        $billing->setName($req->name);

        $billingService->persist($billing);

        $service->flash("Address updated!");
        $res->redirect("/billing");
    } else{
        throw new \Exception("User permission issue");
    }
});
$this->respond("POST", '/add/[additional:additional]?', function($req, $res, $service, $app){
    if(!isset($_COOKIE['user_id'])) return $res->redirect("/");

    try {
        $service->validateParam("full_address", "Address is mandatory")->isLen(1, 255);

        $userService = new UserService($app->db);
        $userData = $userService->findByUserId($_COOKIE['user_id']);


        if(!$userData) throw new Exception("User not found, bad stuff");
        $user = new User($userData);

        $billingService = new BillingService($app->db);

        $billingItems = $billingService->getBillingAddresses($user->getId());

        $billingData = [
            'type' => ($req->set_primary ? 1 : 2), // 1= default shipping, 2= additional shipping address
            'user_id' => $_COOKIE['user_id'],
            'full_address' => $req->full_address
        ];
        $billing = new Billing($billingData);
        $billingId = $billingService->persist($billing);
//        var_dump($billingData);
        if($req->set_primary) {
            $billingService->ensurePrimaryAddress($billingId, $_COOKIE['user_id']);

            $user->setBillingId($billingId);
            if(empty($billingItems)) {
                $user->setShippingId($billingId);
            }
            $userService->persist($user);
        } else {
            $user->setShippingId($billingId);
            $userService->persist($user);
        }
        if($req->add_additional) {
            $res->redirect("/billing/add/additional");
        } else {

            $res->redirect("/billing");
        }
    } catch (\Klein\Exceptions\ValidationException $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'validation']]);
    }
});
$this->respond("GET", "/add/additional", function($req, $res, $service, $app) {
    $service->render("./app/views/billing/add_additional.php");
});
$this->respond("GET", "/add", function($req, $res, $service, $app) {
    if(!isset($_COOKIE['user_id'])) return $res->redirect("/");
    $userService = new UserService($app->db);
    $userData = $userService->findByUserId($_COOKIE['user_id']);
    $user = new User($userData);
    $service->user = $user;
    $service->render("./app/views/billing/add.php");
});