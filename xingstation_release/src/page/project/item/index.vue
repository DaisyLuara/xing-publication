<template>
  <div class="root">
    <div class="item-list-wrap" v-loading="loading">
      <div class="item-content-wrap">
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
            节目数量: {{pagination.total}}
          </span>
          <el-button size="small" type="success" @click="linkToAddItem">投放节目</el-button>
        </div>
        <el-table :data="tableData" style="width: 100%">
          <el-table-column
            prop="name"
            label="节目名称"
            >
            <template slot-scope="scope">
              {{scope.row.project.name}}
            </template>
          </el-table-column>
          <el-table-column
            prop="icon"
            label="节目icon"
            >
            <template slot-scope="scope">
              <img :src="scope.row.project.icon" alt="" class="icon-item"/>
            </template>
          </el-table-column>
          <el-table-column
            prop="pointName"
            label="区域"
            >
            <template slot-scope="scope">
              {{scope.row.point.name}}
            </template>
          </el-table-column>
          <el-table-column
            prop="created_at"
            label="时间"
            >
          </el-table-column>
          <el-table-column
            prop="start_date"
            label="开始时间"
            >
          </el-table-column>
          <el-table-column
            prop="end_date"
            label="结束时间"
            >
          </el-table-column>
          <el-table-column label="操作" width="280">
            <template slot-scope="scope">
              <!-- <el-button size="small" type="danger">删除</el-button> -->
              <el-button size="small" type="primary" @click="linkToEdit(scope.row.id)">修改</el-button>
              <el-button size="small" type="warning" @click="showData(scope.row.id, scope.row.name)">数据</el-button>
              <!-- <el-button size="small">合约</el-button> -->
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
import project from 'service/project'

import { Button, Input, Table, TableColumn, Pagination, Form, FormItem, MessageBox, DatePicker} from 'element-ui'

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
      dataValue: '',
      loading: true,
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
    this.getProjectList()
  },
  methods: {
    getProjectList () {
      let searchArgs = {
        page : this.pagination.currentPage,
        include: 'point,project'
      }
      project.getProjectList(this, searchArgs).then((response) => {
       console.log(response)
       let data = response.data
       this.tableData = data
       this.pagination.total = response.meta.pagination.total
       this.loading = false
      }).catch(error => {
        console.log(error)
       this.loading = false
      })
    },
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
      this.getProjectList()
    },
    linkToAddItem () {
      this.$router.push({
        path: '/project/item/add'
      })
    },
    linkToEdit (id) {
      this.$router.push({
        path: '/project/item/edit/' + id
      })
    },
    showData (id,name) {
      const { href } = this.$router.resolve({
        path: '/project/item/data',
        query: {
          id: id,
          name: name
        }
      })
      window.open(href, '_blank')
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
    "el-form-item": FormItem
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
        .icon-item{
          padding: 10px;
          width: 50%;
        }
        .search-wrap{
          margin-top: 5px;
          display: flex;
          flex-direction: row;
          justify-content: space-between;
          font-size: 16px;
          align-items: center;
          margin-bottom: 10px;
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
