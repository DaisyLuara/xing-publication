const MARKETS_API = '/api/markets'
const MARKET_API = '/api/market'
const POINTS_API = '/api/points'
const POINT_API = '/api/point'
const HOST = process.env.SERVER_URL

// 场地里的场地列表
const getSiteMarketList = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + MARKETS_API, { params: params })
      .then(res => {
        resolve(res.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}
// 场地里的点位列表
const getSitePointList = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + POINTS_API, { params: params })
      .then(res => {
        resolve(res.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}
const getSiteMarketDetail = (context, params, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + MARKETS_API + '/' + id, { params: params })
      .then(res => {
        resolve(res.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}
const getSitePointDetail = (context, params, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + POINTS_API + '/' + id, { params: params })
      .then(res => {
        resolve(res.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}
const siteSaveMarket = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + MARKET_API, params)
      .then(res => {
        resolve(res.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}
const siteModifyMarket = (context, params, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + MARKET_API + '/' + id, params)
      .then(res => {
        resolve(res.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}
const siteSavePoint = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + POINT_API, params)
      .then(res => {
        resolve(res.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}
const siteModifyPoint = (context, params, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + POINTS_API + '/' + id, params)
      .then(res => {
        resolve(res.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}
export {
  siteModifyPoint,
  siteSavePoint,
  siteModifyMarket,
  siteSaveMarket,
  getSitePointDetail,
  getSiteMarketDetail,
  getSitePointList,
  getSiteMarketList
}
