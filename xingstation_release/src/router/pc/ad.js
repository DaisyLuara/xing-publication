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
      path: 'plan',
      meta: {
        title: '广告方案',
        // permission: 'ad.plan'
      },
      component: () =>
        import(/* webpackChunkName: "page/ad/plan/routerView" */ 'page/ad/plan/routerView'),
      children: [
        {
          path: '/',
          name: '广告方案详情列表',
          meta: {
            title: '广告方案详情列表',
            // permission: 'ad.plan.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/ad/plan/index" */ 'page/ad/plan/index')
        },
        {
          path: 'add',
          component: () =>
            import(/* webpackChunkName: "page/ad/plan/adPlanBatchSave" */ 'page/ad/plan/adPlanBatchSave'),
          name: '新增广告方案',
          meta: {
            // permission: 'ad.plan.create'
          }
        },
        {
          path: 'edit/:ad_plan_id/batch',
          component: () =>
            import(/* webpackChunkName: "page/ad/plan/adPlanBatchSave" */ 'page/ad/plan/adPlanBatchSave'),
          name: '编辑广告方案及批量排期',
          meta: {
            // permission: 'ad.plan.update'
          }
        },
        {
          path: 'edit/:ad_plan_id',
          component: () =>
            import(/* webpackChunkName: "page/ad/plan/adPlanSave" */ 'page/ad/plan/adPlanSave'),
          name: '编辑广告方案',
          meta: {
            // permission: 'ad.plan.update'
          }
        },
        {
          path: ':plan_id/add/plan_time',
          component: () =>
            import(/* webpackChunkName: "page/ad/plan/adPlanTimeSave" */ 'page/ad/plan/adPlanTimeSave'),
          name: '新增排期',
          meta: {
            // permission: 'ad.plan.create'
          }
        },
        {
          path: 'edit/plan_time/:plan_time_id',
          component: () =>
            import(/* webpackChunkName: "page/ad/plan/adPlanTimeSave" */ 'page/ad/plan/adPlanTimeSave'),
          name: '更新排期',
          meta: {
            // permission: 'ad.plan.create'
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
