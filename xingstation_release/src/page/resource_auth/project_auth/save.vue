<template>
  <div class="item-wrap-template">
    <div v-loading="setting.loading" :element-loading-text="setting.loadingText" class="pane">
      <div class="pane-title">{{ projectAuthId ? '修改节目授权' : '新增节目授权'}}</div>
      <el-form ref="projectAuthForm" :model="projectAuthForm" label-width="150px" class="duty-form">
        <el-form-item
                prop="project_id"
                label="节目"
                :rules="{required: true, message: '节目不能为空', trigger: 'submit'}"
        >
          <el-select
                  v-model="projectAuthForm.project_id"
                  :loading="searchLoading"
                  remote
                  :remote-method="getProject"
                  placeholder="请输入节目名称"
                  filterable
                  clearable
                  @change="getProject"
          >
            <el-option
                    v-for="item in projectList"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item
                prop="customer_id"
                label="场地主"
                :rules="{required: true, message: '场地主不能为空', trigger: 'submit'}"
        >
          <el-select
                  v-model="projectAuthForm.customer_id"
                  :loading="searchLoading"
                  placeholder="请输入节目名称"
                  filterable
                  clearable
          >
            <el-option
                    v-for="item in marketOwnerList"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id"
            />
          </el-select>
        </el-form-item>



        <el-form-item>
          <el-button type="primary" size="small" @click="submit('projectAuthForm')">保存</el-button>
          <el-button size="small" @click="historyBack">返回</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
  import {
    getProjectAuthDetailData,
    getSearchMarketOwnerCustomer,
    getSearchProjectList,
    modifyProjectAuth,
    saveProjectAuth,
    historyBack,
  } from "service";

  import {Button, DatePicker, Form, FormItem, Input, Option, Select} from "element-ui";

  export default {
    components: {
      ElForm: Form,
      ElFormItem: FormItem,
      ElButton: Button,
      ElInput: Input,
      ElDatePicker: DatePicker,
      ElSelect: Select,
      ElOption: Option
    },
    data() {
      return {
        projectAuthForm: {
          id: "",
          customer_id: "",
          project_id: "",
        },
        marketOwnerList: [],
        projectList: [],
        searchLoading: false,
        projectAuthId: "",
        setting: {
          isOpenSelectAll: true,
          loading: false,
          loadingText: "拼命加载中"
        }
      };
    },
    created() {
      this.projectAuthId = this.$route.params.uid;
      this.getProject();
      this.getMarketOwnerList();
      if (this.projectAuthId) {
        this.getProjectAuthDetail();
      }
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
              message: err.response && err.response.data ?err.response.data.message:'查询场地住出错'
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


      historyBack() {
        historyBack();
      },

      getProjectAuthDetail() {
        this.setting.loading = true;
        getProjectAuthDetailData(this, this.projectAuthId)
          .then(result => {
            this.projectAuthForm = result;
            this.setting.loading = false;
          })
          .catch(err => {
            this.$message({
              type: "warning",
              message: err.response && err.response.data ?err.response.data.message:'查询授权详情出错'
            });
            this.setting.loading = false;
          });
      },

      submit(formName) {
        this.$refs[formName].validate(valid => {
          if (valid) {
            this.setting.loading = true;
            let args = this.projectAuthForm;
            if (this.projectAuthId) {
              modifyProjectAuth(this, this.projectAuthId, args)
                .then(res => {
                  this.$message({
                    type: "success",
                    message: "修改成功"
                  });
                  this.$router.push({
                    path: "/resource_auth/project_auth"
                  });
                  this.setting.loading = false;
                })
                .catch(err => {
                  this.$message({
                    type: "warning",
                    message: err.response.data.message
                  });
                  this.setting.loading = false;
                });
            } else {
              saveProjectAuth(this, args)
                .then(res => {
                  this.$message({
                    type: "success",
                    message: "保存成功"
                  });
                  this.$router.push({
                    path: "/resource_auth/project_auth"
                  });
                  this.setting.loading = false;
                })
                .catch(err => {
                  this.$message({
                    type: "warning",
                    message: err.response.data.message
                  });
                  this.setting.loading = false;
                });
            }
          }
        });
      }
    }
  };
</script>

<style lang="less" scoped>
  .item-wrap-template {
    .pane {
      border-radius: 5px;
      background-color: white;
      padding: 20px 40px 80px;

      .el-select,
      .item-input,
      .el-date-editor.el-input {
        width: 350px;
      }
      .item-list {
        .program-title {
          color: #555;
          font-size: 14px;
        }
      }
      .duty-form {
        width: 900px;
      }
      .pane-title {
        padding-bottom: 20px;
        font-size: 18px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
      }
    }
  }
</style>
