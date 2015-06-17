<a href="/cart">Go to shopingcart</a> <br />
<?php if(isset($_COOKIE['user_id'])): ?>
<a href="/user/logout">Go to logout</a> <br />
    <?php else: ?>
<a href="/user/login">Go to login</a> <br />
<?php
    endif;
    foreach($this->products as $product): ?>
    
            <div>
                <h2><?=$product['name']?> $<?=$product['price']/100?></h2>
                <a href="/product/<?=$product['id']?>">View</a>
                <a href="/cart/<?=$product['id']?>/add">Add to cart</a>
            </div>
            <?php endforeach;?>