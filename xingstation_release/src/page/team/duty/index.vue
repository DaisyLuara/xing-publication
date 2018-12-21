<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="program-list-wrap"
    >
      <div class="program-content-wrap">
        <div class="search-wrap">
          <el-form ref="filters" :model="filters" :inline="true">
            <el-form-item label prop="alias">
              <el-select
                v-model="filters.alias"
                :loading="searchLoading"
                remote
                :remote-method="getProject"
                placeholder="请输入节目名称"
                filterable
                clearable
              >
                <el-option
                  v-for="item in projectList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.alias"
                />
              </el-select>
            </el-form-item>
            <el-form-item label prop="beginDate">
              <el-date-picker
                v-model="filters.beginDate"
                :clearable="false"
                :picker-options="pickerOptions"
                type="daterange"
                start-placeholder="开始时间"
                end-placeholder="结束时间"
                align="right"
              ></el-date-picker>
            </el-form-item>
            <el-form-item label prop>
              <el-button type="primary" size="small" @click="search()">搜索</el-button>
              <el-button size="small" @click="resetForm('filters')">重置</el-button>
            </el-form-item>
          </el-form>
        </div>
        <div class="total-wrap">
          <span class="label">冻结明细列表</span>
          <el-button 
            v-if="legalAffairsManager || bonusManage" 
            type="success"
            size="small"
            @click="addDuty">新增责任</el-button>
        </div>
        <el-table :data="tableData" style="width: 100%">
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="ID">
                  <span>{{ scope.row.id }}</span>
                </el-form-item>
                <el-form-item label="名称">
                  <span>{{ scope.row.name }}</span>
                </el-form-item>
                <el-form-item label="发生日期">
                  <span>{{ scope.row.date }}</span>
                </el-form-item>
                <el-form-item label="测试">
                  <span>{{ scope.row.test }}</span>
                </el-form-item>
                <el-form-item label="运营">
                  <span>{{ scope.row.operation }}</span>
                </el-form-item>
                <el-form-item label="备注">
                  <span>{{ scope.row.remark }}</span>
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
            prop="name"
            label="名称"
            min-width="100"
          />
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="date" 
            label="发生日期" 
            min-width="100"/>
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="test" 
            label="测试" 
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="operation"
            label="运营"
            min-width="100"
          />
          <el-table-column
            v-if="legalAffairsManager || bonusManage"   
            label="操作" 
            min-width="150">
            <template slot-scope="scope">
              <el-button 
                type="primary" 
                size="small">编辑</el-button>
            </template>
          </el-table-column >
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
import { getEventList } from "service";
import { Cookies } from "utils/cookies";
import {
  Select,
  Option,
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
    "el-select": Select,
    "el-option": Option,
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
      searchLoading: false,
      projectList: [],
      filters: {
        alias: "",
        beginDate: []
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
      tableData: [{
        id:1,
        name:'aaa',
        date: '2018-09-09',
        test: 'a',
        operation: 'b',
        remark:'发生了xxxxxxxxxx重大bug，发生了xxxxxxxxxx重大bug，导致了发生了xxxxxxxxxx重大bug发生了xxxxxxxxxx重大bug，导致了发生了xxxxxxxxxx重大bug'

      }]
    };
  },
  computed: {
    legalAffairsManager: function() {
      return this.role.find(r => {
        return r.name === "legal-affairs-manager";
      });
    },
    bonusManage: function() {
      return this.role.find(r => {
        return r.name === "bonus-manager";
      });
    },
  },
  created() {
    let user_info = JSON.parse(Cookies.get("user_info"));
    this.role = user_info.roles.data;
  },
  methods: {
    getProject(query) {
      if (query !== "") {
        this.searchLoading = true;
        let args = {
          name: query
        };
        return getSearchProjectList(this, args)
          .then(response => {
            this.projectList = response.data;
            if (this.projectList.length == 0) {
              this.filters.alias = "";
              this.projectList = [];
            }
            this.searchLoading = false;
          })
          .catch(err => {
            this.searchLoading = false;
          });
      } else {
        this.projectList = [];
      }
    },
    editHandle(data) {
      this.$router.push({
        path: "duty/edit/" + data.id
      });
    },
    addDuty() {
      this.$router.push({
        path: "duty/add"
      });
    },
    getEventList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage,
        alias: this.filters.alias,
        status: this.filters.status
      };
      if (this.filters.alias === "") {
        delete args.alias;
      }
      if (this.filters.status === "") {
        delete args.status;
      }
      getEventList(this, args)
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
      this.getEventList();
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getEventList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.getEventList();
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
