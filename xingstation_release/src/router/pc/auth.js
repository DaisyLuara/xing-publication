import auth from 'service/auth'
import { URL } from '../../constant/Url'

let router = {
  path: 'auth',
  name: '授权',
  meta: {
    title: '授权',
    permission: 'auth',
    url: URL.CDN_URL + 'middle_ground/img/auth-icon.png'
  },
  component: () =>
    import(
      /* webpackChunkName: "page/auth/resourceAuthView" */ 'page/auth/resourceAuthView'
    ),
  children: [
    {
      path: 'project_auth',
      meta: {
        title: '节目授权',
        permission: 'auth.project_auth'
      },
      component: () =>
        import(
          /* webpackChunkName: "page/auth/project_auth/routerView" */ 'page/auth/project_auth/routerView'
        ),
      children: [
        {
          path: '/',
          name: '节目授权列表',
          meta: {
            title: '节目授权列表',
            permission: 'auth.project_auth.read'
          },
          component: () =>
            import(
              /* webpackChunkName: "page/auth/project_auth/index" */ 'page/auth/project_auth/index'
            )
        },
        {
          path: 'add',
          component: () =>
            import(
              /* webpackChunkName: "page/auth/project_auth/save" */ 'page/auth/project_auth/save'
            ),
          name: '新增节目授权',
          meta: {
            permission: 'auth.project_auth.create'
          }
        },
        {
          path: 'edit/:uid',
          component: () =>
            import(
              /* webpackChunkName: "page/auth/project_auth/save" */ 'page/auth/project_auth/save'
            ),
          name: '编辑节目授权',
          meta: {
            permission: 'auth.project_auth.update'
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
      return '/auth/' + route.path
    }
  }
}

export default router
