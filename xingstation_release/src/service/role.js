import { router } from '../main'

const ROLE_API = '/api/tenants/roles'
const DELETE_ROLES = 'api/tenants/roles/destroyAll'
const PERMISSION_API = '/api/users/permissions'
const HOST = process.env.SERVER_URL

export default {
  getManageablePers(context) {
    let promise = new Promise(function(resolve, reject) {
      context.$http.get(HOST + PERMISSION_API).then(response => {
        resolve(response.data.data)
      }).catch(error => {
        reject(error)
      })
    })
    return promise;
  },
  deleteRoles(context, rids) {
    return context.$http.post(HOST + DELETE_ROLES, rids)
  },
  getRoleInfoByRid(context, rid) {
    if (!rid) {
      return false;
    }
    let promise = new Promise(function(resolve, reject) {
      context.$http.get(HOST + ROLE_API + '/' + rid).then(response => {
        resolve(response.data.data)
      }).catch(error => {
        reject(error)
      })
    })

    return promise;
  },
  saveRole(context, args, rid) {
    if (rid) {
      let promise = new Promise(function(resolve, reject) {
        context.$http.put(HOST + ROLE_API + "/" + rid, args).then(response => {
          resolve(response.data.data)
        }).catch(error => {
          reject(error)
        })
      })
      return promise;
    } else {
      let promise = new Promise(function(resolve, reject) {
        context.$http.post(HOST + ROLE_API, args).then(response => {
          resolve(response.data.data)
        }).catch(error => {
          reject(error)
        })
      })
      return promise;
    }
  }
}
