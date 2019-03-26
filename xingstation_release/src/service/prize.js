const LAUNCH_PRIZE_API = '/api/launch/policies'
const HOST = process.env.SERVER_URL

// 奖品投放列表
const getLaunchPirzeList = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + LAUNCH_PRIZE_API, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 新增奖品投放
const saveLaunchPirze = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + LAUNCH_PRIZE_API, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 修改奖品投放
const modifyLaunchPirze = (context, id, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + LAUNCH_PRIZE_API + '/' + id, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 奖品投放详情
const getLaunchPirzeDetail = (context, id, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + LAUNCH_PRIZE_API + '/' + id, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
export {
  getLaunchPirzeList,
  saveLaunchPirze,
  modifyLaunchPirze,
  getLaunchPirzeDetail
}
