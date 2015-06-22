<?php
namespace Microshop\Models;


/**
 * Class User
 *
 * Used to store user data
 *
 * @package Microshop\Models
 */
class User extends \Microshop\Utils\BasicObject {
    /**
     * User email
     *
     * @var string
     */
    private $email;
    /**
     * User first name
     *
     * @var string
     */
    private $first_name;
    /**
     * User last name
     *
     * @var string
     */
    private $last_name;
    /**
     * User password (stored as (PassHash) hash)
     *
     * @var string
     */
    private $password;
    /**
     * User shipping id
     *
     * @var int
     */
    private $shipping_id;
    /**
     * User billing id
     *
     * @var id
     */
    private $billing_id;
    /**
     * User
     * @var null
     */
    private $password_hash;
    /**
     * User password hash/salt
     *
     * @todo remove this, it is depricated
     * @var null
     */
    private $user_session_key;

    /**
     * Convert user array to User object
     *
     * @param $user
     * @throws \Exception
     */
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
     * Get user first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set user first name
     *
     * @param string $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * Get user last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Get user password(hash)
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set user password (hashed)
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Set user last name
     *
     * @param string $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * Get user email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set user email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get user shipping id
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
     * Get user billing id
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
     * Get password hash
     *
     * @todo remove it since it is deprecated or use it for extra salting
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->password_hash;
    }

    /**
     * Set password hash/salt
     *
     * @todo remove it since it is deprecated or use it for extra salting..
     * @param string $password_hash
     */
    public function setPasswordHash($password_hash)
    {
        $this->password_hash = $password_hash;
    }

    /**
     * Get user session key
     *
     * @return string
     */
    public function getUserSessionKey()
    {
        return $this->user_session_key;
    }

    /**
     * Set user session key
     *
     * @param string $user_session_key
     */
    public function setUserSessionKey($user_session_key)
    {
        $this->user_session_key = $user_session_key;
    }

}