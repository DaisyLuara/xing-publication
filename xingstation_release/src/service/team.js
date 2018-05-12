import {router} from '../main'
const TOWER = '/tower/'
const TEAM_API = 'api/v1/teams/'
const Authorization_API = '/login/tower'
export default {
  getTowerList(context, id) {
    return new Promise(function(resolve, reject){
      context.$http.get(TOWER+TEAM_API + id + '/members').then(response => {
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
  },
  towerAuthorization(context) {
    return new Promise(function(resolve, reject){
      context.$http.get(TOWER + Authorization_API).then(response => {
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
    
  }
}

