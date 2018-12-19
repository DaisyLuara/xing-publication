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
      name: '节目投放',
      redirect: 'item',
      meta: {
        title: '节目投放'
      },
      component: () =>
        import(/* webpackChunkName: "page/project/item/routerView" */ 'page/project/item/routerView'),
      children: [
        {
          path: '/',
          name: '节目投放详情列表',
          meta: {
            title: '节目投放详情列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/item/index" */ 'page/project/item/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/project/item/itemSave" */ 'page/project/item/itemSave'),
          name: '新增投放节目',
          meta: {}
        }
      ]
    },
    {
      path: 'schedule',
      name: '模板排期',
      redirect: 'schedule',
      meta: {
        title: '模板排期'
      },
      component: () =>
        import(/* webpackChunkName: "page/project/schedule/routerView" */ 'page/project/schedule/routerView'),
      children: [
        {
          path: '/',
          name: '模板排期列表',
          meta: {
            title: '模板排期列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/schedule/index" */ 'page/project/schedule/index')
        }
      ]
    },
    {
      path: 'list',
      name: '节目列表',
      redirect: 'list',
      meta: {
        title: '节目列表'
      },
      component: () =>
        import(/* webpackChunkName: "page/project/list/routerView" */ 'page/project/list/routerView'),
      children: [
        {
          path: '/',
          name: '节目列表详情',
          meta: {
            title: '节目列表详情'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/list/index" */ 'page/project/list/index')
        }
      ]
    },
    {
      path: 'rules',
      name: '优惠券规则',
      redirect: 'rules',
      meta: {
        title: '优惠券规则'
      },
      component: () =>
        import(/* webpackChunkName: "page/project/rules/routerView" */ 'page/project/rules/routerView'),
      children: [
        {
          path: '/',
          name: '优惠券规则详情',
          meta: {
            title: '优惠券规则详情'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/rules/index" */ 'page/project/rules/index')
        },
        {
          path: 'add',
          name: '优惠券增加',
          meta: {
            title: '优惠券增加'
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
        import(/* webpackChunkName: "page/project/strategy/routerView" */ 'page/project/strategy/routerView'),
      children: [
        {
          path: '/',
          name: '优惠券策略列表',
          meta: {
            title: '优惠券策略列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/strategy/index" */ 'page/project/strategy/index')
        }
      ]
    },
    {
      path: 'coupon',
      name: '优惠券',
      redirect: 'coupon',
      meta: {
        title: '优惠券'
      },
      component: () =>
        import(/* webpackChunkName: "page/project/coupon/routerView" */ 'page/project/coupon/routerView'),
      children: [
        {
          path: '/',
          name: '优惠券投放列表',
          meta: {
            title: '优惠券投放列表'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/coupon/index" */ 'page/project/coupon/index')
        }
      ]
    },
    {
      path: 'wx_cardpackage',
      name: '微信卡券',
      redirect: 'wx_cardpackage',
      meta: {
        title: '微信卡券'
      },
      component: () =>
        import(/* webpackChunkName: "page/project/wx/routerView" */ 'page/project/wx/routerView'),
      children: [
        {
          path: '/',
          name: '微信卡券配置',
          meta: {
            title: '微信卡券配置'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/wx/index" */ 'page/project/wx/index')
        },
        {
          path: 'add',
          name: '微信卡券新增',
          meta: {
            title: '微信卡券新增'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/rules/save" */ 'page/project/wx/save')
        },
        {
          path: 'use',
          name: '微信卡券使用设置',
          meta: {
            title: '微信卡券使用设置'
          },
          component: () =>
            import(/* webpackChunkName: "page/project/rules/save" */ 'page/project/wx/use')
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/project/' + route.path
  }
}

export default router
