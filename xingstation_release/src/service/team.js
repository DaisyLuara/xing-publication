const HOST = process.env.SERVER_URL

const TEAM_API = '/api/team_project'
const TEAM_RATE_API = '/api/team_rate'

const getProgramList = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + TEAM_API, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const saveProgram = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + TEAM_API, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const modifyProgram = (context, params, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + TEAM_API + '/' + id, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const getProgramDetails = (context, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + TEAM_API + '/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const confirmProgram = (context, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + TEAM_API + '/confirm/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const getTeamRate = context => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + TEAM_RATE_API)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const getTeamRateDetails = (context, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + TEAM_RATE_API + '/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const modifyTeamRate = (context, id, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + TEAM_RATE_API + '/' + id, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

export {
  getProgramList,
  saveProgram,
  modifyProgram,
  getProgramDetails,
  confirmProgram,
  getTeamRate,
  getTeamRateDetails,
  modifyTeamRate
}
