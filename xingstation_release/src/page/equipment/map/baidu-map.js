export default {
  init: function() {
    //console.log("初始化百度地图脚本...");
    const AK = 'Xd9T35A3uXsmFl5ofa5sqkPTT2ywMXoi'
    const BMap_URL =
      'http://api.map.baidu.com/api?v=2.0&ak=' + AK + '&callback=onBMapCallback'
    const VMap_URL = 'http://mapv.baidu.com/build/mapv.min.js'
    return new Promise((resolve, reject) => {
      // 如果已加载直接返回
      if (typeof BMap !== 'undefined') {
        resolve(BMap)
        return true
      }
      // 百度地图异步加载回调处理
      window.onBMapCallback = function() {
        console.log('百度地图脚本初始化成功...')
        resolve(BMap)
      }
      // 插入script脚本
      let scriptNode = document.createElement('script')
      scriptNode.setAttribute('type', 'text/javascript')
      scriptNode.setAttribute('src', BMap_URL)
      document.body.appendChild(scriptNode)
    })
  }
}
