import { router } from '../main'
const EQUIPMENT_API = '/api/push'
const FEEDBACK_API = '/tmall/feedback'

const HOST = process.env.SERVER_URL

export default {
  gettEquipmentList(context, args) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + EQUIPMENT_API, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  getFeedBackList(context, args) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + FEEDBACK_API, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  }
}
