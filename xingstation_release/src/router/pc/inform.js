import auth from 'service/auth'

let router = {
  path: 'inform',
  redirect: 'inform/list',
  name: '通知',
  meta: {
    title: '通知',
    permission: '',
  },
  component: () =>
    import(/* webpackChunkName: "page/inform/informView" */ 'page/inform/informView'),
  children: [
    {
      path: 'list',
      name: '消息管理',
      redirect: 'list/index',
      meta: {
        title: '消息管理',
        permission: '',
      },
      component: () =>
        import(/* webpackChunkName: "page/inform/list/routerView" */ 'page/inform/list/routerView'),
      children: [
        {
          path: 'index',
          name: '消息列表',
          meta: {
            title: '消息列表',
            permission: '',
          },
          component: () =>
            import(/* webpackChunkName: "page/inform/list/index" */ 'page/inform/list/index'),
        },
      ],
    },
    {
      path: 'operate',
      name: '操作管理',
      redirect: 'operate/index',
      meta: {
        title: '操作管理',
        permission: '',
      },
      component: () =>
        import(/* webpackChunkName: "page/inform/operate/routerView" */ 'page/inform/operate/routerView'),
      children: [
        {
          path: 'index',
          name: '操作列表',
          meta: {
            title: '操作列表',
            permission: '',
          },
          component: () =>
            import(/* webpackChunkName: "page/inform/operate/index" */ 'page/inform/operate/index'),
        },
      ],
    },
  ],
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/inform/' + route.path
  }
}

export default router
