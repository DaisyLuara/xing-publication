<template>
  <div
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText"
    class="page-list-template tab"
  >
    <div class="actions-wrap">
      <el-button 
        size="small" 
        type="danger" 
        @click="deleteNotifications(selectedNotices)">删除</el-button>
      <el-button 
        v-if="unreadCount !== 0" 
        size="small" 
        type="info" 
        @click="readNotifications">全部读取</el-button>
    </div>
    <div class="table-area">
      <el-table
        ref="notificationTable"
        :data="noticeList"
        highlight-current-row
        @selection-change="handleSelect"
      >
        <el-table-column 
          v-if="setting.isOpenSelectAll" 
          type="selection" 
          width="55"/>
        <el-table-column 
          prop="id" 
          label="ID">
          <template slot-scope="scope">{{ scope.row.data.id }}</template>
        </el-table-column>
        <el-table-column 
          prop="reply_content" 
          label="内容">
          <template slot-scope="scope">{{ scope.row.data.reply_content }}</template>
        </el-table-column>
        <el-table-column 
          prop="user_name" 
          label="创建人">
          <template slot-scope="scope">{{ scope.row.data.user_name }}</template>
        </el-table-column>
        <el-table-column 
          prop="created_at" 
          label="创建时间"/>
        <el-table-column 
          prop="read_at" 
          label="读取时间"/>
        <el-table-column 
          label="操作" 
          width="150">
          <template slot-scope="scope">
            <el-button 
              size="small" 
              type="danger" 
              @click="deleteNotifications(scope.row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <div class="pagination">
      <el-pagination
        :current-page.sync="pagination.currentPage"
        :page-size="pagination.pageSize"
        :total="pagination.total"
        layout="prev, pager, next, jumper"
        @current-change="handleCurrentChange"
      />
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
  MessageBox
} from "element-ui";
import {
  getNoticeList,
  notificationStats,
  readNotifications,
  deleteNotifications
} from "service";
import { mapState } from "vuex";
export default {
  components: {
    ElSelect: Select,
    ElOption: Option,
    ElTable: Table,
    ElTableColumn: TableColumn,
    ElPagination: Pagination,
    ElButton: Button
  },
  props: {
    active: {
      type: String,
      default: "all"
    }
  },
  data() {
    return {
      unreadCount: 0,
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      setting: {
        isOpenSelectAll: true,
        loading: true,
        loadingText: "拼命加载中"
      },
      noticeList: [],
      selectedNotices: [],
      loading: false
    };
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
      if (this.lastClickTab === "all") {
        this.processState();
      }
    }
  },
  mounted() {
    this.processState();
  },
  created() {
    this.getNoticeList();
  },
  methods: {
    openSelectAll() {
      this.setting.isOpenSelectAll = !this.setting.isOpenSelectAll;
      // 清除多选数组
      if (!this.setting.isOpenSelectAll) {
        this.$refs.notificationTable.clearSelection();
      }
    },
    handleSelect(selection) {
      this.selectedNotices = selection;
    },
    deleteNotifications(data) {
      let ids = [];
      if (data.id) {
        ids.push(data.id);
      } else {
        for (let i = 0, sL = data.length; i < sL; i++) {
          ids.push(data[i].id);
        }
      }
      let args = {
        ids: ids
      };
      if (ids.length < 1) {
        this.$message.error("请先选择一个要删除的消息");
      } else {
        MessageBox.confirm("确认删除选中的消息?", "提示", {
          confirmButtonText: "确定",
          cancelButtonText: "取消",
          type: "warning"
        })
          .then(() => {
            this.setting.loadingText = "删除中";
            this.setting.loading = true;
            deleteNotifications(this, args)
              .then(response => {
                this.pagination.currentPage = 1;
                this.notificationStats();
                this.$message({
                  type: "success",
                  message: "删除成功！"
                });
              })
              .catch(error => {
                this.$message({
                  type: "warning",
                  message: error.response.data.message
                });
                this.setting.loading = false;
              });
          })
          .catch(e => {
            console.log(e);
          });
      }
    },
    getNoticeList() {
      this.setting.loading = true;
      let pageNum = this.pagination.currentPage;
      let args = {
        page: pageNum
      };
      this.setting.loadingText = "拼命加载中";
      getNoticeList(this, args)
        .then(response => {
          this.noticeList = response.data;
          this.pagination.total = response.meta.pagination.total;
          this.setting.loading = false;
          this.unreadCount = this.$store.state.notificationCount.noticeCount;
        })
        .catch(error => {
          this.setting.loading = false;
        });
    },
    notificationStats() {
      notificationStats(this)
        .then(response => {
          this.$store.commit("saveNotificationState", response);
          this.unreadCount = response.unread_count;
          this.getNoticeList();
        })
        .catch(err => {
          console.log(err);
        });
    },
    readNotifications() {
      readNotifications(this)
        .then(response => {
          this.notificationStats();
        })
        .catch(err => {
          console.log(err);
        });
    },
    processState() {
      if (this.lastClickTab === "all" || this.lastClickTab === "") {
        this.currentPage =
          this.lastPage !== null ? this.lastPage : this.currentPage;
      }
    },

    handleCurrentChange(e) {
      this.currentPage = e;
      this.getNoticeList();
    }
  }
};
</script>
<style lang="less" scoped>
.page-list-template {
  .actions-wrap {
    margin: 10px auto;
  }
}
</style>

