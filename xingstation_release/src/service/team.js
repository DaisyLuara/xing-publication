import {router} from '../main'
const TEAM_API = '/tower/teams/'
const TOWER_LIST = 'https://tower.im/api/v1/teams/c6dc912c2f494e7ea73bed4488bb3493/members'
export default {
  getTowerList(context,args) {
    return new Promise(function(resolve, reject){
      context.$http.get(TOWER_LIST, {params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getTeamList(context,id) {
    return new Promise(function(resolve, reject){
      context.$http.get(TEAM_API + id + '/projects').then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  }
}

