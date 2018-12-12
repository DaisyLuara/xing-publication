const URL_API = '/api/short_urls'
const HOST = process.env.SERVER_URL

const getUrlList = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + URL_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const saveUrl = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + URL_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
export { getUrlList, saveUrl }
