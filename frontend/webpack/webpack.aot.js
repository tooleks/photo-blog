const {root} = require('./helpers');
const {AotPlugin} = require('@ngtools/webpack');

const tsconfigs = {
    browser: root('./src/tsconfig.browser.json'),
    server: root('./src/tsconfig.server.json')
};

const aotTsconfigs = {
    browser: root('./src/tsconfig.browser.json'),
    server: root('./src/tsconfig.server.json')
};

/**
 * Generates a AotPlugin for @ngtools/webpack
 *
 * @param {string} platform Should either be browser or server
 * @param {boolean} aot Enables/Disables AoT Compilation
 * @returns
 */
function getAotPlugin(platform, aot) {
    return new AotPlugin({
        tsConfigPath: aot ? aotTsconfigs[platform] : tsconfigs[platform],
        skipCodeGeneration: !aot
    });
}

module.exports = {
    getAotPlugin: getAotPlugin
};
