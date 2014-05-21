define([
    'angular',
    'angular-ui-router',
    'module/Example',
    'module/KapLogin',
    'module/KapFileManager'
], function(angular) {

    var module = angular.module('MyApp', [
        'ui.router',
        'Example',
        'KapLogin',
        'KapFileManager'
    ]);

    module.config(function($stateProvider, $urlRouterProvider) {
        
        // For any unmatched url, redirect to ...
        $urlRouterProvider.otherwise("/");
        
        // Now set up the states
        $stateProvider
            .state('home', {
                url: "/",
                templateUrl: "template/MyApp/home.html"
            })
            .state('example', {
                url: "/example",
                templateUrl: "template/Example/example.html"
            })
            .state('example-file-manager', {
                url: "/example/file-manager",
                templateUrl: "template/Example/file-manager.html"
            })
            .state('login', {
                url: "/login",
                templateUrl: "template/KapLogin/login.html",
                controller: 'loginController'
            })
    });

    return module;
});