<template>
  <div
    class="point-data-wrapper" :element-loading-text="setting.loadingText" v-loading="setting.loading">
    <!-- 搜索 -->
    <div class="search-wrap">
      <el-button
        @click="handlePicShow"
        class="more-pic">
        漏斗图
      </el-button>
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
        <li v-for="(item, key) in peopleCount.concat([{ index: 'cpf', display_name: 'CPF转化率' }, { index: 'cpa', display_name:'CPA转化率' }, { index: 'cpl', display_name:'CPL转化率' }])" 
          :key="key" 
          v-if="item.index !== 'outnum'">
          <a 
            :class="'btn color-'+ key" 
            @hover="handleHover(key)"
            >
            <i class="title" >
              {{item.display_name}}
            </i>
            <span class="count" v-if="item.index === 'looknum'">
              {{circleLooknum}}
            </span>
            <span class="count" v-if="item.index === 'playernum7'">
              {{circlePlayernum7}}
            </span>
            <span class="count" v-if="item.index === 'playernum'">
              {{circlePlayernum}}
            </span>
            <span class="count" v-if="item.index === 'lovenum'">
              {{circleLovenum}}
            </span>
            <span class="count" v-if="item.index === 'cpf'">
              {{computedCPF}}
            </span>
            <span class="count" v-if="item.index === 'cpa'">
              {{computedCPA}}
            </span>
            <span class="count" v-if="item.index === 'cpl'">
              {{computedCPL}}
            </span>
            <i :class="'arrow-icon color-' + key"></i>
            <i class="right-arrow-icon" v-if="item.index === 'looknum'">
              {{playernum7DivideLookNum}}
            </i>
            <i class="right-arrow-icon" v-if="item.index === 'playernum7'">
              {{playernumDivideLookNum}}
            </i>
            <i class="right-arrow-icon" v-if="item.index === 'playernum'">
              {{lovenumDivideLookNum}}
            </i>
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
    
    <!-- 年龄分布图 -->
    <div class="age-sex-wrapper"  v-loading="ageFlag"> 
      <div class="sex-part">
        <chart 
          ref="pieChart"
          @click="onClick"
          :options="pieChart" 
          auto-resize />
      </div>
      <div class="age-part">
        <chart
        ref="ageChart"
        :options="ageChart"
        auto-resize />
      </div>
    </div>
    <!-- 时间段与人群特征 -->
    <div class="time-crowd-wrapper"  v-loading="crowdFlag"> 
      <div class="crowd-part">
        <chart
        ref="crowdChart"
        :options="timeAndCrowdChart"
        auto-resize />
      </div>
    </div>
    <!-- 报表部分 -->
    <div v-loading="tableSetting.loading" class="table-wrap">
      <div class="actions-wrap">
        <span class="label">
          <span class="point-title">点位列表 </span> 数量: {{pagination.total}}
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
              <el-form-item label="节目">
                  {{ scope.row.projects }}
              </el-form-item>
              <el-form-item label="围观">
                <span>{{ scope.row.looknum }}</span>
              </el-form-item>
              <el-form-item label="活跃">
                <!-- <span>{{ scope.row.looknum }}</span> -->
              </el-form-item>
              <el-form-item label="铁杆">
                <span>{{ scope.row.playernum }}</span>
              </el-form-item>
              <el-form-item label="拉新">
                <span>{{ scope.row.lovenum }}</span>
              </el-form-item>
              <el-form-item label="输出">
                <span>
                  CPF: {{computedCPF}}
                  CPA：{{computedCPA}}
                  CPL： {{computedCPL}}
                </span>
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
          label="节目"
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
          label="活跃"
          min-width="90"
          :show-overflow-tooltip="true">
          <template slot-scope="scope">
            暂无
          </template>
        </el-table-column>
        <el-table-column
          label="拉新"
          prop="lovenum"
          min-width="90"
          :show-overflow-tooltip="true">
        </el-table-column>
        <el-table-column
          label="平均有效时长"
          min-width="90"
          :show-overflow-tooltip="true">
          <template slot-scope="scope">
            暂无
          </template>
        </el-table-column>
        <el-table-column
          label="输出"
          prop="scannum"
          min-width="120"
          :show-overflow-tooltip="true">
          <template slot-scope="props">
            <div>
              <div>CPF: {{computedCPF}}</div>
              <div>CPA：{{computedCPA}}</div>
              <div>CPL： {{computedCPL}}</div>
            </div>  
          </template>
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
        />
      </div>
    </div>

    <!-- 弹窗for 性别细节 -->
    <div  
      class="chart-dialog"
      v-loading="dialogLoading"
      v-show="shouldDialogShow"
      >
      <div 
        class="dialog-close"
        @click="handleDialogClose"
        >
        关闭
      </div>
      <chart 
        ref="pieChart2"
        :options="sexAndAgeChart" 
        auto-resize />
    </div>

    <!-- dialog for 漏斗 -->
    <div 
      v-loading="sexFlag"
      v-show="shouldPicDialogShow"
      class="pic-dialog">
      <img style="width: 100%" src="~assets/images/data-bg.png" />
            <div 
              class="dialog-close"
              @click="handlePicShow"
              >
              关闭
            </div>
            <div
              :style="style.chartFont" 
              class="looknum">
              {{circleLooknum}}人
            </div>
            <div
              :style="style.chartFont" 
              class="playernum7">
              {{circlePlayernum7}}人
            </div>
            <div
              :style="style.chartFont" 
              class="playernum">
              {{circlePlayernum}}人
            </div>

            <div
              :style="style.chartFont" 
              class="lovenum">
              {{circleLovenum}}人
            </div>

            <div
              :style="style.chartFont" 
              class="cpa">
              {{computedCPA}}
            </div>

            <div
              :style="style.chartFont" 
              class="cph">
              {{computedCPF}}
            </div>

            <div
              :style="style.chartFont" 
              class="cpl">
              {{computedCPL}}
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
import ECharts from 'vue-echarts/components/ECharts'
import 'echarts/lib/chart/line'
import 'echarts/lib/component/markLine'
import 'echarts/lib/chart/pie'
import 'echarts/lib/chart/bar'
import 'echarts/lib/component/title'
import 'echarts/lib/component/tooltip'
import 'echarts/lib/component/legend'

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
    // let data = []

    // for (let i = 0; i <= 360; i++) {
    //   let t = i / 180 * Math.PI
    //   let r = Math.sin(2 * t) * Math.cos(2 * t)
    //   data.push([r, i])
    // }
    return {
      style: {
        chartFont: {
          fontSize: window.innerWidth / 80 + 'px'
        }
      },
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
      shouldDialogShow: false,
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
      pagination: {
        total: 0,
        pageSize: 5,
        currentPage: 1
      },
      tableData: [],
      tempAgeData: null,
      peopleCount: [0, 0, 0, 0],
      type: '',
      userList: [],
      ageType: false,
      sexType: false,
      pointName: '',
      arUserId: '',
      poepleCountFlag: false,
      shouldPicDialogShow: false,
      ageFlag: false,
      sexFlag: false,
      crowdFlag: false,
      userSelect: '',
      projectAlias: '',
      mainChart: {
        color: [
          '#0099FF',
          '#22b572',
          '#F8B62D',
          '#E83828',
          '#197748',
          '#F8B62D',
          '#BC1313'
        ],
        title: {
          text: ''
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
          data: [
            '大屏围观参与人数',
            '大屏活跃玩家人数',
            '大屏铁杆玩家人数',
            '扫码拉新会员注册总数',
            'CPF转化率',
            'CPA转化率',
            'CPL转化率'
          ]
        },
        grid: [
          {
            left: 50,
            right: 50,
            height: '50%'
            // containLabel: true
          },
          {
            left: 50,
            right: 50,
            top: '61%',
            height: '33%'
            // containLabel: true
          }
        ],
        xAxis: [
          {
            type: 'category',
            boundaryGap: false,
            axisLine: { onZero: true },
            data: null
          },
          {
            // show: false,
            gridIndex: 1,
            type: 'category',
            boundaryGap: false,
            axisLine: { onZero: true },
            data: null,
            position: 'top'
          }
        ],
        yAxis: [
          {
            gridIndex: 0,
            type: 'value'
          },
          {
            inverse: true,
            gridIndex: 1,
            type: 'value'
          }
        ],
        series: []
      },
      pieChart: {
        color: ['#0071BC', '#ED1E79'],
        title: {
          text: '用户渗透率',
          left: 'left'
        },
        tooltip: {
          trigger: 'item'
        },
        legend: {
          bottom: 10,
          left: 'center',
          data: ['女', '男']
        },
        series: [
          {
            label: {
              normal: {
                // show: true,
                formatter: '{d}%',
                position: 'inner'
              }
            },
            type: 'pie',
            radius: '65%',
            center: ['50%', '50%'],
            selectedMode: 'single',
            data: null,
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
        color: ['#0071BC', '#ED1E79'],
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow'
          },
          formatter: function(data) {
            let male = (
              parseInt(data[0].value) /
              (parseInt(data[0].value) + parseInt(data[1].value)) *
              100
            ).toFixed(2)
            let female = (
              parseInt(data[1].value) /
              (parseInt(data[0].value) + parseInt(data[1].value)) *
              100
            ).toFixed(2)
            return (
              data[0].axisValue +
              ': <br/>' +
              data[0].marker +
              data[0].seriesName +
              ':' +
              male +
              '%<br/>' +
              data[1].marker +
              data[1].seriesName +
              ':' +
              female +
              '%'
            )
          }
        },
        // legend: {
        //   data: ['男', '女']
        // },
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
            name: '男',
            type: 'bar',
            barGap: '30%',
            barWidth: '60%',
            stack: '总量',
            label: {
              normal: {
                show: true,
                position: 'inside'
              }
            },
            data: null
          },
          {
            name: '女',
            type: 'bar',
            stack: '总量',
            label: {
              normal: {
                show: true,
                position: 'inside'
              }
            },
            data: null
          }
        ]
      },
      timeAndCrowdChart: {
        title: {
          text: '时间段与人群特征',
          align: 'left'
        },
        color: ['#8CC63F', '#FBB03B', '#F15A24', '#662D91', '#ED1E79'],
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'cross',
            crossStyle: {
              color: '#999'
            }
          }
        },
        legend: {
          data: ['00后', '90后', '80后', '70后', '女'],
          align: 'left',
          left: 10,
          top: 30
        },
        grid: {
          left: '5%',
          right: '5%',
          bottom: '4%'
        },
        xAxis: [
          {
            type: 'category',
            // data: [],
            axisPointer: {
              type: 'shadow'
            }
          }
        ],
        yAxis: [
          {
            type: 'value',
            // name: '年龄数量',
            min: 0,
            axisLabel: {
              formatter: '{value}'
            }
          },
          {
            type: 'value',
            // name: '女性百分比',
            max: 100,
            min: 0,
            axisLabel: {
              formatter: '{value} %'
            }
          }
        ],
        series: [
          {
            name: '00后',
            type: 'bar',
            stack: '总量',
            data: null
          },
          {
            name: '90后',
            type: 'bar',
            stack: '总量',
            data: null
          },
          {
            name: '80后',
            type: 'bar',
            stack: '总量',
            data: null
          },
          {
            name: '70后',
            type: 'bar',
            stack: '总量',
            data: null
          },
          {
            name: '女',
            type: 'line',
            yAxisIndex: 1,
            markLine: {
              data: [
                {
                  name: 'Y 轴值为 50 的水平线',
                  yAxis: 50
                }
              ]
            },
            data: null
          }
        ]
      },
      sexAndAgeChart: {
        title: {
          text: '性别年龄分布'
        },
        tooltip: {
          trigger: 'item'
        },
        legend: {
          orient: 'horizontal',
          right: 10,
          top: 'bottom',
          bottom: 10,
          data: null
        },
        series: [
          {
            type: 'pie',
            radius: '65%',
            center: ['50%', '50%'],
            selectedMode: 'single',
            data: null,
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
      dialogLoading: false
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
    },
    circleLooknum: function() {
      return this.peopleCount[0].count === null ? 0 : this.peopleCount[0].count
    },
    circlePlayernum7: function() {
      return this.peopleCount[1].count === null ? 0 : this.peopleCount[1].count
    },
    circlePlayernum: function() {
      return this.peopleCount[2].count === null ? 0 : this.peopleCount[2].count
    },
    circleLovenum: function() {
      return this.peopleCount[3].count === null ? 0 : this.peopleCount[3].count
    },
    playernum7DivideLookNum: function() {
      let result = (
        this.peopleCount[1].count /
        this.peopleCount[0].count *
        100
      ).toFixed(2)
      return result === 0 || result === NaN ? 0 : result + '%'
    },
    playernumDivideLookNum: function() {
      let result = (
        this.peopleCount[2].count /
        this.peopleCount[1].count *
        100
      ).toFixed(2)
      return result === 0 || result === NaN ? 0 : result + '%'
    },
    lovenumDivideLookNum: function() {
      let result = (
        this.peopleCount[3].count /
        this.peopleCount[2].count *
        100
      ).toFixed(2)
      return result === 0 || result === NaN ? 0 : result + '%'
    },
    computedCPF: function() {
      let result = (
        this.peopleCount[1].count /
        this.peopleCount[0].count *
        100
      ).toFixed(2)
      return String(result) + '%'
    },
    computedCPA: function() {
      let result = (
        this.peopleCount[2].count /
        this.peopleCount[0].count *
        100
      ).toFixed(2)
      return String(result) + '%'
    },
    computedCPL: function() {
      let result = (
        this.peopleCount[3].count /
        this.peopleCount[0].count *
        100
      ).toFixed(2)
      return String(result) + '%'
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
    handleDialogClose() {
      this.shouldDialogShow = false
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
          args.index = 'looknum,playernum,lovenum'
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
      this.getAge()
      this.getCrowdTime()
      this.getGender()
      this.setting.loading = false
    },
    getCrowdTime() {
      this.crowdFlag = true
      let args = this.setArgs('8')
      return chart
        .getChartData(this, args)
        .then(response => {
          let chart = this.$refs.crowdChart
          chart.mergeOptions({
            xAxis: {
              type: 'category',
              data: response.map(r => {
                return r.display_name
              })
            },
            series: [
              {
                name: '00后',
                type: 'bar',
                stack: '总量',
                data: response.map(r => {
                  return r.count.century00
                })
              },
              {
                name: '90后',
                type: 'bar',
                stack: '总量',
                data: response.map(r => {
                  return r.count.century90
                })
              },
              {
                name: '80后',
                type: 'bar',
                stack: '总量',
                data: response.map(r => {
                  return r.count.century80
                })
              },
              {
                name: '70后',
                type: 'bar',
                stack: '总量',
                data: response.map(r => {
                  return r.count.century70
                })
              },
              {
                name: '女',
                type: 'line',
                yAxisIndex: 1,
                data: response.map(r => {
                  return r.rate.toFixed(1)
                }),
                markLine: {
                  data: [
                    {
                      name: 'Y 轴值为 50 的水平线',
                      yAxis: 50
                    }
                  ]
                }
              }
            ]
          })
          this.crowdFlag = false
        })
        .catch(err => {
          this.crowdFlag = false
          console.log(err)
        })
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
          this.type = 'looknum,playernum,lovenum,playernum7'
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
          this.tempAgeData = response
          let chart = this.$refs.ageChart
          chart.mergeOptions({
            xAxis: {
              type: 'category',
              data: response.map(r => {
                return r.display_name
              })
            },
            series: [
              {
                name: '男',
                type: 'bar',
                stack: '总量',
                label: {
                  normal: {
                    show: true,
                    position: 'inside'
                  }
                },
                data: response.map(r => {
                  return r.count.male
                })
              },
              {
                name: '女',
                type: 'bar',
                stack: '总量',
                label: {
                  normal: {
                    show: true,
                    position: 'inside'
                  }
                },
                data: response.map(r => {
                  return r.count.female
                })
              }
            ]
          })
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
          let chart = this.$refs.pieChart
          chart.mergeOptions({
            series: [
              {
                data: [
                  {
                    name: '男',
                    value: response[1].count === null ? 0 : response[1].count,
                    selected: true
                  },
                  {
                    name: '女',
                    value: response[0].count === null ? 0 : response[0].count
                  }
                ]
              }
            ]
          })
          this.sexFlag = false
        })
        .catch(err => {
          this.sexFlag = false
          console.log(err)
        })
    },
    onClick(event, instance, echarts) {
      this.dialogLoading = true
      this.shouldDialogShow = true
      let args = this.setArgs('4')
      chart.getChartData(this, args).then(response => {
        let that = this
        let mergeChart = this.$refs.pieChart2
        mergeChart.mergeOptions({
          color:
            event.name === '男'
              ? [
                  '#B6CEF9',
                  '#92B5F9',
                  '#649DFA',
                  '#4188F1',
                  '#397AD8',
                  '#3269B8'
                ].reverse()
              : [
                  '#F38DD0',
                  '#F19AB9',
                  '#EF70A0',
                  '#E3508B',
                  '#CA477B',
                  '#AE3E6C'
                ].reverse(),
          legend: {
            data: response.map(r => {
              return r.display_name
            })
          },
          series: [
            {
              type: 'pie',
              radius: '65%',
              center: ['50%', '50%'],
              selectedMode: 'single',
              data: response.map(r => {
                return {
                  name: r.display_name,
                  value: event.name === '男' ? r.count.male : r.count.female
                }
              }),
              itemStyle: {
                emphasis: {
                  shadowBlur: 10,
                  shadowOffsetX: 0,
                  shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
              }
            }
          ]
        })
        this.dialogLoading = false
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
          this.poepleCountFlag = false
        })
        .catch(err => {
          this.poepleCountFlag = false
          console.log(err)
        })
    },
    processChartData(res) {
      let newOption = {
        legend: {
          data: [
            '大屏围观参与人数',
            '大屏活跃玩家人数',
            '大屏铁杆玩家人数',
            '扫码拉新会员注册总数',
            'CPF转化率',
            'CPA转化率',
            'CPL转化率'
          ]
        },
        xAxis: [
          {
            type: 'category',
            boundaryGap: false,
            data: res.map(r => {
              return r.display_name
            })
          },
          {
            show: false,
            gridIndex: 1,
            type: 'category',
            boundaryGap: false,
            data: res.map(r => {
              return r.display_name
            }),
            position: 'top'
          }
        ],
        series: [
          {
            symbol: 'circle',
            name: '大屏围观参与人数',
            type: 'line',
            areaStyle: { normal: {} },
            data: res.map(r => {
              return r.looknum
            })
          },
          {
            symbol: 'circle',
            name: '大屏活跃玩家人数',
            type: 'line',
            areaStyle: { normal: {} },
            data: res.map(r => {
              return r.playernum7
            })
          },
          {
            symbol: 'circle',
            name: '大屏铁杆玩家人数',
            type: 'line',
            areaStyle: { normal: {} },
            data: res.map(r => {
              return r.playernum
            })
          },
          {
            symbol: 'circle',
            name: '扫码拉新会员注册总数',
            type: 'line',
            areaStyle: { normal: {} },
            data: res.map(r => {
              return r.lovenum
            })
          },

          {
            xAxisIndex: 1,
            yAxisIndex: 1,
            name: 'CPF转化率',
            type: 'line',
            lineStyle: {
              color: '#197748'
            },
            data: res.map(r => {
              return (r.playernum7 / r.looknum * 100).toFixed(2)
            })
          },
          {
            xAxisIndex: 1,
            yAxisIndex: 1,
            name: 'CPA转化率',
            type: 'line',
            lineStyle: {
              color: '#F8B62D'
            },
            data: res.map(r => {
              return (r.playernum / r.looknum * 100).toFixed(2)
            })
          },
          {
            xAxisIndex: 1,
            yAxisIndex: 1,
            name: 'CPL转化率',
            type: 'line',
            type: 'line',
            lineStyle: {
              color: '#BC1313'
            },
            data: res.map(r => {
              return (r.lovenum / r.looknum * 100).toFixed(2)
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
    },
    handlePicShow() {
      this.shouldPicDialogShow = !this.shouldPicDialogShow
    }
  }
}
</script>
<style lang="less" scoped>
.point-data-wrapper {
  .chart-dialog {
    position: fixed;
    z-index: 10000;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: auto;
    width: 600px;
    height: 620px;
    background-color: white;
    border: 1px solid black;
    .dialog-close {
      position: absolute;
      top: 5px;
      right: 5px;
      cursor: pointer;
    }
    .echarts {
      margin-top: 10%;
      height: 90%;
      width: 100%;
    }
  }
  .pic-dialog {
    position: fixed;
    z-index: 10000;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: auto;
    width: 600px;
    height: 700px;
    background-color: white;
    border: 1px solid black;
    .dialog-close {
      position: absolute;
      top: 5px;
      right: 5px;
      cursor: pointer;
    }
    .looknum {
      position: absolute;
      width: 80%;
      left: 10%;
      z-index: 11;
      top: 31%;
      text-align: center;
      color: white;
      font-weight: 800;
    }
    .playernum7 {
      position: absolute;
      width: 80%;
      left: 10%;
      z-index: 11;
      top: 45.5%;
      text-align: center;
      color: white;
      font-weight: 800;
    }
    .playernum {
      position: absolute;
      width: 80%;
      left: 10%;
      z-index: 11;
      top: 62%;
      text-align: center;
      color: white;
      font-weight: 800;
    }
    .lovenum {
      position: absolute;
      width: 80%;
      left: 10%;
      z-index: 11;
      top: 77%;
      text-align: center;
      color: white;
      font-weight: 800;
    }
    .cpa {
      position: absolute;
      width: 20%;
      left: 3%;
      top: 65%;
      font-weight: 800;
      text-align: center;
      color: white;
    }
    .cph {
      position: absolute;
      width: 15%;
      right: 12%;
      top: 54%;
      font-weight: 800;
      text-align: center;
      color: white;
    }
    .cpl {
      position: absolute;
      width: 23%;
      right: 5%;
      top: 85%;
      font-weight: 800;
      text-align: center;
      color: white;
    }
  }
  .search-wrap {
    padding: 30px;
    background: #fff;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    font-size: 16px;
    align-items: center;
    position: relative;
    .more-pic {
      position: absolute;
      top: 10px;
      right: 10px;
    }
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
        padding-bottom: 10px;
      }

      .btn {
        &.color-0 {
          background: url('~assets/images/program/circle.png') center 39px
            no-repeat #0099ff;
          background-size: 80px;
        }
        &.color-1 {
          background: url('~assets/images/program/circle.png') center 39px
            no-repeat #22b572;
          background-size: 80px;
        }
        &.color-2 {
          background: url('~assets/images/program/circle.png') center 39px
            no-repeat #f8b62d;
          background-size: 80px;
        }
        &.color-3 {
          background: url('~assets/images/program/circle.png') center 39px
            no-repeat #e83828;
          background-size: 80px;
        }
        &.color-4 {
          background: url('~assets/images/program/circle.png') center 39px
            no-repeat #197748;
          background-size: 80px;
        }
        &.color-5 {
          background: url('~assets/images/program/circle.png') center 39px
            no-repeat #f8b62d;
          background-size: 80px;
        }
        &.color-6 {
          background: url('~assets/images/program/circle.png') center 39px
            no-repeat #bc1313;
          background-size: 80px;
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
            border-color: #0099ff #ffffff #ffffff #ffffff;
          }
          &.color-1 {
            border-color: #22b572 #ffffff #ffffff #ffffff;
          }
          &.color-2 {
            border-color: #f8b62d #ffffff #ffffff #ffffff;
          }
          &.color-3 {
            border-color: #e83828 #ffffff #ffffff #ffffff;
          }
          &.color-4 {
            border-color: #197748 #ffffff #ffffff #ffffff;
          }
          &.color-5 {
            border-color: #f8b62d #ffffff #ffffff #ffffff;
          }
          &.color-6 {
            border-color: #bc1313 #ffffff #ffffff #ffffff;
          }
        }
        cursor: pointer;
        width: 130px;
        height: 130px;
        display: block;
        border-radius: 5px;
        background: url('~assets/images/program/circle.png') center 35px
          no-repeat #f6f6f6;
        position: relative;

        .title {
          display: block;
          height: 35px;
          padding-left: 20px;
          padding-top: 9px;
          font-size: 14px;
          color: white;
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
          top: 130px;
          left: 55px;
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
    }
  }
  .table-wrap {
    padding: 15px;
    background: #fff;
    margin: 15px 0;
    .point-title {
      font-size: 18px;
      color: #000;
      font-weight: 600;
    }
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
      position: relative;
    }
    .sex-age {
      position: relative;
      padding: 10px;
      background-color: #fff;
      width: 50%;
      min-height: 600px;
      z-index: 10;
      .echarts {
        height: 600px;
        width: 100%;
      }
    }
  }
  .age-sex-wrapper {
    margin-top: 15px;
    background-color: #fff;
    height: 600px;
    padding: 20px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;

    .sex-part {
      width: 25%;
      height: 100%;

      .echarts {
        height: 80%;
        width: 100%;
      }
    }
    .age-part {
      width: 72%;
      left: 3%;
      height: 100%;
    }
  }
  .time-crowd-wrapper {
    margin-top: 15px;
    background-color: #fff;
    height: 600px;
    padding: 20px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    .crowd-part {
      width: 100%;
      height: 100%;
      .echarts {
        height: 90%;
        width: 100%;
      }
    }
  }
  .echarts {
    height: 100%;
    width: 100%;
  }
}
</style>

