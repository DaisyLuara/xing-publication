import {router} from '../main'
const PROJECT_API = '/api/projects/launch'
export default {
  getProjectList(context,args) {
    return new Promise(function(resolve, reject){
      context.$http.get(PROJECT_API, {params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  savePorjectLaunch(context, args, uid) {
    return new Promise(function(resolve, reject){
      context.$http.post(PROJECT_API, args).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },

}
