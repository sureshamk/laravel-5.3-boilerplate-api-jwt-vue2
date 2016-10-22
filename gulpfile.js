const elixir = require('laravel-elixir');


// elixir.ready(function () {
//     elixir.webpack.mergeConfig({
//         resolve: {
//             extensions:[".jsx"]
//         },
//         module: {
//             loaders: [
//                 { test: /\.jsx?$/, loader: 'babel' }
//                 ]
//
//         }
//     })
// });


require('laravel-elixir-vue-2');
require('laravel-elixir-vueify');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix.sass('app.scss')
    //mix.less('app.less')
        .webpack('app.js')
 //   .browserify('app.js')

        .copy('node_modules/bootstrap-sass/assets/fonts/bootstrap/', 'public/fonts/bootstrap');
});
