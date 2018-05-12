import {router} from '../main'
const TEAM_API = '/tower/teams/'
export default {
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
