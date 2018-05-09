import {router} from '../main'
const EQUIPMENT_API = '/api/push'
export default {
  gettEquipmentList(context,args) {
    return new Promise(function(resolve, reject){
      context.$http.get(EQUIPMENT_API, {params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  }
}