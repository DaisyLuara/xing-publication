<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
        <div class="search-wrap">
          <el-form :model="filters" :inline="true" ref="searchForm" >
            <el-form-item label="" prop="name">
              <el-input v-model="filters.name" placeholder="请输入节目名称" style="width: 300px;"></el-input>
            </el-form-item>
            <el-form-item label="" prop="area">
              <el-select v-model="filters.area" placeholder="请选择区域" @change="areaChangeHandle">
                <el-option
                  v-for="item in areaList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="" prop="market">
              <el-select v-model="filters.market" placeholder="请选择商场" filterable :loading="marketLoading" remote :remote-method="getMarket" @change="marketChangeHandle">
                <el-option
                  v-for="item in marketList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
            <el-button @click="search('searchForm')" type="primary">搜索</el-button>
          </el-form>
        </div>
        <div class="actions-wrap">
          <span class="label">
            节目数量: {{pagination.total}}
          </span>
          <el-button size="small" type="success" @click="linkToAddItem">投放节目</el-button>
        </div>
        <el-table :data="tableData" style="width: 100%" highlight-current-row>
          <el-table-column type="selection" width="55" ></el-table-column>
          <el-table-column
            prop="name"
            label="节目名称"
            width="180"
            >
            <template slot-scope="scope">
              {{scope.row.project.name}}
            </template>
          </el-table-column>
          <el-table-column
            prop="icon"
            label="节目icon"
            >
            <template slot-scope="scope">
              <img :src="scope.row.project.icon" alt="" class="icon-item"/>
            </template>
          </el-table-column>
          <el-table-column
            prop="areaName"
            label="区域"
            >
            <template slot-scope="scope">
              {{scope.row.point.market.area.name}}
            </template>
          </el-table-column>
          <el-table-column
            prop="market_name"
            label="商场"
            width="180">
            <template slot-scope="scope">
              {{scope.row.point.market.name}}
            </template>
          </el-table-column>
          <el-table-column
            prop="point_name"
            label="点位"
            width="200">
            <template slot-scope="scope">
              {{scope.row.point.name}}
            </template>
          </el-table-column>
          <el-table-column
            prop="created_at"
            label="时间"
            width="180">
          </el-table-column>
          <el-table-column
            prop="start_date"
            label="开始时间"
            width="180">
          </el-table-column>
          <el-table-column
            prop="end_date"
            label="结束时间"
            width="180">
          </el-table-column>
          <el-table-column label="操作" width="200">
            <template slot-scope="scope">
              <el-button size="small" type="primary" @click="linkToEdit(scope.row.id)">修改</el-button>
              <el-button size="small" type="warning" @click="showData(scope.row.project.alias, scope.row.project.name, arUserName)" v-if="dataShowFlag">数据</el-button>
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
  </div>
</template>

<script>
import project from 'service/project'
import search from 'service/search'

import { Button, Input, Table, TableColumn, Pagination, Form, FormItem, MessageBox, DatePicker, Select, Option} from 'element-ui'

export default {
  data () {
    return {
      filters: {
        name: '',
        market: '',
        area: ''
      },
      marketLoading: false,
      marketList: [],
      areaList: [],
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
  created () {
    this.getProjectList()
    this.getAreaList()
    let user_info = JSON.parse(localStorage.getItem('user_info'))
    this.arUserName = user_info.name
    this.dataShowFlag = user_info.roles.data[0].name === 'legal-affairs' ? false : true
  },
  methods: {
    getProjectList () {
      this.setting.loadingText = "拼命加载中"
      this.setting.loading = true;
      let searchArgs = {
        page : this.pagination.currentPage,
        include: 'point.market.area,project',
        project_name: this.filters.name,
        area_id: this.filters.area,
        market_id: this.filters.market
      }
      project.getProjectList(this, searchArgs).then((response) => {
       let data = response.data
       this.tableData = data
       this.pagination.total = response.meta.pagination.total
      this.setting.loading = false;
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    },
    marketChangeHandle() {
      console.log(this.filters.market)
    },
    areaChangeHandle() {
      this.filters.market = ''
      this.getMarket(this.filters.market)
    },
    getMarket(query) {
      this.marketLoading = true
      let args = {
        name: query,
        include: 'area',
        area_id: this.filters.area
      }
      return search.getMarketList(this,args).then((response) => {
        this.marketList = response.data
        if(this.marketList.length == 0) {
          this.filters.market = ''
          this.marketList = []
        }
        this.marketLoading = false
      }).catch(err => {
        console.log(err)
        this.marketLoading = false
      })
    },
    search (formName) {
      this.pagination.currentPage = 1;
      this.getProjectList();
    },
    changePage (currentPage) {
      this.pagination.currentPage = currentPage
      this.getProjectList()
    },
    linkToAddItem () {
      this.$router.push({
        path: '/project/item/add'
      })
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
    linkToEdit (id) {
      this.$router.push({
        path: '/project/item/edit/' + id
      })
    },
    showData (alias,name,userId) {
      const { href } = this.$router.resolve({
        path: '/project/item/data',
        query: {
          alias: alias,
          name: name,
          uName: userId
        }
      })
      window.open(href, '_blank')
    }
  },
  components: {
    "el-table": Table,
    "el-date-picker": DatePicker,
    "el-table-column":  TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem,
    'el-select': Select,
    'el-option': Option
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
        margin-bottom: 0;
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
