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
      redirect: 'item/index',
      meta: {
        title: '节目投放',
      },
      component: () =>
        import(/* webpackChunkName: "page/project/item/routerView" */ 'page/project/item/routerView'),
      children: [
        {
          path: 'index',
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
        {
          path: 'data',
          component: () =>
            import(/* webpackChunkName: "page/project/item/itemData" */ 'page/project/item/itemData'),
          name: '节目数据',
          meta: {
            title: '统计指标'
          },
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
