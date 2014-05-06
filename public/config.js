require.config({
    baseUrl: '/',
    paths: {
        jquery: 'vendor/jquery/dist/jquery',
        angular: 'vendor/angular/angular',
        affix: 'vendor/bootstrap-sass-official/vendor/assets/javascripts/bootstrap/affix',
        alert: 'vendor/bootstrap-sass-official/vendor/assets/javascripts/bootstrap/alert',
        button: 'vendor/bootstrap-sass-official/vendor/assets/javascripts/bootstrap/button',
        carousel: 'vendor/bootstrap-sass-official/vendor/assets/javascripts/bootstrap/carousel',
        collapse: 'vendor/bootstrap-sass-official/vendor/assets/javascripts/bootstrap/collapse',
        dropdown: 'vendor/bootstrap-sass-official/vendor/assets/javascripts/bootstrap/dropdown',
        tab: 'vendor/bootstrap-sass-official/vendor/assets/javascripts/bootstrap/tab',
        transition: 'vendor/bootstrap-sass-official/vendor/assets/javascripts/bootstrap/transition',
        scrollspy: 'vendor/bootstrap-sass-official/vendor/assets/javascripts/bootstrap/scrollspy',
        modal: 'vendor/bootstrap-sass-official/vendor/assets/javascripts/bootstrap/modal',
        tooltip: 'vendor/bootstrap-sass-official/vendor/assets/javascripts/bootstrap/tooltip',
        popover: 'vendor/bootstrap-sass-official/vendor/assets/javascripts/bootstrap/popover',
        requirejs: 'vendor/requirejs/require'
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
        affix: {
            deps: [
                'jquery'
            ],
            exports: '$.fn.affix'
        },
        alert: {
            deps: [
                'jquery'
            ],
            exports: '$.fn.alert'
        },
        button: {
            deps: [
                'jquery'
            ],
            exports: '$.fn.button'
        },
        carousel: {
            deps: [
                'jquery'
            ],
            exports: '$.fn.carousel'
        },
        collapse: {
            deps: [
                'jquery'
            ],
            exports: '$.fn.collapse'
        },
        dropdown: {
            deps: [
                'jquery'
            ],
            exports: '$.fn.dropdown'
        },
        modal: {
            deps: [
                'jquery'
            ],
            exports: '$.fn.modal'
        },
        popover: {
            deps: [
                'jquery',
                'tooltip'
            ],
            exports: '$.fn.popover'
        },
        scrollspy: {
            deps: [
                'jquery'
            ],
            exports: '$.fn.scrollspy'
        },
        tab: {
            deps: [
                'jquery'
            ],
            exports: '$.fn.tab'
        },
        tooltip: {
            deps: [
                'jquery'
            ],
            exports: '$.fn.tooltip'
        },
        transition: {
            deps: [
                'jquery'
            ],
            exports: '$.support.transition'
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
