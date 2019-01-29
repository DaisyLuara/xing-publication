import auth from 'service/auth'

let router = {
  path: 'activity',
  redirect: 'activity/participants',
  name: '活动',
  meta: {
    title: '活动',
    permission: ''
  },
  component: () =>
    import(/* webpackChunkName: "page/activity/activityView" */ 'page/activity/activityView'),
  children: [
    {
      path: 'participants',
      meta: {
        title: '活动参与者'
      },
      component: () =>
        import(/* webpackChunkName: "page/activity/participants/routerView" */ 'page/activity/participants/routerView'),
      children: [
        {
          path: '/',
          name: '活动参与者列表',
          meta: {
            title: '活动参与者列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/activity/participants/index" */ 'page/activity/participants/index')
        }
      ]
    },
    {
      path: 'bill',
      meta: {
        title: '交易流水'
      },
      component: () =>
        import(/* webpackChunkName: "page/activity/bill/routerView" */ 'page/activity/bill/routerView'),
      children: [
        {
          path: '/',
          name: '流水列表',
          meta: {
            title: '流水列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/activity/bill/index" */ 'page/activity/bill/index')
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/activity/' + route.path
  }
}

export default router
