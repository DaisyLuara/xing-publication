const HOST = process.env.SERVER_URL

const TEAM_API = '/api/team_project'
const TEAM_SYSTEM_API = '/api/team_system_project'
const TEAM_RATE_API = '/api/team_rate'
const SYSTEM_BONUS_API = '/api/system_bonus'
const SYSTEM_DISTRIBTION_BONUS_API = '/api/distribution_bonus'
const SYSTEM_DETAIL_API = '/api/system_detail'
const PERSON_REWARD_API = '/api/person_reward'

// 得到项目列表
const getProgramList = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + TEAM_API, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 保存项目
const saveProgram = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + TEAM_API, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 修改项目
const modifyProgram = (context, params, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + TEAM_API + '/' + id, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 得到项目详情
const getProgramDetails = (context, id, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + TEAM_API + '/' + id, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 确认项目
const confirmProgram = (context, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + TEAM_API + '/confirm/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 得到比例列表
const getTeamRate = context => {
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
// 得到比例详情
const getTeamRateDetails = (context, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + TEAM_RATE_API + '/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 修改比例
const modifyTeamRate = (context, id, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + TEAM_RATE_API + '/' + id, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 新建平台申请
const saveTeamSystemProject = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + TEAM_SYSTEM_API, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 获得平台申请列表
const getTeamSystemProject = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + TEAM_SYSTEM_API, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 奖金分配
const systemDistribute = (context, id, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + TEAM_SYSTEM_API + '/distribute/' + id, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 平台总奖金
const getSystemBonus = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + SYSTEM_BONUS_API, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 平台已分配奖金
const getDistributionBonus = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + SYSTEM_DISTRIBTION_BONUS_API, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 平台驳回
const systemReject = (context, id, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + TEAM_SYSTEM_API + '/reject/' + id, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 平台明细列表

const getSystemDetails = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + SYSTEM_DETAIL_API, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 个人奖金列表
const getPersonRewardList = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + PERSON_REWARD_API, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 个人中心总金额
const getPersonRewardTotal = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + PERSON_REWARD_API + '/total', { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 法务新增平台明细分配
const saveSystemDetailMoney = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + SYSTEM_DETAIL_API, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 法务修改平台明细分配
const modifySystemDetailMoney = (context, id, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + SYSTEM_DETAIL_API + '/' + id, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 平台明细分配详情
const getSystemlMoneyDetail = (context, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + SYSTEM_DETAIL_API + '/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

export {
  getProgramList,
  saveProgram,
  modifyProgram,
  getProgramDetails,
  confirmProgram,
  getTeamRate,
  getTeamRateDetails,
  modifyTeamRate,
  saveTeamSystemProject,
  getTeamSystemProject,
  systemDistribute,
  getSystemBonus,
  getDistributionBonus,
  systemReject,
  getSystemDetails,
  getPersonRewardList,
  getPersonRewardTotal,
  saveSystemDetailMoney,
  modifySystemDetailMoney,
  getSystemlMoneyDetail
}
