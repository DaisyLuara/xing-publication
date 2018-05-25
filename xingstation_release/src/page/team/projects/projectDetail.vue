<template>
<div class="root">
  <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
    <div class="item-content-wrap">
      <el-card class="box-card">
        <div class="search-wrap">
          <h2 style="font-size:25px;font-weight:700">{{content}}</h2>
        </div>
        <hr/>
        <div class="task-wrap">
          <div class="project-wrap">
              <el-checkbox label=""></el-checkbox>
              <span style="font-size:22px;margin-left:6px">{{content}}</span>
          </div>
          <div class="des-wrap">
            <p v-html="desc"></p>
          </div>
          <ul class="project-todolist">
              <li v-for="(item,index) in infoIncluded" :key="index" v-if="item.type==='todos_check_items'">
                <el-checkbox label="" v-model="item.attributes.is_completed"></el-checkbox>
                <span>{{item.attributes.name}}</span>
                <span>
                  （{{(item.relationships.assignee.data!=''&&item.relationships.assignee.data!=null&&item.relationships.assignee.data!=undefined)?item.relationships.assignee.data.id:'' | getMemberName(members)}},{{item.attributes.due_at | getDateTime()}}）
                </span>
            </li>
          </ul>
          <div class="add">
            <i class="el-icon-plus" style="font-size:18px;color:#999"></i>
            <span style="font-size:18px;color:#999">添加检查项</span>
          </div>
        </div>  
      </el-card>
    </div>  
  </div>
</div>
</template>

<script>
import team from 'service/team' 
import auth from 'service/auth'
import { Button, Input, Table, Row, Col,TableColumn, Form, FormItem, MessageBox, Card, CheckboxGroup,Checkbox,} from 'element-ui'

export default {
  data () {
    return {
      members:[],
      desc:'',
      content:'',
      id:'',
      ID:'',
      filters: {
        name: ''
      },
      SERVER_URL: process.env.SERVER_URL,
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      loading: true,
      emptyText: '暂无数据',
      infoData: {},
      infoIncluded: []
    }
  },
  filters:{
    //获取成员名称
    getMemberName:function (arg,members) {
      if(arg==='')
      {
      return "";
      }
      for(var i=0;i<members.length;i++)
      {
        if(arg===members[i].id)
        {
        return members[i].attributes.nickname;
        }
      }
      return "";
    },
    //获取年月日
    getDateTime:function(dateTime){
      if(dateTime===''||dateTime===null||dateTime===undefined)
      {
        return '';
      }
      var dateTimes=new Date(dateTime);
      var year = dateTimes.getFullYear();
      var month =dateTimes.getMonth() + 1;
      var day = dateTimes.getDate();
      var dateStr = year + "年" + month + "月" + day+ "日";
      return dateStr;
    }
},
  mounted() { },
  created () {
    this.id=this.$route.query.id;
    this.ID=this.$route.query.ID;
    auth.refreshUserInfo(this).then((res) => {
      console.log(res)
      this.getTodosInfo()
      this.getProjectMembers();
    }).catch(err => {
      console.log(err)
    })
  },
  methods: {
    //获取任务清单信息
    getTodosInfo () {
    // this.setting.loadingText = "拼命加载中"
    //this.setting.loading = true;
    team.getTodosInfo(this, this.id).then((response) => {
      this.infoIncluded=response.included;
      this.infoData=response.data;
      this.content=this.infoData.attributes.content;
      this.desc=this.infoData.attributes.desc;
      // this.setting.loading = false;
    }).catch(err => {
      console.log(err)
      // this.setting.loading = false;
    })
  },
    //获取成员
    getProjectMembers () {
      team.getProjectMembers(this, this.ID).then((response) => {
        if(response){
          console.log('获取所有用户');
          console.log(response.data);
          this.members = response.data;
        }
      }).catch(error => {
          console.log(error)
      })
    },
  },
  components: {
    "el-table": Table,
    "el-table-column":  TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-card": Card,
    "el-row": Row,
    "el-col": Col,
    'el-checkbox-group': CheckboxGroup,
    'el-checkbox': Checkbox,
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
        position: relative;
        width: 960px;
        margin:0 auto;
        .search-wrap{
          h3{
            padding:15px 5px;
            font-size:24px;
          }
        }
        .task-wrap{
          width:750px;
          height:600px;
          padding:10px;
          
          .project-wrap{
            .el-checkbox{
              font-size: 20px;
              font-weight: 700;
            }
          }
           .des-wrap,.project-todolist{
             margin-left:23px;
            
             p{
                 font-size:18px
             }
           }
           ul{
             li{
               padding:10px 0;
             }
           }
           .add{
             padding:15px 0;
             margin-left:23px;
             //text-align: center;
             font-size:24px;
           }
        }         
    }
  }
}
</style>
