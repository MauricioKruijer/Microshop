<?php
use Microshop\Services\UserService;
use Microshop\Models\User;
use Microshop\Utils\PassHash;
use Microshop\Utils\Session;

/**
 * Respond to /user
 *
 * Redirect to index since it is not being used atm
 */
$this->respond("/?", function($req, $res) {
    $res->redirect("/");
});

/**
 * Respond to /user/create
 *
 * Used to create new users
 */
// Test with:
// curl --data "first_name=Mauricio&last_name=Kruijer&email=mauricio.kruijer@gmail.com&password=abc" http://microshop.dev:8888/user/create
$this->respond('POST', '/create', function($req, $res, $service, $app) {
    // Get user service
    $userService = new UserService($app->db);

    try {
        // Validate mandatory parameters
        // @todo split up try/catch for handling multiple errors
        $service->validateParam('email', 'Please provide a valid email address')->isEmail();
        $service->validateParam('first_name', 'Please provide your first name')->isLen(1, 255);
        $service->validateParam('last_name', 'Please provide your last name')->isLen(1, 255);
        $service->validateParam('password', 'Please provide a password')->isLen(1, 255);

        // Create new User object
        $user = new User($req->paramsPost()->all());

        // Find unique email or throw exception
        if($userService->findUserByEmail($req->email)) throw new \Exception("Email already in use");
        // Check if passwords match
        if($req->password !== $req->password2) throw new \Exception("Passwords dont match");
        // Save cookie
        // @todo replace this with user session key
        $res->cookie("first_name", $req->first_name); // hack hack hack

        // todo fix
        $length = 64;
        $bytes = openssl_random_pseudo_bytes($length * 2);
        // Creat user session key
        $userSessionKey = substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
        Session::write("user_session_key", $userSessionKey);
        // Hash user password
        $user->setPassword(PassHash::hash($req->password));
        // Save user session key
        $user->setUserSessionKey($userSessionKey);

        // Create/save new user
        $userId = $userService->persist($user);
        // Save user id in cookie
        // @todo remove this
        $res->cookie("user_id", $userId); // hack hack hack

        // Redirect new user to billing or serve message json
        if($req->redirect) {
            $res->redirect("/billing");
        } else {
            $res->json(['success' => ['message' => "User successfully created!", 'id'=> $userId]]);
        }
    } catch (\Klein\Exceptions\ValidationException $e) {
        // Handle validation errors
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'validation']]);
    } catch(PDOException $e) {
        // Handle database errors
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'database']]);
    } catch(Exception $e) {
        // Handle all other errors
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'unknown']]);
    }

});

/**
 * Respond to /user/i/edit
 *
 * Handle post request for editing user data, where i is user id
 */
// Test with:
// curl --data "first_name=Mauricio1&last_name=Kruijer1&email=mauricio.kruijer1@gmail.com&password=abcefg" http://microshop.dev:8888/user/1/edit
$this->respond('POST', '/[i:id]/edit', function($req, $res, $service, $app) {
    // @todo check if user is logged in otherwise redirect / serve error
    // Get new user service
    $userService = new UserService($app->db);

    try {
        // Validate mandatory params
        $service->validateParam('email', 'Please provide a valid email address')->isEmail();
        $service->validateParam('first_name', 'Please provide your first name')->isLen(1, 255);
        $service->validateParam('last_name', 'Please provide your last name')->isLen(1, 255);
        $service->validateParam('password', 'Please provide a password')->isLen(1, 255);

        // Get user by id
        // @todo should be handled by $app->user->getId()
        if($foundUser = $userService->findByUserId($req->id)) {
            // Create user object
            // @todo remove this
            $user = new User($foundUser);
            // Update user fields/data
            $user->setFirstName($req->first_name);
            $user->setLastName($req->last_name);
            $user->setPassword($req->password);
            $user->setEmail($req->email);
            // Save changes
            $userService->persist($user);
            // serve success message in json
            $res->json(['success' => ['message' => 'User successfully updated!']]);
        } else {
            // Serve error message in json
            $res->json(['error' => ['message' => 'User not found!', 'type' => 'user']]);
        }
    } catch (\Klein\Exceptions\ValidationException $e) {
        // Handle validation errors
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'validation']]);
    } catch(PDOException $e) {
        // Handle database errors
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'database']]);
    } catch(Exception $e) {
        // Handle all other errors
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'unknown']]);
    }
});

/**
 * Respond to /user/i/delete
 *
 * Handle delete request, where i is user id
 * @todo implement feature
 */
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

/**
 * Respond to /user/login
 *
 * Used to login user, serves login form
 */
$this->respond("GET", '/login', function($req, $res, $service, $app) {
    // @todo check validate user session

    // Check if cookie is set (dirty way to see if user is logged in), if so redirect to billing
    // @todo remove
    if(isset($_COOKIE['user_id'])) return $res->redirect("/billing");
    // Render view
    $service->render("./app/views/user/login.php");
});

/**
 * Respond to /user/login
 *
 * Used to handle user login POST request, redirects on success to billing
 */
$this->respond("POST", '/login',function($req, $res, $service, $app) {
    try {
        // Validated form data
        $service->validateParam('email', 'Please provide a valid email address')->isEmail();
        // @todo check if passord is not empty

        // Create new user service
        $userService = new UserService($app->db);

        // Check if user is found by email
        if($user = $userService->findUserByEmail($req->email)) {
            // Validate user hashed password if matched with stored hash
            if(PassHash::check_password($user['password'], $req->password)) {
                // Save cookie for user (dirty way)
                // @todo remove
                $res->cookie("user_id", $user['id']); // hack hack

                $length = 64;
                $bytes = openssl_random_pseudo_bytes($length * 2);
                // Create user session key
                $userSessionKey = substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
                Session::write("user_session_key", $userSessionKey);

                // Get user data
                $userData = $userService->findByUserId($user['id']);
                // Create new user
                // @todo save user object in $app
                $user = new User($userData);
                // Set user session key (only allow 1 opened session per user)
                $user->setUserSessionKey($userSessionKey);
                // Save/update user session key
                $userService->persist($user);
                // Redirect to billing page
                $res->redirect("/billing");
            } else {
                // User password incorrect flash message and redirect to /login
                // @todo redirect to /user/login
                $service->flash("User not found / incorrect password. Boo-hoo");
                $res->redirect("/login");
            }
        } else {
            // User not found flash message and redirect to /login
            // @todo redirect to /user/login
            $service->flash("User not found / incorrect password.");
            $res->redirect("/login");
        }

    } catch (\Klein\Exceptions\ValidationException $e) {
        // Handle validation errors
        // Flash message and redirect
        // @todo redirect to /user/login
        $service->flash($e->getMessage());
        $res->redirect("/login");
    }
});

/**
 * Respond to /user/logout
 *
 * Used to logout and destroy user session then redirect back to /user/login
 */
$this->respond("GET", "/logout", function($req, $res, $service) {
    Session::destroy();
    foreach($_COOKIE as $key => $val) {
        $res->cookie($key, null);
    }
    $res->redirect("/user/login");
});

/**
 * Respond to /user/signup
 *
 * Used to serve signup form
 */
$this->respond("GET", '/signup', function($req, $res, $service) {
    $service->render("./app/views/user/signup.php");
});