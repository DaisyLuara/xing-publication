import auth from 'service/auth'
import { URL } from '../../constant/Url'

let router = {
  path: 'feedback',
  name: '反馈',
  meta: {
    title: '反馈',
    permission: '',
    url: URL.CDN_URL + 'middle_ground/img/feedback_icon.png'
  },
  component: () =>
    import(
      /* webpackChunkName: "page/feedback/feedbackView" */ 'page/feedback/feedbackView'
    ),
  children: [
    {
      path: 'list',
      meta: {
        title: '反馈列表'
        // permission: 'feedback.item'
      },
      component: () =>
        import(
          /* webpackChunkName: "page/feedback/list/routerView" */ 'page/feedback/list/routerView'
        ),
      children: [
        {
          path: '/',
          name: '反馈详情列表',
          meta: {
            title: '反馈详情列表'
            // permission: 'feedback.item.refeedback'
          },
          component: () =>
            import(
              /* webpackChunkName: "page/feedback/list/index" */ 'page/feedback/list/index'
            )
        },
        {
          path: 'save/:uid',
          component: () =>
            import(
              /* webpackChunkName: "page/feedback/list/save" */ 'page/feedback/list/save'
            ),
          name: '回答反馈',
          meta: {
            // permission: 'ad.item.create'
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
      return '/feedback/' + route.path
    }
  }
}

export default router
