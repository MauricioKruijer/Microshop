<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 16/06/15
 * Time: 20:42
 */

namespace Microshop\Models;


use Microshop\Utils\BasicObject;

class Photo extends BasicObject {
    private $width;
    private $height;
    private $path;
    private $type;
    private $checksum;

    public function __construct($photo) {
        if(!isset($photo['name'])) throw new \Exception("Photo (file)name is mandatory");

        $this->id = (isset($photo['id']) ? $photo['id'] : false);
        $this->name = $photo['name'];

        $this->width = (isset($photo['width']) ? $photo['width'] : null);
        $this->height = (isset($photo['height']) ? $photo['height'] : null);
        $this->path = (isset($photo['path']) ? $photo['path'] : null);
        $this->type = (isset($photo['type']) ? $photo['type'] : null);
        $this->checksum = (isset($photo['checksum']) ? $photo['checksum'] : null);

        $this->created_time = (isset($photo['created_time']) ? $photo['created_time'] : null);
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
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

    /**
     * @return mixed
     */
    public function getChecksum()
    {
        return $this->checksum;
    }

    /**
     * @param mixed $checksum
     */
    public function setChecksum($checksum)
    {
        $this->checksum = $checksum;
    }

}