import { router } from '../main'
const COUPON_API = '/api/coupon/batches'
const ADD_COUPON_API = '/api/company/'
const HOST = process.env.SERVER_URL

export default {
  saveCoupon(context, args, uid, companyId) {
    if (uid) {
      let promise = new Promise(function(resolve, reject) {
        context.$http
          .patch(HOST + COUPON_API + '/' + uid, args)
          .then(response => {
            resolve(response.data.data)
          })
          .catch(error => {
            reject(error)
          })
      })
      return promise
    } else {
      let promise = new Promise(function(resolve, reject) {
        context.$http
          .post(HOST + ADD_COUPON_API + companyId + '/coupon/batch', args)
          .then(response => {
            resolve(response.data.data)
          })
          .catch(error => {
            reject(error)
          })
      })
      return promise
    }
  },
  getCouponDetial(context, uid, args) {
    if (!uid) {
      return false
    }
    let promise = new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + COUPON_API + '/' + uid, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
    return promise
  },
  getCouponList(context, args) {
    let promise = new Promise(function(resolve, reject) {
      context.$http
        .get(HOST + COUPON_API, { params: args })
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
    return promise
  },
  deleteCoupon(context, id) {
    let promise = new Promise(function(resolve, reject) {
      context.$http
        .delete(HOST + COUPON_API + '/' + id)
        .then(response => {
          resolve(response)
        })
        .catch(error => {
          reject(error)
        })
    })
    return promise
  }
}
