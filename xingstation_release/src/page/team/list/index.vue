<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
       <div class="button-wrap">
           
        <el-form :model="filters" :inline="true" ref="searchForm" >
           <el-button  :autofocus="false" round v-for="(item,index) in groupData" :id="item.id" @click="say($event)" >{{item.attributes.name=='ACTIVIEW'?'团队成员':item.attributes.name}}</el-button>
        </el-form>
        <el-button class="btn" @click="towerAuthorization">tower授权</el-button>
        </div>
      <div class="member-wrap">
        <div class="total-wrap">
            <h3 class="label">
            {{gropName}}
            <span style="font-size:10px">共（<b>{{updateDate.length}}</b>）人</span>
            </h3>
        </div>
        <el-table :data="updateDate" style="width: 100%" :show-header="false">
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
            min-width="130"
            >
            <template slot-scope="scope">
              <a class="member-name" href="javascript:;">{{scope.row.attributes.nickname}}</a>
              <el-tag type="info" class="group-tag" v-for="(item,index) in scope.row.relationships.groups.data ">{{item.id | groupFilters(groupData)}}</el-tag>
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
      
      </div>  
    </div>
    <div id="test"></div>
  </div>
</template>

<script>
import team from 'service/team'
let th=this;
import { Button, Input, Table, TableColumn, Pagination,Dialog, Form, FormItem, MessageBox, DatePicker, Select, Option, CheckboxGroup, Checkbox,Tag} from 'element-ui'

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
      tableData: [],
      updateDate:[],
      groupData:[],
      gropName:'团队成员'
    }
  },
  mounted() {
  },
  created () {
    this.getTowerList();
    //alert(document.getElementById("button-wrap").offsetWidth);
    
    // this.getProjectListDetails();
    // let user_info = JSON.parse(localStorage.getItem('user_info'))
    // this.arUserName = user_info.name
    // this.dataShowFlag = user_info.roles.data[0].name === 'legal-affairs' ? false : true
    
  },
  methods: {
    towerAuthorization() {
    var divDom = document.getElementById('test')
    return team.getProjectsList(this).then((response) => {
        console.log(response)
        divDom.innerHTML = response
    }).catch(err => {
    console.log(err)
    })
    },
    say(event)
    {
      //获取当前元素id
      var id = event.currentTarget.id;
		   alert(id);
       //var id='ff84d99229b7402ebf873f1e9caacfa7';
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
          alert(this.groupData[i].attributes.name)
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
      //  this.responseDate=response.data;
      //  this.responseIncluded=response.included;
       this.tableData = response.data;
       this.updateDate=response.data;
       this.groupData=response.included;
       this.setting.loading = false;
       console.log(response);
       this.setting.loading = false;
      }).catch(error => {
        console.log(error)
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
    'el-dialog':Dialog,
    'el-tag':Tag
  }
}
</script>

<style lang="less" scoped>
//::selection { background:red; color:lightgreen; } 
// ::-moz-selection { background:deeppink; color:lightgreen; } 
// ::-webkit-selection { background:deeppink; color:lightgreen;}
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
          .el-button{
            margin:5px 5px 5px 0;
          }
          .el-button:hover{
            background: #72b7e8;
            color: #ffffff;  
          }
          .el-button:focus{
            background: #72b7e8;
            color: #ffffff;  
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
