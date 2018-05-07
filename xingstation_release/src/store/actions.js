import shop from '../service/shop'
// import store from '../service/store'
import comments from '../service/comments'

export default {
  // FETCH_STORE_BY_ID: ({commit, state}, { store_id, context }) => {
  //   return store.getSotreById(context, store_id).then(storeData => {
  //     commit('SET_STORE_BY_ID', {store_id , storeData})
  //   })
  // },

  // FETCH_STORE_LIST: ({commit, state}, { context, args }) => {
  //   return store.getStoreList(context, args).then( result => {
  //     commit('SET_STORE', result.data)
  //   })
  // },

  // FETCH_PAGE_COMPONENTS: ({dispatch, commit, state}, { pid, pageType, previewType, context}) => {
  //   let callFuncName = ''
  //   if(pageType == 'design'){
  //     if(previewType == 'temp'){
  //       callFuncName = 'getPreviewPageByPid'
  //     }else{
  //       callFuncName = 'getShopPageByPid'
  //     }
  //   }else{
  //     if(previewType == 'temp'){
  //       callFuncName = 'getDefinePreviewPageByPid'
  //     }else{
  //       callFuncName = 'getCustomByPageId'
  //     }
  //   }
  //   return shop[callFuncName](context, pid).then(result => {
  //     let components = result.data;
  //     if(result){
  //       commit('SET_PAGE_INFO', { page: result})
  //     }
  //     if(components){
  //       commit('SET_COMPONENTS', { components:  components})
  //       // 获取组件内的store信息
  //       if(components.length > 0){
  //         let fetch_store_promises = [];
  //         components.map(({ store_id }) => {
  //           if(store_id ){
  //             fetch_store_promises.push(dispatch('FETCH_STORE_BY_ID', { store_id: store_id, context }))
  //           }
  //         }).filter(_ => _)

  //         return Promise.all(fetch_store_promises).catch(error => {
  //           console.log(error)
  //         })
  //       }
  //     }

  //   }).catch( error => {
  //     console.log(error)
  //   })
  // },

  // FETCH_COMMENTS: ({commit, state}, { store_id, args, context }) => {
  //   return comments.getCommentsListByLinkId(context, args).then(commentData => {
  //     commit('SET_COMMENTS_BY_ID', {store_id , commentData})
  //   }).catch( error => {
  //     console.log(error)
  //   })
  // },

}
