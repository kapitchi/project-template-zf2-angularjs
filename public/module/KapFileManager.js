define([
    'angular',
    'angular-bootstrap',
    'angular-file-upload'
], function(angular) {

    var module = angular.module('KapFileManager', ['ui.bootstrap', 'angularFileUpload']);

    module.controller('fileManagerController', function($scope, $rootScope, $timeout, $http, $fileUploader) {

        $scope.uploadFilesAction = false;
        $scope.createFolderAction = false;
        
        $scope.navigation = [];
        
        $scope.rootFile = {
            id: 1,
            name: 'Root',
            type: 'DIR'
        };
        
        $scope.navigation.push($scope.rootFile);
        $scope.file = $scope.rootFile;
        
        //init
        $timeout(function() {
            $scope.reloadCurrentFile();
        });

        $scope.openUploadFile = function(file)
        {
            file.$uploadFile = true;
        };

        $scope.closeUploadFile = function(file)
        {
            file.$uploadFile = false;
        }

        $scope.openCreateFolder = function(file)
        {
            file.$createFolder = true;
            
            file.$createFolderData = {
                name: ''
            };
        };
        
        $scope.closeCreateFolder = function(file)
        {
            file.$createFolder = false;
        }
        
        $scope.createFolder = function(file)
        {
            var data = angular.extend({
                parent_id: file.id
            }, file.$createFolderData);
            
            $http.post('/file', data).success(function(data) {
                $scope.closeCreateFolder(file);
                $scope.reloadFile(file);
            });
        }
        
        $scope.isMimeType = function(file, type) {
            return file.mime_type.indexOf(type) === 0;
        };
        
        $scope.openFile = function(file) {
            var itemIndex = $scope.navigation.indexOf(file);
            if(itemIndex !== -1) {
                //remove remaining items
                $scope.navigation.splice(itemIndex + 1);
            }
            else {
                //new file -- push at the end of navigation
                $scope.navigation.push(file);
            }
            
            //current file
            $scope.file = file;
            
            loadFile(file);
        };
        
        $scope.removeFile = function(file)
        {
            $http.delete(file._links.self.href).success(function(data) {
                $scope.reloadCurrentFile();
            });
        }
        
        $scope.reloadFile = function(file)
        {
            loadFile(file, true);
        }

        $scope.reloadCurrentFile = function() {
            loadFile($scope.file, true);
        }
        
        function loadFile(file, force)
        {
            console.log(file); //XXX
            if(file.$loading) {
                file.$scheduleLoad = true;
                return;
            }
            
            if(file.type === 'DIR') {
                if(!force && file.$cachedFiles) {
                    return;
                }

                file.$loading = true;

                $http.get('/file', {
                    params: {
                        parent_id: file.id,
                        recursive: false
                    }
                }).success(function(data) {
                    file.$cachedFiles = data._embedded.file;
                    file.$loading = false;
                    
                    if(file.$scheduleLoad) {
                        file.$scheduleLoad = false;
                        loadFile(file, force);
                    }
                });
            }
            else if(file.type === 'FILE') {
                //todo do something?
            }
        }
        
        // Creates a uploader
        var uploader = $scope.uploader = $fileUploader.create({
            scope: $scope,
            url: '/file'
            //removeAfterUpload: true
        });
        
        // ADDING FILTERS

        // Images only
        uploader.filters.push(function(item /*{File|HTMLInputElement}*/) {
            var type = uploader.isHTML5 ? item.type : '/' + item.value.slice(item.value.lastIndexOf('.') + 1);
            type = '|' + type.toLowerCase().slice(type.lastIndexOf('/') + 1) + '|';
            return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
        });


        // REGISTER HANDLERS

        uploader.bind('afteraddingfile', function (event, item) {
            item.kapFormData = {
                name: item.file.name
            };
            item.formData.push(item.kapFormData);
            item.formData.push({
                'parent_id': item.parentFile.id
            });
        });

        uploader.bind('complete', function (event, xhr, item, response) {
            item.responseFile = response;
            loadFile(item.parentFile, true);
        });

        uploader.bind('completeall', function (event, items) {
            angular.forEach(items, function(item) {
                item.parentFile.$uploadFile = false;
            });
        });

        uploader.bind('whenaddingfilefailed', function (event, item) {
            console.info('When adexampleUploadding a file failed', item);
        });

        uploader.bind('afteraddingall', function (event, items) {
            console.info('After adding all files', items);
        });

        uploader.bind('beforeupload', function (event, item) {
            console.info('beforeupload', item);
        });

        uploader.bind('progress', function (event, item, progress) {
            console.info('Progress: ' + progress, item);
        });

        uploader.bind('success', function (event, xhr, item, response) {
            console.info('Success', xhr, item, response);
        });

        uploader.bind('cancel', function (event, xhr, item) {
            console.info('Cancel', xhr, item);
        });

        uploader.bind('error', function (event, xhr, item, response) {
            console.info('Error', xhr, item, response);
        });

        uploader.bind('progressall', function (event, progress) {
            console.info('Total progress: ' + progress);
        });
        
    });

    /**
     * The ng-thumb directive
     * @author: nerv
     * @version: 0.1.2, 2014-01-09
     */
    module.directive('fileUploadThumb', ['$window', function($window) {
        var helper = {
            support: !!($window.FileReader && $window.CanvasRenderingContext2D),
            isFile: function(item) {
                return angular.isObject(item) && item instanceof $window.File;
            },
            isImage: function(file) {
                var type =  '|' + file.type.slice(file.type.lastIndexOf('/') + 1) + '|';
                return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
            }
        };

        return {
            restrict: 'A',
            template: '<canvas/>',
            link: function(scope, element, attributes) {
                if (!helper.support) return;

                var params = scope.$eval(attributes.fileUploadThumb);

                if (!helper.isFile(params.file)) return;
                if (!helper.isImage(params.file)) return;

                var canvas = element.find('canvas');
                var reader = new FileReader();

                reader.onload = onLoadFile;
                reader.readAsDataURL(params.file);

                function onLoadFile(event) {
                    var img = new Image();
                    img.onload = onLoadImage;
                    img.src = event.target.result;
                }

                function onLoadImage() {
                    var width = params.width || this.width / this.height * params.height;
                    var height = params.height || this.height / this.width * params.width;
                    canvas.attr({ width: width, height: height });
                    canvas[0].getContext('2d').drawImage(this, 0, 0, width, height);
                }
            }
        };
    }]);

    return module;
});