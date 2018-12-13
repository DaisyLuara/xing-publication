<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="program-list-wrap"
    >
      <div class="program-content-wrap">
        <!-- 搜索 -->
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
            <el-form-item label prop="status">
              <el-select
                v-model="filters.status"
                placeholder="请选择状态"
                clearable
                class="coupon-form-select"
              >
                <el-option
                  v-for="item in statusList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
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
            <el-form-item label prop="onlineDate">
              <el-date-picker
                v-model="filters.onlineDate"
                :clearable="false"
                :picker-options="pickerOptions"
                type="daterange"
                start-placeholder="上线开始时间"
                end-placeholder="上线结束时间"
                align="right"
              ></el-date-picker>
            </el-form-item>
            <el-form-item label prop="launchDate">
              <el-date-picker
                v-model="filters.launchDate"
                :clearable="false"
                :picker-options="pickerOptions"
                type="daterange"
                start-placeholder="投放开始时间"
                end-placeholder="投放结束时间"
                align="right"
              ></el-date-picker>
            </el-form-item>
            <el-form-item label prop>
              <el-button type="primary" size="small" @click="search()">搜索</el-button>
              <el-button size="small" @click="resetForm('filters')">重置</el-button>
            </el-form-item>
          </el-form>
        </div>
        <!-- 表单 -->
        <div class="total-wrap">
          <span class="label">
            总数:{{ pagination.total }}
            <el-checkbox
              v-if="role.name === 'tester' || role.name === 'legal-affairs-manager' || role.name === 'operation'"
              v-model="own"
              @change="meSearch"
            >关于我的</el-checkbox>
          </span>
          <div>
            <el-button
              v-if="role.name === 'legal-affairs-manager' || role.name === 'bonus-manager'"
              type="info"
              size="small"
              @click="downloadTable()"
            >下载</el-button>
            <el-button
              v-if="role.name === 'project-manager'"
              type="success"
              size="small"
              @click="addProgram()"
            >新增项目</el-button>
          </div>
        </div>
        <el-table :data="tableData" style="width: 100%">
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="ID">
                  <span>{{ scope.row.id }}</span>
                </el-form-item>
                <el-form-item label="节目名称">
                  <span>{{ scope.row.project_name }}</span>
                </el-form-item>
                <el-form-item label="申请人">
                  <span>{{ scope.row.applicant_name }}</span>
                </el-form-item>
                <el-form-item label="节目类型">
                  <span>{{ scope.row.type }}</span>
                </el-form-item>
                <el-form-item label="开始时间">
                  <span>{{ scope.row.begin_date }}</span>
                </el-form-item>
                <el-form-item label="上线时间">
                  <span>{{ scope.row.online_date }}</span>
                </el-form-item>
                <el-form-item label="投放时间">
                  <span>{{ scope.row.launch_date }}</span>
                </el-form-item>
                <el-form-item label="联动属性">
                  <span>{{ scope.row.link_attribute }}</span>
                </el-form-item>
                <el-form-item label="节目属性">
                  <span>{{ scope.row.project_attribute }}</span>
                </el-form-item>
                <el-form-item label="H5属性">
                  <span>{{ scope.row.h5_attribute }}</span>
                </el-form-item>
                <el-form-item label="小偶属性">
                  <span>{{ scope.row.xo_attribute }}</span>
                </el-form-item>
                <el-form-item label="备注">
                  <span>{{ scope.row.remark }}</span>
                </el-form-item>
                <el-form-item label="状态">
                  <span>{{ scope.row.status }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop="id" label="ID" min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="project_name"
            label="节目名称"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="applicant_name"
            label="申请人"
            min-width="100"
          />
          <el-table-column :show-overflow-tooltip="true" prop="type" label="节目类型" min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="begin_date"
            label="开始时间"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="online_date"
            label="上线时间"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="launch_date"
            label="投放时间"
            min-width="100"
          />
          <el-table-column :show-overflow-tooltip="true" prop="status" label="状态" min-width="100"/>
          <el-table-column label="操作" min-width="150">
            <template slot-scope="scope">
              <el-button
                size="small"
                type="warning"
                @click="editHandle(scope.row)"
              >{{ ((role.name === 'project-manager' && (scope.row.status === '进行中' || scope.row.status === '测试已确认')) || role.name === 'legal-affairs-manager' || role.name === 'bonus-manager' ) ? '修改': '查看' }}</el-button>
              <el-button
                v-if="(role.name === 'tester' && scope.row.status === '进行中') || (role.name === 'operation' && scope.row.status === '测试已确认') || ((role.name === 'legal-affairs-manager' && scope.row.status === '运营已确认' && scope.row.type === '提前节目') || (role.name === 'bonus-manager' && scope.row.status === '运营已确认' && scope.row.type === '提前节目'))"
                size="small"
                @click="confirmProgram(scope.row)"
              >确认</el-button>
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
  getProgramList,
  getSearchProjectList,
  handleDateTypeTransform,
  confirmProgram,
  getExcelData
} from "service";
import { Cookies } from "utils/cookies";
import {
  Button,
  Input,
  Table,
  Select,
  Option,
  TableColumn,
  Pagination,
  Form,
  FormItem,
  MessageBox,
  DatePicker,
  Checkbox
} from "element-ui";

