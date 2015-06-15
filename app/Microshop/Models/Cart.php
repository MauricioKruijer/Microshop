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

    public $totalPrice = 0;

    public function __construct() {
        Session::start();
        $this->restoreProductsFromSession(Session::read("products"));
//        array(
//            "products" => array(
//                'product_id' => ''
//            )
//        )
    }
    public function addProductById($productId) {
        if(!isset($this->products[$productId])) {
            $this->products[$productId] = 1;
            Session::add("products", ["id_" . $productId => 1]);
        } else {
            $this->products[$productId] +=1;
            Session::add("products", ["id_" . $productId => $this->products[$productId]]);

        }
    }
    public function restoreProductsFromSession($products) {
        if(!empty($products)) {
            foreach($products as $productId => $productCount) {
                $this->products[ (int) str_replace("id_", "", $productId) ] = $productCount;
            }
        }
    }
    public function addProduct(Product $product, $addToFlat = false){
        if(!in_array($product, self::$productsList)) {
            self::$productsList[] = $product;
        }
        if($addToFlat) $this->addProductById($product->getId());
//        $this->addProductById($product->getId());

        return true;
        if(isset($this->products[$product->getId()])) {
            $this->products[$product->getId()]['count'] += 1;
//            Session::write($product->getId(), ['count'=> $this->products[$product->getId()]['count']] );

        } else {
            $this->products[$product->getId()] = $product;
            $this->products[$product->getId()]['count'] = 1;

//            Session::write($product->getId(), ['count'=> 0] );
        }
    }

    public function removeProductById($productId, $quantity = 1){
        if($quantity <= 0) throw new \Exception("Quantity must set higher then 0");
        if(isset($this->products[$productId])) {

        }
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
