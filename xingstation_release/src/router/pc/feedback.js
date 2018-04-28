import auth from 'service/auth'

let router = {
  path: 'feedback',
  redirect: 'feedback/option',
  name: '反馈',
  meta: {
    title: '反馈',
    permission: '',
  },
  component: () =>
    import(/* webpackChunkName: "page/feedback/feedbackView" */ 'page/feedback/feedbackView'),
    children: [
      {
        path: 'option',
        name: '反馈管理',
        redirect: 'option/index',
        meta: {
          title: '意见反馈',
          permission: '',
        },
        component: () =>
          import(/* webpackChunkName: "page/feedback/option/routerView" */ 'page/feedback/option/routerView'),
        children: [
          {
            path: 'index',
            name: '意见反馈',
            meta: {
              title: '意见反馈',
              permission: '',
            },
            component: () =>
              import(/* webpackChunkName: "page/feedback/option/index" */ 'page/feedback/option/index'),
          },
        ],
      },
    ],
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
