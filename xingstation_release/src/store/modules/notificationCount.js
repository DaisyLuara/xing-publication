const notificationCount = {
  state: {
    noticeCount: 0,
  },
  mutations: {
    saveNotificationState(state, obj) {
      state.noticeCount = obj.unread_count
    },
  },
  actions: {},
}

export default notificationCount
