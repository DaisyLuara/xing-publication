<template>
  <div class="root">
    <div class="item-list-wrap">
      <div class="search-wrap">
        <el-form ref="searchForm" :model="searchForm"  class="search-form">
          <el-row :gutter="20">
            <el-col :span="8">
              <el-form-item label="" prop="user">
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
                <el-select v-model="searchForm.scene_id" placeholder="请选择场景" filterable  clearable :loading="searchLoading">
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
                    :value="item.id">
                  </el-option>
                </el-select>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="20">
            <el-col :span="8">
              <el-form-item label="" prop="area">
                <el-select v-model="searchForm.area" placeholder="请选择区域" filterable  clearable :loading="searchLoading" @change="areaChangeHandle">
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
              <div class="" id="echart4" ref="mychart" style="height: 500px; width: 100%"></div>
            </el-card>
          </el-col>
          <el-col :span="12">
            <el-card shadow="always">
              <div class="" id="echart3" ref="mychart" style="height: 500px; width: 100%"></div>
            </el-card>
          </el-col>
        </el-row>
      </div>
      <div class="actions-wrap">
        <span class="label">
          数量: {{pagination.total}}
        </span>
      </div>
      <el-table
        :data="tableData5"
        style="width: 100%">
        <el-table-column type="expand">
          <template slot-scope="props">
            <el-form label-position="left" inline class="demo-table-expand">
              <el-form-item label="ID">
                <span>{{ props.row.id }}</span>
              </el-form-item>
              <el-form-item label="点位">
                <span>{{ props.row.point }}</span>
              </el-form-item>
              <el-form-item label="商品 ID">
                <span>{{ props.row.id }}</span>
              </el-form-item>
              <el-form-item label="人气">
                <span>{{ props.row.lookers }}</span>
              </el-form-item>
              <el-form-item label="引流">
                <span>{{ props.row.drainage }}</span>
              </el-form-item>
              <el-form-item label="召唤">
                <span>{{ props.row.call }}</span>
              </el-form-item>
              <el-form-item label="输出">
                <span>{{ props.row.out }}</span>
              </el-form-item>
              <el-form-item label="时间">
                <span>{{ props.row.created_at }}</span>
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
          :show-overflow-tooltip="true">
        </el-table-column>
        <el-table-column
          label="人气"
          prop="lookers">
        </el-table-column>
        <el-table-column
          label="引流"
          prop="drainage"
          :show-overflow-tooltip="true">
        </el-table-column>
        <el-table-column
          label="召唤"
          prop="call"
          :show-overflow-tooltip="true">
        </el-table-column>
        <el-table-column
          label="输出"
          prop="out"
          :show-overflow-tooltip="true">
        </el-table-column>
        <el-table-column
          label="时间"
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
</template>

<script>
import echarts from 'echarts/lib/echarts'
import echartsGl from 'echarts-gl'
import {  Button, Row, Col, Card, DatePicker,FormItem, Form ,Select, Option, Table, TableColumn, Pagination} from 'element-ui'
import search from 'service/search'

export default {
  data() {
    return {
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      dataValue: [new Date().getTime() - 3600 * 1000 * 24*6, new Date().getTime()],
      searchForm: {
        scene_id:'',
        area: '',
        market_id: '',
        point: '',
        user: '',
        project: '',
        dataValue: [new Date().getTime() - 3600 * 1000 * 24*6, new Date().getTime()]
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
          text: '过去7天',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 6);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '过去30天',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 29);
            picker.$emit('pick', [start, end]);
          }
        },]
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
      funnelOptions: {
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
            data: [
              {value: 9139, name: '围观总数'},
              {value: 5463, name: '互动总数'},
              {value: 2434, name: '扫码拉新总数'},
            ]
          }
        ]
      },
      tableData5: [{
        id: '440177',
        point: '镇江豪客来万达金街店一楼',
        lookers: '围观：7',
        drainage: '玩家：1品誉心智转化率：14%',
        call: '会员：0	购买意愿转化率：0%',
        out: '扫码/生成数：0/5场景扫码率：0%',
        created_at: '2018-05-18'
      }, {
        id: '440177',
        point: '镇江豪客来万达金街店一楼',
        lookers: '围观：7',
        drainage: '玩家：1品誉心智转化率：14%',
        call: '会员：0	购买意愿转化率：0%',
        out: '扫码/生成数：0/5场景扫码率：0%',
        created_at: '2018-05-18'
      }, {
        id: '440177',
        point: '镇江豪客来万达金街店一楼',
        lookers: '围观：7',
        drainage: '玩家：1品誉心智转化率：14%',
        call: '会员：0	购买意愿转化率：0%',
        out: '扫码/生成数：0/5场景扫码率：0%',
        created_at: '2018-05-18'
      }, {
        id: '440177',
        point: '镇江豪客来万达金街店一楼',
        lookers: '围观：7',
        drainage: '玩家：1品誉心智转化率：14%',
        call: '会员：0	购买意愿转化率：0%',
        out: '扫码/生成数：0/5场景扫码率：0%',
        created_at: '2018-05-18'
      }]
    }
  },
  mounted() {
    this.handleEcharts()
  },
  created() {
    this.getAreaList()
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
    getUser(query) {
      let args = {
        name: query
      }
      if (query !== '') {
        this.searchLoading = true
          return search.getUser(this, args).then((response) => {
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
      console.log(args)
      return search.getMarketList(this,args).then((response) => {
        console.log(response)
        console.log(response.data)
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
      console.log(33)
      this.getMarket()
    },
    marketChangeHandle() {
      console.log(this.searchForm.market_id)
      this.searchForm.point = ''
      this.getPoint()
    },
    search(formName) {

    },
    resetSearch(formName) {

    },
    changePage (currentPage) {
      this.pagination.currentPage = currentPage
      // this.getAdList()
    },
    handleEcharts() {
      let dom3 = document.getElementById('echart3')
      let myChart3 = echarts.init(dom3)
      if (this.funnelOptions && typeof this.funnelOptions === 'object') {
        myChart3.setOption(this.funnelOptions, true)
        // window.onresize = myChart3.resize;
        // myChart3.resize()
      }
      let dom4 = document.getElementById('echart4')
      let myChart4 = echarts.init(dom4)
      if (this.pieOptions && typeof this.pieOptions === 'object') {
        myChart4.setOption(this.pieOptions, true)
        // window.onresize = myChart4.resize;
      }

      window.onresize = function () { 
        myChart3.resize();
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
    .pagination-wrap{
      margin: 10px auto;
      text-align: right;
    }
  }
}
</style>
