<template>
  <div class="root">
    <div class="item-list-wrap">
      <div id="map" style="width: 100%; height: 100%;"></div>
    </div>
  </div>
</template>

<script>
import BaiduMap from './baidu-map'
export default {
  data() {
    return {
      filters: {
        name: ''
      },
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      dataValue: '',
      loading: true,
      arUserName: '',
      dataShowFlag: true,
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      tableData: []
    }
  },
  mounted() {
    this.init()
  },
  created() {},
  methods: {
    async init() {
      try {
        await this.handleMapvInit()
        await this.handleMapInit()
      } catch (e) {
        console.log(e)
      }
    },
    handleMapInit() {
      return new Promise((resolve, reject) => {
        BaiduMap.init().then(BMap => {
          console.dir(BMap)
          let map = new BMap.Map('container') // 创建地图实例
          let point = new BMap.Point(116.404, 39.915) // 创建点坐标
          map.centerAndZoom(point, 15) // 初始化地图，设置中心点坐标和地图级别
        })
      })
    },
    handleMapvInit() {
      return new Promise((resolve, reject) => {
        let mapv = document.createElement('script')
        mapv.src = 'http://huiyan-fe.github.io/mapv/build/mapv.min.js'
        mapv.async = false
        document.head.appendChild(mapv)
        mapv.onload = () => {
          resolve()
        }
      })
    }
  }
}
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;
  .item-list-wrap {
    background: #fff;
    padding: 30px;
  }
}
</style>
