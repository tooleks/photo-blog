const {root} = require('./helpers');
const webpack = require('webpack');
const HtmlPlugin = require('html-webpack-plugin');
const ScriptExtHtmlPlugin = require('script-ext-html-webpack-plugin');
const GenerateJsonPlugin = require('generate-json-webpack-plugin');

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
    target: 'web',
    plugins: [
        new HtmlPlugin({
            template: root('./src/index.ejs'),
            output: root('./dist'),
            inject: 'body',
            appName: process.env.APP_NAME
        }),
        new ScriptExtHtmlPlugin({
            defaultAttribute: 'defer'
        }),
        new GenerateJsonPlugin('manifest.json', {
            "name": process.env.APP_NAME,
            "author": process.env.APP_AUTHOR,
            "display": "standalone",
            "icons": [
                {
                    "src": "\/android-icon-36x36.png",
                    "sizes": "36x36",
                    "type": "image\/png",
                    "density": "0.75"
                },
                {
                    "src": "\/android-icon-48x48.png",
                    "sizes": "48x48",
                    "type": "image\/png",
                    "density": "1.0"
                },
                {
                    "src": "\/android-icon-72x72.png",
                    "sizes": "72x72",
                    "type": "image\/png",
                    "density": "1.5"
                },
                {
                    "src": "\/android-icon-96x96.png",
                    "sizes": "96x96",
                    "type": "image\/png",
                    "density": "2.0"
                },
                {
                    "src": "\/android-icon-144x144.png",
                    "sizes": "144x144",
                    "type": "image\/png",
                    "density": "3.0"
                },
                {
                    "src": "\/android-icon-192x192.png",
                    "sizes": "192x192",
                    "type": "image\/png",
                    "density": "4.0"
                }
            ]
        })
    ]
};
