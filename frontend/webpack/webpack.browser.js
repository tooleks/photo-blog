const {root} = require('./helpers');
const {AotPlugin} = require('@ngtools/webpack');
const webpack = require('webpack');

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
