import { router } from '../main'
const POLICIES_API = '/api/coupon/policies'
const HOST = process.env.SERVER_URL

export default {
  getPoliciesList(context, args) {
    let promise = new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + POLICIES_API, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
    return promise
  },
}