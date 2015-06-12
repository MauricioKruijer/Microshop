<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 12/06/15
 * Time: 15:07
 */

namespace Microshop\Models {

    class Product extends \Microshop\Utils\Core {
        public function getById() {
//            var_dump($this->pdo->query("SELECT * FROM products")->fetchAll());
            return "Hoi";
        }
    }
}