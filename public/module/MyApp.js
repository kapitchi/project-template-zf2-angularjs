define(['angular'], function(angular) {

    var module = angular.module('MyApp', []);

    module.controller('welcomeController', function($scope) {
        $scope.message = 'This is it!';
    });

    angular.bootstrap(document, ['MyApp']);

    return module;
});