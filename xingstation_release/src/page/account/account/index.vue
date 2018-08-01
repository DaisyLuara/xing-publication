<template>
  <div 
    class="root">
    <div 
      class="account-wrap">
      <div 
        class="item-info">
        <el-form 
          ref="userForm" 
          :model="userForm" 
          :rules="rules" 
          label-width="80px">
          <el-form-item 
            label="姓名" 
            prop="user.name">
            <el-input  
              v-model="userForm.user.name" 
              :maxlength="10" 
              class="user-form-input"/>
          </el-form-item>
          <el-form-item 
            label="手机号码"
            prop="user.phone">
            <el-input  
              v-model="userForm.user.phone" 
              :maxlength="11" 
              class="user-form-input"/>
          </el-form-item>
          <el-form-item 
            label="密码" 
            prop="user.password">
            <el-input 
              v-model="userForm.user.password" 
              type="password" 
              class="user-form-input" />
          </el-form-item>
          <el-form-item 
            label="确认密码" 
            prop="user.repassword">
            <el-input  
              v-model="userForm.user.repassword" 
              type="password" 
              class="user-form-input"/>
          </el-form-item>
          <el-form-item>
            <el-button 
              :loading="loading" 
              type="primary" 
              @click="onSubmit('userForm')">保存</el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</template>

<script>
const USERINFO_API = '/api/user?include=permissions'

import auth from 'service/auth'
import router from 'router'
import {
  Row,
  Col,
  Button,
  Input,
  Form,
  FormItem,
  Checkbox,
  CheckboxGroup,
  RadioGroup,
  Radio
} from 'element-ui'

export default {
  name: 'Account',
  components: {
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
      userForm: {
        user: {
          name: '',
          phone: '',
          password: '',
          repassword: ''
        }
      },
      rules: {
        'user.phone': [
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
            trigger: 'blur'
          }
        ],
        'user.name': [{ message: '请输入姓名', trigger: 'blur' }],
        'user.password': [
          {
            validator: (rule, value, callback) => {
              if (/^\s*$/.test(value)) {
                callback('请输入密码')
              } else if (!/^.{6,20}$/.test(value)) {
                callback('密码长度不正确,请重新输入')
              } else {
                callback()
              }
            },
            trigger: 'blur'
          }
        ],
        'user.repassword': [
          {
            validator: (rule, value, callback) => {
              if (value === '') {
                callback(new Error('请再次输入密码'))
              } else if (value !== this.userForm.user.password) {
                callback(new Error('两次输入密码不一致!'))
              } else {
                callback()
              }
            },
            trigger: 'blur'
          }
        ]
      },
      loading: false
    }
  },
  created: function() {
    if (this.setting.loading == true) {
      return false
    }
    this.setting.loadingText = '拼命加载中'
    let user = JSON.parse(localStorage.getItem('user_info'))
    this.userForm.user.name = user.name
    this.userForm.user.phone = user.phone
    this.setting.loading = false
  },
  methods: {
    onSubmit(formName) {
      this.$refs[formName].validate(valid => {
        if (!this.userForm.user.password && !this.userForm.user.repassword) {
          delete this.rules['user.password']
          delete this.rules['user.repassword']
          delete this[formName].user.password
          valid = true
        }
        if (valid) {
          this.loading = true
          delete this[formName].user.repassword
          auth
            .modifyUser(this, this[formName].user)
            .then(result => {
              this.loading = false
              let user_info = JSON.parse(localStorage.getItem('user_info'))
              user_info.name = result.name
              user_info.phone = result.phone
              user_info.password = ''
              this.$store.commit('setCurUserInfo', user_info)
              localStorage.setItem('user_info', JSON.stringify(user_info))
              this.$message({
                message: '修改成功',
                type: 'success'
              })
            })
            .catch(error => {
              this.loading = false
              console.log(error)
            })
        } else {
          return
        }
      })
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
.root {
  padding: 10px;
  font-size: 14px;
  color: #5e6d82;
  .account-wrap {
    background: #fff;
    padding: 30px;
    .user-form-input {
      width: 385px;
    }
    .system-title {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 10px;
    }
    .item-info {
      .to_view {
        color: #074fc5;
      }
      label {
        margin-left: 15px;
      }
    }
    .company-logo-wrap {
      width: 180px;
      height: 180px;
      margin-left: 15px;
      .company-logo {
        display: inline-block;
        width: 50%;
      }
      .download {
        color: #074fc5;
      }
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
    .user-title {
      margin-bottom: 20px;
    }
    .el-checkbox {
      margin-left: 0px;
      margin-right: 15px;
    }
  }
}
</style>

