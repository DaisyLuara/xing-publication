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
      path: 'ratio',
      name: '比例配置',
      redirect: 'ratio',
      meta: {
        title: '比例配置'
      },
      component: () =>
        import(/* webpackChunkName: "page/team/ratio/routerView" */ 'page/team/ratio/routerView'),
      children: [
        {
          path: '/',
          name: '比例配置列表',
          meta: {
            title: '比例配置列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/ratio/index" */ 'page/team/ratio/index')
        },
        {
          path: 'edit/:uid',
          name: '修改比例',
          meta: {
            title: '修改比例',
            permission: ''
          },
          component: () =>
            import(/* webpackChunkName: "page/team/ratio/save" */ 'page/team/ratio/save')
        }
      ]
    },
    {
      path: 'duty',
      name: '重大责任',
      redirect: 'duty',
      meta: {
        title: '重大责任'
      },
      component: () =>
        import(/* webpackChunkName: "page/team/duty/routerView" */ 'page/team/duty/routerView'),
      children: [
        {
          path: '/',
          name: '重大责任列表',
          meta: {
            title: '重大责任列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/duty/index" */ 'page/team/duty/index')
        },
        {
          path: 'add',
          name: '新增责任',
          meta: {
            title: '新增责任',
            permission: ''
          },
          component: () =>
            import(/* webpackChunkName: "page/team/duty/save" */ 'page/team/duty/save')
        },
        {
          path: 'edit/:uid',
          name: '修改责任',
          meta: {
            title: '修改责任',
            permission: ''
          },
          component: () =>
            import(/* webpackChunkName: "page/team/duty/save" */ 'page/team/duty/save')
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
