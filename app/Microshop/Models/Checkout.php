<?php
namespace Microshop\Models;


use Microshop\Utils\BasicObject;

/**
 * Class Checkout
 *
 * Used to store (temporary) checkout data and make it possible to persist session
 *
 * @package Microshop\Models
 */
class Checkout extends BasicObject {
    /**
     * Checkout id
     *
     * @var bool
     */
    protected $id;
    /**
     * User id
     *
     * @var int
     */
    private  $user_id;
    /**
     * Session key matched with current checkout item
     *
     * @var string
     */
    private $cart_session_key;
    /**
     * Type used to check state of checkout
     * Stored as TINYINT in MySQL
     *
     * @todo consider using constants
     * @example 1=in checkout, 2=payment awaiting, 3=payed, 4=failed, 5=cancelled
     * @var int
     */
    private $type;
    /**
     * Coupon code (not yet validated)
     *
     * @todo implement this feature
     * @var string
     */
    private $coupon;
    /**
     * Billing id
     *
     * @var int
     */
    private $billing_id;
    /**
     * Shipping id
     *
     * @var int
     */
    private $shipping_id;

    /**
     * Converts checkout array to Checkout object
     *
     * @todo use getters and setters. Consider using a trait
     * @param $checkout
     */
    public function __construct($checkout) {
        $this->id = (isset($checkout['id']) ? $checkout['id'] : false);
        $this->user_id = (isset($checkout['user_id']) ? $checkout['user_id'] : null);
        $this->cart_session_key = (isset($checkout['cart_session_key']) ? $checkout['cart_session_key'] : null);
        $this->type = (isset($checkout['type']) ? $checkout['type'] : null);
        $this->coupon = (isset($checkout['coupon']) ? $checkout['coupon'] : null);
        $this->billing_id = (isset($checkout['billing_id']) ? $checkout['billing_id'] : null);
        $this->shipping_id = (isset($checkout['shipping_id']) ? $checkout['shipping_id'] : null);
    }

    /**
     * Get billing id
     *
     * @return int
     */
    public function getBillingId()
    {
        return $this->billing_id;
    }

    /**
     * Set billing id
     *
     * @param int $billing_id
     */
    public function setBillingId($billing_id)
    {
        $this->billing_id = $billing_id;
    }

    /**
     * Get cart session key
     *
     * @return string
     */
    public function getCartSessionKey()
    {
        return $this->cart_session_key;
    }

    /**
     * Set cart session id
     *
     * @param string $cart_session_key
     */
    public function setCartSessionKey($cart_session_key)
    {
        $this->cart_session_key = $cart_session_key;
    }

    /**
     * Get coupon code
     *
     * @todo implement this feature
     * @return string
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * Set coupon code
     *
     * @todo implement this feature
     * @param string $coupon
     */
    public function setCoupon($coupon)
    {
        $this->coupon = $coupon;
    }

    /**
     * Get shipping id
     *
     * @return int
     */
    public function getShippingId()
    {
        return $this->shipping_id;
    }

    /**
     * Set shipping id
     *
     * @param int $shipping_id
     */
    public function setShippingId($shipping_id)
    {
        $this->shipping_id = $shipping_id;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get user id
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set user id
     *
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

}