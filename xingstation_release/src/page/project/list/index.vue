<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
       <div class="search-wrap">
          <el-form :model="filters" :inline="true" ref="searchForm" >
            <el-form-item label="" prop="name">
              <el-input v-model="filters.name" placeholder="请输入节目名称" style="width: 250px;"></el-input>
            </el-form-item>
            <el-button @click="search()" type="primary">搜索</el-button>
            <el-button @click="resetSearch" type="default">重置</el-button>
          </el-form>
        </div>
      <div class="total-wrap">
          <span class="label">
            总数:{{pagination.total}} 
          </span>
        </div>
        <el-table :data="tableData" style="width: 100%" highlight-current-row >
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="产品">
                  <span>{{scope.row.name}}</span> 
                </el-form-item>
                <el-form-item label="图标icon">
                  <a :href="scope.row.icon" target="_blank" style="color: blue">查看</a> 
                </el-form-item>
                <el-form-item label="链接">
                  <a :href="scope.row.link" target="_blank" style="color: blue">查看</a>
                </el-form-item>
                <el-form-item label="版本">
                  <span>{{scope.row.version_name}}</span>
                </el-form-item>
                <el-form-item label="版本号">
                  <span>{{scope.row.version_code}}</span> 
                </el-form-item>
                <el-form-item label="时间">
                  <span>{{ scope.row.created_at }}</span> 
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            prop="name"
            label="产品"
            min-width="100"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="icon"
            label="图标"
            min-width="130"
            >
            <template slot-scope="scope">
              <img :src="scope.row.icon" alt="" class="icon-item"/> 
            </template>
          </el-table-column>
          <el-table-column
            prop="version_name"
            label="版本"
            min-width="100"
            :show-overflow-tooltip="true">
          </el-table-column>
          <el-table-column
            prop="version_code"
            label="版本号"
            min-width="100"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="created_at"
            label="时间"
            min-width="150"
            :show-overflow-tooltip="true">
          </el-table-column>
          <el-table-column label="操作" width="150">
            <template slot-scope="scope">
              <el-button size="small" type="primary" @click="linkToEdit()">推送</el-button> 
              <el-button size="small" type="warning" @click="showData()">编辑</el-button>
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

import { Button, Input, Table, TableColumn, Pagination,Dialog, Form, FormItem, MessageBox, DatePicker, Select, Option, CheckboxGroup, Checkbox} from 'element-ui'

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
    this.getProjectListDetails();
    // let user_info = JSON.parse(localStorage.getItem('user_info'))
    // this.arUserName = user_info.name
    // this.dataShowFlag = user_info.roles.data[0].name === 'legal-affairs' ? false : true
  },
  methods: {
   getProjectListDetails () {
      this.setting.loadingText = "拼命加载中"
      this.setting.loading = true;
      let searchArgs = {
        page : this.pagination.currentPage,
        name : this.filters.name
        }
      project.getProjectListDetails(this, searchArgs).then((response) => {
       let data = response.data
       this.tableData = data
       console.log(response);
       this.pagination.total = response.meta.pagination.total
       this.setting.loading = false;
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    }, 
    changePage (currentPage) {
      alert(currentPage)
      this.pagination.currentPage = currentPage
      this.getProjectListDetails ();
    },
    linkToEdit(){
      console.log(11111);
    },
    showData(){
      console.log(222222);
    },
    search () {
      this.pagination.currentPage = 1;
      this.tableData = []
      this.getProjectListDetails();
    },
    resetSearch () {
      this.filters.name = ''
      this.pagination.currentPage = 1;
      this.getProjectListDetails();
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
    "el-form-item": FormItem,
    'el-select': Select,
    'el-option': Option,
    'el-checkbox-group': CheckboxGroup,
    'el-checkbox': Checkbox,
    'el-dialog':Dialog
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
        .search-wrap{
          margin-top: 5px;
          display: flex;
          flex-direction: row;
          justify-content: space-between;
          font-size: 16px;
          align-items: center;
          margin-bottom: 10px;
          .el-form-item{
            margin-bottom: 0;
          }
          .el-select{
            width: 250px;
          }
          .item-input{
            width: 230px;
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
