<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 13/06/15
 * Time: 15:22
 */

namespace Microshop\Models;


class User extends \Microshop\Utils\BasicObject {
    private $email,
        $first_name,
        $last_name,
        $password,
        $shipping_id,
        $billing_id;
    private $password_hash, $user_session_key;

    function __construct($user) {
        if(!isset($user['first_name'])) throw new \Exception("First name is mandatory");
        if(!isset($user['last_name'])) throw new \Exception("Last name is mandatory");
        if(!isset($user['email'])) throw new \Exception("User email is mandatory");
        if(!isset($user['password'])) throw new \Exception("User password is mandatory");

        $this->id = (isset($user['id']) ? $user['id'] : false);
        $this->first_name = $user['first_name'];
        $this->last_name = $user['last_name'];
        $this->email = $user['email'];
        $this->password = $user['password'];
        $this->password_hash = (isset($user['password_hash']) ? $user['password_hash'] : null);
        $this->user_session_key = (isset($user['user_session_key']) ? $user['user_session_key'] : null);
        $this->shipping_id = (isset($user['shipping_id']) ? $user['shipping_id'] : null);
        $this->billing_id = (isset($user['billing_id']) ? $user['billing_id'] : null);
        $this->created_time = (isset($user['created_time']) ? $user['created_time'] : null);
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param mixed $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
     * @return mixed
     */
    public function getPasswordHash()
    {
        return $this->password_hash;
    }

    /**
     * @param mixed $password_hash
     */
    public function setPasswordHash($password_hash)
    {
        $this->password_hash = $password_hash;
    }

    /**
     * @return mixed
     */
    public function getUserSessionKey()
    {
        return $this->user_session_key;
    }

    /**
     * @param mixed $user_session_key
     */
    public function setUserSessionKey($user_session_key)
    {
        $this->user_session_key = $user_session_key;
    }

}