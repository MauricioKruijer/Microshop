<?php
/**
 * Create new product form
 *
 * Mandatory fields: all
 * @todo fix image upload issue
 */
?>
<form enctype="multipart/form-data" action="" method="post">
    <p>
        <label>Product name <input type="text" name="name"></label>
    </p>
    <p>
        <label>Product sku <input type="text" name="short_description"></label>
    </p>
    <p>
        <label>Product short description <input type="text" name="sku"></label>
    </p>
    <p>
        <label>Product quantity <input type="text" name="quantity"></label>
    </p>
    <p>
        <label>Product price IN CENTS <input type="text" name="price"></label>
    </p>
    <p>
        <label>Product full description <textarea name="description"></textarea></label>
    </p>
    <p>
        <input type="submit" value="Create product"/>
    </p>
</form>