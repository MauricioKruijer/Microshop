/**
 * Created by Mauricio on 15/06/15.
 */
'use strict';

angular.module('Microshop.products', [
    'Microshop.products.product-list'
]).value('api_endpoint', 'http://microshop.dev:8888/products.json');
