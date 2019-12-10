const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

mix.scripts([
   'public/assets/node_modules/jquery/jquery-3.2.1.min.js',
   'public/assets/node_modules/popper/popper.min.js',
   'public/assets/node_modules/bootstrap/dist/js/bootstrap.min.js',
   'public/js/perfect-scrollbar.jquery.min.js',
   'public/js/waves.js',
   'public/js/sidebarmenu.js',
   'public/js/custom.js',
   'public/assets/node_modules/raphael/raphael-min.js',
   'public/assets/node_modules/jquery-sparkline/jquery.sparkline.min.js',
   'public/js/dashboard1.js'
], 'public/js/pagina.js').version();
   