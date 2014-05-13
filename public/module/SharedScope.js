define(['angular'], function(angular) {

    var module = angular.module('SharedScope', []);

    module.service('sharedScope', function($window, $rootScope) {
        function sharedScope() {
            var self = this;
            
            this.register = function($scope, name, value) {
                var reg = self.init();
                reg[name] = value;
                return reg[name];
            }
            
            this.watch = function(name, watcher, timeout) {
                if(!timeout) {
                    timeout = 1000;
                }
                
                $timeout(function() {
                    
                })
            }
            
            this.get = function(name) {
                return init()[name];
            }
            
            this.init = function() {
                if(!$window.top.kapRegistry) {
                    $window.top.kapRegistry = {};
                }

                return $window.top.kapRegistry;
            }
        }
        
        return new sharedRegistry;
    });

    return module;
});