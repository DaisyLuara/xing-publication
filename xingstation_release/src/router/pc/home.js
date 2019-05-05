import auth from 'service/auth'
import { URL } from '../../constant/Url'
let router = {
  path: 'home',
  name: '首页',
  meta: {
    title: '首页',
    permission: 'home',
    url: URL.CDN_URL + 'middle_ground/img/home-icon.png'
  },
  component: () =>
    import(/* webpackChunkName: "page/home/homeView" */ 'page/home/homeView'),
  children: [
    {
      path: 'item',
      meta: {
        title: '首页管理',
        permission: 'home.item'
      },
      component: () =>
        import(
          /* webpackChunkName: "page/home/item/routerView" */ 'page/home/item/routerView'
        ),
      children: [
        {
          path: '/',
          name: '首页详情',
          meta: {
            title: '首页详情',
            permission: 'home.item.read'
          },
          component: () =>
            import(
              /* webpackChunkName: "page/home/item/index" */ 'page/home/item/index'
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
      return '/home/' + route.path
    }
  }
}

export default router
