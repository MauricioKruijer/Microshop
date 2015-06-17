<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 17/06/15
 * Time: 06:09
 */

namespace Microshop\Services;


use Aura\Sql\ExtendedPdo;
use Microshop\Models\Billing;

class BillingService {
    public function __construct(ExtendedPdo $db){
        $this->db = $db;
    }
    public function findForUserById($billingId, $userId) {
        $stmt = "SELECT `id` FROM `billing` WHERE `id` = :id AND `user_id` = :user_id LIMIT 1";
        $bind = ['id' =>$billingId, 'user_id' => $userId];
        return $this->db->fetchOne($stmt, $bind);
    }
    public function getBillingAddresses($userId) {
        $stmt = "SELECT * FROM `billing` WHERE `user_id` = :user_id LIMIT 10";
        $bind = ['user_id' => $userId];
        return $this->db->fetchAll($stmt, $bind);
    }
    public function ensurePrimaryAddress($billingId, $userId) {
        $this->db->beginTransaction();
        try {
            $stmt = "UPDATE `billing` SET `type` = 2 WHERE `user_id` = :user_id";
            $bind = ['user_id' => $userId];
            $this->db->perform($stmt, $bind);
            $stmt = "UPDATE `billing` SET `type` = 1 WHERE `id` = :id AND `user_id` = :user_id";
            $bind = ['id' => $billingId, 'user_id' => $userId];
            $this->db->perform($stmt, $bind);
            return $this->db->commit();
        } catch(\Exception $e) {
            echo "Transaction error " . $e->getMessage();
            $this->db->rollBack();
            return false;
        }

    }
    public function persist(Billing $billing) {
        $stmtTemplate = "
        %s
            `name` = :name,
            `type` = :type,
            `full_address` = :full_address,
            `user_id` = :user_id,
            `is_deleted` = :is_deleted,
            `created_time` = :created_time
        %s
        ";

        $bind = [
            'id' => $billing->getId(),
            'name' => $billing->getName(),
            'type' => $billing->getType(),
            'full_address' => $billing->getFullAddress(),
            'is_deleted' => $billing->getIsDeleted(),
            'user_id' => $billing->getUserId(),
//            'last_updated_time' => $billing->getLastUpdatedTime(),
            'created_time' => date('c')
        ];

        if($billingId = $billing->getId()) {
            $stmt = sprintf($stmtTemplate, "UPDATE `billing` SET", "WHERE `id` = :id LIMIT 1");
            return $this->db->perform($stmt, $bind);
        } else {
            $stmt = sprintf($stmtTemplate, "INSERT INTO `billing` SET", "");
            $result = $this->db->perform($stmt, $bind);
            return $this->db->lastInsertId();
        }

    }
}