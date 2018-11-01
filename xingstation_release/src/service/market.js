const MARKETS_API = '/api/markets'
const MARKET_API = '/api/market'
const POINTS_API = '/api/points'
const POINT_API = '/api/point'
const HOST = process.env.SERVER_URL

export default {
  getMarketList(context, params) {
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
  },
  getPointList(context, params) {
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
  },
  getMarketDetail(context, params, id) {
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
  },
  getPointDetail(context, params, id) {
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
  },
  saveMarket(context, params) {
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
  },
  modifyMarket(context, params, id) {
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
  },
  savePoint(context, params) {
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
  },
  modifyPoint(context, params, id) {
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
}
