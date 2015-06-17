<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 12/06/15
 * Time: 15:07
 */

namespace Microshop\Models {

    use Microshop\Utils\BasicObject;

    class Product extends BasicObject {

        private $short_description;
        private $sku;
        private $quantity;
        private $price;
        private $photo_id;
        private $description;
        private $last_updated_time;

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
         * @return null
         */
        public function getShortDescription()
        {
            return $this->short_description;
        }

        /**
         * @param null $short_description
         */
        public function setShortDescription($short_description)
        {
            $this->short_description = $short_description;
        }

        /**
         * @return mixed
         */
        public function getSku()
        {
            return $this->sku;
        }

        /**
         * @param mixed $sku
         */
        public function setSku($sku)
        {
            $this->sku = $sku;
        }

        /**
         * @return mixed
         */
        public function getDescription()
        {
            return $this->description;
        }

        /**
         * @param mixed $description
         */
        public function setDescription($description)
        {
            $this->description = $description;
        }

        /**
         * @return int
         */
        public function getQuantity()
        {
            return $this->quantity;
        }

        /**
         * @param int $quantity
         */
        public function setQuantity($quantity)
        {
            $this->quantity = $quantity;
        }

        /**
         * @return mixed
         */
        public function getPrice()
        {
            return $this->price;
        }

        /**
         * @param mixed $price
         */
        public function setPrice($price)
        {
            $this->price = $price;
        }

        /**
         * @return int
         */
        public function getPhotoId()
        {
            return $this->photo_id;
        }

        /**
         * @param int $photo_id
         */
        public function setPhotoId($photo_id)
        {
            $this->photo_id = $photo_id;
        }

        /**
         * @return mixed
         */
        public function getLastUpdatedTime()
        {
            return $this->last_updated_time;
        }

        /**
         * @param mixed $last_updated_time
         */
        public function setLastUpdatedTime($last_updated_time)
        {
            $this->last_updated_time = $last_updated_time;
        }



    }
}