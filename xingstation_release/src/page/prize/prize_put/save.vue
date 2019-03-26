<template>
  <div class="root">
    <div class="account-wrap">
      <div class="item-info">
        <div class="prize-title">{{ prizeId ? '奖品投放修改' : '奖品投放新增'}}</div>
        <el-form ref="prizeForm" :model="prizeForm" label-width="180px">
          <el-form-item
            :rules="[{ required: true, message: '请选择公司', trigger: 'submit'}]"
            label="公司名称"
            prop="company_id"
          >
            <el-select
              v-model="prizeForm.company_id"
              :loading="searchLoading"
              placeholder="请选择公司"
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
          <el-form-item
            :rules="[{ required: true, message: '请选择模版', trigger: 'submit'}]"
            label="模版名称"
            prop="policy_id"
          >
            <el-select
              v-model="prizeForm.policy_id"
              :loading="searchLoading"
              placeholder="请选择模版"
              filterable
              clearable
            >
              <el-option
                v-for="item in policyList"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            :rules="[{ required: true, message: '请选择点位', trigger: 'submit'}]"
            label="点位名称"
            prop="oid"
          >
            <el-select
              v-model="prizeForm.oid"
              :loading="searchLoading"
              placeholder="请选择点位"
              filterable
              clearable
            >
              <el-option
                v-for="item in pointList"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            :rules="[{ required: true, message: '请输入节目名称', trigger: 'submit'}]"
            label="节目名称"
            prop="project_id"
          >
            <el-select
              v-model="prizeForm.project_id"
              :loading="searchLoading"
              filterable
              placeholder="请选择节目"
              clearable
            >
              <el-option
                v-for="item in projectList"
                :key="item.id"
                :label="item.name"
                :value="item.id + ',' + item.versionname"
              />
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onSubmit('prizeForm')">保存</el-button>
            <el-button @click="back">返回</el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</template>
<script>
import {
  historyBack,
  getSearchAuthPolicies,
  getSearchAuthPoint,
  getSearchAuthProject,
  getSearchCompany,
  getLaunchPirzeList,
  saveLaunchPirze,
  modifyLaunchPirze,
  getLaunchPirzeDetail
} from "service";
import {
  Form,
  Select,
  Option,
  FormItem,
  Button,
  Input,
  MessageBox
} from "element-ui";
export default {
  components: {
    ElForm: Form,
    ElSelect: Select,
    ElOption: Option,
    ElFormItem: FormItem,
    ElButton: Button
  },
  data() {
    return {
      prizeId: null,
      searchLoading: false,
      prizeForm: {
        company_id: null,
        policy_id: null,
        oid: null,
        project_id: null
      },
      projectList: [],
      companyList: [],
      pointList: [],
      policyList: []
    };
  },
  created() {
    this.prizeId = this.$route.params.uid;
    this.getSearchAuthPolicies();
    this.getSearchAuthPoint();
    this.getSearchAuthProject();
    this.getSearchCompany();
    if (this.prizeId) {
      this.getLaunchPirzeDetail();
    }
  },
  methods: {
    getSearchAuthPolicies() {
      this.searchLoading = true;
      getSearchAuthPolicies()
        .then(res => {
          this.searchLoading = false;
          this.policyList = res;
        })
        .catch(err => {
          this.searchLoading = false;
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    getSearchAuthPoint() {
      this.searchLoading = true;
      getSearchAuthPoint()
        .then(res => {
          this.searchLoading = false;
          this.pointList = res;
        })
        .catch(err => {
          this.searchLoading = false;
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    getSearchAuthProject() {
      this.searchLoading = true;
      getSearchAuthProject()
        .then(res => {
          this.searchLoading = false;
          this.projectList = res;
        })
        .catch(err => {
          this.searchLoading = false;
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    getSearchCompany() {
      this.searchLoading = true;
      getSearchCompany()
        .then(res => {
          this.searchLoading = false;
          this.companyList = res.data;
        })
        .catch(err => {
          this.searchLoading = false;
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    getLaunchPirzeDetail() {
      this.setting.loading = true;
      let args = {
        include: "point.market,project,policy,company"
      };
      getLaunchPirzeDetail(this, this.prizeId, args)
        .then(res => {
          this.prizeForm.project_id =
            res.project.id + "," + res.project.versionname;
          this.prizeForm.oid = res.point.id;
          this.prizeForm.company_id = res.company.id;
          this.prizeForm.policy_id = res.policy.id;
          this.setting.loading = false;
        })
        .catch(err => {
          this.setting.loading = false;
          this.$message({
            message: err.response.data.message,
            type: "success"
          });
        });
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.setting.loading = true;
          let args = {
            company_id: this.prizeForm.company_id,
            project_id: this.prizeForm.project_id.split(",")[0],
            versionname: this.prizeForm.project_id.split(",")[1],
            oid: this.prizeForm.oid,
            policy_id: this.prizeForm.policy_id
          };
          if (this.prizeId) {
            modifyLaunchPirze(this, this.prizeId, args)
              .then(response => {
                this.setting.loading = false;
                this.$message({
                  message: "修改成功",
                  type: "success"
                });
                this.$router.push({
                  path: "/prize/launch"
                });
              })
              .catch(err => {
                this.setting.loading = false;
                this.$message({
                  message: err.response.data.message,
                  type: "success"
                });
              });
          } else {
            saveLaunchPirze(this, args)
              .then(response => {
                this.setting.loading = false;
                this.$message({
                  message: "添加成功",
                  type: "success"
                });
                this.$router.push({
                  path: "/prize/launch"
                });
              })
              .catch(err => {
                this.setting.loading = false;
                this.$message({
                  message: err.response.data.message,
                  type: "success"
                });
              });
          }
        }
      });
    },
    back() {
      historyBack();
    }
  }
};
</script>
<style lang="less" scoped>
.root {
  background: #fff;
  padding: 20px;
  .prize-title {
    font-size: 18px;
    margin: 15px 0 15px 15px;
  }
  .el-select {
    width: 300px;
  }
}
</style>

