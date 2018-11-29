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
          name: '新增节目',
          meta: {
            title: '新增节目',
            permission: ''
          },
          component: () =>
            import(/* webpackChunkName: "page/team/program/save" */ 'page/team/program/save')
        },
        {
          path: 'edit/:uid',
          name: '修改节目',
          meta: {
            title: '修改节目',
            permission: ''
          },
          component: () =>
            import(/* webpackChunkName: "page/team/program/save" */ 'page/team/program/save')
        }
      ]
    },
    {
      path: 'platform',
      name: '平台项目',
      redirect: 'platform',
      meta: {
        title: '平台项目'
      },
      component: () =>
        import(/* webpackChunkName: "page/team/platform/routerView" */ 'page/team/platform/routerView'),
      children: [
        {
          path: '/',
          name: '平台项目列表',
          meta: {
            title: '平台项目列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/platform/index" */ 'page/team/platform/index')
        }
      ]
    },
    {
      path: 'detail',
      name: '平台明细',
      redirect: 'detail',
      meta: {
        title: '平台明细'
      },
      component: () =>
        import(/* webpackChunkName: "page/team/detail/routerView" */ 'page/team/detail/routerView'),
      children: [
        {
          path: '/',
          name: '平台明细列表',
          meta: {
            title: '平台明细列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/detail/index" */ 'page/team/detail/index')
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/team/' + route.path
  }
}

export default router
