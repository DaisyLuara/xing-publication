const ACTIVITY_PARRICIPANTS_API = '/api/activity_participants'
const ACTIVITY_BILLS_API = '/api/red_pack_bills'
const HOST = process.env.SERVER_URL

const getActivityParticipantList = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + ACTIVITY_PARRICIPANTS_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
const getActivityBillList = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + ACTIVITY_BILLS_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

export { getActivityParticipantList, getActivityBillList }
