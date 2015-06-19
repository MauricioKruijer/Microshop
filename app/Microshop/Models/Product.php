<?php
namespace Microshop\Models {

    use Microshop\Utils\BasicObject;

    /**
     * Class Product
     *
     * Used to store product information
     *
     * @package Microshop\Models
     */
    class Product extends BasicObject {

        /**
         * Short product description used for overview pages
         *
         * @var string
         */
        private $short_description;
        /**
         * Product SKU
         *
         * @var string
         */
        private $sku;
        /**
         * Product quantity (stock)
         *
         * @var int
         */
        private $quantity;
        /**
         * Product price in cents
         *
         * @var int
         */
        private $price;
        /**
         * Product photo id
         *
         * @var int
         */
        private $photo_id;
        /**
         * Product description full text
         *
         * @var string
         */
        private $description;
        /**
         * Product last updated timestamp ISO 8601
         *
         * @var string
         */
        private $last_updated_time;

        /**
         * Converts product array to Product object
         *
         * @param $product
         * @throws \Exception
         */
        function __construct($product) {
            if(!isset($product['name'])) throw new \Exception("Product name is mandatory");

            $this->id = (isset($product['id']) ? $product['id'] : false);
            $this->name = $product['name'];

            $this->short_description = (isset($product['short_description']) ? $product['short_description'] : null);
            $this->sku = (isset($product['sku']) ? $product['sku'] : null);
            $this->quantity = (isset($product['quantity']) && $product['quantity'] >= 0 ? $product['quantity'] : 0);
            $this->price = (isset($product['price']) && $product['price'] >= 0 ? $product['price'] : 0);
            $this->photo_id = (isset($product['photo_id']) && $product['photo_id'] >= 0 ? $product['photo_id'] : 0);
            $this->description = (isset($product['description']) ? $product['description'] : null);

            $this->is_deleted = (isset($product['is_deleted']) && !empty($product['is_deleted']) ? $product['is_deleted'] : 0);


            $this->created_time = (isset($product['created_time']) ? $product['created_time'] : date('c'));
        }

        /**
         * Get short description
         *
         * @return string
         */
        public function getShortDescription()
        {
            return $this->short_description;
        }

        /**
         * Set short description
         *
         * @param string $short_description
         */
        public function setShortDescription($short_description)
        {
            $this->short_description = $short_description;
        }

        /**
         * Get SKU
         *
         * @return string
         */
        public function getSku()
        {
            return $this->sku;
        }

        /**
         * Set SKU
         *
         * @param string $sku
         */
        public function setSku($sku)
        {
            $this->sku = $sku;
        }

        /**
         * Get description
         *
         * @return string
         */
        public function getDescription()
        {
            return $this->description;
        }

        /**
         * Set description
         *
         * @param string $description
         */
        public function setDescription($description)
        {
            $this->description = $description;
        }

        /**
         * Get quantity (stock)
         *
         * @return int
         */
        public function getQuantity()
        {
            return $this->quantity;
        }

        /**
         * Set quantity
         *
         * @param int $quantity
         */
        public function setQuantity($quantity)
        {
            $this->quantity = $quantity;
        }

        /**
         * Get product price in cents
         *
         * @return int
         */
        public function getPrice()
        {
            return $this->price;
        }

        /**
         * Set product price in cents
         *
         * @param int $price
         */
        public function setPrice($price)
        {
            $this->price = $price;
        }

        /**
         * Get product photo id
         *
         * @return int
         */
        public function getPhotoId()
        {
            return $this->photo_id;
        }

        /**
         * Set product photo id
         *
         * @param int $photo_id
         */
        public function setPhotoId($photo_id)
        {
            $this->photo_id = $photo_id;
        }

        /**
         * Get last updated time
         *
         * @return string
         */
        public function getLastUpdatedTime()
        {
            return $this->last_updated_time;
        }

        /**
         * Set last updated time
         *
         * @param string $last_updated_time
         */
        public function setLastUpdatedTime($last_updated_time)
        {
            $this->last_updated_time = $last_updated_time;
        }

    }
}