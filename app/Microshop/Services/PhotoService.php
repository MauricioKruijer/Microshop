<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 16/06/15
 * Time: 20:32
 */

namespace Microshop\Services;


use Aura\Sql\ExtendedPdo;
use Microshop\Models\Photo;

class PhotoService {
    private $db;

    public function __construct(ExtendedPdo $db) {
        $this->db = $db;
    }

    public function findById($id) {
        $stmt = "SELECT * FROM `photos` WHERE `id` = :id LIMIT 1";
        $bind = ['id' => $id];

        return $this->db->fetchOne($stmt, $bind);
    }
    public function persist(Photo $photo) {
        if($photoId = $photo->getId()) {
            $stmt = "UPDATE `photo` SET
              `name` = :name,
              `width` = :width,
              `height` = :height,
              `path` = :path,
              `type` = :type,
              `checksum` = :checksum,
              `last_updated_time` = :last_updated_time
            WHERE `id` = :id LIMIT 1";
            $bind = [
                'id' => $photo->getId(),
                'name' => $photo->getName(),
                'width' => $photo->getWidth(),
                'height' => $photo->getHeight(),
                'path' => $photo->getPath(),
                'type' => $photo->getType(),
                'checksum' => $photo->getChecksum(),
                'last_updated_time' => date('c')
            ];
            return $this->db->perform($stmt, $bind);
        } else {
            $stmt = "INSERT INTO `photos` (
                  name,
                  width,
                  height,
                  path,
                  type,
                  checksum,
                  created_time
                ) VALUES (:vals)";
            $bind = [
                'vals' => [
                    'name' => $photo->getName(),
                    'width' => $photo->getWidth(),
                    'height' => $photo->getHeight(),
                    'path' => $photo->getPath(),
                    'type' => $photo->getType(),
                    'checksum' => $photo->getChecksum(),
                    'created_time' => date('c')
                ]
            ];
            $result = $this->db->perform($stmt, $bind);

            return $this->db->lastInsertId();
        }
    }
}