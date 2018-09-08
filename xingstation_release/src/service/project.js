const PROJECT_API = '/api/projects/launch'
const MODIFY_PROJECT_API = '/api/projects/launches'
const PROJECTLIST_API = '/api/projects'
const HOST = process.env.SERVER_URL

export default {
  getProjectList(context, args) {
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
  },
  getProjectListDetails(context, args) {
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
  },
  modifyProject(context, args) {
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
  },
  savePorjectLaunch(context, args) {
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
  },
  modifyProjectLaunch(context, args) {
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
}
