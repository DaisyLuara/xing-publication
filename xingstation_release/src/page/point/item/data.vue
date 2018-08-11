<template>
  <div  
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText"
    class="point-data-wrapper">
    <div 
      class="headline-wrapper">
      <el-date-picker
        v-model="dateTime"
        :default-value="dateTime" 
        :picker-options="pickerOptions2" 
        :clearable="false" 
        type="daterange"
        start-placeholder="开始日期"
        end-placeholder="结束日期"
        style="width:230px"/>
    </div>
    <div 
      id="echart" 
      ref="mychart" 
      style="height: 100%"
      class="bottom" />
  </div>
</template>
<script>
import stats from 'service/stats'
import { Row, Col, DatePicker, Select, Option, Button } from 'element-ui'
import echarts from 'echarts/lib/echarts'
import echartsGl from 'echarts-gl'

export default {
  components: {
    'el-row': Row,
    'el-col': Col,
    'el-date-picker': DatePicker,
    'el-select': Select,
    'el-button': Button,
    'el-option': Option
  },
  data() {
    return {
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: '拼命加载中'
      },
      dateTime: [
        new Date().getTime() - 3600 * 1000 * 24 * 6,
        new Date().getTime()
      ],
      pickerOptions2: {
        disabledDate(time) {
          return time.getTime() < new Date('2016-12-31')
        }
      },
      option: {
        tooltip: {},
        visualMap: {
          max: 20,
          inRange: {
            color: [
              '#313695',
              '#4575b4',
              '#74add1',
              '#abd9e9',
              '#e0f3f8',
              '#ffffbf',
              '#fee090',
              '#fdae61',
              '#f46d43',
              '#d73027',
              '#a50026'
            ]
          }
        },
        xAxis3D: {
          type: 'category',
          data: [
            '12a',
            '1a',
            '2a',
            '3a',
            '4a',
            '5a',
            '6a',
            '7a',
            '8a',
            '9a',
            '10a',
            '11a',
            '12p',
            '1p',
            '2p',
            '3p',
            '4p',
            '5p',
            '6p',
            '7p',
            '8p',
            '9p',
            '10p',
            '11p'
          ]
        },
        yAxis3D: {
          type: 'category',
          data: [
            'Saturday',
            'Friday',
            'Thursday',
            'Wednesday',
            'Tuesday',
            'Monday',
            'Sunday'
          ]
        },
        zAxis3D: {
          type: 'value'
        },
        grid3D: {
          boxWidth: 200,
          boxDepth: 80,
          light: {
            main: {
              intensity: 1.2
            },
            ambient: {
              intensity: 0.3
            }
          }
        },
        series: [
          {
            type: 'bar3D',
            data: [
              [0, 0, 5],
              [0, 1, 1],
              [0, 2, 0],
              [0, 3, 0],
              [0, 4, 0],
              [0, 5, 0],
              [0, 6, 0],
              [0, 7, 0],
              [0, 8, 0],
              [0, 9, 0],
              [0, 10, 0],
              [0, 11, 2],
              [0, 12, 4],
              [0, 13, 1],
              [0, 14, 1],
              [0, 15, 3],
              [0, 16, 4],
              [0, 17, 6],
              [0, 18, 4],
              [0, 19, 4],
              [0, 20, 3],
              [0, 21, 3],
              [0, 22, 2],
              [0, 23, 5],
              [1, 0, 7],
              [1, 1, 0],
              [1, 2, 0],
              [1, 3, 0],
              [1, 4, 0],
              [1, 5, 0],
              [1, 6, 0],
              [1, 7, 0],
              [1, 8, 0],
              [1, 9, 0],
              [1, 10, 5],
              [1, 11, 2],
              [1, 12, 2],
              [1, 13, 6],
              [1, 14, 9],
              [1, 15, 11],
              [1, 16, 6],
              [1, 17, 7],
              [1, 18, 8],
              [1, 19, 12],
              [1, 20, 5],
              [1, 21, 5],
              [1, 22, 7],
              [1, 23, 2],
              [2, 0, 1],
              [2, 1, 1],
              [2, 2, 0],
              [2, 3, 0],
              [2, 4, 0],
              [2, 5, 0],
              [2, 6, 0],
              [2, 7, 0],
              [2, 8, 0],
              [2, 9, 0],
              [2, 10, 3],
              [2, 11, 2],
              [2, 12, 1],
              [2, 13, 9],
              [2, 14, 8],
              [2, 15, 10],
              [2, 16, 6],
              [2, 17, 5],
              [2, 18, 5],
              [2, 19, 5],
              [2, 20, 7],
              [2, 21, 4],
              [2, 22, 2],
              [2, 23, 4],
              [3, 0, 7],
              [3, 1, 3],
              [3, 2, 0],
              [3, 3, 0],
              [3, 4, 0],
              [3, 5, 0],
              [3, 6, 0],
              [3, 7, 0],
              [3, 8, 1],
              [3, 9, 0],
              [3, 10, 5],
              [3, 11, 4],
              [3, 12, 7],
              [3, 13, 14],
              [3, 14, 13],
              [3, 15, 12],
              [3, 16, 9],
              [3, 17, 5],
              [3, 18, 5],
              [3, 19, 10],
              [3, 20, 6],
              [3, 21, 4],
              [3, 22, 4],
              [3, 23, 1],
              [4, 0, 1],
              [4, 1, 3],
              [4, 2, 0],
              [4, 3, 0],
              [4, 4, 0],
              [4, 5, 1],
              [4, 6, 0],
              [4, 7, 0],
              [4, 8, 0],
              [4, 9, 2],
              [4, 10, 4],
              [4, 11, 4],
              [4, 12, 2],
              [4, 13, 4],
              [4, 14, 4],
              [4, 15, 14],
              [4, 16, 12],
              [4, 17, 1],
              [4, 18, 8],
              [4, 19, 5],
              [4, 20, 3],
              [4, 21, 7],
              [4, 22, 3],
              [4, 23, 0],
              [5, 0, 2],
              [5, 1, 1],
              [5, 2, 0],
              [5, 3, 3],
              [5, 4, 0],
              [5, 5, 0],
              [5, 6, 0],
              [5, 7, 0],
              [5, 8, 2],
              [5, 9, 0],
              [5, 10, 4],
              [5, 11, 1],
              [5, 12, 5],
              [5, 13, 10],
              [5, 14, 5],
              [5, 15, 7],
              [5, 16, 11],
              [5, 17, 6],
              [5, 18, 0],
              [5, 19, 5],
              [5, 20, 3],
              [5, 21, 4],
              [5, 22, 2],
              [5, 23, 0],
              [6, 0, 1],
              [6, 1, 0],
              [6, 2, 0],
              [6, 3, 0],
              [6, 4, 0],
              [6, 5, 0],
              [6, 6, 0],
              [6, 7, 0],
              [6, 8, 0],
              [6, 9, 0],
              [6, 10, 1],
              [6, 11, 0],
              [6, 12, 2],
              [6, 13, 1],
              [6, 14, 3],
              [6, 15, 4],
              [6, 16, 0],
              [6, 17, 0],
              [6, 18, 0],
              [6, 19, 0],
              [6, 20, 1],
              [6, 21, 2],
              [6, 22, 2],
              [6, 23, 6]
            ].map(function(item) {
              return {
                value: [item[1], item[0], item[2]]
              }
            }),
            shading: 'color',
            label: {
              show: false,
              textStyle: {
                fontSize: 16,
                borderWidth: 1
              }
            },
            itemStyle: {
              opacity: 1
            },
            emphasis: {
              label: {
                textStyle: {
                  fontSize: 20,
                  color: '#900'
                }
              },
              itemStyle: {
                color: '#900'
              }
            }
          }
        ]
      },
      myChart: null
    }
  },
  mounted() {
    var dom = document.getElementById('echart')
    var myChart = echarts.init(dom)
    if (this.option && typeof this.option === 'object') {
      myChart.setOption(this.option, true)
    }
  }
}
</script>
<style lang="less" scoped>
.point-data-wrapper {
  // padding: 15px;
  .headline-wrapper {
    padding: 20px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    font-size: 16px;
    background-color: #fff;
  }
  .bottom {
    min-height: 800px;
    width: 100%;
    background-color: #fff;
  }
  .content-wrapper {
    padding: 15px;
    background-color: #fff;
    .btns-wrapper {
      min-height: 170px;
      padding: 10px 0;
      display: flex;
      flex-direction: row;
      flex-wrap: wrap;
      li {
        padding-right: 95px;
        padding-top: 20px;
      }
      .btn {
        cursor: pointer;
        width: 145px;
        height: 145px;
        display: block;
        border-radius: 5px;
        background: url('~assets/images/program/circle.png') center 35px
          no-repeat #f6f6f6;
        position: relative;
        .title {
          display: block;
          height: 35px;
          line-height: 35px;
          padding-left: 20px;
          font-size: 14px;
          color: #999;
          font-weight: 600;
          font-style: normal;
        }
        .count {
          display: block;
          text-align: center;
          height: 30px;
          padding-top: 40px;
          font-size: 15px;
          color: #517ebb;
        }
        .arrow-icon {
          position: absolute;
          z-index: 2;
          top: 145px;
          left: 66px;
          display: none;
          width: 17px;
          height: 9px;
          background: url('~assets/images/program/arrow.png') 50% no-repeat;
        }
        .right-arrow-icon {
          position: absolute;
          z-index: 2;
          text-align: center;
          color: #fff;
          line-height: 34px;
          top: 63px;
          right: -90px;
          width: 82px;
          height: 34px;
          background: url('~assets/images/program/right-arrow.png') 50%
            no-repeat;
        }
      }
      .active,
      .btn:hover {
        .title {
          color: #fff;
        }
        .arrow-icon {
          display: block;
        }
        background-color: #517ebb;
      }
    }
    .chart {
      padding-top: 30px;
      width: 100%;
      .highcharts-container {
        // width: 100%;
      }
    }
  }
  .pie-content-wrapper {
    margin-top: 15px;
    .pie-sex-wrapper {
      background-color: #fff;
      width: 100%;
      .headline-wrapper {
        padding: 20px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 16px;
        background-color: #fff;
        color: #444;
      }
      .pie-sex-content {
        .highchart {
          .highcharts-container {
            overflow: hidden !important;
          }
        }
      }
    }
    .pie-age-wrapper {
      background-color: #fff;
      .headline-wrapper {
        padding: 20px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 16px;
        background-color: #fff;
        color: #444;
      }
    }
  }
}
</style>

