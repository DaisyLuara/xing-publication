'use strict'
const merge = require('webpack-merge')
const devEnv = require('./dev.env')

module.exports = merge(devEnv, {
    NODE_ENV: '"testing"',
    SERVER_URL: '"http://papi.newgls.cn"',
    TOWER_URL: '"http://tower.im/"'
})
