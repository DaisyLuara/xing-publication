const HOST = process.env.SERVER_URL

const TEAM_API = '/api/team_project'
const TEAM_RATE_API = '/api/team_rate'
const PERSON_REWARD_API = '/api/person_reward'
const FUTURE_REWARD_API = '/api/person_future_reward'

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
// 冻结明细列表
const getFutureRewardList =  (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + FUTURE_REWARD_API, { params: params })
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
  getPersonRewardList,
  getPersonRewardTotal,
  getFutureRewardList
}
