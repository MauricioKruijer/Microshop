/**
 * Created by Mauricio on 15/06/15.
 */
'use strict';

angular.module('Microshop.products.service', ['ngResource']).
    factory('ProductFactory', ['$resource','products.api_endpoint', function($resource, api_endpoint){
        return $resource(api_endpoint, {}, {
            query: {method: 'GET', params: {productId: 'products'}, isArray: true}
        });
    }]);