"use strict";

const mix = require("laravel-mix");
const path = require("path");

const RESOURCES_DIR_PATH = "./resources";
const DIST_DIR_PATH = "./public";

mix
    .js(path.join(RESOURCES_DIR_PATH, "assets/js/app.js"), path.join(DIST_DIR_PATH, "js"))
    .sass(path.join(RESOURCES_DIR_PATH, "assets/sass/app.scss"), path.join(DIST_DIR_PATH, "css"))
    .copy(path.join(RESOURCES_DIR_PATH, "assets/images/*.*"), path.join(DIST_DIR_PATH, "images"))
    .copy(path.join(RESOURCES_DIR_PATH, "assets/favicon/*.*"), path.join(DIST_DIR_PATH, "favicon"))
    .copy(path.join(RESOURCES_DIR_PATH, "assets/favicon/favicon.ico"), path.join(DIST_DIR_PATH, "favicon.ico"))
    .options({
        extractVueStyles: true,
    });

if (mix.inProduction()) {
    mix.version();
}
