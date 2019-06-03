<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="item-list-wrap"
    >
      <div class="item-content-wrap">
        <div class="search-wrap">
          <el-form 
            ref="searchForm" 
            :model="filters" 
            :inline="true">
            <el-form-item 
              label 
              prop="log_name">
              <el-input 
                v-model="filters.log_name" 
                placeholder="请输入操作名称"
                clearable 
                style="width: 250px;"/>
            </el-form-item>
            <el-form-item
              v-if="type === 'customer' "
              label
              prop="causer_id">
              <el-input
                v-model="filters.causer_id"
                placeholder="请输入操作用户ID"
                clearable
                style="width: 250px;"/>
            </el-form-item>
            <el-form-item
              v-if="type === 'user' "
              label 
              prop="causer_id">
              <el-select
                v-model="filters.causer_id"
                placeholder="请选择操作用户"
                filterable
                clearable
                style="width: 250px;"
              >
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item
              label
              prop="subject_type">
              <el-input
                v-model="filters.subject_type"
                placeholder="请输入操作对象模型名"
                clearable
                style="width: 250px;"/>
            </el-form-item>
            <el-form-item
              v-if="filters.subject_type"
              label
              prop="subject_id">
              <el-input
                v-model="filters.subject_id"
                placeholder="请输入操作对象ID"
                clearable
                style="width: 250px;"/>
            </el-form-item>

            <el-button 
              type="primary" 
              size="small" 
              @click="search()">搜索</el-button>
            <el-button 
              type="default" 
              size="small" 
              @click="resetSearch">重置</el-button>
          </el-form>
        </div>
        <div class="actions-wrap">
          <span class="label">通知数量: {{ pagination.total }}</span>
        </div>
        <el-table 
          ref="multipleTable" 
          :data="tableData" 
          style="width: 100%" 
          type="expand">
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form 
                label-position="left" 
                inline 
                class="demo-table-expand">
                <el-form-item label="操作名称">
                  <span>{{ scope.row.log_name }}</span>
                </el-form-item>
                <el-form-item label="操作人">
                  <span>{{ scope.row.causer.name }}</span>
                </el-form-item>
                <el-form-item label="描述">
                  <span>{{ scope.row.description }}</span>
                </el-form-item>
                <el-form-item label="IP">
                  <span>{{ scope.row.ip }}</span>
                </el-form-item>
                <el-form-item label="操作对象类型">
                  <span>{{ scope.row.subject_type }}</span>
                </el-form-item>
                <el-form-item label="操作对象ID">
                  <span>{{ scope.row.subject_id }}</span>
                </el-form-item>
                <el-form-item label="创建时间">
                  <span>{{ scope.row.created_at }}</span>
                </el-form-item>
                <el-form-item label="更新时间">
                  <span>{{ scope.row.updated_at }}</span>
                </el-form-item>
                <br/>
                <el-form-item label="参值记录">
                  <pre>
                    {{ scope.row.properties }}
                  </pre>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="log_name"
            label="操作名称"
            min-width="130"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="name"
            label="操作人"
            min-width="100">
            <template slot-scope="scope">{{ scope.row.causer.name }}</template>
          </el-table-column>
          <el-table-column 
            prop="description" 
            label="描述" 
            min-width="150"/>
          <el-table-column
            prop="ip"
            label="IP"
            min-width="150"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="created_at"
            label="创建时间"
            min-width="120"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="updated_at"
            label="更新时间"
            min-width="120"
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
import { getActivitiesList, getSearchUserList } from "service";

import {
  Button,
  Input,
  Table,
  TableColumn,
  Pagination,
  MessageBox,
  DatePicker,
  FormItem,
  Form,
  Select,
  Option
} from "element-ui";

export default {
  components: {
    "el-table": Table,
    "el-date-picker": DatePicker,
    "el-table-column": TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-form-item": FormItem,
    "el-form": Form,
    "el-pagination": Pagination,
    "el-select": Select,
    "el-option": Option
  },
  data() {
    return {
      type:"user",
      filters: {
        log_name: "",
        causer_id: "",
        subject_type: null,
        subject_id: null,
      },
      setting: {
        loadingText: "拼命加载中...",
        loading: false
      },
      tableData: [],
      userList: [],
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      }
    };
  },
  created() {
    this.getActivitiesList();
    this.getSearchUserList();
  },
  methods: {
    getSearchUserList() {
      getSearchUserList(this)
        .then(res => {
          this.userList = res.data;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    getActivitiesList() {
      this.setting.loading = true;
      let args = {
        include: "causer",
        type: this.type,
        log_name: this.filters.log_name,
        causer_id: this.filters.causer_id,
        subject_type: this.filters.subject_type,
        subject_id: this.filters.subject_id,
        page: this.pagination.currentPage
      };
      if (this.filters.log_name === "") {
        delete args.log_name;
      }
      if (this.filters.causer_id === "") {
        delete args.causer_id;
      }
      getActivitiesList(this, args)
        .then(response => {
          this.tableData = response.data;
          this.pagination.total = response.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(err => {
          this.setting.loading = false;
          console.log(err);
        });
    },
    search() {
      this.pagination.currentPage = 1;
      this.getActivitiesList();
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getActivitiesList();
    },
    resetSearch() {
      this.filters.log_name = "";
      this.filters.causer_id = "";
      this.filters.subject_type = null;
      this.filters.subject_id = null;
      this.pagination.currentPage = 1;
      this.getActivitiesList();
    }
  }
};
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;
  .item-list-wrap {
    background: #fff;
    padding: 30px;
    .item-content-wrap {
      .icon-item {
        padding: 10px;
        width: 60%;
      }
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
      .search-wrap {
        margin-top: 5px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 16px;
        align-items: center;
        margin-bottom: 10px;
        .el-form-item {
          margin-bottom: 0;
        }
        .item-input {
          width: 180px;
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
      .pagination-wrap {
        margin: 10px auto;
        text-align: right;
      }
    }
  }
}
</style>
