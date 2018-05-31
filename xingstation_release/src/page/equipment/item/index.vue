<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="search-wrap">
        <el-form ref="searchForm" :model="searchForm"  class="search-form">
          <el-row :gutter="20">
            <el-col :span="8" v-if="showUser">
              <el-form-item label="" prop="user_id" >
                <el-select v-model="searchForm.user_id" filterable placeholder="请搜索账号" remote :remote-method="getUser" clearable :loading="searchLoading" @change="userChangeHandle">
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
              <el-form-item label="" prop="project_id">
                <el-select v-model="searchForm.project_id" filterable placeholder="请搜索节目" remote :remote-method="getProject" :loading="searchLoading" clearable>
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
              <el-form-item label="" prop="machine_status">
                <el-select v-model="searchForm.machine_status" placeholder="请选择分类" filterable  clearable>
                  <el-option
                    v-for="item in machineList"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                  </el-option>
                </el-select>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="20">
            <el-col :span="8">
              <el-form-item label="" prop="area_id">
                <el-select v-model="searchForm.area_id" placeholder="请选择区域" filterable  clearable  @change="areaChangeHandle">
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
              <el-form-item label="" prop="point_id">
                <el-select v-model="searchForm.point_id" placeholder="请选择点位"   filterable :loading="searchLoading" clearable>
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
            <el-col :span="10">
              <el-form-item>
                <el-button type="primary" @click="search('searchForm')" size="small"> 搜索</el-button>
                <el-button @click="resetSearch('searchForm')" size="small">重置</el-button>
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
      </div>
      <div class="item-content-wrap">
        <div class="total-wrap">
          <span class="label">
            总数:{{pagination.total}} 
          </span>
        </div>
       <el-table :data="tableData" style="width: 100%" >
         <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="产品">
                  <a :href="scope.row.img" target="_blank" style="color: blue">查看</a>
                </el-form-item>
                <el-form-item label="区域">
                  <span>{{scope.row.area}}</span>
                </el-form-item>
                <el-form-item label="商场">
                  <span>{{scope.row.market}}</span>
                </el-form-item>
                <el-form-item label="点位">
                  <span>{{scope.row.point}}</span>
                </el-form-item>
                <el-form-item label="上次互动">
                  <span style="color: rgb(93, 217, 49)">{{scope.row.faceDate}}</span>
                </el-form-item>
                <el-form-item label="联网时间">
                  <span style="color: rgb(93, 217, 49)">{{ scope.row.networkDate }}</span>
                </el-form-item>
                <el-form-item label="屏幕状态">
                  <span v-if="scope.row.screenStatus==1" style="color: rgb(93, 217, 49)">开启</span>
                  <span v-if="scope.row.screenStatus==0" style="color: rgb(126, 8, 0);">关闭</span>
                </el-form-item>
                <el-form-item label="登录时间">
                  <span>{{ scope.row.loginDate }}</span>
                </el-form-item>
                <el-form-item label="开/关机">
                  <span>{{ scope.row.on_off }}</span>
                </el-form-item>
                <el-form-item label="智能插排">
                  <span style="color: rgb(93, 217, 49)">{{ scope.row.device_id == '' ? '': '有'}}</span>
                </el-form-item>
                <el-form-item label="创建时间">
                  <span>{{ scope.row.created_at }}</span>
                </el-form-item>
                <el-form-item label="修改时间">
                  <span>{{ scope.row.updated_at }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            prop="id"
            label="ID"
            min-width="80">
          </el-table-column>
          <el-table-column
            prop="img"
            label="产品"
            min-width="150"
            :show-overflow-tooltip="true"
            >
            <template slot-scope="scope">
              <img :src="scope.row.img" alt="" class="icon-item"/>
            </template>
          </el-table-column>
          <el-table-column
            prop="market"
            label="商场"
            min-width="130"
            :show-overflow-tooltip="true">
          </el-table-column>
          <el-table-column
            prop="point"
            label="点位"
            min-width="100"
            :show-overflow-tooltip="true">
          </el-table-column>
          <el-table-column
            prop="faceDate"
            label="上次互动"
            min-width="100">
            <template slot-scope="scope">
              <span style="color: rgb(93, 217, 49)">{{scope.row.faceDate}}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="networkDate"
            label="联网时间"
            min-width="100">
            <template slot-scope="scope">
              <span style="color: rgb(93, 217, 49)">{{scope.row.networkDate}}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="screenStatus"
            label="屏幕状态"
            min-width="80">
            <template slot-scope="scope">
              <span v-if="scope.row.screenStatus==1" style="color: rgb(93, 217, 49)">开启</span>
              <span v-if="scope.row.screenStatus==0" style="color: rgb(126, 8, 0);">关闭</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="loginDate"
            label="登录时间"
            min-width="100"
            :show-overflow-tooltip="true">
          </el-table-column>
          <el-table-column
            prop="on_off"
            label="开/关机"
            width="90">
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
import equipment from 'service/equipment'
import search from 'service/search'

import { Button, Input, Table, TableColumn, Pagination, Form, FormItem, MessageBox, Row, Col, Select, Option} from 'element-ui'

export default {
  data () {
    return {
      searchLoading: false,
      userList: [],
      projectList: [],
      areaList: [],
      marketList: [],
      pointList: [],
      machineList: [{
        value: 'online',
        label: '运营中'
      },
      {
        value: 'tmp',
        label: '待解决'
      },
      {
        value: 'dev',
        label: '开发&测试'
      }],      
      searchForm: {
        machine_status:'online',
        area_id: '',
        market_id: '',
        point_id: '',
        user_id: '',
        project_id: '',
      },
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      dataValue: '',
      loading: true,
      arUserName: '',
      dataShowFlag: true,
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      tableData: []
    }
  },
  mounted() {
  },
  computed: {
    showUser(){
      let user_info = JSON.parse(localStorage.getItem('user_info'))
      let roles = user_info.roles.data[0].name
      return roles == 'user' ? false : true
    },
  },
  created () {
    this.getAreaList()
    this.gettEquipmentList();
  },
  methods: {
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
        this.searchForm.user_id = ''
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
      let args = {
        ar_user_id: this.searchForm.user_id,
        name: query
      }
      if(this.showUser){
        this.searchLoading = true
        if(!this.searchForm.user_id){
          delete args.ar_user_id
        } 
        return search.getProjectList(this,args).then((response) => {
          this.projectList = response.data
          this.searchLoading = false
        }).catch(err => {
          console.log(err)
          this.searchLoading = false
        })
      } else {
          let user_info = JSON.parse(localStorage.getItem('user_info'))
          this.searchForm.user_id = user_info.ar_user_id
          args.ar_user_id = this.searchForm.user_id
          this.searchLoading = true
          return search.getProjectList(this,args).then((response) => {
            this.projectList = response.data
            this.searchLoading = false
          }).catch(err => {
            console.log(err)
            this.searchLoading = false
          })
       }
    },
    getMarket(query) {
      this.searchLoading = true
      let args = {
        name: query,
        include: 'area',
        area_id: this.searchForm.area_id
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
    userChangeHandle(){
      this.searchForm.project_id = ''
      if(this.searchForm.user_id) {
        this.getProject('')
      }
    },
    areaChangeHandle() {
      this.searchForm.market_id = ''
      this.searchForm.point_id = ''
      this.getMarket()
    },
    marketChangeHandle() {
      this.searchForm.point_id = ''
      this.getPoint()
    },
    search() {
      this.gettEquipmentList()
    },
    resetSearch(formName) {
      this.$refs[formName].resetFields();
      this.gettEquipmentList()
    },
    gettEquipmentList () {
      this.setting.loadingText = "拼命加载中"
      this.setting.loading = true;
      let args = {
        page : this.pagination.currentPage,
        market_id: this.searchForm.market_id,
        area_id: this.searchForm.area_id,
        point_id: this.searchForm.point_id,
        alias: this.searchForm.project_id,
        ar_user_id: this.searchForm.user_id,
        machine_status : this.searchForm.machine_status
      }
      if(!this.searchForm.project_id) {
        delete args.alias
      }
      if(!this.searchForm.user_id) {
        delete args.ar_user_id
      }
      if(!this.searchForm.machine_status) {
        delete args.machine_status 
      }
      if(!this.searchForm.market_id) {
        delete args.market_id
      }
      if(!this.searchForm.area_id) {
        delete args.area_id
      }
      if(!this.searchForm.point_id) {
        delete args.point_id
      }
      equipment.gettEquipmentList(this, args).then((response) => {
       let data = response.data
       this.tableData = data
       this.tableData.forEach(function (value) {
        value.on_off=value['on_time']+'-'+value['off_time']+'点';
         });
       this.pagination.total = response.meta.pagination.total
       this.setting.loading = false;
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    },
    
    changePage (currentPage) {
      this.pagination.currentPage = currentPage
      this.gettEquipmentList ();
    },
  },
  components: {
    "el-row": Row,
    "el-col": Col,
    "el-table": Table,
    "el-select": Select,
    "el-option": Option,
    "el-table-column":  TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem
  }
}
</script>

<style lang="less" scoped>
  .root {
    font-size: 14px;
    color: #5E6D82;
    .item-list-wrap{
      .search-wrap{
        padding: 30px;
        background: #fff;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 16px;
        align-items: center;
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
      background: #fff;
      padding: 30px;
      .el-form-item{
        margin-bottom: 0;
      }
      .item-content-wrap{
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
        .icon-item{
          padding: 10px;
          width: 50%;
        }
        .total-wrap{
          margin-top: 5px;
          display: flex;
          flex-direction: row;
          justify-content: space-between;
          font-size: 16px;
          align-items: center;
          margin-bottom: 10px;
          .label {
            font-size: 14px;
            margin:5px 0;
          }
        }
        .pagination-wrap{
          margin: 10px auto;
          text-align: right;
        }
      }
    }
  }
</style>
