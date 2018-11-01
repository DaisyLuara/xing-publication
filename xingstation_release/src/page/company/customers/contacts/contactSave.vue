<template>
  <div 
    class="add-customer-wrap">
    <div 
      class="topbar">
      <el-breadcrumb 
        separator="/">
        <el-breadcrumb-item 
          :to="{ path: '/company/customers/contacts?id=' + pid +'&name=' + contactName }">联系人管理</el-breadcrumb-item>
        <el-breadcrumb-item>{{ contactID ? '修改' : '添加' }}</el-breadcrumb-item>
      </el-breadcrumb>
      <headModule/>
    </div>
    <div
      v-loading="setting.loading" 
      :element-loading-text="setting.loadingText">
      <div 
        class="customer-title">
        {{ $route.name }}
      </div>
      <el-form 
        ref="contactForm" 
        :model="contactForm" 
        :rules="rules" 
        label-width="100px">
        <el-form-item 
          label="联系人名称" 
          prop="contact.name">
          <el-input 
            v-model="contactForm.contact.name" 
            :maxlength="50"
            class="customer-form-input"/>
        </el-form-item>
        <el-form-item 
          label="联系电话" 
          prop="contact.phone">
          <el-input 
            v-model="contactForm.contact.phone" 
            :maxlength="11"
            class="customer-form-input"/>
        </el-form-item>
        <el-form-item 
          label="密码" 
          prop="contact.password">
          <el-input 
            type="password"
            v-model="contactForm.contact.password" 
            :maxlength="25"
            class="customer-form-input"/>
        </el-form-item>
        <el-form-item>
          <el-button 
            :loading="loading"  
            type="primary"
            size="small"
            @click="onSubmit('contactForm')">保存</el-button>
          <el-button 
            size="small"
            @click="resetForm('contactForm')">取消</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import company from 'service/company'
import router from 'router'
import { Select, Option, Button, Input, Form, FormItem } from 'element-ui'

export default {
  name: 'AddContact',
  components: {
    'el-select': Select,
    'el-option': Option,
    'el-button': Button,
    'el-input': Input,
    'el-form': Form,
    'el-form-item': FormItem
  },
  data() {
    return {
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: '拼命加载中'
      },
      contactForm: {
        contact: {
          name: '',
          phone: '',
          password: ''
        }
      },
      pid: '',
      contactID: '',
      rules: {
        'contact.phone': [
          {
            validator: (rule, value, callback) => {
              if (/^\s*$/.test(value)) {
                callback('请输入手机')
              } else if (!/^1[3456789]\d{9}$/.test(value)) {
                callback('手机格式不正确,请重新输入')
              } else {
                callback()
              }
            },
            trigger: 'blur',
            required: true
          }
        ],
        'contact.name': [
          { message: '请输入联系人名称', trigger: 'blur', required: true }
        ]
      },
      contactName: '',
      loading: false
    }
  },
  created: function() {
    if (this.setting.loading == true) {
      return false
    }
    this.contactID = this.$route.query.uid
    this.pid = this.$route.query.pid
    this.contactName = this.$route.query.name
    this.getContactDetial()
    this.setting.loadingText = '拼命加载中'
    this.setting.loading = false
  },
  methods: {
    onSubmit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.setting.loading = true
          let pid = this.pid
          let name = this.contactName
          let uid = this.$route.query.uid
          let args = {
            name: this.contactForm.contact.name,
            phone: this.contactForm.contact.phone,
            password: this.contactForm.contact.password
          }
          if (this.contactForm.contact.password === '') {
            delete args.password
          }
          company
            .saveContact(this, pid, args, uid)
            .then(result => {
              this.setting.loading = false
              this.$message({
                message: uid ? '修改成功' : '添加成功',
                type: 'success'
              })
              this.$router.push({
                path: '/company/customers/contacts?id=' + pid + '&name=' + name
              })
            })
            .catch(error => {
              this.setting.loading = false
              console.log(error)
            })
        } else {
          return
        }
      })
    },
    getContactDetial() {
      let uid = this.$route.query.uid
      if (uid) {
        company
          .getContactDetial(this, this.pid, uid)
          .then(result => {
            this.contactForm.contact = result
            this.setting.loading = false
          })
          .catch(err => {
            console.log(err)
            this.setting.loading = false
          })
      }
    },
    resetForm(formName) {
      this.$refs[formName].resetFields()
    },
    historyBack() {
      router.back()
    }
  }
}
</script>
<style scoped lang="less">
.add-customer-wrap {
  background: #fff;
  padding: 30px;
  .customer-form-input {
    width: 385px;
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
  .customer-title {
    margin-bottom: 20px;
  }
  .el-checkbox {
    margin-left: 0px;
    margin-right: 15px;
  }
}
</style>
