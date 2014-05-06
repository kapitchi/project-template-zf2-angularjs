require.config({
    baseUrl: '/',
    paths: {
        
    },
    shim: {
	jquery: {
            exports: 'jquery'
        },
        angular: {
            exports: 'angular',
            deps: [
                'jquery'
            ]
        },
        'angular-resource': {
            deps: [
                'angular'
            ]
        },
        'angular-animate': {
            deps: [
                'angular'
            ]
        },
        'angular-route': {
            deps: [
                'angular'
            ]
        }
    },
    packages: [

    ],
    deps: [

    ],
    config: {
    }
});
