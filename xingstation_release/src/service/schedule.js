const TEMPLATE_API = '/api/projects/launches/tpl'
const SCHEDULE_API = '/api/projects/schedules'
const HOST = process.env.SERVER_URL

const getScheduleList = (context, args) => {
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
const saveSchedule = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + SCHEDULE_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const modifySchedule = (context, id, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + SCHEDULE_API + '/' + id, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
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
export {
  modifyTemplate,
  saveTemplate,
  modifySchedule,
  saveSchedule,
  getScheduleList
}
