import { router } from '../main'

const Store_INFO_API = '/api/store/stores'
const Supply_INFO_API = '/api/store/supplies'
const Common_INFO_API = 'api/common/province'
export default {
  getSuppliesListByShopId(context, shopId) {
    let promise = new Promise(function(resolve, reject) {
      context.$http.get(Store_INFO_API + "/" + shopId).then(response => {
        resolve(response.data.data)
      }).catch(error => {
        reject(error)
      })
    })
    return promise;
  },
  getStoreList(context, args) {
    let promise = new Promise(function(resolve, reject) {
      context.$http.get(Store_INFO_API, { params: args }).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
    return promise;
  },
  getSuppliesList(context, args) {
    let promise = new Promise(function(resolve, reject) {
      context.$http.get(Supply_INFO_API + "?limit=" + args.limit + "&page_num=" + args.page_num).then(response => {
        resolve(response.data.data)

      }).catch(error => {
        reject(error)
      })
    })
    return promise;
  },
  getProvinceCityDistrictList(context) {
    let promise = new Promise(function(resolve, reject) {
      context.$http.get(Common_INFO_API).then(response => {
        resolve(response.data.data)
      }).catch(error => {
        reject(error)
      })
    })
    return promise;
  },
  deleteStore(context, id) {
    console.log(id)
    let promise = new Promise(function(resolve, reject) {
      context.$http.delete(Store_INFO_API + '/' + id).then(response => {
        resolve(response)
      }).catch(err => {
        reject(err)
      })
    })
    return promise;
  },
  submitHandle(context, args) {
    let promise = new Promise(function(resolve, reject) {
      context.$http.post(Store_INFO_API, args).then(response => {
        resolve(response.data.data)
      }).catch(error => {
        reject(error)
      })
    })
    return promise;
  },
  modifyStoreHandle(context, id, args) {
    let promise = new Promise(function(resolve, reject) {
      context.$http.put(Store_INFO_API + '/' + id, args).then(response => {
        resolve(response.data.data)
      }).catch(error => {
        reject(error)
      })
    })
    return promise;
  },
  getSotreById(context, id) {
    let promise = new Promise(function(resolve, reject) {
      context.$http.get(Store_INFO_API + '/' + id).then(response => {
        resolve(response.data.data)
      }).catch(err => {
        reject(err)
      })
    })
    return promise;
  },
  getPageInfosByStoreId(context, storeId) {
    let promise = new Promise(function(resolve, reject) {
      context.$http.get(Store_INFO_API + '/' + storeId + '/pageinfos').then(response => {
        resolve(response.data)
      }).catch(err => {
        reject(err)
      })
    })
    return promise;
  }

}
