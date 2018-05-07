<template>
  <div class="page-list-template tab" :element-loading-text="setting.loadingText" v-loading="setting.loading">
    <div class="actions-wrap">
      <el-button size="small" type="info" @click="readNotifications">全部读取</el-button>
    </div>
    <div class="table-area">
      <el-table
        :data="noticeList">
        <el-table-column
          prop="id"
          label="ID">
          <template slot-scope="scope">
            <!-- <a :href="'/#/company/customers/edit/' + scope.row.data.id" style="color: #108aea">{{scope.row.data.id}}</a> -->
            {{scope.row.data.id}}
          </template>
        </el-table-column>
        <el-table-column
          prop="reply_content"
          label="内容">
          <template slot-scope="scope">
            {{scope.row.data.reply_content}}
          </template>
        </el-table-column>
        <el-table-column
          prop="user_name"
          label="创建人">
          <template slot-scope="scope">
            {{scope.row.data.user_name}}
          </template>
        </el-table-column>
        <el-table-column
          prop="created_at"
          label="创建时间"
          >
        </el-table-column>
        <el-table-column
          prop="read_at"
          label="读取时间"
          >
        </el-table-column>
      </el-table>
    </div>

    <div class="pagination">
      <el-pagination
        @current-change="handleCurrentChange"
        :current-page.sync="pagination.currentPage"
        :page-size="pagination.pageSize"
        layout="prev, pager, next, jumper"
        :total="pagination.total">
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
  Button,
} from 'element-ui'
import notice from 'service/notice'
import { mapState } from 'vuex'
export default {
  props: ['active'],
  components: {
    ElSelect: Select,
    ElOption: Option,
    ElTable: Table,
    ElTableColumn: TableColumn,
    ElPagination: Pagination,
    ElButton: Button
  },
  data() {
    return {
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      setting: {
        loading: true,
        loadingText: "拼命加载中"
      },
      noticeList: [],
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
  created() {
    this.getNoticeList()
  },
  methods: {
    getNoticeList (){
      this.setting.loading = true;
      let pageNum = this.pagination.currentPage
      let args = {
        page: pageNum,
      }
      this.setting.loadingText = "拼命加载中"
      return notice.getNoticeList(this, args).then(response => {
        this.noticeList = response.data
        this.pagination.total = response.meta.pagination.total;
        this.setting.loading = false;
      }).catch(error => {
        this.setting.loading = false;
      })
    },
    notificationStats() {
      return notice.notificationStats(this).then((response) => {
        this.$store.commit('saveNotificationState', response)
        this.getNoticeList()
      }).catch(err => {
        console.log(err)
      })
    },
    readNotifications() {
      return notice.readNotifications(this).then((response) => {
        this.notificationStats()
      }).catch(err => {
        console.log(err)
      })
    },
    processState() {
      if (this.lastClickTab === 'all' || this.lastClickTab === '') {
        this.currentPage =
          this.lastPage !== null ? this.lastPage : this.currentPage
        // this.getData()
      }
    },

    handleCurrentChange(e) {
      this.currentPage = e
      this.getNoticeList()
    },
  }
}
</script>
<style lang="less" scoped>
  .page-list-template{
    .actions-wrap{
      text-align: right;
      margin: 10px auto;
    }
  }
</style>

