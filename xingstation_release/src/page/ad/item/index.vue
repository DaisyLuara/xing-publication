<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
        <div class="search-wrap">
          <!-- <el-form :model="filters" :inline="true" ref="searchForm" >
            <el-form-item label="" prop="name">
              <el-input v-model="filters.name" placeholder="请输入节目名称" style="width: 300px;"></el-input>
            </el-form-item>
              <el-button @click="search('searchForm')" type="primary">搜索</el-button>
          </el-form> -->
          <el-form
        ref="adForm"
        :model="adForm"  :inline="true">
        <el-form-item label="" prop="adTrade">
          <el-select v-model="adForm.ad_trade_id" filterable placeholder="请搜索广告行业" @change="adTradeChangeHandle">
            <el-option
              v-for="item in adTradeList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="" prop="advertiser_id">
          <el-select v-model="adForm.advertiser_id" filterable placeholder="请搜索广告主" @change="advertiserChangeHandle" :loading="searchLoading">
            <el-option
              v-for="item in advertiserList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
         <el-form-item label="" prop="advertisement_id">
          <el-select v-model="adForm.advertisement_id" filterable  placeholder="请搜索广告" :loading="searchLoading" @change="advertisementChangeHandle">
            <el-option
              v-for="item in advertisementList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="" prop="area_id">
          <el-select v-model="adForm.area_id" placeholder="请选择区域" filterable @change="areaChangeHandle">
            <el-option
              v-for="item in areaList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="" prop="market_id">
          <el-select v-model="adForm.market_id"  placeholder="请搜索商场" filterable :loading="searchLoading" remote :remote-method="getMarket" @change="marketChangeHandle">
            <el-option
              v-for="item in marketList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="" prop="point_id">
          <el-select v-model="adForm.point_id" placeholder="请选择点位"   filterable :loading="searchLoading" >
            <el-option
              v-for="item in pointList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="search('adForm')">搜索</el-button>
          <el-button @click="resetSearch('adForm')">重置</el-button>
        </el-form-item>
      </el-form>
        </div>
        <div class="actions-wrap">
          <span class="label">
            广告数量: {{pagination.total}}
          </span>
          <el-button size="small" type="success" @click="linkToAddItem">投放广告</el-button>
        </div>
        <el-table :data="adList" style="width: 100%" highlight-current-row>
          <el-table-column type="selection" width="55" ></el-table-column>
          <el-table-column
            prop="point"
            label="点位"
            min-width="200"
            fixed
            >
          </el-table-column>
          <el-table-column
            prop="advertiser"
            label="广告主"
            min-width="100"
            fixed
            >
          </el-table-column>
          <el-table-column
            prop="advertisement"
            label="广告"
            width="100"
            >
          </el-table-column>
          <el-table-column
            prop="adType"
            label="广告类型"
            width="100"
            >
          </el-table-column>
          <el-table-column
            prop="link"
            label="链接"
            width="80"
            >
            <template slot-scope="scope">
              <a :href="scope.row.link" target="_blank" style="color: blue">查看</a>
            </template>
          </el-table-column>
          <el-table-column
            prop="size"
            label="大小"
            width="80"
            >
          </el-table-column>
          <el-table-column
            prop="kTime"
            label="周期"
            width="80"
            >
          </el-table-column>
          <el-table-column
            prop="startDate"
            label="开始时间"
            min-width="200"
            >
          </el-table-column>
          <el-table-column
            prop="endDate"
            label="结束时间"
            min-width="200"
            >
          </el-table-column>
          <el-table-column
            prop="date"
            label="时间"
            min-width="200"
            >
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
import ad from 'service/ad'
import search from 'service/search'

import { Button, Input, Table,Select,Option, TableColumn, Pagination, Form, FormItem, MessageBox, DatePicker} from 'element-ui'

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
      marketList: [],
      weekdayList: [],
      weekendList: [],
      defineList: [],
      pointList: [],
      adTradeList: [],
      searchLoading: false,
      advertiserList: [],
      advertisementList:[],
      adForm: {
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
      adList: []
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
     getAdTradeList(){
      return search.getAdTradeList(this).then((response) => {
       let data = response.data
       this.adTradeList = data
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    },
    adTradeChangeHandle() {
      console.log(this.adForm.adTrade)
      this.getAdvertiserList()
    },
    advertisementChangeHandle (){
      console.log(this.adForm.advertisement)
    },
    advertiserChangeHandle(){
      console.log(this.adForm.advertiser)
      this.getAdvertisementList()
    },
    getAdvertisementList() {
      let args = {
        advertiser_id: this.adForm.advertiser_id
      }
      return search.getAdvertisementList(this, args).then((response) => {
       let data = response.data
       this.advertisementList = data
       console.log(data)
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    },
    getAdvertiserList() {
      let args = {
        ad_trade_id: this.adForm.ad_trade_id
      }
      return search.getAdvertiserList(this, args).then((response) => {
       let data = response.data
       this.advertiserList = data
       console.log(data)
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    },
    areaChangeHandle() {
      console.log(this.adForm.area)
      this.adForm.market = ''
      this.getMarket(this.adForm.market)
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
      console.log(this.adForm.market)
      this.adForm.point = []
      this.getPoint()
    },
    getPoint() {
      let args = {
        include: 'market',
        market_id: this.adForm.market_id
      }
      this.searchLoading = true
      return search.gePointList(this, args).then((response) => {
        console.log(response)
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
        area_id: this.adForm.area_id
      }
      return search.getMarketList(this,args).then((response) => {
        this.marketList = response.data
        if(this.marketList.length == 0) {
          this.adForm.market = ''
          this.adForm.marketList = []
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
        ad_trade_id: this.adForm.ad_trade_id,
        advertiser_id: this.adForm.advertiser_id,
        advertisement_id: this.adForm.advertisement_id,
        area_id: this.adForm.area_id,
        market_id: this.adForm.market_id,
        point_id: this.adForm.point_id
      }
      this.adForm.ad_trade_id !== '' ? searchArgs : delete searchArgs.ad_trade_id 
      this.adForm.advertiser_id !== '' ? searchArgs : delete searchArgs.advertiser_id 
      this.adForm.advertisement_id !== '' ? searchArgs : delete searchArgs.advertisement_id 
      this.adForm.area_id !== '' ? searchArgs : delete searchArgs.area_id 
      this.adForm.market_id !== '' ? searchArgs : delete searchArgs.market_id 
      this.adForm.point_id !== '' ? searchArgs : delete searchArgs.point_id
      console.log(searchArgs) 
      return ad.getAdList(this, searchArgs).then((response) => {
       let data = response.data
       console.log(data)
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
      this.getAdList();
    },
    resetSearch (formName) {
      this.adForm.ad_trade_id = ''
      this.adForm.advertiser_id = ''
      this.adForm.advertisement_id = ''
      this.adForm.area_id = ''
      this.adForm.market_id = ''
      this.adForm.point_id = ''
      this.pagination.currentPage = 1;
      this.getAdList();
    },
    changePage (currentPage) {
      this.pagination.currentPage = currentPage
      this.getAdList()
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
    "el-form-item": FormItem
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
      .el-form-item{
        margin-bottom: 5px;
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
