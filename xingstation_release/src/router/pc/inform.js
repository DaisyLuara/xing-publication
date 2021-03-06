import auth from 'service/auth'
import { URL } from '../../constant/Url'

let router = {
  path: 'inform',
  name: '通知',
  meta: {
    title: '通知',
    permission: '',
    url: URL.CDN_URL + 'middle_ground/img/notification-icon.png'
  },
  component: () =>
    import(/* webpackChunkName: "page/inform/informView" */ 'page/inform/informView'),
  children: [
    {
      path: 'list',
      meta: {
        title: '消息管理',
        permission: ''
      },
      component: () =>
        import(/* webpackChunkName: "page/inform/list/routerView" */ 'page/inform/list/routerView'),
      children: [
        {
          path: '/',
          name: '消息列表',
          meta: {
            title: '消息列表',
            permission: ''
          },
          component: () =>
            import(/* webpackChunkName: "page/inform/list/index" */ 'page/inform/list/index')
        }
      ]
    },
    {
      path: 'operate',
      meta: {
        title: '操作记录',
        permission: ''
      },
      component: () =>
        import(/* webpackChunkName: "page/inform/operate/routerView" */ 'page/inform/operate/routerView'),
      children: [
        {
          path: '/',
          name: '操作列表',
          meta: {
            title: '操作列表',
            permission: ''
          },
          component: () =>
            import(/* webpackChunkName: "page/inform/operate/index" */ 'page/inform/operate/index')
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/inform/' + route.path
    }
  }
}

export default router
