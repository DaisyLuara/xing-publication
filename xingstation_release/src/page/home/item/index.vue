<template>
  <div class="home-wrap" v-loading="loading">
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
    <div class="tendency-wrap">
      <el-card shadow="always" v-loading="lookerFlag">
        <highcharts :options="pointOptions" class="highchart" ref="pointChar"></highcharts>
      </el-card>
    </div>
    <div class="ranking-wrap">
      <el-card shadow="always" v-loading="pointFlag">
        <highcharts :options="pointTenOptions" class="highchart" ref="pointTenChar"></highcharts> 
      </el-card>
    </div>
    <div class="ranking-wrap">
      <el-card>
        <el-row :gutter="20">
          <el-col :span="16" v-loading="projectFlag">
            <highcharts :options="projectFiveOptions" class="highchart" ref="projectFiveChar"></highcharts>
          </el-col>
          <el-col :span="8" v-loading="userFlag">
            <highcharts :options="userOptions" class="highchart" ref="userChar"></highcharts>
          </el-col>
        </el-row>
      </el-card>
    </div>
    <div class="age-gender-wrap">
      <el-row :gutter="20">
        <el-col :span="12">
          <el-card shadow="always"  v-loading="sexFlag">
              <highcharts :options="sexOptions" class="highchart" ref="sexPie"></highcharts>
          </el-card>
        </el-col>
        <el-col :span="12">
          <el-card shadow="always" v-loading="ageFlag">
            <highcharts :options="ageOptions" class="highchart" ref="agePie" ></highcharts>
          </el-card>
        </el-col>
      </el-row>
    </div>
    <el-dialog
      title="性别详情"
      :visible="dialogVisible"
      width="50%"
      :before-close="handleClose">
        <el-card shadow="always" v-show="!genderDialogFlag">
          <highcharts :options="maleOptions" class="highchart" ref="malePie" v-loading="maleFlag"></highcharts>
        </el-card>
        <el-card shadow="always" v-show="genderDialogFlag">
          <highcharts :options="femaleOptions" class="highchart" ref="femalePie" v-loading="femaleFlag"></highcharts>
        </el-card>
    </el-dialog>
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
import Highcharts from 'highcharts'
import loadExporting from 'highcharts/modules/exporting'
import loadExportData from 'highcharts/modules/export-data'
loadExporting(Highcharts)
loadExportData(Highcharts)

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
      pointOptions: {
        title: {
          text: '围观人数',
          style: { fontSize: '20px' },
          align: 'left'
        },
        xAxis: {
          title: {
            text: ''
          },
          type: 'category'
        },
        yAxis: [
          {
            title: {
              text: null
            }
          }
        ],
        legend: {
          enabled: false
        },
        credits: {
          enabled: false
        },
        tooltip: {
          shared: true,
          formatter: function() {
            var s = ''
            let percent = 0
            if (this.points[1]) {
              percent = new Number(
                (this.points[1].y - this.points[0].y) / this.points[0].y * 100
              ).toFixed(2)
              s = '<b>' + percent + '%' + '</b>'
            }
            for (let i = 0; i < this.points.length; i++) {
              s += '<br/>' + this.points[i].point.name + ': ' + this.y
            }
            return s
          }
        },
        plotOptions: {
          area: {
            dataLabels: {
              enabled: true
            },
            marker: {
              enabled: true,
              symbol: 'circle',
              radius: 2,
              lineWidth: 1,
              states: {
                hover: {
                  enabled: true
                }
              }
            }
          }
        },
        series: [
          {
            name: '围观人数'
          }
        ]
      },
      pointTenOptions: {
        chart: {
          type: 'bar'
        },
        title: {
          text: '点位前10名',
          align: 'left',
          style: { fontSize: '20px' }
        },
        subtitle: {},
        colors: ['#ed1e79', '#0071bc'],
        xAxis: {
          title: {
            text: ''
          },
          labels: {
            autoRotationLimit: 40,
            formatter: function() {
              if (typeof this.value !== 'number') {
                return this.value.substring(0, 5) + '...'
              }
            }
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
      sexOptions: {
        chart: {
          type: 'pie',
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false
        },
        tooltip: {
          headerFormat: '{性别访问数}<br>',
          pointFormat: '{point.name}:{point.y}'
        },
        colors: ['#ed1e79', '#0071bc'],
        plotOptions: {
          pie: {
            minPointSize: 10,
            zMin: 0,
            innerSize: '20%',
            allowPointSelect: true,
            dataLabels: {
              enabled: true,
              format: '{point.name} {point.y} 占比{point.percentage:.1f}% '
            },
            showInLegend: true
          }
        },
        title: {
          text: '性别趋势',
          style: { fontSize: '20px' },
          align: 'left'
        },
        legend: {
          align: 'right',
          verticalAlign: 'middle',
          layout: 'vertical',
          symbolHeight: 12,
          symbolWidth: 20,
          symbolRadius: 2,
          squareSymbol: false
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
      ageOptions: {
        chart: {
          type: 'column'
        },
        title: {
          text: '年龄趋势'
        },
        xAxis: {},
        yAxis: {
          min: 0,
          title: {
            text: ''
          },
          stackLabels: {
            enabled: true,
            style: {
              fontWeight: 'bold',
              color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
          }
        },
        colors: ['#ed1e79', '#0071bc'],
        legend: {
          align: 'right',
          x: -30,
          verticalAlign: 'top',
          y: 25,
          floating: true,
          backgroundColor:
            (Highcharts.theme && Highcharts.theme.background2) || 'white',
          borderColor: '#CCC',
          borderWidth: 1,
          shadow: false
        },
        credits: {
          enabled: false
        },
        tooltip: {
          headerFormat: '<b>{point.x}</b><br/>',
          pointFormat:
            '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>'
        },
        plotOptions: {
          column: {
            stacking: 'normal',
            dataLabels: {
              enabled: false
            }
          }
        },
        series: [
          {
            name: '男',
            data: []
          },
          {
            name: '女',
            data: []
          }
        ]
      },
      maleOptions: {
        chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
          type: 'pie'
        },
        colors: [
          '#b6cef9',
          '#3269b8',
          '#397ad8',
          '#4188f1',
          '#649dfa',
          '#92b5f9'
        ],
        title: {
          text: '男生年龄比例详情'
        },
        tooltip: {
          pointFormat: '{series.name}:{point.y}'
        },
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            showInLegend: true,
            dataLabels: {
              enabled: true,
              format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
          }
        },
        credits: {
          enabled: false
        },
        series: [
          {
            name: '男'
          }
        ]
      },
      femaleOptions: {
        chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
          type: 'pie'
        },
        title: {
          text: '女生年龄比例详情'
        },
        tooltip: {
          pointFormat: '{series.name}: {point.y}'
        },
        legend: {
          reversed: true
        },
        credits: {
          enabled: false
        },
        colors: [
          '#f3bdd0',
          '#ae3e6c',
          '#ca477b',
          '#e3508b',
          '#ef70a0',
          '#f19ab9'
        ],
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            showInLegend: true,
            dataLabels: {
              enabled: true,
              format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
          }
        },
        series: [
          {
            name: '女'
          }
        ]
      },
      userFlag: false,
      ageFlag: false,
      sexFlag: false,
      projectFlag: false,
      pointFlag: false,
      lookerFlag: false
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
      this.getSexChartData()
      this.getAgeChartData()
      this.getLookersChartData()
    },
    getSceneFiveChartData() {
      this.getChartData('scene', '3')
    },
    getPointTenChartData() {
      this.getChartData('point', '2')
    },
    getSexChartData() {
      this.getChartData('sex', '5')
    },
    getSingleGender(gender) {
      this.getChartData('singleGender', '5', gender)
    },
    getAgeChartData(attributeId, name) {
      if (attributeId) {
        this.getChartData('user', '4', attributeId, name)
      } else {
        this.getChartData('age', '4')
      }
    },
    getLookersChartData() {
      this.getChartData('lookers', '1')
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
        case '1':
          args.id = '1'
          this.lookerFlag = true
          break
        case '2':
          args.id = '2'
          this.pointFlag = true
          break
        case '3':
          args.id = '3'
          this.projectFlag = true
          break
        case '4':
          args.id = '4'
          args.attribute_id = charType
          if (!charType) {
            delete args.attribute_id
            this.ageFlag = true
          } else {
            this.userFlag = true
          }
          break
        case '5':
          args.id = '5'
          args.gender = charType
          if (!charType) {
            delete args.gender
            this.sexFlag = true
          } else {
            this.maleFlag = true
            this.femaleFlag = true
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
            case 'singleGender':
              this.femaleFlag = true
              this.maleFlag = true
              let singleGenderData = []
              let maleChart = this.$refs.malePie.chart
              let femaleChart = this.$refs.femalePie.chart
              this.drawSingleChart(response, singleGenderData)
              if (charType === 'female') {
                femaleChart.update({
                  series: [
                    {
                      name: '数量',
                      data: singleGenderData
                    }
                  ]
                })
                this.femaleFlag = false
              } else {
                maleChart.update({
                  series: [
                    {
                      name: '数量',
                      data: singleGenderData
                    }
                  ]
                })
                this.maleFlag = false
              }
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
            case 'sex':
              let sexData = []
              let sexChart = this.$refs.sexPie.chart
              this.drawSingleChart(response, sexData)
              let that = this
              sexChart.update({
                plotOptions: {
                  pie: {
                    innerSize: '20%',
                    allowPointSelect: true,
                    dataLabels: {
                      enabled: true,
                      format:
                        '{point.name} {point.y} 占比{point.percentage:.1f}% '
                    },
                    showInLegend: true
                  },
                  series: {
                    cursor: 'pointer',
                    events: {
                      click: function(event) {
                        let name = event.point.name
                        if (name === '女') {
                          that.maleFlag = true
                          that.genderDialogFlag = true
                          that.getSingleGender('female')
                        } else {
                          that.femaleFlag = true
                          that.getSingleGender('male')
                          that.genderDialogFlag = false
                        }
                        that.dialogVisible = true
                      }
                    }
                  }
                },
                series: [
                  {
                    data: sexData
                  }
                ]
              })
              this.sexFlag = false
              break
            case 'age':
              let ageMaleData = []
              let ageFemaleData = []
              let ageCategories = []
              let ageChart = this.$refs.agePie.chart
              this.drawGenderChart(
                response,
                ageMaleData,
                ageFemaleData,
                ageCategories
              )
              ageChart.update({
                xAxis: {
                  categories: ageCategories
                },
                series: [
                  {
                    name: '女',
                    data: ageFemaleData
                  },
                  {
                    name: '男',
                    data: ageMaleData
                  }
                ]
              })
              this.ageFlag = false
              break
            case 'lookers':
              let lookersData = []
              let lookerChart = this.$refs.pointChar.chart
              this.drawSingleChart(response, lookersData)
              lookerChart.update({
                series: [
                  {
                    type: 'area',
                    lineWidth: 1,
                    color: '#649dfa',
                    fillOpacity: 0.5,
                    zIndex: 0,
                    marker: {
                      enabled: true
                    },
                    data: lookersData
                  }
                ]
              })
              this.lookerFlag = false
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
            case 'sex':
              this.sexFlag = false
              break
            case 'age':
              this.ageFlag = false
              break
            case 'lookers':
              this.lookerFlag = false
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
