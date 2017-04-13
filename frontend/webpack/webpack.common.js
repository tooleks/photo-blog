const {root} = require('./helpers');
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
        path: root('dist')
    },
    module: {
        rules: [
            // Load ts scripts.
            {
                test: /\.ts$/,
                use: '@ngtools/webpack'
            },
            // Load html templates.
            {
                test: /\.html$/,
                use: 'raw-loader'
            },
            // Load component css styles.
            {
                test: /\.css$/,
                include: root('./src/app'),
                use: 'raw-loader'
            },
            // Load assets css styles.
            {
                test: /\.css$/,
                exclude: root('./src/app'),
                use: ['to-string-loader'].concat(ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: 'css-loader'
                }))
            },
            // Load assets resources.
            {
                test: /\.(png|jpe?g|gif|svg|woff|woff2|ttf|eot|ico)$/,
                exclude: root('./assets/static'),
                use: 'file-loader?name=assets/[name].[hash].[ext]'
            },
            // Load static assets resources.
            {
                test: /\.(png|jpe?g|gif|svg|woff|woff2|ttf|eot|ico)$/,
                include: root('./assets/static'),
                use: 'file-loader?name=assets/static/[name].[ext]'
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin('[name].css')
    ]
};
