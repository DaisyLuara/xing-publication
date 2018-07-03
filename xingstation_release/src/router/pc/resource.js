import auth from 'service/auth'

let router = {
  path: 'resource',
  redirect: 'resource/picture',
  name: '资源',
  meta: {
    title: '资源',
    permission: ''
  },
  component: () =>
    import(/* webpackChunkName: "page/resource/resourceView" */ 'page/resource/resourceView'),
  children: [
    {
      path: 'picture',
      name: '图片管理',
      redirect: 'picture',
      meta: {
        title: '图片管理',
        permission: ''
      },
      component: () =>
        import(/* webpackChunkName: "page/resource/picture/routerView" */ 'page/resource/picture/routerView'),
      children: [
        {
          path: '/',
          name: '图片管理列表',
          meta: {
            title: '图片管理列表',
            permission: ''
          },
          component: () =>
            import(/* webpackChunkName: "page/resource/picture/index" */ 'page/resource/picture/index')
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    return '/resource/' + route.path
  }
}

export default router