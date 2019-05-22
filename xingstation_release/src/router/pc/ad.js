import auth from 'service/auth'
import { URL } from '../../constant/Url'

let router = {
  path: 'ad',
  name: '广告',
  meta: {
    title: '广告',
    permission: 'ad',
    url: URL.CDN_URL + 'middle_ground/img/advertisement-icon.png'

  },
  component: () =>
    import(/* webpackChunkName: "page/ad/adView" */ 'page/ad/adView'),
  children: [
    {
      path: 'item',
      meta: {
        title: '广告投放',
        permission: 'ad.item'
      },
      component: () =>
        import(/* webpackChunkName: "page/ad/item/routerView" */ 'page/ad/item/routerView'),
      children: [
        {
          path: '/',
          name: '广告投放详情列表',
          meta: {
            title: '广告投放详情列表',
            permission: 'ad.item.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/ad/item/index" */ 'page/ad/item/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/ad/item/adSave" */ 'page/ad/item/adSave'),
          name: '新增广告投放节目',
          meta: {
            permission: 'ad.item.create'
          }
        }
      ]
    },
    {
      path: 'url',
      meta: {
        title: '短链接',
        permission: 'ad.url'
      },
      component: () =>
        import(/* webpackChunkName: "page/ad/url/routerView" */ 'page/ad/url/routerView'),
      children: [
        {
          path: '/',
          name: '短链接列表',
          meta: {
            title: '短链接列表',
            permission: 'ad.url.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/ad/url/index" */ 'page/ad/url/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/ad/url/add" */ 'page/ad/url/add'),
          name: '短链接增加',
          meta: {
            permission: 'ad.url.create'
          }
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/ad/' + route.path
    }
  }
}

export default router
