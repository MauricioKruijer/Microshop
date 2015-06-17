<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 17/06/15
 * Time: 06:13
 */

namespace Microshop\Models;


use Microshop\Utils\BasicObject;

class Billing extends BasicObject {
    private $full_address;
    private $type;
    private $user_id;

    public function __construct($billing) {
        $this->id = (isset($billing['id']) ?$billing['id'] : null );
        $this->full_address = (isset($billing['full_address']) ?$billing['full_address'] : null );
        $this->type = (isset($billing['type']) ? $billing['type'] : 1 );
        $this->user_id = (isset($billing['user_id']) ? $billing['user_id'] : null );

        $this->name = (isset($billing['name']) ?$billing['name'] : null );
        $this->is_deleted = (isset($billing['is_deleted']) ?$billing['is_deleted'] : 0 );
        $this->created_time = (isset($billing['created_time']) ?$billing['created_time'] : null );
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getFullAddress()
    {
        return $this->full_address;
    }

    /**
     * @param mixed $full_address
     */
    public function setFullAddress($full_address)
    {
        $this->full_address = $full_address;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


}