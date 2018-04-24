import auth from 'service/auth'

let router = {
  path: 'program',
  redirect: 'program/item',
  name: '节目',
  meta: {
    title: '节目',
    permission: '',
  },
  component: () =>
    import(/* webpackChunkName: "page/program/programView" */ 'page/program/programView'),
  children: [
    {
      path: 'item',
      name: '节目管理',
      redirect: 'item/index',
      meta: {
        title: '节目管理',
        // permission: '',
      },
      component: () =>
        import(/* webpackChunkName: "page/program/item/routerView" */ 'page/program/item/routerView'),
      children: [
        {
          path: 'index',
          name: '节目详情列表',
          meta: {
            title: '节目详情列表',
            // permission: '',
          },
          component: () =>
            import(/* webpackChunkName: "page/program/item/index" */ 'page/program/item/index'),
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/program/item/itemSave" */ 'page/program/item/itemSave'),
          name: '新增节目',
          meta: {
            // permission: 'program.item.edit',
          },
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/program/item/itemSave" */ 'page/program/item/itemSave'),
          name: '修改节目',
          meta: {
            // permission: 'program.item.edit',
          },
        },
        {
          path: 'data/:uid',
          component: () =>
            import(/* webpackChunkName: "page/program/item/itemData" */ 'page/program/item/itemData'),
          name: '节目数据',
          meta: {
            // permission: 'program.item.edit',
          },
        },
      ],
    },
  ],
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/program/' + route.path
    }
  }
}

export default router
