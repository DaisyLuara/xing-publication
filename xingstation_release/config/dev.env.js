var merge = require('webpack-merge')
var prodEnv = require('./prod.env')

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  DOMAIN: '"jingfree.top"',
  SERVER_URL: '"http://papi.xingstation.net"',
  HTTPS_SERVER_URL: '"http://papi.xingstation.net"',
  TOWER_URL: '"/tower/"'
})
