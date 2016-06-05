module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    sass: {
      dist: {
        files: {
          'assets/css/<%= pkg.name %>.css': 'client/sass/<%= pkg.name %>.scss'
        }
      }
    },
    cssmin: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      target: {
        files: [{
          expand: true,
          src: 'assets/css/<%= pkg.name %>.css',
          ext: '.min.css'
        }]
      }
    },
    copy: {
      jquery: {
        files: [
          {expand: true, flatten: true, src: ['bower_components/jquery/dist/*'], dest: 'assets/js/', filter: 'isFile'},
        ],
      },
    },
    concat: {
      options: {
        separator: ';',
      },
      dist: {
        src: ['client/js/<%= pkg.name %>.js'],
        dest: 'assets/js/<%= pkg.name %>.js',
      },
    },
    jshint: {
        all: ['Gruntfile.js', 'client/**/*.js']
    },
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      dist: {
        src: 'assets/js/<%= pkg.name %>.js',
        dest: 'assets/js/<%= pkg.name %>.min.js'
      }
    },
    watch: {
      css: {
        files: ['client/**/*.scss'],
        tasks: ['sass', 'cssmin'],
      },
      js: {
        files: ['client/**/*.js'],
        tasks: ['jshint', 'concat', 'uglify'],
      },
      configFiles: {
        files: [ 'Gruntfile.js', 'config/*.js' ],
        options: {
          reload: true
        }
      }
    },
  });

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.registerTask('default', ['sass', 'jshint', 'cssmin', 'copy', 'concat', 'uglify']);
};

