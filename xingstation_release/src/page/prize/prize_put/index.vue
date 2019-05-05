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
            ref="filters" 
            :model="filters" 
            :inline="true">
            <el-form-item 
              label 
              prop="name">
              <el-input
                v-model="filters.name"
                placeholder="请输入节目名称"
                clearable
                style="width: 250px;"
              />
            </el-form-item>
            <el-form-item 
              label 
              prop="company_id">
              <el-select
                v-model="filters.company_id"
                :loading="searchLoading"
                placeholder="请选择公司名称"
                filterable
                clearable
              >
                <el-option
                  v-for="item in companyList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-button 
              type="primary" 
              size="small" 
              @click="search()">搜索</el-button>
            <el-button 
              type="default" 
              size="small" 
              @click="resetSearch('filters')">重置</el-button>
          </el-form>
        </div>
        <div class="total-wrap">
          <span class="label">总数:{{ pagination.total }}</span>
          <div>
            <el-button 
              type="success" 
              size="small" 
              @click="addPrizeLaunch">新增奖品投放</el-button>
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
                <el-form-item label="奖品模版">
                  <span>{{ scope.row.policy.nam }}</span>
                </el-form-item>
                <el-form-item label="公司名称:">
                  <span>{{ scope.row.company.name }}</span>
                </el-form-item>
                <el-form-item label="点位名称:">
                  <span>{{ scope.row.point.name }}</span>
                </el-form-item>
                <el-form-item label="节目名称:">
                  <span>{{ scope.row.project.name }}</span>
                </el-form-item>
                <el-form-item label="更新时间:">
                  <span>{{ scope.row.updated_at }}</span>
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
            prop="policy" 
            label="奖品模版" 
            min-width="130">
            <template slot-scope="scope">{{ scope.row.policy.name }}</template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="company"
            label="公司名称"
            min-width="130"
          >
            <template slot-scope="scope">{{ scope.row.company.name }}</template>
          </el-table-column>
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="point" 
            label="点位名称" 
            min-width="130">
            <template slot-scope="scope">{{ scope.row.point.id !== 0 ? scope.row.point.market.area.name + '-' + scope.row.point.market.name + '-' + scope.row.point.name : '' }}</template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="project"
            label="节目名称"
            min-width="130"
          >
            <template slot-scope="scope">{{ scope.row.project.name }}</template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="updated_at"
            label="更新时间"
            min-width="100"
          />
          <el-table-column 
            label="操作" 
            min-width="100">
            <template slot-scope="scope">
              <el-button 
                size="small" 
                type="warning" 
                @click="linkToEdit(scope.row)">编辑</el-button>
            </template>
          </el-table-column>
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
import { getLaunchPirzeList, getSearchCompany } from "service";

import {
  Button,
  Input,
  Table,
  TableColumn,
  Pagination,
  Form,
  FormItem,
  MessageBox,
  Select,
  Option
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
    "el-select": Select,
    "el-option": Option
  },
  data() {
    return {
      searchLoading: false,
      filters: {
        name: "",
        company_id: null
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
      companyList: [],
      tableData: []
    };
  },
  created() {
    this.getSearchCompany();
    this.getLaunchPirzeList();
  },
  methods: {
    getSearchCompany() {
      getSearchCompany(this)
        .then(res => {
          this.companyList = res.data;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    addPrizeLaunch() {
      this.$router.push({
        path: "/prize/launch/add"
      });
    },
    linkToEdit(row) {
      this.$router.push({
        path: "/prize/launch/edit/" + row.id
      });
    },
    getLaunchPirzeList() {
      this.setting.loadingText = "拼命加载中";
      this.setting.loading = true;
      let args = {
        include: "policy,company,project,point.market.area",
        page: this.pagination.currentPage,
        name: this.filters.name,
        company_id: this.filters.company_id
      };
      if (this.filters.name === "") {
        delete args.name;
      }
      if (!this.filters.company_id) {
        delete args.company_id;
      }
      getLaunchPirzeList(this, args)
        .then(response => {
          let data = response.data;
          this.tableData = data;
          this.pagination.total = response.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(error => {
          console.log(error);
          this.setting.loading = false;
        });
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getLaunchPirzeList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.tableData = [];
      this.getLaunchPirzeList();
    },
    resetSearch(formName) {
      this.$refs[formName].resetFields();
      this.pagination.currentPage = 1;
      this.getLaunchPirzeList();
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

    .el-form-item {
      margin-bottom: 0;
    }
    .item-content-wrap {
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
          margin-bottom: 0;
        }
        .el-select {
          width: 250px;
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
          font-size: 14px;
          margin: 5px 0;
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
