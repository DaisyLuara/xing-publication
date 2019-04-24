import auth from 'service/auth'

let router = {
  path: 'prize',
  redirect: 'prize/rules',
  name: '奖品',
  meta: {
    title: '奖品',
    permission: 'prize'
  },
  component: () =>
    import(/* webpackChunkName: "page/prize/prizeView" */ 'page/prize/prizeView'),
  children: [
    {
      path: 'rules',
      meta: {
        title: '奖品列表',
        permission: 'prize.rules'
      },
      component: () =>
        import(/* webpackChunkName: "page/prize/rules/routerView" */ 'page/prize/rules/routerView'),
      children: [
        {
          path: '/',
          name: '奖品详情',
          meta: {
            title: '奖品详情',
            permission: 'prize.rules.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/prize/rules/index" */ 'page/prize/rules/index')
        },
        {
          path: 'add',
          name: '奖品增加',
          meta: {
            title: '奖品增加',
            permission: 'prize.rules.create'
          },
          component: () =>
            import(/* webpackChunkName: "page/prize/rules/save" */ 'page/prize/rules/save')
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/prize/rules/save" */ 'page/prize/rules/save'),
          name: '奖品修改',
          meta: {
            title: '奖品增加',
            permission: 'prize.rules.update'
          }
        }
      ]
    },
    {
      path: 'strategy',
      meta: {
        title: '奖品模板',
        permission: 'prize.strategy'
      },
      component: () =>
        import(/* webpackChunkName: "page/prize/strategy/routerView" */ 'page/prize/strategy/routerView'),
      children: [
        {
          path: '/',
          name: '奖品模板列表',
          meta: {
            title: '奖品模板列表',
            permission: 'prize.strategy.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/prize/strategy/index" */ 'page/prize/strategy/index')
        },
        {
          path: 'policy',
          name: '奖品模板子条目列表',
          meta: {
            title: '奖品模板子条目详情',
            permission: 'prize.strategy.childRead'
          },
          component: () =>
            import(/* webpackChunkName: "page/prize/strategy/policise" */ 'page/prize/strategy/policise')
        },
        {
          path: 'p_save',
          meta: {
            title: '奖品模版增加',
            permission: 'prize.strategy.create'
          },
          component: () =>
            import(/* webpackChunkName: "page/prize/strategy/strategySave" */ 'page/prize/strategy/strategySave')
        },
        {
          path: 'p_edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/prize/strategy/strategySave" */ 'page/prize/strategy/strategySave'),
          name: '奖品模板修改',
          meta: {
            title: '奖品模板修改',
            permission: 'prize.strategy.update'
          }
        },
        {
          path: 'add',
          meta: {
            title: '子条目增加',
            permission: 'prize.strategy.childCreate'
          },
          component: () =>
            import(/* webpackChunkName: "page/prize/strategy/save" */ 'page/prize/strategy/save')
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/prize/strategy/save" */ 'page/prize/strategy/save'),
          name: '奖品子条目修改',
          meta: {
            title: '奖品子条目修改',
            permission: 'prize.strategy.childUpdate'
          }
        }
      ]
    },
    {
      path: 'coupon',
      meta: {
        title: '奖品核销',
        permission: 'prize.coupon'
      },
      component: () =>
        import(/* webpackChunkName: "page/prize/coupon/routerView" */ 'page/prize/coupon/routerView'),
      children: [
        {
          path: '/',
          meta: {
            title: '奖品核销列表',
            permission: 'prize.coupon.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/prize/coupon/index" */ 'page/prize/coupon/index')
        }
      ]
    },
    {
      path: 'launch',
      meta: {
        title: '奖品投放',
        permission: 'prize.launch'
      },
      component: () =>
        import(/* webpackChunkName: "page/prize/prize_put/routerView" */ 'page/prize/prize_put/routerView'),
      children: [
        {
          path: '/',
          meta: {
            title: '奖品投放列表',
            permission: 'prize.launch.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/prize/prize_put/index" */ 'page/prize/prize_put/index')
        },
        {
          path: 'add',
          meta: {
            title: '奖品投放增加',
            permission: 'prize.launch.create'
          },
          component: () =>
            import(/* webpackChunkName: "page/prize/prize_put/save" */ 'page/prize/prize_put/save')
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(/* webpackChunkName: "page/prize/prize_put/save" */ 'page/prize/prize_put/save'),
          meta: {
            title: '奖品投放修改',
            permission: 'prize.launch.update'
          }
        }
      ]
    },
    {
      path: 'wx_cardpackage',
      meta: {
        title: '微信卡券',
        permission: 'prize.wx_cardpackage'
      },
      component: () =>
        import(/* webpackChunkName: "page/prize/wx/routerView" */ 'page/prize/wx/routerView'),
      children: [
        {
          path: '/',
          meta: {
            title: '微信卡券配置',
            keepAlive: false, // 不需要被缓存,
            permission: 'prize.wx_cardpackage.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/prize/wx/index" */ 'page/prize/wx/index')
        },
        {
          path: 'add',
          name: '微信卡券新增',
          meta: {
            title: '微信卡券新增',
            keepAlive: true, // 需要被缓存
            permission: 'prize.wx_cardpackage.create'
          },
          component: () =>
            import(/* webpackChunkName: "page/prize/wx/save" */ 'page/prize/wx/save')
        },
        {
          path: 'use',
          meta: {
            title: '微信卡券使用设置',
            keepAlive: false, // 不需要被缓存
            permission: 'prize.wx_cardpackage.use'
          },
          component: () =>
            import(/* webpackChunkName: "page/prize/wx/use" */ 'page/prize/wx/use')
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/prize/' + route.path
    }
  }
}

export default router
