<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 15/06/15
 * Time: 19:29
 */

namespace Microshop\Services;


use Aura\Sql\ExtendedPdo;

class CartService {
    private $db;

    public function __construct(ExtendedPdo $db) {
        $this->db = $db;
    }

    public function findCartItemsBySessionKey($sessionKey, $limit = 20) {
        $stmt = "SELECT * FROM `carts` WHERE `session_key` = :session_key ORDER BY `created_time` ASC LIMIT 20";
        $bind = ['session_key' => $sessionKey];
        return $this->db->fetchAll($stmt, $bind);
    }
    public function countTotalCartItemsBySessionKey($sessionKey) {
        $stmt = "SELECT SUM(`quantity`) as `total_items` FROM `carts` WHERE `session_key` = :session_key";
        $bind = ['session_key' => $sessionKey];
        return $this->db->fetchOne($stmt, $bind);
    }
    public function saveProductToCart($productId, $sessionKey) {
        $stmt = "INSERT INTO `carts` (product_id, session_key, created_time) VALUES (:vals)  ON DUPLICATE KEY UPDATE `quantity`=`quantity`+1";
        $bind = [
            'vals'=>[$productId, $sessionKey, date('c')]
        ];
        $result = $this->db->perform($stmt, $bind);
        return $this->db->lastInsertId();
    }
}