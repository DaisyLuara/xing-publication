import auth from 'service/auth'
let router = {
  path: 'system',
  name: '权限',
  component: () =>
    import(/* webpackChunkName: "page/system/systemView" */ 'page/system/systemView'),
  meta: {
    title: '权限',
    permission: 'system'
  },
  // redirect: 'system/user',
  children: [
    {
      // 用户主页，也是用户列表页
      path: 'user',
      component: () =>
        import(/* webpackChunkName: "page/system/user/userView" */ 'page/system/user/userView'),
      meta: {
        title: '用户管理',
        permission: 'system.user'
      },
      children: [
        {
          path: '/',
          component: () =>
            import(/* webpackChunkName: "page/system/user/userList" */ 'page/system/user/userList'),
          name: '用户管理',
          meta: {
            permission: 'system.user.read'
          }
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/system/user/userSave" */ 'page/system/user/userSave'),
          name: '新增用户',
          meta: {
            permission: 'system.user.create'
          }
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/system/user/userSave" */ 'page/system/user/userSave'),
          name: '修改用户',
          meta: {
            permission: 'system.user.update'
          }
        }
      ]
    },
    {
      // 角色主页，也是角色列表页
      path: 'role',
      component: () =>
        import(/* webpackChunkName: "page/system/role/roleView" */ 'page/system/role/roleView'),
      meta: {
        title: '角色管理',
        permission: 'system.role'
      },
      children: [
        {
          path: '/',
          component: () =>
            import(/* webpackChunkName: "page/system/role/roleList" */ 'page/system/role/roleList'),
          name: '角色管理',
          meta: {
            permission: 'system.role.read'
          }
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/system/role/roleSave" */ 'page/system/role/roleSave'),
          name: '新增角色',
          meta: {
            permission: 'system.role.create'
          }
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/system/role/roleSave" */ 'page/system/role/roleSave'),
          name: '修改角色',
          meta: {
            permission: 'system.role.update'
          }
        }
      ]
    },
    {
      // 角色主页，也是角色列表页
      path: 'permission',
      component: () =>
        import(/* webpackChunkName: "page/system/perms/permsView" */ 'page/system/perms/permsView'),
      meta: {
        title: '权限管理',
        permission: 'system.permission'
      },
      children: [
        {
          path: '/',
          component: () =>
            import(/* webpackChunkName: "page/system/perms/permsList" */ 'page/system/perms/permsList'),
          name: '权限管理',
          meta: {
            permission: 'system.permission.read'
          }
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/system/' + route.path
    }
  }
}
export default router
