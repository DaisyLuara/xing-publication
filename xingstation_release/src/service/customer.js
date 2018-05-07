import {router} from '../main'
const CUSTOMER_API = '/api/customers'
export default {
  saveCustomer(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.post(CUSTOMER_API, args).then(response => {
        resolve(response.data.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  // getManageableRoles(context, args) {
  //   let promise = new Promise(function(resolve, reject){
  //     context.$http.get(ROLES_API).then(response => {
  //       resolve(response.data)
  //     }).catch(error => {
  //       reject(error)
  //     })
  //   })
  //   return promise;
  // },
  // getUserInfoByUid(context, uid, args) {
  //   if(!uid) {
  //     return false;
  //   }
  //   let promise = new Promise(function(resolve, reject){
  //     context.$http.get(USER_API + '/' + uid ,{params: args}).then(response => {
  //       resolve(response.data)
  //     }).catch(error => {
  //       reject(error)
  //     })
  //   })
  //   return promise;
  // },
  getCustomerList(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.get(CUSTOMER_API ,{params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  
}
