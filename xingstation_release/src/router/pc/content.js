import auth from 'service/auth'
import { URL } from '../../constant/Url'

let router = {
  path: 'content',
  redirect: 'content/news',
  name: '内容',
  meta: {
    title: '内容',
    // permission: 'content',
    url: URL.CDN_URL + 'middle_ground/img/team-icon.png'
  },
  component: () =>
    import(
      /* webpackChunkName: "page/content/contentView" */ 'page/content/contentView'
    ),
  children: [
    {
      path: 'news',
      name: '新闻列表',
      meta: {
        title: '新闻列表'
        // permission: 'content.news'
      },
      component: () =>
        import(
          /*webpackChunName: "page/content/news/routerView" */ 'page/content/news/routerView'
        ),
      children: [
        {
          path: '/',
          name: '新闻列表',
          meta: {
            title: '新闻列表'
            // permission: 'content.news.read'
          },
          component: () =>
            import(
              /*webpackChunName: "page/content/news/index" */ 'page/content/news/index'
            )
        },
        {
          path: 'add',
          name: '发布新闻',
          meta: {
            title: '发布新闻'
            // permission: 'content.news.add'
          },
          component: () =>
            import(
              /*webpackChunName: "page/content/news/save" */ 'page/content/news/save'
            )
        },
        {
          path: 'edit/:uid',
          name: '修改新闻',
          meta: {
            title: '修改新闻'
            // permission: 'content.news.edit'
          },
          component: () =>
            import(
              /*webpackChunName: "page/content/news/save" */ 'page/content/news/save'
            )
        }
      ]
    },
    {
      path: 'cases',
      name: '案例列表',
      meta: {
        title: '案例列表'
        // permission: 'content.cases'
      },
      component: () =>
        import(
          /*webpackChunName: "page/content/cases/routerView" */ 'page/content/cases/routerView'
        ),
      children: [
        {
          path: '/',
          name: '案例列表',
          meta: {
            title: '案例列表'
            // permission: 'content.cases.read'
          },
          component: () =>
            import(
              /*webpackChunName: "page/content/cases/index" */ 'page/content/cases/index'
            )
        },
        {
          path: 'add',
          name: '发布案例',
          meta: {
            title: '发布案例'
            // permission: 'content.cases.add'
          },
          component: () =>
            import(
              /*webpackChunName: "page/content/cases/save" */ 'page/content/cases/save'
            )
        },
        {
          path: 'types',
          name: '类型管理',
          meta: {
            title: '类型管理'
            // permission: 'content.cases.types'
          },
          component: () =>
            import(
              /*webpackChunName: "page/content/cases/types" */ 'page/content/cases/types'
            )
        },
        {
          path: 'edit/:uid',
          name: '修改案例',
          meta: {
            title: '修改案例'
            // permission: 'content.cases.edit'
          },
          component: () =>
            import(
              /*webpackChunName: "page/content/cases/save" */ 'page/content/cases/save'
            )
        }
      ]
    },
    {
      path: 'jobs',
      name: '职位列表',
      meta: {
        title: '职位列表'
        // permission: 'content.jobs'
      },
      component: () =>
        import(
          /*webpackChunName: "page/content/jobs/routerView" */ 'page/content/jobs/routerView'
        ),
      children: [
        {
          path: '/',
          name: '职位列表',
          meta: {
            title: '职位列表'
            // permission: 'content.jobs.read'
          },
          component: () =>
            import(
              /*webpackChunName: "page/content/jobs/index" */ 'page/content/jobs/index'
            )
        },
        {
          path: 'add',
          name: '发布职位',
          meta: {
            title: '发布职位'
            // permission: 'content.jobs.add'
          },
          component: () =>
            import(
              /*webpackChunName: "page/content/jobs/save" */ 'page/content/jobs/save'
            )
        },
        {
          path: 'edit/:uid',
          name: '修改职位',
          meta: {
            title: '修改职位'
            // permission: 'content.jobs.edit'
          },
          component: () =>
            import(
              /*webpackChunName: "page/content/jobs/save" */ 'page/content/jobs/save'
            )
        }
      ]
    }
  ]
}

router.redirect = () => {
  let routes = router.children
  for (let route of routes) {
    if (auth.checkPathPermission(route)) {
      return '/content/' + route.path
    }
  }
}

export default router
