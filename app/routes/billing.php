<?php
use Microshop\Models\Billing;
use Microshop\Models\User;
use Microshop\Services\BillingService;
use Microshop\Services\UserService;

/**
 * Respond to /billing
 *
 * Get billing overview page or redirect user to provide billing info (/billing/add)
 */
$this->respond("GET", "/?", function($req, $res, $service, $app) {
    // Check if user is logged in, otherwise redirect to / (index)
    // @todo make more secure and validate session
    if(!isset($_COOKIE['user_id'])) return $res->redirect("/");

    // Create new user service
    $userService = new UserService($app->db);
    // Get user data from cookie
    // @todo make more secure and move to $app
    $userData = $userService->findByUserId($_COOKIE['user_id']);
    // Create new user from found user data
    // @todo throw exception on invalid/empty userdata
    $user = new User($userData);
    // Save user object in service, make it available in view
    $service->user = $user;

    // Create new billing service
    $billingService = new BillingService($app->db);
    // Find billing addresses from cookie id
    // @todo use $user->getId() instead
    if($billingAddresses = $billingService->getBillingAddresses($_COOKIE['user_id'])) {
        // Create billing items array to store billing objects
        $billingItems = [];
        // Loop through all found billing items
        foreach ($billingAddresses as $billingAddress) {
            // Create objects from billing data
            $billing = new Billing($billingAddress);
            // Save in array
            $billingItems[] = $billing;
        }
        // Save (filled) billing items array in $service to it available in view
        $service->billingItems = $billingItems;
        // Render billing overview in default layout
        $service->render("./app/views/billing/overview.php");
    } else {
        // If no billing addresses are found then redirect user to provide at least one
        $res->redirect("/billing/add");
    }
});

/**
 * Respond to /i/makeprimary
 * Where i is an integer mapped to billing id (action)
 *
 * Ensures primary shipping address for user
 *
 * @todo test if code is still necessary
 */
$this->respond("GET", '/[i:id]/makeprimary', function($req, $res, $service, $app) {
    // @todo check and validate logged in user

    // Create new user service
    $userService = new UserService($app->db);
    // Get user data from cookie
    // @todo make more secure and move to $app
    $userData = $userService->findByUserId($_COOKIE['user_id']);
    // Create new user from found user data
    // @todo throw exception on invalid/empty userdata
    $user = new User($userData);
    // Save user object in service, make it available in view
    $service->user = $user;

    // Save user id in var
    $userId = $user->getId();

    // Create new billing service
    $billingService = new BillingService($app->db);
    // Find billing addresses by user id
    if($billingItem = $billingService->findForUserById($req->id, $userId)) {
        // Ensure primary address (by id) is set to primary
        // @todo double check if this code is still needed since it should be handled by user object
        $billingService->ensurePrimaryAddress($billingItem['id'], $userId);

        // Save/update primary billing address for user
        $user->setBillingId($billingItem['id']);
        $userService->persist($user);

        // Flash success message and redirect back to billing page
        $service->flash("New primary address selected!");
        $res->redirect("/billing");
        // @todo respond with json (success) message instead of using flash messages
    } else {
        // If the requested billing id doesn't correspond to logged in user throw an exception
        // @todo catch or handle this error better
        throw new \Exception("User permission issue");
    }
});

/**
 * Respond to /i/shiptothisaddress
 * Where i is an integer mapped to billing id (action)
 *
 * Ensures (new) default shipping address for user
 */
$this->respond("GET", '/[i:id]/shiptothisaddress', function($req, $res, $service, $app) {
    // @todo check and validate logged in user

    // Create new user service
    $userService = new UserService($app->db);
    // Get user data from cookie
    // @todo make more secure and move to $app
    $userData = $userService->findByUserId($_COOKIE['user_id']);
    // Create new user from found user data
    // @todo throw exception on invalid/empty userdata
    $user = new User($userData);
    // Save user object in service, make it available in view
    $service->user = $user;

    // Save user id in var
    $userId = $user->getId();

    // Create new billing service
    $billingService = new BillingService($app->db);
    // Find billing addresses by user id
    if($billingItem = $billingService->findForUserById($req->id, $userId)) {
        // Save / update default shipping address
        $user->setShippingId($billingItem['id']);
        $userService->persist($user);

        // Flash success message and redirect back to billing page
        $service->flash("New shipping address selected!");
        $res->redirect("/billing");
        // @todo respond with json (success) message instead of using flash messages
    } else{
        // If the requested billing id doesn't correspond to logged in user throw an exception
        // @todo catch or handle this error better
        throw new \Exception("User permission issue");
    }
});

/**
 * Respond to /i/edit (POST)
 * Where i is an integer mapped to billing id (action)
 *
 * Edit shipping information
 */
