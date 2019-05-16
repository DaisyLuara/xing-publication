const AD_LAUNCH_API = '/api/ad_launch'
const AD_PLAN_API = '/api/ad_plan'
const AD_PLAN_TIME_API = '/api/ad_plan_time'

const HOST = process.env.SERVER_URL

const getAdLaunchList = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + AD_LAUNCH_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const saveAdLaunch = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + AD_LAUNCH_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const modifyAdLaunch = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + AD_LAUNCH_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

//广告方案
const getAdPlanList = (context, args) => {
  return new Promise(function(resolve, reject) {
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

//广告方案详情
const getAdPlanDetail = (context, args, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + AD_PLAN_API + '/' + id, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}


//新增广告方案
const saveAdPlan = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + AD_PLAN_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

//批量更新广告方案以及其下排期
const modifyBatchAdPlan = (context, args, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + AD_PLAN_API + '/' + id, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

//更新广告方案
const modifyAdPlan = (context, args, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .put(HOST + AD_PLAN_API + '/' + id, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

//新增排期
const addAdPlanTime = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + AD_PLAN_TIME_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}


//更新排期
const modifyAdPlanTime = (context, args , plan_id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + AD_PLAN_TIME_API + '/' + plan_id + '/plan_id', args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

export {
  getAdLaunchList,
  saveAdLaunch,
  modifyAdLaunch,
  getAdPlanList,
  saveAdPlan,
  modifyBatchAdPlan,
  modifyAdPlan,
  modifyAdPlanTime,
  getAdPlanDetail

}
