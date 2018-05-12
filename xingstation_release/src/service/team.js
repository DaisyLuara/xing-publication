import {router} from '../main'
const TOWER = '/tower/'
const TEAM_API = 'api/v1/teams/'
export default {
  getTowerList(context) {
    return new Promise(function(resolve, reject){
      context.$http.get(TOWER_LIST).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getProjectsList(context,id) {
    return new Promise(function(resolve, reject){
      context.$http.get(TOWER + TEAM_API + id + '/projects').then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  }
}

