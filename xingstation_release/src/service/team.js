import { router } from '../main'
const HOST = process.env.SERVER_URL

const TEAM_API = '/tower/teams/'
const TASK_API = '/tower/projects/'
const TODOS_API = '/tower/todolists/'
const TODOSINFO_API = '/tower/todos/'
export default {
    getTowerList(context, id) {
        return new Promise(function(resolve, reject) {
            context.$http.get(HOST + TEAM_API + id  + '/members').then(response => {
                resolve(response.data)
            }).catch(error => {
                reject(error)
            })
        })
    },
    getProjectsList(context, id) {
        return new Promise(function(resolve, reject) {
            context.$http.get(HOST + TEAM_API + id + '/projects').then(response => {
                resolve(response.data)
            }).catch(error => {
                reject(error)
            })
        })
    },
    saveProjects(context, args, id) {
        return new Promise(function(resolve, reject) {
            context.$http.post(HOST + TEAM_API + id + '/projects', args).then(response => {
                resolve(response.data)
            }).catch(error => {
                reject(error)
            })
        })
    },
    //获取项目成员
    getProjectMembers(context, id) {
        return new Promise(function(resolve, reject) {
            context.$http.get(HOST + TASK_API + id  + '/members').then(response => {
                resolve(response.data)
            }).catch(error => {
                reject(error)
            })
        })
    },
    //指派任务负责人
    assignment(context, id, args) {
        return new Promise(function(resolve, reject) {
            context.$http.patch(HOST + TODOSINFO_API + id + '/assignment', args).then(response => {

                resolve(response.data)
            }).catch(error => {
                reject(error)
            })
        })
    },
    //更新到期日
    due(context, id, args) {
        return new Promise(function(resolve, reject) {
            context.$http.patch(HOST + TODOSINFO_API + id + '/due', args).then(response => {
                resolve(response.data)
            }).catch(error => {
                reject(error)
            })
        })
    },
    //获取任务列表
    getTodolists(context, id) {
        return new Promise(function(resolve, reject) {
            context.$http.get(HOST + TASK_API + id + '/todolists').then(response => {
                resolve(response.data)
            }).catch(error => {
                reject(error)
            })
        })
    },
    //获取任务列表清单
    getTodos(context, id) {
        return new Promise(function(resolve, reject) {
            context.$http.get(HOST + TODOS_API + id + '/todos').then(response => {
                resolve(response.data)
            }).catch(error => {
                reject(error)
            })
        })
    },
    //创建任务
    createTodos(context, id, args) {
        return new Promise(function(resolve, reject) {
            context.$http.post(HOST + TODOS_API + id + '/todos', args).then(response => {
                resolve(response.data)
            }).catch(error => {
                reject(error)
            })
        })
    },
    //修改任务
    modifyTodos(context, id, args) {
        return new Promise(function(resolve, reject) {
            context.$http.patch(HOST + TODOSINFO_API + id, args).then(response => {
                resolve(response.data)
            }).catch(error => {
                reject(error)
            })
        })
    },
    //删除任务
    deleteTodos(context, id) {
        return new Promise(function(resolve, reject) {
            context.$http.delete(HOST + TODOSINFO_API + id).then(response => {
                resolve(response.data)
            }).catch(error => {
                reject(error)
            })
        })
    },
    //获取任务清单信息
    getTodosInfo(context, id) {
        return new Promise(function(resolve, reject) {
            context.$http.get(HOST + TODOSINFO_API + id).then(response => {
                resolve(response.data)
            }).catch(error => {
                reject(error)
            })
        })
    },
    //完成任务
    completion(context, id) {
        return new Promise(function(resolve, reject) {
            context.$http.post(HOST + TODOSINFO_API + id + '/completion').then(response => {
                resolve(response.data)
            }).catch(error => {
                reject(error)
            })
        })
    },
    //打开任务
    openTask(context, id) {
        return new Promise(function(resolve, reject) {
            context.$http.delete(HOST + TODOSINFO_API + id + '/completion').then(response => {
                resolve(response.data)
            }).catch(error => {
                reject(error)
            })
        })
    }




}