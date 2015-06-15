/**
 * Created by Mauricio on 15/06/15.
 */
'use strict';

angular.module('Microshop.products.product-detail', ['ngRoute']).
    config(['$routeProvider', function($routeProvider) {
        $routeProvider.when('/products/:productId', {
            templateUrl: 'detail/product-detail.html',
            controller: 'ProductDetailCtrl'
        });
    }]).
    filter('splitNewLines', function() {
        return function(text) {
            return text.split(/\n/g);
        };
    }).
    controller("ProductDetailCtrl", ['$scope', '$routeParams', '$filter','ProductFactory', function($scope, $routeParams, $filter, ProductFactory) {
        $scope.product = ProductFactory.get({productId: 6}, function(product) {
            $scope.mainImageUrl = product.images[0];
            $scope.description = $filter('splitNewLines')(product.description);
        });
        //$scope.product = {
        //    id: 1,
        //    sku: 'SEN-1337',
        //    name: "Product Name",
        //    short_description: "This is a 5V Arduino running the 16MHz bootloader. Arduino Pro Mini does not come with connectors populated so that you can solder in any connector or wire with any orientation you need. ",
        //    //description: "It\'s blue! It\'s thin! It\'s the Arduino Pro Mini! SparkFun\'s minimal design approach to Arduino. This is a 5V Arduino running the 16MHz bootloader. Arduino Pro Mini does not come with connectors populated so that you can solder in any connector or wire with any orientation you need. We recommend first time Arduino users start with the Uno R3. It\'s a great board that will get you up and running quickly. The Arduino Pro series is meant for users that understand the limitations of system voltage (5V), lack of connectors, and USB off board. <br> We really wanted to minimize the cost of an Arduino. In order to accomplish this we used all SMD components, made it two layer, etc. This board connects directly to the FTDI Basic Breakout board and supports auto-reset. The Arduino Pro Mini also works with the FTDI cable but the FTDI cable does not bring out the DTR pin so the auto-reset feature will not work. There is a voltage regulator on board so it can accept voltage up to 12VDC. If you’re supplying unregulated power to the board, be sure to connect to the \"RAW\" pin and not VCC. <br> The latest and greatest version of this board breaks out the ADC6 and ADC7 pins as well as adds footprints for optional I2C pull-up resistors!",
        //    description: "It\'s blue! It\'s thin! It\'s the Arduino Pro Mini! SparkFun\'s minimal design approach to Arduino. This is a 5V Arduino running the 16MHz bootloader. Arduino Pro Mini does not come with connectors populated so that you can solder in any connector or wire with any orientation you need. We recommend first time Arduino users start with the Uno R3. It\'s a great board that will get you up and running quickly. The Arduino Pro series is meant for users that understand the limitations of system voltage (5V), lack of connectors, and USB off board. \n We really wanted to minimize the cost of an Arduino. In order to accomplish this we used all SMD components, made it two layer, etc. This board connects directly to the FTDI Basic Breakout board and supports auto-reset. The Arduino Pro Mini also works with the FTDI cable but the FTDI cable does not bring out the DTR pin so the auto-reset feature will not work. There is a voltage regulator on board so it can accept voltage up to 12VDC. If you’re supplying unregulated power to the board, be sure to connect to the \"RAW\" pin and not VCC. \n The latest and greatest version of this board breaks out the ADC6 and ADC7 pins as well as adds footprints for optional I2C pull-up resistors!",
        //    price: 995,
        //    image: "/images/product1/1.jpg",
        //    images: [
        //        "/images/product1/1.jpg",
        //        "/images/product1/2.jpg",
        //        "/images/product1/3.jpg"
        //    ]
        //};

        $scope.changeImage = function(image) {
            $scope.mainImageUrl = image;
        }
    }]);