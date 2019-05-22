const TEMPLATE_API = '/api/projects/tpl'
const HOST = process.env.SERVER_URL
// 模版列表
const getTemplateList = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + TEMPLATE_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 模版新增
const saveTemplate = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + TEMPLATE_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 模版修改
const modifyTemplate = (context, id, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + TEMPLATE_API + '/' + id, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 排期列表
const getScheduleList = (context, pid, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(`${HOST}${TEMPLATE_API}/${pid}/schedules`, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 排期新增
const saveSchedule = (context, pid, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(`${HOST}${TEMPLATE_API}/${pid}/schedules`, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 排期修改
const modifySchedule = (context, id, pid, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(`${HOST}${TEMPLATE_API}/${pid}/schedules/${id}`, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 排期详情
const scheduleDetail = (context, id, pid, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(`${HOST}${TEMPLATE_API}/${pid}/schedules/${id}`, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

export {
  getTemplateList,
  modifyTemplate,
  saveTemplate,
  modifySchedule,
  saveSchedule,
  getScheduleList,
  scheduleDetail
}
