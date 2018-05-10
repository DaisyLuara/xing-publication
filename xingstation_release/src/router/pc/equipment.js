import auth from 'service/auth'

let router = {
  path: 'equipment',
  redirect: 'equipment/item',
  name: '设备',
  meta: {
    title: '设备',
    //permission: 'equipment',
  },
  component: () =>
    import(/* webpackChunkName: "page/equipment/equipmentView" */ 'page/equipment/equipmentView'),
  children: [
    {
      path: 'item',
      name: '设备管理',
      redirect: 'item/index',
      meta: {
        title: '设备管理',
      },
      component: () =>
        import(/* webpackChunkName: "page/equipment/item/routerView" */ 'page/equipment/item/routerView'),
      children: [
        {
          path: 'index',
          name: '设备管理列表',
          meta: {
            title: '设备管理列表',
          },
          component: () =>
            import(/* webpackChunkName: "page/equipment/item/index" */ 'page/equipment/item/index'),
        },
        // {
        //   path: 'add',
        //   component: () =>
        //     import(/* webpackChunkName: "page/ad/item/itemSave" */ 'page/ad/item/itemSave'),
        //   name: '新增投放节目',
        //   meta: {
        //   },
        // },
        // {
        //   path: 'edit/:uid',
        //   component: () =>
        //     import(/* webpackChunkName: "page/ad/item/itemSave" */ 'page/ad/item/itemSave'),
        //   name: '修改投放节目',
        //   meta: {
        //   },
        // },
      ]
    },
  ],
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/equipment/' + route.path
  }
}

export default router
