import auth from 'service/auth'

let router = {
  path: 'setting',
  redirect: 'setting/list',
  name: '配置',
  meta: {
    title: '配置',
    permission: 'setting'
  },
  component: () =>
    import(/* webpackChunkName: "page/setting/settingView" */ 'page/setting/settingView'),
  children: [
    {
      path: 'list',
      name: '节目列表',
      redirect: 'list',
      meta: {
        title: '节目列表'
      },
      component: () =>
        import(/* webpackChunkName: "page/setting/list/routerView" */ 'page/setting/list/routerView'),
      children: [
        {
          path: '/',
          name: '节目列表详情',
          meta: {
            title: '节目列表详情'
          },
          component: () =>
            import(/* webpackChunkName: "page/setting/list/index" */ 'page/setting/list/index')
        }
      ]
    },
    {
      path: 'coupon',
      name: '优惠券列表',
      redirect: 'coupon',
      meta: {
        title: '优惠券列表'
      },
      component: () =>
        import(/* webpackChunkName: "page/setting/coupon/routerView" */ 'page/setting/coupon/routerView'),
      children: [
        {
          path: '/',
          name: '优惠券列表详情',
          meta: {
            title: '优惠券列表详情'
          },
          component: () =>
            import(/* webpackChunkName: "page/setting/coupon/index" */ 'page/setting/coupon/index')
        },
        {
          path: 'add',
          name: '优惠券增加',
          meta: {
            title: '优惠券增加'
          },
          component: () =>
            import(/* webpackChunkName: "page/setting/coupon/save" */ 'page/setting/coupon/save')
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/setting/coupon/save" */ 'page/setting/coupon/save'),
          name: '优惠券修改',
          meta: {
            title: '优惠券增加'
          }
        }
      ]
    },
    {
      path: 'strategy',
      name: '优惠券策略',
      redirect: 'strategy',
      meta: {
        title: '优惠券策略'
      },
      component: () =>
        import(/* webpackChunkName: "page/setting/strategy/routerView" */ 'page/setting/strategy/routerView'),
      children: [
        {
          path: '/',
          name: '优惠券策略列表',
          meta: {
            title: '优惠券策略列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/setting/strategy/index" */ 'page/setting/strategy/index')
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/setting/' + route.path
  }
}

export default router
