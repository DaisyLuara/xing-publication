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
          name: '广告投放详情列表',
          meta: {
            title: '广告投放详情列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/activity/participants/index" */ 'page/activity/participants/index')
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
