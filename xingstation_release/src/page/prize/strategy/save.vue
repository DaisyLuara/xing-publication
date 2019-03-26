<template>
  <div class="root">
    <div class="account-wrap">
      <div class="item-info">
        <div class="prize-title">{{ policyId ? '子条目修改' : '子条目新增'}}</div>
        <el-form ref="policyForm" :model="policyForm" label-width="180px">
          <el-form-item
            :rules="[{ required: true, message: '请选择公司', trigger: 'submit'}]"
            label="公司名称"
            prop="company_id"
          >
            <el-select
              v-model="policyForm.company_id"
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
            :rules="[{ required: true, message: '请选择优惠券', trigger: 'submit'}]"
            label="优惠券名称"
            prop="policy_id"
          >
            <el-select
              v-model="policyForm.policy_id"
              :loading="searchLoading"
              placeholder="请选择优惠券"
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
              v-model="policyForm.oid"
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
              v-model="policyForm.project_id"
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
            <el-button type="primary" @click="onSubmit('policyForm')">保存</el-button>
            <el-button @click="back">返回</el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</template>
<script>
import { historyBack, getSearchCouponList } from "service";
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
      policyId: null,
      searchLoading: false,
      policyForm: {
        company_id: null,
        company_id: null,
        policy_id: null,
        oid: null,
        project_id: null
      },
      projectList: [],
      companyList: [],
      pointList: [],
      policyList: [],
      couponList: []
    };
  },
  methods: {
    getCouponList(company_id) {
      let args = {
        company_id: company_id
      };
      getSearchCouponList(this, args)
        .then(result => {
          this.couponList = result.data;
        })
        .catch(err => {
          console.log(err);
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

