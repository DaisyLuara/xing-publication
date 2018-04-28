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
        <el-form-item label='LOGO'>
          <div class="up-area-cover">
            <img v-if="imageUrl" :src="imageUrl" class="cover">
            <i v-else class="el-icon-plus cover-uploader-icon" @click="handleOpenPane"></i>
            <i v-if="imageUrl !== ''" class="el-icon-circle-cross delete-icon-image" @click="handleImageDelete()"></i>
          </div>
        </el-form-item>
        <el-form-item label="公司全称" prop="customer.company_name">
          <el-input class="customer-form-input" v-model="customerForm.customer.company_name" :maxlength="10"></el-input>
        </el-form-item>
        <el-form-item label="公司网站" prop="customer.company_web">
          <el-input class="customer-form-input" v-model="customerForm.customer.company_web" :maxlength="11"></el-input>
        </el-form-item>
        <el-form-item label="联系人" prop="customer.contact">
          <el-input class="customer-form-input" v-model="customerForm.customer.contact" :maxlength="18"></el-input>
        </el-form-item>
        <el-form-item label="联系人电话" prop="customer.contact_number">
          <el-input class="customer-form-input" v-model="customerForm.customer.contact_number"></el-input>
        </el-form-item>
        <el-form-item label="公司地址" prop="customer.company_address">
          <el-input class="customer-form-input" v-model="customerForm.customer.company_address"></el-input>
        </el-form-item>
        <el-form-item label="电子邮箱" prop="customer.email">
          <el-input class="customer-form-input" v-model="customerForm.customer.email"></el-input>
        </el-form-item>
        <el-form-item label="行业大类" prop="customer.industry_big">
          <el-select v-model="customerForm.customer.industry_big" placeholder="请选择" >
            <el-option
              v-for="item in industry_big"
              :key="item.value"
              :label="item.label"
              :value="item.value" >
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="行业小类" prop="customer.industry_samll">
          <el-select v-model="customerForm.customer.industry_samll" placeholder="请选择" >
            <el-option
              v-for="item in industry_samll"
              :key="item.value"
              :label="item.label"
              :value="item.value" >
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label='上传资质'>
          <div class="up-area-cover">
            <img v-if="imageUrl" :src="imageUrl" class="cover">
            <i v-else class="el-icon-plus cover-uploader-icon" @click="handleOpenPane"></i>
            <i v-if="imageUrl !== ''" class="el-icon-circle-cross delete-icon-image" @click="handleImageDelete()"></i>
          </div>
          <div>jpeg/png,小于5MB(营业执照或同等文件，其他必要材料)</div>
        </el-form-item>
        <el-form-item label="状态" prop="customer.status">
          <el-select v-model="customerForm.customer.status" placeholder="请选择">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" :loading="loading"  @click="onSubmit('customerForm')">保存</el-button>
          <el-button @click="historyBack()">取消</el-button>
        </el-form-item>
      </el-form>
    </div>
    <picture-panel :panelVisible.sync="panelVisible" @close="handleClose" :singleFlag="true"></picture-panel>
  </div>
</template>

<script>
import picturePanel from 'components/common/picturePanel'
import router from 'router'
import {Upload, Select, Option, Button, Input, Form, FormItem } from 'element-ui'

export default {
  name: 'addUser',
  data() {
    return {
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      customerForm: {
        customer: {
          email:'',
          company_name: '',
          company_web: '',
          contact: '',
          contact_number: '',
          industry_big: '',
          company_address: '',
          industry_samll: '',
          qualification: '',
          status: ''
        },
        roles: []
      },
      fileList: [],
      industry_samll: [{
        value: '选项1',
        label: '待合作'
      }, {
        value: '选项2',
        label: '合作中'
      }, {
        value: '选项3',
        label: '已结束'
      }, {
        value: '选项4',
        label: '暂停'
      }],
      industry_big: [{
        value: '选项1',
        label: '待合作'
      }, {
        value: '选项2',
        label: '合作中'
      }, {
        value: '选项3',
        label: '已结束'
      }, {
        value: '选项4',
        label: '暂停'
      }],
      options: [{
        value: '选项1',
        label: '待合作'
      }, {
        value: '选项2',
        label: '合作中'
      }, {
        value: '选项3',
        label: '已结束'
      }, {
        value: '选项4',
        label: '暂停'
      }],
      panelVisible: false,
      customerID: '',
      imageUrl: '',
      rules: {
        "customer.contact_number": [
          { validator: (rule, value, callback) => {
            if (/^\s*$/.test(value)) {
              callback('请输入手机')
            } else if(!/^[0-9]{11}$/.test(value)) {
              callback('手机长度不正确,请重新输入')
            } else {
              callback()
            }
          }, trigger: 'blur' }
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
    "el-upload": Upload,
    picturePanel
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
