module.exports = function (grunt) {
  // Configure grunt
  grunt.initConfig({
    sprite:{
      all: {
        src: ['images/cat-icon.png', 'images/list-icon.png', 'images/g-icon.png'],
        dest: 'images/icons.png',
        destCss: 'icons.css'
      }
    }
  });

  // Load in `grunt-spritesmith`
  grunt.loadNpmTasks('grunt-spritesmith');
};