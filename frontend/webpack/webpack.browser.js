const {root} = require('./helpers');
const webpack = require('webpack');
const {AotPlugin} = require('@ngtools/webpack');

/**
 * This is a client config which should be merged on top of common config
 */
module.exports = {
    entry: {
        'browser': root('./src/main.browser.ts')
    },
    output: {
        filename: '[name].js?v=[hash]'
    },
    target: 'web'
};
