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
            title: '节目智造列表',
            permission: 'team.program.read',
            keepAlive: true
          },
          component: () =>
            import(/* webpackChunkName: "page/team/program/index" */ 'page/team/program/index')
        },
        {
          path: 'add',
          meta: {
            title: '新增节目',
            permission: 'team.program.create'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/program/save" */ 'page/team/program/save')
        },
        {
          path: 'edit/:uid',
          meta: {
            title: '修改节目',
            permission: 'team.program.update'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/program/save" */ 'page/team/program/save')
        },
        {
          path: 'detail/:uid',
          meta: {
            title: '制造详情',
          },
          component: () =>
            import(/* webpackChunkName: "page/team/program/detail" */ 'page/team/program/detail')
        }
      ]
    },
    {
      path: 'ratio',
      meta: {
        title: '智造比例',
        permission: 'team.ratio'
      },
      component: () =>
        import(/* webpackChunkName: "page/team/ratio/routerView" */ 'page/team/ratio/routerView'),
      children: [
        {
          path: '/',
          meta: {
            title: '智造比例列表',
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
      path: 'duty',
      meta: {
        title: '重大责任',
        permission: 'team.duty'
      },
      component: () =>
        import(/* webpackChunkName: "page/team/duty/routerView" */ 'page/team/duty/routerView'),
      children: [
        {
          path: '/',
          name: '重大责任列表',
          meta: {
            title: '重大责任列表',
            permission: 'team.duty.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/duty/index" */ 'page/team/duty/index')
        },
        {
          path: 'add',
          meta: {
            title: '新增责任',
            permission: 'team.duty.create'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/duty/save" */ 'page/team/duty/save')
        },
        {
          path: 'edit/:uid',
          meta: {
            title: '修改责任',
            permission: 'team.duty.update'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/duty/save" */ 'page/team/duty/save')
        }
      ]
    },
    {
      path: 'operation',
      meta: {
        title: '运营文档',
        permission: 'team.operation'
      },
      component: () =>
        import(/* webpackChunkName: "page/team/operation/routerView" */ 'page/team/operation/routerView'),
      children: [
        {
          path: '/',
          name: '运营文档列表',
          meta: {
            title: '平台明细列表',
            permission: 'team.operation.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/team/operation/index" */ 'page/team/operation/index')
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
