import auth from 'service/auth'

let router = {
  path: 'credit',
  name: '分值',
  meta: {
    title: '分值',
    permission: ''
  },
  component: () =>
    import(/* webpackChunkName: "page/credit/creditView" */ 'page/credit/creditView'),
  children: [
    {
      path: 'credit',
      meta: {
        title: '分值列表'
        // permission: 'credit.credit'
      },
      component: () =>
        import(/* webpackChunkName: "page/credit/credit/routerView" */ 'page/credit/credit/routerView'),
      children: [
        {
          path: '/',
          name: '场地主分值列表',
          meta: {
            title: '场地主分值列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/credit/credit/index" */ 'page/credit/credit/index')
        }
      ]
    },
    {
      path: 'credit_log',
      meta: {
        title: '分值记录'
        // permission: 'credit.credit_log'
      },
      component: () =>
        import(/* webpackChunkName: "page/credit/credit_log/routerView" */ 'page/credit/credit_log/routerView'),
      children: [
        {
          path: '/',
          name: '场地主分值记录列表',
          meta: {
            title: '场地主分值记录列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/credit/credit_log/index" */ 'page/credit/credit_log/index')
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/credit/' + route.path
    }
  }
}

export default router
