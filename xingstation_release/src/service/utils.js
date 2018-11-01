import Vue from 'vue'
export default {
  install(Vue, options) {
    Vue.prototype.$cancel = function() {
      history.go(-1)
    }
  },
}
