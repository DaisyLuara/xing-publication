<template>
  <div class="root">
    <div class="account-wrap">
      <div class="item-info">
        <div class="prize-title">{{ policyId ? '子条目修改' : '子条目新增' }}</div>
        <el-form 
          ref="policyForm" 
          :model="policyForm" 
          label-width="180px">
          <el-form-item
            :rules="[{ required: true, message: '请选择优惠券', trigger: 'submit'}]"
            label="优惠券名称"
            prop="coupon_batch_id"
          >
            <el-select
              v-model="policyForm.coupon_batch_id"
              :loading="searchLoading"
              placeholder="请选择优惠券"
              filterable
              clearable
            >
              <el-option
                v-for="item in couponList"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            :rules="[{ required: true, message: '请选择性别', trigger: 'submit'}]"
            label="性别"
            prop="gender"
          >
            <el-select
              v-model="policyForm.gender"
              :loading="searchLoading"
              placeholder="请选择优惠券"
              filterable
              clearable
            >
              <el-option
                v-for="item in genderList"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            :rules="[{ required: true, message: '请填写最大年龄', trigger: 'submit'}]"
            label="最大年龄"
            prop="max_age"
          >
            <el-input 
              v-model="policyForm.max_age" 
              placeholder="请填写最大年龄" 
              clearable/>
          </el-form-item>
          <el-form-item
            :rules="[{ required: true, message: '请填写最小年龄', trigger: 'submit'}]"
            label="最小年龄"
            prop="min_age"
          >
            <el-input 
              v-model="policyForm.min_age" 
              placeholder="请填写最小年龄" 
              clearable/>
          </el-form-item>
          <el-form-item
            :rules="[{ required: true, message: '请填写最大分数', trigger: 'submit'}]"
            label="最大分数"
            prop="max_score"
          >
            <el-input 
              v-model="policyForm.max_score" 
              placeholder="请填写最大分数" 
              clearable/>
          </el-form-item>
          <el-form-item
            :rules="[{ required: true, message: '请填写最小分数', trigger: 'submit'}]"
            label="最小分数"
            prop="min_score"
          >
            <el-input 
              v-model="policyForm.min_score" 
              placeholder="请填写最小分数" 
              clearable/>
          </el-form-item>
          <el-form-item
            :rules="[{ required: true, message: '请填写概率', trigger: 'submit'}]"
            label="概率"
            prop="rate"
          >
            <el-input 
              v-model="policyForm.rate" 
              placeholder="请填写概率" 
              clearable/>
          </el-form-item>
          <el-form-item>
            <el-button 
              type="primary" 
              @click="submit('policyForm')">保存</el-button>
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
  getSearchCoupon,
  saveBatchPolicy,
  modifyBatchPolicy,
  getCouponPolicieseDetail
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
import { truncate } from "fs";
export default {
  components: {
    ElForm: Form,
    ElSelect: Select,
    ElOption: Option,
    ElFormItem: FormItem,
    ElButton: Button,
    ElInput: Input
  },
  data() {
    return {
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      genderList: [
        {
          id: 1,
          name: "女"
        },
        {
          id: 0,
          name: "男"
        }
      ],
      policyId: null,
      searchLoading: false,
      policyForm: {
        min_age: 0,
        max_age: 0,
        gender: 0,
        rate: 0,
        max_score: 0,
        min_score: 0,
        coupon_batch_id: null
      },
      couponList: [],
      cid: null
    };
  },
  created() {
    this.policyId = this.$route.params.uid;
    this.cid = this.$route.query.cid;
    this.pid = this.$route.query.pid;
    this.getCouponList();
    if (this.policyId) {
      this.getCouponPolicieseDetail();
    }
  },

  methods: {
    getCouponPolicieseDetail() {
      this.setting.loading = true;
      getCouponPolicieseDetail(this, this.pid, this.policyId)
        .then(res => {
          this.policyForm.min_age = res.pivot.min_age;
          this.policyForm.max_age = res.pivot.max_age;
          this.policyForm.gender = res.pivot.gender;
          this.policyForm.rate = res.pivot.rate;
          this.policyForm.max_score = res.pivot.max_score;
          this.policyForm.min_score = res.pivot.min_score;
          this.policyForm.coupon_batch_id = res.pivot.coupon_batch_id;
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
            min_age: parseInt(this.policyForm.min_age),
            max_age: parseInt(this.policyForm.max_age),
            gender: parseInt(this.policyForm.gender),
            rate: this.policyForm.rate,
            max_score: parseFloat(this.policyForm.max_score),
            min_score: parseFloat(this.policyForm.min_score),
            coupon_batch_id: this.policyForm.coupon_batch_id
          };
          if (this.policyId) {
            modifyBatchPolicy(this, this.pid, args, this.policyId)
              .then(response => {
                this.$message({
                  message: "修改成功",
                  type: "success"
                });
                this.$router.push({
                  path: "/prize/strategy/policy",
                  query: {
                    pid: this.pid,
                    cid: this.cid
                  }
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
            saveBatchPolicy(this, this.pid, args)
              .then(response => {
                this.$message({
                  message: "添加成功",
                  type: "success"
                });
                this.$router.push({
                  path: "/prize/strategy/policy",
                  query: {
                    pid: this.pid,
                    cid: this.cid
                  }
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
    getCouponList() {
      let args = {
        company_id: this.cid
      };
      getSearchCoupon(this, args)
        .then(result => {
          this.couponList = result.data;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
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

