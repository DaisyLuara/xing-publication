<template>
  <div class="add-customer-wrap">
    <div class="topbar">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/customer/customers' }">联系人管理</el-breadcrumb-item>
        <el-breadcrumb-item>{{contactID ? '修改' : '添加'}}</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="customer-title">
      {{$route.name}}
      </div>
      <el-form ref="contactForm" :model="contactForm" :rules="rules" label-width="100px">
        <el-form-item label="联系人名称" prop="contact.name">
          <el-input class="customer-form-input" v-model="contactForm.contact.name" :maxlength="50"></el-input>
        </el-form-item>
        <el-form-item label="联系电话" prop="contact.phone">
          <el-input class="customer-form-input" v-model="contactForm.contact.phone" :maxlength="11"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" :loading="loading"  @click="onSubmit('contactForm')">保存</el-button>
          <el-button @click="resetForm('contactForm')">取消</el-button>
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
  name: 'addContact',
  data() {
    return {
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      contactForm: {
        contact: {
          name: '',
          phone: '',
        },
      },
      pid: '',
      contactID: '',
      rules: {
        "contact.phone": [
          { validator: (rule, value, callback) => {
            if (/^\s*$/.test(value)) {
              callback('请输入手机')
            } else if(!/^1[3456789]\d{9}$/.test(value)) {
              callback('手机格式不正确,请重新输入')
            } else {
              callback()
            }
          }, trigger: 'blur' , required: true}
        ],
        "contact.name": [
          { message: '请输入联系人名称', trigger: 'blur' , required: true}
        ],
      },
      contactName:'',
      loading: false
    }
  },
  created: function(){
    if(this.setting.loading == true){
      return false
    }
    this.contactID = this.$route.query.uid
    this.pid = this.$route.query.pid
    this.contactName = this.$route.query.name
    this.getContactDetial()
    this.setting.loadingText = "拼命加载中"
    this.setting.loading = false
  },
  methods: {
    onSubmit(formName) {
      this.$refs[formName].validate((valid) => {
        if(valid){
          this.setting.loading = true;
          let pid = this.pid
          let name = this.contactName
          let uid = this.$route.query.uid
          company.saveContact(this, pid, this[formName].contact, uid).then(result => {
            this.setting.loading = false
            this.$message({
              message: uid ? "修改成功" : "添加成功",
              type: "success"
            })
            this.$router.push({
              path: "/company/customers/contacts?id="+id+"&name=" + name
            })
          }).catch(error => {
            this.setting.loading = false
            console.log(error)
          })
        }else{
          console.log('error submit');
          return;
        }
      })
    },
    getContactDetial(){
      let uid = this.$route.query.uid
      if(uid) {
        company.getContactDetial(this, this.pid, uid ).then((result) => {
          this.contactForm.contact = result
          this.setting.loading = false
        }).catch((err) => {
          console.log(err)
          this.setting.loading = false
        })
      }
    },
    resetForm(formName) {
     this.$refs[formName].resetFields();
    },
    historyBack () {
      router.back()
    }
  },
  components: {
    "el-select": Select,
    "el-option": Option,
    "el-button": Button,
    "el-input": Input,
    "el-form": Form,
    "el-form-item": FormItem,
  }
}
</script>
<style scoped lang="less">
  .add-customer-wrap{
    background: #fff;
    padding: 30px;
    .customer-form-input{
      width: 385px;
    }
    .up-area-cover {
      border: 1px dashed #d9d9d9;
      width: 228px;
      height: 228px;
      cursor: pointer;
      position: relative;
      .cover{
        width: 228px;
        height: 228px;
        display: block
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
    .customer-title{
      margin-bottom: 20px;
    }
    .el-checkbox{
      margin-left: 0px;
      margin-right: 15px;
    }
  }
</style>
