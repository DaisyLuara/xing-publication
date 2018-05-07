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
      name: '节目管理',
      redirect: 'item/index',
      meta: {
        title: '节目管理',
      },
      component: () =>
        import(/* webpackChunkName: "page/project/item/routerView" */ 'page/project/item/routerView'),
      children: [
        {
          path: 'index',
          name: '节目详情列表',
          meta: {
            title: '节目详情列表',
          },
          component: () =>
            import(/* webpackChunkName: "page/project/item/index" */ 'page/project/item/index'),
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/project/item/itemSave" */ 'page/project/item/itemSave'),
          name: '新增节目',
          meta: {
          },
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/project/item/itemSave" */ 'page/project/item/itemSave'),
          name: '修改节目',
          meta: {
          },
        },
        {
          path: 'data',
          component: () =>
            import(/* webpackChunkName: "page/project/item/itemData" */ 'page/project/item/itemData'),
          name: '节目数据',
          meta: {
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
