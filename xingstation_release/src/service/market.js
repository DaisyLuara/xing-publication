const MARKETS_API = '/api/markets'
const MARKET_API = '/api/market'
const POINTS_API = '/api/points'
const POINT_API = '/api/point'
const BUSINESS_API = '/api/stores'
const HOST = process.env.SERVER_URL

// 场地里的场地列表
const getSiteMarketList = (context, params) => {
  return new Promise(function (resolve, reject) {
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
  return new Promise(function (resolve, reject) {
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
// 场地详情
const getSiteMarketDetail = (context, params, id) => {
  return new Promise(function (resolve, reject) {
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
// 点位详情
const getSitePointDetail = (context, params, id) => {
  return new Promise(function (resolve, reject) {
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
// 新增场地
const siteSaveMarket = (context, params) => {
  return new Promise(function (resolve, reject) {
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
// 修改场地
const siteModifyMarket = (context, params, id) => {
  return new Promise(function (resolve, reject) {
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
// 保存点位
const siteSavePoint = (context, params) => {
  return new Promise(function (resolve, reject) {
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
// 修改点位
const siteModifyPoint = (context, params, id) => {
  return new Promise(function (resolve, reject) {
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
// 商户列表
const getBusinessList = (context, params) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + BUSINESS_API, { params: params })
      .then(res => {
        resolve(res.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}
// 商户新增
const saveBusiness = (context, params) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .post(HOST + BUSINESS_API, params)
      .then(res => {
        resolve(res.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}

// 修改点位
const modifyBusiness = (context, params, id) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .patch(HOST + BUSINESS_API + '/' + id, params)
      .then(res => {
        resolve(res.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}

// 点位详情
const getBusinessDetail = (context, params, id) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + BUSINESS_API + '/' + id, { params: params })
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
  getSiteMarketList,
  getBusinessList,
  saveBusiness,
  modifyBusiness,
  getBusinessDetail
}
