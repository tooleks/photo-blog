"use strict";

const mix = require("laravel-mix");

mix
    .js("./resources/assets/js/app.js", "./public/js")
    .sass("./resources/assets/sass/app.scss", "./public/css")
    .copy("./resources/assets/images/**", "./public/images")
    .copy("./resources/assets/images/favicons/**", "./public")
    .options({
        extractVueStyles: true,
    });

if (mix.inProduction()) {
    mix.version();
}
