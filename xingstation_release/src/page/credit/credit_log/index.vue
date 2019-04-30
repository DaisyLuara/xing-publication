<template>
  <div
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText"
    class="page-list-template">
    <div class="search-wrap">
      <el-form
        ref="searchForm"
        :inline="true"
        :model="searchForm"
        class="search-form">
        <el-form-item
          label
          prop="beginDate">
          <el-date-picker
            v-model="searchForm.beginDate"
            :default-value="searchForm.beginDate"
            :clearable="false"
            :picker-options="pickerOptions"
            type="daterange"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            align="right"/>
        </el-form-item>
        <el-form-item>
          <el-button
            type="primary"
            size="small"
            @click="search">搜索</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div class="actions-wrap">
      <span class="label">数量: {{ total }}</span>
    </div>
    <div class="table-area">
      <el-table
        :data="tableData"
        style="width: 100%">
        <el-table-column
          prop="id"
          label="ID"
          min-width="80"/>
        <el-table-column
          prop="username"
          label="用户名"
          min-width="100"/>
        <el-table-column
          prop="mobile"
          label="手机号"
          min-width="100"/>
        <el-table-column
          label="分值"
          min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.credit_config ? scope.row.credit_config.type_text : '未知' }}</span><br/>
            <span>{{ scope.row.credits }}</span>
          </template>
        </el-table-column>
        <el-table-column
          label="动作"
          min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.credit_config ? scope.row.credit_config.title:'未知' }}</span><br/>
            <span>{{ scope.row.credit_config ? scope.row.credit_config.sec:'未知' }}</span><br/>
            <span>{{(scope.row.credit_config && scope.row.credit_config.pair === 'vague') ? '模糊匹配' : '精确匹配' }}</span>
          </template>
        </el-table-column>
        <el-table-column
          prop="href"
          label="传入密钥"
          min-width="100"/>
        <el-table-column
          prop="date"
          label="时间"
          min-width="150"/>
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
import { getCreditLogListData,  handleDateTypeTransform } from "service";
import {
  Input,
  Button,
  FormItem,
  Form,
  Table,
  TableColumn,
  Pagination,
  DatePicker
} from "element-ui";
export default {
  components: {
    "el-input": Input,
    "el-button": Button,
    "el-form-item": FormItem,
    "el-form": Form,
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-pagination": Pagination,
    "el-date-picker": DatePicker
  },
  data() {
    return {
      searchForm: {
        beginDate: [
          new Date().getTime() - 3600 * 1000 * 24 * 7,
          new Date().getTime() - 3600 * 1000 * 24
        ]
      },
      currentPage: 1,
      pageSize: 10,
      total: null,
      tableData: [],

      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      pickerOptions: {
        shortcuts: [
          {
            text: "今天",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "昨天",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24);
              end.setTime(end.getTime() - 3600 * 1000 * 24);
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "最近一周",
            onClick(picker) {
              const end = new Date().getTime() - 3600 * 1000 * 24;
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "最近一个月",
            onClick(picker) {
              const end = new Date() - 3600 * 1000 * 24;
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "最近三个月",
            onClick(picker) {
              const end = new Date() - 3600 * 1000 * 24;
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
              picker.$emit("pick", [start, end]);
            }
          }
        ]
      },
    };
  },
  created() {
    this.getCreditLogList();
  },
  methods: {
    search() {
      this.currentPage = 1;
      this.getCreditLogList();
    },
    currentChange(e) {
      this.currentPage = e;
      this.getCreditLogList();
    },
    getCreditLogList() {
      this.setting.loading = true;
      let args = {
        page: this.currentPage,
        include:'credit_config',
        start_date: handleDateTypeTransform(this.searchForm.beginDate[0]),
        end_date: handleDateTypeTransform(this.searchForm.beginDate[1])
      };
      if(this.searchForm.title===''){
        delete args.title
      }
      getCreditLogListData(this, args)
        .then(response => {
          this.tableData = response.data;
          this.total = response.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(err => {
          this.setting.loading = false;
        });
    },

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

