<template>
  <div class="item-wrap-template">
    <div v-loading="setting.loading" :element-loading-text="setting.loadingText" class="pane">
      <div class="pane-title">{{ dutyId ? '修改事件' : '新增事件'}}</div>
      <el-form ref="dutyForm" :model="dutyForm" label-width="150px" class="duty-form">
        <el-form-item
          prop="belong"
          label="节目名称"
          :rules="{required: true, message: '节目名称不能为空', trigger: 'submit'}"
        >
          <el-select
            v-model="dutyForm.belong"
            :loading="searchLoading"
            remote
            :remote-method="getProject"
            placeholder="请输入节目名称"
            filterable
            clearable
            @change="projectHandle"
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
          <el-input v-model="dutyForm.test" :disabled="true" class="item-input"/>
        </el-form-item>
        <el-form-item prop="operation" label="运营">
          <el-input v-model="dutyForm.operation" :disabled="true" class="item-input"/>
        </el-form-item>
        <el-form-item
          :rules="{required: true, message: '发生日期不能为空', trigger: 'submit'}"
          label="发生日期"
          prop="occur_date"
        >
          <el-date-picker
            v-model="dutyForm.occur_date"
            :picker-options="pickerOptions"
            type="date"
            placeholder="选择日期"
            class="coupon-form-date"
          />
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请输入备注', trigger: 'submit' }]"
          label="备注"
          prop="description"
        >
          <el-input
            v-model="dutyForm.description"
            :autosize="{ minRows: 2}"
            :maxlength="1000"
            type="textarea"
            placeholder="请填写备注"
            class="text-input"
          />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" size="small" @click="submit('dutyForm')">保存</el-button>
          <el-button size="small" @click="historyBack">返回</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import {
  saveEvent,
  modifyEvent,
  getEventDetails,
  historyBack,
  getSearchProjectList
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
      dutyForm: {
        belong: "",
        test: "",
        operation: "",
        occur_date: "",
        description: ""
      },
      pickerOptions: {
        disabledDate: time => {
          let month = new Date().getMonth() + 1;
          let year = new Date().getFullYear();
          if (10 <= month && month <= 12) {
            return (
              time.getTime() > new Date(`${year}/12/31`).getTime() ||
              time.getTime() < new Date(`${year}/10/01`).getTime()
            );
          }

          if (7 <= month && month <= 9) {
            return (
              time.getTime() > new Date(`${year}/09/30`).getTime() ||
              time.getTime() < new Date(`${year}/07/01`).getTime()
            );
          }

          if (4 <= month && month <= 6) {
            return (
              time.getTime() > new Date(`${year}/06/30`).getTime() ||
              time.getTime() < new Date(`${year}/04/01`).getTime()
            );
          }

          if (1 <= month && month <= 3) {
            return (
              time.getTime() > new Date(`${year}/03/31`).getTime() ||
              time.getTime() < new Date(`${year}/01/01`).getTime()
            );
          }
        }
      },
      projectList: [],
      searchLoading: false,
      dutyId: "",
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      }
    };
  },
  created() {
    this.dutyId = this.$route.params.uid;
    if (this.dutyId) {
      this.getEventDetails();
    }
  },
  methods: {
    projectHandle(val) {
      let projectChoose = {};
      this.projectList.filter(r => {
        if (r.alias === val) {
          projectChoose = r;
          return;
        }
      });
      this.dutyForm.test = projectChoose.tester;
      this.dutyForm.operation = projectChoose.operation;
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
      getEventDetails(this, this.dutyId)
        .then(res => {
          this.dutyForm.belong = res.belong;
          let project_name = res.project_name
          this.getProject(project_name)
          this.dutyForm.occur_date = res.occur_date;
          this.dutyForm.description = res.description;
          this.dutyForm.test = res.tester_text;
          this.dutyForm.operation = res.operation_text;
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
          let args = this.dutyForm;
          delete this.dutyForm.test;
          delete this.dutyForm.operation;
          if (this.dutyId) {
            modifyEvent(this, this.dutyId, args)
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
