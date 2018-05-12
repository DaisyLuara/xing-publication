import {router} from '../main'
// const TOWER = '/tower/'
const HOST = 'https://tower.im/'
const TEAM_API = 'api/v1/teams/'
const Authorization_API = '/login/tower'
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
      context.$http.get(HOST + TEAM_API + id + '/projects').then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  towerAuthorization(context) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + Authorization_API).then(response => {
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
    
  }
}