export default {
  components: {
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-select": Select,
    "el-option": Option,
    "el-form-item": FormItem,
    "el-date-picker": DatePicker,
    "el-checkbox": Checkbox
  },
  data() {
    return {
      own: "",
      loading: true,
      templateVisible: false,
      filters: {
        alias: "",
        status: "",
        beginDate: [],
        onlineDate: [],
        launchDate: []
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
      statusList: [
        {
          id: 1,
          name: "进行中"
        },
        {
          id: 2,
          name: "测试已确认"
        },
        {
          id: 3,
          name: "运营已确认"
        },
        {
          id: 4,
          name: "主管已确认"
        }
      ],
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
      tableData: [],
      projectList: [],
      searchLoading: false
    };
  },
  created() {
    this.getProgramList();
    let user_info = JSON.parse(Cookies.get("user_info"));
    this.role = user_info.roles.data[0];
  },
  methods: {
    downloadTable() {
      let args = this.setArgs();
      delete args.own;
      delete args.page;
      args.type = 'team_project'
      return getExcelData(this, args)
        .then(response => {
          const a = document.createElement("a");
          a.href = response;
          a.download = "download";
          a.click();
          window.URL.revokeObjectURL(response);
        })
        .catch(err => {
          console.log(err);
        });
    },
    confirmProgram(row) {
      this.$confirm("确认通过吗?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          let id = row.id;
          confirmProgram(this, id)
            .then(res => {
              this.$message({
                type: "success",
                message: "确认成功!"
              });
              this.getProgramList();
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
    editHandle(data) {
      this.$router.push({
        path: "program/edit/" + data.id
      });
    },
    addProgram() {
      this.$router.push({
        path: "program/add"
      });
    },
    meSearch() {
      this.getProgramList();
    },
    setArgs() {
      let args = {
        page: this.pagination.currentPage,
        alias: this.filters.alias,
        status: this.filters.status,
        start_date_begin: handleDateTypeTransform(this.filters.beginDate[0]),
        end_date_begin: handleDateTypeTransform(this.filters.beginDate[1]),
        start_date_online: handleDateTypeTransform(this.filters.onlineDate[0]),
        end_date_online: handleDateTypeTransform(this.filters.onlineDate[1]),
        start_date_launch: handleDateTypeTransform(this.filters.launchDate[0]),
        end_date_launch: handleDateTypeTransform(this.filters.launchDate[1]),
        own: this.own
      };
      if (this.filters.alias === "") {
        delete args.alias;
      }
      if (this.own === "") {
        delete args.own;
      }
      if (!this.filters.status) {
        delete args.status;
      }
      if (JSON.stringify(this.filters.beginDate) === "[]") {
        delete args.start_date_begin;
        delete args.end_date_begin;
      }
      if (JSON.stringify(this.filters.onlineDate) === "[]") {
        delete args.start_date_online;
        delete args.end_date_online;
      }
      if (JSON.stringify(this.filters.launchDate) === "[]") {
        delete args.start_date_launch;
        delete args.end_date_launch;
      }
      return args;
    },
    getProgramList() {
      this.setting.loading = true;
      let args = this.setArgs();
      // let args = {
      //   page: this.pagination.currentPage,
      //   alias: this.filters.alias,
      //   status: this.filters.status,
      //   start_date_begin: handleDateTypeTransform(this.filters.beginDate[0]),
      //   end_date_begin: handleDateTypeTransform(this.filters.beginDate[1]),
      //   start_date_online: handleDateTypeTransform(this.filters.onlineDate[0]),
      //   end_date_online: handleDateTypeTransform(this.filters.onlineDate[1]),
      //   start_date_launch: handleDateTypeTransform(this.filters.launchDate[0]),
      //   end_date_launch: handleDateTypeTransform(this.filters.launchDate[1]),
      //   own: this.own
      // };
      // if (this.filters.alias === "") {
      //   delete args.alias;
      // }
      // if (this.own === "") {
      //   delete args.own;
      // }
      // if (!this.filters.status) {
      //   delete args.status;
      // }
      // if (JSON.stringify(this.filters.beginDate) === "[]") {
      //   delete args.start_date_begin;
      //   delete args.end_date_begin;
      // }
      // if (JSON.stringify(this.filters.onlineDate) === "[]") {
      //   delete args.start_date_online;
      //   delete args.end_date_online;
      // }
      // if (JSON.stringify(this.filters.launchDate) === "[]") {
      //   delete args.start_date_launch;
      //   delete args.end_date_launch;
      // }
      getProgramList(this, args)
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
    resetForm(formName) {
      this.$refs[formName].resetFields();
      this.pagination.currentPage = 1;
      this.getProgramList();
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getProgramList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.getProgramList();
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
