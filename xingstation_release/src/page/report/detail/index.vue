<template>
  <div class="point-data-wrapper" :element-loading-text="setting.loadingText" v-loading="setting.loading">
    <!-- 搜索 -->
    <div class="search-wrap">
      <el-form ref="searchForm" class="search-form">
        <el-row :gutter="20">
          <el-col :span="8" v-if="showUser">
            <el-form-item label="" prop="user" >
              <el-select v-model="userSelect" filterable placeholder="请选择用户(可搜索)"  :loading="searchLoading" remote :remote-method="getUser" @change="userChangeHandle" clearable>
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item label="" prop="project" >
              <el-select v-model="projectSelect" filterable placeholder="请选择节目(可搜索)" :loading="searchLoading" remote :remote-method="getProject" @change="projectChangeHandle" clearable>
                <el-option
                  v-for="item in projectList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.alias">
                </el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item label="" prop="scene" >
              <el-select v-model="sceneSelect" placeholder="请选择场景" filterable  clearable>
                <el-option
                  v-for="item in sceneList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="8">
            <el-form-item label="" prop="area_id" >
              <el-select v-model="area_id" placeholder="请选择区域" filterable  clearable  @change="areaChangeHandle">
                <el-option
                  v-for="item in areaList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item label="" prop="market_id" >
              <el-select v-model="market_id" placeholder="请搜索商场" filterable :loading="searchLoading" remote :remote-method="getMarket"  @change="marketChangeHandle" clearable>
                <el-option
                  v-for="item in marketList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item label="" prop="point_id" >
              <el-select v-model="point_id" placeholder="请选择点位"   filterable :loading="searchLoading" clearable>
                <el-option
                  v-for="item in pointList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="14">
            <el-form-item label="" prop="date" >
              <el-date-picker
              v-model="dateTime"
              type="daterange"
              align="right"
              unlink-panels
              start-placeholder="开始日期"
              end-placeholder="结束日期"
              :default-value="dateTime"
              :clearable="false"
              :picker-options="pickerOptions2"
              >
              </el-date-picker>
            </el-form-item>
          </el-col>
          <el-col :span="10">
            <el-form-item>
              <el-button @click="searchHandle" type="primary" size="small">搜索</el-button>
              <el-button @click="resetSearch" size="small">重置</el-button>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
    </div>

    <!-- 主要图表部分 -->
    <div class="content-wrapper" v-loading="poepleCountFlag">
      <ul class="btns-wrapper">
        <li v-for="(item, key) in peopleCount" :key="key" v-if="item.index !== 'outnum'">
          <a 
            :class="'btn color-'+ key" 
            @hover="handleHover(key)"
            @click="lineDataHandle(item)">
            <i class="title" >
              {{item.display_name}}
            </i>
            <span class="count" v-if="item.index === 'scannum'">
              {{item.count == null ? 0 : item.count}} / {{(peopleCount[key-1].count == null) ? 0 : new Number((item.count/peopleCount[key-1].count)*100).toFixed(1)}}%
            </span>
            <span class="count" v-if="item.index !== 'scannum'">
              {{item.count == null ? 0 : item.count}}
            </span>
            <i :class="'arrow-icon color-' + key"></i>
            <i class="right-arrow-icon" v-if="key != peopleCountLength -1">{{(peopleCount[key+1].count == null) ? 0 : (item.index !== 'playernum' ? new Number((peopleCount[key+1].count/item.count)*100).toFixed(1) : new Number((peopleCount[key+2].count/item.count)*100).toFixed(1))}}%</i>
          </a>
        </li>
      </ul>
      <div class="chart-wrapper">
        <chart 
          ref="mainChart"
          :options="mainChart" 
          auto-resize />
      </div>
    </div>
    
    <!-- 漏斗图和年龄分布 -->
    <div class="transfer-sex-wrapper">
          <div class="transfer">
            <img style="width: 100%" src="~assets/images/data-bg.png" />
          </div>
          <div class="sex-age">
            <chart 
              ref="pieChart"
              :options="pieChart" 
              auto-resize />
          </div>
    </div>

    <!-- 年龄分布图 -->
    <div class="age-wrapper">
      <chart
        ref="ageChart"
        :options="ageChart"
        auto-resize />
    </div>
    <!-- 报表部分 -->
    <div v-loading="tableSetting.loading" class="table-wrap">
      <div class="actions-wrap">
        <span class="label">
          数量: {{pagination.total}}
        </span>
        <div>
          <el-select v-model="reportValue" placeholder="请选择导出报表类型">
            <el-option
              v-for="item in reportList"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
          <el-button type="success" size="small"  @click="changeReportType">下载</el-button>
        </div>
      </div>
      <el-table
        :data="tableData"
        style="width: 100%">
        <el-table-column type="expand">
          <template slot-scope="scope">
            <el-form label-position="left" inline class="demo-table-expand">
              <el-form-item label="ID">
                <span>{{ scope.row.id }}</span>
              </el-form-item>
              <el-form-item label="点位">
                  {{ scope.row.area_name }} {{scope.row.market_name}} {{scope.row.point_name}}
              </el-form-item>
              <el-form-item label="历史节目">
                  {{ scope.row.projects }}
              </el-form-item>
              <el-form-item label="围观">
                <span>{{ scope.row.looknum }}</span>
              </el-form-item>
              <el-form-item label="互动">
                <span>{{ scope.row.playernum }}</span>
              </el-form-item>
              <el-form-item label="输出">
                <span>扫码/生成数：{{ scope.row.scannum }} / {{ scope.row.outnum }} 扫码率：{{scope.row.outnum !== 0 ? new Number((scope.row.scannum / scope.row.outnum )*100).toFixed(1): 0 }}%</span>
              </el-form-item>
              <el-form-item label="拉新">
                <span>{{ scope.row.lovenum }}</span>
              </el-form-item>
              <el-form-item label="时间">
                <span>{{ scope.row.min_date }} - {{scope.row.max_date}}</span>
              </el-form-item>
            </el-form>
          </template>
        </el-table-column>
        <el-table-column
          label="ID"
          prop="id"
          width="100">
        </el-table-column>
        <el-table-column
          label="点位"
          prop="point"
          min-width="130"
          :show-overflow-tooltip="true">
          <template slot-scope="props">
            {{ props.row.area_name }} {{props.row.market_name}} {{props.row.point_name}}
          </template>
        </el-table-column>
        <el-table-column
          label="历史节目"
          prop="projects"
          min-width="130"
          :show-overflow-tooltip="true">
        </el-table-column>
        <el-table-column
          label="围观"
          prop="looknum"
          min-width="90">
        </el-table-column>
        <el-table-column
          label="互动"
          prop="playernum"
          min-width="90"
          :show-overflow-tooltip="true">
        </el-table-column>
        <el-table-column
          label="输出"
          prop="scannum"
          min-width="120"
          :show-overflow-tooltip="true">
          <template slot-scope="props">
            <span>扫码/生成数：{{ props.row.scannum }} / {{ props.row.outnum }} <br/> 扫码率：{{props.row.outnum !== 0 ? new Number((props.row.scannum / props.row.outnum) *100).toFixed(1): 0}}%</span>
          </template>
        </el-table-column>
        <el-table-column
          label="拉新"
          prop="lovenum"
          min-width="90"
          :show-overflow-tooltip="true">
        </el-table-column>
        <el-table-column
          label="时间"
          min-width="120"
          prop="created_at"
          :show-overflow-tooltip="true">
          <template slot-scope="props">
            <span>{{ props.row.min_date }} - {{props.row.max_date}}</span>
          </template>
        </el-table-column>
      </el-table>
      <div class="pagination-wrap">
        <el-pagination
        layout="prev, pager, next, jumper, total"
        :total="pagination.total"
        :page-size="pagination.pageSize"
        :current-page="pagination.currentPage"
        @current-change="changePage"
        >
        </el-pagination>
      </div>
    </div>
  </div>
