import {router} from '../main'
const TOWER = '/tower/'
const HOST = 'https://tower.im/'
const TEAM_API = 'api/v1/teams/'
const Authorization_API = '/login/tower'
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

