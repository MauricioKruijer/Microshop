<?php
namespace Microshop\Models;


use Microshop\Utils\BasicObject;

/**
 * Class Photo
 *
 * Used to store uploaded photo information
 *
 * @package Microshop\Models
 */
class Photo extends BasicObject {
    /**
     * Image width
     *
     * @var int
     */
    private $width;
    /**
     * Image height
     *
     * @var int
     */
    private $height;
    /**
     * Local image path
     *
     * @var string
     */
    private $path;
    /**
     * Image type
     *
     * @example image/jpeg
     * @var string
     */
    private $type;
    /**
     * Image checksum
     *
     * @todo make use of it or get rid of it
     * @var string
     */
    private $checksum;

    /**
     * Converts photo array to Photo object
     *
     * @todo use getters and setters
     * @param $photo
     * @throws \Exception
     */
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
     * Get photo width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set photo width
     *
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Get photo height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set photo height
     *
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Get photo path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set photo path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get photo type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set photo type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get photo checksum
     *
     * @return string
     */
    public function getChecksum()
    {
        return $this->checksum;
    }

    /**
     * Set photo SHA1 checksum
     *
     * @param string $checksum
     */
    public function setChecksum($checksum)
    {
        $this->checksum = $checksum;
    }

}