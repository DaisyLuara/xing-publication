/**
 * http配置
 */
import axios from 'axios'
import router from '../router'
import { Message, MessageBox } from 'element-ui'
import store from '../store'
import auth from '../service/auth'
import app from '../main'
// const REFRESH_TOKEN_API = '/api/users/refresh'

function VueAxios(Vue) {
  if (VueAxios.installed) {
    return
  }

  // axios默认设置

  // axios.defaults.baseURL = process.env.SERVER_URL;
  // axios.defaults.withCredentials = true;

  // http拦截器
  axios.interceptors.request.use(function(config) {
    // config.headers['Authorization'] = 'Bearer ' + auth.getToken();
    // one request of refreshing token can be send at one time
    // auth or logout cannot trrigle refresh token
    // if (store.getters.isRefreshToken || config.url.includes('auth') || config.url.includes('logout')) {
    if (config.url.includes('auth') || config.url.includes('logout')) {
      config.headers['Authorization'] = 'Bearer ' + auth.getToken();
      return config;
      
    } else if(config.url.includes('tower')){
      console.log('tower')
      // config.headers['Authorization'] = 'Bearer ' + auth.getTowerAccessToken();
      config.headers['Authorization'] = 'Bearer 8995a9869d18fa19ab7f86dd2e883e9d6e2c92d6a4c89a5faaf77a3efbb3fb67'
      console.log(config)
      return config;
    }else{
      // if (auth.checkTokenRefresh()) {
      //   // refresh token
      //   return auth.refreshToken(app).then(result => {
      //     return config;
      //   }).catch(error => {
      //     return config;
      //   })
      // } else {
      //   return config
      // }
      config.headers['Authorization'] = 'Bearer ' + auth.getToken();
      return config
    }

  }, function(error) {
    return Promise.reject(error);
  });

  axios.interceptors.response.use(function(response) {
    // Do something with response data
    let result = response.data;
    console.log(response)
    console.log(result)
    // if (result && !result.success) {
    //   if (response.config && response.config.passError) {
    //     return Promise.reject(response);
    //   } else {
    //     return Promise.reject(result);
    //   }
    // }
    // localStorage.setItem('tower_auth',true);
    return response;
  }, function(error) {
    console.log(error)
    console.log(222222)
    if (error.response) {
      // The request was made and the server responded with a status code
      // that falls out of the range of 2xx
      if (error.response.status == 401) {
        if (error.response.config.url.includes('tower')) {
            let user_info = JSON.parse(localStorage.getItem('user_info'))
            let id = user_info.id
            console.log(user_info.id)
            // localStorage.setItem('tower_auth',false);
            console.log(process.env.SERVER_URL)
            window.open(process.env.SERVER_URL+ '/api/login/tower?id=' + id)
        } else {
          // 退出登录，清除登录信息，跳转到登录页面
          // Message.error("对不起，您未被授权")
          auth.clearLoginData(app)
          router.push({
            path: '/login'
          })
          Message.error("请求出错：代码" + error.response.status)
        }
      } else {
        if(error.response.status == 429) {
          Message.error("请求出错:" + error.response.statusText)
        }else{
          Message.error("请求出错：代码" + error.response.status)
        }
      }
    } else if (error.request) {
      // The request was made but no response was received
      // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
      // http.ClientRequest in node.js
      console.log(error.request);
    } else {
      // Something happened in setting up the request that triggered an Error
      console.log('Error', error.message);
    }
    // Do something with response error
    return Promise.reject(error);
  });

  // 挂axios到Vue上
  Vue.axios = axios

  Object.defineProperties(Vue.prototype, {

    axios: {
      get() {
        return axios
      }
    },

    $http: {
      get() {
        return axios
      }
    }
  })
};

if (typeof window !== 'undefined' && window.Vue) {
  window.Vue.use(VueAxios)
}

export default VueAxios;