define(['angular'], function(angular) {

    var module = angular.module('SharedRegistry', []);

    module.service('sharedRegistry', function($window) {
        function sharedRegistry() {
            var self = this;

            function getStorage() {
                var window = $window;
                
                //opened window?
                if($window.opener) {
                    window = $window.opener;
                }
                
                if(!window.top.kapRegistry) {
                    window.top.kapRegistry = {};
                }

                return window.top.kapRegistry;
            }
            
            function getItem(name) {
                return getStorage()[name];
            }
            
            this.register = function(name, $scope, scopeVar) {
                var reg = getStorage();
                
                reg[name] = {
                    $scope: $scope,
                    scopeVar: scopeVar
                };
            }
            
            this.notify = function(name) {
                getItem(name).$scope.$apply();
            }
            
            this.watch = function(name, watcher) {
                var item = getItem(name);
                item.$scope.$watch(item.scopeVar, watcher);
            }
            
            this.get = function(name) {
                var item = getItem(name);
                return item.$scope[item.scopeVar];
            }
            
        }
        
        return new sharedRegistry;
    });

    return module;
});