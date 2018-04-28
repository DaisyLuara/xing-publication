<template>
  <div class="contract-wrap">
    <div class="actions-wrap">
      <el-button size="small" type="success"  @click="linkToAddContract">新增合约</el-button>
    </div>
    <el-table :data="tableData" style="width: 100%">
      <el-table-column
        prop="name"
        label="合约名称"
        min-width="200"
        >
      </el-table-column>
      <el-table-column
        prop="info"
        label="资料信息"
        min-width="200"
        >
      </el-table-column>
      <el-table-column label="操作" width="280">
        <template slot-scope="scope">
          <el-button size="small" type="danger">删除</el-button>
          <el-button size="small" type="primary">修改</el-button>
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
</template>
<script>

import { Button, Table, TableColumn, Pagination, MessageBox } from 'element-ui'

export default {
  name: 'contractList',
  data () {
    return {
      pagination: {
        total: 100,
        pageSize: 10,
        currentPage: 1
      },
      tableData: [{
        name: '苏宁易购商场导览',
        info: '杨清远'
      }, {
        name: '苏宁易购商场导览',
        info: '杨清远',
      }, {
        name: '苏宁易购商场导览',
        info: '杨清远'
      }, {
        name: '苏宁易购商场导览',
        info: '杨清远'
      }]
    }
  },
  created () {
  },
  methods: {
    changePage() {
      console.log(1)
    },
    linkToAddContract () {
      this.$router.push({
        path: '/contract/list/add'
      })
    }
  },
  components: {
    "el-table": Table,
    "el-table-column":  TableColumn,
    "el-button": Button,
    "el-pagination": Pagination,
  }
}
</script>

<style lang="less" scoped>
  .contract-wrap{
    background: #fff;
    margin: 10px;
    padding: 30px;
    .actions-wrap{
      padding-bottom: 10px;
      text-align: right;
    }
    .pagination-wrap{
      margin: 10px auto;
      text-align: right;
    }
  }

</style>
