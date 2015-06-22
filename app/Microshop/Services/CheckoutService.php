<?php
namespace Microshop\Services;


use Aura\Sql\ExtendedPdo;
use Microshop\Models\Checkout;

/**
 * Class CheckoutService
 *
 * Used to fetch and save data from MySQL
 *
 * @package Microshop\Services
 */
class CheckoutService {
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
     * @param $key
     * @return array
     */
    public function findByCartSessionKey($key) {
        $stmt = "SELECT * FROM `checkout` WHERE `cart_session_key` = :cart_session_key LIMIT 1";
        $bind = ['cart_session_key' => $key];

        return $this->db->fetchOne($stmt, $bind);
    }

    /**
     * Save/update checkout data in database
     *
     * @param Checkout $checkout
     * @return int|\PDOStatement
     */
    public function persist(Checkout $checkout) {
        $stmtTemplate = "
        %s
            `user_id` = :user_id,
            `type` = :type,
            `cart_session_key` = :cart_session_key,
            `coupon` = :coupon,
            `billing_id` = :billing_id,
            `shipping_id` = :shipping_id,
            `created_time` = :created_time
        %s
        ";

        $bind = [
            'id' => $checkout->getId(),
            'user_id' => $checkout->getUserId(),
            'type' => $checkout->getType(),
            'cart_session_key' => $checkout->getCartSessionKey(),
            'coupon' => $checkout->getCoupon(),
            'billing_id' => $checkout->getBillingId(),
            'shipping_id' => $checkout->getShippingId(),
            'created_time' => date('c')
        ];

        if($checkoutId = $checkout->getId()) {
            $stmt = sprintf($stmtTemplate, "UPDATE `checkout` SET", "WHERE `id` = :id LIMIT 1");
            return $this->db->perform($stmt, $bind);
        } else {
            $stmt = sprintf($stmtTemplate, "INSERT INTO `checkout` SET", "");
            $result = $this->db->perform($stmt, $bind);
            return $this->db->lastInsertId();
        }
    }
}