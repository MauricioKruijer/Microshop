<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 14/06/15
 * Time: 00:38
 */


namespace Microshop\Models;

use Microshop\Utils\Session;

class Cart extends \Microshop\Utils\BasicObject  {
    private $products = array();
    private static $productsList = array();

    private $totalCartItems = 0;

    public $totalCartValue = 0;

    public function __construct() {
        Session::start();

        $this->restoreProductsFromSession(Session::read("cart_products"));
    }

    public function addProductById($productId, $quantity = 1) {
        $this->products[$productId] = $quantity;

        Session::add("cart_products", ["id_" . $productId => $quantity]);
    }
    public function restoreProductsFromSession($products) {
        if(!empty($products)) {
            foreach($products as $productId => $productCount) {
                $this->products[ (int) str_replace("id_", "", $productId) ] = $productCount;
            }
        }
    }
    public function addProduct(Product $product, $quantity = 1){
        if(!in_array($product, self::$productsList)) {
            self::$productsList[] = $product;
        }
        $this->addProductById($product->getId(), $quantity);

        return true;
    }
    public function deleteProductById($productId) {
        // todo
    }
    // todo refactor to substractProductById
    public function removeProductById($productId, $quantity = 1){
        if($quantity <= 0) throw new \Exception("Quantity must set higher then 0");

        if(isset($this->products[$productId])) {
            if($this->products[$productId] > 0) {
                $this->products[$productId] -= $quantity;
                if($this->products[$productId] > 0)
                    return $this->products[$productId];
            }

            unset($this->products[$productId]);

            Session::remove("products", $productId);

            return 0;

        } else {
            throw new \Exception("Item cant be removed from cart");
        }
    }

    /**
     * @return int
     */
    public function getTotalCartValue()
    {
        return $this->totalCartValue;
    }

    /**
     * @param int $totalCartValue
     */
    public function setTotalCartValue($totalCartValue)
    {
        $this->totalCartValue = $totalCartValue;
    }

    /**
     * @return int
     */
    public function getTotalCartItems()
    {
        return $this->totalCartItems;
    }

    /**
     * @param int $totalCartItems
     */
    public function setTotalCartItems($totalCartItems)
    {
        $this->totalCartItems = $totalCartItems;
    }

    public function getProductsList() {
        return self::$productsList;
    }
    public function getProducts() {
        return $this->products;
    }
}
