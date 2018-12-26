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
      name: '节目智造',
      redirect: 'program',
      meta: {
        title: '节目智造',
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
            permission: '',
            keepAlive: true
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
      name: '智造比例',
      redirect: 'ratio',
      meta: {
        title: '智造比例'
      },
      component: () =>
        import(/* webpackChunkName: "page/team/ratio/routerView" */ 'page/team/ratio/routerView'),
      children: [
        {
          path: '/',
          name: '智造比例列表',
          meta: {
            title: '智造比例列表'
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
    },
    {
      path: 'operation',
      name: '运营文档',
      redirect: 'operation',
      meta: {
        title: '运营文档'
      },
      component: () =>
        import(/* webpackChunkName: "page/team/operation/routerView" */ 'page/team/operation/routerView'),
      children: [
        {
          path: '/',
          name: '运营文档列表',
          meta: {
            title: '运营文档列表'
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
    return '/team/' + route.path
  }
}

export default router
