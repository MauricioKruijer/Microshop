<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 17/06/15
 * Time: 05:52
 */
$totalBillingItems = count($this->billingItems);
foreach($this->billingItems as $billing):
?>
<form action="/billing/<?=$billing->getId()?>/edit" method="post">
    <fieldset>
    <?php if($billing->getType() == 1): ?>
        <h2>PRIMARY bill and shipping address</h2>
    <?php else: ?>
    <p><a href="/billing/<?=$billing->getId()?>/makeprimary">Make primary make primary billing address</a></p>
    <?php endif; ?>
    <p><label>Name this address: <input type="text" name="name" value="<?=$billing->getName()?>"></label></p>
    <p><label>Edit address: <input type="text" name="full_address" value="<?=$billing->getFullAddress()?>"></label></p>
    <p><input type="submit" value="edit this address" /></p>
    </fieldset>
</form>
<?php endforeach; ?>