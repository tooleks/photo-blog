const {root} = require('./helpers');
const {AotPlugin} = require('@ngtools/webpack');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const ScriptExtPlugin = require('script-ext-html-webpack-plugin');
const webpack = require('webpack');

/**
 * This is a client config which should be merged on top of common config
 */
module.exports = {
    entry: {
        'vendor': root('./src/vendor.ts'),
        'browser': root('./src/main.browser.ts')
    },
    output: {
        filename: '[name].js'
    },
    target: 'web',
    plugins: [
        new HtmlWebpackPlugin({
            template: root('./src/index.html'),
            output: root('dist'),
            inject: 'head',
            favicon: root('./assets/static/img/favicon.ico')
        }),
        new ScriptExtPlugin({
            defaultAttribute: 'defer'
        })
    ]
};
