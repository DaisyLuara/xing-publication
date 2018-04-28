import auth from 'service/auth'

export default {
  path: 'system',
  name: '权限',
  component: () =>
    import(/* webpackChunkName: "page/system/systemView" */ 'page/system/systemView'),
  meta: {
    title: '权限',
    permission: 'system',
  },
  redirect: 'system/user',
  children: [
    {
      // 用户主页，也是用户列表页
      path: 'user',
      component: () =>
        import(/* webpackChunkName: "page/system/user/userView" */ 'page/system/user/userView'),
      meta: {
        title: '用户管理',
        permission: 'system.user',
      },
      children: [
        {
          path: '/',
          component: () =>
            import(/* webpackChunkName: "page/system/user/userList" */ 'page/system/user/userList'),
          name: '用户管理',
          meta: {
            permission: 'system.user.view',
          },
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/system/user/userSave" */ 'page/system/user/userSave'),
          name: '新增用户',
          meta: {
            permission: 'system.user.edit',
          },
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/system/user/userSave" */ 'page/system/user/userSave'),
          name: '修改',
          meta: {
            permission: 'system.user.edit',
          },
        },
      ],
    },
    {
      // 角色管理、包含权限管理路由
      path: 'role',
      component: () =>
        import(/* webpackChunkName: "page/system/role/roleView" */ 'page/system/role/roleView'),
      meta: {
        title: '角色管理',
        permission: 'system.role',
      },
      children: [
        {
          path: '/',
          name: '角色管理',
          component: () =>
            import(/* webpackChunkName: "page/system/role/roleList" */ 'page/system/role/roleList'),
          meta: {
            // permission: 'system.role.view'
          },
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/system/role/roleSave" */ 'page/system/role/roleSave'),
        },
        {
          path: 'edit/:rid',
          component: () =>
            import(/* webpackChunkName: "page/system/role/roleSave" */ 'page/system/role/roleSave'),
        },
      ],
    },
  ],
}
