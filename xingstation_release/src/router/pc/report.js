import auth from 'service/auth'

let router = {
  path: 'report',
  redirect: 'report/detail',
  name: '数据',
  meta: {
    title: '数据',
    permission: ''
  },
  component: () =>
    import(/* webpackChunkName: "page/report/reportView" */ 'page/report/reportView'),
  children: [
    {
      path: 'detail',
      name: '数据总况',
      redirect: 'detail/index',
      meta: {
        title: '数据总况',
        permission: ''
      },
      component: () =>
        import(/* webpackChunkName: "page/report/detail/routerView" */ 'page/report/detail/routerView'),
      children: [
        {
          path: 'index',
          name: '数据详情',
          meta: {
            title: '数据详情',
            permission: ''
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
    return '/report/' + route.path
  }
}

export default router
