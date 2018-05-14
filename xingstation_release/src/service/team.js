import {router} from '../main'
const HOST = process.env.TOWER_URL

const TEAM_API = 'api/v1/teams/'
export default {
  getTowerList(context, id) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + TEAM_API + id + '/members').then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getProjectsList(context,id) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + TEAM_API + id + '/projects').then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
}

