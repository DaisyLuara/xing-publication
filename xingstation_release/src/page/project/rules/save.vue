
<template>
  <div 
    class="add-coupon-wrap">
    <div 
      class="topbar">
      <el-breadcrumb 
        separator="/">
        <el-breadcrumb-item 
          :to="{ path: '/project/rules' }">优惠券规则管理</el-breadcrumb-item>
        <el-breadcrumb-item>{{ couponID ? '修改' : '添加' }}</el-breadcrumb-item>
      </el-breadcrumb>
      <headModule/>
    </div>
    <div 
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText" >
      <div 
        class="coupon-title">
        {{ $route.name }}
      </div>
      <el-form 
        ref="couponForm" 
        :model="couponForm"
        :rules="rules" 
        label-width="180px">
        <el-form-item 
          :rules="{required: true, message: '公司不能为空', trigger: 'submit'}"
          label="公司" 
          prop="company_id" >
          <el-select 
            v-model="couponForm.company_id" 
            placeholder="请选择公司" 
            class="coupon-form-select">
            <el-option
              v-for="item in companyList"
              :key="item.id"
              :label="item.name"
              :value="item.id"/>
          </el-select>
        </el-form-item>
        <el-form-item 
          label="创建人" 
          prop="user_name">
          <el-input 
            v-model="user_name" 
            :disabled="true"
            class="coupon-form-input"/>
        </el-form-item>
        <el-form-item 
          label="标题" 
          prop="title">
          <el-input 
            v-model="couponForm.title" 
            class="coupon-form-input"/>
        </el-form-item>
        <el-form-item 
          :rules="{required: true, message: '优惠券名称不能为空', trigger: 'submit'}"
          label="优惠券名称" 
          prop="name" >
          <el-input
            v-model="couponForm.name" 
            class="coupon-form-input" />
        </el-form-item>
        <el-form-item 
          label="优惠券描述" 
          prop="description">
          <el-input 
            v-model="couponForm.description" 
            type="textarea"
            class="coupon-form-input"/>
        </el-form-item>
        <el-form-item 
          label="图片链接" 
          prop="image_url">
          <el-input 
            v-model="couponForm.image_url"
            class="coupon-form-input"/>
        </el-form-item>
        <el-form-item 
          label="跳转链接" 
          prop="redirect_url">
          <el-input 
            v-model="couponForm.redirect_url"
            class="coupon-form-input"/>
        </el-form-item>
        <el-form-item 
          label="优先级" 
          prop="sort_order">
          <el-input
            v-model="couponForm.sort_order"
            :maxlength="3"
            class="coupon-form-input"/>
        </el-form-item>
        <el-form-item 
          label="类型" 
          prop="type">
          <el-radio 
            v-model="couponForm.type" 
            :label="1">优惠券</el-radio>
          <el-radio 
            v-model="couponForm.type" 
            :label="2">小样</el-radio>
        </el-form-item>
        <el-form-item 
          label="金额" 
          prop="amount">
          <el-input
            v-model="couponForm.amount" 
            :maxlength="6"
            class="coupon-form-input"/>
        </el-form-item>
        <el-form-item 
          :rules="{required: true, message: '库存总数不能为空', trigger: 'submit'}"
          label="库存总数" 
          prop="count">
          <el-input 
            v-model="couponForm.count" 
            :maxlength="6"
            class="coupon-form-input"/>
        </el-form-item>
        <el-form-item 
          :rules="{required: true, message: '剩余库存不能为空', trigger: 'submit'}"
          label="剩余库存" 
          prop="stock" >
          <el-input 
            v-model="couponForm.stock" 
            :maxlength="6"
            class="coupon-form-input"/>
        </el-form-item>
        <el-form-item 
          label="每人最大获取数" 
          prop="people_max_get">
          <el-input 
            v-model="couponForm.people_max_get" 
            :maxlength="6"
            class="coupon-form-input"/>
        </el-form-item>
        <el-form-item 
          label="是否开启每人无限领取" 
          prop="pmg_status">
          <el-radio 
            v-model="couponForm.pmg_status" 
            :label="1">开启</el-radio>
          <el-radio 
            v-model="couponForm.pmg_status" 
            :label="0">关闭</el-radio>
        </el-form-item>
        <el-form-item 
          label="每天最大获取数" 
          prop="day_max_get">
          <el-input 
            v-model="couponForm.day_max_get" 
            :maxlength="6"
            class="coupon-form-input"/>
        </el-form-item>
        <el-form-item 
          label="是否开启每天无限领取" 
          prop="dmg_status">
          <el-radio 
            v-model="couponForm.dmg_status" 
            :label="1">开启</el-radio>
          <el-radio 
            v-model="couponForm.dmg_status" 
            :label="0">关闭</el-radio>
        </el-form-item>
        <el-form-item 
          label="是否固定日期" 
          prop="is_fixed_date">
          <el-radio 
            v-model="couponForm.is_fixed_date" 
            :label="1">固定</el-radio>
          <el-radio 
            v-model="couponForm.is_fixed_date" 
            :label="0">不固定</el-radio>
        </el-form-item>
        <el-form-item 
          label="延后生效日期" 
          prop="delay_effective_day">
          <el-input 
            v-model="couponForm.delay_effective_day" 
            :maxlength="6"
            class="coupon-form-input"/>
        </el-form-item>
        <el-form-item 
          label="有效天数" 
          prop="effective_day">
          <el-input 
            v-model="couponForm.effective_day" 
            :maxlength="6"
            class="coupon-form-input"/>
        </el-form-item>
        <el-form-item 
          label="开始日期" 
          prop="start_date">
          <el-date-picker
            v-model="couponForm.start_date"
            type="date"
            placeholder="选择日期"
            class="coupon-form-date"
            value-format="yyyy-MM-dd HH:mm:ss"/>
        </el-form-item>
        <el-form-item 
          label="结束日期"
          prop="end_date">
          <el-date-picker
            v-model="couponForm.end_date"
            type="date"
            placeholder="选择日期"
            class="coupon-form-date"
            value-format="yyyy-MM-dd HH:mm:ss"
          />
        </el-form-item>
        <el-form-item 
          label="状态" 
          prop="is_active">
          <el-radio 
            v-model="couponForm.is_active" 
            :label="1">启用</el-radio>
          <el-radio 
            v-model="couponForm.is_active" 
            :label="0">停用</el-radio>
        </el-form-item>
        <el-form-item>
          <el-button 
            type="primary"
            @click="onSubmit('couponForm')">保存</el-button>
          <el-button 
            @click="historyBack()">取消</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import coupon from 'service/coupon'
