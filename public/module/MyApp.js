define([
    'angular',
    'angular-ui-router',
    'angular-xeditable',
    'module/Example',
    'module/KapLogin',
    'module/KapFileManager',
    'module/KapAlbum'
], function(angular) {

    var module = angular.module('MyApp', [
        'ui.router',
        'xeditable',
        'Example',
        'KapLogin',
        'KapFileManager',
        'KapAlbum'
    ]);

    module.config(function($stateProvider, $urlRouterProvider, $provide) {
        
        // For any unmatched url, redirect to ...
        $urlRouterProvider.otherwise("/");
        
        // Now set up the states
        $stateProvider
            .state('app', {
                abstract: true
            })
            .state('app.home', {
                url: "/",
                templateUrl: "template/MyApp/home.html"
            })
            .state('app.login', {
                url: "/login",
                templateUrl: "template/KapLogin/login.html",
                controller: 'loginController'
            })
            .state('example', {
                url: "/example",
                templateUrl: "template/Example/example.html"
            })
            .state('example-file-manager', {
                url: "/example/file-manager",
                templateUrl: "template/Example/file-manager.html"
            })
            .state('example-album', {
                url: "/example/album",
                templateUrl: "template/Example/album.html"
            })

        //TODO FIX http://stackoverflow.com/questions/21714655/angular-js-angular-ui-router-reloading-current-state-refresh-data
        $provide.decorator('$state', function($delegate, $stateParams) {
            $delegate.forceReload = function() {
                return $delegate.go($delegate.current, $stateParams, {
                    reload: true,
                    inherit: false,
                    notify: true
                });
            };
            return $delegate;
        });
        
    });

    module.run(function(editableOptions, $rootScope) {
        editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
        
    });

    return module;
});