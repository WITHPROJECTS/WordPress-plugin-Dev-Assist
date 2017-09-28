const gulp     = require('gulp');
const sassConf = require('withpro-gulp-sass');
const jsConf   = require('withpro-gulp-js');

// =============================================================================
// PATH
// =============================================================================
let path = {
  'project' : '/',
  'src' : {
    'js'        : 'asset/src/js',
    'sass'      : 'asset/src/sass',
    'sassMixin' : 'asset/src/sass/mixin',
    'font'      : 'asset/src/font',
    'iconfont'  : 'asset/src/font/icon',
    'lib'       : [
      'asset/lib/sass',
      'asset/src/sass'
    ]
  },
  'dest' : {
    'js'       : 'asset/build/js',
    'css'      : 'asset/build/css',
    'image'    : 'asset/build/img',
    'font'     : 'asset/build/font',
    'iconfont' : 'asset/build/font/icon'
  }
};

// jsConf.concat = {
//   'class.js' : [
//     'class/_Validator.js'
//   ]
// };
// =============================================================================
// CONFIG
// =============================================================================
sassConf.path = path;
jsConf.path   = path;
sassConf.options.iconfont = {
  'prependUnicode'     : false,
  'fixedWidth'         : true,
  // 'fontHeight'         : 500,
  'timestamp'          : Math.round(Date.now()/1000),
  'normalize'          : true,
  'centerHorizontally' : true
};

sassConf.init();
jsConf.init();

// =============================================================================
// TASK
// =============================================================================
gulp.task('default', ()=>{});
gulp.task('build', ['js-build', 'sass-build']);
gulp.task('watch', ['js-watch', 'sass-watch']);