</template>
<script>
import stats from 'service/stats'
import search from 'service/search'
import chart from 'service/chart'
import {
  Row,
  Col,
  DatePicker,
  Select,
  Option,
  Button,
  Form,
  FormItem,
  Table,
  TableColumn,
  Pagination,
  MessageBox
} from 'element-ui'
import Highcharts from 'highcharts'
import loadExporting from 'highcharts/modules/exporting'
import loadExportData from 'highcharts/modules/export-data'
import reportViewVue from '../reportView.vue'
import ECharts from 'vue-echarts/components/ECharts'
import 'echarts/lib/chart/line'
import 'echarts/lib/chart/pie'
import 'echarts/lib/chart/bar'
import 'echarts/lib/component/tooltip'
import 'echarts/lib/component/legend'

loadExporting(Highcharts)
loadExportData(Highcharts)

export default {
  components: {
    'el-row': Row,
    'el-col': Col,
    'el-date-picker': DatePicker,
    'el-select': Select,
    'el-button': Button,
    'el-option': Option,
    'el-form-item': FormItem,
    'el-form': Form,
    'el-table': Table,
    'el-table-column': TableColumn,
    'el-pagination': Pagination,
    chart: ECharts
  },
  data() {
    let data = []

    for (let i = 0; i <= 360; i++) {
      let t = i / 180 * Math.PI
      let r = Math.sin(2 * t) * Math.cos(2 * t)
      data.push([r, i])
    }
    return {
      tableSetting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      reportList: [
        {
          value: 'point',
          label: '点位数据'
        },
        {
          value: 'marketing',
          label: '营销成果数据'
        },
        {
          value: 'marketing_top',
          label: '营销成果Top100'
        },
        {
          value: 'daily_average',
          label: '日均数据'
        },
        {
          value: 'project_point',
          label: '节目数据'
        }
      ],
      reportValue: 'point',
      area_id: '',
      market_id: '',
      point_id: '',
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: '拼命加载中'
      },
      dateTime: [
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
      areaList: [],
      marketList: [],
      pointList: [],
      sceneList: [],
      projectSelect: '',
      sceneSelect: '',
      searchLoading: false,
      projectList: [],
      active: '围观总数',
      splineOptions: {
        chart: {
          type: 'area'
        },
        title: {
          text: null
        },
        xAxis: {
          title: {
            text: '日期'
          },
          type: 'category'
        },
        yAxis: [
          {
            title: {
              text: null
            },
            floor: 0,
            tickAmount: 5
          }
        ],
        legend: {
          enabled: false
        },
        credits: {
          enabled: false
        },
        plotOptions: {
          area: {
            dataLabels: {
              enabled: true
            },
            marker: {
              enabled: false,
              symbol: 'circle',
              radius: 2,
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
            color: '#517ebb',
            name: '数量'
          }
        ]
      },
      agePieOptions: {
        chart: {
          type: 'column'
        },
        title: {
          text: '年龄分布',
          align: 'left'
        },
        xAxis: {
          title: {
            text: '范围'
          },
          type: 'category'
        },
        plotOptions: {
          column: {
            dataLabels: {
              enabled: true
            }
          }
        },
        yAxis: [
          {
            title: {
              text: '年龄统计'
            },
            tickAmount: 5
          }
        ],
        legend: {
          enabled: false
        },
        credits: {
          enabled: false
        },
        series: [
          {
            color: '#7cb5ec',
            name: '年龄统计'
          }
        ]
      },
      sexPieOptions: {
        chart: {
          type: 'pie',
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false
        },
        title: {
          text: '性别分布',
          align: 'left'
        },
        tooltip: {
          headerFormat: '{性别访问数}<br>',
          pointFormat:
            '{point.name}: <b>{point.y} 占比{point.percentage:.1f}%</b>'
        },
        colors: ['#5eb6c8', '#ffd259'],
        plotOptions: {
          pie: {
            innerSize: '20%',
            allowPointSelect: true,
            cursor: 'pointer',
            // depth: 40,
            dataLabels: {
              enabled: true,
              format: '{point.name} {point.y} 占比{point.percentage:.1f}% '
            },
            showInLegend: true
          }
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
        series: [
          {
            type: 'pie',
            name: '性别访问数'
          }
        ]
      },
      pagination: {
        total: 0,
        pageSize: 5,
        currentPage: 1
      },
      tableData: [],
      peopleCount: [],
      type: '',
      userList: [],
      ageType: false,
      sexType: false,
      pointName: '',
      arUserId: '',
      poepleCountFlag: false,
      ageFlag: false,
      sexFlag: false,
      userSelect: '',
      projectAlias: '',
      mainChart: {
        title: {
          text: '堆叠区域图'
        },
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'cross',
            label: {
              backgroundColor: '#6a7985'
            }
          }
        },
        legend: {
          data: ['邮件营销', '联盟广告', '视频广告', '直接访问', '搜索引擎']
        },
        toolbox: {
          feature: {
            saveAsImage: {}
          }
        },
        grid: [
          {
            left: 50,
            right: 50,
            height: '35%'
          },
          {
            left: 50,
            right: 50,
            top: '47%',
            height: '35%'
          }
        ],
        xAxis: [
          {
            type: 'category',
            boundaryGap: false,
            data: ['周一', '周二', '周三', '周四', '周五', '周六']
          },
          {
            show: false,
            gridIndex: 1,
            type: 'category',
            boundaryGap: false,
            axisLine: { onZero: true },
            data: ['周一', '周二', '周三', '周四', '周五', '周六'],
            position: 'top'
          }
        ],
        yAxis: [
          {
            type: 'value'
          },
          {
            gridIndex: 1,
            type: 'value'
          }
        ],
        series: [
          {
            name: '邮件营销',
            type: 'line',
            stack: '总量',
            areaStyle: { normal: {} }
          },
          {
            name: '联盟广告',
            type: 'line',
            xAxisIndex: 1,
            yAxisIndex: 1,
            stack: '总量',
            areaStyle: { normal: {} },
            data: []
          }
        ]
      },
      pieChart: {
        title: {
          text: '天气情况统计',
          subtext: '虚构数据',
          left: 'center'
        },
        tooltip: {
          trigger: 'item',
          formatter: '{a} <br/>{b} : {c} ({d}%)'
        },
        legend: {
          // orient: 'vertical',
          // top: 'middle',
          bottom: 10,
          left: 'center',
          data: ['西凉', '益州', '兖州', '荆州', '幽州']
        },
        series: [
          {
            type: 'pie',
            radius: '65%',
            center: ['50%', '50%'],
            selectedMode: 'single',
            data: [
              {
                value: 1548,
                name: '幽州'
              },

              { value: 735, name: '西凉', selected: true }
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
      ageChart: {
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            // 坐标轴指示器，坐标轴触发有效
            type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
          }
        },
        legend: {
          data: ['直接访问', '邮件营销', '联盟广告', '视频广告', '搜索引擎']
        },
        grid: {
          left: '3%',
          right: '4%',
          bottom: '3%',
          containLabel: true
        },
        xAxis: {
          type: 'category',
          data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
        },
        yAxis: {
          type: 'value'
        },
        series: [
          {
            name: '直接访问',
            type: 'bar',
            stack: '总量',
            label: {
              normal: {
                show: true,
                position: 'insideRight'
              }
            },
            data: [320, 302, 301, 334, 390, 330, 320]
          },
          {
            name: '邮件营销',
            type: 'bar',
            stack: '总量',
            label: {
              normal: {
                show: true,
                position: 'insideRight'
              }
            },
            data: [120, 132, 101, 134, 90, 230, 210]
          },
          {
            name: '联盟广告',
            type: 'bar',
            stack: '总量',
            label: {
              normal: {
                show: true,
                position: 'insideRight'
              }
            },
            data: [220, 182, 191, 234, 290, 330, 310]
          },
          {
            name: '视频广告',
            type: 'bar',
            stack: '总量',
            label: {
              normal: {
                show: true,
                position: 'insideRight'
              }
            },
            data: [150, 212, 201, 154, 190, 330, 410]
          },
          {
            name: '搜索引擎',
            type: 'bar',
            stack: '总量',
            label: {
              normal: {
                show: true,
                position: 'insideRight'
              }
            },
            data: [820, 832, 901, 934, 1290, 1330, 1320]
          }
        ]
      }
    }
  },
  created() {
    this.setting.loading = true
    this.getSceneList()
    this.getAreaList()
    this.allPromise()
  },
  computed: {
    peopleCountLength: function() {
      return this.peopleCount.length
    },
    showUser() {
      let user_info = JSON.parse(localStorage.getItem('user_info'))
      let roles = user_info.roles.data[0].name
      return roles == 'user' ? false : true
    }
  },
  methods: {
    changeReportType() {
      if (this.reportValue === 'point') {
        if (!this.point_id) {
          this.$message({
            message: '点位数据下载，请选择点位',
            type: 'warning'
          })
        } else {
          this.getExcelData()
        }
      } else if (this.reportValue === 'project_point') {
        if (!this.projectSelect) {
          this.$message({
            message: '节目数据下载，请选择节目',
            type: 'warning'
          })
        } else {
          this.getExcelData()
        }
      } else {
        this.getExcelData()
      }
    },
    getExcelData() {
      let args = this.setArgs()
      args.type = this.reportValue
      delete args.id
      return chart
        .getExcelData(this, args)
        .then(response => {
          const a = document.createElement('a')
          a.href = response
          a.download = 'download'
          a.click()
          window.URL.revokeObjectURL(response)
        })
        .catch(err => {
          console.log(err)
        })
    },
    getAreaList() {
      return search
        .getAeraList(this)
        .then(response => {
          let data = response.data
          this.areaList = data
        })
        .catch(error => {
          console.log(error)
          this.setting.loading = false
        })
    },
    getPointList() {
      this.tableSetting.loading = true
      let args = this.setArgs()
      args.page = this.pagination.currentPage
      delete args.id
      return stats
        .getStaus(this, args)
        .then(response => {
          this.tableData = response.data
          this.pagination.total = response.meta.pagination.total
          this.setting.loading = false
          this.tableSetting.loading = false
        })
        .catch(err => {
          console.log(err)
          this.tableSetting.loading = false
          this.setting.loading = false
        })
    },
    getMarket(query) {
      this.searchLoading = true
      let args = {
        name: query,
        include: 'area',
        area_id: this.area_id
      }
      return search
        .getMarketList(this, args)
        .then(response => {
          this.marketList = response.data
          if (this.marketList.length == 0) {
            this.market_id = ''
            this.marketList = []
          }
          this.searchLoading = false
        })
        .catch(err => {
          console.log(err)
          this.searchLoading = false
        })
    },
    getPoint() {
      let args = {
        include: 'market',
        market_id: this.market_id
      }
      this.searchLoading = true
      return search
        .gePointList(this, args)
        .then(response => {
          this.pointList = response.data
          this.searchLoading = false
        })
        .catch(err => {
          this.searchLoading = false
          console.log(err)
        })
    },
    areaChangeHandle() {
      this.market_id = ''
      this.point_id = ''
      this.getMarket()
    },
    marketChangeHandle() {
      this.point_id = ''
      this.getPoint()
    },
    getSceneList() {
      return search
        .getSceneList(this)
        .then(response => {
          this.sceneList = response.data
        })
        .catch(err => {
          console.log(err)
        })
    },
    searchHandle() {
      this.pagination.currentPage = 1
      this.active = '围观总数'
      this.projectAlias = this.projectSelect
      this.setting.loading = true
      this.allPromise()
    },
    resetSearch() {
      if (this.showUser) {
        this.userSelect = ''
        this.arUserId = this.userSelect
        this.projectSelect = ''
        this.area_id = ''
        this.market_id = ''
        this.point_id = ''
        this.sceneSelect = ''
      } else {
        this.projectSelect = ''
      }
      this.setting.loading = true
      this.allPromise()
    },
    projectChangeHandle() {
      this.projectAlias = this.projectSelect
    },
    userChangeHandle() {
      this.arUserId = this.userSelect
      this.projectSelect = ''
      if (this.arUserId) {
        this.getProject('')
      }
    },
    allPromise() {
      this.getPointList()
      this.getPeopleCount()
      // this.getAge()
      // this.getGender()
      this.setting.loading = false
    },
    getUser(query) {
      let args = {
        name: query
      }
      if (query !== '') {
        this.searchLoading = true
        return search
          .getUserList(this, args)
          .then(response => {
            this.userList = response.data
            if (this.userList.length == 0) {
              this.projectList = []
              this.projectSelect = ''
            }
            this.searchLoading = false
          })
          .catch(err => {
            console.log(err)
            this.searchLoading = false
          })
      } else {
        this.userSelect = ''
        this.userList = []
        return false
      }
    },
    getProject(query) {
      let args = {
        ar_user_id: this.arUserId,
        name: query
      }
      if (this.showUser) {
        this.searchLoading = true
        if (!this.arUserId) {
          delete args.ar_user_id
        }
        return search
          .getProjectList(this, args)
          .then(response => {
            this.projectList = response.data
            this.searchLoading = false
          })
          .catch(err => {
            console.log(err)
            this.searchLoading = false
          })
      } else {
        let user_info = JSON.parse(localStorage.getItem('user_info'))
        this.arUserId = user_info.ar_user_id
        args.ar_user_id = this.arUserId
        this.searchLoading = true
        return search
          .getProjectList(this, args)
          .then(response => {
            this.projectList = response.data
            this.searchLoading = false
          })
          .catch(err => {
            console.log(err)
            this.searchLoading = false
          })
      }
    },
    getPeopleCount() {
      this.poepleCountFlag = true
      let args = this.setArgs('6')
      return chart
        .getChartData(this, args)
        .then(response => {
          this.peopleCount = response
          this.type = this.peopleCount[0].index
          this.getLineData()
        })
        .catch(err => {
          this.poepleCountFlag = false
          console.log(err)
        })
    },
    getAge() {
      this.ageFlag = true
      let args = this.setArgs('4')
      return chart
        .getChartData(this, args)
        .then(response => {
          let ageChart = this.$refs.agePie.chart
          let dataAge = []
          for (let i = 0; i < response.length; i++) {
            dataAge.push({
              name: response[i].display_name,
              y: parseInt(response[i].count)
            })
          }
          ageChart.series[0].setData(dataAge, true)
          this.ageFlag = false
        })
        .catch(err => {
          this.ageFlag = false
          console.log(err)
        })
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.getPointList()
    },
    getGender() {
      this.sexFlag = true
      let args = this.setArgs('5')
      return chart
        .getChartData(this, args)
        .then(response => {
          let genderChat = this.$refs.sexPie.chart
          let dataGender = []
          for (let i = 0; i < response.length; i++) {
            if (i == 0) {
              dataGender.push({
                name: response[i].display_name,
                y: parseInt(response[i].count),
                sliced: true,
                selected: true
              })
            } else {
              dataGender.push([
                response[i].display_name,
                parseInt(response[i].count)
              ])
            }
          }
          genderChat.series[0].setData(dataGender, true)
          this.sexFlag = false
        })
        .catch(err => {
          this.sexFlag = false
          console.log(err)
        })
    },
    setArgs(id) {
      let args = {
        id: id,
        start_date: this.handleDateTransform(this.dateTime[0]),
        end_date: this.handleDateTransform(
          new Date(this.dateTime[1]).getTime()
        ),
        alias: this.projectAlias,
        ar_user_id: this.arUserId,
        market_id: this.market_id,
        scene_id: this.sceneSelect,
        area_id: this.area_id,
        point_id: this.point_id
      }
      if (!this.projectAlias) {
        delete args.alias
      }
      if (!this.arUserId) {
        delete args.ar_user_id
      }
      if (!this.sceneSelect) {
        delete args.scene_id
      }
      if (!this.area_id) {
        delete args.area_id
      }
      if (!this.point_id) {
        delete args.point_id
      }
      if (!this.market_id) {
        delete args.market_id
      }
      return args
    },
    getLineData() {
      this.poepleCountFlag = true
      let args = this.setArgs('7')
      args.index = this.type
      return chart
        .getChartData(this, args)
        .then(response => {
          let dataLine = []
          let chart = this.$refs.mainChart
          chart.mergeOptions(this.processChartData(response))
          console.dir(chart)
          // for (let k = 0; k < response.length; k++) {
          //   dataLine.push({
          //     y: parseInt(response[k].count),
          //     name: response[k].display_name
          //   })
          // }
          // chart.series[0].setData(dataLine, true)
          this.poepleCountFlag = false
        })
        .catch(err => {
          this.poepleCountFlag = false
          console.log(err)
        })
    },
    processChartData(res) {
      let newOption = {
        xAxis: [
          {
            type: 'category',
            boundaryGap: false,
            data: res.map(r => {
              return r.display_name
            })
          }
        ],
        series: [
          {
            name: '数量',
            type: 'line',
            areaStyle: { normal: {} },
            data: res.map(r => {
              return r.count
            })
          }
        ]
      }
      return newOption
    },
    lineDataHandle(obj) {
      this.active = obj.display_name
      this.type = obj.index
      this.getLineData()
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
    },
    handleHover(key) {
      console.log(key)
    }
  }
}
</script>
<style lang="less" scoped>
.point-data-wrapper {
  // padding: 10px;
  .search-wrap {
    padding: 30px;
    background: #fff;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    font-size: 16px;
    align-items: center;
    .el-form-item {
      margin-bottom: 10px;
    }
    .el-select {
      width: 200px;
    }
    .warning {
      background: #ebf1fd;
      padding: 8px;
      margin-left: 10px;
      color: #444;
      font-size: 12px;
      i {
        color: #4a8cf3;
        margin-right: 5px;
      }
    }
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
      margin-bottom: 10px;
      li {
        padding-right: 95px;
        padding-top: 20px;
      }
      .btn:hover {
        .title {
          color: #fff;
        }
        .arrow-icon {
          position: absolute;
          z-index: 2;
          top: 145px;
          left: 66px;
          width: 0;
          height: 0;
          border-width: 13px 10px;
          border-style: solid;
          &.color-0 {
            border-color: #8fe5b8 #ffffff #ffffff #ffffff;
          }
          &.color-1 {
            border-color: #0099ff #ffffff #ffffff #ffffff;
          }
          &.color-2 {
            border-color: #22b573 #ffffff #ffffff #ffffff;
          }
          &.color-3 {
            border-color: #f8b62d #ffffff #ffffff #ffffff;
          }
          &.color-4 {
            border-color: #e83828 #ffffff #ffffff #ffffff;
          }
          &.color-5 {
            border-color: #93278f #ffffff #ffffff #ffffff;
          }
        }
        &.color-0 {
          background: url('~assets/images/program/circle.png') center 35px
            no-repeat #8fe5b8;
        }
        &.color-1 {
          background: url('~assets/images/program/circle.png') center 35px
            no-repeat #0099ff;
        }
        &.color-2 {
          background: url('~assets/images/program/circle.png') center 35px
            no-repeat #22b573;
        }
        &.color-3 {
          background: url('~assets/images/program/circle.png') center 35px
            no-repeat #f8b62d;
        }
        &.color-4 {
          background: url('~assets/images/program/circle.png') center 35px
            no-repeat #e83828;
        }
        &.color-5 {
          background: url('~assets/images/program/circle.png') center 35px
            no-repeat #93278f;
        }
        // background-color: #517ebb;
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
          width: 0;
          height: 0;
          border-width: 13px 10px;
          border-style: solid;
          border-color: #f6f6f6 #ffffff #ffffff #ffffff;
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
    }
    .chart-wrapper {
      padding-top: 30px;
      width: 100%;
      height: 800px;
      .highcharts-container {
        // width: 100%;
      }
    }
  }
  .table-wrap {
    padding: 15px;
    background: #fff;
    margin: 15px 0;
    .demo-table-expand {
      font-size: 0;
    }
    .demo-table-expand label {
      width: 90px;
      color: #99a9bf;
    }
    .demo-table-expand .el-form-item {
      margin-right: 0;
      margin-bottom: 0;
      width: 50%;
    }
  }
  .pagination-wrap {
    margin: 10px auto;
    text-align: right;
  }
  .actions-wrap {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    font-size: 16px;
    align-items: center;
    margin-bottom: 10px;
    .label {
      font-size: 14px;
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
  .transfer-sex-wrapper {
    margin-top: 15px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    background-color: #fff;
    min-height: 600px;
    .transfer {
      background-color: #fff;
      width: 50%;
      padding: 10px;
    }
    .sex-age {
      padding: 10px;
      background-color: #fff;
      width: 50%;
      min-height: 600px;
      .echarts {
        height: 600px;
        width: 100%;
      }
    }
  }
  .age-wrapper {
    margin-top: 15px;
    background-color: #fff;
    height: 600px;
    padding: 20px;
  }
  .echarts {
    height: 100%;
    width: 100%;
  }
}
</style>

