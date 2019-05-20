var merge = require('webpack-merge')
var prodEnv = require('./prod.env')

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  DOMAIN: '"jingfree.top"',
  SERVER_URL: '"http://api.ad.xingstation.test"',
  HTTPS_SERVER_URL: '"http://api.ad.xingstation.test"',
  CDN_URL: "'http://cdn.xingstation.cn/'"
})
