module.exports = function(grunt){
	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		/**
		 * Sass task
		 */
		sass: {
			dev: {
				options: {
					style: 'expanded',
					sourcemap: 'none',
				},
				files : {
					/* File destination : root file to decypher*/
					'style-human.css' : 'sass/style.scss'
				}
			},
			dist: {
				options: {
					style: 'compressed',
					sourcemap: 'none',
				},
				files : {
					/* File destination : root file to decypher*/
					'style.css' : 'sass/style.scss'
				}
			}
		},

				/**
		 * LESS task
		 */
		less: {
			/*
			development: {
				options: {
					paths: ["css"]				
				},
				files : {
					// File destination : root file to decypher
					'compiled/style-human.css' : 'css/layout.less'
				}
			},*/
			production: {
				options: {
					paths: ["css"],				
					cleancss: true
				},
				files : {
					/* File destination : root file to decypher*/
					'compiled/style.css' : 'css/layout.less',
					'compiled/responsive.css' : 'css/responsive.less',
					'compiled/print.css' : 'css/print.less'
				}
			}		

		},

		/**
		 * CSS Minify (use this for LESS)
		 */
		cssmin: {
			target: {
				files: [{
					expand: true,
					cwd: 	'compiled/',
					src: 	['*.css','!*.min.css'],
					dest: 	'',
					ext: 	'.css'
				}]
			}
		},

		/**
		 * Autoprefixer
		 */
		autoprefixer: {
			options: {
		 		browsers: ['last 2 versions']
		 	},
		 	// prefix all files
		 	multiple_files: {
		 		expand: true,
		 		flatten: true,
		 		src: 'compiled/*.css',
		 		dest: ''
		 	}
		},

		/**
		 * Watch task
		 */
		watch: {
			css: {
				files: '**/*.less',
				/* tasks: ['less','autoprefixer', 'cssmin'] */
				tasks: ['less','cssmin']
			}
		}
	});

	// May need to install these packages again
	// Use npm install grunt-package-name --save-dev
	// grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.registerTask('default',['watch']);
}