/**
 * Created by Mauricio on 15/06/15.
 */
'use strict';

angular.module('Microshop.products', [
    'Microshop.products.service',
    'Microshop.products.product-add',
    'Microshop.products.product-list',
    'Microshop.products.product-detail',
    'Microshop.products.add-to-cart-directive'
])
    .value('products.api_endpoint', 'http://microshop.dev:8888/products/:productId.json');
