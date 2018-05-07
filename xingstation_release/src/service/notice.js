import {router} from '../main'
const NOTICE_API = '/api/user/notifications'
export default {
  getNoticeList(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.get(NOTICE_API ,{params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  
}
