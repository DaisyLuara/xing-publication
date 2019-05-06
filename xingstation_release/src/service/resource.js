const ACTIVITY_MEDIA_API = '/api/activity_media'
const TENANT_MEDIA_API = '/api/company_media'
const ACTIVITY_MEDIA_AUDIT_API = '/api/activity_media/audit'
const TENANT_MEDIA_AUDIT_API = '/api/company_media/audit'
const QINNIU_API = '/api/qiniu_oauth'
const MEDIA_UPLOAD_AP = '/api/media_upload'
const IMG_MEDIA_UPLOAD_AP = '/api/pub_media'
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

// 商户审核列表
const getTenantMediaList = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + TENANT_MEDIA_API, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 商户审核
const TenantMediaAudit = (context, id, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + TENANT_MEDIA_AUDIT_API + '/' + id, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 获得七牛token
const getQiniuToken = context => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + QINNIU_API)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 传给后台七牛的key和文件name，主要用在不是图片上传的
const getMediaUpload = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + MEDIA_UPLOAD_AP, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 传给后台七牛的key和文件name，主要用在图片资源上传的
const imgMediaUpload = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + IMG_MEDIA_UPLOAD_AP, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 图片资源列表
const getImgMediaList = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + IMG_MEDIA_UPLOAD_AP, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 图片资源修改
const modifyImgMedia = (context, id, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + IMG_MEDIA_UPLOAD_AP + '/' + id, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

export {
  getActivityMediaList,
  activityMediaAudit,
  getTenantMediaList,
  TenantMediaAudit,
  getQiniuToken,
  getMediaUpload,
  imgMediaUpload,
  getImgMediaList,
  modifyImgMedia
}
