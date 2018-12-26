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
              v-if="tester || legalAffairsManager || operation"
              v-model="own"
              @change="meSearch"
            >关于我的</el-checkbox>
          </span>
          <div>
            <el-button
              v-if="legalAffairsManager || bonusManage"
              type="success"
              size="small"
              @click="downloadTable()"
            >下载</el-button>
            <el-button v-if="projectManage" type="success" size="small" @click="addProgram()">新增项目</el-button>
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
                  <span>{{ scope.row.status === 1 ? '进行中' : scope.row.status === 2 ? '测试已确认' : scope.row.status === 3 ? '运营已确认' : '主管已确认' }}</span>
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
          <el-table-column :show-overflow-tooltip="true" prop="status" label="状态" min-width="100">
            <template
              slot-scope="scope"
            >{{ scope.row.status === 1 ? '进行中' : scope.row.status === 2 ? '测试已确认' : scope.row.status === 3 ? '运营已确认' : '主管已确认' }}</template>
          </el-table-column>
          <el-table-column label="操作" min-width="150">
            <template slot-scope="scope">
              <el-button
                size="small"
                type="warning"
                @click="editHandle(scope.row)"
              >{{ ((projectManage && (scope.row.status === 1 || scope.row.status === 2)) || legalAffairsManager || bonusManage ) ? '修改': '查看' }}</el-button>
              <el-button
                v-if="(tester && scope.row.status === 1) || (operation && scope.row.status === 2) || ((legalAffairsManager && scope.row.status === 3 && scope.row.type === '提前节目') || (bonusManage && scope.row.status === 3 && scope.row.type === '提前节目'))"
                size="small"
                @click="confirmProgramHandle(scope.row)"
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
    <el-dialog :visible.sync="dialogFormVisible" :close-on-click-modal="false" :show-close="false">
      <el-form label-position="left" label-width="80px">
        <el-form-item
          :rules="[{ required: true, message: '请上传测试文档', trigger: 'submit' }]"
          label="测试文档"
          prop="ids"
        >
          <el-upload
            ref="upload"
            :action="Domain"
            :data="uploadForm"
            :on-success="handleSuccess"
            :before-upload="beforeUpload"
            :on-remove="handleRemove"
            :before-remove="beforeRemove"
            :file-list="fileList"
            :limit="1"
            :on-exceed="handleExceed"
            class="upload-demo"
          >
            <el-button size="small" type="primary">点击上传</el-button>
            <div
              slot="tip"
              style="display:inline-block"
              class="el-upload__tip"
            >支持文件类型：doc(.docx)、.pdf</div>
          </el-upload>
        </el-form-item>
        <el-form-item label-position="right">
          <el-button size="small" @click="cancel">取 消</el-button>
          <el-button size="small" type="primary" @click="submit">确 定</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import {
  getProgramList,
  getPersonRewardTotal,
  getSearchProjectList,
  handleDateTypeTransform,
  confirmProgram,
  getExcelData,
  getMediaUpload,
  getQiniuToken
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
  Checkbox,
  Upload,
  Dialog
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
    "el-checkbox": Checkbox,
    "el-upload": Upload,
    "el-dialog": Dialog
  },
  data() {
    return {
      own: "",
      Domain: "http://upload.qiniu.com",
      uploadToken: "",
      uploadKey: "",
      uploadForm: {
        token: "",
        key: ""
      },
      fileList: [],
      ids: [],
      dialogFormVisible: false,
      templateVisible: false,
      confirmId: null,
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
  computed: {
    projectManage: function() {
      return this.role.find(r => {
        return r.name === "project-manager";
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
    },
    legalAffairsManager: function() {
      return this.role.find(r => {
        return r.name === "legal-affairs-manager";
      });
    },
    tester: function() {
      return this.role.find(r => {
        return r.name === "tester";
      });
    }
  },
  activated() {},
  deactivated() {},
  created() {
    this.getProgramList();
    let user_info = JSON.parse(Cookies.get("user_info"));
    this.role = user_info.roles.data;
    this.getQiniuToken();
  },
  methods: {
    downloadTable() {
      let args = this.setArgs();
      delete args.own;
      delete args.page;
      args.type = "team_project";
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
    cancel() {
      this.dialogFormVisible = false;
    },
    submit() {
      let testerMediaIds = [];
      let args = {};
      if (this.fileList.length > 0) {
        this.fileList.map(r => {
          testerMediaIds.push(r.id);
        });
        this.ids = testerMediaIds.join(",");
        args.media_id = this.ids;
        this.confirmProgram(this.confirmId, args);
      } else {
        this.$message({
          type: "warning",
          message: "测试文档素材必须上传"
        });
        return;
      }
    },
    confirmProgramHandle(row) {
      this.$confirm("确认通过吗?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          let id = row.id;
          let status = row.status;
          let testerMediaIds = [];
          if (status === 1) {
            this.confirmId = id;
            this.dialogFormVisible = true;
          } else {
            this.confirmProgram(id);
          }
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消确认"
          });
        });
    },
    confirmProgram(id, args) {
      confirmProgram(this, id, args)
        .then(res => {
          this.$message({
            type: "success",
            message: "确认成功!"
          });
          this.dialogFormVisible = false;
          this.getProgramList();
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    getQiniuToken() {
      getQiniuToken(this)
        .then(res => {
          this.uploadToken = res;
          this.uploadForm.token = this.uploadToken;
        })
        .catch(err => {
          console.log(err);
        });
    },
    handleRemove(file, fileList) {
      this.fileList = fileList;
    },
    handleExceed(files, fileList) {
      this.$message.warning(
        `当前限制选择 1 个文件，本次选择了 ${
          files.length
        } 个文件，共选择了 ${files.length + fileList.length} 个文件`
      );
    },
    beforeRemove(file, fileList) {
      return this.$confirm(`确定移除 ${file.name}？`);
    },
    beforeUpload(file) {
      let name = file.name;
      let type = name.substring(name.lastIndexOf("."));
      let isLt100M = file.size / 1024 / 1024 < 100;
      let time = new Date().getTime();
      let random = parseInt(Math.random() * 10 + 1, 10);
      let suffix = time + "_" + random + "_" + name;
      let key = encodeURI(`${suffix}`);
      if (!(type === ".docx" || type === ".doc" || type === ".pdf")) {
        this.uploadForm.token = "";
        return this.$message.error("文件类型只支持(docx、doc、pdf)");
      }
      if (!isLt100M) {
        this.uploadForm.token = "";
        return this.$message.error("上传大小不能超过 100MB!");
      } else {
        this.uploadForm.token = this.uploadToken;
      }
      this.uploadForm.key = key;
      return this.uploadForm;
    },
    // 上传成功后的处理
    handleSuccess(response, file, fileList) {
      let key = response.key;
      let name = file.name;
      let size = file.size;
      let type = name.substring(name.lastIndexOf("."));
      this.getMediaUpload(key, name, size);
    },
    getMediaUpload(key, name, size) {
      let params = {
        key: key,
        name: name,
        size: size
      };
      getMediaUpload(this, params)
        .then(res => {
          this.fileList.push(res);
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
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
        own: this.own === true ? 1 : 0
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
        .label-money {
          font-size: 18px;
          font-weight: 700;
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
