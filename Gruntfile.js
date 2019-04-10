// Inside your Gruntfile.js
module.exports = function (grunt) {
    // Define a zip task
    grunt.initConfig({
        copy: {
            main: {
                files: [
                    // includes files within path
                    {
                        expand: false,
                        src: '../../../administrator/language/en-GB/en-GB.plg_system_scrollanimation.ini',
                        dest: 'language/en-GB/en-GB.plg_system_scrollanimation.ini',
                        filter: 'isFile'
                    },
                    {
                        expand: false,
                        src: '../../../administrator/language/en-GB/en-GB.plg_system_scrollanimation.sys.ini',
                        dest: 'language/en-GB/en-GB.plg_system_scrollanimation.sys.ini',
                        filter: 'isFile'
                    },
                    {
                        expand: true,
                        cwd: '../../../media/plg_system_scrollanimation/',
                        src: '**',
                        dest: 'media/',
                        filter: 'isFile'
                    },
                ],
            },
        },

        zip: {
            'plg_system_scrollanimation.zip': [
                'form/**',
                'language/**',
                'media/**',
                'index.html',
                'scrollanimation.php',
                'scrollanimation.xml',
            ]
        },
    });

    // Load in `grunt-zip`
    grunt.loadNpmTasks('grunt-zip');
    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.task.registerTask('build', ['copy', 'zip']);
  };