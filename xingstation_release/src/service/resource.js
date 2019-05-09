const ACTIVITY_MEDIA_API = '/api/activity_media'
const TENANT_MEDIA_API = '/api/company_media'
const ACTIVITY_MEDIA_AUDIT_API = '/api/activity_media/audit'
const TENANT_MEDIA_AUDIT_API = '/api/company_media/audit'
const QINNIU_API = '/api/qiniu_oauth'
const MEDIA_UPLOAD_API = '/api/media_upload'
const PUB_MEDIA = '/pub_media'
const MEDIA_GROUP_API = '/api/pub_group'
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
      .post(HOST + MEDIA_UPLOAD_API, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 传给后台七牛的key和文件name，主要用在图片资源上传的
const imgMediaUpload = (context, groupId, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + MEDIA_GROUP_API + '/' + groupId + PUB_MEDIA, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 图片资源列表
const getImgMediaList = (context, groupId, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + MEDIA_GROUP_API + '/' + groupId + PUB_MEDIA, {
        params: params
      })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 图片资源修改
const modifyImgMedia = (context, id, params, groupId) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(
        HOST + MEDIA_GROUP_API + '/' + groupId + PUB_MEDIA + '/' + id,
        params
      )
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 组列表
const getMediaGroup = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + MEDIA_GROUP_API, { params: params })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
// 增加分组
const saveMediaGroup = (context, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + MEDIA_GROUP_API, params)
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

// 修改分组名称
const modifyMediaGroupName = (context, groupId, params) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + MEDIA_GROUP_API + '/' + groupId, params)
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
  modifyImgMedia,
  getMediaGroup,
  saveMediaGroup,
  modifyMediaGroupName
}
