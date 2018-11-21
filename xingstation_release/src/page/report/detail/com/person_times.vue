<template>
  <div
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText"
    class="person-times-wrap" >
    <!-- 主要图表部分 -->
    <div 
      v-loading="poepleCountFlag"
      class="content-wrapper">
      <ul class="btns-wrapper">
        <li 
          v-for="(item, key) in peopleCount"
          :key="item.number.index">
          <div class="person-btn-wrap">
            <div :class="'person-btn-top-'+ key"></div>
            <div class="person-btn person-btn-left">
              <div class="person-btn-title">{{ item.number.display_name }}</div>
              <div class="person-btn-count">{{ item.number.count ? item.number.count : 0 }}</div>
            </div>
          </div>
        </li>
      </ul>
      <ul class="btns-rate-wrapper">
        <li
          v-for="(item, key) in peopleCount"
          :key="item.number.index">
          <div class="person-rate-btn-wrap">
            <div class="person-btn">
              <div class="person-rate-item">
                <div class="person-btn-name">
                  {{ key === 0 ? '' : item.rate.display_name }}
                </div>
                <div class="person-btn-title"> {{ key === 0 ? '' : '转化率' }}</div>
                <div class="person-btn-count">{{ key === 0 ? '' : item.rate.value }}</div>
              </div>
              <div 
                v-if="key !==0 "
                :class="'person-rate-color-' + key">
                <div class="person-rate-content"></div>
              </div>
            </div>
          </div>
        </li>
      </ul>
      <div 
        class="chart-person-times-wrapper">
        <chart 
          ref="mainPersonTimesChart"
          :options="mainPersonTimesChart" 
          auto-resize />
      </div>
    </div>
    <!-- 其他图表 -->
    <el-collapse 
      v-model="activeNames" 
      @change="handleChange">
      <!-- 年龄分布图 -->
      <el-collapse-item 
        title="年龄分布图" 
        name="1" 
        class="echart-person-times-data">
        <div 
          v-loading="ageFlag"
          class="age-person-sex-wrapper" > 
          <div 
            class="sex-person-part">
            <chart 
              ref="pieSexChart"
              :options="pieSexChart" 
              @click="onClick"
            />
          </div>
          <div 
            class="age-person-part">
            <chart
              ref="agePersonChart"
              :options="agePersonChart"/>
          </div>
        </div>
      </el-collapse-item>
      <!-- 时间段与人群特征 -->
      <el-collapse-item 
        title="时间段与人群特征" 
        name="2"  
        class="echart-person-times-data">
        <div  
          v-loading="crowdFlag"
          class="time-crowd-person-wrapper" > 
          <div 
            class="crowd-person-part">
            <chart
              ref="crowdPersonChart"
              :options="timeAndCrowdPersonChart"/>
          </div>
        </div>
      </el-collapse-item>
      <!-- 节目日化人气 -->
      <el-collapse-item 
        title="节目日化人气" 
        name="3"  
        class="echart-person-times-data">
        <div 
          class="ranking-person-wrap">
          <div
            v-loading="projectPersonFlag"
            class="project-person-part">
            <chart 
              ref="projectPersonChar"
              :options="projectPersonOptions"
              @click="clickProject"/>
          </div>
          <div
            v-loading="userFlag"
            class="project-age-person-part">
            <chart
              ref="PersonprojectAgeChart"
              :options="PersonprojectAgeChart"/>
          </div>
        </div>
      </el-collapse-item>
      <!-- 报表部分 -->
      <el-collapse-item 
        title="点位列表" 
        name="4" 
        class="echart-person-times-data">
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
                    <span>{{ scope.row.looktimes }}</span>
                  </el-form-item>
                  <el-form-item 
                    label="7″fCPE">
                    <span>{{ scope.row.playtimes7 }}</span>
                  </el-form-item>
                  <el-form-item 
                    label="7″fCPE">
                    <span>{{ scope.row.playtimes7 }}</span>
                  </el-form-item>
                  <el-form-item 
                    label="15″fCPE">
                    <span>{{ scope.row.playtimes15 }}</span>
                  </el-form-item>
                  <el-form-item 
                    label="21″fCPE">
                    <span>{{ scope.row.playtimes21 }}</span>
                  </el-form-item>
                  <el-form-item 
                    label="fCPR(二维码生成数)">
                    <span>{{ scope.row.outnum }}</span>
                  </el-form-item>
                  <el-form-item 
                    label="fCPA(扫码跳转次数)">
                    <span>{{ scope.row.omo_scannum }}</span>
                  </el-form-item>
                  <el-form-item 
                    label="fCPL(拉新会员数)">
                    <span>{{ scope.row.lovetimes }}</span>
                  </el-form-item>
                  <el-form-item 
                    label="输出">
                    <div>
                      <div>7″fCPE: {{ scope.row.rate.fCPE7 }}</div>
                      <div>15″fCPE{{ scope.row.rate.fCPE15 }}</div>
                      <div>21″fCPE {{ scope.row.rate.fCPE21 }}</div>
                      <div>fCPR {{ scope.row.rate.fCPR }}</div>
                      <div>fCPA {{ scope.row.rate.fCPA }}</div>
                      <div>fCPL {{ scope.row.rate.fCPL }}</div>
                      <div>fCPS {{ scope.row.rate.fCPS }}</div>
                    </div>
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
              prop="looktimes"
              min-width="90"/>
            <el-table-column
              :show-overflow-tooltip="true"
              label="7″fCPE"
              prop="playtimes7"
              min-width="90"
            />
            <el-table-column
              :show-overflow-tooltip="true"
              label="15″fCPE"
              prop="playtimes15"
              min-width="90"
            />
            <el-table-column
              :show-overflow-tooltip="true"
              label="21″fCPE"
              prop="playtimes21"
              min-width="90"/>
            <el-table-column
              :show-overflow-tooltip="true"
              label="输出"
              prop="scannum"
              min-width="120"
            >
              <template 
                slot-scope="props">*
                <div>
                  <div>7″fCPE: {{ props.row.rate.fCPE7 }}</div>
                  <div>15″fCPE: {{ props.row.rate.fCPE15 }}</div>
                  <div>21″fCPE: {{ props.row.rate.fCPE21 }}</div>
                  <div>fCPR: {{ props.row.rate.fCPR }}</div>
                  <div>fCPA: {{ props.row.rate.fCPA }}</div>
                  <div>fCPL: {{ props.row.rate.fCPL }}</div>
                  <div>fCPS: {{ props.row.rate.fCPS }}</div>
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
        ref="pieSexChart2"
        :options="sexAndAgeChart"
        auto-resize />
    </div>

    <!-- dialog for 漏斗 -->
    <div
      v-loading="rateDialog"
      v-show="shouldPicDialogShow"
      class="pic-times-dialog">
      <div 
        class="dialog-close"
        @click="handlePicShow">
        关闭
      </div>
      <div class="pic-times-content">
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
                <span 
                  class="legend-text" 
                  @click="legendHandle('0')">
                  <span 
                    :class="{'label-gray': !dataOptions[0]}"
                    class="legend-text-one"/>围观</span>
                <span 
                  class="legend-text" 
                  @click="legendHandle('1')">
                  <span
                    :class="{'label-gray': !dataOptions[1]}" 
                    class="legend-text-two"/>7秒</span>
                <span 
                  class="legend-text" 
                  @click="legendHandle('2')">
                  <span 
                    :class="{'label-gray': !dataOptions[2]}"
                    class="legend-text-three" />15秒</span>
                <span 
                  class="legend-text" 
                  @click="legendHandle('3')">
                  <span 
                    :class="{'label-gray': !dataOptions[3]}"
                    class="legend-text-four" />21秒</span>
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
                    class="legend-text-seven " />核销</span>
              </div>
              <PicChart 
                :chartdata="chartdata" 
                :data-options="dataOptions" 
                :width="width"/>
            </div>
          </el-col>
          <el-col :span="12">
            <chart 
              ref="personRateChart"
              :options="personRateOption"
              auto-resize 
              class="rate-chart"/>
          </el-col>
        </el-row>
      </div>
    </div>
  </div>
