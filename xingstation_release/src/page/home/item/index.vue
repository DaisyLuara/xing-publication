<template>
  <div 
    v-loading="loading"
    class="home-wrap">
    <!-- 搜索 -->
    <div 
      class="search-wrap">
      <el-date-picker
        v-model="dataValue"
        :default-value="dataValue"
        :clearable="false"
        :picker-options="pickerOptions2"
        type="daterange"
        align="right"
        unlink-panels
        start-placeholder="开始日期"
        end-placeholder="结束日期"
        @change="dateChangeHandle"/>
    </div>
    <!-- 点位前10 -->
    <div 
      class="ranking-wrap">
      <el-card 
        v-loading="pointFlag"
        shadow="always" >
        <Highcharts 
          ref="pointTenChar"
          :options="pointTenOptions" 
          class="highchart"/>
      </el-card>
    </div>
    <!-- 行业模块 -->
    <div 
      class="ranking-wrap">
      <el-card>
        <el-row 
          :gutter="20">
          <!-- 行业前5 -->
          <el-col 
            v-loading="projectFlag"
            :span="16" >
            <Highcharts 
              ref="projectFiveChar"
              :options="projectFiveOptions" 
              class="highchart" />
          </el-col>
          <!-- 业态年龄场景 -->
          <el-col 
            v-loading="userFlag"
            :span="8" >
            <Highcharts 
              ref="userChar"
              :options="userOptions" 
              class="highchart"/>
          </el-col>
        </el-row>
      </el-card>
    </div>
    <!-- 活跃数 -->
    <div 
      class="tendency-wrap">
      <el-card 
        v-loading="activeFlag"
        shadow="always">
        <Highcharts 
          ref="activeChar"
          :options="activeOptions" 
          class="highchart" />
      </el-card>
    </div>
  </div>
</template>
<script>
import {
  Button,
  Row,
  Col,
  Card,
  DatePicker,
} from 'element-ui'
import Highcharts from 'highcharts';
import { genComponent } from 'vue-highcharts';
import { getHomeChartData } from 'service'

export default {
  components: {
    ElRow: Row,
    ElCol: Col,
    ElCard: Card,
    ElDatePicker: DatePicker,
    Highcharts: genComponent('Highcharts', Highcharts),
  },
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
          type: 'column'
        },
        title: {
          text: '场地月活指数MAU TOP15 (共 0 个场地)',
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
            min: 0,
            title: {
              text: '月活用户数'
            }
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
            data: []
          }
        ]
      },
      pointTenOptions: {
        chart: {
          type: 'bar'
        },
        title: {
          text: '人气TOP10',
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
          }
        },
        yAxis: {
          min: 0,
          title: null,
          stackLabels: {
            enabled: true,
            style: {
              fontWeight: 'bold',
              color: 'gray'
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
          text: '场景业态热度TOP5',
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
        colors: ['#3b9aca', '#8cc63f', '#fbb03b', '#ed1e79', '#662d91'],
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
          text: '场景业态用户构成',
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
      userFlag: false,
      ageFlag: false,
      projectFlag: false,
      pointFlag: false,
      activeFlag: false
    }
  },
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
      this.getAgeChartData()
      this.getActiveChartData()
    },
    // 业态前5
    getSceneFiveChartData() {
      this.getHomeChartData('scene', '3')
    },
    getPointTenChartData() {
      this.getHomeChartData('point', '2')
    },
    getAgeChartData(attributeId, name) {
      if (attributeId) {
        // 行业年龄
        this.getHomeChartData('user', '4', attributeId, name)
      } else {
        this.getHomeChartData('age', '4')
      }
    },
    getActiveChartData() {
      this.getHomeChartData('active', '9')
    },
    getHomeChartData(type, id, charType, name) {
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
      return getHomeChartData(this, args)
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
                    text: '场景业态用户构成'
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
                  text: '场景业态用户构成' + '(' + name + ')'
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
              let monthData = []
              let activeChart = this.$refs.activeChar.chart
              let data = response.data
              let market_num = response.market_num
              if (data.length > 0) {
                data.map((value, key) => {
                  monthData.push({
                    name: value.display_name,
                    y: parseFloat(value.count)
                  })
                })
              }
              activeChart.update({
                title: {
                  text: '场地月活指数MAU TOP15 (共' + market_num + '个场地)',
                  align: 'left'
                },
                series: [
                  {
                    name: '月活用户数',
                    data: monthData
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
              this.userFlag = false
              break
            case 'point':
              this.pointFlag = false
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
