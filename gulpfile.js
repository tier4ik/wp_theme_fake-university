const gulp = require('gulp');
const browserSync = require('browser-sync').create();

function sync() {

  browserSync.init({
    proxy: 'http://fake-university.local'
  });

  gulp.watch('*.php').on('change', browserSync.reload);

}

exports.sync = sync;