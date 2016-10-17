module.exports = function (grunt) {
    'use strict';

    var env = grunt.option('env') || 'prod';
    grunt.config('env', env);
    console.log('Environment: ' + env);

    grunt.config('locales', [
        'uk',
        'en',
        'ru'
    ]);

    grunt.initConfig({
        jshint: {
            files: [],
            options: {
                loopfunc: true,
                globals: {
                    jQuery: true,
                    console: true,
                    module: true
                }
            }
        },
        uglify: {
            messages: {
                files: (function () {
                    var files = {}, locale;
                    grunt.config('locales').forEach(function (locale) {
                        files['Resources/public/js/messages.' + locale + '.js'] = [
                            'Resources/assets/components/*/messages.' + locale + '.js'
                        ];
                    });
                    return files;
                })()
            },
        },
        jade: {
            components: {
                options: {
                    client: true,
                    debug: grunt.config('env') !== 'prod',
                    compileDebug: grunt.config('env') !== 'prod',
                    processName: function(filename) {
                        var path = require('path');
                        return path.basename(filename, '.jade');
                    }
                },
                files: {
                    "Resources/public/js/components.jade.js": [
                        "Resources/assets/components/staticPageEditor/*.jade",
                        "Resources/assets/components/staticPageList/*.jade",
                        "Resources/assets/components/staticPagesPage/*.jade"
                    ]
                }
            }
        },
        copy: {
            tinymce: {
                expand: true,
                cwd: 'bower_components/tinymce/',
                src: [
                    '**'
                ],
                dest: 'Resources/public/js/tinymce'
            }
        },
        watch: {
            project: {
                files: [
                    'Resources/assets/**/*'
                ],
                tasks: ['build'],
                options: {},
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-jade');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-newer');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('build', [
        'newer:jade', 'copy', 'uglify'
    ]);

    grunt.registerTask('listen', [
        'watch'
    ]);

    grunt.registerTask('default', [
        'build'
    ]);
};