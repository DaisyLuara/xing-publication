<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
       <div id="button-wrap">
           
        <el-form :model="filters" :inline="true" ref="searchForm" >
           <el-button  type="primary" round>团队成员</el-button>
            <el-button  type="primary" round>开发</el-button>
            <el-button  type="primary" round>产品经理</el-button>
            <el-button  type="primary" round>测试</el-button>
            <el-button  type="primary" round>UI设计</el-button>
            <el-button  type="primary" round>颜镜店</el-button>
            <el-button  type="primary" round>星视度智造</el-button>
            <el-button  type="primary" round>平台运营</el-button>
            <el-button  type="primary" round>知识产权</el-button>
            <el-button  type="primary" round>业务</el-button>
            <el-button  type="primary" round>访客</el-button> 
          </el-form>
            
        </div>
      <div class="member-wrap">
        <div class="total-wrap">
            <h3 class="label" style="font-size:16px">
            团队成员
            <span style="font-size:10px">共（<b>54</b>）人</span>
            </h3>
        </div>
        <el-table :data="tableData" style="width: 100%" highlight-current-row >
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
            prop="name"
            label="名称"
            min-width="100"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="version_name"
            label="邮箱"
            min-width="100"
            :show-overflow-tooltip="true">
          </el-table-column>
        </el-table>
     </div>
      
      </div>  
    </div>
  </div>
</template>

<script>
// import search from 'service/search'

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
    //alert(document.getElementById("button-wrap").offsetWidth);
    
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
        #button-wrap{
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
          .el-button{
            margin-top:5px;
            margin-left:0;
            background:#72b7e8;
            border:none;
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
