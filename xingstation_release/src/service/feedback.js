const FEEDBACK_API = '/api/feedback'
const HOST = process.env.SERVER_URL

const getFeedbackList = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + FEEDBACK_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const getFeedbackDetail = (context, id, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + FEEDBACK_API + '/' + id, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const saveFeedback = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + FEEDBACK_API, args)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

export { getFeedbackList, getFeedbackDetail, saveFeedback }
