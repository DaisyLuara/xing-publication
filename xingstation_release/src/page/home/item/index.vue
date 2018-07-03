<template>
  <div class="home-wrap" v-loading="loading">
    <!-- 搜索 -->
    <div class="search-wrap">
      <el-date-picker
      v-model="dataValue"
      type="daterange"
      align="right"
      unlink-panels
      start-placeholder="开始日期"
      end-placeholder="结束日期"
      :default-value="dataValue"
      :clearable="false"
      :picker-options="pickerOptions2"
      @change="dateChangeHandle">
      </el-date-picker>
    </div>
    <!-- 活跃数 -->
    <div class="tendency-wrap">
      <el-card shadow="always" v-loading="activeFlag">
        <highcharts :options="activeOptions" class="highchart" ref="activeChar"></highcharts>
      </el-card>
    </div>
    <!-- 点位前10 -->
    <div class="ranking-wrap">
      <el-card shadow="always" v-loading="pointFlag">
        <highcharts :options="pointTenOptions" class="highchart" ref="pointTenChar"></highcharts> 
      </el-card>
    </div>
    <!-- 行业块 -->
    <div class="ranking-wrap">
      <el-card>
        <el-row :gutter="20">
          <!-- 行业前5 -->
          <el-col :span="16" v-loading="projectFlag">
            <highcharts :options="projectFiveOptions" class="highchart" ref="projectFiveChar"></highcharts>
          </el-col>
          <!-- 业态年龄场景 -->
          <el-col :span="8" v-loading="userFlag">
            <highcharts :options="userOptions" class="highchart" ref="userChar"></highcharts>
          </el-col>
        </el-row>
      </el-card>
    </div>
    <!-- 时间段与人群特征 -->
    <div class="ranking-wrap">
      <el-card shadow="always" v-loading="timeFlag">
        <highcharts :options="timeOptions" class="highchart" ref="timeChar"></highcharts> 
      </el-card>
    </div>
  </div>
</template>
<script>
import {
  Tabs,
  TabPane,
  Button,
  Row,
  Col,
  Card,
  DatePicker,
  Dialog
} from 'element-ui'
import Vue from 'vue'
import Highcharts from 'highcharts'
import VueHighcharts from 'vue-highcharts'
Vue.use(VueHighcharts)

import chartData from 'service/chart'

