const SKIP_API = '/api/system_skip'
const HOST = process.env.SERVER_URL

const redirectUrl = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + SKIP_API, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
export { redirectUrl }
