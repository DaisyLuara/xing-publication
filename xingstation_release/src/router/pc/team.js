import auth from 'service/auth'

let router = {
  path: 'team',
  redirect: 'team/list',
  name: '团队',
  meta: {
    title: '团队',
    permission: '',
  },
  component: () =>
    import(/* webpackChunkName: "page/team/teamView" */ 'page/team/teamView'),
  children: [
    {
      path: 'list',
      name: '团队管理',
      redirect: 'list/index',
      meta: {
        title: '团队管理',
        permission: '',
      },
      component: () =>
        import(/* webpackChunkName: "page/team/list/routerView" */ 'page/team/list/routerView'),
      children: [
        {
          path: 'index',
          name: '团队列表',
          meta: {
            title: '团队列表',
            permission: '',
          },
          component: () =>
            import(/* webpackChunkName: "page/team/list/index" */ 'page/team/list/index'),
        },
      ],
    },
  ],
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/team/' + route.path
  }
}

export default router
