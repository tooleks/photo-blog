const {root} = require('./helpers');
const {AotPlugin} = require('@ngtools/webpack');

/**
 * This is a server config which should be merged on top of common config
 */
module.exports = {
    entry: {
        'vendor': root('./src/vendor.ts'),
        'server': root('./src/main.server.ts')
    },
    output: {
        filename: '[name].js'
    },
    target: 'node'
};