import search from 'service/search'
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
  name: 'AddCoupon',
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
  },
  data() {
    var checkEndDate = (rule, value, callback) => {
      if (!value) {
        callback()
        return
      }
      if (
        new Date(value.replace(/\-/g, '/')).getTime() <
        new Date(this.couponForm.start_date.replace(/\-/g, '/')).getTime()
      ) {
        callback(new Error('结束日期要大于开始日期'))
      } else {
        callback()
      }
    }
    var checkSortOrder = (rule, value, callback) => {
      if (!value) {
        callback()
        return
      }
      console.log(/^[0-9]+.?[0-9]*$/.test(value))
      if (/^[0-9]+.?[0-9]*$/.test(value)) {
        if (parseInt(value) < 1 || parseInt(value) > 100) {
          callback(new Error('优先级只能是1-100'))
        } else {
          callback()
        }
      } else {
        callback('优先级只能是数字')
      }
    }
    return {
      companyList: [],
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: '拼命加载中'
      },
      rules: {
        end_date: [{ validator: checkEndDate, trigger: 'submit' }],
        sort_order: [{ validator: checkSortOrder, trigger: 'submit' }]
      },
      user_name: '',
      couponForm: {
        name: '',
        title: '',
        description: '',
        company_id: '',
        type: 1,
        image_url: '',
        redirect_url: '',
        amount: 0,
        count: 0,
        sort_order: 1,
        stock: 0,
        people_max_get: 0,
        pmg_status: 0,
        day_max_get: 0,
        dmg_status: 0,
        is_fixed_date: 0,
        delay_effective_day: 0,
        effective_day: 0,
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
    this.couponID = this.$route.params.uid
    this.setting.loadingText = '拼命加载中'
    this.setting.loading = true
    //获取公司列表
    let companyPromise = search
      .getCompanyList(this)
      .then(result => {
        this.companyList = result.data
      })
      .catch(error => {
        console.log(error)
      })

    Promise.all([companyPromise]).then(() => {
      if (this.couponID) {
        let args = {
          include: 'user,company'
        }
        coupon
          .getCouponDetial(this, this.couponID, args)
          .then(result => {
            this.couponForm.name = result.name
            this.couponForm.description = result.description
            this.couponForm.company_id = result.company.id
            this.couponForm.image_url = result.image_url
            this.couponForm.amount = result.amount
            this.couponForm.count = result.count
            this.couponForm.stock = result.stock
            this.couponForm.people_max_get = result.people_max_get
            this.couponForm.pmg_status = result.pmg_status
            this.couponForm.day_max_get = result.day_max_get
            this.couponForm.dmg_status = result.dmg_status
            this.couponForm.is_fixed_date = result.is_fixed_date
            this.couponForm.delay_effective_day = result.delay_effective_day
            this.couponForm.effective_day = result.effective_day
            this.couponForm.start_date = result.start_date
            this.couponForm.end_date = result.end_date
            this.couponForm.is_active = result.is_active
            this.couponForm.redirect_url = result.redirect_url
            this.couponForm.type = result.type
            this.couponForm.sort_order = result.sort_order
            this.couponForm.title = result.title
            this.user_name = result.user.name
            this.setting.loading = false
          })
          .catch(error => {
            console.log(error)
          })
      } else {
        this.user_name = user.name
        this.setting.loading = false
      }
    })
  },
  methods: {
    onSubmit(formName) {
      let company_id = this.couponForm.company_id
      let args = {
        name: this.couponForm.name,
        description: this.couponForm.description,
        image_url: this.couponForm.image_url,
        amount: this.couponForm.amount,
        count: this.couponForm.count,
        stock: this.couponForm.stock,
        people_max_get: this.couponForm.people_max_get,
        pmg_status: this.couponForm.pmg_status,
        day_max_get: this.couponForm.day_max_get,
        dmg_status: this.couponForm.dmg_status,
        is_fixed_date: this.couponForm.is_fixed_date,
        delay_effective_day: this.couponForm.delay_effective_day,
        effective_day: this.couponForm.effective_day,
        is_active: this.couponForm.is_active,
        redirect_url: this.couponForm.redirect_url,
        type: this.couponForm.type,
        sort_order: this.couponForm.sort_order,
        title: this.couponForm.title
      }
      if (!this.couponForm.image_url) {
        delete args.image_url
      }
      if (this.couponForm.title === '') {
        delete args.title
      }
      if (this.couponForm.redirect_url === '') {
        delete args.redirect_url
      }
      if (!this.couponForm.description) {
        delete args.description
      }
      if (this.couponForm.start_date) {
        args.start_date = this.couponForm.start_date
      }
      if (this.couponForm.end_date) {
        args.end_date = this.handleDateTransform(this.couponForm.end_date)
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          coupon
            .saveCoupon(this, args, this.couponID, company_id)
            .then(result => {
              this.loading = false
              this.$message({
                message: this.couponID ? '修改成功' : '添加成功',
                type: 'success'
              })
              this.$router.push({
                path: '/project/rules/'
              })
            })
            .catch(error => {
              this.loading = false
              console.log(error)
            })
        }
      })
    },
    resetForm(formName) {
      this.$refs[formName].resetFields()
    },
    historyBack() {
      router.back()
    },
    handleDateTransform(valueDate) {
      let dateValue = valueDate.replace(/\-/g, '/')
      let date = new Date(dateValue)
      let year = date.getFullYear() + '-'
      let mouth =
        (date.getMonth() + 1 < 10
          ? '0' + (date.getMonth() + 1)
          : date.getMonth() + 1) + '-'
      let day =
        (date.getDate() < 10 ? '0' + date.getDate() : date.getDate()) + ''
      let hours = date.getHours() === 0 ? date.getHours() + 23 : date.getHours()
      let minutes =
        date.getMinutes() === 0 ? date.getMinutes() + 59 : date.getMinutes()
      let second =
        date.getSeconds() === 0 ? date.getSeconds() + 59 : date.getSeconds()
      return year + mouth + day + ' ' + hours + ':' + minutes + ':' + second
    }
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
