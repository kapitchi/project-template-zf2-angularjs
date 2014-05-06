require.config({
    baseUrl: '/',
    paths: {
        jquery: 'vendor/jquery/dist/jquery',
        angular: 'vendor/angular/angular'
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
