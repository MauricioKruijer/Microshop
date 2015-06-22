<?php
/**
 * Create billing form
 *
 * User must provide at least one billing address (by default also used for shipping).
 * Let the user add another address after providing the first one.
 * This new address will be set as primary shipping address
 *
 * Mandatory fields: full_address
 * Optional: add_additional
 *
 * @todo split up full_address to separate fields (street, zip, state, etc)
 */
?>
<h1>Provide Billing info</h1>
<form action="/billing/add" method="post">
    <p><label>Address <input type="text" name="full_address"> </label></p>
    <p><label><input type="checkbox" name="set_primary" value="1" <?=(empty($this->user->getBillingId()) ? 'checked':'')?>> Use for billing  </label></p>
    <p><label><input type="checkbox" name="add_additional"> Add another address</label></p>
    <p><input type="submit" value="Save and continue"></p>
</form>