const STAFFS_API = '/api/staffs'
const AREAS_API = '/api/areas/query'
const MARKET_API = '/api/markets/query'
const MODULE_API = '/api/launches/tpl/query'
const POINT_API = '/api/points/query'
const PROJECT_API = '/api/projects/query'
const AD_TRADE_API = '/api/ad_trade/query'
const AD_PLAN_API = '/api/ad_plan/query'
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
const PERMISSION_API = '/api/permission/query'
const ROLE_API = '/api/role/query'
const CONTRACT_RECEIPT_API = '/api/contract/query'
const TEAM_PROJECT_API = '/api/team_projects/query'
const BD_API = '/api/bd_users/query'
const STORES_API = '/api/stores/query'
const COMPANY_MARKET_API = '/api/company/markets/query'
const PLATYING_TYPES_API = '/api/playing_types/query'
const CUSTOMERS_API = '/api/customers/query'
const CUSTOMERS_MARKET_OWNER_API = '/api/customers/role/market_owner/query'
const AUTH_POINT_API = '/api/authorized_points/query'
const AUTH_POLICY_API = '/api/authorized_policies/query'
const AUTH_PROJECT_API = '/api/authorized_projects/query'
const SKIN_API = '/api/project_skin/query'
const HOST = process.env.SERVER_URL

// 区域
const getSearchAeraList = context => {
  return new Promise(function (resolve, reject) {
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
  return new Promise(function (resolve, reject) {
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
  return new Promise(function (resolve, reject) {
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
  return new Promise(function (resolve, reject) {
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
  return new Promise(function (resolve, reject) {
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
// 广告行业
const getSearchAdTradeList = context => {
  return new Promise(function (resolve, reject) {
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
// 广告方案
const getSearchAdPlanList = (context, args) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + AD_PLAN_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
//广告
const getSearchAdvertisementList = (context, args) => {
  return new Promise(function (resolve, reject) {
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
  return new Promise(function (resolve, reject) {
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
  return new Promise(function (resolve, reject) {
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
const getSearchCompany = (context, args) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + COMPANY_API,{params: args})
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
  return new Promise(function (resolve, reject) {
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
  return new Promise(function (resolve, reject) {
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
  return new Promise(function (resolve, reject) {
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
  return new Promise(function (resolve, reject) {
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
  return new Promise(function (resolve, reject) {
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
  return new Promise(function (resolve, reject) {
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
  return new Promise(function (resolve, reject) {
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
  return new Promise(function (resolve, reject) {
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

// 权限树状结构
const getPermission = (context, args) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + PERMISSION_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}
// 收款合同

const getContractReceiptList = (context, params) => {
  return new Promise(function (resolve, reject) {
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
// 角色
const getSearchRole = (context, args) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + ROLE_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 原创节目
const getSearchCopyrightProject = (context, params) => {
  return new Promise(function (resolve, reject) {
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

// bd
const getSearchBDList = (context, params) => {
  return new Promise(function (resolve, reject) {
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
// 商户
const getStoresList = (context, params) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + STORES_API, { params: params })
      .then(response => {
        resolve(response.data.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 公司商户

const getCompanyMarketList = (context, params) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + COMPANY_MARKET_API, { params: params })
      .then(response => {
        resolve(response.data.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 玩法配置
const getSearchPlayingTypes = (context, params) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + PLATYING_TYPES_API, { params: params })
      .then(response => {
        resolve(response.data.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 联系人
const getSearchCustomer = (context, params) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + CUSTOMERS_API, { params: params })
      .then(response => {
        resolve(response.data.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

//场地主
const getSearchMarketOwnerCustomer = (context, params) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + CUSTOMERS_MARKET_OWNER_API, { params: params })
      .then(response => {
        resolve(response.data.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 已授权点位

const getSearchAuthPoint = (context, params) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + AUTH_POINT_API, { params: params })
      .then(response => {
        resolve(response.data.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 已授权节目

const getSearchAuthProject = (context, params) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + AUTH_PROJECT_API, { params: params })
      .then(response => {
        resolve(response.data.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 已授权策略

const getSearchAuthPolicies = (context, params) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + AUTH_POLICY_API, { params: params })
      .then(response => {
        resolve(response.data.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 皮肤

const getSearchSkin = (context, params) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + SKIN_API, { params: params })
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
  getSearchAdPlanList,
  getSearchAdvertisementList,
  getSearchStaffsList,
  getSearchCouponList,
  getSearchTeamRateList,
  getSearchUserList,
  getSearchShopCustomerList,
  getSearchBDManagerList,
  getSearchLegalManagerList,
  getSearchPolicyList,
  getSearchCompany,
  getSearchSceneList,
  getFormatsList,
  getPermission,
  getSearchRole,
  getContractReceiptList,
  getSearchCopyrightProject,
  getSearchBDList,
  getStoresList,
  getCompanyMarketList,
  getSearchPlayingTypes,
  getSearchCustomer,
  getSearchMarketOwnerCustomer,
  getSearchAuthPolicies,
  getSearchAuthPoint,
  getSearchAuthProject,
  getSearchSkin
}
