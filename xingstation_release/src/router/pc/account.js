import auth from 'service/auth'

let router = {
  path: 'account',
  name: '',
    redirect:'account/account',
  meta: {
    title: '',
    permission: ''
  },
  component: () =>
    import(/* webpackChunkName: "page/account/accountView" */ 'page/account/accountView'),
  children: [
    {
      path: 'account',
      meta: {
        title: '账号管理',
        permission: ''
      },
      component: () =>
        import(/* webpackChunkName: "page/account/account/routerView" */ 'page/account/account/routerView'),
      children: [
        {
          path: '/',
          name: '账号详情',
          meta: {
            title: '账号详情',
            permission: ''
          },
          component: () =>
            import(/* webpackChunkName: "page/account/account/index" */ 'page/account/account/index')
        },

      ]
    },
    {
      path: 'center',
      meta: {
        title: '个人中心',
        permission: ''
      },
      component: () =>
        import(/* webpackChunkName: "page/account/center/routerView" */ 'page/account/center/routerView'),
      children: [
        {
          path: '/',
          name: '个人中心列表',
          meta: {
            title: '个人中心列表',
            permission: ''
          },
          component: () =>
            import(/* webpackChunkName: "page/account/center/index" */ 'page/account/center/index')
        },
        {
          path: 'freeze',
          name: '冻结明细列表',
          meta: {
            title: '冻结明细列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/account/center/freeze" */ 'page/account/center/freeze')
        }
      ]
    },
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    // if (auth.checkPathPermission(route)) {
      return '/account/' + route.path
    // }
  }
}

export default router
