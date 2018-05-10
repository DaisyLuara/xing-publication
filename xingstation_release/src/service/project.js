import {router} from '../main'
const PROJECT_API = '/api/projects/launch'
const MODIFY_PROJECT_API = '/api/projects/launches'
const PROJECTLIST_API = '/api/projects'
export default {
  getProjectList(context,args) {
    return new Promise(function(resolve, reject){
      context.$http.get(PROJECT_API, {params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getProjectListDetails(context,args) {
    return new Promise(function(resolve, reject){
      context.$http.get(PROJECTLIST_API, {params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  savePorjectLaunch(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.post(PROJECT_API, args).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  modifyProjectLaunch(context, args){
    return new Promise(function(resolve, reject){
      context.$http.patch(MODIFY_PROJECT_API, args).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  }

}
