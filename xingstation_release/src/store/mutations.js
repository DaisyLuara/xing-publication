import Vue from 'vue'

export default {
  SET_STORE_BY_ID: (state, {store_id, storeData}) => {
    Vue.set(state.store, store_id, storeData || false)
  },

  SET_COMPONENTS: (state, { components }) => {
    state.components = components
  },

  SET_PAGE_INFO: (state, { page }) => {
    state.page = page
  },

  SET_STORE: (state, storeList) => {
    let storeObj = {}
    for(let i = 0, l = storeList.length; i < l; i++){
      storeObj[storeList[i].id] = storeList[i]
    }
    state.store = storeObj;
  },

  SET_COMMENTS_BY_ID: (state, {store_id, commentData}) => {
    Vue.set(state.comments, store_id, commentData || false) /* false means user not found */
  },

}
