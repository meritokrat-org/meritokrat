var path = require('path'),
    gulp = require('gulp'),
    uglify = require('gulp-uglify'),
    less = require('gulp-less'),
    minifyCss = require('gulp-minify-css'),
    jade = require('gulp-jade'),
    iife = require('gulp-iife'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    watch = require('gulp-watch'),
    gulpIf = require('gulp-if');

var isDevEnv = true,
    buildpath = 'www',
    kern = {
        script: {
            name: 'kern.js',
            basepath: 'bower_components',
            buildpath: path.join(buildpath, 'js'),
            sources: [
                'jquery/dist/jquery.js',
                'angular/angular.js',
                'angular-animate/angular-animate.js',
                'angular-aria/angular-aria.js',
                'angular-messages/angular-messages.js',
                'angular-material/angular-material.js',
                'angular-ui-router/release/angular-ui-router.js'
            ]
        },
        style: {
            basepath: 'bower_components',
            buildpath: path.join(buildpath, 'css'),
            sources: [
                'Ionicons/css/ionicons.css',
                'angular-material/angular-material.css'
            ]
        },
        font: {
            basepath: 'bower_components',
            buildpath: path.join(buildpath, 'fonts'),
            sources: [
                'Ionicons/fonts/ionicons.*',
                'material-design-icons/iconfont/MaterialIcons-Regular.*'
            ]
        }
    },
    app = {
        script: {
            name: 'app.js',
            basepath: 'src',
            buildpath: path.join(buildpath, 'js'),
            sources: [
                'AppModule.js',
                '**/*Module.js',
                '**/config/**/*Config.js',
                '**/model/**/*Entity.js',
                '**/model/**/*Manager.js',
                '**/model/**/*Store.js',
                '**/factory/**/*Factory.js',
                '**/provider/**/*Provider.js',
                '**/service/**/*Service.js',
                '**/service/**/*Unit.js',
                '**/controller/**/*Controller.js',
                '**/directive/**/*.js'
            ]
        },
        style: {
            buildpath: path.join(buildpath, 'css'),
            sources: ['resources/styles/style.less']
        },
        view: {
            sources: [
                'resources/views/*.jade',
                'src/*/view/**/*.jade'
            ],
            rename: [
                {'^(\\w+).html$': './index.html'},
                {'^(\\w+)/view/(\\w+).html$': './html/$1/$2.html'}
            ]
        }
    };

gulp.task('default', function () {

});

/**
 * development tool
 */
gulp.task('watch', ['build'], function () {
    var map = {
        '.js': 'build:app:script',
        '.less': 'build:app:style',
        '.jade': 'build:view'
    };

    var sources = (function (sources, basepath) {
        return sources.map(function (source) {
            return path.join(basepath, source);
        });
    })(app.script.sources, app.script.basepath);

    watch(
        sources.concat(
            [
                'lib/zz-iframe/**/*.js',
                'resources/styles/**/*.less'
            ],
            app.view.sources
        ),
        function (file) {
            if (!map.hasOwnProperty(file.extname))
                return;
            gulp.start(map[file.extname]);
        }
    );
})

gulp.task('build', ['build:script', 'build:style', 'build:font', 'build:view']);

/**
 * javascript compiler
 */
gulp.task(
    'build:script',
    ['build:kern:script', 'build:app:script']
);
gulp.task('build:kern:script', function () {
    return gulp
        .src((function (sources, basepath) {
            return sources.map(function (source) {
                return path.join(basepath, source);
            })
        })(kern.script.sources, kern.script.basepath))
        .pipe(concat(kern.script.name))
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(kern.script.buildpath));
});
gulp.task('build:lib', function () {
    return gulp.src([
            './lib/zz-iframe/zz-iframe.js'
        ])
        .pipe(uglify().on('error', function(){
            console.log(arguments);
        }))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(path.join(app.script.buildpath, 'lib')));
});
gulp.task('build:app:script', ['build:lib'], function () {
    return gulp
        .src((function (sources, basepath) {
            return sources.map(function (source) {
                return path.join(basepath, source);
            });
        })(app.script.sources, app.script.basepath))
        .pipe(iife())
        .pipe(gulpIf(!isDevEnv, uglify()))
        .pipe(concat(app.script.name))
        .pipe(gulpIf(!isDevEnv, rename({suffix: '.min'})))
        .pipe(gulp.dest(app.script.buildpath));
});

/**
 * styles compiler
 */
gulp.task('build:style', ['build:kern:style', 'build:app:style']);
gulp.task('build:kern:style', function () {
    return gulp
        .src((function (sources, basepath) {
            return sources.map(function (source) {
                return path.join(basepath, source);
            })
        })(kern.style.sources, kern.style.basepath))
        .pipe(minifyCss())
        .pipe(concat('common.css'))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(kern.style.buildpath));
});
gulp.task('build:app:style', function () {
    return gulp
        .src(app.style.sources)
        .pipe(less())
        .pipe(gulpIf(!isDevEnv, minifyCss()))
        .pipe(gulpIf(!isDevEnv, rename({suffix: '.min'})))
        .pipe(gulp.dest(app.style.buildpath));
});

/**
 * fonts
 */
gulp.task('build:font', ['build:kern:font']);
gulp.task('build:kern:font', function () {
    return gulp
        .src((function (sources, basepath) {
            return sources.map(function (source) {
                return path.join(basepath, source);
            })
        })(kern.font.sources, kern.font.basepath))
        .pipe(gulp.dest(kern.font.buildpath));
});

/**
 * views compiler
 */
gulp.task('build:view', function () {
    return gulp
        .src(app.view.sources)
        .pipe(jade({
            pretty: true
        }))
        .pipe(rename(function (f) {
            var rules = app.view.rename,
                filepath = path.join(f.dirname, f.basename + f.extname);

            rules.forEach(function (rule) {
                Object
                    .keys(rule)
                    .forEach(function (regexp) {
                        var replacement = filepath.replace(new RegExp(regexp, 'g'), rule[regexp]),
                            parts = path.parse(replacement);

                        if (replacement === filepath)
                            return;

                        (function (m) {
                            Object
                                .keys(m)
                                .forEach(function (k) {
                                    f[m[k]] = parts[k];
                                })
                        })({
                            dir: 'dirname',
                            name: 'basename',
                            ext: 'extname'
                        });
                    });
            });

            return f;
        }))
        .pipe(gulp.dest(buildpath));
});

