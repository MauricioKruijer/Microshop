<?php
namespace Microshop\Models;

use Microshop\Utils\Session;

/**
 * Class Cart
 *
 * Cart object used for storing cart information
 *
 * @package Microshop\Models
 */
class Cart extends \Microshop\Utils\BasicObject  {

    /**
     * Products array contains flat items
     *
     * @var array
     */

    private $products = array();
    /**
     * Product list contains array of Product objects
     *
     * @todo consider making private instead of static private
     * @var array
     */
    private static $productsList = array();

    /**
     * Sum of total currently availible in cart
     *
     * @var int
     */
    private $totalCartItems = 0;

    /**
     * Sum of total cart items value
     *
     * @var int
     */
    public $totalCartValue = 0;

    /**
     * Construct new cart object and restore items from session
     */
    public function __construct() {
        Session::start();

        $this->restoreProductsFromSession(Session::read("cart_products"));
    }

    /**
     * Add product object to productsList
     *
     * @param $productId
     * @param int $quantity
     */
    public function addProductById($productId, $quantity = 1) {
        $this->products[$productId] = $quantity;

        Session::add("cart_products", ["id_" . $productId => $quantity]);
    }

    /**
     * Restore flat products array from session
     *
     * @todo consider using private function
     * @param $products
     */
    public function restoreProductsFromSession($products) {
        if(!empty($products)) {
            foreach($products as $productId => $productCount) {
                $this->products[ (int) str_replace("id_", "", $productId) ] = $productCount;
            }
        }
    }

    /**
     * Add Product objects to productsList
     *
     * @param Product $product
     * @param int $quantity
     * @return bool
     */
    public function addProduct(Product $product, $quantity = 1){
        if(!in_array($product, self::$productsList)) {
            self::$productsList[] = $product;
        }
        $this->addProductById($product->getId(), $quantity);

        return true;
    }

    /**
     * Delete products from cart
     *
     * @todo implement function, currently the only way to remove an item from your cart is by
     * removing each item one at the time
     * @param $productId
     */
    public function deleteProductById($productId) {
        // todo
    }

    /**
     * Remove products by id from flat product array
     *
     * @todo todo refactor to substractProductById
     * @param $productId
     * @param int $quantity
     * @return int
     * @throws \Exception
     */
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
     * Get total summed cart value in cents
     *
     * @return int
     */
    public function getTotalCartValue()
    {
        return $this->totalCartValue;
    }

    /**
     * Set total summed cart value in cents
     *
     * @param int $totalCartValue
     */
    public function setTotalCartValue($totalCartValue)
    {
        $this->totalCartValue = $totalCartValue;
    }

    /**
     * Get summed total cart value in cents
     *
     * @return int
     */
    public function getTotalCartItems()
    {
        return $this->totalCartItems;
    }

    /**
     * Set total cart value in cents
     *
     * @param int $totalCartItems
     */
    public function setTotalCartItems($totalCartItems)
    {
        $this->totalCartItems = $totalCartItems;
    }

    /**
     * Get array with Product objects
     *
     * @return array
     */
    public function getProductsList() {
        return self::$productsList;
    }

    /**
     * Get flat array with product quantities in cart
     * @example returned data [id_x] => y // X is product_id and Y is quantity
     * @return array
     */
    public function getProducts() {
        return $this->products;
    }
}
