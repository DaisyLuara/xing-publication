import auth from 'service/auth'

let router = {
  path: 'company',
  redirect: 'company/customers',
  name: '客户',
  meta: {
    title: '客户',
    permission: 'company',
  },
  component: () =>
    import(/* webpackChunkName: "page/company/companyView" */ 'page/company/companyView'),
  children: [
    {
      path: 'customers',
      name: '客户管理',
      redirect: 'customers/index',
      meta: {
        title: '客户管理',
      },
      component: () =>
        import(/* webpackChunkName: "page/company/customers/routerView" */ 'page/company/customers/routerView'),
      children: [
        {
          path: 'index',
          name: '客户管理列表',
          meta: {
            title: '客户管理列表',
          },
          component: () =>
            import(/* webpackChunkName: "page/company/customers/index" */ 'page/company/customers/index'),
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/company/customers/customerSave" */ 'page/company/customers/customerSave'),
          name: '新增客户',
          meta: {
          },
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/company/customers/customerSave" */ 'page/company/customers/customerSave'),
          name: '修改客户',
          meta: {
          },
        },
        {
          path: 'contacts',
          component: () =>
            import(/* webpackChunkName: "page/company/customers/contacts/contactList" */ 'page/company/customers/contacts/contactList'),
          name: '联系人详情列表',
          meta: {
            title: '联系人详情列表'
          },
        },
        {
          path: 'contacts/add',
          component: () =>
            import(/* webpackChunkName: "page/company/customers/contacts/contactSave" */ 'page/company/customers/contacts/contactSave'),
          name: '新增联系人',
          meta: {
          },
        },
        {
          path: 'contacts/edit',
          component: () =>
            import(/* webpackChunkName: "page/company/customers/contacts/contactSave" */ 'page/company/customers/contacts/contactSave'),
          name: '修改联系人',
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
    return '/company/' + route.path
  }
}

export default router
