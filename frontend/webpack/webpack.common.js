const {root} = require('./helpers');
const webpack = require('webpack');
const Dotenv = require('dotenv-webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const ScriptExtPlugin = require('script-ext-html-webpack-plugin');

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
                use: 'file-loader?name=assets/static/[name].[ext]'
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
            template: root('./src/index.html'),
            output: root('./dist'),
            inject: 'body',
            favicon: root('./assets/static/img/favicon.ico')
        }),
        new ScriptExtPlugin({
            defaultAttribute: 'defer'
        })
    ]
};