export default {
  components: {
    ElRow: Row,
    ElCol: Col,
    ElCard: Card,
    ElDialog: Dialog,
    ElDatePicker: DatePicker
  },
  computed: {},
  data() {
    return {
      dialogVisible: false,
      genderDialogFlag: false,
      maleFlag: true,
      femaleFlag: true,
      loading: false,
      dataValue: [
        new Date().getTime() - 3600 * 1000 * 24 * 7,
        new Date().getTime()
      ],
      pickerOptions2: {
        shortcuts: [
          {
            text: '今天',
            onClick(picker) {
              const end = new Date()
              const start = new Date()
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '昨天',
            onClick(picker) {
              const end = new Date()
              const start = new Date()
              start.setTime(start.getTime() - 3600 * 1000 * 24)
              end.setTime(end.getTime() - 3600 * 1000 * 24)
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '最近一周',
            onClick(picker) {
              const end = new Date()
              const start = new Date()
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7)
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '最近一个月',
            onClick(picker) {
              const end = new Date()
              const start = new Date()
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30)
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '最近三个月',
            onClick(picker) {
              const end = new Date()
              const start = new Date()
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90)
              picker.$emit('pick', [start, end])
            }
          }
        ]
      },
      activeOptions: {
        chart: {
          zoomType: 'xy'
        },
        title: {
          text: '月活用户指数MAU',
          align: 'left'
        },
        credits: {
          enabled: false
        },
        xAxis: [
          {
            type: 'category',
            crosshair: true
          }
        ],
        colors: ['#E4AB00', '#1AB1CE'],
        yAxis: [
          {
            // Primary yAxis
            labels: {
              format: '{value} %',
              style: {
                color: '#1AB1CE'
              }
            },
            title: {
              text: '环比变量',
              style: {
                color: '#1AB1CE'
              }
            }
          },
          {
            // Secondary yAxis
            title: {
              text: '月活用户数',
              style: {
                color: '#E4AB00'
              }
            },
            labels: {
              format: '{value}',
              style: {
                color: '#E4AB00'
              }
            },
            opposite: true
          }
        ],
        tooltip: {
          shared: true
        },
        legend: {
          align: 'left',
          verticalAlign: 'top',
          y: 30
        },
        series: [
          {
            name: '月活用户数',
            type: 'column',
            yAxis: 1,
            data: []
          },
          {
            name: '环比变量',
            type: 'spline',
            data: []
          }
        ]
      },
      pointTenOptions: {
        chart: {
          type: 'bar'
        },
        title: {
          text: '点位人气TOP10',
          align: 'left',
          style: { fontSize: '20px' }
        },
        subtitle: {},
        colors: ['#ed1e79', '#0071bc'],
        xAxis: {
          crosshair: true,
          title: {
            text: ''
          },
          labels: {
            autoRotationLimit: 40
            // formatter: function() {
            //   if (typeof this.value !== 'number') {
            //     return this.value.substring(0, 5) + '...'
            //   }
            // }
          }
        },
        yAxis: {
          min: 0,
          title: null,
          stackLabels: {
            enabled: true,
            style: {
              fontWeight: 'bold',
              color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
          }
        },
        tooltip: {
          headerFormat: '<b>{point.x}</b><br/>',
          pointFormat:
            '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>'
        },
        plotOptions: {
          series: {
            stacking: 'normal'
          },
          bar: {
            dataLabels: {
              enabled: false
            }
          }
        },
        legend: {
          align: 'left',
          verticalAlign: 'top',
          y: 30,
          reversed: true
        },
        credits: {
          enabled: false
        },
        series: [
          {
            name: '女',
            data: []
          },
          {
            name: '男',
            data: []
          }
        ]
      },
      projectFiveOptions: {
        chart: {
          type: 'bar'
        },
        title: {
          text: '场景行业结构前5位',
          align: 'left',
          style: { fontSize: '20px' }
        },
        subtitle: {},
        xAxis: {
          crosshair: true,
          type: 'category',
          title: {
            text: ''
          },
          labels: {
            autoRotationLimit: 40
          }
        },
        yAxis: {
          min: 0,
          title: null,
          labels: {
            overflow: 'justify'
          }
        },
        tooltip: {},
        plotOptions: {
          bar: {
            dataLabels: {
              enabled: true
            }
          }
        },
        legend: false,
        credits: {
          enabled: false
        },
        series: [
          {
            color: '#00a99d',
            name: '数量'
          }
        ]
      },
      userOptions: {
        chart: {
          animation: {
            duration: 1000
          },
          type: 'pie',
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false
        },
        colors: ['#8cc63f', '#fbb03b', '#ed1e79', '#662d91'],
        tooltip: {
          headerFormat: '{性别访问数}<br>',
          pointFormat:
            '{point.name}: <b>{point.y} 占比{point.percentage:.1f}%</b>'
        },
        plotOptions: {
          pie: {
            minPointSize: 10,
            innerSize: '20%',
            zMin: 0,
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: true,
              format: '{point.name}{point.percentage:.1f}% '
            },
            showInLegend: true
          }
        },
        title: {
          text: '业态场景用户结构',
          style: { fontSize: '20px' },
          align: 'left'
        },
        legend: {
          align: 'left',
          verticalAlign: 'top',
          y: 30,
          reversed: false
        },
        credits: {
          enabled: false
        },
        yAxis: [
          {
            title: {
              text: ''
            }
          }
        ],
        series: [
          {
            type: 'pie',
            name: '性别访问数'
          }
        ]
      },
      timeOptions: {
        title: {
          text: '时间段与人群特征',
          align: 'left'
        },
        xAxis: {
          type: 'category',
          crosshair: true
        },
        yAxis: [
          {
            // Primary yAxis
            labels: {
              format: '{value}',
              style: {
                color: Highcharts.getOptions().colors[1]
              }
            },
            title: {
              text: '年龄数量',
              style: {
                color: Highcharts.getOptions().colors[1]
              }
            }
          },
          {
            // Secondary yAxis
            title: {
              text: '女性百分比',
              style: {
                color: '#ED1E79'
              }
            },
            labels: {
              format: '{value}%',
              style: {
                color: '#ED1E79'
              }
            },
            opposite: true
          }
        ],
        legend: {
          align: 'left',
          verticalAlign: 'top',
          y: 30
        },
        plotOptions: {
          series: {
            stacking: 'normal'
          }
        },
        credits: {
          enabled: false
        },
        series: [
          {
            type: 'column',
            name: '00后',
            color: '#8CC63F',
            data: []
          },
          {
            type: 'column',
            color: '#FBB03B',
            name: '90后',
            data: []
          },
          {
            type: 'column',
            name: '80后',
            color: '#F15A24',
            data: []
          },
          {
            type: 'column',
            name: '70后',
            color: '#662D91',
            data: []
          },
          {
            type: 'spline',
            name: '女',
            color: '#ED1E79',
            data: [],
            yAxis: 1,
            marker: {
              lineWidth: 2,
              lineColor: Highcharts.getOptions().colors[3],
              fillColor: '#ED1E79'
            }
          }
        ]
      },
      timeFlag: false,
      userFlag: false,
      ageFlag: false,
      projectFlag: false,
      pointFlag: false,
      activeFlag: false
    }
  },
  mounted() {},
  created() {
    this.dateChangeHandle()
  },
  methods: {
    handleClose() {
      this.dialogVisible = false
    },
    dateChangeHandle() {
      this.userFlag = true
      this.getSceneFiveChartData()
      this.getPointTenChartData()
      this.getTimeChartData()
      this.getAgeChartData()
      this.getActiveChartData()
    },
    // 业态前5
    getSceneFiveChartData() {
      this.getChartData('scene', '3')
    },
    getPointTenChartData() {
      this.getChartData('point', '2')
    },
    getTimeChartData() {
      this.getChartData('time', '8')
    },
    getAgeChartData(attributeId, name) {
      if (attributeId) {
        // 行业年龄
        this.getChartData('user', '4', attributeId, name)
      } else {
        this.getChartData('age', '4')
      }
    },
    getActiveChartData() {
      this.getChartData('active', '9')
    },
    getChartData(type, id, charType, name) {
      let args = {
        start_date: this.handleDateTransform(this.dataValue[0]),
        end_date: this.handleDateTransform(
          new Date(this.dataValue[1]).getTime()
        ),
        home_page: true
      }
      switch (id) {
        // 活跃度
        case '9':
          args.id = '9'
          this.activeFlag = true
          break
        // 点位
        case '2':
          args.id = '2'
          this.pointFlag = true
          break
        //行业前5
        case '3':
          args.id = '3'
          this.projectFlag = true
          break
        //时间
        case '8':
          args.id = '8'
          this.timeFlag = true
          break
        // 年龄
        case '4':
          args.id = '4'
          args.attribute_id = charType
          if (!charType) {
            delete args.attribute_id
            this.ageFlag = true
          } else {
            //行业年龄
            this.userFlag = true
          }
          break
      }
      return chartData
        .getChartData(this, args)
        .then(response => {
          switch (type) {
            case 'scene':
              let projectData = []
              let projectChart = this.$refs.projectFiveChar.chart
              let _this = this
              if (response.length > 0) {
                response.map((value, key) => {
                  projectData.push({
                    name: value.display_name,
                    y: parseInt(value.count),
                    id: value.attribute_id
                  })
                })
                projectChart.update({
                  plotOptions: {
                    bar: {
                      dataLabels: {
                        enabled: true
                      }
                    },
                    series: {
                      cursor: 'pointer',
                      events: {
                        click: function(event) {
                          let attribute_id = event.point.id
                          let name = event.point.name
                          _this.getAgeChartData(attribute_id, name)
                        }
                      }
                    }
                  },
                  series: [
                    {
                      data: projectData
                    }
                  ]
                })
                this.getAgeChartData(
                  response[0].attribute_id,
                  response[0].display_name
                )
                this.projectFlag = false
              } else {
                this.projectFlag = false
                this.userFlag = false
                projectChart.update({
                  series: [
                    {
                      data: projectData
                    }
                  ]
                })
                this.$refs.userChar.chart.update({
                  title: {
                    text: '业态场景用户结构'
                  },
                  series: [
                    {
                      name: '数量',
                      data: []
                    }
                  ]
                })
              }
              break
            case 'user':
              let userChart = this.$refs.userChar.chart
              let userData = []
              this.drawSingleChart(response, userData)
              userChart.update({
                title: {
                  text: '业态场景用户结构' + '(' + name + ')'
                },
                series: [
                  {
                    name: '数量',
                    data: userData
                  }
                ]
              })
              this.userFlag = false
              break
            case 'time':
              let femalData = []
              let sevenData = []
              let zeroData = []
              let nineData = []
              let eightData = []
              let timeChart = this.$refs.timeChar.chart
              if (response.length > 0) {
                response.map((value, key) => {
                  femalData.push({
                    name: value.time,
                    y: parseFloat(value.rate)
                  })
                  zeroData.push({
                    name: value.time,
                    // y: parseFloat(value.count['00'])
                    y: parseFloat(value.count.century00)
                  })
                  nineData.push({
                    name: value.time,
                    // y: parseFloat(value.count['90'])
                    y: parseFloat(value.count.century90)
                  })
                  sevenData.push({
                    name: value.time,
                    // y: parseFloat(value.count['70'])
                    y: parseFloat(value.count.century70)
                  })
                  eightData.push({
                    name: value.time,
                    // y: parseFloat(value.count['80'])
                    y: parseFloat(value.count.century80)
                  })
                })
              }
              timeChart.update({
                series: [
                  {
                  type: 'column',
                  name: '00后',
                  color: '#8CC63F',
                  data: zeroData
                },
                {
                  type: 'column',
                  color: '#FBB03B',
                  name: '90后',
                  data: nineData
                },
                {
                  type: 'column',
                  name: '80后',
                  color: '#F15A24',
                  data: eightData
                },
                {
                  type: 'column',
                  name: '70后',
                  color: '#662D91',
                  data: sevenData
                },
                {
                  type: 'spline',
                  name: '女',
                  color: '#ED1E79',
                  data: femalData,
                  yAxis: 1,
                  marker: {
                    lineWidth: 2,
                    lineColor: '#ED1E79',
                    fillColor: '#ED1E79'
                  }
                }
                ]
              })
              this.timeFlag = false
              console.log(response)
              break
            case 'point':
              let pointMaleData = []
              let pointFemaleData = []
              let poineCategories = []
              let pointChart = this.$refs.pointTenChar.chart
              this.drawGenderChart(
                response,
                pointMaleData,
                pointFemaleData,
                poineCategories
              )
              pointChart.update({
                xAxis: {
                  categories: poineCategories
                },
                series: [
                  {
                    name: '女',
                    data: pointFemaleData
                  },
                  {
                    name: '男',
                    data: pointMaleData
                  }
                ]
              })
              this.pointFlag = false
              break
            case 'active':
              let columnData = []
              let splineData = []
              let activeChart = this.$refs.activeChar.chart
              if (response.length > 0) {
                response.map((value, key) => {
                  columnData.push({
                    name: value.month,
                    y: parseFloat(value.playernum)
                  })
                  splineData.push({
                    name: value.month,
                    y: parseFloat(value.rate)
                  })
                })
              }
              activeChart.update({
                series: [
                  {
                    name: '月活用户数',
                    type: 'column',
                    yAxis: 1,
                    data: columnData
                  },
                  {
                    name: '环比变量',
                    type: 'spline',
                    data: splineData
                  }
                ]
              })
              this.activeFlag = false
              break
          }
        })
        .catch(err => {
          switch (type) {
            case 'scene':
              this.projectFlag = false
              break
            case 'point':
              this.pointFlag = false
              break
            case 'time':
              this.timeFlag = false
              break
            case 'user':
              this.userFlag = false
              break
            case 'active':
              this.activeFlag = false
              break
          }
        })
    },
    drawGenderChart(response, dataMale, dataFemale, categories) {
      if (response.length > 0) {
        response.map((value, key) => {
          categories.push(value.display_name)
          dataMale.push(parseInt(value.count.male))
          dataFemale.push(parseInt(value.count.female))
        })
      }
    },
    drawSingleChart(response, data) {
      if (response.length > 0) {
        response.map((value, key) => {
          data.push({ name: value.display_name, y: parseInt(value.count) })
        })
      }
    },
    handleDateTransform(valueDate) {
      let date = new Date(valueDate)
      let year = date.getFullYear() + '-'
      let mouth =
        (date.getMonth() + 1 < 10
          ? '0' + (date.getMonth() + 1)
          : date.getMonth() + 1) + '-'
      let day =
        (date.getDate() < 10 ? '0' + date.getDate() : date.getDate()) + ''
      return year + mouth + day
    }
  }
}
</script>
<style lang="less" scoped>
.home-wrap {
  background-color: #fff;
  padding: 15px;
  .search-wrap {
    text-align: right;
    margin-top: 5px;
    font-size: 16px;
    align-items: center;
    margin-bottom: 10px;
  }
  .tendency-wrap,
  .ranking-wrap,
  .age-gender-wrap {
    margin-bottom: 15px;
  }
}
</style>
