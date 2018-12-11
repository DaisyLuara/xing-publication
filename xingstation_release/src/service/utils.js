import router from 'router'
export default {
  install(Vue) {
    Vue.prototype.$cancel = function() {
      history.go(-1)
    }
  }
}
// 格式 1028-09-09 23:59:59
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
// 格式 yyyy-MM-dd hh:mm:ss
const handleDateTimeTransform = time => {
  var d = new Date(time)
  var year = d.getFullYear()
  var month = change(d.getMonth() + 1)
  var day = change(d.getDate())
  var hour = change(d.getHours())
  var minute = change(d.getMinutes())
  var second = change(d.getSeconds())
  function change(t) {
    if (t < 10) {
      return '0' + t
    } else {
      return t
    }
  }
  return (time =
    year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second)
}
// 格式yyyy-MM-dd
const handleDateTypeTransform = valueDate => {
  let date = new Date(valueDate)
  let year = date.getFullYear() + '-'
  let mouth =
    (date.getMonth() + 1 < 10
      ? '0' + (date.getMonth() + 1)
      : date.getMonth() + 1) + '-'
  let day = (date.getDate() < 10 ? '0' + date.getDate() : date.getDate()) + ''
  return year + mouth + day
}

export {
  install,
  handleDateTransform,
  historyBack,
  handleDateTimeTransform,
  handleDateTypeTransform
}
