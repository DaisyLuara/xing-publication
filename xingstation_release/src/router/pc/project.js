import auth from 'service/auth'

let router = {
  path: 'project',
  redirect: 'project/item',
  name: '投放',
  meta: {
    title: '投放',
    permission: 'project',
  },
  component: () =>
    import(/* webpackChunkName: "page/project/projectView" */ 'page/project/projectView'),
  children: [
    {
      path: 'item',
      name: '投放管理',
      redirect: 'item/index',
      meta: {
        title: '投放管理',
      },
      component: () =>
        import(/* webpackChunkName: "page/project/item/routerView" */ 'page/project/item/routerView'),
      children: [
        {
          path: 'index',
          name: '投放详情列表',
          meta: {
            title: '投放详情列表',
          },
          component: () =>
            import(/* webpackChunkName: "page/project/item/index" */ 'page/project/item/index'),
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/project/item/itemSave" */ 'page/project/item/itemSave'),
          name: '新增投放',
          meta: {
          },
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/project/item/itemSave" */ 'page/project/item/itemSave'),
          name: '修改投放',
          meta: {
          },
        },
        {
          path: 'data',
          component: () =>
            import(/* webpackChunkName: "page/project/item/itemData" */ 'page/project/item/itemData'),
          name: '投放数据',
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
