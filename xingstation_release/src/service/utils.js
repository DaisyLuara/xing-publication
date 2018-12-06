import Vue from 'vue'
import router from 'router'
export default {
  install(Vue, options) {
    Vue.prototype.$cancel = function() {
      history.go(-1)
    }
  }
}
const handleDateTransform = valueDate => {
  let dateValue = valueDate.replace(/\-/g, '/')
  let date = new Date(dateValue)
  let year = date.getFullYear() + '-'
  let mouth =
    (date.getMonth() + 1 < 10
      ? '0' + (date.getMonth() + 1)
      : date.getMonth() + 1) + '-'
  let day = (date.getDate() < 10 ? '0' + date.getDate() : date.getDate()) + ''
  let hours = date.getHours() === 0 ? date.getHours() + 23 : date.getHours()
  let minutes =
    date.getMinutes() === 0 ? date.getMinutes() + 59 : date.getMinutes()
  let second =
    date.getSeconds() === 0 ? date.getSeconds() + 59 : date.getSeconds()
  return year + mouth + day + ' ' + hours + ':' + minutes + ':' + second
}
const historyBack = () => {
  router.back()
}
export { install, handleDateTransform, historyBack }
