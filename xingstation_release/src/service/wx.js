const HOST = process.env.SERVER_URL

const CARD_API = '/api/cards/list'
const SINGLECARD_API = '/api/cards/show/'
const UPDATE_SINGLECARD_API = '/api/cards/update/'
const DELETE_SINGLECARD_API = '/api/cards/delete/'
//新增
const ADD_SINGLECARD_API = '/api/cards/delete/'
//查卡券列表
const getCardList = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + CARD_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
//获取单个卡券详情
const getSingleCard = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .get(HOST + SINGLECARD_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
//修改单个卡券
const modifySingleCard = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .patch(HOST + UPDATE_SINGLECARD_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
//删除单个卡券
const deleteSingleCard = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .delete(HOST + DELETE_SINGLECARD_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}
//新增卡券
const addSingleCard = (context, args) => {
  return new Promise(function(resolve, reject) {
    context.$http
      .post(HOST + ADD_SINGLECARD_API, { params: args })
      .then(response => {
        resolve(response.data)
      })
      .catch(error => {
        reject(error)
      })
  })
}

export {
  getCardList,
  getSingleCard,
  modifySingleCard,
  deleteSingleCard,
  addSingleCard
}
