<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 12/06/15
 * Time: 22:14
 */

namespace Microshop\Services;

use \Aura\Sql\ExtendedPdo;

class ProductService {
    private $db;

    public function __construct(ExtendedPdo $db) {
        $this->db = $db;
    }

    public function findByProductId($productId) {
        $stmt = "SELECT * FROM `products` WHERE `id` = :id LIMIT 1";
        $bind = array('id' => $productId);

        $result = $this->db->fetchOne($stmt, $bind);

        if(!empty($result) ){
            return $result;
        } else {
            return null;
        }
    }
}