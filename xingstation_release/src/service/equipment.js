import {router} from '../main'
const EQUIPMENT_API = '/api/push'
const HOST = process.env.SERVER_URL

export default {
  gettEquipmentList(context,args) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + EQUIPMENT_API, {params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  }
}