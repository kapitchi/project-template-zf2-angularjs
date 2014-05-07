module.exports = function(grunt) {

    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        bower: {
            requirejs: {
                rjsConfig: 'public/config.js',
                options: {
                    baseUrl: './public'
                }
            }
        },
        karma: {
            unit: {
                configFile: 'test/karma.conf.js'
            }
        }
    });

};
