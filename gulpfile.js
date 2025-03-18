const { src, dest, watch } = require('gulp');
const uglify = require('gulp-uglify');
const postcss = require("gulp-postcss");
const babel = require('gulp-babel');
const rename = require('gulp-rename');
const tailwindcss = require('@tailwindcss/postcss'); // Use the correct package

// const bundleJs = () => {
//   return src('./resources/js/**/*.js') // Source all js files
//     .pipe(uglify()) // Minify js
//     .pipe(dest('./webroot/js/')); // Save output
// }

const bundleJs = () => {
  return src('./resources/js/**/*.js') // Get all JS files
    .pipe(babel({ presets: ['@babel/preset-env'] })) // Transpile ES6+ to ES5
    // .pipe(uglify()) // Minify JS files
    // .pipe(rename({ suffix: '.min' })) // Add ".min" to the output file names
    .pipe(dest('./webroot/js/')); // Save files to webroot/js/
};

const bundleCss = () => {
  return src('./resources/css/**/*.css') // Source all CSS files
    .pipe(postcss([tailwindcss(), require("autoprefixer")]))
    .pipe(dest('./webroot/css/')); // Save output
};

const devWatch = () => {
  bundleCss();
  bundleJs();
  watch('./resources/css/**/*.css', bundleCss); // Watch for CSS changes
  watch('./resources/js/**/*.js', bundleJs); // Watch for js changes
}

exports.bundleJs = bundleJs;
exports.devWatch = devWatch;
