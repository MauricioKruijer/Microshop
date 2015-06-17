<?php
use Microshop\Utils\Session;
?>
<h1>Review your order</h1>
<fieldset>
    <h2>Billed to</h2>
    <p><?= $this->user->getFirstName() . ' ' . $this->user->getLastName() ?></p>
    <p><?=$this->user->getEmail()?></p>
    <p><?=$this->billingAddress['name']?></p>
    <p><?=$this->billingAddress['full_address']?></p>
    <p><a href="/billing"><small>Not correct? Change it here</small></a></p>
</fieldset>
<fieldset>
    <h2>Shipped to</h2>
    <p><?= $this->user->getFirstName() . ' ' . $this->user->getLastName() ?></p>
    <p><?=$this->shippingAddress['name']?></p>
    <p><?=$this->shippingAddress['full_address']?></p>
    <p><a href="/billing"><small>Not correct? Change it here</small></a></p>
</fieldset>
<?php
$totalPrice = $this->cart->getTotalCartValue()/100;
?>
<table>
    <tr>
        <th>Id</th>
        <th>Product name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Sub total</th>
    </tr>

    <?php foreach($this->cart->getProductsList() as $product) : $productQuantity = Session::read('cart_products', 'id_' . $product->getId());?>
        <tr>
            <th><?=$product->getId()?></th>
            <th><?=$product->getName()?></th>
            <th><?=($productQuantity)?></th>
            <th><?=($product->getPrice()/100)?></th>
            <th><?=($product->getPrice() * $productQuantity /100)?></th>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="5" style="text-align: right">total</td>
    </tr>
    <tr>
        <td colspan="5" style="text-align: right"><?= $totalPrice ?></td>
    </tr>
</table>