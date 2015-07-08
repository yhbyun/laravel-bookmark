// GruntFile.js for Laravel 4 Development
// https://gist.github.com/JasonMortonNZ/5485857

module.exports = function(grunt) {
  "use strict";
  grunt.initConfig({
    watch: {
      all: {
        files: ['public/css/*', 'public/js/*', 'public/templates/*'],
        tasks: ['clean:all', 'concat:dev', 'concat:template', 'uglify:dev']
      }
    },
    clean: {
      all: {
        src: ['public/assets', 'tmp']
      }
    },
    css: {
      files: {
        'public/assets/css/css-style.css': ['public/css/!(flat-ui).css', 'public/css/flat-ui.css']
      }
    },
    copy: {
      dist: {
        files: {
          'tmp/' : ['public/js/**/*.js', 'public/templates/*.html']
        },
        options: {
          processContent: function (content) {
            return content.replace(/('|")\/bookmark\//g, '$1/').replace(/('|")\/bookmark/g, '$1/').replace(/<!--dist ignore begin-->[\s\S]*<!--dist ignore end-->/gi, '');
            //return content.replace(/env\s*: 'development', \/\/ live/g, "env: 'production',").replace(/\$\{project\.version\}/g, grunt.template.process('<%= pkg.version %>'));
          }
        }
      },
      dev: {
        files: {
          'tmp/' : ['public/js/**/*.js', 'public/templates/*.html']
        }
      }
    },
    concat: {
      dev: {
        seperator: ';',
        stripBanners: false,
        src: ['tmp/public/js/jquery.min.js', 'tmp/public/js/json2.js',
          'tmp/public/js/underscore-min.js', 'tmp/public/js/handlebars.min.js', 'tmp/public/js/backbone-min.js',
          'tmp/public/js/backbone-validation-min.js', 'tmp/public/js/backbone.validation.bootstrap.js',
          'tmp/public/js/backbone.queryparams.js',
          'tmp/public/js/bootstrap-switch.js',  'tmp/public/js/flatui-checkbox.js','tmp/public/js/flatui-radio.js',
          'tmp/public/js/jquery.masonry.min.js', 'tmp/public/js/bootstrap.min.js', 'tmp/public/js/utils.js',
          'tmp/public/js/jquery-ui.min.js', 'tmp/public/js/jquery.tagsinput.rewrite.js', 'tmp/public/js/plugins.js',
          'tmp/public/js/models/*.js', 'tmp/public/js/views/*.js', 'tmp/public/js/routers/*.js', 'tmp/public/js/App.js'
        ],
        dest: 'public/assets/js/script.js',
        nonull: true
      },
      template: {
        options: {
          banner: 'Templates = {};\n',
          process: function(src, filepath) {
            return 'Templates.' + filepath.slice(filepath.lastIndexOf('/') + 1).split('.')[0] + ' = \'' +
              src.replace(/(\r\n|\n|\r)/gm, ' ').replace(/\s+/gm, ' ').replace(/'/gm, "\\'")
              + '\';\n';
          },
        },
        src: ['tmp/public/templates/*.html'],
        dest: 'public/assets/js/templates.js'
      }
    },
    // production version url starts with /bookmark, but local dev version starts with /
    'string-replace': {
      dev: {
        files: {
          'public/assets/js/script.js': ['public/assets/js/script.js'],
          'public/assets/js/templates.js': ['public/assets/js/templates.js']
        },
        options: {
          replacements: [{
            pattern: /\/bookmark\//g,
            replacement: '/'
          }, {
            pattern: /['"]\/bookmark['"]/g,
            replacement: '"/"'
          }]
        }
      }
    },
    uglify: {
      dev: {
        files: {
          'public/assets/js/script.min.js': ['public/assets/js/script.js']
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-string-replace');

  grunt.registerTask('build-dev', ['clean:all', 'copy:dev', 'concat:dev', 'concat:template', 'string-replace:dev', 'uglify:dev']);
  grunt.registerTask('build', ['clean:all', 'copy:dev', 'concat:dev', 'concat:template', 'uglify:dev']);
};
