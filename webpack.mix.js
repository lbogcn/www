let mix = require('laravel-mix');

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

mix.js('resources/assets/admin/js/index.js', 'public/js/admin.js').version();

mix.less('resources/assets/web/less/index.less', 'public/css/app.css').version();

if (mix.inProduction()) {
    mix.version();
}