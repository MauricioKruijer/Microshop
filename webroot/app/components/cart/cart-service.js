/**
 * Created by Mauricio on 17/06/15.
 */
'use strict';

angular.module('Microshop.cart.cart-service', ['ngResource']).
    factory('Cart', ['$resource', 'cart.api_endpoint', function($resource, api_endpoint) {
        return $resource(api_endpoint, {productId: '@_id'}, {
            totals: {
                method: "GET",
                params: {productId: 'totals'}
            }
        });
    }]);