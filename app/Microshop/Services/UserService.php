<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 13/06/15
 * Time: 16:54
 */

namespace Microshop\Services;


use Aura\Sql\ExtendedPdo;
use Microshop\Models\User;

class UserService {
    private $db;

    public function __construct(ExtendedPdo $db) {
        $this->db = $db;
    }
    public function findUserByEmail($userEmail) {
        $stmt = "SELECT `id` FROM `users` WHERE `email` = :email LIMIT 1";
        $bind = ['email' => $userEmail];

        return $this->db->fetchOne($stmt,$bind);
    }
    public function findByUserId($userId){
        $stmt = "SELECT * FROM `users` WHERE `id` = :id LIMIT 1";
        $bind = ['id' => $userId];

        return $this->db->fetchOne($stmt, $bind);
    }

    public function persist(User $user){
        if($userId = $user->getId()) {
            $stmt = "UPDATE `users` SET
                  `first_name` = :first_name,
                  `last_name` = :last_name,
                  `email` = :email,
                  `password` = :password,
                  `billing_id` = :billing_id,
                  `shipping_id` = :shipping_id,
                  `is_deleted` = :is_deleted,
                  `last_updated_time` = :last_updated_time
              WHERE `id` = :id LIMIT 1";

            $bind = [
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'billing_id' => $user->getBillingId(),
                'shipping_id' => $user->getShippingId(),
                'is_deleted' => $user->getIsDeleted(),
                'last_updated_time' => date('c'),
                'id' => $userId
            ];

            return $this->db->perform($stmt, $bind);
        } else {
            $stmt = "INSERT INTO `users` (first_name, last_name, email, password, created_time) VALUES (:vals)";
            $bind = [
                'vals' => [
                    $user->getFirstName(),
                    $user->getLastName(),
                    $user->getEmail(),
                    $user->getPassword(),
                    date('c')
                ]
            ];

            $result = $this->db->perform($stmt, $bind);
            return $this->db->lastInsertId();
        }
    }
}