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
    controller('ProductListCtrl', ['api_endpoint', function(api_endpoint) {
        //
        console.log("Ha dump die url dan " + api_endpoint)
    }]);