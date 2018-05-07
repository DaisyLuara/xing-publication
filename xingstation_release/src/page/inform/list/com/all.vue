<template>
  <div class="page-list-template tab" v-loading="loading">
    <div class="table-area">
      <el-table
        :data="tableData">
        <el-table-column
          prop="name"
          label="名称">
        </el-table-column>
        <el-table-column
          prop="reply_content"
          label="内容">
        </el-table-column>
        <el-table-column
          prop="created_at"
          label="时间"
          >
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
import {
  Select,
  Option,
  Table,
  TableColumn,
  Pagination,
} from 'element-ui'
import { mapState } from 'vuex'
export default {
  props: ['active'],
  components: {
    ElSelect: Select,
    ElOption: Option,
    ElTable: Table,
    ElTableColumn: TableColumn,
    ElPagination: Pagination,
  },
  data() {
    return {
      currentPage: 1,
      pageTotal: 1,
      pageSize: 10,
      tableData: [{
          date: '2018-04-08 09:55:42',
          name: '宏伊广场',
          area: '上海',
          icon: ''
        }, {
          date: '2018-04-08 09:55:42',
          name: '宏伊广场',
          area: '上海',
          icon: ''
        }, {
          date: '2018-04-08 09:55:42',
          name: '宏伊广场',
          area: '上海',
          icon: ''
        }, {
          date: '2018-04-08 09:55:42',
          name: '宏伊广场',
          area: '上海',
          icon: ''
        }],
      loading: false,
    }
  },
  computed: {
    ...mapState({
      lastPage: state => state.appState.lastPage,
      lastSearchValue: state => state.appState.lastSearchValue,
      lastClickTab: state => state.appState.lastClickTab
    })
  },
  watch: {
    lastClickTab: function() {
      if (this.lastClickTab === 'all') {
        this.processState()
      }
    }
  },
  mounted() {
    this.processState()
  },
  methods: {
    processState() {
      if (this.lastClickTab === 'all' || this.lastClickTab === '') {
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
          location_type_id: 1,
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
