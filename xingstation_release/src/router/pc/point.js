import auth from 'service/auth'

let router = {
  path: 'point',
  redirect: 'point/item',
  name: '点位',
  meta: {
    title: '点位',
    permission: 'point',
  },
  component: () =>
    import(/* webpackChunkName: "page/point/pointView" */ 'page/point/pointView'),
  children: [
    {
      path: 'item',
      name: '点位管理',
      redirect: 'item/index',
      meta: {
        title: '点位管理',
      },
      component: () =>
        import(/* webpackChunkName: "page/point/item/routerView" */ 'page/point/item/routerView'),
      children: [
        {
          path: 'index',
          name: '点位详情列表',
          meta: {
            title: '点位详情列表',
          },
          component: () =>
            import(/* webpackChunkName: "page/point/item/index" */ 'page/point/item/index'),
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/point/item/itemSave" */ 'page/point/item/itemSave'),
          name: '新增点位',
          meta: {
          },
        },
        {
          path: 'data',
          component: () =>
            import(/* webpackChunkName: "page/point/item/data" */ 'page/point/item/data'),
          name: '点位数据',
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
    return '/point/' + route.path
  }
}

export default router