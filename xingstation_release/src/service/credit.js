const CREDIT_API = '/api/credits'
const CREDIT_LOG_API = '/api/credit_logs'
const HOST = process.env.SERVER_URL

const getCreditListData = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + CREDIT_API + '/market_owner', { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const getCreditLogListData = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + CREDIT_LOG_API + '/market_owner', { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}



export {
  getCreditListData,
  getCreditLogListData,

}
