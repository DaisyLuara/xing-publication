<template>
  <div
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText"
    class="page-list-template"
  >
    <div class="search-wrap">
      <el-form ref="searchForm" :inline="true" :model="searchForm" class="search-form">
        <el-form-item label prop="device_code">
          <el-input
            v-model="searchForm.device_code"
            clearable
            placeholder="deviceCode"
            class="item-input"
          />
        </el-form-item>
        <el-form-item label prop="action">
          <el-input v-model="searchForm.action" clearable placeholder="action" class="item-input"/>
        </el-form-item>
        <el-form-item label prop="code">
          <el-input v-model="searchForm.code" clearable placeholder="code" class="item-input"/>
        </el-form-item>
        <el-form-item label prop="dataValue">
          <el-date-picker
            v-model="searchForm.dataValue"
            :clearable="false"
            :picker-options="pickerOptions2"
            type="datetimerange"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            align="right"
          ></el-date-picker>
        </el-form-item>
        <el-form-item label>
          <el-button type="primary" size="small" @click="search('searchForm')">搜索</el-button>
          <el-button type="default" size="small" @click="resetSearch('searchForm')">重置</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div class="actions-wrap">
      <span class="label">数量: {{ total }}</span>
    </div>
    <div class="table-area">
      <el-table :data="tableData" style="width: 100%">
        <el-table-column type="expand">
          <template slot-scope="scope">
            <el-form label-position="left" inline class="demo-table-expand">
              <el-form-item label="ID">
                <span>{{ scope.row.id }}</span>
              </el-form-item>
              <el-form-item label="创建时间">
                <span>{{ scope.row.created_at }}</span>
              </el-form-item>
              <el-form-item label="device_code">
                <span>{{ scope.row.device_code }}</span>
              </el-form-item>
              <el-form-item label="action">
                <span>{{ scope.row.action }}</span>
              </el-form-item>
              <el-form-item label="couponID">
                <span>{{ scope.row.coupon_id }}</span>
              </el-form-item>
              <el-form-item label="code">
                <span>{{ scope.row.code }} s</span>
              </el-form-item>
              <el-form-item label="游戏名称">
                <span>{{ scope.row.game_name }}</span>
              </el-form-item>
              <el-form-item label="userNick">
                <span>{{ scope.row.user_nick }}</span>
              </el-form-item>
            </el-form>
          </template>
        </el-table-column>
        <el-table-column prop="id" label="ID" min-width="200"></el-table-column>
        <el-table-column
          :show-overflow-tooltip="true"
          prop="created_at"
          label="创建时间"
          min-width="150"
        />
        <el-table-column
          :show-overflow-tooltip="true"
          prop="device_code"
          label="deviceCode"
          min-width="120"
        />
        <el-table-column
          :show-overflow-tooltip="true"
          prop="action"
          label="action"
          min-width="120"
        />
        <el-table-column
          :show-overflow-tooltip="true"
          prop="coupon_id"
          label="couponID"
          min-width="120"
        />
        <el-table-column :show-overflow-tooltip="true" prop="code" label="code" min-width="120"/>
        <el-table-column
          :show-overflow-tooltip="true"
          prop="game_name"
          label="游戏名称"
          min-width="120"
        />
        <el-table-column
          :show-overflow-tooltip="true"
          prop="user_nick"
          label="userNick"
          min-width="180"
        />
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
import { getFeedBackList, handleDateTimeTransform } from "service";
import {
  Input,
  Button,
  FormItem,
  Form,
  Pagination,
  DatePicker
} from "element-ui";
export default {
  components: {
    "el-input": Input,
    "el-button": Button,
    "el-form-item": FormItem,
    "el-form": Form,
    "el-pagination": Pagination,
    "el-date-picker": DatePicker
  },
  data() {
    return {
      searchForm: {
        action: "",
        device_code: "",
        code: "",
        dataValue: []
      },
      pickerOptions2: {
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
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "最近一个月",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "最近三个月",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
              picker.$emit("pick", [start, end]);
            }
          }
        ]
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
    this.getFeedBackList();
  },
  methods: {
    search() {
      this.currentPage = 1;
      this.getFeedBackList();
    },
    resetSearch(formName) {
      this.$refs[formName].resetFields();
      this.currentPage = 1;
      this.getFeedBackList();
    },
    currentChange(e) {
      this.currentPage = e;
      this.getFeedBackList();
    },
    getFeedBackList() {
      this.setting.loading = true;
      let args = {
        page: this.currentPage,
        action: this.searchForm.action,
        device_code: this.searchForm.device_code,
        start_date: handleDateTimeTransform(this.searchForm.dataValue[0]),
        end_date: handleDateTimeTransform(this.searchForm.dataValue[1]),
        code: this.searchForm.code
      };
      if (!this.searchForm.action) {
        delete args.action;
      }
      if (!this.searchForm.device_code) {
        delete args.device_code;
      }
      if (this.searchForm.code === "") {
        delete args.code;
      }
      if (!this.searchForm.dataValue[0]) {
        delete args.start_date;
      }
      if (!this.searchForm.dataValue[1]) {
        delete args.end_date;
      }
      getFeedBackList(this, args)
        .then(res => {
          this.tableData = res.data;
          this.total = res.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(err => {
          this.setting.loading = false;
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
}
</style>

