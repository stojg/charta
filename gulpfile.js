var gulp = require('gulp');
var elixir = require('laravel-elixir');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');

var paths = {
	jsx: ['resources/assets/jsx/**/*.jsx'],
	js: ['resources/assets/js/**/*.js'],
	sass: ['resources/assets/sass/**/*.scss']
};

gulp.task('sass', function () {
	return elixir(function (mix) {
		mix.sass('app.scss');
		//mix.version("css/app.css");
	});
});

gulp.task('js', function () {
	//return elixir(function (mix) {
		//mix.version("js/all.js");
	//});
	return gulp.src([
		'resources/assets/js/highlight.min.js',
		'resources/assets/js/codemirror.js',
		'resources/assets/js/markdown.js',
		'resources/assets/js/mousetrap.min.js',
		'resources/assets/js/app.js'
		])
		.pipe(concat('all.js'))
		//.pipe(uglify())
		.pipe(gulp.dest('public/js/'));
});

gulp.task('watch', function() {
	gulp.watch(paths.jsx, ['jsx']);
	gulp.watch(paths.sass, ['sass']);
	gulp.watch(paths.js, ['js']);
});

gulp.task('default', ['sass', 'js']);




