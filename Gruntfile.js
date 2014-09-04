module.exports = function(grunt) {

    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        makepot: {
            target: {
                options: {
                    domainPath: '/languages',    // Where to save the POT file.
                    mainFile: 'flowplayer.php',      // Main project file.
                    potFilename: 'flowplayer5.pot',   // Name of the POT file.
                    type: 'wp-plugin'  // Type of project (wp-plugin or wp-theme).
                }
            }
        },
        po2mo: {
            files: {
                src: 'languages/*.po',
                expand: true,
            },
        },
        // Copy the plugin into the build directory
        copy: {
            main: {
                src:  [
                    '**',
                    '!node_modules/**',
                    '!build/**',
                    '!.git/**',
                    '!Gruntfile.js',
                    '!package.json',
                    '!.gitignore',
                    '!.gitmodules',
                    '!*.xml',
                    '!**/*~'
                ],
                dest: 'build/'
            }
        },

        uglify: {
            options: {
                mangle: false
            },
            admin: {
                files: [
                    {
                        expand: true,     // Enable dynamic expansion.
                        cwd: 'admin/assests/js/',
                        src: ['*.js', '!*.min.js'],
                        dest: 'admin/assets/js',
                        rename: function (dest, src) {
                            var filename = src.substring(src.lastIndexOf('/'), src.length);
                            filename = filename.substring(0, filename.lastIndexOf('.'));
                            return dest + filename + '.min.js';
                        }
                    },
                ]
            },
            frontend: {
                files: [
                    {
                        expand: true,     // Enable dynamic expansion.
                        cwd: 'frontend/assests/js/',
                        src: ['*.js', '!*.min.js'],
                        dest: 'frontend/assets/js',
                        rename: function (dest, src) {
                            var filename = src.substring(src.lastIndexOf('/'), src.length);
                            filename = filename.substring(0, filename.lastIndexOf('.'));
                            return dest + filename + '.min.js';
                        }
                    },
                ]
            }
        },

        cssmin: {
            admin: {
                expand: true,
                cwd: 'admin/assets/css/',
                src: ['*.css', '!*.min.css'],
                dest: 'admin/assets/css/',
                ext: '.min.css'
            },
            frontend: {
                expand: true,
                cwd: 'frontend/assets/css/',
                src: ['*.css', '!*.min.css'],
                dest: 'frontend/assets/css/',
                ext: '.min.css'
            }
        },


    });

    grunt.loadNpmTasks( 'grunt-wp-i18n' );
    grunt.loadNpmTasks('grunt-contrib-copy');

};


