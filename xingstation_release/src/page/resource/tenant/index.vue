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
            :model="searchForm" 
            :inline="true">
            <el-form-item 
              label 
              prop="status">
              <el-select 
                v-model="searchForm.status" 
                placeholder="请选择状态" 
                filterable 
                clearable>
                <el-option
                  v-for="item in statusList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item label>
              <el-button 
                type="primary" 
                size="small" 
                @click="search">搜索</el-button>
              <el-button 
                type="default" 
                size="small" 
                @click="resetSearch('searchForm')">重置</el-button>
            </el-form-item>
          </el-form>
        </div>
        <div class="total-wrap">
          <span class="label">总数:{{ pagination.total }}</span>
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
                <el-form-item label="商户">
                  <span>{{ scope.row.company_name }}</span>
                </el-form-item>
                <el-form-item label="名称">
                  <span>{{ scope.row.name }}</span>
                </el-form-item>
                <el-form-item label="资源">
                  <a 
                    :href="scope.row.url" 
                    target="_blank" 
                    style="color: blue">查看</a>
                </el-form-item>
                <el-form-item label="创建时间">
                  <span>{{ scope.row.created_at }}</span>
                </el-form-item>
                <el-form-item label="状态">
                  <span>{{ scope.row.status === 0 ? '未通过' : scope.row.status === 1 ? '通过' : '待审核' }}</span>
                </el-form-item>
                <el-form-item label="审核人">
                  <span>{{ scope.row.audit_user_name }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="id" 
            label="ID" 
            min-width="80"/>
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="company_name" 
            label="商户" 
            min-width="100"/>
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="name" 
            label="名称" 
            min-width="100"/>
          <el-table-column 
            prop="url" 
            label="资源" 
            min-width="130">
            <template slot-scope="scope">
              <img 
                :src="scope.row.url" 
                alt 
                class="icon-item">
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="created_at"
            label="创建时间"
            min-width="100"
          />
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="status" 
            label="状态" 
            min-width="100">
            <template
              slot-scope="scope"
            >{{ scope.row.status === 0 ? '未通过' : scope.row.status === 1 ? '通过' : '待审核' }}</template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="audit_user_name"
            label="审核人"
            min-width="150"
          />
          <el-table-column 
            label="操作" 
            min-width="100">
            <template slot-scope="scope">
              <el-button 
                size="small" 
                type="success" 
                @click="pass(scope.row)">通过</el-button>
              <el-button 
                size="small" 
                type="warning" 
                @click="reject(scope.row)">驳回</el-button>
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
import { getTenantMediaList, TenantMediaAudit } from "service";

import {
  Button,
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
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-select": Select,
    "el-option": Option
  },
  data() {
    return {
      searchForm: {
        status: ""
      },
      statusList: [
        {
          id: 0,
          name: "未通过"
        },
        {
          id: 1,
          name: "通过"
        },
        {
          id: 2,
          name: "待审核"
        }
      ],
      loading: false,
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      tableData: []
    };
  },
  created() {
    this.getTenantMediaList();
  },
  methods: {
    pass(data) {
      let id = data.id;
      let args = {
        status: 1
      };
      let message = "审核通过!";
      this.TenantMediaAudit(id, args, message);
    },
    reject(data) {
      let id = data.id;
      let args = {
        status: 0
      };
      let message = "驳回成功!";
      this.TenantMediaAudit(id, args, message);
    },
    TenantMediaAudit(id, args, message) {
      this.$confirm("审核, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          TenantMediaAudit(this, id, args)
            .then(res => {
              this.getTenantMediaList();
              this.$message({
                type: "success",
                message: message
              });
            })
            .catch(err => {
              console.log(err);
            });
        })
        .catch(() => {});
    },
    getTenantMediaList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage,
        status: this.searchForm.status
      };
      this.searchForm.status === "" ? delete args.status : "";
      getTenantMediaList(this, args)
        .then(response => {
          let data = response.data;
          this.tableData = data;
          this.pagination.total = response.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(error => {
          this.$message({
            type: "warning",
            message: error.response.data.message
          });
          this.setting.loading = false;
        });
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getTenantMediaList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.getTenantMediaList();
    },
    resetSearch(formName) {
      this.$refs[formName].resetFields();
      this.pagination.currentPage = 1;
      this.getTenantMediaList();
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
