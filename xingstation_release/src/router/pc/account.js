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
      redirect: 'accoun',
      meta: {
        title: '账号管理',
      },
      component: () =>
        import(/* webpackChunkName: "page/account/account/routerView" */ 'page/account/account/routerView'),
      children: [
        {
          path: '/',
          name: '账号详情',
          meta: {
            title: '账号详情',
          },
          component: () =>
            import(/* webpackChunkName: "page/account/account/index" */ 'page/account/account/index'),
        },
      ],
    },
  ],
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/account/' + route.path
  }
}

export default router
