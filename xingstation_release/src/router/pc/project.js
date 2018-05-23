import auth from 'service/auth'

let router = {
  path: 'project',
  redirect: 'project/item',
  name: '节目',
  meta: {
    title: '节目',
    permission: 'project',
  },
  component: () =>
    import(/* webpackChunkName: "page/project/projectView" */ 'page/project/projectView'),
  children: [
    {
      path: 'item',
      name: '节目投放',
      redirect: 'item',
      meta: {
        title: '节目投放',
      },
      component: () =>
        import(/* webpackChunkName: "page/project/item/routerView" */ 'page/project/item/routerView'),
      children: [
        {
          path: '/',
          name: '节目投放详情列表',
          meta: {
            title: '节目投放详情列表',
          },
          component: () =>
            import(/* webpackChunkName: "page/project/item/index" */ 'page/project/item/index'),
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/project/item/itemSave" */ 'page/project/item/itemSave'),
          name: '新增投放节目',
          meta: {
          },
        },
      ],
    },
    {
      path: 'list',
      name: '节目列表',
      redirect: 'list',
      meta: {
        title: '节目列表',
      },
      component: () =>
        import(/* webpackChunkName: "page/project/list/routerView" */ 'page/project/list/routerView'),
      children: [
        {
          path: '/',
          name: '节目列表详情',
          meta: {
            title: '节目列表详情',
          },
          component: () =>
            import(/* webpackChunkName: "page/project/list/index" */ 'page/project/list/index'),
        },
      ],
    },
  ],
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/project/' + route.path
  }
}

export default router
