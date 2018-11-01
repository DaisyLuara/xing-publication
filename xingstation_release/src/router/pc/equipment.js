import auth from 'service/auth'

let router = {
  path: 'equipment',
  redirect: 'equipment/item',
  name: '设备',
  meta: {
    title: '设备',
    permission: 'device'
  },
  component: () =>
    import(/* webpackChunkName: "page/equipment/equipmentView" */ 'page/equipment/equipmentView'),
  children: [
    {
      path: 'item',
      name: '设备管理',
      redirect: 'item',
      meta: {
        title: '设备管理'
      },
      component: () =>
        import(/* webpackChunkName: "page/equipment/item/routerView" */ 'page/equipment/item/routerView'),
      children: [
        {
          path: '/',
          name: '设备管理列表',
          meta: {
            title: '设备管理列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/equipment/item/index" */ 'page/equipment/item/index')
        }
      ]
    },
    {
      path: 'map',
      name: '地图总览',
      redirect: 'map',
      meta: {
        title: '地图总览'
      },
      component: () =>
        import(/* webpackChunkName: "page/equipment/map/routerView" */ 'page/equipment/map/routerView'),
      children: [
        {
          path: '/',
          name: '热力图',
          meta: {
            title: '热力图'
          },
          component: () =>
            import(/* webpackChunkName: "page/equipment/map/index" */ 'page/equipment/map/index')
        }
      ]
    },
    {
      path: 'feedback',
      name: '数据回流',
      redirect: 'list',
      meta: {
        title: '数据回流'
      },
      component: () =>
        import(/* webpackChunkName: "page/equipment/feedback/routerView" */ 'page/equipment/feedback/routerView'),
      children: [
        {
          path: '/',
          name: '数据回流列表',
          meta: {
            title: '数据回流列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/equipment/feedback/index" */ 'page/equipment/feedback/index')
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/equipment/' + route.path
  }
}

export default router
