<?php
/**
 * Create additional billing form
 *
 * Save additional billing address by default as primary shipping address
 *
 * Mandatory fields: full_address
 *
 * @todo split up full_address to separate fields (street, zip, state, etc)
 */?>
<h1>Provide Additional Billing info</h1>
<form action="/billing/add/additional" method="post">
    <p><label>Address <input type="text" name="full_address"> </label></p>
    <p><input type="submit" value="Save"></p>
</form>