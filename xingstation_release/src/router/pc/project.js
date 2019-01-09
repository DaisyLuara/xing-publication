import auth from 'service/auth'

let router = {
  path: 'project',
  redirect: 'project/item',
  name: '节目',
  meta: {
    title: '节目',
    permission: 'project'
  },
  component: () =>
    import(/* webpackChunkName: "page/project/projectView" */ 'page/project/projectView'),
  children: [
    {
      path: 'item',
      meta: {
        title: '节目投放',
        permission: 'project.item'
      },
      component: () =>
        import(/* webpackChunkName: "page/project/item/routerView" */ 'page/project/item/routerView'),
      children: [
        {
          path: '/',
          meta: {
            title: '节目投放详情列表',
            permission: 'project.item.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/item/index" */ 'page/project/item/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/project/item/itemSave" */ 'page/project/item/itemSave'),
          meta: {
            title: '新增投放节目',
            permission: 'project.item.create'
          }
        }
      ]
    },
    {
      path: 'schedule',
      meta: {
        title: '模板排期',
        permission: 'project.schedule'
      },
      component: () =>
        import(/* webpackChunkName: "page/project/schedule/routerView" */ 'page/project/schedule/routerView'),
      children: [
        {
          path: '/',
          name: '模板排期列表',
          meta: {
            title: '模板排期列表',
            permission: 'project.schedule.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/schedule/index" */ 'page/project/schedule/index')
        }
      ]
    },
    {
      path: 'list',
      meta: {
        title: '节目列表',
        permission: 'project.list'
      },
      component: () =>
        import(/* webpackChunkName: "page/project/list/routerView" */ 'page/project/list/routerView'),
      children: [
        {
          path: '/',
          name: '节目列表详情',
          meta: {
            title: '节目列表详情',
            permission: 'project.list.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/list/index" */ 'page/project/list/index')
        }
      ]
    },
    {
      path: 'rules',
      meta: {
        title: '优惠券规则',
        permission: 'project.rules'
      },
      component: () =>
        import(/* webpackChunkName: "page/project/rules/routerView" */ 'page/project/rules/routerView'),
      children: [
        {
          path: '/',
          name: '优惠券规则详情',
          meta: {
            title: '优惠券规则详情',
            permission: 'project.rules.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/rules/index" */ 'page/project/rules/index')
        },
        {
          path: 'add',
          name: '优惠券增加',
          meta: {
            title: '优惠券增加',
            permission: 'project.rules.create'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/rules/save" */ 'page/project/rules/save')
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/project/rules/save" */ 'page/project/rules/save'),
          name: '优惠券修改',
          meta: {
            title: '优惠券增加',
            permission: 'project.rules.update'
          }
        }
      ]
    },
    {
      path: 'strategy',
      meta: {
        title: '优惠券策略',
        permission: 'project.strategy'
      },
      component: () =>
        import(/* webpackChunkName: "page/project/strategy/routerView" */ 'page/project/strategy/routerView'),
      children: [
        {
          path: '/',
          name: '优惠券策略列表',
          meta: {
            title: '优惠券策略列表',
            permission: 'project.strategy.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/strategy/index" */ 'page/project/strategy/index')
        }
      ]
    },
    {
      path: 'coupon',
      meta: {
        title: '优惠券',
        permission: 'project.coupon'
      },
      component: () =>
        import(/* webpackChunkName: "page/project/coupon/routerView" */ 'page/project/coupon/routerView'),
      children: [
        {
          path: '/',
          name: '优惠券投放列表',
          meta: {
            title: '优惠券投放列表',
            permission: 'project.coupon.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/coupon/index" */ 'page/project/coupon/index')
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/project/' + route.path
    }
  }
}

export default router