</template>
<script>
import stats from 'service/stats'
import search from 'service/search'
import chart from 'service/chart'
import Vue from 'vue'
import PicChart from './timesChart'
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
      PersonprojectAgeChart: {
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
      projectPersonOptions: {
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow'
          }
        },
        color: ['#006eff'],
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
            name: '围观参与玩家人次',
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
      projectPersonFlag: false,
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
      shouldDialogShow: false,
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: '拼命加载中'
      },
      personRateOption: {
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
          top: 10,
          left: 20
        },
        toolbox: {
          show: true
        },
        calculable: true,
        color: ['#5687f8', '#74bd66'],
        xAxis: [
          {
            type: 'category',
            axisTick: { show: false },
            data: [
              '7″fCPE\n转化率',
              '15″fCPE\n转化率',
              '21″fCPE\n转化率',
              'fCPA\n转化率',
              'fCPL\n转化率',
              'fCPS\n转化率'
            ]
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
            // barWidth: 40,
            data: null
          },
          {
            name: '总体各级平均转化率',
            type: 'bar',
            // barWidth: 40,
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
      peopleCount: [],
      type: '',
      ageType: false,
      sexType: false,
      pointName: '',
      poepleCountFlag: false,
      shouldPicDialogShow: false,
      ageFlag: false,
      rateDialog: false,
      crowdFlag: false,
      mainPersonTimesChart: {
        color: [
          '#006eff',
          '#05a253',
          '#ffdd00',
          '#8e007d',
          '#ffa200',
          '#ff008a',
          '#e9200f',
          '#946f32',
          '#03662e',
          '#f0bf00',
          '#700068',
          '#9b7800',
          '#d00084',
          '#af0004',
          '#7e3b10'
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
            '围观人次',
            '7″fCPE',
            '15″fCPE',
            '21″fCPE',
            'fCPR(二维码生成数)',
            'fCPA(扫码跳转次数)',
            'fCPL(拉新会员数)',
            'fCPS玩家人次',
            '7″fCPE转化率',
            '15″fCPE转化率',
            '21″fCPE转化率',
            'fCPR转化率',
            'fCPA转化率',
            'fCPL转化率',
            'fCPS转化率',
          ]
        },
        grid: [
          {
            left: 50,
            right: 50,
            height: '50%'
          },
          {
            left: 50,
            right: 50,
            top: '61%',
            height: '33%'
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
      pieSexChart: {
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
      agePersonChart: {
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
      timeAndCrowdPersonChart: {
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
  computed: {},
  mounted() {
    let that = this
    window.onresize = function() {
      that.handleChange()
    }
  },
  created() {},
  methods: {
    handleChange(val) {
      this.$nextTick(function() {
        this.$refs.crowdPersonChart.resize()
        this.$refs.agePersonChart.resize()
        this.$refs.pieSexChart.resize()
        this.$refs.projectPersonChar.resize()
        this.$refs.PersonprojectAgeChart.resize()
        if (window.innerWidth > 1300) {
          this.width = ((window.innerWidth - 60 + 20) * 0.5 - 20) * 0.6
        }
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
      let args = this.setArgs('7')
      args.belong = belong
      return chart
        .getTimesChartData(this, args)
        .then(response => {
          let chart = this.$refs.PersonprojectAgeChart
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
      this.projectPersonFlag = true
      let args = this.setArgs('6')
      return chart
        .getTimesChartData(this, args)
        .then(response => {
          this.projectTop = response
          let chart = this.$refs.projectPersonChar
          chart.mergeOptions({
            yAxis: {
              type: 'category',
              data: response.map(r => {
                return r.display_name
              })
            },
            series: [
              {
                name: '围观参与玩家人次',
                type: 'bar',
                stack: '总量',
                label: {
                  normal: {
                    show: true,
                    position: 'insideRight'
                  }
                },
                data: response.map(r => {
                  return r.count.looktimes
                })
              }
            ]
          })
          if (response.length > 0) {
            this.getProjectAge(response[response.length - 1].index)
          } else {
            this.getProjectAge()
          }
          this.projectPersonFlag = false
        })
        .catch(err => {
          this.projectPersonFlag = false
          console.log(err)
        })
    },
    handleDialogClose() {
      this.shouldDialogShow = false
    },
    getPointList() {
      this.tableSetting.loading = true
      let args = this.setArgs()
      args.page = this.pagination.currentPage
      delete args.id
      return stats
        .getTimesStaus(this, args)
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
      let args = this.setArgs('5')
      return chart
        .getTimesChartData(this, args)
        .then(response => {
          let chart = this.$refs.crowdPersonChart
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
      let args = this.setArgs('1')
      return chart
        .getTimesChartData(this, args)
        .then(response => {
          this.peopleCount = response
          this.type =
            'looktimes,playtimes7,playtimes15,playtimes21,outnum,omo_scannum,lovetimes,verifytimes'
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
        .getTimesChartData(this, args)
        .then(response => {
          this.tempAgeData = response
          let chart = this.$refs.agePersonChart
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
      let args = this.setArgs('3')
      return chart
        .getTimesChartData(this, args)
        .then(response => {
          let chart = this.$refs.pieSexChart
          chart.mergeOptions({
            series: [
              {
                data: [
                  {
                    name: response[1].display_name,
                    value: response[1].count === null ? 0 : response[1].count,
                    selected: true
                  },
                  {
                    name: response[0].display_name,
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
      chart.getTimesChartData(this, args).then(response => {
        let that = this
        let mergeChart = this.$refs.pieSexChart2
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
        start_date: this.handleDateTransform(this.searchForm.dateTime[0]),
        end_date: this.handleDateTransform(
          new Date(this.searchForm.dateTime[1]).getTime()
        ),
        alias: this.searchForm.projectAlias,
        ar_user_id: this.searchForm.arUserId,
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
        delete args.ar_user_id
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
      let args = this.setArgs('2')
      args.index = this.type
      return chart
        .getTimesChartData(this, args)
        .then(response => {
          let dataLine = []
          let chart = this.$refs.mainPersonTimesChart
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
            '围观人次',
            '7″fCPE',
            '15″fCPE',
            '21″fCPE',
            'fCPR(二维码生成数)',
            'fCPA(扫码跳转次数)',
            'fCPL(拉新会员数)',
            'fCPS玩家人次',
            '7″fCPE转化率',
            '15″fCPE转化率',
            '21″fCPE转化率',
            'fCPR转化率',
            'fCPA转化率',
            'fCPL转化率',
            'fCPS转化率'
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
            name: '围观人次',
            type: 'line',
            areaStyle: { normal: {} },
            data: res.map(r => {
              return r.looktimes
            })
          },
          {
            symbol: 'circle',
            name: '7″fCPE',
            type: 'line',
            areaStyle: { normal: {} },
            data: res.map(r => {
              return r.playtimes7
            })
          },
          {
            symbol: 'circle',
            name: '15″fCPE',
            type: 'line',
            areaStyle: { normal: {} },
            data: res.map(r => {
              return r.playtimes15
            })
          },
          {
            symbol: 'circle',
            name: '21″fCPE',
            type: 'line',
            areaStyle: { normal: {} },
            data: res.map(r => {
              return r.playtimes21
            })
          },
          {
            symbol: 'circle',
            name: 'fCPR(二维码生成数)',
            type: 'line',
            areaStyle: { normal: {} },
            data: res.map(r => {
              return r.outnum
            })
          },
          {
            symbol: 'circle',
            name: 'fCPA(扫码跳转次数)',
            type: 'line',
            areaStyle: { normal: {} },
            data: res.map(r => {
              return r.omo_scannum
            })
          },
          {
            symbol: 'circle',
            name: 'fCPL(拉新会员数)',
            type: 'line',
            areaStyle: { normal: {} },
            data: res.map(r => {
              return r.lovetimes
            })
          },
          {
            symbol: 'circle',
            name: 'fCPS玩家人次',
            type: 'line',
            areaStyle: { normal: {} },
            data: res.map(r => {
              return r.verifytimes
            })
          },
          {
            xAxisIndex: 1,
            yAxisIndex: 1,
            name: '7″fCPE转化率',
            type: 'line',
            lineStyle: {
              color: '#03662e'
            },
            data: res.map(r => {
              return r.playtimes7_rate
            })
          },
          {
            xAxisIndex: 1,
            yAxisIndex: 1,
            name: '15″fCPE转化率',
            type: 'line',
            lineStyle: {
              color: '#f0bf00'
            },
            data: res.map(r => {
              return r.playtimes15_rate
            })
          },
          {
            xAxisIndex: 1,
            yAxisIndex: 1,
            name: '21″fCPE转化率',
            type: 'line',
            lineStyle: {
              color: '#700068'
            },
            data: res.map(r => {
              return r.playtimes21
            })
          },
          {
            xAxisIndex: 1,
            yAxisIndex: 1,
            name: 'fCPR转化率',
            type: 'line',
            type: 'line',
            lineStyle: {
              color: '#9b7800'
            },
            data: res.map(r => {
              return r.outnum_rate
            })
          },
          {
            xAxisIndex: 1,
            yAxisIndex: 1,
            name: 'fCPA转化率',
            type: 'line',
            lineStyle: {
              color: '#d00084'
            },
            data: res.map(r => {
              return r.omo_scannum_rate
            })
          },
          {
            xAxisIndex: 1,
            yAxisIndex: 1,
            name: 'fCPL转化率',
            type: 'line',
            lineStyle: {
              color: '#af0004'
            },
            data: res.map(r => {
              return r.lovetimes_rate
            })
          },
          {
            xAxisIndex: 1,
            yAxisIndex: 1,
            name: 'fCPS转化率',
            type: 'line',
            lineStyle: {
              color: '#7e3b10'
            },
            data: res.map(r => {
              return r.verifytimes_rate
            })
          }
        ]
      }
      return newOption
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
    getConversionRate() {
      this.rateDialog = true
      let args = this.setArgs('8')
      return chart
        .getTimesChartData(this, args)
        .then(response => {
          this.chartdata = []
          let chart = this.$refs.personRateChart
          response.data.map(r => {
            let value = r.count === null ? 0 : r.count
            this.chartdata.push(value)
          })
          this.rateDay = response.day
          this.marketCount = response.market_count
          this.screenCount = response.oid_count
          chart.mergeOptions({
            series: [
              {
                name: '当前范围各级转化率',
                type: 'bar',
                barGap: 0,
                // barWidth: 40,
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
                // barWidth: 40,
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
.person-times-wrap {
  .btns-wrapper {
    padding: 10px 0;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    li {
      margin-bottom: 10px;
    }
    .person-btn-wrap {
      width: 150px;
      .person-btn-top-0,
      .person-btn-top-1,
      .person-btn-top-2,
      .person-btn-top-3,
      .person-btn-top-4,
      .person-btn-top-5,
      .person-btn-top-6,
      .person-btn-top-7 {
        height: 6px;
        margin: 0 3px;
      }
      .person-btn-top-0 {
        background: #006eff;
      }
      .person-btn-top-1 {
        background: #05a253;
      }
      .person-btn-top-2 {
        background: #ffdd00;
      }
      .person-btn-top-3 {
        background: #8e007d;
      }
      .person-btn-top-4 {
        background: #ffa200;
      }
      .person-btn-top-5 {
        background: #ff008a;
      }
      .person-btn-top-6 {
        background: #e9200f;
      }
      .person-btn-top-7 {
        background: #946f32;
      }
      .person-btn {
        text-align: center;
        padding: 10px 0;
        width: 100%;
        height: 100%;
        border: 1px solid #ccc;
        border-left: none;
        .person-btn-title {
          font-size: 12px;
          color: #222;
        }
        .person-btn-count {
          font-size: 16px;
          color: #222;
        }
      }
      .person-btn-left {
        border-left: 1px solid #ccc;
      }
    }
  }
  .btns-rate-wrapper {
    padding: 10px 0;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    li {
      margin-bottom: 10px;
    }
    .person-rate-btn-wrap {
      width: 150px;
      .person-btn {
        text-align: center;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        .person-rate-color-0,
        .person-rate-color-1,
        .person-rate-color-2,
        .person-rate-color-3,
        .person-rate-color-4,
        .person-rate-color-5,
        .person-rate-color-6,
        .person-rate-color-7 {
          width: 30px;
          height: 30px;
          border-radius: 50%;
          margin-top: 26px;
          margin-left: 10px;
          .person-rate-content {
            width: 20px;
            height: 20px;
            background: #fff;
            border-radius: 50%;
            margin: 5px;
          }
        }
        .person-rate-color-0 {
          background: #005791;
        }
        .person-rate-color-1 {
          background: #03662e;
        }
        .person-rate-color-2 {
          background: #f0bf00;
        }
        .person-rate-color-3 {
          background: #700068;
        }
        .person-rate-color-4 {
          background: #9b7800;
        }
        .person-rate-color-5 {
          background: #d00084;
        }
        .person-rate-color-6 {
          background: #af0004;
        }
        .person-rate-color-7 {
          background: #7e3b10;
        }
        .person-rate-item {
          .person-btn-name {
            color: #222;
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 10px;
          }
          .person-btn-title {
            font-size: 12px;
            color: #222;
          }
          .person-btn-count {
            font-size: 14px;
            color: #222;
          }
        }
      }
    }
  }
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

  .pic-times-dialog {
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
    .pic-times-content {
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
              background: #007bff;
            }
            .legend-text-two {
              background: #05a254;
            }
            .legend-text-three {
              background: #ffdd00;
            }
            .legend-text-four {
              background: #90007d;
            }
            .legend-text-five {
              background: #ff0089;
            }
            .legend-text-six {
              background: #eb1000;
            }
            .legend-text-seven {
              background: #956e32;
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
    .chart-person-times-wrapper {
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
  .age-person-sex-wrapper {
    margin-top: 15px;
    background-color: #fff;
    height: 600px;
    padding: 20px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    .sex-person-part {
      width: 25%;
      height: 100%;
      .echarts {
        height: 100%;
        width: 100%;
      }
    }
    .age-person-part {
      width: 72%;
      left: 3%;
      height: 100%;
    }
  }
  .time-crowd-person-wrapper {
    margin-top: 15px;
    background-color: #fff;
    height: 600px;
    padding: 20px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    .crowd-person-part {
      width: 100%;
      height: 100%;
      .echarts {
        height: 90%;
        width: 100%;
      }
    }
  }
  .ranking-person-wrap {
    margin-top: 15px;
    background-color: #fff;
    height: 600px;
    padding: 20px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    .project-person-part {
      width: 60%;
      height: 100%;
      .echarts {
        height: 100%;
        width: 90%;
      }
    }
    .project-age-person-part {
      width: 35%;
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

