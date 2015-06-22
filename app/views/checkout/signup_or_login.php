<?php
/**
 * Display signup or login form
 *
 * User is not yet logged in or registered. Display both forms
 */
?>
<H1>Hey there!</H1>
<h2>You can login:</h2>
<?php
// Show login form
// @todo use $this->partial()
include_once(APP_ROOT . 'views/user/login.php');
?>
<h2>Or signup:</h2>
<?php
// Show signup form
// @todo use $this->partial()
include_once(APP_ROOT . 'views/user/signup.php');
?>