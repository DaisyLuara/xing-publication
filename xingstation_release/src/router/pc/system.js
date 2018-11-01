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
      },
      children: [
        {
          path: '/',
          component: () =>
            import(/* webpackChunkName: "page/system/user/userList" */ 'page/system/user/userList'),
          name: '用户管理',
          meta: {
          },
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/system/user/userSave" */ 'page/system/user/userSave'),
          name: '新增用户',
          meta: {
          },
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/system/user/userSave" */ 'page/system/user/userSave'),
          name: '修改',
          meta: {
          },
        },
      ],
    },
    
  ],
}
