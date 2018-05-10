import {router} from '../main'
const AREAS_API = '/api/areas/query'
const MARKET_API = '/api/markets/query'
const MODULE_API = '/api/launches/tpl/query'
const POINT_API = '/api/points/query'
const PROJECT_API = '/api/projects/query'
const AD_TRADE_API = '/api/ad_trade/query'
const ADVERTISER_API='/api/advertiser/query'
const ADVERTISEMENT_API = '/api/advertisement/query'
export default {
  getAeraList(context) {
    return new Promise(function(resolve, reject){
      context.$http.get(AREAS_API).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getMarketList(context,args) {
    return new Promise(function(resolve, reject){
      context.$http.get(MARKET_API,{params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getModuleList(context) {
    return new Promise(function(resolve, reject){
      context.$http.get(MODULE_API).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  gePointList(context,args) {
    return new Promise(function(resolve, reject){
      context.$http.get(POINT_API,{params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getProjectList(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.get(PROJECT_API,{params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getAdTradeList(context) {
    return new Promise(function(resolve, reject){
      context.$http.get(AD_TRADE_API).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getAdvertiserList(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.get(ADVERTISER_API,{params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getAdvertisementList(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.get(ADVERTISEMENT_API,{params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  }

}
