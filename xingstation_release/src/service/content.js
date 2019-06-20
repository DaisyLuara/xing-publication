const HOST = process.env.SERVER_URL
const LIST_API = HOST + '/api/content_'
const NEWS_LIST_API = HOST + '/api/content_news'
const NEWS_DETAIL_API = HOST + ''
const CASES_LIST_API = HOST + ''
const JOBS_LIST_API = HOST + ''

const getTableList = (context, type, args) => {
  return new Promise((resolve, reject) => {
    context.$http
      .get(LIST_API + type, { params: args })
      .then(res => {
        resolve(res.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}
const getNewsDetail = (context, args) => {
  return new Promise((resolve, reject) => {
    context.$http
      .get(NEWS_DETAIL_API, { params: args })
      .then(res => {
        resolve(res.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}
const modifyNews = (context, args) => {
  return new Promise((resolve, reject) => {
    context.$http
      .patch(NEWS_DETAIL_API, args)
      .then(res => {
        resolve(res.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}
const postNews = (context, args) => {
  return new Promise((resolve, reject) => {
    context.$http
      .post(NEWS_DETAIL_API, args)
      .then(res => {
        resolve(res.data)
      })
      .catch(err => {
        reject(err)
      })
  })
}

const getCasesList = () => {}
export { getTableList, getNewsDetail, modifyNews, postNews, getCasesList }
