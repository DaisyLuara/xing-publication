import { router } from '../main'

const HOST = process.env.SERVER_URL
const REGISTER_API = '/api/users/register'
const LOGIN_API = '/api/users/auth'
const CHECK_LOGIN_API = '/api/users/alive'
const LOGOUT_API = '/api/users/logout'
const USERINFO_API = '/api/users/user'
const REFRESH_TOKEN_API = '/api/users/refresh'
const IMAGE_CAPTCHA = '/api/common/getCaptchaKey'
const IMAGE_CAPTCHA_URL = '/api/common/captcha'
const RESET_PASSWORD = '/api/password/reset'
const RESET_FIRST_PASSWORD = '/api/password/resetFirstPassword'
const SMS_CAPTCHA = '/api/sms/sendVerifySms'
const VOICE_CAPTCHA = '/api/sms/sendVoiceVerifySms'
export default {
  login(context, creds, redirect) {
    context.setting.submiting = true;
    console.log(creds)
    context.$http.post(LOGIN_API, creds).then(response => {
        //  将token与权限存储到cookie和localstorage中,取的时候从localstorage中取
        let loginResult = response.data.data;
        this.setToken(context, loginResult);
        context.$message({
          message: "登录成功!",
          type: "success"
        })
        context.setting.submiting = false;
        this.refreshUserInfo(context).then(() => {
          // if (this.checkFacility()) {
          //   context.$router.push({
          //     path: '/m'
          //   })
          // } else {
            context.$router.push({
              path: redirect ? redirect : '/'
            })
          // }
        })
      })
      .catch(err => {
        context.setting.loginFailedTimes++;
        context.setting.submiting = false;
        if (err.data && err.data.first_login) {
          context.$store.commit('setOldPassword', {
            mobile: creds.passport,
            old_password: creds.password
          })
          context.$router.push({
            path: '/setNewPassword'
          })
        }
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
    context.$http.get(LOGOUT_API).then(data => {
      this.clearLoginData(context)

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
    localStorage.removeItem('jwt_begin_time')
  },

  refreshUserInfo(context) {
    let promise = new Promise((resolve, reject) => {
      context.$http.get(USERINFO_API).then(response => {
          let result = response.data;
          localStorage.setItem("user_info", JSON.stringify(result.data))
            //context.$store.commit('setCurUserInfo', result.data)
          resolve(result.data)
        })
        .catch(error => {
          reject(error)
        })
    })

    return promise
  },

  getToken() {
    return localStorage.getItem('jwt_token1')
  },

  getUserInfo() {
    let user_info = localStorage.getItem('user_info')
    if (user_info) {
      return JSON.parse(user_info)
    }
    return {}
  },

  getPermission() {
    let user_info = this.getUserInfo()
    return user_info.perms
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

  // 判断token是否需要refresh
  checkTokenRefresh() {
    let nowTime = new Date(),
      tokenBeginTime = this.getTokenBeginTime(),
      tokenLifeTime = this.getTokenLifeTime(),
      differTime = nowTime - tokenBeginTime;
    // token过期，不能发送refresh请求
    if (this.checkTokenExpired()) {
      return false;
    }

    let thresholdTime = Math.floor((tokenLifeTime - 5) / 3),
      tokenlatestLifeTime = Math.floor(differTime / (60 * 1000));

    if (tokenlatestLifeTime >= thresholdTime) {
      return true;
    }

    return false;

  },

  refreshToken(context) {
    context.$store.commit('setRefreshTokenStatus', true)
    let promise = new Promise((resolve, reject) => {
      context.$http.get(REFRESH_TOKEN_API).then(response => {
        let tokenObj = response.data.data;
        this.setToken(context, tokenObj)
        context.$store.commit('setRefreshTokenStatus', false)
        resolve("更新token成功")
      }).catch(error => {
        context.$store.commit('setRefreshTokenStatus', false)
        reject(error)
      })
    })

    return promise;
  },

  setToken(context, tokenObj) {
    context.$cookie.set("jwt_token", tokenObj.token)
    localStorage.setItem("jwt_token", tokenObj.token)
    context.$cookie.set("jwt_ttl", tokenObj.ttl)
    localStorage.setItem("jwt_ttl", tokenObj.ttl)
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
    // console.log("距离token生成已过去:" + tokenlatestLifeTime + "分钟", "token时效:" + tokenLifeTime + "分钟")
    if (tokenlatestLifeTime >= tokenLifeTime) {
      return true;
    }

    return false;
  },

  // 获取图形验证码
  getImageCaptcha(context) {
    let promise = new Promise((resolve, reject) => {
      context.$http.get(IMAGE_CAPTCHA).then(result => {
        resolve(result.data);
      }).catch(error => {
        reject(error)
      })
    })
    return promise;
  },

  getImageCaptchaUrl(key) {
    return HOST + IMAGE_CAPTCHA_URL + '?key=' + key
  },

  resetPassword(context, params) {
    context.setting.submiting = true;
    context.$http.post(RESET_PASSWORD, params).then(response => {
        let resetResult = response.data.data;
        // 成功，登出用户
        context.setting.submiting = false;
        context.$message({
          message: "密码重置成功，请重新登录!",
          type: "success"
        })

        if (this.checkLogin(context)) {
          this.logout(context);
        } else {
          // 仅仅清除历史登录数据，并不发送登出请求
          this.clearLoginData(context);
          context.$router.push({
            path: '/login'
          })
        }

      })
      .catch(err => {
        context.getImageCaptcha();
        context.setting.submiting = false;
      })
  },

  sendSmsCaptcha(context, mobile) {
    let promise = new Promise((resolve, reject) => {
      context.$http.post(SMS_CAPTCHA, { mobile }).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
    return promise;
  },

  sendVoiceCaptcha(context, mobile) {
    let promise = new Promise((resolve, reject) => {
      context.$http.post(VOICE_CAPTCHA, { mobile }).then(response => {
        resolve(response.data)
      }).catch(error => {
        reject(error)
      })
    })
    return promise;
  },

  register(context, creds, redirect) {
    context.setting.submiting = true
    context.$http.post(REGISTER_API, creds).then(response => {
        let registerResult = response.data.data
        context.$message({
          message: "注册成功!",
          type: "success"
        })
        context.setting.submiting = false
        context.$router.push({
          path: '/login'
        })
      })
      .catch(err => {
        context.setting.submiting = false
      })
  },

  setFirstPassword(context, params) {
    context.setting.submiting = true;
    context.$http.post(RESET_FIRST_PASSWORD, params).then(response => {
        let registerResult = response.data.data
        context.$message({
          message: "新密码设置成功!",
          type: "success"
        })
        context.setting.submiting = false
        this.login(context, {
            passport: params.mobile,
            password: params.new_password,
            remember_token: true
          })
          // context.$router.push({
          //   path: redirect ? redirect : '/login'
          // })
      })
      .catch(err => {
        context.setting.submiting = false;
      })
  }
}

function hasPermission(name, perms) {
  if (!perms) {
    return false;
  }
  if (name == perms.name) {
    return true
  }
  if (perms.children && perms.children.length == 0) {
    return false
  }
  for (let i in perms) {
    if (name == perms[i]['name']) {
      return true
    } else if (name.indexOf(perms[i]['name']) == 0) {
      return hasPermission(name, perms[i]['children'])
    }
  }
  return false
}
