import auth from 'service/auth'

let router = {
  path: 'contract',
  redirect: 'contract/list',
  name: '合约',
  meta: {
    title: '合约',
    permission: 'contract',
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
      },
      component: () =>
        import(/* webpackChunkName: "page/contract/list/routerView" */ 'page/contract/list/routerView'),
      children: [
        {
          path: 'index',
          name: '合约管理列表',
          meta: {
            title: '合约管理列表',
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
          },
        },
      ],
    },
  ],
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/contract/' + route.path
  }
}

export default router
