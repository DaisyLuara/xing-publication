import auth from 'service/auth'

let router = {
  path: 'account',
  name: '',
  meta: {
    title: '',
    permission: 'account'
  },
  component: () =>
    import(/* webpackChunkName: "page/account/accountView" */ 'page/account/accountView'),
  children: [
    {
      path: 'account',
      name: '账号管理',
      meta: {
        title: '账号管理',
        permission: 'account.account'
      },
      component: () =>
        import(/* webpackChunkName: "page/account/account/routerView" */ 'page/account/account/routerView'),
      children: [
        {
          path: '/',
          name: '账号详情',
          meta: {
            title: '账号详情',
            permission: 'account.account.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/account/account/index" */ 'page/account/account/index')
        }
      ]
    },
    {
      path: 'center',
      name: '个人中心',
      redirect: 'center',
      meta: {
        title: '个人中心',
        permission: 'account.center'
      },
      component: () =>
        import(/* webpackChunkName: "page/account/center/routerView" */ 'page/account/center/routerView'),
      children: [
        {
          path: '/',
          name: '个人中心列表',
          meta: {
            title: '个人中心列表',
            permission: 'account.center.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/account/center/index" */ 'page/account/center/index')
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/account/' + route.path
    }
  }
}

export default router
