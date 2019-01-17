const ROLE_API = '/api/role'
const HOST = process.env.SERVER_URL

// 详情
const getRoleInfo = (context, rid) => {
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
        .patch(HOST + ROLE_API + '/' + rid, args)
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
// 角色列表
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

// 删除角色
const deleteRole = (context, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .delete(HOST + ROLE_API + '/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
export { saveRole, getRoleInfo, getRoleList, deleteRole }
