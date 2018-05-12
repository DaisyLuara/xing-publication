import {router} from '../main'
const STAFFS_API = '/api/staffs'
const USER_PROJECT_API = '/api/projects/query'
const STATS_API = '/api/stats'
const DAY_DETAIL_API = '/api/detail'
const AGE_GENDER_API = '/api/age_gender'
const HOST = process.env.SERVER_URL

export default {
  getUser(context,args) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + STAFFS_API, {params:args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getProject(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + USER_PROJECT_API, {params:args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getStaus(context, args){
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + STATS_API, {params:args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getDayDetail(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + DAY_DETAIL_API, {params:args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getAgeAndGender(context, args){
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + AGE_GENDER_API, {params:args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  }

}
