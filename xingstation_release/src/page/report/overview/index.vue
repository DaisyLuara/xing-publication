<template>
  <div class="root">
    <div class="item-list-wrap"  :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="search-wrap">
        <el-form ref="searchForm" :model="searchForm"  class="search-form">
          <el-row :gutter="20">
            <el-col :span="8" v-if="showUser">
              <el-form-item label="" prop="user" >
                <el-select v-model="searchForm.user" filterable placeholder="请搜索账号" remote :remote-method="getUser" clearable :loading="searchLoading">
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
              <el-form-item label="" prop="scene_id">
                <el-select v-model="searchForm.scene_id" placeholder="请选择场景" filterable  clearable>
                  <el-option
                    v-for="item in sceneList"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id">
                  </el-option>
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :span="8">
              <el-form-item label="" prop="project">
                <el-select v-model="searchForm.project" filterable placeholder="请搜索节目" remote :remote-method="getProject" :loading="searchLoading" clearable>
                  <el-option
                    v-for="item in projectList"
                    :key="item.id"
                    :label="item.name"
                    :value="item.alias">
                  </el-option>
                </el-select>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="20">
            <el-col :span="8">
              <el-form-item label="" prop="area">
                <el-select v-model="searchForm.area" placeholder="请选择区域" filterable  clearable  @change="areaChangeHandle">
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
              <el-form-item label="" prop="market_id">
                <el-select v-model="searchForm.market_id" placeholder="请搜索商场" filterable :loading="searchLoading" remote :remote-method="getMarket"  @change="marketChangeHandle" clearable>
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
              <el-form-item label="" prop="point">
                <el-select v-model="searchForm.point" placeholder="请选择点位"   filterable :loading="searchLoading" clearable>
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
              <el-form-item label="">
                <el-date-picker
                  v-model="searchForm.dataValue"
                  type="daterange"
                  align="right"
                  unlink-panels
                  range-separator="至"
                  start-placeholder="开始日期"
                  end-placeholder="结束日期"
                  :default-value="searchForm.dataValue"
                  :clearable="false"
                  :picker-options="pickerOptions2"
                  style="width: 310px;"
                  >
                </el-date-picker>
              </el-form-item>
            </el-col>
            <el-col :span="10">
              <el-form-item>
                  <el-button type="primary" @click="search('searchForm')" size="small"> 搜索</el-button>
                  <el-button @click="resetSearch('searchForm')" size="small">重置</el-button>
                </el-form-item>
            </el-col>
          </el-row>
        </el-form>
      </div>
      <div class="chart-wrap">
        <el-row :gutter="20">
          <el-col :span="12">
            <el-card shadow="always">
              <div class="" id="echart4" ref="chart" style="height: 500px; width: 100%"></div>
            </el-card>
          </el-col>
          <el-col :span="12">
            <el-card shadow="always" v-loading="machineAcquisitionFlag">
              <div class="" id="machineAcquisition" ref="acquisitionChart" style="height: 500px; width: 100%"></div>
            </el-card>
          </el-col>
        </el-row>
      </div>
      <div :element-loading-text="tableSetting.loadingText" v-loading="tableSetting.loading">
        <div class="actions-wrap">
          <span class="label">
            数量: {{pagination.total}}
          </span>
          <div>
            <el-button size="small" type="success" >导出</el-button>
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
                <el-form-item label="围观">
                  <span>{{ scope.row.looknum }}</span>
                </el-form-item>
                <el-form-item label="互动">
                  <span>{{ scope.row.playernum }}</span>
                </el-form-item>
                <el-form-item label="拉新">
                  <span>{{ scope.row.lovenum }}</span>
                </el-form-item>
                <el-form-item label="扫码率">
                  <span>扫码/生成数：{{ scope.row.scannum }} / {{ scope.row.outnum }} 扫码率：{{scope.row.outnum !== 0 ? new Number(scope.row.scannum / scope.row.outnum ).toFixed(1): 0 }}%</span>
                </el-form-item>
                <el-form-item label="创建时间">
                  <span>{{ scope.row.created_at }}</span>
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
            prop=""
            min-width="130"
            :show-overflow-tooltip="true">
            <template slot-scope="scope">
              {{ scope.row.area_name }} {{scope.row.market_name}} {{scope.row.point_name}}
            </template>
          </el-table-column>
          <el-table-column
            label="围观"
            prop="looknum"
            min-width="100">
          </el-table-column>
          <el-table-column
            label="互动"
            prop="playernum"
            min-width="100"
            :show-overflow-tooltip="true">
          </el-table-column>
          <el-table-column
            label="拉新"
            prop="lovenum"
            min-width="100"
            :show-overflow-tooltip="true">
          </el-table-column>
          <el-table-column
            label="扫码率"
            prop=""
            min-width="120"
            :show-overflow-tooltip="true">
            <template slot-scope="scope">
            <span>扫码/生成数：{{ scope.row.scannum }} / {{ scope.row.outnum }} <br/> 扫码率：{{scope.row.outnum !== 0 ? new Number(scope.row.scannum / scope.row.outnum ).toFixed(1): 0}}%</span>
            </template>
          </el-table-column>
          <el-table-column
            label="创建时间"
            min-width="120"
            prop="created_at">
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
  </div>
