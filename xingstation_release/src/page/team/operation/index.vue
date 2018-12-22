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
            <el-form-item label prop="name">
              <el-input v-model="filters.name" clearable placeholder="请输入文档名称"/>
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
          <span class="label">总数：{{ pagination.total }}</span>
          <el-button v-if="operation" type="success" size="small" @click="addDocument">新增文档</el-button>
        </div>
        <el-table :data="tableData" style="width: 100%">
          <el-table-column :show-overflow-tooltip="true" prop="id" label="ID" min-width="100"/>
          <el-table-column :show-overflow-tooltip="true" prop="name" label="文档名称" min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="created_at"
            label="上传日期"
            min-width="100"
          />
          <el-table-column label="操作" min-width="150">
            <template slot-scope="scope">
              <el-button
                v-if="operation"
                type="primary"
                size="small"
                @click="editHandle(scope.row)"
              >编辑</el-button>
              <el-button
                v-if="operation"
                type="error"
                size="small"
                @click="deleteDocument(scope.row)"
              >删除</el-button>
              <el-button
                v-if="legalAffairsManager || bonusManage"
                size="small"
                @click="downloadDocument(scope.row)"
              >下载</el-button>
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
  deleteOperationDocument,
  getOperationDocumentList,
  saveOperationDocument,
  modifyOperationDocument,
  getOperationDocumentDetails,
  handleDateTypeTransform
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
      filters: {
        name: "",
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
      role: null,
      tableData: [
        {
          id: 1,
          name: "aaa",
          created_at: "2018-09-09"
        }
      ]
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
    operation: function() {
      return this.role.find(r => {
        return r.name === "operation";
      });
    }
  },
  created() {
    let user_info = JSON.parse(Cookies.get("user_info"));
    this.role = user_info.roles.data;
    // this.getOperationDocumentList();
  },
  methods: {
    editHandle(data) {},
    addDocument() {},
    deleteDocument(data) {
      this.$confirm("确认删除吗?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          let id = data.id;
          deleteOperationDocument(this, id)
            .then(res => {
              this.$message({
                type: "success",
                message: "删除成功!"
              });
              this.getOperationDocumentList();
            })
            .catch(err => {
              this.$message({
                type: "warning",
                message: err.response.data.message
              });
            });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消确认"
          });
        });
    },
    downloadDocument(data) {
      let url = data.url;
      const xhr = new XMLHttpRequest();
      xhr.open("GET", url, true);
      xhr.responseType = "blob";
      xhr.onload = () => {
        var urlObject = window.URL || window.webkitURL || window;
        let a = document.createElement("a");
        a.href = urlObject.createObjectURL(new Blob([xhr.response]));
        a.download = data.name;
        a.click();
      };
      xhr.send();
    },
    getOperationDocumentList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage,
        name: this.filters.name,
        start_date: handleDateTypeTransform(this.filters.beginDate[0]),
        end_date: handleDateTypeTransform(this.filters.beginDate[1])
      };
      if (this.filters.name === "") {
        delete args.name;
      }
      if (JSON.stringify(this.filters.beginDate) === "[]") {
        delete args.start_date_begin;
        delete args.end_date_begin;
      }
      getOperationDocumentList(this, args)
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
      this.getOperationDocumentList();
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getOperationDocumentList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.getOperationDocumentList();
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
