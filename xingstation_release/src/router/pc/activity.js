import auth from 'service/auth'
import { URL } from '../../constant/Url'

let router = {
  path: 'activity',
  redirect: 'activity/participants',
  name: '活动',
  meta: {
    title: '活动',
    permission: 'activity',
    url: URL.CDN_URL + 'middle_ground/img/activity-icon.png'
  },
  component: () =>
    import(/* webpackChunkName: "page/activity/activityView" */ 'page/activity/activityView'),
  children: [
    {
      path: 'participants',
      meta: {
        title: '活动参与者',
        permission: 'activity.participants'
      },
      component: () =>
        import(/* webpackChunkName: "page/activity/participants/routerView" */ 'page/activity/participants/routerView'),
      children: [
        {
          path: '/',
          meta: {
            title: '活动参与者列表',
            permission: 'activity.participants.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/activity/participants/index" */ 'page/activity/participants/index')
        }
      ]
    },
    {
      path: 'bill',
      meta: {
        title: '交易流水',
        permission: 'activity.bill'
      },
      component: () =>
        import(/* webpackChunkName: "page/activity/bill/routerView" */ 'page/activity/bill/routerView'),
      children: [
        {
          path: '/',
          meta: {
            title: '流水列表',
            permission: 'activity.bill.read'
          },
          component: () =>
            import(/* webpackChunkName: "page/activity/bill/index" */ 'page/activity/bill/index')
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/activity/' + route.path
    }
  }
}

export default router
