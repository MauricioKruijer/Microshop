<?php
/**
 * Cart overview
 *
 * Display cart products overview. Let the user add one more for each item in cart.
 * By subtracting one item it it possible to delete the product from cart
 *
 * Link to checkout
 *
 * @todo set fixed quantity for product and let the user remove product with one click
 */
?>
<h1>Total items <?=$this->cart->getTotalCartItems()?></h1>

<?php
foreach($this->cart->getProductsList() as $product): ?>

    <div>
        <h2><?=$product->getName()?></h2>
        <?=\Microshop\Utils\Session::read('cart_products', 'id_' . $product->getId()) ?>x
        <a href="/product/<?=$product->getId()?>">View</a>
        <a href="/cart/<?=$product->getId()?>/add">Add to cart one more</a>
        <a href="/cart/<?=$product->getId()?>/delete">Delete one</a>
    </div>
<?php endforeach;?>
<a href="/checkout">Checkout</a>