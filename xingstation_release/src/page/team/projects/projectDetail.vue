<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
         <el-card class="box-card">
        <!-- 标题 -->
          <div class="search-wrap">
              <h3>{{infoData.attributes.content}}</h3>
          </div>
          <div class="task-wrap">
              <div class="project-wrap">
                 <el-checkbox label="tower.im集成"></el-checkbox>
              </div>
              <div class="des-wrap">
                <p v-html="infoData.attributes.desc"></p>
              </div>
              <div class="progress">
                <el-progress :percentage="70"></el-progress>
              </div>
              <ul class="project-todolist">
                 <li v-for="(item,index) in infoIncluded" :key="index" v-if="item.type==='todos_check_items'">
                   <el-checkbox :label="item.attributes.name" v-model="item.attributes.is_completed"></el-checkbox>
                   <span>（陈重,5月18日）</span>
                </li>
              </ul>
              <div class="add">
                <i class="el-icon-circle-plus-outline"></i>
                <span>添加检查项</span>
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
 
import { Button, Input, Table, Row, Col,TableColumn, Form, FormItem, MessageBox, Card, CheckboxGroup,Checkbox, Progress,} from 'element-ui'

export default {
  data () {
      return {
      ID:'',
      filters: {
        name: ''
      },
      SERVER_URL: process.env.SERVER_URL,
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
      emptyText: '暂无数据',
      infoData: {},
      infoIncluded: []
    }
  },
  mounted() {
  },
  created () {
    this.ID=this.$route.query.id;
    this.getTodosInfo()
    // alert(this.ID)
    // auth.refreshUserInfo(this).then((res) => {
    //   console.log(res)
    //   this.getTeamsList();
    // }).catch(err => {
    //   console.log(err)
    //   this.setting.loading = false;
    // })
  },
  methods: {
    //获取任务清单信息
     getTodosInfo () {
      // this.setting.loadingText = "拼命加载中"
      // this.setting.loading = true;
       team.getTodosInfo(this, this.ID).then((response) => {
         this.infoIncluded=response.included;
         this.infoData=response.data;
         console.log("==============")
         console.log( this.infoIncluded)
         console.log( this.infoData)
         console.log("==============")
         // this.setting.loading = false;
      }).catch(err => {
        console.log(err)
      //  this.setting.loading = false;
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
    'el-progress':Progress
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
          .progress{
            padding:15px 0;
            margin-left:23px;
          }
           .des-wrap,.project-todolist{
             margin-left:23px;
             font-size:14px
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
