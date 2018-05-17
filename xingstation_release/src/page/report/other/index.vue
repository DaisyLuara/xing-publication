<template>
  <div class="root">
    <div class="bottom" id="echart" ref="mychart" style="height: 900px; width: 70%"></div>
    <div class="bottom" id="echart2" ref="mychart" style="height: 900px; width: 70%"></div>
  </div>
</template>

<script>
import echarts from 'echarts/lib/echarts'
import echartsGl from 'echarts-gl'
export default {
  data() {
    return {
      option: {
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
      option2: {
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
    var dom = document.getElementById('echart')
    var myChart = echarts.init(dom)
    if (this.option && typeof this.option === 'object') {
      myChart.setOption(this.option, true)
    }

    var dom2 = document.getElementById('echart2')
    var myChart2 = echarts.init(dom2)
    if (this.option2 && typeof this.option2 === 'object') {
      myChart2.setOption(this.option2, true)
    }
  }
}
</script>

<style lang="less" scoped>
.iOdometer {
  font-size: 2em;
  margin: 0;
}
.root {
  padding: 10px;
  font-size: 14px;
  color: #5e6d82;
  border-radius: 15px;
  overflow: hidden;
  .bottom {
    min-height: 900px;
    width: 100%;
    padding: 5%;
    background-color: #fff;
  }
}
</style>
