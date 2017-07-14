const {root} = require('./webpack/helpers');
require('dotenv').config({path: root('./.env')});
const webpackMerge = require('webpack-merge');
const commonPartial = require('./webpack/webpack.common');
const browserPartial = require('./webpack/webpack.browser');
const serverPartial = require('./webpack/webpack.server');
const productionPartial = require('./webpack/webpack.production');
const {getAotPlugin} = require('./webpack/webpack.aot');

module.exports = function (options, webpackOptions) {
    options = options || {};

    let serverConfig = webpackMerge({}, commonPartial, serverPartial, {
        plugins: [
            getAotPlugin('server', !!options.aot)
        ]
    });

    let browserConfig = webpackMerge({}, commonPartial, browserPartial, {
        plugins: [
            getAotPlugin('browser', !!options.aot)
        ]
    });

    if (process.env.NODE_ENV === 'production') {
        browserConfig = webpackMerge({}, browserConfig, productionPartial);
    }

    const configs = [];
    if (options.browser) {
        console.log(`> Running build for a browser environment (${process.env.NODE_ENV}).`);
        configs.push(browserConfig);
    } else if (options.server) {
        console.log(`> Running build for a server environment (${process.env.NODE_ENV}).`);
        configs.push(serverConfig);
    }

    return configs;
};
