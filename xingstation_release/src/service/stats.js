import {router} from '../main'
const STATS_API = '/api/stats'
export default {
  getStats(context,args) {
    return new Promise(function(resolve, reject){
      context.$http.get(STATS_API, {params:args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  }

}
