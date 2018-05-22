<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
        <!-- <div class="search-wrap">
          <el-form  :model="filters" :inline="true">
            <el-form-item label="">
              <el-input v-model="filters.causer_name" style="width:200px" placeholder="请输入操作人的名字" clearable></el-input>
            </el-form-item>
            <el-form-item label="">
              <el-date-picker
                v-model="filters.created_at"
                type="date"
                placeholder="选择日期"
                clearable  style="width: 200px;">
              </el-date-picker>
            </el-form-item>
            <el-button @click="search" type="primary" size="small">搜索</el-button>
            <el-button @click="resetSearch" type="default" size="small">重置</el-button>
          </el-form>
        </div> -->
        <div class="actions-wrap">
          <span class="label">
            通知数量: {{pagination.total}}
          </span>
        </div>
        <el-table :data="tableData" style="width: 100%" ref="multipleTable" type="expand">
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="操作名称">
                  <span>{{scope.row.log_name}}</span>
                </el-form-item>
                <el-form-item label="操作人">
                  <span>{{scope.row.causer.name}}</span>
                </el-form-item>
                <el-form-item label="描述">
                  <span>{{scope.row.description}}</span>
                </el-form-item>
                <!-- <el-form-item label="操作详情">
                  <span>{{scope.row.properties.toString()}}</span>
                </el-form-item> -->
                <el-form-item label="创建时间">
                  <span>{{scope.row.created_at}}</span>
                </el-form-item>
                <el-form-item label="更新时间">
                  <span>{{scope.row.updated_at}}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            prop="log_name"
            label="操作名称"
            min-width="130"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="name"
            label="操作人"
            min-width="100"
            :show-overflow-tooltip="true"
            >
            <template slot-scope="scope">
              {{scope.row.causer.name}}
            </template>
          </el-table-column>
          <el-table-column
            prop="description"
            label="描述"
            min-width="150"
            >
          </el-table-column>
          <!-- <el-table-column
            prop="properties"
            label="操作详情"
            min-width="150"
            :show-overflow-tooltip="true"
            >
            <template slot-scope="scope">
              {{scope.row.properties.toString()}}
            </template>
          </el-table-column> -->
          <el-table-column
            prop="created_at"
            label="创建时间"
            min-width="120"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="updated_at"
            label="更新时间"
            min-width="120"
            :show-overflow-tooltip="true"
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
import notice from 'service/notice'

import { Button, Input, Table, TableColumn, Pagination, MessageBox, DatePicker, FormItem, Form} from 'element-ui'

export default {
  data () {
    return {
      // filters: {
      //   causer_name:'',
      //   created_at: ''
      // },
      setting: {
        loadingText: '拼命加在中...',
        loading: false,
      },
      tableData: [],
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      }
    }
  },
  mounted() {
  },
  created () {
    this.getActivitiesList()
   
  },
  methods: {
    getActivitiesList() {
      this.setting.loading = true
      let args = {
        'include': 'causer',
        // 'causer_name': this.filters.causer_name,
        // 'created_at': new Date(this.filters.created_at).getTime() / 1000,
        'page': this.pagination.currentPage
      }
      return notice.getActivitiesList(this, args).then((response) => {
        this.tableData = response.data
        this.pagination.total = response.meta.pagination.total
        this.setting.loading = false
      }).catch(err=> {
        this.setting.loading = false
        console.log(err)
      })
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.getActivitiesList()
    },
    // search (){
    //   this.pagination.currentPage = 1;
    //   this.getActivitiesList();
    // },
    // resetSearch () {
    //   this.filters.created_at = ''
    //   this.filters.causer_name = ''
    //   this.pagination.currentPage = 1;
    //   this.getActivitiesList();
    // },
  },
  components: {
    "el-table": Table,
    "el-date-picker": DatePicker,
    "el-table-column":  TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-form-item": FormItem,
    "el-form": Form,
    "el-pagination": Pagination
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
      .item-content-wrap{
        .icon-item{
          padding: 10px;
          width: 60%;
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
            margin-bottom: 0;
          }
          .item-input{
            width: 180px;
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
  }
</style>
