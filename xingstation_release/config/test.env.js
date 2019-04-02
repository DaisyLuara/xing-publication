'use strict'
const merge = require('webpack-merge')
const devEnv = require('./dev.env')

module.exports = merge(devEnv, {
  NODE_ENV: '"testing"',
  DOMAIN: '"xingstation.net"',
  SERVER_URL: '"http://papi.xingstation.net"',
  HTTPS_SERVER_URL: '"https://papi.xingstation.net"',
  TOWER_URL: '"http://tower.im/"'
})
