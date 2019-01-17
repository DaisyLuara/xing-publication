import auth from 'service/auth'

let router = {
  path: 'report',
  redirect: 'report/detail',
  name: '数据',
  meta: {
    title: '数据',
    permission: 'report'
  },
  component: () =>
    import(/* webpackChunkName: "page/report/reportView" */ 'page/report/reportView'),
  children: [
    {
      path: 'detail',
      meta: {
        title: '详细数据',
        permission: 'report.detail'
      },
      component: () =>
        import(/* webpackChunkName: "page/report/detail/routerView" */ 'page/report/detail/routerView'),
      children: [
        {
          path: '/',
          name: '详细数据展示',
          meta: {
            title: '详细数据展示',
            permission: 'report.detail.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/report/detail/index" */ 'page/report/detail/index')
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/report/' + route.path
    }
  }
}

export default router
