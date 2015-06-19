<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 13/06/15
 * Time: 15:18
 */

namespace Microshop\Utils;

// Consider making a trait
/**
 * Class BasicObject
 * @package Microshop\Utils
 */
class BasicObject {
    /**
     * Id
     * @var int
     */
    protected $id;
    /**
     * Name
     * @var string
     */
    protected $name;
    /**
     * Is deleted boolean
     * @todo test saving boolean with pdo
     * @var int
     */
    protected $is_deleted;
    /**
     * Created date ISO 8601
     * @var string
     */
    protected $created_time;
    /**
     * Get id
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set id
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Get name
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set name
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Is deleted boolean
     * @todo test with pdo and boolean
     * @return int
     */
    public function getIsDeleted()
    {
        return $this->is_deleted;
    }

    /**
     * Set is deleted
     * @todo try with pdo and boolean type
     * @param int $is_deleted
     */
    public function setIsDeleted($is_deleted)
    {
        $this->is_deleted = $is_deleted;
    }

    /**
     * Get created time ISO 8601
     * @return string
     */
    public function getCreatedTime() {
        return $this->created_time;
    }

    /**
     * Set created time ISO 8601
     * @param string $created_time
     */
    public function setCreatedTime($created_time) {
        $this->created_time = $created_time;
    }
}