import { router } from '../main'
import { Message, MessageBox } from 'element-ui'
const HOST = process.env.SERVER_URL
const LOGIN_API = '/api/authorizations'
const LOGOUT_API = '/api/authorizations/current'
const USERINFO_API = '/api/user?include=permissions,roles'
const IMAGE_CAPTCHA = '/api/captchas'
const USER_API = '/api/user'
const SMS_CAPTCHA = '/api/verificationCodes'
const TOWER_OUTH_TOKEN = '/api/oauth/token?include=permissions,roles'
export default {
  login(context, creds, redirect) {
    context.setting.submiting = true;
    console.log(creds)
    context.$http.post(HOST + LOGIN_API, creds).then(response => {
      console.log(response)
        //  将token与权限存储到cookie和localstorage中,取的时候从localstorage中取
        let loginResult = response.data;
        this.setToken(context, loginResult);
        context.$message({
          message: "登录成功!",
          type: "success"
        })
        context.setting.submiting = false;
        this.refreshUserInfo(context).then(() => {
          context.$router.push({
            path: redirect ? redirect : '/'
          })
        })
      })
      .catch(err => {
        console.log(err)
        // context.setting.loginFailedTimes++;
        context.setting.submiting = false;
      })
  },
  checkFacility() {
    if (/AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))) {
      if (window.location.href.indexOf("?mobile") < 0) {
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
          return true;
        } else {
          return false;
        }
      }
    }
  },
  // 根据本地token来检测用户的登录状态
  checkLogin(context) {
    if (this.checkTokenExpired(context)) {
      return false;
    } else {
      return true;
    }
  },

  logout(context) {
    context.$http.delete(HOST + LOGOUT_API).then(data => {
      this.clearLoginData(context)
      let setIntervalValue = context.$store.state.notificationCount.setIntervalValue
      clearInterval(setIntervalValue)
      context.$router.push({
        path: '/login'
      })
    }).catch(err => {
      console.log(err)
    });
  },

  // 清楚一切登录相关数据
  clearLoginData(context) {
    context.$cookie.delete('jwt_token');
    context.$cookie.delete('user_info');
    context.$cookie.delete('jwt_ttl');
    context.$cookie.delete('jwt_begin_time');
    localStorage.removeItem('jwt_token')
    localStorage.removeItem('user_info')
    localStorage.removeItem('jwt_ttl')
    localStorage.removeItem('permissions')
    localStorage.removeItem('jwt_begin_time')
    let setIntervalValue = context.$store.state.notificationCount.setIntervalValue
    clearInterval(setIntervalValue)
  },

  refreshUserInfo(context) {
    return new Promise((resolve, reject) => {
      context.$http.get(HOST + USERINFO_API).then(response => {
          let result = response.data;
          console.log(result)
          localStorage.setItem('permissions',JSON.stringify(result.permissions))
          localStorage.removeItem('user_info')
          localStorage.setItem("user_info", JSON.stringify(result))
            //context.$store.commit('setCurUserInfo', result.data)
          resolve(result.data)
        })
        .catch(error => {
          reject(error)
        })
    })
  },

  getToken() {
    return localStorage.getItem('jwt_token')
  },

  getTowerAccessToken() {
    let user_info = JSON.parse(localStorage.getItem('user_info'))
    console.log(user_info.tower_access_token)
    return user_info.tower_access_token
  },

  getUserInfo() {
    let permissions = localStorage.getItem('permissions')
    if (permissions) {
      return JSON.parse(permissions)
    }
    return {}
  },

  getPermission() {
    let permissions = this.getUserInfo()
    return permissions
  },

  checkPathPermission(route) {
    if (!route.meta || !route.meta.permission) {
      return true
    }
    return this.checkPermission(route.meta.permission)
  },

  checkPermission(name) {
    return hasPermission(name, this.getPermission())
  },

  // 获取token的时效，分钟为单位
  getTokenLifeTime() {
    return localStorage.getItem('jwt_ttl')
  },

  // 获取token生成的时间
  getTokenBeginTime() {
    return localStorage.getItem('jwt_begin_time')
  },

  setToken(context, tokenObj) {
    context.$cookie.set("jwt_token", tokenObj.access_token)
    localStorage.setItem("jwt_token", tokenObj.access_token)
    context.$cookie.set("jwt_ttl", tokenObj.expires_in)
    localStorage.setItem("jwt_ttl", tokenObj.expires_in)
    let tokenBeginTime = (new Date()).getTime()
    context.$cookie.set("jwt_begin_time", tokenBeginTime)
    localStorage.setItem("jwt_begin_time", tokenBeginTime)
  },

  // 检测token是否过期, 过期返回true，没有过期返回false
  checkTokenExpired() {
    let nowTime = new Date(),
      localToken = this.getToken(),
      tokenBeginTime = this.getTokenBeginTime(),
      tokenLifeTime = this.getTokenLifeTime(),
      differTime = nowTime - tokenBeginTime;
    if (!localToken || !tokenBeginTime || !tokenLifeTime) {
      return true;
    }

    let tokenlatestLifeTime = Math.floor(differTime / (60 * 1000));
    console.log("距离token生成已过去:" + tokenlatestLifeTime + "分钟", "token时效:" + tokenLifeTime + "分钟")
    if (tokenlatestLifeTime >= tokenLifeTime) {
      return true;
    }

    return false;
  },

  modifyUser(context,args){
    return new Promise((resolve, reject) => {
      context.$http.patch(HOST + USER_API, args).then(result => {
        resolve(result.data);
      }).catch(error => {
        reject(error)
      })
    })
  },

  refreshTowerOuthToken(context) {
    return new Promise((resolve, reject) => {
      context.$http.post(HOST + TOWER_OUTH_TOKEN).then(result => {
        resolve(result.data);
      }).catch(error => {
        reject(error)
      })
    })
  },

  // 获取图形验证码
  getImageCaptcha(context, args) {
    let promise = new Promise((resolve, reject) => {
      context.$http.post(HOST + IMAGE_CAPTCHA, args).then(result => {
        resolve(result.data);
      }).catch(error => {
        reject(error)
      })
    })
    return promise;
  },
  sendSmsCaptcha(context, args) {
    let promise = new Promise((resolve, reject) => {
      context.$http.post(HOST + SMS_CAPTCHA, args).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
    return promise;
  }
}

function hasPermission(name, perms) {
  if (!perms) {
    return false;
  }
  for (let i in perms.data) {
    if (name == perms.data[i]['name']) {
      return true
    } 
  }
  return false
}
