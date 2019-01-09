import auth from 'service/auth'
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
      meta: {
        title: '节目管理',
        permission: 'team.program'
      },
      component: () =>
        import(/* webpackChunkName: "page/team/program/routerView" */ 'page/team/program/routerView'),
      children: [
        {
          path: '/',
          meta: {
            title: '节目列表',
            permission: 'team.program.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/program/index" */ 'page/team/program/index')
        },
        {
          path: 'add',
          name: '新增节目',
          meta: {
            title: '新增节目',
            permission: 'team.program.create'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/program/save" */ 'page/team/program/save')
        },
        {
          path: 'edit/:uid',
          name: '修改节目',
          meta: {
            title: '修改节目',
            permission: 'team.program.update'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/program/save" */ 'page/team/program/save')
        }
      ]
    },
    {
      path: 'ratio',
      meta: {
        title: '比例配置',
        permission: 'team.ratio'
      },
      component: () =>
        import(/* webpackChunkName: "page/team/ratio/routerView" */ 'page/team/ratio/routerView'),
      children: [
        {
          path: '/',
          name: '比例配置列表',
          meta: {
            title: '比例配置列表',
            permission: 'team.ratio.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/ratio/index" */ 'page/team/ratio/index')
        },
        {
          path: 'edit/:uid',
          name: '修改比例',
          meta: {
            title: '修改比例',
            permission: 'team.ratio.update'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/ratio/save" */ 'page/team/ratio/save')
        }
      ]
    },
    {
      path: 'platform',
      meta: {
        title: '平台项目',
        permission: 'team.platform'
      },
      component: () =>
        import(/* webpackChunkName: "page/team/platform/routerView" */ 'page/team/platform/routerView'),
      children: [
        {
          path: '/',
          name: '平台项目列表',
          meta: {
            title: '平台项目列表',
            permission: 'team.platform.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/platform/index" */ 'page/team/platform/index')
        }
      ]
    },
    {
      path: 'detail',
      meta: {
        title: '平台明细',
        permission: 'team.detail'
      },
      component: () =>
        import(/* webpackChunkName: "page/team/detail/routerView" */ 'page/team/detail/routerView'),
      children: [
        {
          path: '/',
          name: '平台明细列表',
          meta: {
            title: '平台明细列表',
            permission: 'team.detail.read'
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
    if (auth.checkPathPermission(route)) {
      return '/team/' + route.path
    }
  }
}

export default router
