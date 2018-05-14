<template>
  <div class="root">
    <div class="item-list-wrap">
      <div id="container" style="width: 100%; height: 80vh;"></div>
      <canvas id="canvas"></canvas>
    </div>
  </div>
</template>

<script>
import BaiduMap from './baidu-map'
export default {
  data() {
    return {
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

    handleMapInit() {
      return new Promise((resolve, reject) => {
        BaiduMap.init()
          .then(BMap => {
            window.BMap = BMap
            this.map = new BMap.Map('container') // 创建地图实例
            this.map.centerAndZoom(new BMap.Point(105.403119, 38.028658), 5) // 初始化地图,设置中心点坐标和地图级别
            this.map.enableScrollWheelZoom(true) // 开启鼠标滚轮缩放
            this.map.setMapStyle({
              style: 'midnight'
            })
            let styleJson = this.styleJson
            this.map.setMapStyle({ styleJson: styleJson })
            this.map.addEventListener('click', function() {
              alert('您点击了地图。')
            })
            this.handleMapVInit().then(() => {
              let mapv = window.mapv
              let randomCount = 300
              let data = []
              let citys = [
                '北京',
                '天津',
                '上海',
                '重庆',
                '石家庄',
                '太原',
                '呼和浩特',
                '哈尔滨',
                '长春',
                '沈阳',
                '济南',
                '南京',
                '合肥',
                '杭州',
                '南昌',
                '福州',
                '郑州',
                '武汉',
                '长沙',
                '广州',
                '南宁',
                '西安',
                '银川',
                '兰州',
                '西宁',
                '乌鲁木齐',
                '成都',
                '贵阳',
                '昆明',
                '拉萨',
                '海口'
              ]

              // 构造数据
              while (randomCount--) {
                let cityCenter = mapv.utilCityCenter.getCenterByCityName(
                  citys[parseInt(Math.random() * citys.length)]
                )
                data.push({
                  geometry: {
                    type: 'Point',
                    coordinates: [
                      cityCenter.lng - 2 + Math.random() * 4,
                      cityCenter.lat - 2 + Math.random() * 4
                    ]
                  },
                  count: 30 * Math.random()
                })
              }
              console.dir(data)
              let dataSet = new mapv.DataSet(data)

              let options = {
                fillStyle: 'rgba(55, 50, 250, 0.8)',
                shadowColor: 'rgba(255, 250, 50, 1)',
                shadowBlur: 20,
                max: 100,
                size: 50,
                label: {
                  show: true,
                  fillStyle: 'white'
                  // shadowColor: 'yellow',
                  // font: '20px Arial',
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
              this.mapvLayer = new mapv.baiduMapLayer(
                this.map,
                dataSet,
                options
              )
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
  }
}
</style>
