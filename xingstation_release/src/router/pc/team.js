let router = {
  path: 'team',
  redirect: 'team/program',
  name: '团队',
  meta: {
    title: '团队',
    permission: 'team'
  },
  component: () =>
    import(/* webpackChunkName: "page/team/teamView" */ 'page/team/teamView'),
  children: [
    {
      path: 'program',
      name: '节目管理',
      redirect: 'program',
      meta: {
        title: '节目管理',
        permission: ''
      },
      component: () =>
        import(/* webpackChunkName: "page/team/program/routerView" */ 'page/team/program/routerView'),
      children: [
        {
          path: '/',
          name: '节目列表',
          meta: {
            title: '节目列表',
            permission: ''
          },
          component: () =>
            import(/* webpackChunkName: "page/team/program/index" */ 'page/team/program/index')
        },
        {
          path: 'add',
          name: '新建节目',
          meta: {
            title: '新建节目',
            permission: ''
          },
          component: () =>
            import(/* webpackChunkName: "page/team/program/save" */ 'page/team/program/save')
        },
      ]
    }
    // {
    //   path: 'list',
    //   name: '成员管理',
    //   redirect: 'list/index',
    //   meta: {
    //     title: '成员管理',
    //     permission: ''
    //   },
    //   component: () =>
    //     import(/* webpackChunkName: "page/team/list/routerView" */ 'page/team/list/routerView'),
    //   children: [
    //     {
    //       path: 'index',
    //       name: '成员列表',
    //       meta: {
    //         title: '成员列表',
    //         permission: ''
    //       },
    //       component: () =>
    //         import(/* webpackChunkName: "page/team/list/index" */ 'page/team/list/index')
    //     }
    //   ]
    // }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/team/' + route.path
  }
}

export default router
