import {router} from '../main'
const STAFFS_API = '/api/staffs'
const AREAS_API = '/api/areas/query'
const MARKET_API = '/api/markets/query'
const MODULE_API = '/api/launches/tpl/query'
const POINT_API = '/api/points/query'
const PROJECT_API = '/api/projects/query'
const AD_TRADE_API = '/api/ad_trade/query'
const ADVERTISER_API='/api/advertiser/query'
const ADVERTISEMENT_API = '/api/advertisement/query'
const SENCE_API = '/api/scene/query'
const COMPANY_API = '/api/company/query'
const COUPON_API = '/api/coupon_batch/query'
const HOST = process.env.SERVER_URL

export default {
  getAeraList(context) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + AREAS_API).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getMarketList(context,args) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + MARKET_API,{params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getModuleList(context) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + MODULE_API).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  gePointList(context,args) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + POINT_API,{params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getProjectList(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + PROJECT_API,{params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getAdTradeList(context) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + AD_TRADE_API).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getAdvertiserList(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + ADVERTISER_API,{params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getAdvertisementList(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + ADVERTISEMENT_API,{params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getUserList(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + STAFFS_API, {params:args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getSceneList(context) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + SENCE_API).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getCompanyList(context) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + COMPANY_API).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getCouponList(context) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + COUPON_API).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  }

}
