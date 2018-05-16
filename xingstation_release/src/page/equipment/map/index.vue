<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="func">
        <div></div>
        <el-switch
          v-model="requestToday"
          @change="getDataByTimeArea()"
          active-text="当天"
          inactive-text="所有">
        </el-switch>
      </div>
      <div id="container" style="width: 100%; height: 80vh;"></div>
      <canvas id="canvas"></canvas>
    </div>
  </div>
</template>

<script>
import BaiduMap from './baidu-map'
import { Switch } from 'element-ui'
export default {
  components: {
    ElSwitch: Switch
  },
  data() {
    return {
      requestToday: true,
      currentLat: 31.20936447823612,
      currentlng: 121.6082842304611,
      currentLevel: 12,
      bindTime: [],
      map: null,
      mapvLayer: null,
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
      tableData: [],
      styleJson: [
        {
          featureType: 'land',
          elementType: 'geometry.fill',
          stylers: {
            color: '#0a233cff'
          }
        },
        {
          featureType: 'highway',
          elementType: 'geometry',
          stylers: {
            color: '#000000ff',
            weight: '0.1'
          }
        },
        {
          featureType: 'arterial',
          elementType: 'all',
          stylers: {
            color: '#000000ff',
            weight: '0.1'
          }
        },
        {
          featureType: 'local',
          elementType: 'all',
          stylers: {
            color: '#000000ff',
            weight: '0.1'
          }
        },
        {
          featureType: 'railway',
          elementType: 'all',
          stylers: {
            color: '#000000ff',
            weight: '0.1',
            lightness: 1
          }
        },
        {
          featureType: 'subway',
          elementType: 'all',
          stylers: {
            color: '#000000ff',
            weight: '0.1'
          }
        },
        {
          featureType: 'airportlabel',
          elementType: 'labels',
          stylers: {
            color: '#000000ff'
          }
        },
        {
          featureType: 'local',
          elementType: 'labels.text.fill',
          stylers: {
            color: '#011f3aff'
          }
        },
        {
          featureType: 'green',
          elementType: 'all',
          stylers: {
            color: '#0a233cff'
          }
        },
        {
          featureType: 'manmade',
          elementType: 'all',
          stylers: {
            color: '#0a233cff'
          }
        },
        {
          featureType: 'water',
          elementType: 'all',
          stylers: {
            color: '#070e15ff'
          }
        },
        {
          featureType: 'building',
          elementType: 'all',
          stylers: {
            color: '#0a233cff'
          }
        },
        {
          featureType: 'districtlabel',
          elementType: 'labels.text.stroke',
          stylers: {
            color: '#001930ff'
          }
        },
        {
          featureType: 'districtlabel',
          elementType: 'labels.text.fill',
          stylers: {
            color: '#0788b4ff'
          }
        },
        {
          featureType: 'road',
          elementType: 'labels.text.fill',
          stylers: {
            color: '#0788b4ff'
          }
        },
        {
          featureType: 'road',
          elementType: 'labels.text.stroke',
          stylers: {
            color: '#001930ff'
          }
        },
        {
          featureType: 'manmade',
          elementType: 'labels.text.stroke',
          stylers: {
            color: '#001930ff'
          }
        },
        {
          featureType: 'manmade',
          elementType: 'labels.text.fill',
          stylers: {
            color: '#0788b4ff'
          }
        },
        {
          featureType: 'poilabel',
          elementType: 'labels.text.fill',
          stylers: {
            color: '#0788b4ff'
          }
        },
        {
          featureType: 'poilabel',
          elementType: 'labels.text.stroke',
          stylers: {
            color: '#001930ff'
          }
        },
        {
          featureType: 'administrative',
          elementType: 'geometry',
          stylers: {
            color: '#000000ff'
          }
        }
      ],
      zoom: [
        2000000,
        1000000,
        500000,
        200000,
        100000,
        50000,
        25000,
        20000,
        10000,
        5000,
        2000,
        1000,
        500,
        200
      ]
    }
  },
  mounted() {
    this.init()
  },
  methods: {
    async init() {
      try {
        await this.handleMapInit()
      } catch (e) {
        console.log(e)
      }
    },
    clearLayer() {
      this.mapvLayer.destroy()
    },
    getDataByTimeArea() {
      this.setting.loading = true
      let request_url = process.env.SERVER_URL + '/api/point/map'
      let computedDistance =
        this.getDistanceByLevel(this.currentLevel - 2) / 1000
      let request_para = {
        params: {
          lat: this.currentLat,
          lng: this.currentlng,
          distance: computedDistance,
          date: this.requestToday ? 'today' : 'all'
        }
      }
      this.$http.get(request_url, request_para).then(r => {
        console.dir(r)
        this.setting.loading = false
        let resArr = r.data.data
        let data = []
        for (let i = 0; i < resArr.length; i++) {
          data.push({
            geometry: {
              type: 'Point',
              coordinates: [resArr[i].lng, resArr[i].lat]
            },
            count: resArr[i].count
          })
        }
        let dataSet = new mapv.DataSet(data)

        let options = {
          fillStyle: 'rgba(55, 50, 250, 0.8)',
          shadowColor: 'rgba(255, 250, 50, 1)',
          shadowBlur: 20,
          max: 100,
          size: 50,
          label: {
            show: true,
            fillStyle: 'white',
            // shadowColor: 'yellow',
            font: '15px Arial'
            // shadowBlur: 10,
          },
          globalAlpha: 0.5,
          gradient: {
            0.25: 'rgb(0,0,255)',
            0.55: 'rgb(0,255,0)',
            0.85: 'yellow',
            1.0: 'rgb(255,0,0)'
          },
          draw: 'honeycomb'
        }
        this.mapvLayer = new mapv.baiduMapLayer(this.map, dataSet, options)
      })
    },
    handleMapVInit() {
      return new Promise((resolve, reject) => {
        try {
          if (typeof mapv !== 'undefined') {
            resolve()
          } else {
            const VMap_URL = 'http://mapv.baidu.com/build/mapv.min.js'
            let vMapNode = document.createElement('script')
            vMapNode.setAttribute('type', 'text/javascript')
            vMapNode.setAttribute('src', VMap_URL)
            document.body.appendChild(vMapNode)
            if (
              vMapNode.readyState === 'loaded' ||
              vMapNode.readyState === 'complete'
            ) {
              console.log('VMap加载完毕')
              resolve()
            } else {
              vMapNode.onload = () => {
                resolve()
                console.log('VMap加载完毕')
              }
            }
          }
        } catch (e) {
          reject(e)
          console.log(e)
        }
      })
    },
    getDistanceByLevel(level) {
      if (level > 15) {
        return 1000
      } else if (level <= 3) {
        return this.zoom[0]
      } else {
        return this.zoom[level - 3]
      }
    },
    handleMapInit() {
      return new Promise((resolve, reject) => {
        BaiduMap.init()
          .then(BMap => {
            window.BMap = BMap
            this.map = new BMap.Map('container') // 创建地图实例
            this.map.centerAndZoom(
              new BMap.Point(this.currentlng, this.currentLat),
              this.currentLevel
            ) // 初始化地图,设置中心点坐标和地图级别
            this.map.enableScrollWheelZoom(true) // 开启鼠标滚轮缩放
            this.map.setMapStyle({
              style: 'midnight'
            })
            let styleJson = this.styleJson
            this.map.setMapStyle({ styleJson: styleJson })
            this.map.addEventListener('zoomend', () => {
              this.clearLayer()
              let zoomLevel = this.map.getZoom()
              let position = this.map.getCenter()
              this.currentLevel = zoomLevel
              this.currentLat = position.lat
              this.currentlng = position.lng

              this.getDataByTimeArea()
              console.log(zoomLevel)
              console.log(position)
            })
            this.handleMapVInit().then(() => {
              let mapv = window.mapv
              this.getDataByTimeArea()
            })
          })
          .catch(e => {
            reject(e)
            console.log(e)
          })
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
    .func {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
      margin: 10px 0;
    }
  }
}
</style>
