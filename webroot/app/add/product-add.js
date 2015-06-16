/**
 * Created by Mauricio on 16/06/15.
 */
'use strict';

angular.module('Microshop.products.product-add', ['ngRoute', 'monospaced.elastic', 'flow']).
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
            console.log('catchAll', arguments);
        });
    }]).
    controller("ProductAddCtrl", ['$scope', 'ProductFactory', function($scope, ProductFactory) {
        $scope.description = "";
    }]);