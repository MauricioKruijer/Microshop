/**
 * Created by Mauricio on 15/06/15.
 */
'use strict';

angular.module('Microshop.products.add-to-cart-directive', [])
    .directive('addToCart', function() {
        return {
            restrict: 'E',
            templateUrl: 'components/products/add-to-cart-directive.html',
            link: function($scope) {
                $scope.addToCart = function(product) {
                    var labelClass = "badge";
                    var addToCartCount = 0;
                    var shoppingCartBadge = jQuery("#nav-shopping-cart").find("span");
                    var shoppingCartCount = shoppingCartBadge.text();
                    var transformShow = ('' === shoppingCartCount ? true : false);

                    if(transformShow) {

                        var elemProductImage = jQuery(event.target).parents().find('img.product_image_' + product.id);

                        jQuery(elemProductImage).effect("transfer", {to: jQuery("#nav-shopping-cart")}, 750, function() {
                            shoppingCartBadge.removeClass().addClass(labelClass + " bounce animated").text(1).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                                jQuery(this).removeClass().addClass(labelClass);
                            }).css('background-color', "#4caf50");
                        });

                        jQuery('.ui-effects-transfer:last').css({
                            'background-image': 'url(' + product.image + ')',
                            'background-position': 'center',
                            'background-size': 'cover',
                            'opacity': 0.5,
                            'border-radius': '5px'
                        });

                        transformShow = false;
                    } else {
                        console.log("2nd time+", shoppingCartCount)
                        var animateEffect = "bounce";

                        if(addToCartCount >=4 ) {
                            animateEffect = "bounceIn";
                        }
                        if(addToCartCount >=9 ) {
                            animateEffect = "zoomIn";
                        }
                        if(addToCartCount >= 99 ) {
                            animateEffect = "hinge";
                        }

                        shoppingCartCount = parseInt(shoppingCartCount);
                        shoppingCartBadge.removeClass().addClass(labelClass + " " + animateEffect + " animated").text(shoppingCartCount+1).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                            jQuery(this).removeClass().addClass(labelClass);
                        }).css('background-color', "#4caf50");
                    }

                    addToCartCount++;
                }
            }
        };
    });