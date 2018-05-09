import {router} from '../main'
const AD_API = '/api/ad_launch'
export default {
  getAdList(context,args) {
    return new Promise(function(resolve, reject){
      context.$http.get(AD_API, {params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },

}
