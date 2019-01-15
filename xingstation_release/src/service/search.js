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
const USER_API = '/api/user/query'
const TEAM_RATE_API = '/api/team_rate/query'
const FORMAT_API = '/api/attribute/query'
const CONTRACT_RECEIPT_API = '/api/contract/query'
const TEAM_PROJECT_API = '/api/team_projects/query'
const BD_API = '/api/bd_users/query'
const HOST = process.env.SERVER_URL

// 区域
const getSearchAeraList = context => {
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
}
// 商场
const getSearchMarketList = (context, args) => {
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
}
// 模版
const getSearchModuleList = context => {
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
}
// 点位
const getSearchPointList = (context, args) => {
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
}
// 节目
const getSearchProjectList = (context, args) => {
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
}
// 广告主
const getSearchAdTradeList = context => {
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
}
// 广告
const getSearchAdvertiserList = (context, args) => {
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
}
const getSearchAdvertisementList = (context, args) => {
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
}
// 星视度用户
const getSearchStaffsList = (context, args) => {
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
}
// 场景
const getSearchSceneList = context => {
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
}
// 公司
const getSearchCompanyList = context => {
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
}
// 优惠券
const getSearchCouponList = (context, args) => {
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
}
const getSearchPolicyList = context => {
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
}
// 法务主管
const getSearchLegalManagerList = context => {
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
}
// BD主管
const getSearchBDManagerList = context => {
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
}
// 核销人
const getSearchShopCustomerList = (context, params) => {
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
// 用户
const getSearchUserList = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + USER_API, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 比例
const getSearchTeamRateList = context => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + TEAM_RATE_API)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 业态
const getFormatsList = context => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + FORMAT_API)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 收款合同

const getContractReceiptList = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + CONTRACT_RECEIPT_API, { params: params })
      .then(response => {
        resolve(response.data.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const getSearchCopyrightProject = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + TEAM_PROJECT_API, { params: params })
      .then(response => {
        resolve(response.data.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const getSearchBDList = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + BD_API, { params: params })
      .then(response => {
        resolve(response.data.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
export {
  getSearchAeraList,
  getSearchMarketList,
  getSearchModuleList,
  getSearchPointList,
  getSearchProjectList,
  getSearchAdTradeList,
  getSearchAdvertiserList,
  getSearchAdvertisementList,
  getSearchStaffsList,
  getSearchCouponList,
  getSearchTeamRateList,
  getSearchUserList,
  getSearchShopCustomerList,
  getSearchBDManagerList,
  getSearchLegalManagerList,
  getSearchPolicyList,
  getSearchCompanyList,
  getSearchSceneList,
  getFormatsList,
  getContractReceiptList,
  getSearchCopyrightProject,
  getSearchBDList
}
