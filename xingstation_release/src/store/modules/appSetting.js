const appSetting = {
  state: {
    isRefreshToken: false,
    currRoute: {},
    loadingApp: false,
  },
  mutations: {
    refreshRoute(state, route) {
      state.currRoute = route
    },
    setRefreshTokenStatus(state, status) {
      state.isRefreshToken = status
    },
    setLoadingAppStatus(state, status) {
      state.loadingApp = status
    },
  },
  actions: {},
}

export default appSetting
