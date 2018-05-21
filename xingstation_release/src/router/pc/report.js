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
      name: '详细数据',
      redirect: 'detail/index',
      meta: {
        title: '详细数据',
        permission: ''
      },
      component: () =>
        import(/* webpackChunkName: "page/report/detail/routerView" */ 'page/report/detail/routerView'),
      children: [
        {
          path: 'index',
          name: '详细数据展示',
          meta: {
            title: '详细数据展示',
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
