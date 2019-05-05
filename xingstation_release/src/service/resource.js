const ACTIVITY_MEDIA_API = '/api/activity_media'
const ACTIVITY_MEDIA_AUDIT_API = '/api/activity_media/audit'
const HOST = process.env.SERVER_URL
// 活动审核列表
const getActivityMediaList = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + ACTIVITY_MEDIA_API, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 活动审核
const activityMediaAudit = (context, id, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + ACTIVITY_MEDIA_AUDIT_API + '/' + id, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

export { getActivityMediaList, activityMediaAudit }
