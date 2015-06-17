/**
 * Created by Mauricio on 16/06/15.
 */
'use strict';

angular.module('Microshop.products.product-add', ['ngRoute', 'monospaced.elastic', 'flow', 'ui.bootstrap.showErrors']).
    config(['$routeProvider', 'flowFactoryProvider', function($routeProvider, flowFactoryProvider) {
        $routeProvider.when('/products/add', {
            templateUrl: 'add/product-add.html',
            controller: 'ProductAddCtrl'
        });

        flowFactoryProvider.defaults = {
            //target: api_endpoint.replace(':productId', 'add'),
            target: "http://microshop.dev:8888/photos/add.json",
            //target: "http://microshop.dev:8888/upload.php",
            permanentErrors: [404, 500, 501],
            maxChunkRetries: 1,
            chunkRetryInterval: 5000,
            simultaneousUploads: 4
        };
        flowFactoryProvider.on('catchAll', function (event) {
            //console.log('catchAll', arguments);
        });
    }]).
    controller("ProductAddCtrl", ['$scope', 'Product', function($scope, Product) {
        //$scope.product = {
        //    images: []
        //};

        //$scope.dropClass = "has-errors";

        $scope.product = new Product();
        $scope.product = {
            images: []
        };


        $scope.successMethod = function($file, $message, $flow ) {
            console.log("successMethod>");
            //console.log($file);
            var response = JSON.parse($message);
            if(response.success) {
                $scope.dropClass = "drop";
                $scope.product.images.push(response.success.image_id);
            }

        }

        $scope.saveNewProduct = function() {
            $scope.$broadcast('show-errors-check-validity');
            if ($scope.addProductForm.$valid) {
                //console.log("Save dem shizzle");

                if($scope.product.images.length >0) {
                    $scope.product.photo_id = $scope.product.images[0];

                    Product.save($scope.product, function(res) {
                        //console.log("saved product cb", res)
                        alert("YAY");
                        $scope.product = new Product();
                    });
                } else {
                    $scope.dropClass = "has-errors";

                    jQuery("#photoUploadThingy").removeClass().addClass("bounce animated").one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                        jQuery(this).removeClass();//.addClass(labelClass);
                    });//.css('background-color', "#4caf50");
                }
            }
        }
    }]);