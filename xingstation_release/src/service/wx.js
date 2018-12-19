const COUPON_API = '/api/coupon/batches'
const HOST = process.env.SERVER_URL

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

export { getCouponList }
