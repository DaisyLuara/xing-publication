const getters = {
  isRefreshToken: state => state.appSetting.isRefreshToken,
  currRoute: state => state.appSetting.currRoute,
  loadingApp: state => state.appSetting.loadingApp
};
export default getters
