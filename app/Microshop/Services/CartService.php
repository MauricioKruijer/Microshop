<?php
namespace Microshop\Services;


use Aura\Sql\ExtendedPdo;

/**
 * Class CartService
 *
 * Used to fetch and save data from MySQL
 *
 * @package Microshop\Services
 */
class CartService {
    /**
     * @var ExtendedPdo
     */
    private $db;

    /**
     * @param ExtendedPdo $db
     */
    public function __construct(ExtendedPdo $db) {
        $this->db = $db;
    }

    /**
     * @param $sessionKey
     * @param int $limit
     * @return array
     */
    public function findCartItemsBySessionKey($sessionKey, $limit = 20) {
        $stmt = "SELECT * FROM `carts` WHERE `session_key` = :session_key ORDER BY `created_time` ASC LIMIT 20";
        $bind = ['session_key' => $sessionKey];
        return $this->db->fetchAll($stmt, $bind);
    }

    /**
     * @param $sessionKey
     * @return array
     */
    public function countTotalCartItemsBySessionKey($sessionKey) {
        $stmt = "SELECT SUM(`quantity`) as `total_items` FROM `carts` WHERE `session_key` = :session_key";
        $bind = ['session_key' => $sessionKey];
        return $this->db->fetchOne($stmt, $bind);
    }

    /**
     * @param $productId
     * @param $sessionKey
     * @return int
     */
    public function saveProductToCart($productId, $sessionKey) {
        $stmt = "INSERT INTO `carts` (product_id, session_key, created_time) VALUES (:vals)  ON DUPLICATE KEY UPDATE `quantity`=`quantity`+1";
        $bind = [
            'vals'=>[$productId, $sessionKey, date('c')]
        ];
        $result = $this->db->perform($stmt, $bind);
        return $this->db->lastInsertId();
    }

    /**
     * @todo refactor typo
     * @param $productId
     * @param $sessionKey
     * @return int
     */
    public function substractProductFromCart($productId, $sessionKey) {
        $stmt = "INSERT INTO `carts` (product_id, session_key, created_time) VALUES (:vals)  ON DUPLICATE KEY UPDATE `quantity`=`quantity`-1";
        $bind = [
            'vals'=>[$productId, $sessionKey, date('c')]
        ];
        $result = $this->db->perform($stmt, $bind);
        return $this->db->lastInsertId();
    }

    /**
     * @param $productId
     * @param $sessionKey
     * @return \PDOStatement
     */
    public function removeProductFromCart($productId, $sessionKey) {
        $stmt = "DELETE FROM `carts` WHERE `product_id` = :product_id AND `session_key` = :session_key LIMIT 1";
        $bind = [
            'product_id' => $productId,
            'session_key' => $sessionKey
        ];
        return $this->db->perform($stmt, $bind);
    }
}