$this->respond("POST", '/[i:id]/edit', function($req, $res, $service, $app) {
    // Check if user is logged in, otherwise redirect to / (index)
    // @todo make more secure and validate session
    if(!isset($_COOKIE['user_id'])) return $res->redirect("/");
    // Save user id in var
    $userId = $_COOKIE['user_id'];

    // Create new billing service
    $billingService = new BillingService($app->db);
    if($billingItem = $billingService->findForUserById($req->id, $userId)) {
        // Create new Billing object from found data
        $billing = new Billing($billingItem);
        // Update full address
        $billing->setFullAddress($req->full_address);
        // Update name
        $billing->setName($req->name);
        // @todo split up fields

        // Save all changed in database
        $billingService->persist($billing);

        // Flash success message and redirect back to /billing
        $service->flash("Address updated!");
        $res->redirect("/billing");
        // @todo respond with json (success) message instead of using flash messages
    } else{
        // If the requested billing id doesn't correspond to logged in user throw an exception
        // @todo catch or handle this error better
        throw new \Exception("User permission issue");
    }
});

/**
 * Respond to /billing/add and /billing/add/additional (POST)
 *
 * Add new (additional) billing/shipping address
 * Additional action is used to redirect to a new form to quickly add a new address
 */
$this->respond("POST", '/add/[additional:additional]?', function($req, $res, $service, $app){
    // @todo check and validate logged in user

    // Check if user is logged in (insecurely)
    if(!isset($_COOKIE['user_id'])) return $res->redirect("/");

    try {
        // Check if full address is provided otherwise throw an exception
        $service->validateParam("full_address", "Address is mandatory")->isLen(1, 255);

        // Create user service
        $userService = new UserService($app->db);
        // Find userdata by cookie
        // @todo retrieve userobject from $app->user and use $app->user->getId()
        $userData = $userService->findByUserId($_COOKIE['user_id']);

        // If now userdata has been found throw exception
        // @todo deprecate this code
        if(!$userData) throw new Exception("User not found, bad stuff");
        // Create new user object
        // @todo should be solved by using $app->user
        $user = new User($userData);

        // Create new billing service
        $billingService = new BillingService($app->db);

        // Find billing items for user
        $billingItems = $billingService->getBillingAddresses($user->getId());

        // Init new billing data array
        // @todo remove type item
        $billingData = [
            'type' => ($req->set_primary ? 1 : 2), // 1= default shipping, 2= additional shipping address
            'user_id' => $_COOKIE['user_id'],
            'full_address' => $req->full_address
        ];
        // Create new billing object from array
        $billing = new Billing($billingData);
        // Save billing object in db and store id in var
        $billingId = $billingService->persist($billing);
        // If billing item is requested to be set as primary ($_POST['set_primary'])
        if($req->set_primary) {
            // Ensure billing item is primary by billing id for user (id)
            // @todo remove this line, should be solved by user object
            $billingService->ensurePrimaryAddress($billingId, $_COOKIE['user_id']);

            // Update primary billing address for user
            $user->setBillingId($billingId);
            // If there are no existing billing items for user also set billing id as shipping id
            if(empty($billingItems)) {
                // Set billing id also as shipping id
                $user->setShippingId($billingId);
            }
            // Save user changes
            // @todo move line outside of this if/else block
            $userService->persist($user);
        } else {
            // Save newly added address as shipping address
            // @todo think about user experience, I think this will be preferred since the user wants to add a new address
            $user->setShippingId($billingId);
            // Save user changes
            // @todo move line outside of this if/else block
            $userService->persist($user);
        }
        // Check if $_POST['add_additional'] is set/given
        if($req->add_additional) {
            // Then redirect to add another address
            $res->redirect("/billing/add/additional");
        } else {
            // Redirect back to billing overview
            $res->redirect("/billing");
        }
    } catch (\Klein\Exceptions\ValidationException $e) {
        // Handle validation errors
        $res->json(["error" => ['message' => $e->getMessage(), 'type' => 'validation']]);
    }
});

/**
 * Respond to /billing/additional
 *
 * Render add_additional view. Show form to add additional billing info
 */
$this->respond("GET", "/add/additional", function($req, $res, $service, $app) {
    $service->render("./app/views/billing/add_additional.php");
});

/**
 * Respond to /billing/add
 *
 * Render add view. Show form to add shipping address with options to add an additional one
 */
$this->respond("GET", "/add", function($req, $res, $service, $app) {
    // @todo check and validate logged in user

    // Check if user is logged in (insecurely)
    if(!isset($_COOKIE['user_id'])) return $res->redirect("/");

    // Create user service
    $userService = new UserService($app->db);
    // Find userdata by cookie
    // @todo retrieve userobject from $app->user and use $app->user->getId()
    $userData = $userService->findByUserId($_COOKIE['user_id']);
    // Create new user object from found data
    $user = new User($userData);
    // Make user object available in view
    $service->user = $user;
    $service->render("./app/views/billing/add.php");
});