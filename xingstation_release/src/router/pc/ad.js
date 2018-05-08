import auth from 'service/auth'

let router = {
  path: 'ad',
  redirect: 'ad/item',
  name: '广告',
  meta: {
    title: '广告',
    // permission: 'ad',
  },
  component: () =>
    import(/* webpackChunkName: "page/ad/adView" */ 'page/ad/adView'),
  children: [
    {
      path: 'item',
      name: '广告投放',
      redirect: 'item/index',
      meta: {
        title: '广告投放',
      },
      component: () =>
        import(/* webpackChunkName: "page/ad/item/routerView" */ 'page/ad/item/routerView'),
      children: [
        {
          path: 'index',
          name: '广告投放详情列表',
          meta: {
            title: '广告投放详情列表',
          },
          component: () =>
            import(/* webpackChunkName: "page/ad/item/index" */ 'page/ad/item/index'),
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
    return '/ad/' + route.path
  }
}

export default router
