/**
 * Created by Mauricio on 14/06/15.
 */
'use strict';

angular.module('Microshop.products.product-list', ['ngRoute']).
    config(['$routeProvider', function($routeProvider) {
        $routeProvider.when('/products', {
            templateUrl: 'list/product-list.html',
            controller: 'ProductListCtrl'
        });
    }]).
    controller('ProductListCtrl', ['$scope','ProductFactory', 'Product', function($scope, ProductFactory, Product) {
        //console.log(ProductFactory.query())
        $scope.products = Product.query();
        //$scope.products = [{
        //    id: 1,
        //    name: "Arduino Pro Mini 328",
        //    short_description: "This is a 5V Arduino running the 16MHz bootloader. Arduino Pro Mini does not come with connectors populated so that you can solder in any connector or wire with any orientation you need. ",
        //    price: 995,
        //    image: "/images/product1/1.jpg"
        //}, {
        //    id: 2,
        //    name: "Breadboard Full-Size (Bare)",
        //    short_description: "This is your tried and true full size solderless breadboard! It has 2 power buses, 10 columns, and 63 rows - with a total of 830 tie in points",
        //    price: 595,
        //    image: "/images/product2/1.jpg"
        //}, {
        //    id: 3,
        //    name: "Break Away Headers Straight",
        //    short_description: "A row of headers - break to fit. 40 pins that can be cut to any size. Used with custom PCBs or general custom headers",
        //    price: 150,
        //    image: "/images/product3/1.jpg"
        //}, {
        //    id: 4,
        //    name: "WiFi Module ESP8266",
        //    short_description: "The ESP8266 WiFi Module is a self contained SOC with integrated TCP/IP protocol stack that can give any microcontroller access to your WiFi network.",
        //    price: 695,
        //    image: "/images/product4/1.jpg"
        //}, {
        //    id: 5,
        //    name: "Hook-Up Wire Assortment (Solid Core, 22 AWG)",
        //    short_description: "An assortment of colored wires: you know it’s a beautiful thing",
        //    price: 1695,
        //    image: "/images/product5/1.jpg"
        //}, {
        //    id: 6,
        //    name: "Polymer Lithium Ion Battery 2000mAh",
        //    short_description: "These are very slim, extremely light weight batteries based on the new Polymer Lithium Ion chemistry. This is the highest energy density currently in production",
        //    price: 1295,
        //    image: "/images/product6/1.jpg"
        //}];

        //console.log("Ha dump die url dan " + api_endpoint)
    }]);