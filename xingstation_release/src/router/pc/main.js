import auth from 'service/auth'

let router = {
  path: 'main',
  redirect: 'main/data',
  name: '首页',
  meta: {
    title: '首页',
    permission: '',
  },
  component: () =>
    import(/* webpackChunkName: "page/main/mainView" */ 'page/main/mainView'),
    children: [
      {
        path: 'data',
        name: '首页管理',
        redirect: 'data/index',
        meta: {
          title: '首页管理',
          permission: '',
        },
        component: () =>
          import(/* webpackChunkName: "page/main/data/routerView" */ 'page/main/data/routerView'),
        children: [
          {
            path: 'index',
            name: '首页数据',
            meta: {
              title: '首页数据',
              permission: '',
            },
            component: () =>
              import(/* webpackChunkName: "page/main/data/index" */ 'page/main/data/index'),
          },
        ],
      },
    ],
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/main/' + route.path
    }
  }
}

export default router
