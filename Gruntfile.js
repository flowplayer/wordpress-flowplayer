module.exports = function(grunt) {

	require('load-grunt-tasks')(grunt);

	grunt.initConfig({

		dirs: {
			lang: 'languages',
		},

		makepot: {
			target: {
				options: {
					domainPath: '/languages/',    // Where to save the POT file.
					mainFile: 'flowplayer.css',      // Main project file.
					potFilename: 'flowplayer5.pot',   // Name of the POT file.
					type: 'wp-plugin',  // Type of project (wp-plugin or wp-theme).
					exclude: ['build/.*'],       // List of files or directories to ignore.
					processPot: function( pot, options ) {
						pot.headers['report-msgid-bugs-to'] = 'https://wordpress.org/support/plugin/flowplayer5';
						pot.headers['plural-forms'] = 'nplurals=2; plural=n != 1;';
						pot.headers['last-translator'] = 'Ulrich Pogson <ulrich@pogson.com>\n';
						pot.headers['language-team'] = 'Ulrich Pogson <ulrich@pogson.com>\n';
						pot.headers['x-poedit-basepath'] = '.\n';
						pot.headers['x-poedit-language'] = 'English\n';
						pot.headers['x-poedit-country'] = 'UNITED STATES\n';
						pot.headers['x-poedit-sourcecharset'] = 'utf-8\n';
						pot.headers['X-Poedit-KeywordsList'] = '__;_e;__ngettext:1,2;_n:1,2;__ngettext_noop:1,2;_n_noop:1,2;_c,_nc:4c,1,2;_x:1,2c;_ex:1,2c;_nx:4c,1,2;_nx_noop:4c,1,2;\n';
						pot.headers['x-textdomain-support'] = 'yes\n';
						return pot;
					}
				}
			}
		},

		exec: {
			update_po_wti: { // Update WebTranslateIt translation - grunt exec:update_po_wti
				cmd: 'wti pull',
				cwd: 'languages/',
			}
		},

		potomo: {
			dist: {
				options: {
					poDel: true,
				},
				files: [{
					expand: true,
					cwd: '<%= dirs.lang %>/',
					src: ['*.po'],
					dest: '<%= dirs.lang %>/',
					ext: '.mo',
					nonull: true
				}]
			}
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
					'!**/.wti',
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

};
