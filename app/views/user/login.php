<?php
/**
 * User login form
 *
 * Mandatory fields: email, password
 */
?>
<form action="/user/login" method="post">
    <p><label for="email">Email <input id="email" type="text" name="email" /></label></p>
    <p><label for="password">Password <input id="password" type="password" name="password" /></label></p>
    <p><input type="submit" value="Login"/></p>
</form>