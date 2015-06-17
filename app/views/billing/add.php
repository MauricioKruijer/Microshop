<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 17/06/15
 * Time: 06:56
 */?>
<h1>Provide Billing info</h1>
<form action="/billing/add" method="post">
    <p><label>Address <input type="text" name="full_address"> </label></p>
    <p><label><input type="checkbox" name="set_primary" value="1" checked> Use for billing  </label></p>
    <p><label><input type="checkbox" name="add_additional"> Add another address</label></p>
    <p><input type="submit" value="Save and continue"></p>
</form>