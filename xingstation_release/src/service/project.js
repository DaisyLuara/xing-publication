const PROJECT_API = '/api/projects/launch'
const MODIFY_PROJECT_API = '/api/projects/launches'
const PROJECTLIST_API = '/api/projects'
const EXCEL_COUPONS_API = '/api/coupons/export'
const HOST = process.env.SERVER_URL

const getPutProjectList = (context, args) => {
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
const getProjectListDetails = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + PROJECTLIST_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const modifyProject = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + PROJECTLIST_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const savePorjectLaunch = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + PROJECT_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const modifyProjectLaunch = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + MODIFY_PROJECT_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const getExcelCouponsData = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + EXCEL_COUPONS_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

export {
  getPutProjectList,
  getProjectListDetails,
  modifyProject,
  savePorjectLaunch,
  modifyProjectLaunch,
  getExcelCouponsData
}
