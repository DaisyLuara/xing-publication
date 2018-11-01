import { router } from '../main'
const URL_API = '/api/short_urls'
const HOST = process.env.SERVER_URL
export default {
  getUrlList(context, args) {
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
  },
  saveUrl(context, args) {
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
}
