<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 12/06/15
 * Time: 15:07
 */

namespace Microshop\Models {

    class Product  {
        private $id,
            $name,
            $created_time;

        function __construct($product) {
            if(!isset($product['name'])) throw Exception("Product name is mandatory");

            $this->id = (isset($product['id']) ? $product['id'] : false);
            $this->name = $product['name'];
            $this->created_time = (isset($product['created_time']) ? $product['created_time'] : null);
        }

        /**
         * @return mixed
         */
        public function getCreatedTime() {
            return $this->created_time;
        }

        /**
         * @return mixed
         */
        public function getId() {
            return $this->id;
        }

        /**
         * @return mixed
         */
        public function getName() {
            return $this->name;
        }

        /**
         * @param mixed $id
         */
        public function setId($id) {
            $this->id = $id;
        }

        /**
         * @param mixed $name
         */
        public function setName($name) {
            $this->name = $name;
        }

        /**
         * @param mixed $created_time
         */
        public function setCreatedTime($created_time) {
            $this->created_time = $created_time;
        }

    }
}