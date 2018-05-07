<template>
  <div class="add-customer-wrap">
    <div class="topbar">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/customer/customers' }">客户管理</el-breadcrumb-item>
        <el-breadcrumb-item>{{customerID ? '修改' : '添加'}}</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="customer-title">
      {{$route.name}}
      </div>
      <el-form ref="customerForm" :model="customerForm" :rules="rules" label-width="100px">
        <el-form-item label="公司名称" prop="customer.name">
          <el-input class="customer-form-input" v-model="customerForm.customer.name" :maxlength="50"></el-input>
        </el-form-item>
        <el-form-item label="联系人" prop="customer.customer_name">
          <el-input class="customer-form-input" v-model="customerForm.customer.customer_name" :maxlength="18"></el-input>
        </el-form-item>
        <el-form-item label="联系人电话" prop="customer.phone">
          <el-input class="customer-form-input" v-model="customerForm.customer.phone" :maxlength="11"></el-input>
        </el-form-item>
        <el-form-item label="公司地址" prop="customer.address">
          <el-input class="customer-form-input" v-model="customerForm.customer.address" :maxlength="60"></el-input>
        </el-form-item>
        <!-- <el-form-item label="状态" prop="customer.status">
          <el-select v-model="customerForm.customer.status" placeholder="请选择">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-form-item> -->
        <el-form-item>
          <el-button type="primary" :loading="loading"  @click="onSubmit('customerForm')">保存</el-button>
          <el-button @click="historyBack()">取消</el-button>
        </el-form-item>
      </el-form>
    </div>
    <!-- <picture-panel :panelVisible.sync="panelVisible" @close="handleClose" :singleFlag="true"></picture-panel> -->
  </div>
</template>

<script>
import customer from 'service/customer'
import router from 'router'
import { Select, Option, Button, Input, Form, FormItem } from 'element-ui'

export default {
  name: 'addCustomer',
  data() {
    return {
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      customerForm: {
        customer: {
          name: '',
          phone: '',
          address: '',
          customer_name: '',
        },
      },
      customerID: '',
      rules: {
        "customer.phone": [
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
        "customer.name": [
          { message: '请输入公司名称', trigger: 'blur' , required: true}
        ],
        "customer.customer_name": [
          { message: '请输入联系人', trigger: 'blur' , required: true}
        ],
        "customer.address": [
          { message: '请输入公司地址', trigger: 'blur' , required: true}
        ],
      },
      loading: false
    }
  },
  created: function(){
    if(this.setting.loading == true){
      return false
    }
    this.customerID = this.$route.params.uid
    this.setting.loadingText = "拼命加载中"
    this.setting.loading = false
  },
  methods: {
    handlePreview(file) {
      console.log(file);
    },
    onSubmit(formName) {
      this.$refs[formName].validate((valid) => {
        if(valid){
          this.loading = true;
          console.log(this[formName])
          customer.saveCustomer(this, this[formName].customer).then(result => {
            this.loading = false;
            this.$message({
              message: "添加成功",
              type: "success"
            })
            // todo是否返回用户列表
            this.$router.push({
              path: "/customer/customers"
            })
          }).catch(error => {
            this.loading = false;
            console.log(error)
          })
        }else{
          console.log('error submit');
          return;
        }
      })
    },
    handleClose(data) {
      if (data && data.length > 0 && data[0].media_url) {
        console.dir(data)
        this.imageUrl = data[0].media_url
      } else {
        console.log('图片上传失败')
      }
    },
    handleOpenPane() {
      this.panelVisible = true
    },
    handleImageDelete() {
      this.imageUrl = ''
    },
    resetForm(formName) {
     
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
