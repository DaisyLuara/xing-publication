<template>
  <div class="root">
    <div
            v-loading="setting.loading"
            :element-loading-text="setting.loadingText"
            class="item-list-wrap">
      <div class="item-content-wrap">
        <!-- 搜索 -->
        <div class="search-wrap">
          <el-form ref="searchForm" :model="searchForm" :inline="true">
            <el-row :gutter="20">
              <el-form-item label prop="customer_id">
                <el-select
                        v-model="searchForm.customer_id"
                        placeholder="场地主"
                        filterable
                        clearable>
                  <el-option
                          v-for="item in marketOwnerList"
                          :key="item.id"
                          :label="item.name"
                          :value="item.id"
                  />
                </el-select>
              </el-form-item>

              <el-form-item label prop="project_id">
                <el-select
                        v-model="searchForm.project_id"
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
                          :value="item.id"
                  />
                </el-select>
              </el-form-item>
              <el-button type="primary" size="small" @click="search('searchForm')">搜索</el-button>
              <el-button type="default" size="small" @click="resetSearch('searchForm')">重置</el-button>
            </el-row>
          </el-form>
        </div>
        <!-- 点位列表 -->
        <div class="total-wrap">
          <span class="label">总数:{{ pagination.total }}</span>
          <div>
            <el-button size="small" type="success" @click="addProjectAuth">新建节目授权</el-button>
          </div>
        </div>
        <el-table :data="tableData" style="width: 100%">
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="ID:">
                  <span>{{ scope.row.id }}</span>
                </el-form-item>
                <el-form-item label="授权场地主ID:">
                  <span>{{ scope.row.customer_id }}</span>
                </el-form-item>
                <el-form-item label="授权场地主:">
                  <span>{{ scope.row.customer_name }}</span>
                </el-form-item>
                <el-form-item label="节目ID:">
                  <span>{{ scope.row.project_id }}</span>
                </el-form-item>
                <el-form-item label="节目名称:">
                  <span>{{ scope.row.project_name }}</span>
                </el-form-item>
                <el-form-item label="时间">
                  <span>{{ scope.row.date }}</span>
                </el-form-item>

              </el-form>
            </template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop="id" label="ID" width="80"/>
          <el-table-column :show-overflow-tooltip="true" prop="customer_name" label="授权场地主" min-width="100"/>
          <el-table-column :show-overflow-tooltip="true" prop="project_name" label="节目名称" min-width="100"/>
          <el-table-column :show-overflow-tooltip="true" prop="date" label="时间" min-width="100"/>

          <el-table-column label="操作" min-width="100">
            <template slot-scope="scope">
              <el-button size="mini" type="warning" @click="editProjectAuth(scope.row)">编辑
              </el-button>
              <el-button size="mini" type="danger" @click="deleteProjectAuth(scope.row)">删除
              </el-button>

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
  import {
    destroyProjectAuth,
    getProjectAuthListData,
    getSearchMarketOwnerCustomer,
    getSearchProjectList
  } from "service";

  import {
    Button,
    Col,
    Form,
    FormItem,
    MessageBox,
    Input,
    Option,
    Pagination,
    Row,
    Select,
    Table,
    TableColumn
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
      "el-option": Option,
      "el-row": Row,
      "el-col": Col
    },
    data() {
      return {
        searchForm: {
          customer_id: "",
          project_id: "",

        },
        marketOwnerList: [],
        projectList: [],
        setting: {
          loading: false,
          loadingText: "拼命加载中"
        },
        searchLoading: false,
        pagination: {
          total: 0,
          pageSize: 10,
          currentPage: 1
        },

        tableData: []
      };
    },
    created() {
      this.getMarketOwnerList();
      this.getProject();
      this.getProjectAuthList();
    },
    methods: {
      getMarketOwnerList() {
        getSearchMarketOwnerCustomer(this)
          .then(result => {
            this.marketOwnerList = result;
          })
          .catch(err => {
            this.$message({
              type: "warning",
              message: err.response.data.message
            });
          });
      },

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

      addProjectAuth() {
        this.$router.push({
          path: "/resource_auth/project_auth/add"
        });
      },

      editProjectAuth(data) {
        this.$router.push({
          path: "/resource_auth/project_auth/edit/" + data.id
        });
      },

      deleteProjectAuth(data) {
        MessageBox.confirm("确认删除该授权?", "提示", {
          confirmButtonText: "确定",
          cancelButtonText: "取消",
          type: "warning"
        })
          .then(() => {
            this.setting.loadingText = "删除中";
            this.setting.loading = true;

            destroyProjectAuth(this, data.id)
              .then(res => {
                this.pagination.currentPage = 1;
                this.getProjectAuthList();
                this.$message({
                  type: "success",
                  message: "删除成功！"
                });
              })
              .catch(err => {
                this.$message({
                  type: "warning",
                  message: err.response.data.message
                });
                this.setting.loading = false;
              });
          })
          .catch(e => {
            console.log(e);
          });


      },

      getProjectAuthList() {
        this.setting.loading = true;
        this.setting.loadingText = "拼命加载中";
        let args = {
          page: this.pagination.currentPage,
          customer_id: this.searchForm.customer_id,
          project_id: this.searchForm.project_id,
        };

        getProjectAuthListData(this, args)
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

      changePage(currentPage) {
        this.pagination.currentPage = currentPage;
        this.getProjectAuthList();
      },
      search() {
        this.pagination.currentPage = 1;
        this.getProjectAuthList();
      },
      resetSearch(formName) {
        this.$refs[formName].resetFields();
        this.pagination.currentPage = 1;
        this.getProjectAuthList();
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
            margin-bottom: 10px;
          }
          .el-select {
            width: 200px;
          }
          .item-input {
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
