import auth from 'service/auth'

let router = {
  path: 'account',
  redirect: 'account/account',
  name: '',
  meta: {
    title: '',
    permission: '',
  },
  component: () =>
    import(/* webpackChunkName: "page/account/accountView" */ 'page/account/accountView'),
  children: [
    {
      path: 'account',
      name: '账号管理',
      redirect: 'account/index',
      meta: {
        title: '账号管理',
      },
      component: () =>
        import(/* webpackChunkName: "page/account/account/routerView" */ 'page/account/account/routerView'),
      children: [
        {
          path: 'index',
          name: '账号详情',
          meta: {
            title: '账号详情',
          },
          component: () =>
            import(/* webpackChunkName: "page/account/account/index" */ 'page/account/account/index'),
        },
      ],
    },
    // {
    //   path: 'operation',
    //   name: '操作日志',
    //   redirect: 'operation/index',
    //   meta: {
    //     title: '操作日志',
    //   },
    //   component: () =>
    //     import(/* webpackChunkName: "page/account/operation/routerView" */ 'page/account/operation/routerView'),
    //   children: [
    //     {
    //       path: 'index',
    //       name: '日志详情',
    //       meta: {
    //         title: '日志详情',
    //       },
    //       component: () =>
    //         import(/* webpackChunkName: "page/account/operation/index" */ 'page/account/operation/index'),
    //     },
    //   ],
    // },
  ],
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/account/' + route.path
  }
}

export default router
