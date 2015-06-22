<?php
namespace Microshop\Services;


use Aura\Sql\ExtendedPdo;

/**
 * Class ProductPhotoService
 *
 * Used to fetch and save data from MySQL
 *
 * @package Microshop\Services
 */
class ProductPhotoService {
    /**
     * @param ExtendedPdo $db
     */
    public function __construct(ExtendedPdo $db) {
        $this->db = $db;
    }

    /**
     * @param $productId
     * @param int $limit
     * @return array
     */
    public function getPhotosByProductId($productId, $limit = 15) {
        $stmt = "SELECT
            `p`.*
        FROM
          `product_photos` AS `pp`
        LEFT JOIN
          `photos` AS `p`
            ON `p`.`id` = `pp`.`photo_id`
        WHERE `product_id` = :product_id LIMIT :limit";
        $bind = [
            'product_id' => $productId,
            'limit' => $limit
        ];
        return $this->db->fetchAll($stmt, $bind);
    }

    /**
     * @param $imageIds
     * @param $productId
     * @return bool
     */
    public function bulkLinkPhotoToProduct($imageIds, $productId){
        $stmt = "INSERT INTO `product_photos` (`product_id`,`photo_id`, `created_time`) VALUES (:vals)";
        $this->db->beginTransaction();
        try {
            foreach($imageIds as $imageId) {
                $bind = [
                    'vals' => [$productId, $imageId, date('c')]
                ];
                $this->db->perform($stmt, $bind);
            }
            return $this->db->commit();
        } catch(\Exception $e) {
            echo "Transaction error " . $e->getMessage();
            $this->db->rollBack();
        }


    }
}