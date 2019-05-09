import auth from 'service/auth'
import { URL } from '../../constant/Url'

let router = {
  path: 'resource',
  name: '资源',
  meta: {
    title: '资源',
    permission: 'resource',
    url: URL.CDN_URL + 'middle_ground/img/resource_icon.png'
  },
  component: () =>
    import(
      /* webpackChunkName: "page/resource/resourceView" */ 'page/resource/resourceView'
    ),
  children: [
    {
      path: 'picture',
      meta: {
        title: '图片管理',
        permission: 'resource.publication'
      },
      component: () =>
        import(
          /* webpackChunkName: "page/resource/picture/routerView" */ 'page/resource/picture/routerView'
        ),
      children: [
        {
          path: '/',
          name: '图片管理列表',
          meta: {
            title: '图片管理列表',
            permission: 'resource.publication.read'
          },
          component: () =>
            import(
              /* webpackChunkName: "page/resource/picture/index" */ 'page/resource/picture/index'
            )
        }
      ]
    },
    {
      path: 'activity',
      meta: {
        title: '活动审核',
        permission: 'resource.activity'
      },
      component: () =>
        import(
          /* webpackChunkName: "page/resource/activity/routerView" */ 'page/resource/activity/routerView'
        ),
      children: [
        {
          path: '/',
          name: '活动审核列表',
          meta: {
            title: '活动审核列表',
            permission: 'resource.activity.read'
          },
          component: () =>
            import(
              /* webpackChunkName: "page/resource/activity/index" */ 'page/resource/activity/index'
            )
        }
      ]
    },
    {
      path: 'tenant',
      meta: {
        title: '商户审核',
        permission: 'resource.company'
      },
      component: () =>
        import(
          /* webpackChunkName: "page/resource/tenant/routerView" */ 'page/resource/tenant/routerView'
        ),
      children: [
        {
          path: '/',
          name: '商户审核列表',
          meta: {
            title: '商户审核列表',
            permission: 'resource.company.read'
          },
          component: () =>
            import(
              /* webpackChunkName: "page/resource/tenant/index" */ 'page/resource/tenant/index'
            )
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/resource/' + route.path
    }
  }
}
export default router
