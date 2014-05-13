define(['angular', 'module/SharedRegistry'], function(angular) {

    var module = angular.module('LoginPage', ['SharedRegistry']);
    
    module.controller('loginController', function($scope, $http, $timeout, $window, sharedRegistry) {
        var dialog;
        
        $scope.authenticationOptions = [];

        $scope.status = {
            state: 'none',
            message: ''
        };
        
        //init
        $timeout(function() {
            $http.get('/authentication-service').success(function(data) {
                $scope.authenticationOptions = data._embedded.authentication_service.filter(function(item) {
                    return item.enabled;
                });
            });
        });

        sharedRegistry.register('loginController.status', $scope, 'status');

        $scope.$watch('status', function(val) {
            if(val.state === 'authenticated') {
                $scope.status.state = 'none';
                $scope.closeDialog();
            }
        }, true);

        //scope functions
        $scope.openDialog = function(option) {
            $scope.status.state = 'authenticating';
            $scope.status.message = 'Authenticating ...';
            
            dialog = $window.open(option.redirectUri, "Login dialog", "width=1024,height=768,dialog=1,location=1,status=1,minimizable=0,close=0,dependent");
        }
        
        $scope.closeDialog = function() {
            if(!dialog) {
                return;
            }

            dialog.close();
        }
        
    });

    return module;
});