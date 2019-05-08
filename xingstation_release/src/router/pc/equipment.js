import auth from 'service/auth'
import { URL } from '../../constant/Url'

let router = {
  path: 'equipment',
  name: '设备',
  meta: {
    title: '设备',
    permission: 'device',
    url: URL.CDN_URL + 'middle_ground/img/device-icon.png'

  },
  component: () =>
    import(/* webpackChunkName: "page/equipment/equipmentView" */ 'page/equipment/equipmentView'),
  children: [
    {
      path: 'item',
      meta: {
        title: '设备管理',
        permission: 'device.item'
      },
      component: () =>
        import(/* webpackChunkName: "page/equipment/item/routerView" */ 'page/equipment/item/routerView'),
      children: [
        {
          path: '/',
          name: '设备管理列表',
          meta: {
            title: '设备管理列表',
            permission: 'device.item.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/equipment/item/index" */ 'page/equipment/item/index')
        }
      ]
    },
    {
      path: 'map',
      meta: {
        title: '地图总览',
        permission: 'device.map'
      },
      component: () =>
        import(/* webpackChunkName: "page/equipment/map/routerView" */ 'page/equipment/map/routerView'),
      children: [
        {
          path: '/',
          name: '热力图',
          meta: {
            title: '热力图',
            permission: 'device.map.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/equipment/map/index" */ 'page/equipment/map/index')
        }
      ]
    },
    {
      path: 'feedback',
      meta: {
        title: '数据回流',
        permission: 'device.feedback'
      },
      component: () =>
        import(/* webpackChunkName: "page/equipment/feedback/routerView" */ 'page/equipment/feedback/routerView'),
      children: [
        {
          path: '/',
          name: '数据回流列表',
          meta: {
            title: '数据回流列表',
            permission: 'device.feedback.read'
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
    if (auth.checkPathPermission(route)) {
      return '/equipment/' + route.path
    }
  }
}

export default router
