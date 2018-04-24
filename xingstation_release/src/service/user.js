import {router} from '../main'
const USER_API = '/api/tenants/users'
const ROLES_API = '/api/tenants/roles' // 获取当前用户可分配角色的分页接口
const DELETE_USERS = 'api/tenants/users/destroyAll ' //删除多个用户
export default {
  saveUser(context, args, uid) {
    if(uid){
      let promise = new Promise(function(resolve, reject){
        context.$http.put(USER_API + "/" + uid, args).then(response => {
          resolve(response.data.data)
        }).catch(error => {
          reject(error)
        })
      })
      return promise;
    }else{
      let promise = new Promise(function(resolve, reject){
        context.$http.post(USER_API, args).then(response => {
          resolve(response.data.data)
        }).catch(error => {
          reject(error)
        })
      })
      return promise;
    }
  },
  getManageableRoles(context, args) {
    let promise = new Promise(function(resolve, reject){
      context.$http.get(ROLES_API, {params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
    return promise;
  },
  getUserInfoByUid(context, uid) {
    if(!uid) {
      return false;
    }

    let promise = new Promise(function(resolve, reject){
      context.$http.get(USER_API + "/" + uid).then(response => {
        resolve(response.data.data)
      }).catch(error => {
        reject(error)
      })
    })

    return promise;
  },
  getUserList(context, args) {
    let promise = new Promise(function(resolve, reject){
      context.$http.get(USER_API, {params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })

    return promise;
  },
  deleteUsers(context, uids) {
    return context.$http.post(DELETE_USERS, uids)
  }
}
