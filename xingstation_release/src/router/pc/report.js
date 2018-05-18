import auth from 'service/auth'

let router = {
  path: 'report',
  redirect: 'report/total',
  name: '报表',
  meta: {
    title: '报表',
    permission: ''
  },
  component: () =>
    import(/* webpackChunkName: "page/report/reportView" */ 'page/report/reportView'),
  children: [
    {
      path: 'total',
      name: '概览管理',
      redirect: 'total/index',
      meta: {
        title: '概览数据',
        permission: ''
      },
      component: () =>
        import(/* webpackChunkName: "page/report/total/routerView" */ 'page/report/total/routerView'),
      children: [
        {
          path: 'index',
          name: '概览详情',
          meta: {
            title: '概览详情',
            permission: ''
          },
          component: () =>
            import(/* webpackChunkName: "page/report/total/index" */ 'page/report/total/index')
        }
      ]
    },
    {
      path: 'project',
      name: '节目管理',
      redirect: 'project/index',
      meta: {
        title: '节目数据',
        permission: ''
      },
      component: () =>
        import(/* webpackChunkName: "page/report/projet/routerView" */ 'page/report/project/routerView'),
      children: [
        {
          path: 'index',
          name: '节目数据',
          meta: {
            title: '节目数据',
            permission: ''
          },
          component: () =>
            import(/* webpackChunkName: "page/report/project/index" */ 'page/report/project/index')
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
