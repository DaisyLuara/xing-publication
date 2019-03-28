<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="program-list-wrap"
    >
      <div class="program-content-wrap">
        <div class="search-wrap">
          <el-form 
            ref="filters" 
            :model="filters" 
            :inline="true">
            <el-form-item 
              label 
              prop="name">
              <el-input 
                v-model="filters.name" 
                placeholder="节目名称" 
                clearable 
                class="item-input"/>
            </el-form-item>
            <el-form-item 
              label 
              prop="beginDate">
              <el-date-picker
                v-model="filters.beginDate"
                :clearable="true"
                :picker-options="pickerOptions"
                type="daterange"
                start-placeholder="开始时间"
                end-placeholder="结束时间"
                align="right"
              />
            </el-form-item>
            <el-form-item 
              label 
              prop="getDate">
              <el-date-picker
                v-model="filters.getDate"
                :clearable="true"
                :picker-options="pickerOptions"
                type="daterange"
                start-placeholder="发放开始时间"
                end-placeholder="发放结束时间"
                align="right"
              />
            </el-form-item>
            <el-form-item 
              label 
              prop>
              <el-button 
                type="primary" 
                size="small" 
                @click="search()">搜索</el-button>
              <el-button 
                size="small" 
                @click="resetForm('filters')">重置</el-button>
            </el-form-item>
          </el-form>
        </div>
        <div class="total-wrap">
          <div>
            <span class="label">
              累计奖金(¥):
              <span class="count">{{ moneyTotal }}</span>
            </span>
            <span 
              v-if="operation || tester" 
              class="label" 
              style="margin-left:20px;">
              冻结奖金:
              <span class="count">{{ freezeTotal }}</span>
              <span
                class="details"
                style="margin-left:20px;cursor:pointer;"
                @click="freezeDetail"
              >明细</span>
            </span>
          </div>
        </div>
        <el-table 
          :data="tableData" 
          style="width: 100%">
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form 
                label-position="left" 
                inline 
                class="demo-table-expand">
                <el-form-item label="ID">
                  <span>{{ scope.row.id }}</span>
                </el-form-item>
                <el-form-item label="名称">
                  <span>{{ scope.row.project_name }}</span>
                </el-form-item>
                <el-form-item label="获取时间">
                  <span>{{ scope.row.date }}</span>
                </el-form-item>
                <el-form-item label="类型">
                  <span>{{ scope.row.type }}</span>
                </el-form-item>
                <el-form-item label="类别">
                  <span>{{ scope.row.main_type }}</span>
                </el-form-item>
                <el-form-item label="金额">
                  <span>{{ scope.row.total }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="id" 
            label="ID" 
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="project_name"
            label="名称"
            min-width="100"
          />
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="date" 
            label="获取日期" 
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="get_date"
            label="发放日期"
            min-width="100"
          />
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="type" 
            label="类型" 
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="main_type"
            label="类别"
            min-width="100"
          />
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="total" 
            label="金额" 
            min-width="100"/>
        </el-table>
        <div class="pagination-wrap">
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
import {
  getPersonRewardList,
  getPersonRewardTotal,
  handleDateTypeTransform,
  getPersonFutureRewardTotal
} from "service";
import { Cookies } from "utils/cookies";
import {
  Button,
  Input,
  Table,
  TableColumn,
  Pagination,
  Form,
  FormItem,
  MessageBox,
  DatePicker
} from "element-ui";

export default {
  components: {
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-date-picker": DatePicker
  },
  data() {
    return {
      freezeTotal: 0,
      filters: {
        name: "",
        beginDate: [
          new Date().getTime() - 3600 * 1000 * 24 * 6,
          new Date().getTime()
        ],
        getDate: []
      },
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      role: "",
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
      roles: [],
      moneyTotal: 0,
      tableData: []
    };
  },
  computed: {
    operation: function() {
      return this.roles.find(r => {
        return r.name === "operation";
      });
    },
    tester: function() {
      return this.roles.find(r => {
        return r.name === "tester";
      });
    }
  },
  created() {
    let user_info = JSON.parse(Cookies.get("user_info"));
    this.roles = user_info.roles.data;
    this.getPersonRewardList();
    this.getPersonRewardTotal();
    this.getPersonFutureRewardTotal();
  },
  methods: {
    freezeDetail() {
      this.$router.push({
        path: "/account/center/freeze"
      });
    },
    getPersonFutureRewardTotal() {
      let args = {
        name: this.filters.name
      };
      !this.filters.name ? delete args.name : args;
      args = this.dateHandle(args);

      getPersonFutureRewardTotal(this, args)
        .then(res => {
          this.freezeTotal = res.total_reward;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    getPersonRewardTotal() {
      let args = {
        name: this.filters.name
      };
      !this.filters.name ? delete args.name : args;
      args = this.dateHandle(args);
      getPersonRewardTotal(this, args)
        .then(res => {
          this.moneyTotal = res.total_reward;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    dateHandle(args) {
      if (
        JSON.stringify(this.filters.getDate) === "[]" ||
        JSON.stringify(this.filters.getDate) === "null"
      ) {
        delete args.start_get_date;
        delete args.end_get_date;
      } else {
        args.start_get_date = handleDateTypeTransform(this.filters.getDate[0]);
        args.end_get_date = handleDateTypeTransform(this.filters.getDate[1]);
      }
      if (
        JSON.stringify(this.filters.beginDate) === "[]" ||
        JSON.stringify(this.filters.beginDate) === "null"
      ) {
        delete args.start_date;
        delete args.end_date;
      } else {
        args.start_date = handleDateTypeTransform(this.filters.beginDate[0]);
        args.end_date = handleDateTypeTransform(this.filters.beginDate[1]);
      }
      return args;
    },
    getPersonRewardList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage,
        name: this.filters.name
      };
      if (this.filters.name === "") {
        delete args.name;
      }
      if (!this.filters.status) {
        delete args.status;
      }
      args = this.dateHandle(args);

      getPersonRewardList(this, args)
        .then(res => {
          this.tableData = res.data;
          this.pagination.total = res.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
          this.setting.loading = false;
        });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
      this.pagination.currentPage = 1;
      this.getPersonRewardList();
      this.getPersonRewardTotal();
      this.getPersonFutureRewardTotal();
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getPersonRewardList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.getPersonRewardList();
      this.getPersonRewardTotal();
      this.getPersonFutureRewardTotal();
    }
  }
};
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;

  .program-list-wrap {
    background: #fff;
    padding: 30px;

    .el-form-item {
      margin-bottom: 15px;
    }
    .program-content-wrap {
      .demo-table-expand {
        font-size: 0;
      }
      .demo-table-expand label {
        width: 90px;
        color: #99a9bf;
      }
      .demo-table-expand .el-form-item {
        margin-right: 0;
        margin-bottom: 0;
        width: 50%;
      }
      .icon-item {
        padding: 10px;
        width: 50%;
      }
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
        .el-select {
          width: 200px;
        }
        .item-input {
          width: 230px;
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
      .total-wrap {
        margin-top: 5px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 16px;
        align-items: center;
        margin-bottom: 10px;
        .label {
          font-size: 18px;
          margin: 5px 0;
          .count {
            color: #03a9f4;
          }
          .details {
            color: #00bcd4;
          }
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
