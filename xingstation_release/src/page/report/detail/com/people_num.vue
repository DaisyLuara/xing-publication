<template>
  <div
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText"
    class="people_num_wrapper" >
    <!-- 主要图表部分 -->
    <div 
      v-loading="poepleCountFlag"
      class="content-wrapper">
      <ul
        class="btns-wrapper">
        <li 
          v-for="(item, key) in peopleCount.concat([{ index: 'cpf', display_name: 'CPF转化率' }, { index: 'cpr', display_name:'CPR转化率' }, { index: 'cpa', display_name:'CPA转化率' }, { index: 'cpl', display_name:'CPL转化率' }])" 
          v-if="item.index !== 'outnum'"
          :key="key">
          <a 
            :class="'btn color-'+ key">
            <i class="title" >
              {{ item.display_name }}
            </i>
            <span  
              v-if="item.index === 'looknum'"
              class="count">
              {{ circleLooknum }}
            </span>
            <span 
              v-if="item.index === 'playernum7'"
              class="count" >
              {{ circlePlayernum7 }}
            </span>
            <span 
              v-if="item.index === 'playernum'"
              class="count">
              {{ circlePlayernum }}
            </span>
            <span 
              v-if="item.index === 'omo_outnum'"
              class="count" >
              {{ circleOmoOutnum }}
            </span>
            <span 
              v-if="item.index === 'lovenum'"
              class="count" >
              {{ circleLovenum }}
            </span>
            <span 
              v-if="item.index === 'cpf'"
              class="count" >
              {{ computedCPF }}
            </span>
            <span  
              v-if="item.index === 'cpr'" 
              class="count">
              {{ computedCPR }}
            </span>
            <span  
              v-if="item.index === 'cpa'" 
              class="count">
              {{ computedCPA }}
            </span>
            <span 
              v-if="item.index === 'cpl'" 
              class="count" >
              {{ computedCPL }}
            </span>
            <i 
              :class="'arrow-icon color-' + key" />
            <i 
              v-if="item.index === 'looknum'"
              class="right-arrow-icon">
              {{ playernum7DivideLookNum }}
            </i>
            <i
              v-if="item.index === 'playernum7'"
              class="right-arrow-icon">
              {{ playernumDivideLookNum }}
            </i>
            <i 
              v-if="item.index === 'playernum'"
              class="right-arrow-icon" >
              {{ lovenumDivideLookNum }}
            </i>
            <i 
              v-if="item.index === 'omo_outnum'"
              class="right-arrow-icon" >
              {{ lovenumDivideOmoOutnum }}
            </i>
          </a>
        </li>
      </ul>
      <div 
        class="chart-wrapper">
        <chart 
          ref="mainChart"
          :options="mainChart" 
          auto-resize />
      </div>
    </div>
    <el-collapse 
      v-model="activeNames" 
      @change="handleChange">
      <!-- 年龄分布图 -->
      <el-collapse-item 
        title="年龄分布图" 
        name="1" 
        class="echart-data">
        <div 
          v-loading="ageFlag"
          class="age-sex-wrapper" > 
          <div 
            class="sex-part">
            <chart 
              ref="pieChart"
              :options="pieChart" 
              @click="onClick"
            />
          </div>
          <div 
            class="age-part">
            <chart
              ref="ageChart"
              :options="ageChart"/>
          </div>
        </div>
      </el-collapse-item>
      <!-- 时间段与人群特征 -->
      <el-collapse-item 
        title="时间段与人群特征" 
        name="2"  
        class="echart-data">
        <div  
          v-loading="crowdFlag"
          class="time-crowd-wrapper" > 
          <div 
            class="crowd-part">
            <chart
              ref="crowdChart"
              :options="timeAndCrowdChart"/>
          </div>
        </div>
      </el-collapse-item>
      <!-- 节目日化人气 -->
      <el-collapse-item 
        title="节目日化人气" 
        name="3"  
        class="echart-data">
        <div 
          class="ranking-wrap">
          <div
            v-loading="projectFlag"
            class="project-part">
            <chart 
              ref="projectChar"
              :options="projectOptions"
              @click="clickProject"/>
          </div>
          <div
            v-loading="userFlag"
            class="project-age-part">
            <chart
              ref="projectAgeChart"
              :options="projectAgeChart"/>
          </div>
        </div>
      </el-collapse-item>
      <!-- 报表部分 -->
      <el-collapse-item 
        title="点位列表" 
        name="4" 
        class="echart-data">
        <div 
          v-loading="tableSetting.loading"
          class="table-wrap">
          <div 
            class="actions-wrap">
            <span 
              class="label">
              <span 
                class="point-title">点位列表 
              </span> 数量: {{ pagination.total }}
            </span>
            <div>
              <el-select 
                v-model="reportValue" 
                placeholder="请选择导出报表类型">
                <el-option
                  v-for="item in reportList"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"/>
              </el-select>
              <el-button 
                type="success"
                size="small" 
                @click="changeReportType">下载</el-button>
            </div>
          </div>
          <el-table
            :data="tableData"
            style="width: 100%">
            <el-table-column 
              type="expand">
              <template 
                slot-scope="scope">
                <el-form 
                  label-position="left" 
                  inline 
                  class="demo-table-expand">
                  <el-form-item 
                    label="ID">
                    <span>{{ scope.row.id }}</span>
                  </el-form-item>
                  <el-form-item 
                    label="点位">
                    {{ scope.row.area_name }} {{ scope.row.market_name }} {{ scope.row.point_name }}
                  </el-form-item>
                  <el-form-item 
                    label="节目">
                    {{ scope.row.projects }}
                  </el-form-item>
                  <el-form-item 
                    label="围观">
                    <span>{{ scope.row.looknum }}</span>
                  </el-form-item>
                  <el-form-item 
                    label="活跃">
                    <span>{{ scope.row.playernum7 }}</span>
                  </el-form-item>
                  <el-form-item 
                    label="铁杆">
                    <span>{{ scope.row.playernum }}</span>
                  </el-form-item>
                  <el-form-item 
                    label="拉新">
                    <span>{{ scope.row.lovenum }}</span>
                  </el-form-item>
                  <el-form-item 
                    label="输出">
                    <span>
                      CPF: {{ computedCPF }}
                      CPR：{{ computedCPR }}
                      CPL： {{ computedCPL }}
                    </span>
                  </el-form-item>
                  <el-form-item 
                    label="时间">
                    <span>{{ scope.row.min_date }} - {{ scope.row.max_date }}</span>
                  </el-form-item>
                </el-form>
              </template>
            </el-table-column>
            <el-table-column
              label="ID"
              prop="id"
              width="100"/>
            <el-table-column
              :show-overflow-tooltip="true"
              label="点位"
              prop="point"
              min-width="130">
              <template 
                slot-scope="props">
                {{ props.row.area_name }} {{ props.row.market_name }} {{ props.row.point_name }}
              </template>
            </el-table-column>
            <el-table-column
              :show-overflow-tooltip="true"
              label="节目"
              prop="projects"
              min-width="130"
            />
            <el-table-column
              label="围观"
              prop="looknum"
              min-width="90"/>
            <el-table-column
              :show-overflow-tooltip="true"
              label="活跃"
              min-width="90"
              prop="playernum7"
            />
            <el-table-column
              :show-overflow-tooltip="true"
              label="铁杆"
              min-width="90"
              prop="playernum"
            />
            <el-table-column
              :show-overflow-tooltip="true"
              label="拉新"
              prop="lovenum"
              min-width="90"
            />
            <el-table-column
              :show-overflow-tooltip="true"
              label="输出"
              prop="scannum"
              min-width="120"
            >
              <template 
                slot-scope="props">*
                <div>
                  <div>CPF: {{ ((props.row.playernum7 / props.row.looknum) * 100).toFixed(2) }}%</div>
                  <div>CPR：{{ ((props.row.playernum / props.row.looknum) * 100).toFixed(2) }}%</div>
                  <div>CPL：{{ ((props.row.lovenum / props.row.looknum) * 100).toFixed(2) }}%</div>
                </div>  
              </template>
            </el-table-column>
            <el-table-column
              :show-overflow-tooltip="true"
              label="时间"
              min-width="120"
              prop="created_at"
            >
              <template 
                slot-scope="props">
                <span>{{ props.row.min_date }} - {{ props.row.max_date }}</span>
              </template>
            </el-table-column>
          </el-table>
          <div 
            class="pagination-wrap">
            <el-pagination
              :total="pagination.total"
              :page-size="pagination.pageSize"
              :current-page="pagination.currentPage"
              layout="prev, pager, next, jumper, total"
              @current-change="changePage"
            />
          </div>
        </div>
      </el-collapse-item>
    </el-collapse>
    <!-- 弹窗for 性别细节 -->
    <div  
      v-loading="dialogLoading"
      v-show="shouldDialogShow"
      class="chart-dialog">
      <div 
        class="dialog-close"
        @click="handleDialogClose">
        关闭
      </div>
      <chart 
        ref="pieChart2"
        :options="sexAndAgeChart"
        auto-resize />
    </div>

    <!-- dialog for 漏斗 -->
    <div
      v-loading="rateDialog"
      v-show="shouldPicDialogShow"
      class="pic-dialog">
      <div 
        class="dialog-close"
        @click="handlePicShow">
        关闭
      </div>
      <div class="pic-content">
        <div 
          class="actions-wrap-pic">
          <div 
            class="label">
            <div class="item-text">时间：{{ handleDateTransform(searchForm.dateTime[0]) }}  -  {{ handleDateTransform(searchForm.dateTime[1]) }}</div>
            <div
              v-if="sceneInfo" 
              class="item-text">场景：{{ sceneInfo }}</div>
            <div 
              v-if="projectInfo"
              class="item-text" >节目：{{ projectInfo }}</div>
            <div 
              v-if="addressInfo"
              class="item-text" >地址：{{ addressInfo }}</div>
          </div>
          <div
            style="text-align: right;" 
            class="label">
            <span class="icon-wrap">
              <span class="icon-num">{{ rateDay }}<sub>天</sub></span>
              <img src="~assets/images/icons/date_icon.png">
            </span>
            <span class="icon-wrap">
              <span class="icon-num">{{ marketCount }}<sub>个场地</sub></span>
              <img src="~assets/images/icons/tower_icon.png">
            </span>
            <span class="icon-wrap">
              <span class="icon-num">{{ screenCount }}<sub>座大屏</sub></span>
              <img src="~assets/images/icons/machine_icon.png">
            </span>
          </div>
        </div>
        <el-row :gutter="20">
          <el-col :span="12">
            <div class="funnel">
              <div class="legend">
                <!-- <span 
                  class="legend-text" 
                  @click="legendHandle('0')">
                  <span 
                    :class="{'label-gray': !dataOptions[0]}"
                    class="legend-text-one"/>爆光</span> -->
                <span 
                  class="legend-text" 
                  @click="legendHandle('1')">
                  <span
                    :class="{'label-gray': !dataOptions[1]}" 
                    class="legend-text-two"/>围观</span>
                <span 
                  class="legend-text" 
                  @click="legendHandle('2')">
                  <span 
                    :class="{'label-gray': !dataOptions[2]}"
                    class="legend-text-three" />活跃</span>
                <span 
                  class="legend-text" 
                  @click="legendHandle('3')">
                  <span 
                    :class="{'label-gray': !dataOptions[3]}"
                    class="legend-text-four" />铁杆</span>
                <span 
                  class="legend-text" 
                  @click="legendHandle('4')">
                  <span 
                    :class="{'label-gray': !dataOptions[4]}"
                    class="legend-text-five" />跳转</span>
                <span 
                  class="legend-text" 
                  @click="legendHandle('5')">
                  <span 
                    :class="{'label-gray': !dataOptions[5]}"
                    class="legend-text-six" />拉新</span>
                <span 
                  class="legend-text" 
                  @click="legendHandle('6')">
                  <span 
                    :class="{'label-gray': !dataOptions[6]}"
                    class="legend-text-seven " />转发</span>
              </div>
              <PicChart 
                :chartdata="chartdata" 
                :data-options="dataOptions" 
                :width="width"/>
            </div>
          </el-col>
          <el-col :span="12">
            <chart 
              ref="rateChart"
              :options="rateOption"
              auto-resize 
              class="rate-chart"/>
          </el-col>
        </el-row>
      </div>
    </div>
  </div>
