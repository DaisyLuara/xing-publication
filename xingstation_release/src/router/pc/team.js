import auth from 'service/auth'

let router = {
  path: 'team',
  redirect: 'team/projects',
  name: '团队',
  meta: {
    title: '团队',
    permission: '',
  },
  component: () =>
    import(/* webpackChunkName: "page/team/teamView" */ 'page/team/teamView'),
  children: [
    {
      path: 'projects',
      name: '项目管理',
      meta: {
        title: '项目管理',
        permission: '',
      },
      component: () =>
        import(/* webpackChunkName: "page/team/projects/routerView" */ 'page/team/projects/routerView'),
      children: [
        {
          path: '/',
          name: '项目列表',
          meta: {
            title: '项目列表',
            permission: '',
          },
          component: () =>
            import(/* webpackChunkName: "page/team/projects/index" */ 'page/team/projects/index'),
        },
        {
          path: 'add',
          name: '新建项目',
          meta: {
            title: '新建项目',
            permission: '',
          },
          component: () =>
            import(/* webpackChunkName: "page/team/projects/projectSave" */ 'page/team/projects/projectSave'),
        },
      ],
    },
    {
      path: 'list',
      name: '成员管理',
      meta: {
        title: '成员管理',
        permission: '',
      },
      component: () =>
        import(/* webpackChunkName: "page/team/list/routerView" */ 'page/team/list/routerView'),
      children: [
        {
          path: '/',
          name: '成员列表',
          meta: {
            title: '成员列表',
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
