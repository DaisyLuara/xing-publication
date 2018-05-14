<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
       <el-card class="box-card">
        <div class="button-wrap">  
        <el-form :model="filters" :inline="true" ref="searchForm" >
           <el-button class="button" size="small" v-for="(item,index) in groupData" :key="item.id" :class="{'active': item.id == active}"  @click="say(item.id)" >{{item.attributes.name=='ACTIVIEW'?'团队成员':item.attributes.name}}</el-button>
        </el-form>
        <!-- <el-button class="btn" @click="towerAuthorization">tower授权</el-button> -->
        </div>
      <div class="member-wrap">
        <div class="total-wrap">
            <h1 class="label" style="font-size:32px">
            {{gropName}}
            <span style="font-size:14px;color: #999;">(共<b>{{updateDate.length}}</b>人)</span>
            </h1>
        </div>
        <el-table :data="updateDate" style="width: 100%" :show-header="false" :empty-text="emptyText">
          <el-table-column
            prop="attributes.gavatar"
            label="图标"
            min-width="80"
            align="center"
            >
            <template slot-scope="scope">
              <img :src="scope.row.attributes.gavatar" alt=""  class="icon-item"/> 
            </template>
          </el-table-column>
          <el-table-column
            prop="attributes.nickname"
            label="名称"
            min-width="150"
            >
            <template slot-scope="scope">
              <a class="member-name" href="javascript:;">{{scope.row.attributes.nickname}}</a>
              <el-tag type="info" class="group-tag" v-for="(item,index) in scope.row.relationships.groups.data " :key="item.id" size="mini">{{item.id | groupFilters(groupData)}}</el-tag>
            </template>
          </el-table-column>
          <el-table-column
            prop="attributes.phone"
            label="手机号"
            min-width="100"
            >
            <template slot-scope="scope">
              <span>{{scope.row.attributes.phone==null?'-':scope.row.attributes.phone}}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="attributes.mailbox"
            label="邮箱"
            min-width="100"
           >
          </el-table-column>
          <el-table-column
            prop="attributes.comment"
            label="内容"
            min-width="100"
            align="right"
           >
            <template slot-scope="scope">
              <span>{{scope.row.attributes.comment==null?'-':scope.row.attributes.comment}}</span>
            </template>
          </el-table-column>
        </el-table>
      </div>
      </el-card>
      </div>  
    </div>
    <div id="test"></div>
  </div>
</template>

<script>
import team from 'service/team'
import auth from 'service/auth'

let th=this;
import { Button, Input, Table, TableColumn,Dialog, Form, FormItem ,Tag,Card} from 'element-ui'

export default {
  data () {
      return {
      active:'',
      SERVER_URL: process.env.SERVER_URL,
      filters: {
        name: ''
      },
      emptyText: '暂无数据',
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
      tableData: [],
      updateDate:[],
      groupData:[],
      gropName:'团队成员'
    }
  },
  mounted() {
  },
  created () {
    auth.refreshUserInfo(this).then((res) => {
      console.log(res)
    }).catch(err => {
      console.log(err)
    })
    // if(localStorage.getItem('tower_auth') === 'false') {
    //     console.log(22)
    //     this.emptyText = '请点击tower授权按钮'
    // } else {
    //     console.log(2333)
    //     this.emptyText = '暂无数据'
    //     this.getTowerList();
    // }
      this.getTowerList();
  },
  methods: {
    towerAuthorization() {
        let user_info = JSON.parse(localStorage.getItem('user_info'))
        window.open(this.SERVER_URL+ '/api/login/tower')
    },

    say(id)
    {
       this.active=id;
       this.updateDate=new Array();
       for(var i=0;i<this.groupData.length;i++)
       {
        if(id===this.groupData[i].id)
        {
          this.gropName=this.groupData[i].attributes.name;
          if('ACTIVIEW'==this.groupData[i].attributes.name)
          {
            this.gropName='团队成员';
            this.updateDate=this.tableData;
          }
          break;
        }
       }
       if(this.updateDate.length<=0)
       {
       //获取对应分组数据
        for(var i=0;i<this.tableData.length;i++)
        {
          for(var j=0;j<this.tableData[i].relationships.groups.data.length;j++)
          {
            if(id==this.tableData[i].relationships.groups.data[j].id)
          {
            this.updateDate.push(this.tableData[i]);
            break;
          }
          }
      }
     }   
    },
    getTowerList () {
      this.setting.loadingText = "拼命加载中"
      this.setting.loading = true;
      let id = 'c6dc912c2f494e7ea73bed4488bb3493'
      team.getTowerList(this, id).then((response) => {
        localStorage.setItem('tower_auth', true)
        this.tableData = response.data;
        this.updateDate=response.data;
        this.groupData=response.included;
        this.active=this.groupData[0].id;
        this.setting.loading = false;
       console.log(response);
       this.setting.loading = false;
      }).catch(error => {
      this.setting.loading = false;
      })
    }
  },
  filters:{
    groupFilters:function (arg,datas) {
     for(var i=0;i<datas.length;i++){
      if(arg==datas[i].id){
         return datas[i].attributes.name;
      }
     }
        return "";
    }
    },
  components: {
    "el-table": Table,
    "el-table-column":  TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-form": Form,
    "el-form-item": FormItem,
    'el-dialog':Dialog,
    'el-tag':Tag,
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
      .el-form-item{
        margin-bottom: 0;
      }
      .el-button:hover {
        color: #606266;
      }
      .el-table{
        font-size: 18px;
        color: #333;
      }
      .item-content-wrap{
        position: relative;
        width: 960px;
        margin: 0 auto;
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
          padding: 5px;
          width: 45%;
          border-radius:50%;
        }
        .button-wrap{
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
          .button{
            background-color: #f0f0f0;
            border: 1px solid #f0f0f0;
            border-radius: 20px;
          }
          .el-button{
            margin:5px 5px 5px 0;
          }
          .active{
            background-color: #aed4d1;
            border: 1px solid #aed4d1;
            color: #fff;
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
            font-size: 18px;
            margin:10px  0 10px 5px;
          }
        }
        .member-name{
          display:block;
          font-size:16px;
          font-weight:700;
        }
        .group-tag{
           margin:5px 5px 5px 0;
           //padding:0 5px;
           border-radius:20px;
           font-size:14px;
        }
      }
    }
  }
</style>
