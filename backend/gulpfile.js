var gulp = require('gulp'),
    exec = require('child_process').exec,
    runSequence = require('run-sequence');

/**
 * Generate API documentation.
 * Documentation file path: ./doc/api/dist/index.html
 */
gulp.task('generate-api-doc', function (callback) {
    return exec('apidoc -i ./api/ -o ../docs/api/dist', function (err, stdOut, stdErr) {
        console.log(stdOut);
        console.log(stdErr);
        callback(stdErr);
    });
});

/**
 * Default gulp.js task.
 */
gulp.task('default', function () {
    console.log('What a lovely holiday. There\'s nothing funny left to say.')
});
