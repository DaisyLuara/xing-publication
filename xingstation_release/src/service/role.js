import { router } from '../main'

const ROLE_API = '/api/tenants/roles'
const DELETE_ROLES = 'api/tenants/roles/destroyAll'
const PERMISSION_API = '/api/users/permissions'

export default {
  getManageablePers(context) {
    let promise = new Promise(function(resolve, reject) {
      context.$http.get(PERMISSION_API).then(response => {
        resolve(response.data.data)
      }).catch(error => {
        reject(error)
      })
    })
    return promise;
  },
  deleteRoles(context, rids) {
    return context.$http.post(DELETE_ROLES, rids)
  },
  getRoleInfoByRid(context, rid) {
    if (!rid) {
      return false;
    }
    let promise = new Promise(function(resolve, reject) {
      context.$http.get(ROLE_API + '/' + rid).then(response => {
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
        context.$http.put(ROLE_API + "/" + rid, args).then(response => {
          resolve(response.data.data)
        }).catch(error => {
          reject(error)
        })
      })
      return promise;
    } else {
      let promise = new Promise(function(resolve, reject) {
        context.$http.post(ROLE_API, args).then(response => {
          resolve(response.data.data)
        }).catch(error => {
          reject(error)
        })
      })
      return promise;
    }
  }
}
