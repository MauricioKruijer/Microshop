<?php
namespace Microshop\Services;


use Aura\Sql\ExtendedPdo;
use Microshop\Models\Billing;

/**
 * Class BillingService
 *
 * Used to fetch and save data from MySQL
 *
 * @package Microshop\Services
 */
class BillingService {
    /**
     * @param ExtendedPdo $db
     */
    public function __construct(ExtendedPdo $db){
        $this->db = $db;
    }

    /**
     * Get billing address by user id
     *
     * @param $billingId
     * @param $userId
     * @return array
     */
    public function findForUserById($billingId, $userId) {
        $stmt = "SELECT * FROM `billing` WHERE `id` = :id AND `user_id` = :user_id LIMIT 1";
        $bind = ['id' =>$billingId, 'user_id' => $userId];
        return $this->db->fetchOne($stmt, $bind);
    }

    /**
     * Get billing addresses by user id
     *
     * @param $userId
     * @return array
     */
    public function getBillingAddresses($userId) {
        $stmt = "SELECT * FROM `billing` WHERE `user_id` = :user_id LIMIT 10";
        $bind = ['user_id' => $userId];
        return $this->db->fetchAll($stmt, $bind);
    }

    /**
     * Ensure primary billing address for user
     *
     * @todo double check if it is still necessary
     * @param $billingId
     * @param $userId
     * @return bool
     */
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

    /**
     * Save or update Billing object to database
     *
     * @todo walk though object attributes by using a trait or parent class
     * @param Billing $billing
     * @return int|\PDOStatement
     */
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