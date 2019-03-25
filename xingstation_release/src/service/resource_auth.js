const PROJECT_AUTH_API = '/api/project_auth'
const HOST = process.env.SERVER_URL

//节目投放授权列表
const getProjectAuthListData = (context, args) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + PROJECT_AUTH_API, {params: args})
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

//节目投放授权详情
const getProjectAuthDetailData = (context,id) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + PROJECT_AUTH_API + '/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 添加节目授权
const saveProjectAuth = (context, params) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .post(HOST + PROJECT_AUTH_API, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 修改节目授权
const modifyProjectAuth = (context, id, params) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .patch(HOST + PROJECT_AUTH_API + '/' + id, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 删除节目授权
const destroyProjectAuth = (context, id) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .delete(HOST + PROJECT_AUTH_API + '/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

export {
  getProjectAuthListData,
  getProjectAuthDetailData,
  saveProjectAuth,
  modifyProjectAuth,
  destroyProjectAuth
}
