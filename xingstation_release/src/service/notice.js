import {router} from '../main'
const NOTICE_API = '/api/user/notifications'
const READ_NOTICE_API = '/api/user/read/notifications'
const NOTICE_SRATS_API = '/api/user/notifications/stats'
const ACTIVITIES_API = '/api/user/activities'
const HOST = process.env.SERVER_URL

export default {
  getNoticeList(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + NOTICE_API ,{params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  readNotifications(context) {
    return new Promise(function(resolve, reject){
      context.$http.patch(HOST + READ_NOTICE_API).then(response => {
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  notificationStats(context) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + NOTICE_SRATS_API).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getActivitiesList(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + ACTIVITIES_API ,{params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
