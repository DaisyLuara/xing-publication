<template>
  <div class="root" :element-loading-text="setting.loadingText" v-loading="setting.loading">
    <div class="customer-list-wrap">
      <div class="customer-content-wrap">
        <!-- <div class="search-wrap">
          <el-form :model="filters" :inline="true" ref="searchForm">
            <el-form-item label="" prop="name">
              <el-input v-model="filters.name" placeholder="请输入客户名称" style="width: 300px;"></el-input>
            </el-form-item>
              <el-button @click="search('searchForm')" type="primary">搜索</el-button>
          </el-form>
        </div> -->
        <div class="actions-wrap">
          <span class="label">
            客户数量: {{pagination.total}}
          </span>
          <el-button size="small" type="success" @click="linkToAddClient">新增客户</el-button>
        </div>
        <el-table :data="customerList" style="width: 100%">
          <el-table-column
            prop="name"
            label="公司全称"
            >
          </el-table-column>
          <el-table-column
            prop="customer_name"
            label="联系人">
          </el-table-column>
          <el-table-column
            prop="phone"
            label="联系人电话"
            >
          </el-table-column>
          <el-table-column
            prop="address"
            label="公司地址"
            >
          </el-table-column>
          <el-table-column
            prop="status"
            label="状态"
            >
            <template slot-scope="scope">
              {{test(scope.row)}}
            </template>
          </el-table-column>
          <el-table-column
            prop="user_name"
            label="销售"
            >
            <template slot-scope="scope">
              {{scope.row.user.name}}
            </template>
          </el-table-column>
          <el-table-column label="操作" width="200">
            <template slot-scope="scope">
              <!-- <el-button size="small" type="danger">删除</el-button> -->
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
import customer from 'service/customer'

import { Button, Input, Table, TableColumn, Pagination, Form, FormItem, MessageBox } from 'element-ui'

export default {
  data () {
    return {
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      pagination: {
        total: 100,
        pageSize: 10,
        currentPage: 1
      },
      customerList: []
    }
  },
  created () {
    this.getCustomerList()
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
    test(item) {
      switch(item.status){
        case 1:
          return '待合作'
        break;
        case 2:
          return '合作中'
        break;
        case 3:
          return '已结束'
        break;
      }
    },
    getCustomerList(){
      if(this.setting.loading == true){
        return false;
      }
      let pageNum = this.pagination.currentPage
      let args = {
        include: 'user',
        page: pageNum,
      }
      this.setting.loadingText = "拼命加载中"
      this.setting.loading = true;
      return customer.getCustomerList(this, args).then(response => {
        this.setting.loading = false;
        this.customerList = response.data;
        this.pagination.total = response.meta.pagination.total;
        this.pagination.pageSize = response.meta.pagination.total_pages;
        this.handleRole();
      }).catch(error => {
        this.setting.loading = false;
      })
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
