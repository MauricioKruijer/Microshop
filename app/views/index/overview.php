<a href="/cart">Go to shopingcart</a> <br />
<a href="/user/login">Go to login</a> <br />

<?php foreach($this->products as $product): ?>
    
            <div>
                <h2><?=$product['name']?></h2>
                <a href="/product/<?=$product['id']?>">View</a>
                <a href="/cart/<?=$product['id']?>/add">Add to cart</a>
            </div>
            <?php endforeach;?>