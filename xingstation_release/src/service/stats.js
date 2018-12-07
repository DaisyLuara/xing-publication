import { router } from '../main'
const STATS_API = '/api/stats'
const STATS_TIMES_API = '/api/times_stats'
const HOST = process.env.SERVER_URL

export default {
  getStaus(context, args) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + STATS_API, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },
  getTimesStaus(context, args) {
    return new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + STATS_TIMES_API, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  }
}
