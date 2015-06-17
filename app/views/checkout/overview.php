<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 17/06/15
 * Time: 08:43
 */
use Microshop\Utils\Session;


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
<a href="/checkout/shipping">Wrap up, finish order. Go to shipping</a>