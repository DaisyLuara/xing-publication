import auth from 'service/auth'

let router = {
  path: 'home',
  redirect: 'home/item',
  name: '首页',
  meta: {
    title: '首页',
    permission: '',
  },
  component: () =>
    import(/* webpackChunkName: "page/home/homeView" */ 'page/home/homeView'),
  children: [
    {
      path: 'item',
      name: '首页管理',
      redirect: 'item/index',
      meta: {
        title: '首页管理',
      },
      component: () =>
        import(/* webpackChunkName: "page/home/item/routerView" */ 'page/home/item/routerView'),
      children: [
        {
          path: 'index',
          name: '首页详情',
          meta: {
            title: '首页详情',
          },
          component: () =>
            import(/* webpackChunkName: "page/home/item/index" */ 'page/home/item/index'),
        },
      ],
    },
  ],
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/home/' + route.path
  }
}

export default router