</template>
<script>
import {
  getExcelData,
  getChartData,
  getStaus,
  handleDateTypeTransform
} from 'service'
import Vue from 'vue'
import PicChart from './numChart'
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
  MessageBox,
  Collapse,
  CollapseItem
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
    'el-collapse': Collapse,
    'el-collapse-item': CollapseItem,
    chart: ECharts,
    PicChart
  },
  props: {
    searchForm: {
      type: Object,
      default: function() {
        return {}
      }
    }
  },
  data() {
    return {
      projectAgeChart: {
        tooltip: {
          trigger: 'item',
          formatter: '{a} <br/>{b}: {c}'
        },
        color: ['#3b9aca', '#8CC63F', '#FBB03B', '#F15A24', '#662D91'],
        legend: {
          x: 'left',
          data: ['10后', '00后', '90后', '80后', '70前/后']
        },
        series: [
          {
            name: '年龄分布',
            type: 'pie',
            radius: ['10%', '50%'],
            data: null
          }
        ]
      },
      projectOptions: {
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow'
          }
        },
        color: ['#E83828', '#E80F9B', '#F8B62D', '#22b572', '#0099FF'],
        grid: {
          left: '3%',
          right: '4%',
          bottom: '3%',
          containLabel: true
        },
        xAxis: {
          type: 'value'
        },
        yAxis: {
          type: 'category',
          data: null
        },
        series: [
          {
            name: '扫码拉新会员注册总数',
            type: 'bar',
            stack: '总量',
            label: {
              normal: {
                show: true,
                position: 'insideRight'
              }
            },
            data: null
          },
          {
            name: 'OMO有效跳转人数',
            type: 'bar',
            stack: '总量',
            label: {
              normal: {
                show: true,
                position: 'insideRight'
              }
            },
            data: null
          },
          {
            name: '大屏铁杆玩家人数',
            type: 'bar',
            stack: '总量',
            label: {
              normal: {
                show: true,
                position: 'insideRight'
              }
            },
            data: null
          },
          {
            name: '大屏活跃玩家人数',
            type: 'bar',
            stack: '总量',
            label: {
              normal: {
                show: true,
                position: 'insideRight'
              }
            },
            data: null
          },
          {
            name: '大屏围观参与人数',
            type: 'bar',
            stack: '总量',
            label: {
              normal: {
                show: true,
                position: 'insideRight'
              }
            },
            data: null
          }
        ]
      },
      projectFlag: false,
      userFlag: false,
      activeNames: ['1', '2', '3', '4'],
      rateDay: 0,
      marketCount: 0,
      screenCount: 0,
      chartdata: [],
      dataOptions: [true, true, true, true, true, true, false],
      width: ((window.innerWidth - 60 + 20) * 0.5 - 20) * 0.6,
      sceneInfo: '',
      projectInfo: '',
      addressInfo: '',
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
        },
        {
          value: 'person_reward',
          label: '个人绩效'
        }
      ],
      shouldDialogShow: false,
      reportValue: 'point',
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: '拼命加载中'
      },
      rateOption: {
        title: {
          text: ''
        },
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow'
          }
        },
        legend: {
          data: ['当前范围各级转化率', '总体各级平均转化率'],
          bottom: 0,
          left: 'center'
        },
        toolbox: {
          show: true
        },
        calculable: true,
        color: ['#5687f8', '#74bd66'],
        xAxis: [
          {
            type: 'category',
            data: ['CPF转化率', 'CPR转化率', 'CPL转化率']
          }
        ],
        yAxis: [
          {
            type: 'value',
            axisLabel: {
              formatter: '{value} %'
            },
            max: 100,
            min: 0
          }
        ],
        series: [
          {
            name: '当前范围各级转化率',
            type: 'bar',
            barGap: 0,
            barWidth: 40,
            data: null
          },
          {
            name: '总体各级平均转化率',
            type: 'bar',
            barWidth: 40,
            data: null
          }
        ]
      },
      pagination: {
        total: 0,
        pageSize: 5,
        currentPage: 1
      },
      tableData: [],
      tempAgeData: null,
      peopleCount: [0, 0, 0, 0, 0],
      type: '',
      ageType: false,
      sexType: false,
      pointName: '',
      poepleCountFlag: false,
      shouldPicDialogShow: false,
      ageFlag: false,
      rateDialog: false,
      crowdFlag: false,
      mainChart: {
        color: [
          '#0099FF',
          '#22b572',
          '#F8B62D',
          '#E80F9B',
          '#E83828',
          '#197748',
          '#F8B62D',
          '#be136e',
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
            'OMO有效跳转人数',
            '扫码拉新会员注册总数',
            'CPF转化率',
            'CPR转化率',
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
          top: 30,
          left: '0',
          data: ['女', '男']
        },
        series: [
          {
            label: {
              normal: {
                // show: true,
                formatter: '{d}%',
                fontSize: 18,
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
          textStyle: {
            fontSize: 16
          },
          formatter: function(data) {
            let male = (
              (parseInt(data[0].value) /
                (parseInt(data[0].value) + parseInt(data[1].value))) *
              100
            ).toFixed(2)
            let female = (
              (parseInt(data[1].value) /
                (parseInt(data[0].value) + parseInt(data[1].value))) *
              100
            ).toFixed(2)
            return (
              data[0].axisValue +
              ': <br/>' +
              data[0].marker +
              data[0].seriesName +
              ':' +
              data[0].value +
              ' ' +
              male +
              '%<br/>' +
              data[1].marker +
              data[1].seriesName +
              ':' +
              data[1].value +
              ' ' +
              female +
              '%'
            )
          }
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
            name: '男',
            type: 'bar',
            barGap: '30%',
            barWidth: '60%',
            stack: '总量',
            data: null
          },
          {
            name: '女',
            type: 'bar',
            stack: '总量',
            label: {
              normal: {
                show: true,
                position: 'top'
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
        color: [
          '#3b9aca',
          '#8CC63F',
          '#FBB03B',
          '#F15A24',
          '#662D91',
          '#ED1E79'
        ],
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'cross',
            crossStyle: {
              color: '#999'
            }
          },
          formatter: function(data) {
            let century10 = (
              (parseInt(data[0].value) /
                (parseInt(data[0].value) +
                  parseInt(data[1].value) +
                  parseInt(data[2].value) +
                  parseInt(data[3].value) +
                  parseInt(data[4].value))) *
              100
            ).toFixed(2)
            let century00 = (
              (parseInt(data[1].value) /
                (parseInt(data[0].value) +
                  parseInt(data[1].value) +
                  parseInt(data[2].value) +
                  parseInt(data[3].value) +
                  parseInt(data[4].value))) *
              100
            ).toFixed(2)
            let century90 = (
              (parseInt(data[2].value) /
                (parseInt(data[0].value) +
                  parseInt(data[1].value) +
                  parseInt(data[2].value) +
                  parseInt(data[3].value) +
                  parseInt(data[4].value))) *
              100
            ).toFixed(2)
            let century80 = (
              (parseInt(data[3].value) /
                (parseInt(data[0].value) +
                  parseInt(data[1].value) +
                  parseInt(data[2].value) +
                  parseInt(data[3].value) +
                  parseInt(data[4].value))) *
              100
            ).toFixed(2)
            let century70 = (
              (parseInt(data[4].value) /
                (parseInt(data[0].value) +
                  parseInt(data[1].value) +
                  parseInt(data[2].value) +
                  parseInt(data[3].value) +
                  parseInt(data[4].value))) *
              100
            ).toFixed(2)
            return (
              data[0].axisValue +
              ': <br/>' +
              data[0].marker +
              data[0].seriesName +
              ':' +
              data[0].value +
              ' ' +
              century10 +
              '%<br/>' +
              data[1].marker +
              data[1].seriesName +
              ':' +
              data[1].value +
              ' ' +
              century00 +
              '%<br/>' +
              data[2].marker +
              data[2].seriesName +
              ':' +
              data[2].value +
              ' ' +
              century90 +
              '%<br/>' +
              data[3].marker +
              data[3].seriesName +
              ':' +
              data[3].value +
              ' ' +
              century80 +
              '%<br/>' +
              data[4].marker +
              data[4].seriesName +
              ':' +
              data[4].value +
              ' ' +
              century70 +
              '%'
            )
          }
        },
        legend: {
          data: ['10后', '00后', '90后', '80后', '70前/后', '女'],
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
            name: '10后',
            type: 'bar',
            stack: '总量',
            data: null
          },
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
            name: '70前/后',
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
      projectTop: [],
      dialogLoading: false
    }
  },
  computed: {
    peopleCountLength: function() {
      return this.peopleCount.length
    },
    showUser() {
      let user_info = JSON.parse(this.$cookie.get('user_info'))
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
    circleOmoOutnum: function() {
      return this.peopleCount[3].count === null ? 0 : this.peopleCount[3].count
    },
    circleLovenum: function() {
      return this.peopleCount[4].count === null ? 0 : this.peopleCount[4].count
    },
    playernum7DivideLookNum: function() {
      let result = (
        (this.peopleCount[1].count / this.peopleCount[0].count) *
        100
      ).toFixed(2)
      return result === 0 || result === NaN ? 0 : result + '%'
    },
    playernumDivideLookNum: function() {
      let result = (
        (this.peopleCount[2].count / this.peopleCount[1].count) *
        100
      ).toFixed(2)
      return result === 0 || result === NaN ? 0 : result + '%'
    },
    lovenumDivideLookNum: function() {
      let result = (
        (this.peopleCount[3].count / this.peopleCount[2].count) *
        100
      ).toFixed(2)
      return result === 0 || result === NaN ? 0 : result + '%'
    },
    lovenumDivideOmoOutnum: function() {
      let result = (
        (this.peopleCount[4].count / this.peopleCount[3].count) *
        100
      ).toFixed(2)
      return result === 0 || result === NaN ? 0 : result + '%'
    },
    computedCPF: function() {
      let result = (
        (this.peopleCount[1].count / this.peopleCount[0].count) *
        100
      ).toFixed(2)
      return String(result) + '%'
    },
    computedCPR: function() {
      let result = (
        (this.peopleCount[2].count / this.peopleCount[0].count) *
        100
      ).toFixed(2)
      return String(result) + '%'
    },
    computedCPA: function() {
      let result = (
        (this.peopleCount[3].count / this.peopleCount[0].count) *
        100
      ).toFixed(2)
      return String(result) + '%'
    },
    computedCPL: function() {
      let result = (
        (this.peopleCount[4].count / this.peopleCount[0].count) *
        100
      ).toFixed(2)
      return String(result) + '%'
    }
  },
  mounted() {
    let that = this
    window.onresize = function() {
      that.handleChange()
      if (window.innerWidth > 1300) {
        that.width = ((window.innerWidth - 60 + 20) * 0.5 - 20) * 0.6
      }
    }
  },
  created() {},
  methods: {
    handleChange(val) {
      this.$nextTick(function() {
        this.$refs.crowdChart.resize()
        this.$refs.ageChart.resize()
        this.$refs.pieChart.resize()
        this.$refs.projectChar.resize()
        this.$refs.projectAgeChart.resize()
      })
    },
    legendHandle(index) {
      switch (index) {
        case '0':
          this.dataOptions[0] = !this.dataOptions[0]
          Vue.set(this.dataOptions, 0, this.dataOptions[0])
          break
        case '1':
          this.dataOptions[1] = !this.dataOptions[1]
          Vue.set(this.dataOptions, 1, this.dataOptions[1])
          break
        case '2':
          this.dataOptions[2] = !this.dataOptions[2]
          Vue.set(this.dataOptions, 2, this.dataOptions[2])
          break
        case '3':
          this.dataOptions[3] = !this.dataOptions[3]
          Vue.set(this.dataOptions, 3, this.dataOptions[3])
          break
        case '4':
          this.dataOptions[4] = !this.dataOptions[4]
          Vue.set(this.dataOptions, 4, this.dataOptions[4])
          break
        case '5':
          this.dataOptions[5] = !this.dataOptions[5]
          Vue.set(this.dataOptions, 5, this.dataOptions[5])
          break
        case '6':
          this.dataOptions[6] = !this.dataOptions[6]
          Vue.set(this.dataOptions, 6, this.dataOptions[6])
          break
      }
    },
    getProjectAge(belong) {
      this.userFlag = true
      let args = this.setArgs('12')
      args.belong = belong
      return getChartData(this, args)
        .then(response => {
          let chart = this.$refs.projectAgeChart
          chart.mergeOptions({
            series: [
              {
                data: [
                  {
                    name: '10后',
                    value: response.century10 === null ? 0 : response.century10
                  },
                  {
                    name: '00后',
                    value: response.century00 === null ? 0 : response.century00
                  },
                  {
                    name: '90后',
                    value: response.century90 === null ? 0 : response.century90
                  },
                  {
                    name: '80后',
                    value: response.century80 === null ? 0 : response.century80
                  },
                  {
                    name: '70前/后',
                    value: response.century70 === null ? 0 : response.century70
                  }
                ]
              }
            ]
          })
          this.userFlag = false
        })
        .catch(err => {
          this.userFlag = false
          console.log(err)
        })
    },
    clickProject(event, instance, echarts) {
      let project = this.projectTop[event.dataIndex]
      let belong = project.index
      this.getProjectAge(belong)
    },
    getProjectTop() {
      this.projectFlag = true
      let args = this.setArgs('11')
      return getChartData(this, args)
        .then(response => {
          this.projectTop = response
          let chart = this.$refs.projectChar
          chart.mergeOptions({
            yAxis: {
              type: 'category',
              data: response.map(r => {
                return r.display_name
              })
            },
            series: [
              {
                name: '扫码拉新会员注册总数',
                type: 'bar',
                stack: '总量',
                label: {
                  normal: {
                    show: true,
                    position: 'insideRight'
                  }
                },
                data: response.map(r => {
                  return r.count.lovenum
                })
              },
              {
                name: 'OMO有效跳转人数',
                type: 'bar',
                stack: '总量',
                label: {
                  normal: {
                    show: true,
                    position: 'insideRight'
                  }
                },
                data: response.map(r => {
                  return r.count.omo_outnum
                })
              },
              {
                name: '大屏铁杆玩家人数',
                type: 'bar',
                stack: '总量',
                label: {
                  normal: {
                    show: true,
                    position: 'insideRight'
                  }
                },
                data: response.map(r => {
                  return r.count.playernum
                })
              },
              {
                name: '大屏活跃玩家人数',
                type: 'bar',
                stack: '总量',
                label: {
                  normal: {
                    show: true,
                    position: 'insideRight'
                  }
                },
                data: response.map(r => {
                  return r.count.playernum7
                })
              },
              {
                name: '大屏围观参与人数',
                type: 'bar',
                stack: '总量',
                label: {
                  normal: {
                    show: true,
                    position: 'insideRight'
                  }
                },
                data: response.map(r => {
                  return r.count.looknum
                })
              }
            ]
          })
          if (response.length > 0) {
            this.getProjectAge(response[response.length - 1].index)
          } else {
            this.getProjectAge()
          }
          this.projectFlag = false
        })
        .catch(err => {
          this.projectFlag = false
          console.log(err)
        })
    },
    changeReportType() {
      if (this.reportValue === 'point') {
        if (!this.searchForm.point_id) {
          this.$message({
            message: '点位数据下载，请选择点位',
            type: 'warning'
          })
        } else {
          this.getExcelData()
        }
      } else if (this.reportValue === 'project_point') {
        if (this.searchForm.projectSelect.length === 0) {
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
      return getExcelData(this, args)
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
    getPointList() {
      this.tableSetting.loading = true
      let args = this.setArgs()
      args.page = this.pagination.currentPage
      delete args.id
      return getStaus(this, args)
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
    searchHandle() {
      this.pagination.currentPage = 1
      this.setting.loading = true
      this.allChartData()
    },
    resetSearch() {
      this.setting.loading = true
      this.allChartData()
    },
    allChartData() {
      this.setting.loading = true
      this.getPointList()
      this.getPeopleCount()
      this.getAge()
      this.getCrowdTime()
      this.getGender()
      this.getProjectTop()
      this.setting.loading = false
    },
    getCrowdTime() {
      this.crowdFlag = true
      let args = this.setArgs('8')
      return getChartData(this, args)
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
                name: '10后',
                type: 'bar',
                stack: '总量',
                data: response.map(r => {
                  return r.count.century10
                })
              },
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
                name: '70前/后',
                type: 'bar',
                stack: '总量',
                label: {
                  normal: {
                    show: true,
                    position: 'top',
                    color: '#000',
                    fontSize: 16,
                    formatter: function(data) {
                      let content = ''
                      let index = data.dataIndex
                      let singleSum = parseInt(
                        parseInt(response[index].count.century10) +
                          parseInt(response[index].count.century00) +
                          parseInt(response[index].count.century90) +
                          parseInt(response[index].count.century80) +
                          parseInt(response[index].count.century70)
                      )
                      let sum = 0
                      response.map(r => {
                        sum +=
                          parseInt(r.count.century10) +
                          parseInt(r.count.century00) +
                          parseInt(r.count.century90) +
                          parseInt(r.count.century80) +
                          parseInt(r.count.century70)
                      })
                      let percent = ((singleSum / sum) * 100).toFixed(1) + '%'
                      return percent + '\n' + singleSum
                    }
                  }
                },
                data: response.map(r => {
                  return r.count.century70
                })
              },
              {
                name: '女',
                type: 'line',
                yAxisIndex: 1,
                data: response.map(r => {
                  return r.rate
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
    getPeopleCount() {
      this.poepleCountFlag = true
      let args = this.setArgs('6')
      return getChartData(this, args)
        .then(response => {
          this.peopleCount = response
          this.type = 'looknum,playernum,lovenum,playernum7,omo_outnum'
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
      return getChartData(this, args)
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
                    position: 'top',
                    color: '#000',
                    fontSize: 18,
                    formatter: function(data) {
                      let content = ''
                      let index = data.dataIndex
                      let singleSum = parseInt(
                        parseInt(response[index].count.female) +
                          parseInt(response[index].count.male)
                      )
                      let sum = 0
                      response.map(r => {
                        sum += parseInt(r.count.male) + parseInt(r.count.female)
                      })
                      let percent = ((singleSum / sum) * 100).toFixed(1) + '%'
                      return percent + '\n' + singleSum
                    }
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
      let args = this.setArgs('5')
      return getChartData(this, args)
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
        })
        .catch(err => {
          console.log(err)
        })
    },
    onClick(event, instance, echarts) {
      this.dialogLoading = true
      this.shouldDialogShow = true
      let args = this.setArgs('4')
      getChartData(this, args).then(response => {
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
        start_date: handleDateTypeTransform(this.searchForm.dateTime[0]),
        end_date: handleDateTypeTransform(
          new Date(this.searchForm.dateTime[1]).getTime()
        ),
        alias: this.searchForm.projectAlias,
        ar_user_z: this.searchForm.arUserId,
        market_id: this.searchForm.market_id[0],
        scene_id: this.searchForm.sceneSelect,
        area_id: this.searchForm.area_id,
        point_id: this.searchForm.point_id,
        workday: 0,
        weekend: 0,
        holiday: 0
      }
      if (this.searchForm.timeFrame.length > 0) {
        for (let i = 0; i < this.searchForm.timeFrame.length; i++) {
          if (this.searchForm.timeFrame[i] === '工作日') {
            args.workday = 1
          }
          if (this.searchForm.timeFrame[i] === '周末') {
            args.weekend = 1
          }
          if (this.searchForm.timeFrame[i] === '假日') {
            args.holiday = 1
          }
        }
      }
      if (!this.searchForm.projectAlias) {
        delete args.alias
      }
      if (!this.searchForm.arUserId) {
        delete args.ar_user_z
      }
      if (!this.searchForm.sceneSelect) {
        delete args.scene_id
      }
      if (!this.searchForm.area_id) {
        delete args.area_id
      }
      if (!this.searchForm.point_id) {
        delete args.point_id
      }
      if (this.searchForm.market_id.length === 0) {
        delete args.market_id
      }
      return args
    },
    getLineData() {
      this.poepleCountFlag = true
      let args = this.setArgs('7')
      args.index = this.type
      return getChartData(this, args)
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
            'OMO有效跳转人数',
            '扫码拉新会员注册总数',
            'CPF转化率',
            'CPR转化率',
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
            name: 'OMO有效跳转人数',
            type: 'line',
            areaStyle: { normal: {} },
            data: res.map(r => {
              return r.omo_outnum
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
              return ((r.playernum7 / r.looknum) * 100).toFixed(2)
            })
          },
          {
            xAxisIndex: 1,
            yAxisIndex: 1,
            name: 'CPR转化率',
            type: 'line',
            lineStyle: {
              color: '#F8B62D'
            },
            data: res.map(r => {
              return ((r.playernum / r.looknum) * 100).toFixed(2)
            })
          },
          {
            xAxisIndex: 1,
            yAxisIndex: 1,
            name: 'CPA转化率',
            type: 'line',
            lineStyle: {
              color: '#be136e'
            },
            data: res.map(r => {
              return ((r.omo_outnum / r.looknum) * 100).toFixed(2)
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
              return ((r.lovenum / r.looknum) * 100).toFixed(2)
            })
          }
        ]
      }
      return newOption
    },
    lineDataHandle(obj) {
      this.type = obj.index
      this.getLineData()
    },
    handleDateTransform(valueDate) {
      return handleDateTypeTransform(valueDate)
    },
    getConversionRate() {
      this.rateDialog = true
      let args = this.setArgs('10')
      return getChartData(this, args)
        .then(response => {
          this.chartdata = []
          let chart = this.$refs.rateChart
          this.chartdata.push(0)
          response.data.map(r => {
            let value = r.count === null ? 0 : r.count
            this.chartdata.push(value)
          })
          this.chartdata.push(0)
          this.rateDay = response.day
          this.marketCount = response.market_count
          this.screenCount = response.oid_count
          chart.mergeOptions({
            xAxis: {
              type: 'category',
              data: response.rate.rate.map(r => {
                return r.display_name
              })
            },
            series: [
              {
                name: '当前范围各级转化率',
                type: 'bar',
                barGap: 0,
                barWidth: 40,
                data: response.rate.rate.map(r => {
                  return r.count
                }),
                label: {
                  normal: {
                    show: true,
                    position: 'top',
                    color: '#cb0017',
                    rich: {
                      a: {
                        color: 'red',
                        fontSize: 16,
                        fontWeight: 600
                      },
                      b: {
                        color: 'green',
                        fontSize: 16,
                        fontWeight: 600
                      }
                    },
                    formatter: function(data) {
                      let index = data.dataIndex
                      if (
                        parseFloat(data.value) -
                          parseFloat(response.rate.total_rate[index].count) >
                        0
                      ) {
                        return (
                          '{a|' +
                          (
                            parseFloat(data.value) -
                            parseFloat(response.rate.total_rate[index].count)
                          ).toFixed(1) +
                          '%}'
                        )
                      } else {
                        return (
                          '{b|' +
                          -(
                            parseFloat(data.value) -
                            parseFloat(response.rate.total_rate[index].count)
                          ).toFixed(1) +
                          '%}'
                        )
                      }
                    }
                  }
                }
              },
              {
                name: '总体各级平均转化率',
                type: 'bar',
                barWidth: 40,
                data: response.rate.total_rate.map(r => {
                  return r.count
                })
              }
            ]
          })
          this.rateDialog = false
        })
        .catch(err => {
          this.rateDialog = false
          console.log(err)
        })
    },
    handlePicShow(sceneList, projectList, areaList, marketList, pointList) {
      this.shouldPicDialogShow = !this.shouldPicDialogShow
      let that = this
      if (this.shouldPicDialogShow) {
        this.getConversionRate()
        if (that.searchForm.sceneSelect) {
          let scene = sceneList.find(function(r) {
            if (r.id === that.searchForm.sceneSelect) {
              return r.name
            }
          })
          this.sceneInfo = scene.name
        }
        if (that.searchForm.projectSelect.length !== 0) {
          let project = projectList.find(function(r) {
            if (r.alias === that.searchForm.projectSelect[0]) {
              return r.name
            }
          })
          that.projectInfo = project.name
        }
        if (that.searchForm.area_id) {
          let area = areaList.find(function(r) {
            if (r.id === that.searchForm.area_id) {
              return r.name
            }
          })
          this.addressInfo = area.name
        }
        if (that.searchForm.market_id.length !== 0) {
          let market = marketList.find(function(r) {
            if (r.id === that.searchForm.market_id[0]) {
              return r.name
            }
          })
          this.addressInfo = this.addressInfo + market.name
        }
        if (that.searchForm.point_id) {
          let point = pointList.find(function(r) {
            if (r.id === that.searchForm.point_id) {
              return r.name
            }
          })
          this.addressInfo = this.addressInfo + point.name
        }
      }
      if (window.innerWidth > 1300) {
        this.width = ((window.innerWidth - 60 + 20) * 0.5 - 20) * 0.6
      }
    }
  }
}
</script>
<style lang="less" scoped>
.people_num_wrapper {
  background: #fff;
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
    position: absolute;
    z-index: 10000;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: auto;
    min-width: 1000px;
    overflow-x: scroll;
    background-color: white;
    border: 1px solid black;
    .dialog-close {
      position: absolute;
      top: 5px;
      right: 5px;
      cursor: pointer;
      z-index: 200;
    }
    .pic-content {
      padding: 30px;
      height: 100%;
      .actions-wrap-pic {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 16px;
        align-items: center;
        margin-top: 40px;
        margin-bottom: 30px;

        .label {
          flex: 1;
          font-size: 14px;
          .date {
            padding: 10px 20px;
            border: 1px solid #969292;
            border-radius: 4px;
            width: 205px;
          }
          .item-text {
            margin: 10px 0;
          }

          .icon-wrap {
            display: inline;
            margin-right: 30px;
            img {
              width: 3%;
            }
            .icon-num {
              font-size: 30px;
              color: #444;
              font-weight: 600;
              display: inline-block;
              sub {
                font-size: 14px;
                font-weight: 500;
              }
            }
          }
        }
      }
      .funnel {
        .legend {
          margin-bottom: 30px;
          .legend-text {
            font-size: 12px;
            color: #222;
            cursor: pointer;
            span {
              height: 11px;
              margin-right: 5px;
              width: 25px;
              display: inline-block;
              border-radius: 5px;
            }

            .legend-text-one {
              background: #8fe5b8;
            }
            .legend-text-two {
              background: #0099ff;
            }
            .legend-text-three {
              background: #22b573;
            }
            .legend-text-four {
              background: #f8b62d;
            }
            .legend-text-five {
              background: #e80f9b;
            }
            .legend-text-six {
              background: #e83828;
            }
            .legend-text-seven {
              background: #9e8047;
            }
            .label-gray {
              background: #aba6a6;
            }
          }
        }
      }
      .rate-chart {
        height: 100%;
        width: 100%;
        min-height: 550px;
      }
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
    .cpr {
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
            no-repeat #e80f9b;
          background-size: 80px;
        }
        &.color-4 {
          background: url('~assets/images/program/circle.png') center 39px
            no-repeat #e83828;
          background-size: 80px;
        }
        &.color-5 {
          background: url('~assets/images/program/circle.png') center 39px
            no-repeat #197748;
          background-size: 80px;
        }
        &.color-6 {
          background: url('~assets/images/program/circle.png') center 39px
            no-repeat #f8b62d;
          background-size: 80px;
        }
        &.color-7 {
          background: url('~assets/images/program/circle.png') center 39px
            no-repeat #be136e;
          background-size: 80px;
        }
        &.color-8 {
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
            border-color: #e80f9b #ffffff #ffffff #ffffff;
          }
          &.color-4 {
            border-color: #e83828 #ffffff #ffffff #ffffff;
          }
          &.color-5 {
            border-color: #197748 #ffffff #ffffff #ffffff;
          }
          &.color-6 {
            border-color: #f8b62d #ffffff #ffffff #ffffff;
          }
          &.color-7 {
            border-color: #be136e #ffffff #ffffff #ffffff;
          }
          &.color-8 {
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
    margin-top: 40px;
    margin-bottom: 30px;
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
        height: 100%;
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
  .ranking-wrap {
    margin-top: 15px;
    background-color: #fff;
    height: 600px;
    padding: 20px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    .project-part {
      width: 55%;
      height: 100%;
      .echarts {
        height: 100%;
        width: 90%;
      }
    }
    .project-age-part {
      width: 40%;
      left: 3%;
      height: 100%;
    }
  }
  .echarts {
    height: 100%;
    width: 100%;
  }
}
</style>

