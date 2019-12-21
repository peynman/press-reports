const mix = require('laravel-mix');
let exec = require('child_process').exec;
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
mix.config.webpackConfig.output = {
    chunkFilename: '[name].bundle.js',
    publicPath: 'resources/dis',
};

mix.js('resources/js/app.js', '/js')
    .extract([
        'axios',
        'vue',
        'vue-router',
        'vue-axios',
        'vue-template-compiler',
        'vuex',
    ]).setPublicPath('resources/dis/');



mix.sass('resources/sass/app.scss', 'resources/dis/css')
    .setResourceRoot('../')
    .then(() => {
        exec('node_modules/rtlcss/bin/rtlcss.js resources/dis/css/app.css resources/dis/css/app-rtl.css');
    });

if (mix.inProduction()) {
    mix.version();
} else {
    mix.copyDirectory('resources/dis/', '../../storage/app/public/vendor/larapress-dashboard');
}

mix.disableNotifications();
