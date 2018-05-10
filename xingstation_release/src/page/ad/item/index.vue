<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
        <div class="search-wrap">
          <el-form ref="adSearchForm" :model="adSearchForm"  :inline="true" class="search-form">
            <el-form-item label="" prop="adTrade">
              <el-select v-model="adSearchForm.ad_trade_id" filterable placeholder="请搜索广告行业" @change="adTradeChangeHandle('search')" clearable>
                <el-option
                  v-for="item in adTradeList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="" prop="advertiser_id">
              <el-select v-model="adSearchForm.advertiser_id" filterable placeholder="请搜索广告主" @change="advertiserChangeHandle('search')" :loading="searchLoading" clearable>
                <el-option
                  v-for="item in advertiserList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="" prop="advertisement_id">
              <el-select v-model="adSearchForm.advertisement_id" filterable  placeholder="请搜索广告" :loading="searchLoading" clearable>
                <el-option
                  v-for="item in advertisementList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="" prop="area_id">
              <el-select v-model="adSearchForm.area_id" placeholder="请选择区域" filterable @change="areaChangeHandle" clearable>
                <el-option
                  v-for="item in areaList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="" prop="market_id">
              <el-select v-model="adSearchForm.market_id"  placeholder="请搜索商场" filterable :loading="searchLoading" remote :remote-method="getMarket" @change="marketChangeHandle" clearable>
                <el-option
                  v-for="item in marketList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="" prop="point_id">
              <el-select v-model="adSearchForm.point_id" placeholder="请选择点位"   filterable :loading="searchLoading" clearable>
                <el-option
                  v-for="item in pointList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="search('adSearchForm')">搜索</el-button>
              <el-button @click="resetSearch('adSearchForm')">重置</el-button>
            </el-form-item>
          </el-form>
        </div>
        <div class="editCondition-wrap" style="padding: 0 0 15px;">
          <el-form :model="editCondition" :inline="true" ref="editForm" >
            <el-form-item label="修改选项" style="margin-bottom: 0;">
              <el-checkbox-group v-model="editCondition.conditionList">
                <el-checkbox v-for="item in conditionContent" :label="item" :key="item"></el-checkbox>
              </el-checkbox-group>
            </el-form-item>
            <el-button @click="modifyEdit" type="danger" size="small">修改</el-button>
          </el-form>
        </div>
        <div class="actions-wrap">
          <span class="label">
            广告数量: {{pagination.total}}
          </span>
          <el-button size="small" type="success" @click="linkToAddItem">投放广告</el-button>
        </div>
        <el-table :data="adList" style="width: 100%" highlight-current-row @selection-change="handleSelectionChange" ref="multipleTable">
          <el-table-column type="selection" width="55" ></el-table-column>
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="点位">
                  <span>{{scope.row.point}}</span>
                </el-form-item>
                <el-form-item label="广告主">
                  <span>{{scope.row.advertiser}}</span>
                </el-form-item>
                <el-form-item label="广告">
                  <span>{{scope.row.advertisement}}</span>
                </el-form-item>
                <el-form-item label="类型">
                  <span>{{scope.row.adType}}</span>
                </el-form-item>
                <el-form-item label="链接">
                  <a :href="scope.row.link" target="_blank" style="color: blue">查看</a>
                </el-form-item>
                <el-form-item label="大小">
                  <span>{{ scope.row.size }}</span>
                </el-form-item>
                <el-form-item label="周期">
                  <span>{{ scope.row.kTime }}</span>
                </el-form-item>
                <el-form-item label="开始时间">
                  <span>{{ scope.row.startDate }}</span>
                </el-form-item>
                <el-form-item label="结束时间">
                  <span>{{ scope.row.endDate }}</span>
                </el-form-item>
                <el-form-item label="时间">
                  <span>{{ scope.row.date }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            prop="point"
            label="点位"
            min-width="150"
            :show-overflow-tooltip="true">
          </el-table-column>
          <el-table-column
            prop="advertiser"
            label="广告主"
            min-width="80"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="advertisement"
            label="广告"
            min-width="80"
            :show-overflow-tooltip="true">
          </el-table-column>
          <el-table-column
            prop="kTime"
            label="周期"
            min-width="80"
            :show-overflow-tooltip="true"
           >
          </el-table-column>
          <el-table-column
            prop="date"
            label="时间"
            min-width="150"
            :show-overflow-tooltip="true">
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
      <el-dialog title="批量修改" :visible.sync="editVisible" @close="dialogClose" v-loading="loading">
        <el-form
        ref="adForm"
        :model="adForm" label-width="150px">
          <el-form-item label="广告行业" prop="ad_trade_id"  v-if="modifyOptionFlag.ad_trade_id" :rules="[{ type: 'number', required: true, message: '请选择广告行业', trigger: 'submit' }]">
            <el-select v-model="adForm.ad_trade_id" filterable placeholder="请搜索" @change="adTradeChangeHandle('edit')" clearable>
              <el-option
                v-for="item in adTradeList"
                :key="item.id"
                :label="item.name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="广告主" prop="advertiser_id" v-if="modifyOptionFlag.advertiser_id" :rules="[{ type: 'number', required: true, message: '请选择广告主', trigger: 'submit' }]">
            <el-select v-model="adForm.advertiser_id" placeholder="请选择" filterable @change="advertiserChangeHandle('edit')" :loading="searchLoading" clearable>
              <el-option
                v-for="item in advertiserFormList"
                :key="item.id"
                :label="item.name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="广告" prop="advertisement_id" v-if="modifyOptionFlag.advertisement_id" :rules="[{ type: 'number', required: true, message: '请选择广告', trigger: 'submit' }]">
            <el-select v-model="adForm.advertisement_id" placeholder="请选择" filterable :loading="searchLoading" clearable>
              <el-option
                v-for="item in advertisementFormList"
                :key="item.id"
                :label="item.name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="周期(s)" prop="cycle" v-if="modifyOptionFlag.cycle" :rules="[{ required: true, message: '请输入周期', trigger: 'submit'}]">
            <el-input v-model="adForm.cycle" placeholder="请输入周期" ></el-input>
          </el-form-item>
          <el-form-item label="开始时间" prop="sdate" v-if="modifyOptionFlag.sdate" :rules="[{ type: 'date', required: true, message: '请输入开始时间', trigger: 'submit' }]">
            <el-date-picker
            v-model="adForm.sdate"
            type="date"
            placeholder="选择开始时间" :editable="false">
            </el-date-picker>
          </el-form-item>
          <el-form-item label="结束时间" prop="edate" v-if="modifyOptionFlag.edate" :rules="[{ type: 'date', required: true, message: '请输入结束时间', trigger: 'submit' }]">
            <el-date-picker
            v-model="adForm.edate"
            type="date"
            placeholder="选择结束时间"
            :editable="false"
            >
            </el-date-picker>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitModify('adForm')">完成</el-button>
          </el-form-item>
         </el-form>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import ad from 'service/ad'
import search from 'service/search'

import { Button, Input, Table,Select, Option, Col, TableColumn, Pagination, Form, FormItem, MessageBox, DatePicker, Checkbox, CheckboxGroup, Dialog} from 'element-ui'

export default {
  data () {
    return {
      filters: {
        name: ''
      },
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      conditionContent: ['广告行业','广告主','广告','周期','开始时间','结束时间'],
      editCondition:{
        conditionList: [],
      },
      loading: false,
      marketList: [],
      weekdayList: [],
      weekendList: [],
      defineList: [],
      pointList: [],
      adTradeList: [],
      searchLoading: false,
      advertiserList: [],
      advertiserFormList:[],
      advertisementList:[],
      advertisementFormList: [],
      adSearchForm: {
        ad_trade_id: '',
        advertiser_id: '',
        advertisement_id: '',
        area_id: '',
        market_id: '',
        point_id: '',
      },
      areaList: [],
      dataValue: '',
      loading: true,
      arUserName: '',
      dataShowFlag: true,
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      modifyOptionFlag: {
        ad_trade_id: false,
        advertiser_id: false,
        advertisement_id: false,
        cycle: false,
        sdate: false,
        edate: false,
      },
      adForm: {
        ad_trade_id: '',
        advertiser_id: '',
        advertisement_id: '',
        cycle: 0,
        sdate: '',
        edate: '',
      },
      aoids: [],
      adList: [],
      selectAll: [],
      editVisible: false,
      slectedLength: 0
    }
  },
  mounted() {
  },
  created () {
    this.setting.loading = true
    let areaList = this.getAreaList()
    let adTradeList = this.getAdTradeList()
    let adList = this.getAdList()
    Promise.all([ areaList, adTradeList,adList]).then(() => {
      this.setting.loading = false
    }).catch((err) => {
      console.log(err)
      this.setting.loading = false
    })
    // let user_info = JSON.parse(localStorage.getItem('user_info'))
    // this.arUserName = user_info.name
    // this.dataShowFlag = user_info.roles.data[0].name === 'legal-affairs' ? false : true
  },
  methods: {
    handleSelectionChange(val) {
      this.selectAll = val
    },
    dialogClose() {
      if(!this.editVisible) {
        this.editCondition.conditionList = []
        this.$refs.multipleTable.clearSelection();
      }
    },
    getAdTradeList(){
      return search.getAdTradeList(this).then((response) => {
       let data = response.data
       this.adTradeList = data
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    },
    adTradeChangeHandle(type) {
      if(type === 'edit') {
        this.adForm.advertiser_id = ''
        } else {
        this.adSearchForm.advertiser_id = ''
      }
      this.getAdvertiserList(type)
    },
    
    advertiserChangeHandle(type){
      console.log(this.adForm.advertiser_id)
      if(type === 'edit') {
        this.adForm.advertisement_id = ''
        } else {
        this.adSearchForm.advertisement_id = ''
      }
      this.getAdvertisementList(type)
    },
    getAdvertisementList(type) {
      let args = {}
      if(type === 'edit') {
        args = {
          advertiser_id: this.adForm.advertiser_id
        }
      } else {
        args = {
          advertiser_id: this.adSearchForm.advertiser_id
        }
      }
      this.searchLoading = true
      return search.getAdvertisementList(this, args).then((response) => {
        let data = response.data
        console.log(data)
        if(type === 'edit') {
          this.advertisementFormList = data
        } else {
          this.advertisementList = data
        }
        this.searchLoading = false
      }).catch(error => {
        console.log(error)
      this.searchLoading = false
      })
    },
    getAdvertiserList(type) {
      let args = {}
      if(type === 'edit') {
        args = {
          ad_trade_id: this.adForm.ad_trade_id
        }
      } else {
        args = {
          ad_trade_id: this.adSearchForm.ad_trade_id
        }
      }
      this.searchLoading = true
      return search.getAdvertiserList(this, args).then((response) => {
        let data = response.data
        if(type === 'edit') {
          this.advertiserFormList = data
        } else {
          this.advertiserList = data
        }
        this.searchLoading = false
      }).catch(error => {
        console.log(error)
        this.searchLoading = false
      })
    },
    areaChangeHandle() {
      this.adSearchForm.market_id = ''
      this.getMarket(this.adSearchForm.market)
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
    marketChangeHandle() {
      this.adSearchForm.point_id = ''
      this.getPoint()
    },
    getPoint() {
      let args = {
        include: 'market',
        market_id: this.adSearchForm.market_id
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
    getMarket(query) {
      this.searchLoading = true
      let args = {
        name: query,
        include: 'area',
        area_id: this.adSearchForm.area_id
      }
      return search.getMarketList(this,args).then((response) => {
        this.marketList = response.data
        if(this.marketList.length == 0) {
          this.adSearchForm.market = ''
          this.adSearchForm.marketList = []
        }
        this.searchLoading = false
      }).catch(err => {
        console.log(err)
        this.searchLoading = false
      })
    },
    getAdList () {
      this.setting.loadingText = "拼命加载中"
      this.setting.loading = true;
      let searchArgs = {
        page : this.pagination.currentPage,
        ad_trade_id: this.adSearchForm.ad_trade_id,
        advertiser_id: this.adSearchForm.advertiser_id,
        advertisement_id: this.adSearchForm.advertisement_id,
        area_id: this.adSearchForm.area_id,
        market_id: this.adSearchForm.market_id,
        point_id: this.adSearchForm.point_id
      }
      this.adSearchForm.ad_trade_id !== '' ? searchArgs : delete searchArgs.ad_trade_id 
      this.adSearchForm.advertiser_id !== '' ? searchArgs : delete searchArgs.advertiser_id 
      this.adSearchForm.advertisement_id !== '' ? searchArgs : delete searchArgs.advertisement_id 
      this.adSearchForm.area_id !== '' ? searchArgs : delete searchArgs.area_id 
      this.adSearchForm.market_id !== '' ? searchArgs : delete searchArgs.market_id 
      this.adSearchForm.point_id !== '' ? searchArgs : delete searchArgs.point_id
      return ad.getAdList(this, searchArgs).then((response) => {
        let data = response.data
        this.adList = data
        this.pagination.total = response.meta.pagination.total
        this.setting.loading = false;
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    },
    search (formName) {
      this.pagination.currentPage = 1;
      this.editCondition.conditionList = []
      this.getAdList();
    },
    resetSearch (formName) {
      this.adSearchForm.ad_trade_id = ''
      this.adSearchForm.advertiser_id = ''
      this.adSearchForm.advertisement_id = ''
      this.adSearchForm.area_id = ''
      this.adSearchForm.market_id = ''
      this.adSearchForm.point_id = ''
      this.pagination.currentPage = 1;
      this.editCondition.conditionList = []
      this.getAdList();
    },
    changePage (currentPage) {
      this.pagination.currentPage = currentPage
      this.editCondition.conditionList = []
      this.getAdList()
    },
    modifyEdit() {
      if(this.selectAll.length == 0 ){
        this.$message({
          message: "请选择广告",
          type: "warning"
        })
      }else{
        if(this.editCondition.conditionList.length == 0) {
            this.$message({
            message: "请选择修改项目",
            type: "warning"
          })
        } else{
          this.getAdTradeList()
          this.adForm = {
            ad_trade_id: '',
            advertiser_id: '',
            advertisement_id: '',
            cycle: '',
            sdate: '',
            edate: '',
          }
          this.aoids = []
          let optionModify = this.editCondition.conditionList
          console.log(optionModify)
          for (let i = 0; i < this.selectAll.length; i++) {
            let id = this.selectAll[i].id
            this.aoids.push(id)
          }
          this.modifyOptionFlag.ad_trade_id = false
          this.modifyOptionFlag.advertiser_id = false
          this.modifyOptionFlag.advertisement_id = false
          this.modifyOptionFlag.cycle = false
          this.modifyOptionFlag.sdate = false
          this.modifyOptionFlag.edate = false
          for (let k = 0; k < optionModify.length; k++) {
            let type = optionModify[k]
            switch(type) {
              case '广告行业':
                this.modifyOptionFlag.ad_trade_id = true
                this.modifyOptionFlag.advertiser_id = true
                this.modifyOptionFlag.advertisement_id = true
              break
              case '广告主':
                this.modifyOptionFlag.ad_trade_id = true
                this.modifyOptionFlag.advertiser_id = true
                this.modifyOptionFlag.advertisement_id = true
              break
              case '广告':
                this.modifyOptionFlag.ad_trade_id = true
                this.modifyOptionFlag.advertiser_id = true
                this.modifyOptionFlag.advertisement_id = true
              break
              case '周期':
                this.modifyOptionFlag.cycle = true
              break
              case '开始时间':
                this.modifyOptionFlag.sdate = true
              break
              case '结束时间':
                this.modifyOptionFlag.edate = true
              break
            }
          }
          this.editVisible = true
        }
      }
    },
    submitModify(formName) {
      this.$refs[formName].validate((valid) => {
        if(valid){
        this.loading = true
          let args = {
            sdate: new Date(this.adForm.sdate).getTime() / 1000,
            edate: new Date(this.adForm.edate).getTime() / 1000,
            atid: this.adForm.ad_trade_id,
            atiid: this.adForm.advertiser_id,
            aid: this.adForm.advertisement_id,
            ktime: parseInt(this.adForm.cycle),
            aoids: this.aoids
          }
          this.modifyOptionFlag.ad_trade_id ? args : delete args.atid 
          this.modifyOptionFlag.advertiser_id ? args : delete args.atiid 
          this.modifyOptionFlag.advertisement_id ? args : delete args.aid 
          this.modifyOptionFlag.cycle ? args : delete args.ktime 
          this.modifyOptionFlag.sdate ? args : delete args.sdate 
          this.modifyOptionFlag.edate ? args : delete args.edate 
          return ad.modifyAdLaunch(this, args).then((response) => {
            this.loading = false
            this.$message({
              message: "修改成功",
              type: "success"
            })
            this.getAdList();
            this.editVisible = false
            this.editCondition.conditionList = []
          }).catch((err) => {
            this.loading = false
            console.log(err)
          })
        }else{
          this.loading = false
          console.log('error submit');
          return;
        }
      })
    },
    linkToAddItem () {
      this.$router.push({
        path: '/ad/item/add'
      })
    },
  },
  components: {
    "el-table": Table,
    "el-date-picker": DatePicker,
    "el-table-column":  TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-select": Select,
    "el-option": Option,
    "el-form-item": FormItem,
    'el-checkbox-group': CheckboxGroup,
    'el-checkbox': Checkbox,
    'el-dialog':Dialog,
    "el-col": Col
  }
}
</script>

<style lang="less" scoped>
  .root {
    font-size: 14px;
    color: #5E6D82;
    .item-list-wrap{
      background: #fff;
      padding: 30px;
      .el-select,.item-input,.el-input{
        width: 380px;
      }
      
      .el-form-item{
         margin-bottom: 20px;
      }
      .el-table__body-wrapper{
        overflow-x: auto;
        overflow-y: overlay;
        position: relative;
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
      .item-content-wrap{
        .icon-item{
          padding: 10px;
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
            margin-bottom: 5px;
          }
          .el-select{
            width: 200px;
          }
          @media (max-width: 1368px) {
            .el-select{
              width: 250px;
            }
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
          margin-top: 5px;
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
  }
</style>
