<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
        <el-card class="box-card">
          <div class="search-wrap">
            <el-form :model="filters" :inline="true" ref="searchForm" >
              <!-- <el-form-item label="" prop="name">
                <el-button icon="el-icon-star-off" class="btn"></el-button>
              </el-form-item> -->
              <el-form-item label="" prop="name">
                <el-button class="btn active">所有项目</el-button>
              </el-form-item>
              <el-form-item label="" prop="">
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
          style="width: 100%" :show-header="false" :cell-class-name="tableColClassName">
            <el-table-column
              prop="name"
              label=""
              >
            </el-table-column>
          </el-table>
        </el-card>
      </div>  
    </div>
  </div>
</template>

<script>
 import team from 'service/team'
import { Button, Input, Table, TableColumn, Form, FormItem, MessageBox, Card,} from 'element-ui'

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
      titleArr: [],
      allProjectsList: [{
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
    this.getTeamsList();
    // let user_info = JSON.parse(localStorage.getItem('user_info'))
    // this.arUserName = user_info.name
    // this.dataShowFlag = user_info.roles.data[0].name === 'legal-affairs' ? false : true
    
  },
  methods: {
    tableColClassName({row, column, rowIndex, columnIndex}) {
      console.log(22)
      return "col-td";
    },
    getTeamsList () {
      this.setting.loadingText = "拼命加载中"
      this.setting.loading = true;
      let id = 'c6dc912c2f494e7ea73bed4488bb3493'
      return team.getProjectsList(this, id).then((response) => {
        this.allProjectsList = response.data
        console.log(response)
        this.titleArr = response.include
        this.setting.loading = false;
      }).catch(err => {
        console.log(err)
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
    "el-table-column":  TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-card": Card
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
      .el-table{
        font-size: 18px;
        color: #333;
      }
      .el-table--enable-row-hover .el-table__body tr:hover>td {
        background-color: #FBFDF7;
      }
      .item-content-wrap{
        position: relative;
        width: 960px;
        margin: 0 auto;
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
