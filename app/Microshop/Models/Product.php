<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 12/06/15
 * Time: 15:07
 */

namespace Microshop\Models {

    class Product extends \Microshop\Utils\BasicObject {
        function __construct($product) {
            if(!isset($product['name'])) throw new \Exception("Product name is mandatory");

            $this->id = (isset($product['id']) ? $product['id'] : false);
            $this->name = $product['name'];
            $this->created_time = (isset($product['created_time']) ? $product['created_time'] : null);
        }
    }
}