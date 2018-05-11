<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
       <div class="search-wrap">
          <el-form :model="filters" :inline="true" ref="searchForm" >
            <el-form-item label="" prop="name">
               <el-button icon="el-icon-star-off" class="btn"></el-button>
            </el-form-item>
            <el-form-item label="" prop="name">
               <el-button class="btn active">所有项目</el-button>
            </el-form-item>
            <el-form-item label="" prop="name">
               <el-button class="btn">3月峰会</el-button>
            </el-form-item>
            <el-form-item label="" prop="name">
              <el-button class="btn">APP开发</el-button>
            </el-form-item>
            <el-form-item label="" prop="name">
              <el-button class="btn">游戏模版修改</el-button>
            </el-form-item>
            <el-form-item label="" prop="name">
              <el-button class="btn">星视度平台</el-button>
            </el-form-item>
          </el-form>
        </div>
        <el-table
        :data="tableData"
        style="width: 100%" :show-header="false">
          <el-table-column
            prop="name"
            label=""
            width="180">
          </el-table-column>
        </el-table>
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
      tableData: [{
        name: '商场室内点位展示-小程序',
      }, {
        name: '商场室内点位展示-小程序',
      }, {
        name: '商场室内点位展示-小程序',
      }, {
        name: '商场室内点位展示-小程序',
      }]
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
      .el-table__row:hover {
        background-color: #FBFDF7;
      }
      .item-content-wrap{
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
            border: 1px solid #f0f0f0;
            border-radius: 20px;
          }
          .active{
            background-color: #aed4d1;
            border: 1px solid #aed4d1;
            color: #fff;
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
