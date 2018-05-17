import auth from 'service/auth'

let router = {
  path: 'report',
  redirect: 'report/total',
  name: '报表',
  meta: {
    title: '报表',
    permission: '',
  },
  component: () =>
    import(/* webpackChunkName: "page/report/reportView" */ 'page/report/reportView'),
  children: [
    {
      path: 'total',
      name: '总数管理',
      redirect: 'total/index',
      meta: {
        title: '总数管理',
        permission: '',
      },
      component: () =>
        import(/* webpackChunkName: "page/report/total/routerView" */ 'page/report/total/routerView'),
      children: [
        {
          path: 'index',
          name: '总数',
          meta: {
            title: '总数',
            permission: '',
          },
          component: () =>
            import(/* webpackChunkName: "page/report/total/index" */ 'page/report/total/index'),
        },
      ],
    },
  ],
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/report/' + route.path
  }
}

export default router
