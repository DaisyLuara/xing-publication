<template>
  <div class="root">
    <div class="account-wrap">
      <div class="item-info">
        <div class="prize-title">{{ templateId ? '奖品模版修改' : '奖品模版新增' }}</div>
        <el-form
          ref="templateForm"
          :model="templateForm"
          label-width="180px"
        >
          <el-form-item
            :rules="[{ type: 'number', required: true, message: '请选择公司', trigger: 'submit' }]"
            label="公司"
            prop="company_id"
          >
            <el-select
              :disabled="disableEdit"
              v-model="templateForm.company_id"
              :loading="searchLoading"
              placeholder="请选择公司"
              filterable
              clearable
              class="item-select"
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
            :rules="[{ type: 'string', required: true, message: '请输入名称', trigger: 'submit' }]"
            label="模板名"
            prop="name"
          >
            <el-input
              v-model="templateForm.name"
              placeholder="请输入名称"
              class="item-input"
            />
          </el-form-item>
          <el-form-item
            :rules="{required: true, message: '模板类型不能为空', trigger: 'submit'}"
            label="模板类型"
            prop="type"
          >
            <el-radio-group v-model="templateForm.type">
              <el-tooltip
                class="item"
                effect="dark"
                content="随机发放模板中某一张奖品券"
                placement="top"
              >><el-radio :label="1">抽奖</el-radio>
              </el-tooltip>
              <el-tooltip
                class="item"
                effect="dark"
                content="发放模板中全部奖品券"
                placement="top"
              >
                <el-radio :label="2">券包</el-radio>
              </el-tooltip>
            </el-radio-group>
          </el-form-item>
          <el-form-item
            :rules="{required: true, message: '每天无限领取不能为空', trigger: 'submit'}"
            label="每天无限领取"
            prop="per_person_unlimit"
          >
            <el-radio-group
              v-model="templateForm.per_person_unlimit"
              @change="unlimitedDayHandle"
            >
              <el-radio :label="1">开启</el-radio>
              <el-radio :label="0">关闭</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item
            v-if="peopleDayShow"
            :rules="{required: true, message: '每天最大获取数不能为空', trigger: 'submit'}"
            label="每天最大获取数"
            prop="per_person_times"
          >
            <el-input
              v-model="templateForm.per_person_times"
              :maxlength="6"
              class="coupon-form-input"
            />
          </el-form-item>
          <el-form-item
            :rules="{required:true}"
            label="每人每天无限领取"
            prop="per_person_per_day_unlimit"
          >
            <el-radio-group
              v-model="templateForm.per_person_per_day_unlimit"
              @change="unlimitedPeopleHandle"
            >
              <el-radio :label="1">开启</el-radio>
              <el-radio :label="0">关闭</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item
            v-if="peopleReceiveShow"
            :rules="{required: true, message: '每人每天最大获取数不能为空', trigger: 'submit'}"
            label="每人每天最大获取数"
            prop="per_person_per_day_times"
          >
            <el-input
              v-model="templateForm.per_person_per_day_times"
              :maxlength="6"
              class="coupon-form-input"
            />
          </el-form-item>
          <el-form-item>
            <el-button
              type="primary"
              @click="submit('templateForm')"
            >保存</el-button>
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
  getSearchCompany,
  savePolicy,
  modifyPolicy,
  getPolicyDetail
} from "service";
import {
  Form,
  Select,
  Option,
  FormItem,
  Button,
  Input,
  MessageBox,
  Radio,
  RadioGroup,
  Tooltip
} from "element-ui";
import { truncate } from "fs";
export default {
  components: {
    ElForm: Form,
    ElSelect: Select,
    ElOption: Option,
    ElFormItem: FormItem,
    ElButton: Button,
    ElInput: Input,
    ElRadio: Radio,
    ElRadioGroup: RadioGroup,
    "el-tooltip": Tooltip,
  },
  data() {
    return {
      disableEdit: false,
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      templateId: null,
      searchLoading: false,
      peopleReceiveShow: false,
      peopleDayShow: false,
      templateForm: {
        type: 1,
        name: "",
        per_person_unlimit: 1,
        per_person_times: 0,
        per_person_per_day_unlimit: 1,
        per_person_per_day_times: 0,
        company_id: null
      },
      companyList: []
    };
  },
  created() {
    this.templateId = this.$route.params.uid;
    this.getSearchCompany();
    if (this.templateId) {
      this.getPolicyDetail();
      this.disableEdit = true
    } else {
      this.disableEdit = false
    }
  },

  methods: {
    unlimitedDayHandle(val) {
      if (val === 1) {
        this.peopleDayShow = false;
      } else {
        this.peopleDayShow = true;
      }
    },
    unlimitedPeopleHandle(val) {
      if (val === 1) {
        this.peopleReceiveShow = false;
      } else {
        this.peopleReceiveShow = true;
      }
    },
    getSearchCompany() {
      getSearchCompany(this)
        .then(result => {
          this.companyList = result.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getPolicyDetail() {
      this.setting.loading = true;
      let args = {
        include: "company,batches"
      };
      getPolicyDetail(this, this.templateId, args)
        .then(res => {
          this.templateForm.name = res.name;
          this.templateForm.type = res.type;
          this.templateForm.per_person_unlimit = res.per_person_unlimit;
          this.templateForm.per_person_times = res.per_person_times;
          this.templateForm.per_person_per_day_unlimit =
            res.per_person_per_day_unlimit;
          this.templateForm.per_person_per_day_times =
            res.per_person_per_day_times;
          this.templateForm.company_id = res.company.id;
          this.unlimitedDayHandle(res.per_person_per_day_unlimit);
          this.unlimitedPeopleHandle(res.per_person_unlimit);
          this.setting.loading = false;
        })
        .catch(err => {
          this.setting.loading = false;
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let args = {
            type: this.templateForm.type,
            name: this.templateForm.name,
            per_person_unlimit: this.templateForm.per_person_unlimit,
            per_person_times: this.templateForm.per_person_times,
            per_person_per_day_unlimit: this.templateForm
              .per_person_per_day_unlimit,
            per_person_per_day_times: this.templateForm.per_person_per_day_times
          };
          if (this.templateId) {
            modifyPolicy(this, this.templateId, args)
              .then(response => {
                this.$message({
                  message: "修改成功",
                  type: "success"
                });
                this.$router.push({
                  path: "/prize/strategy"
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
            savePolicy(this, this.templateForm.company_id, args)
              .then(response => {
                this.$message({
                  message: "添加成功",
                  type: "success"
                });
                this.$router.push({
                  path: "/prize/strategy"
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
  .el-select,
  .el-input {
    width: 300px;
  }
}
</style>

