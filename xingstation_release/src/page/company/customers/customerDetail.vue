<template>
  <div class="root">
    <div class="item-list-wrap">
      <div class="item-content-wrap">
        <div class="show-item-wrap" >
          <el-row :gutter="20">
            <el-col :span="8">
              <el-card class="customer-info-wrap" id="test">
                <div slot="header" class="clearfix">
                  <span>客户基础信息</span>
                </div>
                <div class="customer-content">
                  <el-row :gutter="10">
                    <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12"><div class="item-info">百度公司</div></el-col>
                    <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12"><div class="item-info">王小虎</div></el-col>
                  </el-row>
                  <el-row :gutter="10">
                    <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12"><div class="item-info">http://www.baidu.com</div></el-col>
                    <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12"><div class="item-info">13604689090</div></el-col>
                  </el-row>
                  <el-row :gutter="10">
                    <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12"><div class="item-info">行业大类测试</div></el-col>
                    <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12"><div class="item-info">上海市普陀区金沙江路 1517 弄</div></el-col>
                  </el-row>
                  <el-row :gutter="10">
                    <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12"><div class="item-info">行业小类测试</div></el-col>
                  </el-row>
                  <el-row :gutter="10">
                    <el-col :xs="24" :sm="24" :md="8" :lg="8" :xl="8"><div class="item-info"><img src="http://sapi.xingstation.com/storage/2018/01/18/HvzTVmGedMvBcNBxYyzGTmaVsfK9GUqTH980oN9b.jpeg" alt="资质证照"/></div></el-col>
                    <el-col :xs="24" :sm="24" :md="8" :lg="8" :xl="8"><div class="item-info"><img src="http://sapi.xingstation.com/storage/2018/01/18/HvzTVmGedMvBcNBxYyzGTmaVsfK9GUqTH980oN9b.jpeg" alt="资质证照"/></div></el-col>
                    <el-col :xs="24" :sm="24" :md="8" :lg="8" :xl="8"><div class="item-info"><img src="http://sapi.xingstation.com/storage/2018/01/18/HvzTVmGedMvBcNBxYyzGTmaVsfK9GUqTH980oN9b.jpeg" alt="资质证照"/></div></el-col>
                  </el-row>
                </div>
              </el-card>
            </el-col>
            <el-col :span="16">
              <el-card class="data-wrap">
                <div slot="header" class="clearfix">
                  <span>数据总览</span>
                </div>
                <div class="data-content">
                </div>
              </el-card>
            </el-col>
          </el-row>
        </div>
        <div class="search-wrap">
          <el-form :model="filters" :inline="true" ref="searchForm" :rules="rules">
            <el-form-item label="" prop="name">
              <el-input v-model="filters.name" placeholder="请输入节目名称" style="width: 300px;"></el-input>
            </el-form-item>
              <el-button @click="search('searchForm')" type="primary">搜索</el-button>
          </el-form>
        </div>
        <div class="actions-wrap">
          <span class="label">
            节目数量: 12
          </span>
          <el-button size="small" type="success" @click="linkToAddItem">新增节目</el-button>
        </div>
        <el-table :data="tableData" style="width: 100%">
          <el-table-column
            prop="name"
            label="节目名称"
            >
          </el-table-column>
          <el-table-column
            prop="release_time"
            label="发布时间"
            >
          </el-table-column>
          <el-table-column
            prop="salesman"
            label="关联销售"
            >
          </el-table-column>
          <el-table-column
            prop="count"
            label="点位数量">
          </el-table-column>
          <el-table-column
            prop="status"
            label="状态">
          </el-table-column>
          <el-table-column
            prop="put_time"
            label="投放时段">
          </el-table-column>
          <el-table-column label="操作" width="280">
            <template slot-scope="scope">
              <el-button size="small" type="danger">删除</el-button>
              <el-button size="small" type="primary" @click="linkToEdit(scope.row.id)">修改</el-button>
              <el-button size="small" type="warning">数据</el-button>
              <el-button size="small">合约</el-button>
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
import { Button, Input, Table, TableColumn, Pagination, Form, FormItem, Row, Col, MessageBox, Card} from 'element-ui'

export default {
  data () {
    return {
      filters: {
        name: ''
      },
      rules: {
        // name: [
        //   { required: true, message: '请输入客户名称', trigger: 'blur' },
        // ]
      },
      pagination: {
        total: 100,
        pageSize: 10,
        currentPage: 1
      },
      tableData: [{
        name: '苏宁易购商场导览',
        salesman: '杨清远',
        count: '3',
        put_time: '2018/01/01-2018/02/04 共计30天',
        release_time: '2018-04-06 12:03:46',
        status: '投放中'
      }, {
        name: '苏宁易购商场导览',
        salesman: '杨清远',
        put_time: '2018/01/01-2018/02/04 共计30天',
        release_time: '2018-04-06 12:03:46',
        count: '3',
        status: '投放中'
      }, {
        name: '苏宁易购商场导览',
        salesman: '杨清远',
        put_time: '2018/01/01-2018/02/04 共计30天',
        release_time: '2018-04-06 12:03:46',
        count: '3',
        status: '投放中'
      }, {
        name: '苏宁易购商场导览',
        salesman: '杨清远',
        put_time: '2018/01/01-2018/02/04 共计30天',
        release_time: '2018-04-06 12:03:46',
        count: '3',
        status: '投放中'
      }]
    }
  },
  mounted() {
    let height = document.getElementById('test').offsetHeight
    document.getElementsByClassName('data-wrap')[0].style.height = height + 'px'
  },
  methods: {
    search (formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          alert('submit!');
        } else {
          console.log('error submit!!');
          return false;
        }
      });
      console.log('search')
    },
    changePage (currentPage) {
      this.pagination.currentPage = currentPage
    },
    linkToAddItem () {
      this.$router.push({
        path: '/program/item/add'
      })
    },
    linkToEdit (id) {
      this.$router.push({
        path: '/program/item/edit/' + id
      })
    }
  },
  components: {
    "el-row": Row,
    "el-col": Col,
    "el-table": Table,
    "el-table-column":  TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
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
      .el-form-item{
        margin-bottom: 0;
      }
      .item-content-wrap{
        .show-item-wrap{
          margin-bottom: 15px; 
          .customer-info-wrap{
            .clinet-info-title{
              font-size: 24px;
              font-weight: 700;
            }
            .customer-content{
              padding: 10px;
              .item-info{
                margin-bottom: 10px;
                font-size: 16px;
                color: #444;
                word-wrap: break-word;
                img{
                  width: 50%;
                }
              }
            }
          }
          .data-wrap{
            .data-title{
              font-size: 24px;
              font-weight: 700;
            }
          }
        }
        .actions-wrap{
          margin-top: 5px;
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
