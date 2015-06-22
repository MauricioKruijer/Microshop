<?php
namespace Microshop\Models;


use Microshop\Utils\BasicObject;

/**
 * Class Billing
 *
 * Billing object used for storing billing address info
 * also being used for shipping adderess info (set by type)
 *
 * @package Microshop\Models
 */
class Billing extends BasicObject {

    /**
     * Address stored as string e.g. "Straatnaam 1337"
     *
     * @todo split up in multiple fields, address, zip etc
     * @var string
     */
    private $full_address;
    /**
     * Type is used to determine if the item is uses as Shipping address or just as billing address
     *
     * Saved as TINTYINT in MySQL
     *
     * @todo consider using constant type to enhance readability
     * @var int
     */
    private $type;
    /**
     * User id
     *
     * @var int
     */
    private $user_id;


    /**
     * Converts billing array to Biling object
     *
     * @todo use getters and setters. Consider using a trait
     * @param $billing
     */
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