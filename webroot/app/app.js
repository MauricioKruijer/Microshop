'use strict';

// Declare app level module which depends on views, and components
angular.module('Microshop', [
    'ngRoute',
    'Microshop.view1',
    'Microshop.view2',
    'Microshop.products',
    'Microshop.cart',
    'Microshop.version'
]).
config(['$routeProvider', function($routeProvider) {
  $routeProvider.otherwise({redirectTo: '/products'});
}]);
