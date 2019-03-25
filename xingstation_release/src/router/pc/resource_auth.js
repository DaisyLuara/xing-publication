import auth from 'service/auth'

let router = {
  path: 'resource_auth',
  name: '授权',
  meta: {
    title: '授权',
    permission: 'resource_auth'
  },
  component: () =>
    import(/* webpackChunkName: "page/resource_auth/resourceAuthView" */ 'page/resource_auth/resourceAuthView'),
  children: [
    {
      path: 'project_auth',
      meta: {
        title: '节目授权',
        permission: 'resource_auth.project_auth'
      },
      component: () =>
        import(/* webpackChunkName: "page/resource_auth/project_auth/routerView" */ 'page/resource_auth/project_auth/routerView'),
      children: [
        {
          path: '/',
          name: '节目授权列表',
          meta: {
            title: '节目授权列表',
            permission: 'resource_auth.project_auth.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/resource_auth/project_auth/index" */ 'page/resource_auth/project_auth/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/resource_auth/project_auth/Save" */ 'page/resource_auth/project_auth/Save'),
          name: '新增节目授权',
          meta: {
            permission: 'resource_auth.project_auth.create'
          }
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/resource_auth/project_auth/Save" */ 'page/resource_auth/project_auth/Save'),
          name: '编辑节目授权',
          meta: {
            permission: 'resource_auth.project_auth.update'
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
      return '/resource_auth/' + route.path
    }
  }
}

export default router
