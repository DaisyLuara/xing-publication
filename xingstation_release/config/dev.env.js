var merge = require('webpack-merge')
var prodEnv = require('./prod.env')

module.exports = merge(prodEnv, {
    NODE_ENV: '"development"',
    SERVER_URL: '"http://papi.newgls.cn"',
    HTTPS_SERVER_URL: '"http://papi.newgls.cn"',
    TOWER_URL: '"/tower/"'
})