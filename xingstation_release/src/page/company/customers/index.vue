<template>
  <div 
    v-loading="setting.loading" 
    :element-loading-text="setting.loadingText"
    class="root">
    <div 
      class="customer-list-wrap">
      <div 
        class="customer-content-wrap">
        <div 
          class="search-wrap">
          <el-form 
            ref="searchForm"
            :model="filters" 
            :inline="true" 
          >
            <el-form-item 
              label="" 
              prop="name">
              <el-input 
                v-model="filters.name" 
                placeholder="请输入客户名称" 
                style="width: 200px;"/>
            </el-form-item>
            <el-button  
              type="primary" 
              size="small"
              @click="search('searchForm')">搜索</el-button>
            <el-button 
              type="default" 
              size="small"
              @click="resetSearch">重置</el-button>
          </el-form>
        </div>
        <div 
          class="actions-wrap">
          <span 
            class="label">
            客户数量: {{ pagination.total }}
          </span>
          <el-button 
            size="small" 
            type="success" 
            @click="linkToAddClient">新增客户</el-button>
        </div>
        <el-table 
          :data="customerList" 
          style="width: 100%">
          <el-table-column
            prop="name"
            label="公司全称"
          />
          <el-table-column
            prop="address"
            label="公司地址"
          />
          <el-table-column
            prop="status"
            label="状态"
          >
            <template 
              slot-scope="scope">
              {{ statusHanlde(scope.row) }}
            </template>
          </el-table-column>
          <el-table-column
            prop="user_name"
            label="销售"
          >
            <template 
              slot-scope="scope">
              {{ scope.row.user.name }}
            </template>
          </el-table-column>
          <el-table-column
            prop="created_at"
            label="创建时间"
          />
          <el-table-column
            prop="updated_at"
            label="修改时间"
          />
          <el-table-column 
            label="操作" 
            width="280">
            <template 
              slot-scope="scope">
              <el-button 
                size="small" 
                type="primary"
                @click="linkToEdit(scope.row.id)">修改</el-button>
              <el-button 
                size="small" 
                @click="showContactDetail(scope.row.id,scope.row.name)">联系人详情</el-button>
            </template>
          </el-table-column>
        </el-table>
        <div 
          class="pagination-wrap">
          <el-pagination
            :total="pagination.total"
            :page-size="pagination.pageSize"
            :current-page="pagination.currentPage"
            layout="prev, pager, next, jumper, total"
            @current-change="changePage"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import company from 'service/company'

import {
  Button,
  Input,
  Table,
  TableColumn,
  Pagination,
  Form,
  FormItem,
  MessageBox
} from 'element-ui'

export default {
  components: {
    'el-table': Table,
    'el-table-column': TableColumn,
    'el-button': Button,
    'el-input': Input,
    'el-pagination': Pagination,
    'el-form': Form,
    'el-form-item': FormItem
  },
  data() {
    return {
      filters: {
        name: ''
      },
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      pagination: {
        total: 100,
        pageSize: 10,
        currentPage: 1
      },
      customerList: []
    }
  },
  created() {
    this.getCustomerList()
  },
  methods: {
    search(formName) {
      this.pagination.currentPage = 1
      this.getCustomerList()
    },
    statusHanlde(item) {
      switch (item.status) {
        case 1:
          return '待合作'
          break
        case 2:
          return '合作中'
          break
        case 3:
          return '已结束'
          break
      }
    },
    getCustomerList() {
      if (this.setting.loading == true) {
        return false
      }
      let pageNum = this.pagination.currentPage
      let args = {
        include: 'user',
        page: pageNum,
        name: this.filters.name
      }
      this.setting.loadingText = '拼命加载中'
      this.setting.loading = true
      return company
        .getCustomerList(this, args)
        .then(response => {
          this.setting.loading = false
          this.customerList = response.data
          this.pagination.total = response.meta.pagination.total
          this.handleRole()
        })
        .catch(error => {
          this.setting.loading = false
        })
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.getCustomerList()
    },
    linkToAddClient() {
      this.$router.push({
        path: '/company/customers/add'
      })
    },
    linkToEdit(id) {
      this.$router.push({
        path: '/company/customers/edit/' + id
      })
    },
    resetSearch() {
      this.filters.name = ''
      this.pagination.currentPage = 1
      this.getCustomerList()
    },
    showContactDetail(id, name) {
      const { href } = this.$router.resolve({
        path: '/company/customers/contacts',
        query: {
          id: id,
          name: name
        }
      })
      window.open(href, '_blank')
    }
  }
}
</script>

<style lang="less" scoped>
.root {
  // padding: 10px;
  font-size: 14px;
  color: #5e6d82;
  .customer-list-wrap {
    background: #fff;
    padding: 30px;
    .customer-content-wrap {
      .el-form-item {
        margin-bottom: 0;
      }
      .actions-wrap {
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
      .pagination-wrap {
        margin: 10px auto;
        text-align: right;
      }
    }
  }
}
</style>
