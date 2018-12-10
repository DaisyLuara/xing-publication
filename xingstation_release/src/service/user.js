const USER_API = '/api/system/users'
const ROLES_API = '/api/system/roles' // 获取当前用户可分配角色的分页接口
const HOST = process.env.SERVER_URL

const saveUser = (context, args, uid) => {
  if (uid) {
    return new Promise(function(resolve, reject) {
      context.$http
        .patch(HOST + USER_API + '/' + uid, args)
        .then(response => {
          resolve(response.data.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  } else {
    return new Promise(function(resolve, reject) {
      context.$http
        .post(HOST + USER_API, args)
        .then(response => {
          resolve(response.data.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  }
}
const getManageableRoles = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + ROLES_API)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const getUserDetial = (context, uid, args) => {
  if (!uid) {
    return false
  }
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + USER_API + '/' + uid, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const getUserList = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + USER_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const deleteUser = (context, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .delete(HOST + USER_API + '/' + id)
      .then(response => {
        resolve(response)
      })
      .catch(error => {
        reject(error)
      })
  })
}
export { deleteUser, getUserList, getUserDetial, getManageableRoles, saveUser }
