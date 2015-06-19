<?php
namespace Microshop\Services;


use Aura\Sql\ExtendedPdo;
use Microshop\Models\User;

/**
 * Class UserService
 *
 * Used to fetch and save data from MySQL
 *
 * @package Microshop\Services
 */
class UserService {
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
     * @param $userEmail
     * @return array
     */
    public function findUserByEmail($userEmail) {
        $stmt = "SELECT `id`, `password` FROM `users` WHERE `email` = :email LIMIT 1";
        $bind = ['email' => $userEmail];

        return $this->db->fetchOne($stmt,$bind);
    }

    /**
     * @param $userId
     * @return array
     */
    public function findByUserId($userId){
        $stmt = "SELECT * FROM `users` WHERE `id` = :id LIMIT 1";
        $bind = ['id' => $userId];

        return $this->db->fetchOne($stmt, $bind);
    }

    /**
     * Save/update data in database
     *
     * @param User $user
     * @return int|\PDOStatement
     */
    public function persist(User $user){
        if($userId = $user->getId()) {
            $stmt = "UPDATE `users` SET
                  `first_name` = :first_name,
                  `last_name` = :last_name,
                  `email` = :email,
                  `password` = :password,
                  `password_hash` = :password_hash,
                  `user_session_key` = :user_session_key,
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
                'password_hash' => $user->getPasswordHash(),
                'user_session_key' => $user->getUserSessionKey(),
                'billing_id' => $user->getBillingId(),
                'shipping_id' => $user->getShippingId(),
                'is_deleted' => $user->getIsDeleted(),
                'last_updated_time' => date('c'),
                'id' => $userId
            ];

            return $this->db->perform($stmt, $bind);
        } else {
            $stmt = "INSERT INTO `users` (first_name, last_name, email, password, password_hash, user_session_key, created_time) VALUES (:vals)";
            $bind = [
                'vals' => [
                    $user->getFirstName(),
                    $user->getLastName(),
                    $user->getEmail(),
                    $user->getPassword(),
                    $user->getPasswordHash(),
                    $user->getUserSessionKey(),
                    date('c')
                ]
            ];

            $result = $this->db->perform($stmt, $bind);
            return $this->db->lastInsertId();
        }
    }
}