let mix = require('laravel-mix');

mix
    .js('src/index.js', 'js/app.js').vue()
    .sass('src/scss/style.scss', 'css/app.css').vue();
