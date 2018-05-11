<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
       <div class="search-wrap">
          <el-form :model="filters" :inline="true" ref="searchForm" >
            <el-form-item label="" prop="name">
               <el-button icon="el-icon-star-off" circle class="btn"></el-button>
            </el-form-item>
            <el-form-item label="" prop="name">
               <el-button round class="btn">所有项目</el-button>
            </el-form-item>
            <el-form-item label="" prop="name">
               <el-button round class="btn">3月峰会</el-button>
            </el-form-item>
            <el-form-item label="" prop="name">
              <el-button round class="btn">APP开发</el-button>
            </el-form-item>
            <el-form-item label="" prop="name">
              <el-button round class="btn">游戏模版修改</el-button>
            </el-form-item>
            <el-form-item label="" prop="name">
              <el-button round class="btn">星视度平台</el-button>
            </el-form-item>
          </el-form>
        </div>
      <div class="total-wrap">
          <span class="label">
            总数:1
          </span>
        </div>
      
      </div>  
    </div>
  </div>
</template>

<script>

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
    // this.getProjectListDetails();
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
      // this.pagination.currentPage = 1;
      // this.tableData = []
      // this.getProjectListDetails();
    },
    resetSearch () {
      this.filters.name = ''
      // this.pagination.currentPage = 1;
      // this.getProjectListDetails();
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
        margin-bottom: 10px;
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
          .btn{
            background-color: #f0f0f0;
          }
          .el-form-item{
            margin-bottom: 10px;
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
