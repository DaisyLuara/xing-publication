var merge = require('webpack-merge')
var prodEnv = require('./prod.env')

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  DOMAIN: '"jingfree.top"',
  SERVER_URL: '"http://api.ad.jingfree.top"',
  HTTPS_SERVER_URL: '"http://api.ad.jingfree.top"',
  TOWER_URL: '"/tower/"'
})
