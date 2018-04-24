import auth from 'service/auth'

let router = {
  path: 'help',
  redirect: 'help/helps',
  name: '帮助',
  meta: {
    title: '帮助',
    permission: '',
  },
  component: () =>
    import(/* webpackChunkName: "page/help/helpView" */ 'page/help/helpView'),
    children: [
      {
        path: 'helps',
        name: '帮助管理',
        redirect: 'helps/index',
        meta: {
          title: '帮助管理',
          permission: '',
        },
        component: () =>
          import(/* webpackChunkName: "page/help/helps/routerView" */ 'page/help/helps/routerView'),
        children: [
          {
            path: 'index',
            name: '帮助操作',
            meta: {
              title: '帮助操作',
              permission: '',
            },
            component: () =>
              import(/* webpackChunkName: "page/help/helps/index" */ 'page/help/helps/index'),
          },
        ],
      },
    ],
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/help/' + route.path
    }
  }
}

export default router
