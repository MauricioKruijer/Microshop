/**
 * Created by Mauricio on 15/06/15.
 */
'use strict';

angular.module('Microshop.cart', [
    'Microshop.cart.cart-service',
    'Microshop.cart.cart-directive',
    'Microshop.cart.cart-list'
]).value('cart.api_endpoint', 'http://microshop.dev:8888/shoppingcart/:productId');