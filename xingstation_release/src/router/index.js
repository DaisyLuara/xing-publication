import Vue from 'vue'
import auth from 'service/auth'
import Router from 'vue-router'
import { Message } from 'element-ui'
import login from 'page/login'
import logout from 'page/logout'
import store from 'store'
import pcRouter from 'router/pcRouter'
import PageNotFound from 'page/PageNotFound'

Vue.use(Router)

var router = new Router({
  // mode: 'history',
  routes: [
    pcRouter,
    {
      path: '/login',
      component: login,
    },
    {
      path: '/logout',
      component: logout,
    },
    // {
    //   path: '/register',
    //   component: register,
    // },
    // {
    //   path: '/findPassword',
    //   component: findPassword,
    // },
    // {
    //   path: '/setNewPassword',
    //   component: setNewPassword,
    // },
    { path: '*', component: PageNotFound },
  ],
})

// Add router hook for handling asyncData.
// Doing it after initial route is resolved so that we don't double-fetch
// the data that we already have. Using router.beforeResolve() so that all
// async components are resolved.

// router.beforeResolve((to, from, next) => {
//   const matched = router.getMatchedComponents(to)
//   const prevMatched = router.getMatchedComponents(from)
//   let diffed = false
//   const activated = matched.filter((c, i) => {
//     return diffed || (diffed = prevMatched[i] !== c)
//   })
//   const asyncDataHooks = activated.map(c => c.asyncData).filter(_ => _)
//   if (!asyncDataHooks.length) {
//     return next()
//   }
//   console.log(asyncDataHooks)
//   // bar.start()
//   Promise.all(asyncDataHooks.map(hook => hook({ store, route: to })))
//     .then(() => {
//       // bar.finish()
//       next()
//     })
//     .catch(next)
// })

router.beforeEach((to, from, next) => {
  // 手机访问PC页面，导向M页
  // let anyFacility = ['/login', '/findPassword', '/register'] //目前来说公用m和pc的页面，都不需要走m的路由！
  // // if (
  // //   auth.checkFacility() &&
  // //   to.path != '/m' &&
  // //   !anyFacility.includes(to.path)
  // // ) {
  // //   next({ path: '/m' })
  // // }

  // 非登录白名单（非登录状态下，仍然可以访问的路由）
  let loginess = ['/login']
  if (!auth.checkLogin()) {
    let pathWhiteList = loginess.filter(pathness => {
      if (to.path == pathness) {
        return pathness
      }
    })

    if (pathWhiteList.length < 1) {
      next({ path: '/login' })
    } else {
      next()
    }
    return
  }

  // // 登录黑名单（登录状态下，不可再访问的路由）
  let unlessLogout = ['/login']
  let loginBlackList = unlessLogout.filter(unlessPath => {
    if (to.path == unlessPath) {
      return unlessPath
    }
  })
  if (loginBlackList.length > 0) {
    next({ path: '/' })
    return
  }
console.log(to)
  // 权限白名单(不受权限限制)
  // let permissioness = ['/login', '/findPassword']
  let hasPathPermission = auth.checkPathPermission(to)
  if (hasPathPermission) {
    store.commit('refreshRoute', to)
    next()
  } else {
    Message.error('您没有权限访问这个页面')
  }
})

export default router
