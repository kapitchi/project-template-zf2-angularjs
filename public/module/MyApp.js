define(['angular', 'angular-bootstrap'], function(angular) {

    var module = angular.module('MyApp', ['ui.bootstrap']);

    module.controller('demoController', function($scope, $http, $timeout) {

        $scope.items = [];
        $scope.totalItems = 0;
        $scope.currentPage = 1;
        $scope.pageSize = 0;
        $scope.loading = false;

        //init
        $timeout(function() {
            $scope.refreshData();
        });

        //scope functions
        $scope.pageChanged = function() {
            $scope.refreshData();
        };

        $scope.refreshData = function() {
            $scope.loading = true;

            $http.get('/example', {
                params: {
                    page: $scope.currentPage
                }
            }).success(function(data) {
                $scope.totalItems = data.total_items;
                $scope.pageSize = data.page_size;
                $scope.items = data._embedded.example;

                $scope.loading = false;
            });
        };

    });

    //TODO bootstrap MyApp on document
    angular.bootstrap(document, ['MyApp']);

    return module;
});