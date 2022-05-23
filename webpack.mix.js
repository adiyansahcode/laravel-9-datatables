const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .setPublicPath('public')
  .setResourceRoot('../') // Turns assets paths in css relative to css file
  .js('resources/js/app.js', 'js/app.js')
  .sass('resources/scss/app.scss', 'css/app.css')

if (mix.inProduction()) {
  mix.version();
} else {
  // Uses inline source-maps on development
  mix.webpackConfig({
    devtool: "inline-source-map",
    stats: {
      children: true
    }
  });
}
