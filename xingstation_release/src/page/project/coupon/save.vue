<template>
  <div class="add-coupon-wrap">
    <div class="topbar">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/project/coupon' }">优惠券管理</el-breadcrumb-item>
        <el-breadcrumb-item>{{couponID ? '修改' : '添加'}}</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="coupon-title">
      {{$route.name}}
      </div>
      <el-form ref="couponForm" :model="couponForm" label-width="180px">
        <el-form-item label="公司" prop="company_id" :rules="{required: true, message: '公司不能为空', trigger: 'submit'}">
          <el-select v-model="couponForm.company_id" placeholder="请选择公司" class="coupon-form-select">
            <el-option
              v-for="item in companyList"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="创建人" prop="user_name">
          <el-input class="coupon-form-input" v-model="user_name" :disabled="true"></el-input>
        </el-form-item>
        <el-form-item label="优惠券名称" prop="coupon_name" :rules="{required: true, message: '优惠券名称不能为空', trigger: 'submit'}">
          <el-input class="coupon-form-input" v-model="couponForm.coupon_name"></el-input>
        </el-form-item>
        <el-form-item label="优惠券描述" prop="coupon_desc">
          <el-input class="coupon-form-input" v-model="couponForm.coupon_desc" type="textarea"></el-input>
        </el-form-item>
        <el-form-item label="图片链接" prop="image">
          <el-input class="coupon-form-input" v-model="couponForm.image" ></el-input>
        </el-form-item>
        <el-form-item label="金额" prop="amount">
          <el-input class="coupon-form-input" v-model="couponForm.amount" :maxlength='4'></el-input>
        </el-form-item>
        <el-form-item label="库存总数" prop="count" :rules="{required: true, message: '库存总数不能为空', trigger: 'submit'}">
          <el-input class="coupon-form-input" v-model="couponForm.count" :maxlength='4'></el-input>
        </el-form-item>
        <el-form-item label="剩余库存" prop="stock" :rules="{required: true, message: '剩余库存不能为空', trigger: 'submit'}">
          <el-input class="coupon-form-input" v-model="couponForm.stock" :maxlength='4'></el-input>
        </el-form-item>
        <el-form-item label="每人最大获取数" prop="people_max_get">
          <el-input class="coupon-form-input" v-model="couponForm.people_max_get" :maxlength='4'></el-input>
        </el-form-item>
        <el-form-item label="是否开启每人无限领取" prop="pmg_status">
          <el-radio v-model="couponForm.pmg_status" :label="1">开启</el-radio>
          <el-radio v-model="couponForm.pmg_status" :label="0">关闭</el-radio>
        </el-form-item>
        <el-form-item label="每天最大获取数" prop="day_max_get">
          <el-input class="coupon-form-input" v-model="couponForm.day_max_get" :maxlength='4'></el-input>
        </el-form-item>
        <el-form-item label="是否开启每天无限领取" prop="dmg_status">
          <el-radio v-model="couponForm.dmg_status" :label="1">开启</el-radio>
          <el-radio v-model="couponForm.dmg_status" :label="0">关闭</el-radio>
        </el-form-item>
        <el-form-item label="是否固定日期" prop="is_fixed_date">
          <el-radio v-model="couponForm.is_fixed_date" :label="1">固定</el-radio>
          <el-radio v-model="couponForm.is_fixed_date" :label="0">不固定</el-radio>
        </el-form-item>
        <el-form-item label="延后生效日期" prop="delay_effective_day">
          <el-input class="coupon-form-input" v-model="couponForm.delay_effective_day" :maxlength='4'></el-input>
        </el-form-item>
        <el-form-item label="有效天数" prop="effective_day">
          <el-input class="coupon-form-input" v-model="couponForm.effective_day" :maxlength='4'></el-input>
        </el-form-item>
        <el-form-item label="开始日期" prop="start_date">
          <el-date-picker
            v-model="couponForm.start_date"
            type="date"
            placeholder="选择日期"
            class="coupon-form-date">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="结束日期" prop="end_date">
          <el-date-picker
            v-model="couponForm.end_date"
            type="date"
            placeholder="选择日期"
            class="coupon-form-date">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="状态" prop="is_active">
          <el-radio v-model="couponForm.is_active" :label="1">启用</el-radio>
          <el-radio v-model="couponForm.is_active" :label="0">停用</el-radio>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit('couponForm')">保存</el-button>
          <el-button @click="historyBack()">取消</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import coupon from 'service/coupon'
