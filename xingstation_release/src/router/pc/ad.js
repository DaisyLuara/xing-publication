import auth from 'service/auth'
import { URL } from '../../constant/Url'

let router = {
  path: 'ad',
  name: '广告',
  meta: {
    title: '广告',
    permission: 'ad',
    url: URL.CDN_URL + 'middle_ground/img/advertisement-icon.png'

  },
  component: () =>
    import(/* webpackChunkName: "page/ad/adView" */ 'page/ad/adView'),
  children: [
    {
      path: 'advertisement',
      meta: {
        title: '广告素材',
        permission: 'ad.advertisement'
      },
      component: () =>
        import(/* webpackChunkName: "page/ad/advertisement/routerView" */ 'page/ad/advertisement/routerView'),
      children: [
        {
          path: '/',
          name: '广告素材列表',
          meta: {
            title: '广告素材列表',
            permission: 'ad.advertisement.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/ad/advertisement/index" */ 'page/ad/advertisement/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/ad/advertisement/save" */ 'page/ad/advertisement/save'),
          name: '广告素材增加',
          meta: {
            permission: 'ad.advertisement.create'
          }
        },
        {
          path: 'edit/:aid',
          component: () =>
            import(/* webpackChunkName: "page/ad/advertisement/save" */ 'page/ad/advertisement/save'),
          name: '广告素材编辑',
          meta: {
            permission: 'ad.advertisement.update'
          }
        }
      ]
    },
    {
      path: 'plan',
      meta: {
        title: '广告模版',
        permission: 'ad.plan'
      },
      component: () =>
        import(/* webpackChunkName: "page/ad/plan/routerView" */ 'page/ad/plan/routerView'),
      children: [
        {
          path: '/',
          name: '广告模版列表',
          meta: {
            title: '广告模版列表',
            permission: 'ad.plan.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/ad/plan/index" */ 'page/ad/plan/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/ad/plan/adPlanSave" */ 'page/ad/plan/adPlanSave'),
          name: '新增广告模版',
          meta: {
            permission: 'ad.plan.create'
          }
        },
        {
          path: 'edit/:plan_id',
          component: () =>
            import(/* webpackChunkName: "page/ad/plan/adPlanSave" */ 'page/ad/plan/adPlanSave'),
          name: '编辑广告模版',
          meta: {
            permission: 'ad.plan.update'
          }
        },
        {
          path: ':plan_id/plan_time',
          component: () =>
            import(/* webpackChunkName: "page/ad/plan/schedule" */ 'page/ad/plan/schedule'),
          name: '排期列表',
          meta: {
            permission: 'ad.plan.create'
          }
        },
        {
          path: ':plan_id/plan_time/add',
          component: () =>
            import(/* webpackChunkName: "page/ad/plan/adPlanTimeSave" */ 'page/ad/plan/adPlanTimeSave'),
          name: '新增排期',
          meta: {
            permission: 'ad.plan.create'
          }
        },
        {
          path: '/plan_time/edit/:plan_time_id',
          component: () =>
            import(/* webpackChunkName: "page/ad/plan/adPlanTimeSave" */ 'page/ad/plan/adPlanTimeSave'),
          name: '更新排期',
          meta: {
            permission: 'ad.plan.update'
          }
        }
      ]
    },
    {
      path: 'item',
      meta: {
        title: '广告投放',
        permission: 'ad.item'
      },
      component: () =>
        import(/* webpackChunkName: "page/ad/item/routerView" */ 'page/ad/item/routerView'),
      children: [
        {
          path: '/',
          name: '广告投放详情列表',
          meta: {
            title: '广告投放详情列表',
            permission: 'ad.item.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/ad/item/index" */ 'page/ad/item/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/ad/item/adSave" */ 'page/ad/item/adSave'),
          name: '新增广告投放节目',
          meta: {
            permission: 'ad.item.create'
          }
        }
      ]
    },
    {
      path: 'url',
      meta: {
        title: '短链接',
        permission: 'ad.url'
      },
      component: () =>
        import(/* webpackChunkName: "page/ad/url/routerView" */ 'page/ad/url/routerView'),
      children: [
        {
          path: '/',
          name: '短链接列表',
          meta: {
            title: '短链接列表',
            permission: 'ad.url.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/ad/url/index" */ 'page/ad/url/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/ad/url/add" */ 'page/ad/url/add'),
          name: '短链接增加',
          meta: {
            permission: 'ad.url.create'
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
      return '/ad/' + route.path
    }
  }
}

export default router
