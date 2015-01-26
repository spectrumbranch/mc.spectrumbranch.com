module.exports = function(grunt) {

   grunt.initConfig({
       sass: {
           dist: {
               files: {
                   'css/main.css': 'css/main.scss'
               }
           }
       },
   

        watch: {
            css: {
               files: '**/*.scss',
                tasks: ['sass']
                  }
                },


        cssmin: {
            target: {
                files: [{
                  expand: true,
                  cwd: '/css',
                  src: ['*.css', '!*.min.css'],
                  dest: '/css',
                  ext: '.min.css'
                        }]
                    }
                }
    
  });

   grunt.loadNpmTasks('grunt-contrib-sass');
   grunt.loadNpmTasks('grunt-contrib-watch');
   grunt.loadNpmTasks('grunt-contrib-cssmin');

   grunt.registerTask('default', ['sass']);
   grunt.registerTask('default',['watch']);
   grunt.registerTask('default',['cssmin']);

}