<?php
namespace Microshop\Services;
use Aura\Sql\Exception;
use Microshop\Models\Product;

use \Aura\Sql\ExtendedPdo;

/**
 * Class ProductService
 *
 * Used to fetch and save data from MySQL
 *
 * @package Microshop\Services
 */
class ProductService {
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
     * @param $productId
     * @return array|null
     */
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

    /**
     * @param int $maxLimit
     * @return array
     */
    public function getOverViewItems($maxLimit = 9) {
        $stmt = "SELECT * FROM `products` ORDER BY `created_time` DESC LIMIT :limit";
        $bind = ['limit' => $maxLimit];
        return $this->db->fetchAll($stmt, $bind);
    }

    /**
     * Save/update data in database
     *
     * @param Product $product
     * @return int|\PDOStatement
     */
    public function persist(Product $product) {
        $stmtTemplate = "
        %s
            `name` = :name,
            `short_description` = :short_description,
            `sku` = :sku,
            `quantity` = :quantity,
            `price` = :price,
            `photo_id` = :photo_id,
            `description` = :description,
            `is_deleted` = :is_deleted,
            `last_updated_time` = :last_updated_time,
            `created_time` = :created_time
        %s
        ";

        $bind = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'short_description' => $product->getShortDescription(),
            'sku' => $product->getSku(),
            'quantity' => $product->getQuantity(),
            'price' => $product->getPrice(),
            'photo_id' => $product->getPhotoId(),
            'description' => $product->getDescription(),
            'is_deleted' => $product->getIsDeleted(),
            'last_updated_time' => $product->getLastUpdatedTime(),
            'created_time' => date('c')
        ];

        if($productId = $product->getId()) {
            $stmt = sprintf($stmtTemplate, "UPDATE `products` SET", "WHERE `id` = :id LIMIT 1");
            return $this->db->perform($stmt, $bind);
        } else {
            $stmt = sprintf($stmtTemplate, "INSERT INTO `products` SET", "");
            $result = $this->db->perform($stmt, $bind);
            return $this->db->lastInsertId();
        }

    }
}