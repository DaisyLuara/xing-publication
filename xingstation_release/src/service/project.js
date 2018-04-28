import {router} from '../main'
const PROJECT_API = '/api/projects' // 获取当前用户可分配角色的分页接口
export default {
  getProjectList(context) {
    return new Promise(function(resolve, reject){
      context.$http.get(PROJECT_API).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  }

}
