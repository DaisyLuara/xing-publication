<template>
  <div class="root">
    <div class="customer-list-wrap">
      <div class="customer-content-wrap">
        <div class="search-wrap">
          <el-form :model="filters" :inline="true" ref="searchForm" :rules="rules">
            <el-form-item label="" prop="name">
              <el-input v-model="filters.name" placeholder="请输入客户名称" style="width: 300px;"></el-input>
            </el-form-item>
              <el-button @click="search('searchForm')" type="primary">搜索</el-button>
          </el-form>
        </div>
        <div class="actions-wrap">
          <span class="label">
            客户数量: 12
          </span>
          <el-button size="small" type="success" @click="linkToAddClient">新增客户</el-button>
        </div>
        <el-table :data="tableData" style="width: 100%">
          <el-table-column
            prop="company_name"
            label="公司全称"
            width="150"
            >
          </el-table-column>
          <el-table-column
            prop="company_web"
            label="公司网站"
            width="280"
            >
          </el-table-column>
          <el-table-column
            prop="contact"
            label="联系人">
          </el-table-column>
          <el-table-column
            prop="contact_number"
            label="联系人电话"
            width="150">
          </el-table-column>
          <el-table-column
            prop="industry_big"
            label="行业大类"
            width="150">
          </el-table-column>
          <el-table-column
            prop="company_address"
            label="公司地址"
            width="280">
          </el-table-column>
          <el-table-column
            prop="industry_samll"
            label="行业小类"
            width="150">
          </el-table-column>
          <el-table-column
            prop="qualification"
            label="资质">
          </el-table-column>
          <el-table-column
            prop="status"
            label="状态"
            width="100">
          </el-table-column>
          <el-table-column label="操作" width="220">
            <template slot-scope="scope">
              <el-button size="small" type="danger">删除</el-button>
              <el-button size="small" type="primary" @click="linkToEdit(scope.row.id)">修改</el-button>
              <el-button size="small" @click="showDetail(scope.row.id)">详情</el-button>
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
import { Button, Input, Table, TableColumn, Pagination, Form, FormItem, MessageBox } from 'element-ui'

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
        company_name: '百度',
        company_web: 'http://www.baidu.com',
        contact: '王小虎',
        contact_number: '13409809090',
        industry_big: '测试',
        company_address: '上海市普陀区金沙江路 1517 弄',
        industry_samll: '测试小类',
        qualification: '',
        status: '未开始'
      }, {
        company_name: '百度',
        company_web: 'http://www.baidu.com',
        contact: '王小虎',
        contact_number: '13409809090',
        industry_big: '测试',
        company_address: '上海市普陀区金沙江路 1517 弄',
        industry_samll: '测试小类',
        qualification: '',
        status: '开始'
      }, {
        company_name: '百度',
        company_web: 'http://www.baidu.com',
        contact: '王小虎',
        contact_number: '13409809090',
        industry_big: '测试',
        company_address: '上海市普陀区金沙江路 1517 弄',
        industry_samll: '测试小类',
        qualification: '',
        status: '结束'
      }, {
        company_name: '百度',
        company_web: 'http://www.baidu.com',
        contact: '王小虎',
        contact_number: '13409809090',
        industry_big: '测试',
        company_address: '上海市普陀区金沙江路 1517 弄',
        industry_samll: '测试小类',
        qualification: '',
        status: '暂停'
      }]
    }
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
    linkToAddClient () {
      this.$router.push({
        path: '/customer/customers/add'
      })
    },
    linkToEdit (id) {
      this.$router.push({
        path: '/customer/customers/edit/' + id
      })
    },
    showDetail (id) {
      this.$router.push({
        path: '/customer/customers/detail/' + id
      })
    }
  },
  components: {
    "el-table": Table,
    "el-table-column":  TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem
  }
}
</script>

<style lang="less" scoped>
  .root {
    // padding: 10px;
    font-size: 14px;
    color: #5E6D82;
    .customer-list-wrap{
      background: #fff;
      padding: 30px;
      .customer-content-wrap{
        .el-form-item{
          margin-bottom: 0;
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
