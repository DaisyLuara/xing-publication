const AD_API = '/api/ad_launch'
const HOST = process.env.SERVER_URL

const getAdList = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + AD_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const saveAdLaunch = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + AD_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const modifyAdLaunch = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + AD_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
export { getAdList, saveAdLaunch, modifyAdLaunch }
