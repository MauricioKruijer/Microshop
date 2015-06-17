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
        <?php if($billing->getId() == $this->user->getBillingId()): ?>
            <h2>Selected as billing address</h2>
        <?php else: ?>
        <p><a href="/billing/<?=$billing->getId()?>/makeprimary">Make primary make primary billing address</a></p>
        <?php endif; ?>
        <?php if($billing->getId() == $this->user->getShippingId()): ?>
            <h2>Selected as shipping address</h2>
        <?php else: ?>
        <p><a href="/billing/<?=$billing->getId()?>/shiptothisaddress">Make primary make primary shipping address</a></p>
        <?php endif; ?>
        <p><label>Name this address: <input type="text" name="name" value="<?=$billing->getName()?>"></label></p>
        <p><label>Edit address: <input type="text" name="full_address" value="<?=$billing->getFullAddress()?>"></label></p>
        <p><input type="submit" value="edit this address" /></p>
    </fieldset>
    <br>
</form>
<?php endforeach; ?>