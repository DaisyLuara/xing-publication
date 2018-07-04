import { router } from '../main'
const USER_API = '/api/system/users'
const ROLES_API = '/api/system/roles' // 获取当前用户可分配角色的分页接口
const HOST = process.env.SERVER_URL

export default {
  saveUser(context, args, uid) {
    if (uid) {
      let promise = new Promise(function(resolve, reject) {
        context.$http
          .patch(HOST + USER_API + '/' + uid, args)
          .then(response => {
            resolve(response.data.data)
          })
          .catch(error => {
            reject(error)
          })
      })
      return promise
    } else {
      let promise = new Promise(function(resolve, reject) {
        context.$http
          .post(HOST + USER_API, args)
          .then(response => {
            resolve(response.data.data)
          })
          .catch(error => {
            reject(error)
          })
      })
      return promise
    }
  },
  getManageableRoles(context, args) {
    let promise = new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + ROLES_API)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
    return promise
  },
  getUserDetial(context, uid, args) {
    if (!uid) {
      return false
    }
    let promise = new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + USER_API + '/' + uid, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
    return promise
  },
  getUserList(context, args) {
    let promise = new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + USER_API, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
    return promise
  },
  deleteUser(context, id) {
    let promise = new Promise(function(resolve, reject) {
      context.$http
        .delete(HOST + USER_API + '/' + id)
        .then(response => {
          resolve(response)
        })
        .catch(error => {
          reject(error)
        })
    })
    return promise
  }
}
