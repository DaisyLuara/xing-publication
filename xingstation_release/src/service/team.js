const HOST = process.env.SERVER_URL

const TEAM_API = '/api/team_project'
const TEAM_RATE_API = '/api/team_rate'
const PERSON_REWARD_API = '/api/person_reward'
const FUTURE_REWARD_API = '/api/person_future_reward'
const QINNIU_API = '/api/qiniu_oauth'
const MEDIA_UPLOAD_AP = '/api/media_upload'
const EVENT_API = '/api/team_project_bug_records'
const OPERATION_MEDIA_API = '/api/media_infos'
const EXCEL_TEAM_API = '/api/team_project_export'


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
const confirmProgram = (context, id, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + TEAM_API + '/confirm/' + id, params)
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

// 个人冻结奖金金额
const getPersonFutureRewardTotal = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + FUTURE_REWARD_API + '/total', { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 冻结明细列表
const getFutureRewardList = (context, params) => {
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

// 重大列表
const getEventList = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + EVENT_API, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 保存重大事件
const saveEvent = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + EVENT_API, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 修改重大事件
const modifyEvent = (context, id, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + EVENT_API + '/' + id, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 得到重大事件详情
const getEventDetails = (context, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + EVENT_API + '/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 获得七牛token
const getQiniuToken = context => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + QINNIU_API)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 传给后台七牛的key和文件name
const getMediaUpload = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + MEDIA_UPLOAD_AP, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 运营文档列表
const getOperationDocumentList = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + OPERATION_MEDIA_API, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 保存运营文档
const saveOperationDocument = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + OPERATION_MEDIA_API, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 修改运营文档
const modifyOperationDocument = (context, params, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + OPERATION_MEDIA_API + '/' + id, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 得到运营文档详情
const getOperationDocumentDetails = (context, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + OPERATION_MEDIA_API + '/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 删除运营文档详情
const deleteOperationDocument = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .delete(HOST + OPERATION_MEDIA_API, { params: params })
      .then(response => {
        resolve(response)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 导出
const getExcelTeamData = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + EXCEL_TEAM_API, { params: args })
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
  getFutureRewardList,
  getEventList,
  saveEvent,
  modifyEvent,
  getEventDetails,
  getQiniuToken,
  getMediaUpload,
  getOperationDocumentList,
  saveOperationDocument,
  modifyOperationDocument,
  getOperationDocumentDetails,
  deleteOperationDocument,
  getPersonFutureRewardTotal,
  getExcelTeamData
}
