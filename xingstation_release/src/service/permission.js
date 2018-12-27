const PERMISSION_API = '/api/permission'
const HOST = process.env.SERVER_URL

// 详情
const getPermissionInfo = (context, id) => {
  if (!id) {
    return false
  }
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + PERMISSION_API + '/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 保存修改
const savePermission = (context, args, id) => {
  if (id) {
    return new Promise(function(resolve, reject) {
      context.$http
        .patch(HOST + PERMISSION_API + '/' + id, args)
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
        .post(HOST + PERMISSION_API, args)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  }
}
// 权限列表
const getPermissionList = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + PERMISSION_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 删除权限
const deletePermission = (context, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .delete(HOST + PERMISSION_API + '/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

export {
  savePermission,
  getPermissionInfo,
  getPermissionList,
  deletePermission
}
