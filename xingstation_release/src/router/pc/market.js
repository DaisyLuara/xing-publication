import auth from 'service/auth'

let router = {
  path: 'market',
  redirect: 'market/site',
  name: '场地',
  meta: {
    title: '场地',
    permission: 'market'
  },
  component: () =>
    import(/* webpackChunkName: "page/market/marketView" */ 'page/market/marketView'),
  children: [
    {
      path: 'site',
      name: '场地管理',
      meta: {
        title: '场地管理',
        permission: 'market.site'
      },
      component: () =>
        import(/* webpackChunkName: "page/market/site/routerView" */ 'page/market/site/routerView'),
      children: [
        {
          path: '/',
          name: '场地列表',
          meta: {
            title: '场地列表',
            permission: 'market.site.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/market/site/index" */ 'page/market/site/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/market/site/save" */ 'page/market/site/save'),
          name: '新增场地',
          meta: {
            permission: 'market.site.create'
          }
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/market/site/save" */ 'page/market/site/save'),
          name: '修改',
          meta: {
            permission: 'market.site.update'
          }
        }
      ]
    },
    {
      path: 'point',
      name: '点位管理',
      meta: {
        title: '点位管理',
        permission: 'market.point'
      },
      component: () =>
        import(/* webpackChunkName: "page/market/point/routerView" */ 'page/market/point/routerView'),
      children: [
        {
          path: '/',
          name: '点位列表',
          meta: {
            title: '点位列表',
            permission: 'market.point.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/market/point/index" */ 'page/market/point/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/market/point/save" */ 'page/market/point/save'),
          name: '新增点位',
          meta: {
            permission: 'market.point.create'
          }
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/market/point/save" */ 'page/market/point/save'),
          name: '修改',
          meta: {
            permission: 'market.point.update'
          }
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/market/' + route.path
    }
  }
}

export default router
