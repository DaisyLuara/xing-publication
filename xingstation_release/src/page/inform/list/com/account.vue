<template>
  <div class="page-list-template tab">
    <div class="table-area">
      <el-table
        :data="tableData">
        <el-table-column
          prop="name"
          label="名称">
        </el-table-column>
        <el-table-column
          prop="info"
          label="信息">
        </el-table-column>
        <el-table-column
          prop="date"
          label="时间">
        </el-table-column>
      </el-table>
    </div>

    <div class="pagination">
      <el-pagination
        @current-change="handleCurrentChange"
        :current-page.sync="currentPage"
        :page-size="pageSize"
        layout="prev, pager, next, jumper"
        :total="pageTotal">
      </el-pagination>
    </div>
    
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { Table, TableColumn, Pagination, Input } from 'element-ui'
export default {
  props: ['active'],
  components: {
    ElTable: Table,
    ElTableColumn: TableColumn,
    ElPagination: Pagination,
    ElInput: Input
  },
  data() {
    return {
      tableData: [{
          name: '狗年大吉',
          info: '启动失败',
          date: '2018-4-12'
        }, {
          name: '狗年大吉',
          info: '启动失败',
          date: '2018-4-12'
        }, {
          name: '狗年大吉',
          date: '2018-4-12',
          info: '启动失败',
        }, {
          name: '狗年大吉',
          date: '2018-4-12',
          info: '启动失败',
        }],
      pageSize: 10,
      loading: true,
      currentPage: 1,
      pageTotal: 1
    }
  },
  watch: {
    lastClickTab: function() {
      if (this.lastClickTab === 'account') {
        this.processState()
      }
    }
  },
  computed: {
    ...mapState({
      lastPage: state => state.appState.lastPage,
      lastSearchValue: state => state.appState.lastSearchValue,
      lastClickTab: state => state.appState.lastClickTab
    })
  },
  mounted() {
    this.processState()
  },
  methods: {
    processState() {
      if (this.lastClickTab === 'account') {
        this.currentPage =
          this.lastPage !== null ? this.lastPage : this.currentPage
        // this.getData()
      }
    },
    handleCurrentChange(e) {
      this.currentPage = e
      this.getData()
    },
    getData() {
      const getUrl = '/api/location/locations'
      let params = {
        params: {
          page_num: this.currentPage,
          location_name: this.searchValueName,
          location_type_id: 2,
          limit: this.pageSize
        }
      }
      this.$http.get(getUrl, params).then(response => {
        if (response.status === 200) {
          this.tableData = response.data.data
          this.pageSize = response.data.page.limit
          this.pageTotal = response.data.page.count
          this.loading = false
        } else {
          this.$message.error(response.message)
        }
      }).catch(error => {
        console.log(error)
      })
    },
  }
}
</script>

<style lang="less" scoped>

</style>
