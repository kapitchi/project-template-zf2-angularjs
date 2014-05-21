define(['angular'], function(angular) {

    var module = angular.module('SharedRegistry', []);

    module.service('sharedRegistry', function($window) {
        function sharedRegistry() {
            var self = this;

            function getStorage() {
                var window = $window;
                
                //opened window?
                try {
                    if($window.opener) {
                        window = $window.opener;
                    }
                    
                    //test for cross-origin - let's access some property from window
                    window.document;
                    
                } catch (e) {
                    //browser throws DOMException for cross-origin errors, if it's not re-throw
                    if(!e instanceof DOMException) {
                        throw e;
                    }
                    
                    //possible cross-origin - our top window is same one
                    window = $window;
                }
                
                if(!window.top.kapRegistry) {
                    window.top.kapRegistry = {};
                }

                return window.top.kapRegistry;
            }
            
            function getItem(name) {
                var item = getStorage()[name];
                if(item === undefined) {
                    throw "sharedRegistry.getItem: Can't find item for '" +name + "'";
                }
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