import auth from 'service/auth'

let router = {
  path: 'contract',
  redirect: 'contract/list',
  name: '合约',
  meta: {
    title: '合约',
    permission: '',
  },
  component: () =>
    import(/* webpackChunkName: "page/contract/contractView" */ 'page/contract/contractView'),
  children: [
    {
      path: 'list',
      name: '合约管理',
      redirect: 'list/index',
      meta: {
        title: '合约管理',
        permission: '',
      },
      component: () =>
        import(/* webpackChunkName: "page/contract/list/routerView" */ 'page/contract/list/routerView'),
      children: [
        {
          path: 'index',
          name: '合约管理列表',
          meta: {
            title: '合约管理列表',
            permission: '',
          },
          component: () =>
            import(/* webpackChunkName: "page/contract/list/index" */ 'page/contract/list/index'),
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/contract/list/contractSave" */ 'page/contract/list/contractSave'),
          name: '新增合约',
          meta: {
            // permission: 'contract.list.edit',
          },
        },
        // {
        //   path: 'edit/:uid',
        //   component: () =>
        //     import(/* webpackChunkName: "page/contract/list/clientSave" */ 'page/contract/list/clientSave'),
        //   name: '修改合约',
        //   meta: {
        //     // permission: 'contract.list.edit',
        //   },
        // },
      ],
    },
  ],
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/contract/' + route.path
    }
  }
}

export default router
