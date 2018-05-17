<template>
  <div class="root">

    <el-row :gutter="20">
      <el-col :span="12">
        <el-card shadow="always">
          <highcharts :options="pieOptions" class="highchart" ref="piechar"></highcharts>
        </el-card>
      </el-col>
      <el-col :span="12">
        <el-card shadow="always">
          <highcharts :options="funnelOptions" class="highchart" ref="funnelchar"></highcharts>
        </el-card>
      </el-col>
    </el-row>
    <el-row :gutter="20">
      <el-col :span="12">
        <el-card shadow="always">
          <div class="" id="echart" ref="mychart" style="height: 900px; width: 100%"></div>
        </el-card>
      </el-col>
      <el-col :span="12">
        <el-card shadow="always">
          <div class="" id="echart2" ref="mychart" style="height: 900px; width: 100%"></div>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import echarts from 'echarts/lib/echarts'
import echartsGl from 'echarts-gl'

import { Tabs, TabPane, Button, Row, Col, Card, DatePicker } from 'element-ui'

import Highcharts from 'highcharts'
import loadExporting from 'highcharts/modules/exporting'
import loadFunnel from 'highcharts/modules/funnel'
loadExporting(Highcharts)
loadFunnel(Highcharts)

export default {
  data() {
    return {
      pieOptions: {
        title: {
          text: '屏幕总数 381 台'
        },
        xAxis: {
          categories: [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
          ]
        },
        plotOptions: {
          pie: {
            // innerSize: 100,
            innerSize: '20%',
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: true,
              format: '{point.name} {point.y} '
            },
            showInLegend: true
          }
        },
        colors: ['#e6861c', '#38449a', '#c0002b'],
        legend: {
          enabled: false
        },
        credits: {
          enabled: false
        },
        series: [
          {
            type: 'pie',
            allowPointSelect: true,
            keys: ['name', 'y', 'selected', 'sliced'],
            data: [
              // ['屏幕总数', 381, true, true],
              ['总人数', 2011386, , true, true],
              ['人脸总张数', 4778197, false],
              ['曝光人数', 20215890, false]
            ],
            showInLegend: true
          }
        ]
      },
      funnelOptions: {
        chart: {
          type: 'funnel'
        },
        title: {
          text: '总互动时间 4453 分钟'
        },
        colors: ['#e6861c', '#38449a', '#c0002b'],
        plotOptions: {
          series: {
            dataLabels: {
              enabled: true,
              format: '<b>{point.name} {point.y:,.0f}</b> ',
              color:
                (Highcharts.theme && Highcharts.theme.contrastTextColor) ||
                'black',
              softConnector: true
            },
            center: ['40%', '50%'],
            neckWidth: '0%',
            neckHeight: '0%',
            width: '80%'
          }
        },
        legend: {
          enabled: false
        },
        credits: {
          enabled: false
        },
        series: [
          {
            name: 'Unique users',
            data: [
              ['围观总数', 9139],
              ['互动总数', 5463],
              ['扫码拉新总数', 2434]
            ]
          }
        ]
      },
      barOption: {
        title: {
          text: '每屏互动指标',
          // subtext: '每座屏互动时间2138(分钟)',
          textStyle: {
            fontSize: 20
          },
          subtextStyle: {
            fontSize: 16
          }
        },
        legend: {
          data: ['日均', '总数'],
          textStyle: {
            fontSize: 18
          }
        },
        xAxis: {
          type: 'category',
          data: ['每座屏围观数', '每座屏互动总数', '每座屏扫码拉新总数']
        },
        yAxis: {
          type: 'value'
        },
        color: ['#e6861c', '#38449a', '#c0002b'],
        series: [
          {
            name: '日均',
            label: {
              normal: {
                show: true,
                position: 'insideTop'
              }
            },
            data: [76, 41, 10],
            type: 'bar'
          },
          {
            name: '总数',
            label: {
              normal: {
                show: true,
                position: 'insideTop'
              }
            },
            data: [5279, 2862, 692],
            type: 'bar'
          }
        ],
        toolbox: {
          feature: {
            dataView: { readOnly: false },
            restore: {},
            saveAsImage: {}
          }
        }
      },
      barOption2: {
        title: {
          text: '携程每屏互动指标',
          // subtext: '每座屏互动时间495(分钟)',
          textStyle: {
            fontSize: 20
          },
          subtextStyle: {
            fontSize: 16
          }
        },
        legend: {
          data: ['日均', '总数'],
          textStyle: {
            fontSize: 18
          }
        },
        xAxis: {
          type: 'category',
          data: ['每座屏围观数', '每座屏互动总数', '每座屏扫码拉新总数']
        },
        yAxis: {
          type: 'value'
        },
        color: ['#e6861c', '#38449a', '#c0002b'],
        series: [
          {
            name: '日均',
            label: {
              normal: {
                show: true,
                position: 'insideTop'
              }
            },
            data: [239, 140, 62],
            type: 'bar'
          },
          {
            name: '总数',
            label: {
              normal: {
                show: true,
                position: 'insideTop'
              }
            },
            data: [1015, 607, 270],
            type: 'bar'
          }
        ],
        toolbox: {
          feature: {
            dataView: { readOnly: false },
            restore: {},
            saveAsImage: {}
          }
        }
      },
      myChart: null,
      myChart2: null
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
      let dom = document.getElementById('echart')
      let myChart = echarts.init(dom)
      if (this.barOption && typeof this.barOption === 'object') {
        myChart.setOption(this.barOption, true)
      }

      let dom2 = document.getElementById('echart2')
      let myChart2 = echarts.init(dom2)
      if (this.barOption2 && typeof this.barOption2 === 'object') {
        myChart2.setOption(this.barOption2, true)
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
