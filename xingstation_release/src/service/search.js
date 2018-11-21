const STAFFS_API = '/api/staffs'
const AREAS_API = '/api/areas/query'
const MARKET_API = '/api/markets/query'
const MODULE_API = '/api/launches/tpl/query'
const POINT_API = '/api/points/query'
const PROJECT_API = '/api/projects/query'
const AD_TRADE_API = '/api/ad_trade/query'
const ADVERTISER_API = '/api/advertiser/query'
const ADVERTISEMENT_API = '/api/advertisement/query'
const SENCE_API = '/api/scene/query'
const COMPANY_API = '/api/company/query'
const COUPON_API = '/api/coupon_batch/query'
const POLICY_API = '/api/policy/query'
const LEGAL_MANAGER_API = '/api/legal_manager/query'
const BD_MANAGER_API = '/api/bd_manager/query'
const CUSTOMER_API = '/api/customer/query'
const HOST = process.env.SERVER_URL

export default {
  // 区域
  getAeraList(context) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + AREAS_API)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  // 商场
  getMarketList(context, args) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + MARKET_API, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  // 模版
  getModuleList(context) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + MODULE_API)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  // 点位
  gePointList(context, args) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + POINT_API, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  // 节目
  getProjectList(context, args) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + PROJECT_API, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  // 广告主
  getAdTradeList(context) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + AD_TRADE_API)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  // 广告
  getAdvertiserList(context, args) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + ADVERTISER_API, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  getAdvertisementList(context, args) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + ADVERTISEMENT_API, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  // 用户
  getUserList(context, args) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + STAFFS_API, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  // 场景
  getSceneList(context) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + SENCE_API)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  // 公司
  getCompanyList(context) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + COMPANY_API)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  // 优惠券
  getCouponList(context, args) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + COUPON_API, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  getPolicyList(context) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + POLICY_API)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  // 法务主管
  getLegalManagerList(context) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + LEGAL_MANAGER_API)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  // BD主管
  getBDManagerList(context) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + BD_MANAGER_API)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  // 核销人
  getShopCustomerList(context, params) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + CUSTOMER_API, { params: params })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  }
}
