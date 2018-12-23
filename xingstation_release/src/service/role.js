const ROLE_API = '/api/tenants/roles'
const PERMISSION_API = '/api/users/permissions'
const HOST = process.env.SERVER_URL

const getManageablePers = context => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + PERMISSION_API)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 详情
const getRoleInfoByRid = (context, rid) => {
  if (!rid) {
    return false
  }
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + ROLE_API + '/' + rid)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 保存修改
const saveRole = (context, args, rid) => {
  if (rid) {
    return new Promise(function(resolve, reject) {
      context.$http
        .put(HOST + ROLE_API + '/' + rid, args)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  } else {
    return new Promise(function(resolve, reject) {
      context.$http
        .post(HOST + ROLE_API, args)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  }
}
// 详情
const getRoleList = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + ROLE_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
export { getManageablePers, saveRole, getRoleInfoByRid, getRoleList }
