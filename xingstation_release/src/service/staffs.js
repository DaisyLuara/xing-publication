import {router} from '../main'
const STAFFS_API = '/api/staffs'
export default {
  getStaffsList(context,args) {
    return new Promise(function(resolve, reject){
      context.$http.get(STAFFS_API).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  }

}
