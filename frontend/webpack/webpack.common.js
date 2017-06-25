const {root} = require('./helpers');
require('dotenv').config({path: root('./.env')});
const webpack = require('webpack');
const Dotenv = require('dotenv-webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const ScriptExtPlugin = require('script-ext-html-webpack-plugin');
const GenerateJsonPlugin = require('generate-json-webpack-plugin');

/**
 * This is a common webpack config which is the base for all builds
 */
module.exports = {
    devtool: 'source-map',
    node: {
        fs: 'empty'
    },
    resolve: {
        extensions: ['.ts', '.js']
    },
    output: {
        path: root('./dist')
    },
    module: {
        rules: [
            {
                test: /\.ts$/,
                use: '@ngtools/webpack'
            },
            {
                test: /\.html$/,
                use: 'raw-loader'
            },
            {
                test: /\.css$/,
                use: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: ['css-loader?sourceMap', 'sass-loader?sourceMap']
                })
            },
            {
                test: /\.(png|jpe?g|gif|svg|woff|woff2|ttf|eot|ico)$/,
                exclude: root('./assets/static'),
                use: 'file-loader?name=assets/[name].[hash].[ext]'
            },
            {
                test: /\.(png|jpe?g|gif|svg|woff|woff2|ttf|eot|ico)$/,
                include: root('./assets/static'),
                exclude: root('./assets/static/favicon'),
                use: 'file-loader?name=assets/static/[name].[ext]'
            },
            {
                test: /\.(png|ico|xml|json)$/,
                include: root('./assets/static/favicon'),
                use: 'file-loader?name=[name].[ext]'
            }
        ]
    },
    plugins: [
        new Dotenv({
            path: root('./.env'),
            safe: true
        }),
        new ExtractTextPlugin('[name].css?v=[hash]'),
        new HtmlWebpackPlugin({
            template: root('./src/index.ejs'),
            output: root('./dist'),
            inject: 'body',
            appName: process.env.APP_NAME
        }),
        new ScriptExtPlugin({
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
