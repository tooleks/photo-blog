"use strict";

const mix = require("laravel-mix");

mix
    .js("./resources/js/app.js", "./public/js")
    .sass("./resources/sass/app.scss", "./public/css")
    .copy("./resources/images/**", "./public/images")
    .copy("./resources/images/favicons/**", "./public")
    .options({
        extractVueStyles: true,
    });

if (mix.inProduction()) {
    mix.version();
}
