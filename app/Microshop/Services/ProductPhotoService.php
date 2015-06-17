<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 17/06/15
 * Time: 01:48
 */

namespace Microshop\Services;


use Aura\Sql\ExtendedPdo;

class ProductPhotoService {
    public function __construct(ExtendedPdo $db) {
        $this->db = $db;
    }
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