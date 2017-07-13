const {root} = require('./helpers');

/**
 * This is a server config which should be merged on top of common config
 */
module.exports = {
    entry: {
        'server': root('./src/main.server.ts')
    },
    output: {
        filename: '[name].js'
    },
    target: 'node'
};
