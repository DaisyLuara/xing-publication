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
            <el-form-item label prop>
              <el-button type="primary" size="small" @click="search">搜索</el-button>
              <el-button size="small" @click="resetForm('filters')">重置</el-button>
            </el-form-item>
          </el-form>
        </div>
        <div class="total-wrap">
          <span class="label">冻结明细列表</span>
        </div>
        <el-table :data="tableData" style="width: 100%">
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="ID">
                  <span>{{ scope.row.id }}</span>
                </el-form-item>
                <el-form-item label="名称">
                  <span>{{ scope.row.project_name }}</span>
                </el-form-item>
                <el-form-item label="已发奖金">
                  <span>{{ scope.row.got_money }}</span>
                </el-form-item>
                <el-form-item label="冻结奖金">
                  <span>{{ scope.row.freeze_money }}</span>
                </el-form-item>
                <el-form-item label="重大责任">
                  <span>{{ scope.row.bug_record }}</span>
                </el-form-item>
                <el-form-item label="扣除奖金">
                  <span>{{ scope.row.deduction_money }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop="id" label="ID" min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="project_name"
            label="名称"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="got_money"
            label="已发奖金"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="freeze_money"
            label="冻结奖金"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="bug_record"
            label="重大责任"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="deduction_money"
            label="扣除奖金"
            min-width="100"
          />
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
import { getFutureRewardList, getSearchProjectList } from "service";
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
  MessageBox
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
    "el-form-item": FormItem
  },
  data() {
    return {
      searchLoading: false,
      projectList: [],
      filters: {
        alias: ""
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
      tableData: []
    };
  },
  created() {
    this.getFutureRewardList();
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

    getFutureRewardList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage,
        alias: this.filters.alias
      };
      if (this.filters.alias === "") {
        delete args.alias;
      }
      getFutureRewardList(this, args)
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
      this.getFutureRewardList();
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getFutureRewardList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.getFutureRewardList();
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
