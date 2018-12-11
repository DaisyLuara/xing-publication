const CHART_API = '/api/chart_data'
const CHART_TIMES_API = '/api/chart_data_times'
const EXCEL_API = '/api/export'
const HOST = process.env.SERVER_URL

const getChartData = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + CHART_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const getExcelData = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + EXCEL_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const getTimesChartData = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + CHART_TIMES_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
export { getTimesChartData, getExcelData, getChartData }
