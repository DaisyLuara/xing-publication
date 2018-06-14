import {router} from '../main'
const SCHEDULE_API = '/api/projects/launches/tpl'
const HOST = process.env.SERVER_URL

export default {
  getScheduleList(context,args) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + SCHEDULE_API, {params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
