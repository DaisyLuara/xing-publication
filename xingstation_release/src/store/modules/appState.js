const appState = {
  namespaced: true,
  state: {
    lastPage: null,
    lastClickTab: '',
    lastSearchValue: '',
  },
  mutations: {
    saveCurrentState(state, obj) {
      state.lastPage = obj.page
      state.lastClickTab = obj.tab
      state.lastSearchValue = obj.searchValue
    },
  },
  actions: {},
}

export default appState
