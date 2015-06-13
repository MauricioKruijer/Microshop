<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 13/06/15
 * Time: 15:18
 */

namespace Microshop\Utils;

// Consider making a trait
class BasicObject {
    protected $id,
        $name,
        $is_deleted,
        $created_time;
    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getIsDeleted()
    {
        return $this->is_deleted;
    }

    /**
     * @param mixed $is_deleted
     */
    public function setIsDeleted($is_deleted)
    {
        $this->is_deleted = $is_deleted;
    }

    /**
     * @return mixed
     */
    public function getCreatedTime() {
        return $this->created_time;
    }

    /**
     * @param mixed $created_time
     */
    public function setCreatedTime($created_time) {
        $this->created_time = $created_time;
    }
}