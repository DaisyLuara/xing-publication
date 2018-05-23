import auth from 'service/auth'

let router = {
  path: 'ad',
  redirect: 'ad/item',
  name: '广告',
  meta: {
    title: '广告',
    permission: 'ad',
  },
  component: () =>
    import(/* webpackChunkName: "page/ad/adView" */ 'page/ad/adView'),
  children: [
    {
      path: 'item',
      name: '广告投放',
      meta: {
        title: '广告投放',
      },
      component: () =>
        import(/* webpackChunkName: "page/ad/item/routerView" */ 'page/ad/item/routerView'),
      children: [
        {
          path: '/',
          name: '广告投放详情列表',
          meta: {
            title: '广告投放详情列表',
          },
          component: () =>
            import(/* webpackChunkName: "page/ad/item/index" */ 'page/ad/item/index'),
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/ad/item/adSave" */ 'page/ad/item/adSave'),
          name: '新增广告投放节目',
          meta: {
          },
        },
      ]
    },
  ],
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/ad/' + route.path
  }
}

export default router
