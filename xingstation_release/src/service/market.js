const MARKET_API = '/api/markets'
const POINT_API = '/api/points'
const HOST = process.env.SERVER_URL

export default {
  getMarketList(context, params) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + MARKET_API, { params: params })
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
        .get(HOST + POINT_API, { params: params })
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
        .get(HOST + MARKET_API + '/' + id, { params: params })
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
        .get(HOST + POINT_API + '/' + id, { params: params })
        .then(res => {
          resolve(res.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  }
}
