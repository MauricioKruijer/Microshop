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
        $password;

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
}