import router from 'router'
import {
  Button,
  Input,
  Form,
  FormItem,
  RadioGroup,
  Radio,
  DatePicker,
  Select,
  Option
} from 'element-ui'

export default {
  name: 'addCoupon',
  data() {
    return {
      companyList: [
        {
          value: '选项1',
          label: '黄金糕'
        },
        {
          value: '选项2',
          label: '双皮奶'
        },
        {
          value: '选项3',
          label: '蚵仔煎'
        },
        {
          value: '选项4',
          label: '龙须面'
        },
        {
          value: '选项5',
          label: '北京烤鸭'
        }
      ],
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: '拼命加载中'
      },
      user_name: '',
      couponForm: {
        coupon_name: '',
        coupon_desc: '',
        company_id: '',
        image: '',
        amount: null,
        count: null,
        stock: null,
        people_max_get: null,
        pmg_status: 0,
        day_max_get: null,
        dmg_status: null,
        is_fixed_date: null,
        delay_effective_day: null,
        effective_day: null,
        start_date: '',
        end_date: '',
        is_active: 1
      },
      couponID: ''
    }
  },
  created() {
    if (this.setting.loading == true) {
      return false
    }
    let user = JSON.parse(localStorage.getItem('user_info'))
    this.user_name = user.name
    this.couponID = this.$route.params.uid
    this.setting.loadingText = '拼命加载中'
    this.setting.loading = true
    if (this.couponID) {
      let args = {
        include: 'roles'
      }
      coupon
        .getCouponDetial(this, this.couponID, args)
        .then(result => {
          // this.userForm.user.phone = result.phone
          // this.userForm.user.name = result.name
          this.setting.loading = false
        })
        .catch(error => {
          console.log(error)
        })
    } else {
      this.setting.loading = false
    }
  },
  methods: {
    onSubmit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
        }
      })
    },
    resetForm(formName) {
      this.$refs[formName].resetFields()
    },
    historyBack() {
      router.back()
    }
  },
  components: {
    'el-button': Button,
    'el-input': Input,
    'el-form': Form,
    'el-form-item': FormItem,
    'el-radio-group': RadioGroup,
    'el-radio': Radio,
    'el-select': Select,
    'el-date-picker': DatePicker,
    'el-option': Option
  }
}
</script>
<style scoped lang="less">
.add-coupon-wrap {
  background: #fff;
  padding: 20px;
  .coupon-form-input {
    width: 300px;
  }
  .coupon-form-date {
    width: 300px;
  }
  .coupon-form-select {
    width: 300px;
  }
  .up-area-cover {
    border: 1px dashed #d9d9d9;
    width: 228px;
    height: 228px;
    cursor: pointer;
    position: relative;
    .cover {
      width: 228px;
      height: 228px;
      display: block;
    }
    .cover-uploader-icon {
      font-size: 28px;
      color: #8c939d;
      width: 228px;
      height: 228px;
      line-height: 228px;
      text-align: center;
    }
    .delete-icon-image {
      position: absolute;
      top: 5px;
      right: 5px;
      font-size: 20px;
      color: #83909a;
      cursor: pointer;
    }
  }
  .coupon-title {
    margin-bottom: 20px;
  }
  .el-checkbox {
    margin-left: 0px;
    margin-right: 15px;
  }
}
</style>
