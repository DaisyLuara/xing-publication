const POLICIES_API = '/api/coupon/policies'
const COMPANY_API = '/api/company/'
const COUPON_POLICY_API = '/api/coupon/policy/'
const BATCH_API = '/api/policy/'
const HOST = process.env.SERVER_URL

const getPoliciesList = (context, args) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + POLICIES_API, {params: args})
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const getPoliciesListByCompany = (context, company_id) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + POLICIES_API + '/' + company_id + '/company')
      .then(response => {
        resolve(response.data.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const savePolicy = (context, companyId, args) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .post(HOST + COMPANY_API + companyId + '/coupon/policy', args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const modifyPolicy = (context, companyId, args) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .patch(HOST + COUPON_POLICY_API + companyId, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const getPolicyDetail = (context, id, args) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .get(HOST + POLICIES_API + '/' + id, {params: args})
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const modifyBatchPolicy = (context, policyId, args, id) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .patch(HOST + BATCH_API + policyId + '/batch_policy/' + id, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const saveBatchPolicy = (context, policyId, args) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .post(HOST + BATCH_API + policyId, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const deleteBatchPolicy = (context, policyId, id) => {
  return new Promise(function (resolve, reject) {
    context.$http
      .delete(HOST + BATCH_API + policyId + '/batch_policy/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
export {
  deleteBatchPolicy,
  saveBatchPolicy,
  modifyBatchPolicy,
  modifyPolicy,
  savePolicy,
  getPoliciesList,
  getPoliciesListByCompany,
  getPolicyDetail
}
