<template>
  <div class="item-wrap-template">
    <div v-loading="setting.loading" :element-loading-text="setting.loadingText" class="pane">
      <div class="pane-title">{{ projectAuthId ? '修改节目授权' : '新增节目授权'}}</div>
      <el-form ref="projectAuthForm" :model="projectAuthForm" label-width="150px" class="duty-form">
        <el-form-item
                prop="belong"
                label="节目"
                :rules="{required: true, message: '节目不能为空', trigger: 'submit'}"
        >
          <el-select
                  v-model="projectAuthForm.belong"
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
                    :value="item.alias"
            />
          </el-select>
        </el-form-item>
        <el-form-item prop="test" label="测试">
          <el-input v-model="projectAuthForm.test" :disabled="true" class="item-input"/>
        </el-form-item>
        <el-form-item prop="operation" label="运营">
          <el-input v-model="projectAuthForm.operation" :disabled="true" class="item-input"/>
        </el-form-item>

        <el-form-item
                :rules="[{ required: true, message: '请输入备注', trigger: 'submit' }]"
                label="备注"
                prop="description"
        >
          <el-input
                  v-model="projectAuthForm.description"
                  :autosize="{ minRows: 2}"
                  :maxlength="1000"
                  type="textarea"
                  placeholder="请填写备注"
                  class="text-input"
          />
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
    getSearchMarketOwnerCustomer,
    getSearchProjectList,
    getProjectAuthDetailData,
    saveProjectAuth,
    modifyProjectAuth
  } from "service";

  import {
    Form,
    FormItem,
    Button,
    Input,
    MessageBox,
    DatePicker,
    Select,
    Option
  } from "element-ui";

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
      if (this.projectAuthId) {
        this.getEventDetails();
      }
    },
    methods: {
      getMarketOwnerList() {
        getSearchMarketOwnerCustomer(this)
          .then(result => {
            this.marketOwnerList = result;
            console.log(result);
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


      historyBack() {
        historyBack();
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
      getEventDetails() {
        this.setting.loading = true;
        getEventDetails(this, this.projectAuthId)
          .then(res => {
            this.projectAuthForm.belong = res.belong;
            let project_name = res.project_name
            this.getProject(project_name)
            this.projectAuthForm.occur_date = res.occur_date;
            this.projectAuthForm.description = res.description;
            this.projectAuthForm.test = res.tester_text;
            this.projectAuthForm.operation = res.operation_text;
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
      submit(formName) {
        this.$refs[formName].validate(valid => {
          if (valid) {
            this.setting.loading = true;
            let args = this.projectAuthForm;
            delete this.projectAuthForm.test;
            delete this.projectAuthForm.operation;
            if (this.projectAuthId) {
              modifyEvent(this, this.projectAuthId, args)
                .then(res => {
                  this.$message({
                    type: "success",
                    message: "修改成功"
                  });
                  this.$router.push({
                    path: "/team/duty"
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
              saveEvent(this, args)
                .then(res => {
                  this.$message({
                    type: "success",
                    message: "保存成功"
                  });
                  this.$router.push({
                    path: "/team/duty"
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
