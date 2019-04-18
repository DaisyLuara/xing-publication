<template>
  <div
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText"
    class="page-list-template"
  >
    <div class="search-wrap">
      <el-form ref="searchForm" :inline="true" :model="searchForm" class="search-form">
        <el-form-item label prop="description">
          <el-input v-model="searchForm.description" placeholder="请输入备注" clearable/>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" size="small" @click="search">搜索</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div class="actions-wrap">
      <span class="label">反馈数量: {{ total }}</span>
    </div>
    <div class="table-area">
      <el-table :data="tableData" style="width: 100%">
        <el-table-column prop="id" label="ID" min-width="200"/>
        <el-table-column prop="title" label="问题" min-width="200"/>
        <el-table-column prop="company_name" label="公司" min-width="180"/>
        <el-table-column prop="createable_name" label="账号" min-width="180"/>
        <el-table-column prop="created_at" label="时间" min-width="180"/>
        <el-table-column label="操作" min-width="100">
          <template slot-scope="scope">
            <el-button type="success" size="small" @click="saveFeedback(scope.row)">详情</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="pagination">
      <el-pagination
        :current-page="currentPage"
        :page-size="pageSize"
        :total="total"
        layout="total, prev, pager, next, jumper"
        @current-change="currentChange"
      />
    </div>
  </div>
</template>
<script>
import { getFeedbackList } from "service";
import {
  Input,
  Button,
  FormItem,
  Form,
  Table,
  TableColumn,
  Pagination
} from "element-ui";
export default {
  components: {
    "el-input": Input,
    "el-button": Button,
    "el-form-item": FormItem,
    "el-form": Form,
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-pagination": Pagination
  },
  data() {
    return {
      searchForm: {
        description: ""
      },
      currentPage: 1,
      pageSize: 10,
      total: null,
      tableData: [],
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      }
    };
  },
  created() {
    this.getFeedbackList();
  },
  methods: {
    search() {
      this.currentPage = 1;
      this.getFeedbackList();
    },
    currentChange(e) {
      this.currentPage = e;
      this.getFeedbackList();
    },
    getFeedbackList() {
      this.setting.loading = true;
      let args = {
        page: this.currentPage,
        include: "childrenFeedback"
      };
      getFeedbackList(this, args)
        .then(response => {
          this.tableData = response.data;
          this.total = response.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(err => {
          this.setting.loading = false;
        });
    },
    saveFeedback(row) {
      this.$router.push({
        path: "/feedback/list/save/" + row.id
      });
    }
  }
};
</script>

<style lang="less" scoped>
.page-list-template {
  padding: 30px;
  .search-wrap {
    margin-top: 5px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    font-size: 16px;
    align-items: center;
    margin-bottom: 10px;
    .el-form-item {
      margin-bottom: 10px;
    }
    .el-input {
      width: 200px;
    }
    .warning {
      background: #ebf1fd;
      padding: 8px;
      margin-left: 10px;
      color: #444;
      font-size: 12px;
      i {
        color: #4a8cf3;
        margin-right: 5px;
      }
    }
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
  .copy-link {
    color: #03a9f4;
  }
}
</style>

