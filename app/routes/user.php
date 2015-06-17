<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 12/06/15
 * Time: 17:43
 */
use Microshop\Services\UserService;
use Microshop\Models\User;
use Microshop\Utils\PassHash;
use Microshop\Utils\Session;
//Session::dump();
$this->respond("/?", function($req, $res) {
    $res->redirect("/");
});
// Test with:
// curl --data "first_name=Mauricio&last_name=Kruijer&email=mauricio.kruijer@gmail.com&password=abc" http://microshop.dev:8888/user/create
$this->respond('POST', '/create', function($req, $res, $service, $app) {
    $userService = new UserService($app->db);

    try {
        $service->validateParam('email', 'Please provide a valid email address')->isEmail();
        $service->validateParam('first_name', 'Please provide your first name')->isLen(1, 255);
        $service->validateParam('last_name', 'Please provide your last name')->isLen(1, 255);
        $service->validateParam('password', 'Please provide a password')->isLen(1, 255);

        $user = new User($req->paramsPost()->all());

        if($userService->findUserByEmail($req->email)) throw new \Exception("Email already in use");

        if($req->password !== $req->password2) throw new \Exception("Passwords dont match");

        $res->cookie("first_name", $req->first_name); // hack hack hack

        // todo fix
        $length = 64;
        $bytes = openssl_random_pseudo_bytes($length * 2);

        $userSessionKey = substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
        Session::write("user_session_key", $userSessionKey);

//        $user->setPasswordHash();
        $user->setPassword(PassHash::hash($req->password));
        $user->setUserSessionKey($userSessionKey);


        $userId = $userService->persist($user);
        $res->cookie("user_id", $userId); // hack hack hack

        if($req->redirect) {
            $res->redirect("/billing");
        } else {
            $res->json(['success' => ['message' => "User successfully created!", 'id'=> $userId]]);
        }
    } catch (\Klein\Exceptions\ValidationException $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'validation']]);
    } catch(PDOException $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'database']]);
    } catch(Exception $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'unknown']]);
    }

});

// Test with:
// curl --data "first_name=Mauricio1&last_name=Kruijer1&email=mauricio.kruijer1@gmail.com&password=abcefg" http://microshop.dev:8888/user/1/edit
$this->respond('POST', '/[i:id]/edit', function($req, $res, $service, $app) {
    $userService = new UserService($app->db);

    try {
        $service->validateParam('email', 'Please provide a valid email address')->isEmail();
        $service->validateParam('first_name', 'Please provide your first name')->isLen(1, 255);
        $service->validateParam('last_name', 'Please provide your last name')->isLen(1, 255);
        $service->validateParam('password', 'Please provide a password')->isLen(1, 255);

        if($foundUser = $userService->findByUserId($req->id)) {
            $user = new User($foundUser);

            $user->setFirstName($req->first_name);
            $user->setLastName($req->last_name);
            $user->setPassword($req->password);
            $user->setEmail($req->email);

            $userService->persist($user);

            $res->json(['success' => ['message' => 'User successfully updated!']]);
        } else {
            $res->json(['error' => ['message' => 'User not found!', 'type' => 'user']]);
        }
    } catch (\Klein\Exceptions\ValidationException $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'validation']]);
    } catch(PDOException $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'database']]);
    } catch(Exception $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'unknown']]);
    }

});

// Test with:
// curl -X DELETE http://microshop.dev:8888/user/1/delete
$this->respond('DELETE', '/[i:id]/delete', function($req, $res, $service, $app) {
    $userService = new UserService($app->db);
    try {
        if($foundUser = $userService->findByUserId($req->id)) {
            $product = new User($foundUser);

            $product->setIsDeleted(1);
            $userService->persist($product);

            $res->json(['success' => ['message' => 'User successfully deleted!']]);

        } else {
            $res->json(['error' => ['message' => 'User not found!', 'type' => 'product']]);
        }
    } catch(PDOException $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'database']]);
    } catch(Exception $e) {
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'unknown']]);
    }

});
$this->respond('GET','/[i:id]/', function($req, $res) { $res->redirect("/user/" . $req->id); });
$this->respond('GET',"/[i:id]", function($req, $res, $service, $app) {
    $userService = new UserService($app->db);

    if($foundUser = $userService->findByUserId($req->id)) {
        var_dump($foundUser);
    } else {
        // user not found
        $res->redirect("/");
    }
});
//$service->render('./app/views/user/add.php');

$this->respond("GET", '/login', function($req, $res, $service, $app) {
    if(isset($_COOKIE['user_id'])) return $res->redirect("/billing");
    $service->render("./app/views/user/login.php");
});
$this->respond("POST", '/login',function($req, $res, $service, $app) {
    try {
        $service->validateParam('email', 'Please provide a valid email address')->isEmail();

        $userService = new UserService($app->db);

        if($user = $userService->findUserByEmail($req->email)) {

            if(PassHash::check_password($user['password'], $req->password)) {
                $res->cookie("user_id", $user['id']); // hack hack

                $length = 64;
                $bytes = openssl_random_pseudo_bytes($length * 2);

                $userSessionKey = substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
                Session::write("user_session_key", $userSessionKey);


                $userData = $userService->findByUserId($user['id']);
                $user = new User($userData);
                $user->setUserSessionKey($userSessionKey);
                $userService->persist($user);

                $res->redirect("/billing");
            } else {
                $service->flash("User not found / incorrect password. Boo-hoo");
                $res->redirect("/login");
            }
        } else {
            $service->flash("User not found / incorrect password.");
            $res->redirect("/login");
        }

    } catch (\Klein\Exceptions\ValidationException $e) {
        $service->flash($e->getMessage());
        $res->redirect("/login");
//        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'validation']]);
    }


});
$this->respond("GET", "/logout", function($req, $res, $service) {
    Session::destroy();
    foreach($_COOKIE as $key => $val) {
        $res->cookie($key, null);
    }
    $res->redirect("/user/login");
});
$this->respond("GET", '/signup', function($req, $res, $service) {
    $service->render("./app/views/user/signup.php");
});