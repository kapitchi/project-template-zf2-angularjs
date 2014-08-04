define([
    'angular',
    'jquery',
    'angular-bootstrap'
], function(angular, $) {

    var module = angular.module('KapAlbum', ['ui.bootstrap']);

    module.config(function($stateProvider) {

        $stateProvider
            .state('app.album', {
                url: "/album/:albumId",
                views: {
                    'container@': {
                        templateUrl: "template/KapAlbum/album.html",
                        controller: 'albumController'
                    }
                },
                resolve: {
                    album: function($http, $stateParams) {
                        return $http.get('/album/' + $stateParams.albumId).then(function(response) {
                            return response.data;
                        });
                    }
                }
            })
            .state('app.album.newItem', {
                url: "/new-item",
                views: {
                    'container@': {
                        templateUrl: "template/KapAlbum/album-item-edit.html",
                        controller: function($scope, $stateParams, $state, album) {
                            if($stateParams.newItem) {
                                
                            }
                            
                            console.log($stateParams.newItem); //XXX
                            console.log($state); //XXX
                            console.log(album); //XXX
                        }
                    }
                }
            })
            .state('app.album.item', {
                url: "/item/:albumItemId",
                views: {
                    'container@': {
                        controller: 'albumItemController',
                        templateUrl: 'template/KapAlbum/album-item.html'
                    }
                },
                resolve: {
                    albumItem: function($http, $stateParams) {
                        return $http.get('/album_item/' + $stateParams.albumItemId).then(function(response) {
                            return response.data;
                        });
                    }
                }
            })
            .state('app.album.item.edit', {
                url: "/edit",
                views: {
                    'container@': {
                        controller: 'albumItemEditController'
                        //templateUrl: 'template/KapAlbum/album-item-edit.html'
                    }
                }
            });
    });

    module.controller('albumController', function($scope, $state, $stateParams, $timeout, $http, $modal, album) {
        $scope.album = album;
        $scope.items = [];

        //init
        $timeout(function() {
            $scope.reload();
        });

        $scope.reload = function() {
            $http.get('/album_item?' + $.param({
                query: {
                    album_id: album.id
                },
                page_size: 9999
            })).success(function(data) {
                $scope.items = data._embedded.album_item;

                //$scope.nextItemUrl = data._links.next ? data._links.next.href : null;
                //$scope.previousItemUrl = data._links.previous ? data._links.previous.href : null;
            });
        };
        
        $scope.addItemAfter = function(item) {
            var newItem = {
                title: 'New title',
                description: 'Description ...',
                type: 'EMBED_URL',
                url: '',
                index: item.index + 1
            };
            
            //$scope.items.splice($scope.items.indexOf(item) + 1, 0, newItem);
            
            $state.go('.newItem', $stateParams);
            
            //modal.close();
        };

        $scope.editItem = function(item) {
            $state.go('.item.edit', {
                albumItemId: item.id
            });
        };
    });

    module.controller('albumItemController', function($scope, $state, $timeout, $http, album, albumItem) {
        $scope.album = album;
        $scope.item = albumItem;
        
        $scope.reload = function() {
            $state.forceReload();
        }
        
        $scope.edit = function()
        {
            $state.go('.edit');
        }
    });

    module.controller('albumItemEditController', function($scope, $state, $modal, albumService, album, albumItem) {
//        var item = {
//            title: 'New title',
//            description: 'Description ...',
//            type: 'EMBED_URL',
//            url: ''
//        };
        
        var previousState = '^.^';
        
        var modalInstance = $modal.open({
            templateUrl: "template/KapAlbum/album-item-edit.html",
            controller: function($scope, $modalInstance) {
                $scope.album = album;
                $scope.item = albumItem;
                
                $scope.save = function() {
                    albumService.saveItem($scope.item).then(function() {
                        $modalInstance.close();
                        $state.go(previousState);
                    }, function() {
                        console.log('error'); //XXX
                    });
                }
                
                $scope.cancel = function() {
                    $modalInstance.dismiss('ERROR');
                    $state.go(previousState);
                }
                
                $scope.changeType = function() {
                    
                }
            },
            //keyboard: false,
            size: 'lg'
        });
        
        function save(item) {
            albumService.saveItem(item);
        }
        
    });
    
    module.service('albumService', function($http) {
        this.saveItem = function(item) {
            delete item._links;
            
            if(item.id) {
                return $http.put('/album_item/' + item.id, item);
            }
            
            return $http.post('/album_item', item);
        }
    });

    return module;
});