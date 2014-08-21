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
                    },
                    albumItems: function($http, album) {
                        return $http.get('/album_item?' + $.param({
                                query: {
                                    album_id: album.id
                                },
                                page_size: 9999
                        })).then(function(data) {
                            var items = data.data._embedded.album_item;
                            return items;
                        });
                    }
                }
            })
            .state('app.album.newItem', {
                url: "/new-item",
                views: {
                    'container@': {
                        controller: 'albumItemEditController',
                        templateUrl: "template/KapAlbum/album-item-edit.html"
                    }
                },
                resolve: {
                    albumItem: function() {
                        return {
                            id: null,
                            title: 'New title'
                        };
                    }
                }
            })
            .state('app.album.editItem', {
                url: "/edit-item/:albumItemId",
                views: {
                    'container@': {
                        controller: 'albumItemEditController',
                        templateUrl: 'template/KapAlbum/album-item-edit.html'
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
            .state('app.album.viewItem', {
                url: "/showcase",
                views: {
                    'container@': {
                        controller: 'albumItemViewController',
                        templateUrl: 'template/KapAlbum/album-item-view.html'
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
                    albumItem: function($http, $stateParams, albumItems) {
                        //TODO
                        return true;
                    }
                }
            })
            ;
    });

    module.controller('albumController', function($scope, $state, $stateParams, $timeout, $sce, $http, $modal, album, albumItems) {
        $scope.album = album;
        $scope.items = albumItems;

        //init
        $timeout(function() {
            $scope.reload();
        });
        
        $scope.reload = function() {
            $state.forceReload();
        }
        
        $scope.getImageUrl = function(file) {
            return '/file-access?id='+ file.id;
        }
        
        $scope.reload = function() {
            
        };
        
        $scope.addItemAfter = function(item) {
            $state.go('.newItem', $stateParams);
        };

        $scope.editItem = function(item) {
            $state.go('.editItem', {
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
        
        $scope.edit = function(item)
        {
            $state.go('.editItem', {
                albumItemId: item.id
            });
        }
    });

    module.controller('albumItemViewController', function($scope, $state, $stateParams, $timeout, $http, $modal, $sce, album, albumItems) {
        
        $scope.album = album;
        
        $scope.currentIndex = 0;
        if($stateParams.index) {
            $scope.currentIndex = parseInt($stateParams.index);
        }
        
        $scope.item = albumItems[$scope.currentIndex];
        
        $scope.hasPrevious = function() {
            return $scope.currentIndex > 0;
        }
        
        $scope.hasNext = function() {
            return $scope.currentIndex < (albumItems.length - 1);
        }
        
        $scope.showNext = function() {
            $stateParams.index++;
            $scope.currentIndex++;
            $scope.item = albumItems[$scope.currentIndex];
        }

        $scope.showPrevious = function() {
            $stateParams.index--;
            $scope.currentIndex--;
            $scope.item = albumItems[$scope.currentIndex];
        }

        $scope.getIframeUrl = function(albumItem) {
            return $sce.trustAsResourceUrl(albumItem.url);
        }

        $scope.getYoutubeVideoEmbedUrl = function(albumItem) {
            var url = 'http://www.youtube.com/embed/' + albumItem.youbube_video_id;
            return $sce.trustAsResourceUrl(url);
        }

        $scope.reload = function() {
            $state.forceReload();
        }

        $scope.edit = function(item)
        {
            $state.go('.editItem', {
                albumItemId: item.id
            });
        }
    });

    module.controller('albumItemEditController', function($scope, $state, $modal, albumService, album, albumItem) {
//        var item = {
//            title: 'New title',
//            description: 'Description ...',
//            type: 'EMBED_URL',
//            url: ''
//        };
        
        var previousState = '^';
        
        $scope.item = albumItem;

        $scope.save = function(item) {
            albumService.saveItem(item, album).then(function() {
                $state.go(previousState);
            }, function() {
                console.log('error'); //XXX
            });
        }

        $scope.cancel = function() {
            $state.go(previousState);
        }

        $scope.changeType = function() {

        }
        
    });

    module.service('apiService', function($http, $q) {
        var self = this;

        var baseUrl = '/';
        
        function get(service) {
            var deferred = $q.defer();

            $http.get(baseUrl + service).success(function(data) {
                deferred.resolve(data);
            }).error(function(data, status, headers) {
                console.error("HTTP GET", data, status, headers);
                deferred.reject(status);
            });

            return deferred.promise;
        }

        function post(service, data) {
            var deferred = $q.defer();

            $http.post(baseUrl + service, data).success(function(data) {
                deferred.resolve(data);
            }).error(function(data, status, headers) {
                console.error("HTTP POST", data, status, headers);
                deferred.reject(status);
            });

            return deferred.promise;
        }

        function put(service, id, data) {
            var deferred = $q.defer();

            $http.put(baseUrl + service + '/' + id, data).success(function(data) {
                deferred.resolve(data);
            }).error(function(data, status, headers) {
                console.error("HTTP PUT", data, status, headers);
                deferred.reject(status);
            });

            return deferred.promise;
        }

        function remove(service, id) {
            var deferred = $q.defer();

            $http.delete(baseUrl + service + '/' + id).success(function(data) {
                deferred.resolve(data);
            }).error(function(data, status, headers) {
                console.error("HTTP DELETE", data, status, headers);
            });

            return deferred.promise;
        }
        
        return {
            get: get,
            create: post,
            update: put,
            remove: remove
        };
    });
    
    module.service('albumService', function(apiService) {
        this.saveItem = function(item, album) {
            if(item.id) {
                return apiService.update('album_item', item.id, item);
            }

            return apiService.create('album_item', item).then(function(albumItem) {
                apiService.create('album_items', {
                    album_item_id: albumItem.id,
                    album_id: album.id,
                    index: 0
                })
                
                return albumItem;
            });
        }
    });

    return module;
});