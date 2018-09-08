let router = {
  path: 'market',
  redirect: 'market/site',
  name: '场地',
  meta: {
    title: '场地',
    permission: ''
  },
  component: () =>
    import(/* webpackChunkName: "page/market/marketView" */ 'page/market/marketView'),
  children: [
    {
      path: 'site',
      name: '场地管理',
      redirect: 'site',
      meta: {
        title: '场地管理'
      },
      component: () =>
        import(/* webpackChunkName: "page/market/site/routerView" */ 'page/market/site/routerView'),
      children: [
        {
          path: '/',
          name: '场地列表',
          meta: {
            title: '场地列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/market/site/index" */ 'page/market/site/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/market/site/save" */ 'page/market/site/save'),
          name: '新增场地',
          meta: {}
        }
      ]
    },
    {
      path: 'point',
      name: '点位管理',
      redirect: 'point',
      meta: {
        title: '点位管理'
      },
      component: () =>
        import(/* webpackChunkName: "page/market/point/routerView" */ 'page/market/point/routerView'),
      children: [
        {
          path: '/',
          name: '点位列表',
          meta: {
            title: '点位列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/market/point/index" */ 'page/market/point/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/market/point/save" */ 'page/market/point/save'),
          name: '新增点位',
          meta: {}
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/market/' + route.path
  }
}

export default router
