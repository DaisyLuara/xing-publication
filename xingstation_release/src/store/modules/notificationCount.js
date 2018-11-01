const notificationCount = {
  state: {
    noticeCount: 0,
    setIntervalValue: ''
  },
  mutations: {
    saveNotificationState(state, obj) {
      state.noticeCount = obj.unread_count
      state.setIntervalValue = obj.setIntervalValue
    },
  },
  actions: {},
}

export default notificationCount
