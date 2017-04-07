var gulp = require('gulp'),
	sass = require('gulp-sass'),
	concatCSS = require('gulp-concat-css'),
	minifyCSS = require('gulp-minify-css'),
	rename = require('gulp-rename'),
	del = require('del'),
	sourcemaps = require('gulp-sourcemaps'),
	gulpIf = require('gulp-if'),
	uglify = require('gulp-uglifyjs'),
	minifyJS = require('gulp-minify'),
	imagemin = require('gulp-imagemin');

/* Set environment variable for detecting whether sourcemap is necessary or not */
const isDevelopment = !process.env.NODE_ENV || process.env.NODE_ENV == 'development';

/* Delete the whole build_ui directory to be sure that we don't store deleted files */
gulp.task('clean', function () {
	return del('build_ui');
});

/* Copy vendor files without minification or anything else */
gulp.task('copy-sources', function() {
	gulp.src('./src/vendor/**/*.*')
		.pipe(gulp.dest('./build_ui/vendor'));

	return gulp.src('./src/themes/**/*.*')
		.pipe(gulp.dest('./build_ui/themes'));
});

/* Task for compilation .scss and .sass files */
gulp.task('compile-sass', function() {
	return gulp.src(['./src/themes/registry-contract/scss/**/*.scss','./src/themes/registry-contract/sass/**/*.sass'])
		.pipe(gulpIf(isDevelopment, sourcemaps.init()))
		.pipe(sass())
		.pipe(gulpIf(isDevelopment, sourcemaps.write()))
		.on('error', sass.logError)
		.pipe(gulp.dest('./src/themes/registry-contract/css'))
});

/* Task for concat and minifyJS common.css, depends on sass task */
gulp.task('minifyCSS-common', function() {
	return gulp.src('./src/themes/registry-contract/css/*.css')
		.pipe(concatCSS('common.css'))
		.pipe(minifyCSS())
		.pipe(rename('common.min.css'))
		.pipe(gulp.dest('./build_ui/themes/registry-contract/css/'));
});

/* Task for just minifying single .css files, depends on sass task */
gulp.task('minifyCSS-single', function() {
	return gulp.src('./src/themes/registry-contract/css/single/**/*.css')
		.pipe(minifyCSS())
		.pipe(gulp.dest('./build_ui/themes/registry-contract/css/single/'));
});

gulp.task('minifyJS-common', function() {
	del('build_ui/themes/registry-contract/js');

	return gulp.src('./src/themes/registry-contract/js/*.js')
		.pipe(uglify('common.js'))
		.pipe(rename('common.min.js'))
		.pipe(gulp.dest('./build_ui/themes/registry-contract/js/'))
});

gulp.task('minifyJS-single', function() {
	return gulp.src('./src/themes/registry-contract/js/single/**/*.js')
		.pipe(minifyJS({
			noSource: true,
			ext:{
				min:'.min.js'
			},
			ignoreFiles: ['-min.js']
		}))
		.pipe(gulp.dest('./build_ui/themes/registry-contract/js/single/'));
});

gulp.task('compress-img', function() {
	return gulp.src('./src/themes/registry-contract/**/*.{png,PNG,jpeg,jpg,gif,svg}')
		.pipe(imagemin())
		.pipe(gulp.dest('./build_ui/themes/registry-contract/'));
});

gulp.task('watcher', function () {
	gulp.watch(
		'./src/themes/registry-contract/{scss,sass}/**/*.{scss,sass}',
		gulp.series('compile-sass', 'minifyCSS-common', 'minifyCSS-single')
	);
	gulp.watch(
		'./src/themes/registry-contract/js/**/*.js',
		gulp.series('minifyJS-common', 'minifyJS-single')
	);
	gulp.watch(
		'./src/themes/registry-contract/**/*.{png,PNG,jpeg,jpg,gif,svg}',
		gulp.series('compress-img')
	);
});

gulp.task('build', function() {
	gulp.series(
		'clean', 'copy-sources', 'compile-sass', 'minifyCSS-common', 'minifyCSS-single', 'minifyJS-common', 'minifyJS-single', 'compress-img'
	)();
});
