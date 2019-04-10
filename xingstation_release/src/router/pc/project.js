import auth from 'service/auth'

let router = {
  path: 'project',
  redirect: 'project/item',
  name: '节目',
  meta: {
    title: '节目',
    permission: 'project'
  },
  component: () =>
    import(/* webpackChunkName: "page/project/projectView" */ 'page/project/projectView'),
  children: [
    {
      path: 'item',
      meta: {
        title: '节目投放',
        permission: 'project.item'
      },
      component: () =>
        import(/* webpackChunkName: "page/project/item/routerView" */ 'page/project/item/routerView'),
      children: [
        {
          path: '/',
          meta: {
            title: '节目投放详情列表',
            permission: 'project.item.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/item/index" */ 'page/project/item/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/project/item/itemSave" */ 'page/project/item/itemSave'),
          meta: {
            title: '新增投放节目',
            permission: 'project.item.create'
          }
        }
      ]
    },
    {
      path: 'schedule',
      meta: {
        title: '模板排期',
        permission: 'project.schedule'
      },
      component: () =>
        import(/* webpackChunkName: "page/project/schedule/routerView" */ 'page/project/schedule/routerView'),
      children: [
        {
          path: '/',
          name: '模板排期列表',
          meta: {
            title: '模板排期列表',
            permission: 'project.schedule.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/schedule/index" */ 'page/project/schedule/index')
        }
      ]
    },
    {
      path: 'list',
      meta: {
        title: '节目列表',
        permission: 'project.list'
      },
      component: () =>
        import(/* webpackChunkName: "page/project/list/routerView" */ 'page/project/list/routerView'),
      children: [
        {
          path: '/',
          name: '节目列表详情',
          meta: {
            title: '节目列表详情',
            permission: 'project.list.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/list/index" */ 'page/project/list/index')
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/project/' + route.path
    }
  }
}

export default router
