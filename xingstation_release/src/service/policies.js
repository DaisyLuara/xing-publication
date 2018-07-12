import { router } from '../main'
const POLICIES_API = '/api/coupon/policies'
const COMPANY_API = '/api/company/'
const COUPON_POLICY_API = '/api/coupon/policy/'
const BATCH_API = '/api/policy/'
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
  savePolicy(context, companyId, args) {
    let promise = new Promise(function(resolve, reject) {
      context.$http
        .post(HOST + COMPANY_API + companyId + '/coupon/policy', args)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
    return promise
  },
  modifyPolicy(context, companyId, args) {
    let promise = new Promise(function(resolve, reject) {
      context.$http
        .patch(HOST + COUPON_POLICY_API + companyId, args)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
    return promise
  },
  modifyBatchPolicy(context, companyId, args, id) {
    let promise = new Promise(function(resolve, reject) {
      context.$http
        .patch(HOST + BATCH_API + companyId + '/batch_policy/' + id, args)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
    return promise
  },
  saveBatchPolicy(context, policyId, args) {
    let promise = new Promise(function(resolve, reject) {
      context.$http
        .post(HOST + BATCH_API + policyId, args)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
    return promise
  },
  deleteBatchPolicy(context, companyId, id) {
    let promise = new Promise(function(resolve, reject) {
      context.$http
        .delete(HOST + BATCH_API + companyId + '/batch_policy/' + id)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          reject(error)
        })
    })
    return promise
  }
}
