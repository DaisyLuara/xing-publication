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
          <el-button
            v-if="operation"
            type="success"
            size="small"
            @click="dialogFormVisible = true"
          >新增文档</el-button>
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
    <el-dialog :visible.sync="dialogFormVisible" :close-on-click-modal="false" :show-close="false">
      <el-form
        v-loading="loading"
        ref="documentForm"
        :model="documentForm"
        label-position="left"
        label-width="80px"
      >
        <el-form-item
          :rules="[{ required: true, message: '请填写文档名称', trigger: 'submit' }]"
          label="文档名称"
          prop="document_name"
        >
          <el-input
            v-model="documentForm.document_name"
            :maxlength="200"
            placeholder="请输入文档名称"
            class="text-input"
          />
        </el-form-item>
        <el-form-item label="运营文档" prop="ids">
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
            >文件类型只支持docx、doc、pdf、ppt、pptx、xlsx、xls</div>
          </el-upload>
        </el-form-item>
        <el-form-item label-position="right">
          <el-button size="small" @click="cancel">取 消</el-button>
          <el-button size="small" type="primary" @click="submit('documentForm')">确 定</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import {
  deleteOperationDocument,
  getOperationDocumentList,
  saveOperationDocument,
  modifyOperationDocument,
  getOperationDocumentDetails,
  handleDateTypeTransform,
  getQiniuToken,
  getMediaUpload
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
  DatePicker,
  Dialog,
  Upload
} from "element-ui";

export default {
  components: {
    "el-upload": Upload,
    "el-dialog": Dialog,
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
      ids: [],
      Domain: "http://upload.qiniu.com",
      uploadToken: "",
      uploadKey: "",
      uploadForm: {
        token: "",
        key: ""
      },
      documentForm: {
        document_name: ""
      },
      fileList: [],
      loading: false,
      dialogFormVisible: false,
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
      tableData: [],
      editId: null
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
    this.getQiniuToken();
    this.getOperationDocumentList();
  },
  methods: {
    cancel() {
      if (!this.editId) {
        this.documentForm.document_name = "";
        this.ids = [];
      }
      this.dialogFormVisible = false;
    },
    submit(formName) {
      let operationMediaIds = [];
      if (this.fileList.length > 0) {
        this.fileList.map(r => {
          operationMediaIds.push(r.id);
        });
        this.ids = operationMediaIds.join(",");
      } else {
        this.$message({
          type: "warning",
          message: "运营文档素材必须上传"
        });
        return;
      }
      let args = {
        name: this.documentForm.document_name,
        media_id: this.ids
      };
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (this.editId) {
            args.id = this.editId;
            modifyOperationDocument(this, args, this.editId)
              .then(res => {
                this.dialogFormVisible = false;
                this.$message({
                  message: "修改成功",
                  type: "success"
                });
                this.getOperationDocumentList();
              })
              .catch(err => {
                this.$message({
                  message: err.response.data.message,
                  type: "warning"
                });
              });
          } else {
            saveOperationDocument(this, args)
              .then(res => {
                this.dialogFormVisible = false;
                this.$message({
                  message: "提交成功",
                  type: "success"
                });
                this.getOperationDocumentList();
              })
              .catch(err => {
                this.$message({
                  message: err.response.data.message,
                  type: "warning"
                });
              });
          }
        }
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
      if (
        !(
          type === ".docx" ||
          type === ".doc" ||
          type === ".pdf" ||
          type === ".ppt" ||
          type === ".pptx" ||
          type === ".xlsx" ||
          type === ".xls"
        )
      ) {
        this.uploadForm.token = "";
        return this.$message.error(
          "文件类型只支持docx、doc、pdf、ppt、pptx、xlsx、xls"
        );
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
      this.dialogFormVisible = true;
      this.loading = true;
      this.getOperationDocumentDetails(data.id);
    },
    getOperationDocumentDetails(id) {
      this.editId = id;
      getOperationDocumentDetails(this, id)
        .then(res => {
          this.documentForm.document_name = res.name;
          let operationMediaData = [];
          if (res.media) {
            operationMediaData.push(res.media);
          }
          this.fileList = operationMediaData;
          this.loading = false;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
          this.loading = false;
        });
    },
    deleteDocument(data) {
      this.$confirm("确认删除吗?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          let ids = Array.of(data.id);
          console.log(ids);
          let args = {
            ids: ids
          };
          deleteOperationDocument(this, args)
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
      let url = data.media.url;
      const xhr = new XMLHttpRequest();
      xhr.open("GET", url, true);
      xhr.responseType = "blob";
      xhr.onload = () => {
        var urlObject = window.URL || window.webkitURL || window;
        let a = document.createElement("a");
        a.href = urlObject.createObjectURL(new Blob([xhr.response]));
        a.download = data.media.name;
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
        delete args.start_date;
        delete args.end_date;
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
