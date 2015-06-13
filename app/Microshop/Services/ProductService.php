<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 12/06/15
 * Time: 22:14
 */

namespace Microshop\Services;
use Aura\Sql\Exception;
use Microshop\Models\Product;

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

    public function persist(Product $product) {
        if($productId = $product->getId()) {
            $stmt = "UPDATE `products` SET `name` = :name WHERE `id` = :id LIMIT 1";
            $bind = [
                'id' => $productId,
                'name' => $product->getName()
            ];

            return $this->db->perform($stmt, $bind);
        } else {
            $stmt = "INSERT INTO `products` (name, created_time) VALUES (:vals)";
            $bind = [
                'vals' => [
                    $product->getName(),
                    date('c')
                ]
            ];

            $result = $this->db->perform($stmt, $bind);
//            echo $result->queryString; // Debugs
            return $this->db->lastInsertId();

        }
    }
}