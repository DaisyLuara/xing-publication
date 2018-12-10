const COUPON_API = '/api/coupon/batches'
const COUPONS_API = '/api/coupons'
const ADD_COUPON_API = '/api/company/'
const SYNC_COUPON_API = '/api/coupon/sync'
const HOST = process.env.SERVER_URL

const saveCoupon = (context, args, uid, companyId) => {
  if (uid) {
    return new Promise(function(resolve, reject) {
      context.$http
        .patch(HOST + COUPON_API + '/' + uid, args)
        .then(response => {
          resolve(response.data.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  } else {
    return new Promise(function(resolve, reject) {
      context.$http
        .post(HOST + ADD_COUPON_API + companyId + '/coupon/batch', args)
        .then(response => {
          resolve(response.data.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  }
}
const getCouponDetial = (context, uid, args) => {
  if (!uid) {
    return false
  }
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + COUPON_API + '/' + uid, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const getCouponList = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + COUPON_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const deleteCoupon = (context, id) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .delete(HOST + COUPON_API + '/' + id)
      .then(response => {
        resolve(response)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const getSyncCoupon = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + SYNC_COUPON_API, { params: args })
      .then(response => {
        resolve(response)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const putInCouponList = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + COUPONS_API, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
export {
  saveCoupon,
  getCouponDetial,
  getCouponList,
  deleteCoupon,
  getSyncCoupon,
  putInCouponList
}
