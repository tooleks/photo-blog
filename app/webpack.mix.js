const mix = require("laravel-mix");
const path = require("path");

const ASSETS_DIR = "resources/assets";
const PUBLIC_DIR = "public";

mix
    .js(path.join(ASSETS_DIR, "js/app.js"), path.join(PUBLIC_DIR, "js"))
    .sass(path.join(ASSETS_DIR, "sass/app.scss"), path.join(PUBLIC_DIR, "css"))
    .copy(path.join(ASSETS_DIR, "img/*.*"), path.join(PUBLIC_DIR, "img"))
    .copy(path.join(ASSETS_DIR, "favicon/*.*"), path.join(PUBLIC_DIR, "favicon"))
    .copy(path.join(ASSETS_DIR, "favicon/favicon.ico"), path.join(PUBLIC_DIR, "favicon.ico"))
    .options({
        extractVueStyles: true,
    });

if (mix.inProduction()) {
    mix.version();
}
