const {root} = require('./helpers');
const webpack = require('webpack');
const DotenvPlugin = require('dotenv-webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

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
                exclude: [
                    root('./assets/static'),
                    root('./assets/favicon')
                ],
                use: 'file-loader?name=assets/[name].[hash].[ext]'
            },
            {
                test: /\.(png|jpe?g|gif|svg|woff|woff2|ttf|eot|ico)$/,
                include: root('./assets/static'),
                use: 'file-loader?name=assets/static/[name].[ext]'
            },
            {
                test: /\.(png|ico|xml|json)$/,
                include: root('./assets/favicon'),
                use: 'file-loader?name=[name].[ext]'
            }
        ]
    },
    plugins: [
        new DotenvPlugin({
            path: root('./.env'),
            safe: true
        }),
        new ExtractTextPlugin('[name].[hash].css')
    ]
};
