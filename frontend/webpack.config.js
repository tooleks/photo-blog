const {root} = require('./webpack/helpers');
require('dotenv').config({path: root('./.env')});
const ngtools = require('@ngtools/webpack');
const webpackMerge = require('webpack-merge');
const commonPartial = require('./webpack/webpack.common');
const browserPartial = require('./webpack/webpack.browser');
const serverPartial = require('./webpack/webpack.server');
const productionPartial = require('./webpack/webpack.production');
const {getAotPlugin} = require('./webpack/webpack.aot');

module.exports = function (options, webpackOptions) {
    options = options || {};

    console.log(`Running build for the ${process.env.NODE_ENV ? process.env.NODE_ENV : 'local'} environment.`);

    let serverConfig = webpackMerge({}, commonPartial, serverPartial, {
        plugins: [
            getAotPlugin('server', !!options.aot)
        ]
    });

    let clientConfig = webpackMerge({}, commonPartial, browserPartial, {
        plugins: [
            getAotPlugin('client', !!options.aot)
        ]
    });

    if (process.env.NODE_ENV === 'production') {
        clientConfig = webpackMerge({}, clientConfig, productionPartial);
    }

    const configs = [];
    if (!options.aot) {
        configs.push(clientConfig, serverConfig);
    } else if (options.client) {
        configs.push(clientConfig);
    } else if (options.server) {
        configs.push(serverConfig);
    }

    return configs;
};
