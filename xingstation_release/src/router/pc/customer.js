import auth from 'service/auth'

let router = {
  path: 'customer',
  redirect: 'customer/customers',
  name: '客户',
  meta: {
    title: '客户',
    permission: 'customer',
  },
  component: () =>
    import(/* webpackChunkName: "page/customer/customerView" */ 'page/customer/customerView'),
  children: [
    {
      path: 'customers',
      name: '客户管理',
      redirect: 'customers/index',
      meta: {
        title: '客户管理',
      },
      component: () =>
        import(/* webpackChunkName: "page/customer/customers/routerView" */ 'page/customer/customers/routerView'),
      children: [
        {
          path: 'index',
          name: '客户管理列表',
          meta: {
            title: '客户管理列表',
          },
          component: () =>
            import(/* webpackChunkName: "page/customer/customers/index" */ 'page/customer/customers/index'),
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/customer/customers/customerSave" */ 'page/customer/customers/customerSave'),
          name: '新增客户',
          meta: {
          },
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/customer/customers/customerSave" */ 'page/customer/customers/customerSave'),
          name: '修改客户',
          meta: {
          },
        },
        {
          path: 'detail/:uid',
          component: () =>
            import(/* webpackChunkName: "page/customer/customers/customerDetail" */ 'page/customer/customers/customerDetail'),
          name: '客户详情',
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
    return '/customer/' + route.path
  }
}

export default router
