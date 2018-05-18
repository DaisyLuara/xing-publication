<template>
  <div class="root">

    <el-row :gutter="20">
      <el-col :span="12">
        <el-card shadow="always">
          <div class="" id="echart4" ref="mychart" style="height: 500px; width: 100%"></div>
        </el-card>
      </el-col>
      <el-col :span="12">
        <el-card shadow="always">
          <div class="" id="echart3" ref="mychart" style="height: 500px; width: 100%"></div>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import echarts from 'echarts/lib/echarts'
import echartsGl from 'echarts-gl'
import { Tabs, TabPane, Button, Row, Col, Card, DatePicker } from 'element-ui'

export default {
  data() {
    return {
      pieOptions: {
        title : {
          text: '屏幕总数 381',
          x:'center'
        },
        tooltip : {
          trigger: 'item',
          formatter: "{a} <br/>{b} : {c}"
        },
        toolbox: {
          feature: {
            dataView: {readOnly: false},
            restore: {},
            saveAsImage: {}
          }
        },
        colors: ['#0a5ab4',],
        legend: {
            orient: 'vertical',
            left: 'left',
            data: ['总人数','人脸总张数','曝光人数']
        },
        series : [
          {
            label: {
              normal: {
                formatter: '{b}\n {c}',
                // position: 'inside'
              },
              emphasis: {
                textStyle: {
                  fontSize: 20
                }
              }
            },
            name: '数量',
            type: 'pie',
            radius : ['50%', '70%'],
            data:[
              {value:2011386, name:'总人数', selected:true},
              {value:4778197, name:'人脸总张数'},
              {value:20215890, name:'曝光人数'},
            ],
            itemStyle: {
              emphasis: {
                shadowBlur: 10,
                shadowOffsetX: 0,
                shadowColor: 'rgba(0, 0, 0, 0.5)'
              }
            }
          }
        ]
      },
      funnelOptions: {
        title: {
          text: '总数',
        },
        tooltip: {
          trigger: 'item',
          formatter: "{a} <br/>{b} : {c}%"
        },
        toolbox: {
          feature: {
            dataView: {readOnly: false},
            restore: {},
            saveAsImage: {}
          }
        },
        legend: {
            // data: ['展现','点击','访问']
        },
        calculable: true,
        series: [
          {
            name:'总数',
            type:'funnel',
            left: '10%',
            top: 60,
            bottom: 60,
            width: '80%',
            min: 0,
            minSize: '0%',
            maxSize: '100%',
            sort: 'descending',
            gap: 2,
            label: {
              normal: {
                formatter: '{b} {c}',
                position: 'inside'
              },
              emphasis: {
                textStyle: {
                  fontSize: 20
                }
              }
            },
            labelLine: {
              normal: {
                length: 10,
                lineStyle: {
                  width: 1,
                  type: 'solid'
                }
              }
            },
            itemStyle: {
              normal: {
                borderColor: '#fff',
                borderWidth: 1
              }
            },
            data: [
              {value: 9139, name: '围观总数'},
              {value: 5463, name: '互动总数'},
              {value: 2434, name: '扫码拉新总数'},
            ]
          }
        ]
      },
    }
  },
  mounted() {
    this.handleEcharts()
  },
  components: {
    ElRow: Row,
    ElCol: Col,
    ElCard: Card,
    ElDatePicker: DatePicker
  },
  methods: {
    handleEcharts() {
      let dom3 = document.getElementById('echart3')
      let myChart3 = echarts.init(dom3)
      if (this.funnelOptions && typeof this.funnelOptions === 'object') {
        myChart3.setOption(this.funnelOptions, true)
      }
      let dom4 = document.getElementById('echart4')
      let myChart4 = echarts.init(dom4)
      if (this.pieOptions && typeof this.pieOptions === 'object') {
        myChart4.setOption(this.pieOptions, true)
      }
    }
  }
}
</script>

<style lang="less" scoped>
.root {
  padding: 10px;
  font-size: 14px;
  color: #5e6d82;
  background-color: #fff;
}
</style>
