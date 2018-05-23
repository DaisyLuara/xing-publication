import {router} from '../main'
const CHART_API = '/api/chart_data'
const EXCEL_API = '/api/export'
const HOST = process.env.SERVER_URL

export default {
  getChartData(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.post(HOST + CHART_API, args).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getExcelData(context, args) {
    return new Promise(function(resolve, reject){
      context.$http.get(HOST + EXCEL_API, {params: args}).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
