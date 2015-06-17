/**
 * Created by Mauricio on 17/06/15.
 */
'use strict';

angular.module('Microshop.cart.cart-directive', []).
    directive("cartButton", ['Cart', '$interpolate', function(Cart, $interpolate) {
        //var linkFn;
        //
        //linkFn = function(scope, el, attr, fromCtrl) {
        //    scope.totalCount = Cart.totals();
        //    console.log(scope.totalCount);
        //    return;
        //};
        //return {
        //    restrict: 'A',
        //    link: linkFn
        //}
        return {
            restrict: 'AE',
            replace: true,
            template: '<a ng-href="#/cart">Cart <span class="badge">{{cart.total_items}}</span></a>',
            link: function(scope, elem, attrs) {
                scope.cart = Cart.totals();
                scope.$on('update-badge-count', function() {
                    console.log("DIRECTIVE EVENT FIRED!");
                    scope.cart = Cart.totals();
                });
                elem.find('span').css('background-color', "#4caf50")
                //elem.bind('click', function() {
                //    elem.css('background-color', 'white');
                //    scope.$apply(function() {
                //        scope.color = "white";
                //    });
                //});
                //elem.bind('mouseover', function() {
                //    elem.css('cursor', 'pointer');
                //});
            }
        }
    }]);