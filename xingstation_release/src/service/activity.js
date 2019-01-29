const ACTIVITY_PARRICIPANTS_API = '/api/activity_participants'
const ACTIVITY_BILLS_API = '/api/red_pack_bills'
const REDPACK_API = '/api/activity_participants/redpack'
const RESEND_REDPACK_API = '/api/redpack/resend'
const HOST = process.env.SERVER_URL

// 活动参与者
const getActivityParticipantList = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + ACTIVITY_PARRICIPANTS_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 流水列表
const getActivityBillList = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + ACTIVITY_BILLS_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 发红包
const sendRedPack = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + REDPACK_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 重新发放
const reSendRedPack = (context, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + RESEND_REDPACK_API + '/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

export {
  getActivityParticipantList,
  getActivityBillList,
  sendRedPack,
  reSendRedPack
}
