<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 17/06/15
 * Time: 10:30
 */

namespace Microshop\Models;


use Microshop\Utils\BasicObject;

class Checkout extends BasicObject {
    protected $id;
    private  $user_id, $cart_session_key,$type, $coupon, $billing_id, $shipping_id;

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
     * @return null
     */
    public function getBillingId()
    {
        return $this->billing_id;
    }

    /**
     * @param null $billing_id
     */
    public function setBillingId($billing_id)
    {
        $this->billing_id = $billing_id;
    }

    /**
     * @return null
     */
    public function getCartSessionKey()
    {
        return $this->cart_session_key;
    }

    /**
     * @param null $cart_session_key
     */
    public function setCartSessionKey($cart_session_key)
    {
        $this->cart_session_key = $cart_session_key;
    }

    /**
     * @return null
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * @param null $coupon
     */
    public function setCoupon($coupon)
    {
        $this->coupon = $coupon;
    }

    /**
     * @return null
     */
    public function getShippingId()
    {
        return $this->shipping_id;
    }

    /**
     * @param null $shipping_id
     */
    public function setShippingId($shipping_id)
    {
        $this->shipping_id = $shipping_id;
    }

    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param null $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return null
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param null $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

}