</template>

<script>
import echarts from 'echarts/lib/echarts'
import echartsGl from 'echarts-gl'
import {  Button, Row, Col, Card, DatePicker,FormItem, Form ,Select, Option, Table, TableColumn, Pagination} from 'element-ui'
import stats from 'service/stats'
import search from 'service/search'
import chart from 'service/chart'
import reportViewVue from '../reportView.vue';

export default {
  data() {
    return {
      tableSetting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      pagination: {
        total: 0,
        pageSize: 5,
        currentPage: 1
      },
      searchForm: {
        scene_id:'',
        area: '',
        market_id: '',
        point: '',
        user: '',
        project: '',
        dataValue: [new Date().getTime() - 3600 * 1000 * 24 * 7, new Date().getTime()]
      },
      pickerOptions2: {
        shortcuts: [{
          text: '今天',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              picker.$emit('pick', [start, end]);
            }
          }, {
          text: '昨天',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24);
              end.setTime(end.getTime() - 3600 * 1000 * 24);
              picker.$emit('pick', [start, end]);
            }
          },{
          text: '最近一周',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近一个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            picker.$emit('pick', [start, end]);
          }
        },{
          text: '最近三个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            picker.$emit('pick', [start, end]);
          }
        }]
      },
      projectList: [],
      sceneList: [],
      areaList: [],
      marketList: [],
      pointList: [],
      userList: [],
      searchLoading: false,
      pieOptions: {
        title : {
          text: '屏幕总数 381',
          x:'center'
        },
        tooltip : {
          trigger: 'item',
          formatter: "{b} : {c}"
        },
        toolbox: {
          feature: {
            dataView: {readOnly: false},
            restore: {},
            saveAsImage: {}
          }
        },
        color: ['#90bcde','#508ebc','#0c5ca0'],
        legend: {
            orient: 'vertical',
            left: 'left',
            data: ['总人数','人脸总张数','曝光人数']
        },
        series : [
          {
            label: {
              normal: {
                formatter: '{b}\n {c}',
                // position: 'inside'
                textStyle: {
                  fontSize: 18,
                  fontWeight: 700
                }
              }
            },
            name: '数量',
            type: 'pie',
            radius : ['50%', '70%'],
            data:[
              {value:2011386, name:'总人数', selected:true},
              {value:4778197, name:'人脸总张数'},
              {value:20215890, name:'曝光人数'},
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
      machineAcquisitionChart: {},
      machineAcquisitionFlag: false,
      tableData: [],
      machineAcquisitionData: []
    }
  },
  computed: {
    showUser(){
      let user_info = JSON.parse(localStorage.getItem('user_info'))
      let roles = user_info.roles.data[0].name
      return roles == 'user' ? false : true
    },
    machineAcquisitionOrigin () {
      return this.machineAcquisitionData;
    },
    machineAcquisitionOption() {
      let that = this;
      let obj = {
        title: {
          text: '总数',
        },
        tooltip: {
          trigger: 'item',
          formatter: "{b} : {c}"
        },
        toolbox: {
          feature: {
            dataView: {readOnly: false},
            restore: {},
            saveAsImage: {}
          }
        },
        color: ['#90bcde','#508ebc','#f5b12f'],
        legend: {
        },
        calculable: true,
        series: [
          {
            name:'总数',
            type:'funnel',
            left: '10%',
            top: 60,
            bottom: 60,
            width: '80%',
            min: 0,
            minSize: '0%',
            maxSize: '100%',
            sort: 'descending',
            gap: 2,
            label: {
              normal: {
                formatter: '{b} {c}',
                position: 'inside',
                textStyle: {
                  fontSize: 18,
                  fontWeight: 700
                }
              },
            },
            labelLine: {
              normal: {
                length: 10,
                lineStyle: {
                  width: 1,
                  type: 'solid'
                }
              }
            },
            itemStyle: {
              normal: {
                borderColor: '#fff',
                borderWidth: 1
              }
            },
            data: that.machineAcquisitionOrigin
          }
        ]
      }
      return obj
    }
  },
  mounted() {
    this.getMachineAcquisitionTotal()
  },
  updated () {
    if (!this.machineAcquisitionChart) {
      this.getMachineAcquisitionTotal();
    }
  },
  created() {
    this.setting.loading = true
    this.getAreaList()
    this.getSceneList()
    this.getPointList()
    
    
  },
  components: {
    ElRow: Row,
    ElCol: Col,
    ElCard: Card,
    ElDatePicker: DatePicker,
    ElButton: Button,
    ElForm: Form,
    ElFormItem: FormItem,
    ElSelect: Select,
    ElOption: Option,
    ElTable: Table,
    ElTableColumn: TableColumn,
    ElPagination: Pagination
  },
  methods: {
    getPointList() {
      this.tableSetting.loading = true
      let args = this.setArgs()
      return stats.getStaus(this,args).then((response) => {
        console.log(response)
        this.tableData = response.data
        this.pagination.total = response.meta.pagination.total
        this.setting.loading = false
        this.tableSetting.loading = false
      }).catch((err) => {
        console.log(err)
        this.tableSetting.loading = false
        this.setting.loading = false
      })
    },
    setArgs() {
      let args = {
        start_date : this.handleDateTransform(this.searchForm.dataValue[0]),
        end_date: this.handleDateTransform(new Date(this.searchForm.dataValue[1]).getTime()),
        page: this.pagination.currentPage,
        scene_id: this.searchForm.scene_id,
        market_id: this.searchForm.market_id,
        area_id: this.searchForm.area,
        ar_user_id: this.searchForm.user,
        point_id: this.searchForm.point,
        alias: this.searchForm.project
      }
      if(!this.searchForm.project) {
        delete args.alias
      }
      if(!this.searchForm.user) {
        delete args.ar_user_id
      }
      if(!this.searchForm.scene_id) {
        delete args.scene_id
      }
      if(!this.searchForm.market_id) {
        delete args.market_id
      }
      if(!this.searchForm.area) {
        delete args.area_id
      }
      if(!this.searchForm.point) {
        delete args.point_id
      }
      return args
    },
    getSceneList() {
      return search.getSceneList(this).then((response) => {
        this.sceneList = response.data
      }).catch(err=> {
        console.log(err)
      })
    },
    getUser(query) {
      let args = {
        name: query
      }
      if (query !== '') {
        this.searchLoading = true
          return search.getUserList(this, args).then((response) => {
            this.userList = response.data
            if(this.userList.length == 0) {
            }
            this.searchLoading = false
          }).catch(err => {
            console.log(err)
            this.searchLoading = false
          })
      }else{
        this.searchForm.user = ''
        this.userList = []
        return false
      }
    },
    getAreaList () {
      return search.getAeraList(this).then((response) => {
       let data = response.data
       this.areaList = data
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    },
    getProject(query) {
      this.searchLoading = true
      let args = {
        name: query,
      }
      return search.getProjectList(this,args).then((response) => {
        this.projectList = response.data
        if(this.projectList.length == 0) {
          this.searchForm.project = ''
          this.projectList = []
        }
        this.searchLoading = false
      }).catch(err => {
        console.log(err)
        this.searchLoading = false
      })
    },
    getMarket(query) {
      this.searchLoading = true
      let args = {
        name: query,
        include: 'area',
        area_id: this.searchForm.area
      }
      return search.getMarketList(this,args).then((response) => {
        this.marketList = response.data
        if(this.marketList.length == 0) {
          this.searchForm.market_id = ''
          this.marketList = []
        }
        this.searchLoading = false
      }).catch(err => {
        console.log(err)
        this.searchLoading = false
      })
    },
    getPoint() {
      let args = {
        include: 'market',
        market_id: this.searchForm.market_id
      }
      this.searchLoading = true
      return search.gePointList(this, args).then((response) => {
        this.pointList = response.data
        this.searchLoading = false
      }).catch(err => {
        this.searchLoading = false
        console.log(err)
      })
    },
    areaChangeHandle() {
      this.searchForm.market_id = ''
      this.searchForm.point = ''
      this.getMarket()
    },
    marketChangeHandle() {
      this.searchForm.point = ''
      this.getPoint()
    },
    search() {
      this.getMachineAcquisitionTotal()
      this.getPointList()
    },
    resetSearch(formName) {
      this.searchForm.dataValue = [new Date().getTime() - 3600 * 1000 * 24*6, new Date().getTime()]
      this.$refs[formName].resetFields();
    },
    changePage (currentPage) {
      this.pagination.currentPage = currentPage
      this.getPointList()
    },
    handleDateTransform (valueDate) {
      let date = new Date (valueDate)
      let year = date.getFullYear() + '-';
      let mouth = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
      let day = (date.getDate() <10  ? '0'+(date.getDate()) : date.getDate()) + '';
      return year+mouth+day
    },
    getMachineAcquisitionTotal() {
      this.machineAcquisitionFlag = true
      let args = this.setArgs()
      args.id = '6'
      delete args.page
      return chart.getChartData(this, args).then((response) => {
        this.machineAcquisitionData = []
        for(let j = 0; j < response.length; j++){
          if(response[j].display_name !== '生成数') {
            this.machineAcquisitionData.push({
              value: response[j].count,
              name: response[j].display_name
            })
          }
        }
        this.handleEcharts()
        this.machineAcquisitionFlag = false
      }).catch((err) => {
        this.machineAcquisitionFlag = false
        console.log(err)
      })
    },
    handleEcharts() {
      let machineAcquisitionDom = this.$refs.acquisitionChart
      this.machineAcquisitionChart = echarts.init(machineAcquisitionDom)
      this.machineAcquisitionChart.setOption(this.machineAcquisitionOption)

      let dom4 = document.getElementById('echart4')
      let myChart4 = echarts.init(dom4)
      if (this.pieOptions && typeof this.pieOptions === 'object') {
        myChart4.setOption(this.pieOptions, true)
        // window.onresize = myChart4.resize;
      }
      window.onresize = function () { 
        this.machineAcquisitionChart.resize();
        myChart4.resize();
      };
    }
  }
}
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;
  background-color: #fff;
  .item-list-wrap{
    background: #fff;
    padding: 30px;
    .chart-wrap {
      margin: 20px 0;
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
    .search-wrap{
      margin-top: 5px;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      font-size: 16px;
      align-items: center;
      margin-bottom: 10px;
      .el-form-item{
        margin-bottom: 10px;
      }
      .el-select{
        width: 200px;
      }
      .warning{
        background: #ebf1fd;
        padding: 8px;
        margin-left: 10px;
        color: #444;
        font-size: 12px;
        i{
          color: #4a8cf3;
          margin-right: 5px;
        }
      }
    }
    .actions-wrap{
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
    .pagination-wrap{
      margin: 10px auto;
      text-align: right;
    }
  }
}
</style>
