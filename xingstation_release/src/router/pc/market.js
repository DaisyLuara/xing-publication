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
      meta: {
        title: '场地管理'
      },
      component: () =>
        import(/* webpackChunkName: "page/market/site/routerView" */ 'page/market/site/routerView'),
      children: [
        {
          path: '/',
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
          meta: {
            title: '新增场地'
          }
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/market/site/save" */ 'page/market/site/save'),
          meta: {
            title: '场地修改'
          }
        }
      ]
    },
    {
      path: 'point',
      meta: {
        title: '点位管理'
      },
      component: () =>
        import(/* webpackChunkName: "page/market/point/routerView" */ 'page/market/point/routerView'),
      children: [
        {
          path: '/',
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
          meta: {
            title: '新增点位'
          }
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/market/point/save" */ 'page/market/point/save'),
          meta: {
            title: '点位修改'
          }
        }
      ]
    },
    {
      path: 'business',
      meta: {
        title: '商户管理'
      },
      component: () =>
        import(/* webpackChunkName: "page/market/business/routerView" */ 'page/market/business/routerView'),
      children: [
        {
          path: '/',
          meta: {
            title: '商户管理列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/market/business/index" */ 'page/market/business/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/market/business/save" */ 'page/market/business/save'),
          meta: {
            title: '新增商户'
          }
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/market/business/save" */ 'page/market/business/save'),
          meta: {
            title: '商户修改'
          }
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
