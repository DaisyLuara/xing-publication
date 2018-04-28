const curUserInfo = {
  state: {
    mobile: '',
    old_password: '',
    new_password: ''
  },
  mutations: {
    setCurUserInfo (state, userInfo) {
      for(let item in userInfo){
        if(userInfo.hasOwnProperty(item)) {
          state[item] = userInfo[item]
        }
      }
    },
    setOldPassword (state, params) {
      state.mobile = params.mobile
      state.old_password = params.old_password
      state.new_password = params.new_password
    }
  },
  actions: {}
};

export default curUserInfo;
