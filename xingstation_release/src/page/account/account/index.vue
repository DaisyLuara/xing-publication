<template>
  <div class="root">
    <div class="account-wrap">
      <el-row :gutter="10" v-show="true">
        <el-col :xs="12" :sm="12" :md="12" :lg="12" :xl="12">
          <div class="item-info">
            <el-form ref="userForm" :model="userForm" :rules="rules" label-width="80px">
              <el-form-item label='头像照片'>
                <div class="up-area-cover">
                  <img v-if="imageUrl" :src="imageUrl" class="cover">
                  <i v-else class="el-icon-plus cover-uploader-icon" @click="handleOpenPane"></i>
                  <i v-if="imageUrl !== ''" class="el-icon-circle-cross delete-icon-image" @click="handleImageDelete()"></i>
                </div>
              </el-form-item>
              <el-form-item label="账号ID" prop="user.id">
                <el-input class="user-form-input" v-model="userForm.user.id" :disabled="true"></el-input>
              </el-form-item>
              <el-form-item label="关联者" prop="user.name">
                <el-input class="user-form-input" v-model="userForm.user.name" :maxlength="10"></el-input>
              </el-form-item>
              <el-form-item label="手机号码" prop="user.mobile">
                <el-input class="user-form-input" v-model="userForm.user.mobile" :maxlength="11" placeholder="初始密码发送到手机"></el-input>
              </el-form-item>
              <el-form-item label="身份证" prop="user.identity_card">
                <el-input class="user-form-input" v-model="userForm.user.identity_card" :maxlength="18"></el-input>
              </el-form-item>
              <el-form-item label="电子邮箱" prop="user.email">
                <el-input class="user-form-input" v-model="userForm.user.email"></el-input>
              </el-form-item>
              <el-form-item label="部门" prop="user.department">
                <el-input class="user-form-input" v-model="userForm.user.department" :disabled="true"></el-input>
              </el-form-item>
              <el-form-item label="职位" prop="user.position">
                <el-input class="user-form-input" v-model="userForm.user.position" :disabled="true"></el-input>
              </el-form-item>
              <el-form-item label="密码" prop="user.password">
                <el-input class="user-form-input" v-model="userForm.user.password"></el-input>
              </el-form-item>
              <el-form-item label="账户类型" prop="roles">
                <label v-for="role in allRoles" :key="role.id">
                  {{role.display_name}}
                </label>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" :loading="loading"  @click="onSubmit('userForm')">保存</el-button>
                <el-button @click="historyBack()">取消</el-button>
              </el-form-item>
            </el-form>
          </div>
        </el-col>
        <el-col :xs="12" :sm="12" :md="12" :lg="12" :xl="12">
          <div class="item-info">
            <el-form label-width="80px">
              <el-form-item label='LOGO'>
                <div class="company-logo-wrap">
                  <img :src="imageUrlLogo" class="company-logo">
                  <span class="download">下载</span>
                </div>
              </el-form-item>
              <el-form-item label="账号ID">
                <label>7836097</label>
              </el-form-item>
              <el-form-item label="公司全称">
                <label>上海星视度科技有限公司</label>
              </el-form-item>
              <el-form-item label="公司电话">
                <label>+ 86 21 88888888</label>
              </el-form-item>
              <el-form-item label="公司网站">
                <label>www.xingstation.com</label>
              </el-form-item>
              <el-form-item label="电子邮箱">
                <label>contact@xingstation.com</label>
              </el-form-item>
              <el-form-item label="公司地址">
                <label>上海市浦东新区浦东南路1118号10楼</label>
              </el-form-item>
              <el-form-item label="营业执照">
                <label class="to_view">查看</label>
              </el-form-item>
              <el-form-item label="发票信息">
                <label class="to_view">查看</label>
              </el-form-item>
            </el-form>
          </div>
        </el-col>
      </el-row>
      <el-form ref="userForm" :model="mainForm" :rules="rulesMain" label-width="80px" v-show="false">
        <el-form-item label='LOGO'>
          <div class="up-area-cover">
            <img v-if="mainForm.imageUrl" :src="mainForm.imageUrl" class="cover">
            <i v-else class="el-icon-plus cover-uploader-icon" @click="handleOpenPane"></i>
          </div>
        </el-form-item>
        <el-form-item label="账号ID" prop="user.id">
          <el-input class="user-form-input" v-model="mainForm.user.id" :disabled="true"></el-input>
        </el-form-item>
        <el-form-item label="公司全称" prop="user.name">
          <el-input class="user-form-input" v-model="mainForm.user.name"></el-input>
        </el-form-item>
        <el-form-item label="公司电话" prop="user.mobile">
          <el-input class="user-form-input" v-model="mainForm.user.mobile"></el-input>
        </el-form-item>
        <el-form-item label="公司网站" prop="user.web">
          <el-input class="user-form-input" v-model="mainForm.user.web"></el-input>
        </el-form-item>
        <el-form-item label="电子邮箱" prop="user.email">
          <el-input class="user-form-input" v-model="mainForm.user.email"></el-input>
        </el-form-item>
        <el-form-item label="公司地址" prop="user.address">
          <el-input class="user-form-input" v-model="mainForm.user.address"></el-input>
        </el-form-item>
        <el-form-item label="营业执照" prop="user.business">
          <div class="up-area-cover">
            <img v-if="mainForm.user.business" :src="mainForm.user.business" class="cover">
            <i v-else class="el-icon-plus cover-uploader-icon" @click="handleOpenPane"></i>
          </div>
        </el-form-item>
        <el-form-item label="发票信息" prop="user.info">
          <el-input class="user-form-input" v-model="mainForm.user.info"></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="user.password">
          <el-input class="user-form-input" v-model="mainForm.user.password"></el-input>
        </el-form-item>
        <div class="system-title">系统定制</div>
        <el-form-item label="登录界面LOGO" prop="user.logo">
          <div class="up-area-cover">
            <img v-if="mainForm.user.logo" :src="mainForm.user.logo" class="cover">
            <i v-else class="el-icon-plus cover-uploader-icon" @click="handleOpenPane"></i>
          </div>
        </el-form-item>
        <el-form-item label="缩略LOGO" prop="user.small_logo">
          <div class="up-area-cover">
            <img v-if="mainForm.user.small_logo" :src="mainForm.user.small_logo" class="cover">
            <i v-else class="el-icon-plus cover-uploader-icon" @click="handleOpenPane"></i>
          </div>
        </el-form-item>
        <el-form-item label="角标URL" prop="user.url">
          <el-input class="user-form-input" v-model="mainForm.user.url"></el-input>
        </el-form-item>
        <el-form-item label="标题信息" prop="user.titleInfo">
          <el-input class="user-form-input" v-model="mainForm.user.titleInfo"></el-input>
        </el-form-item>
        <el-form-item label="版权信息" prop="user.copyright">
          <el-input class="user-form-input" v-model="mainForm.user.copyright"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" :loading="loading"  @click="onSubmit('mainForm')">保存</el-button>
          <el-button @click="historyBack()">取消</el-button>
        </el-form-item>
      </el-form>
    </div>
    <picture-panel :panelVisible.sync="panelVisible" @close="handleClose" :singleFlag="true"></picture-panel>
  </div>
</template>

<script>
import user from 'service/user'
import store from 'service/store'
import router from 'router'
import picturePanel from 'components/common/picturePanel'
import {Row, Col, Button, Input, Form, FormItem, Checkbox, CheckboxGroup, RadioGroup, Radio} from 'element-ui'

export default {
  name: 'addUser',
  data() {
    return {
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      checked: false,
      userForm: {
        user: {
          id: '7836097',
          email: 'liufeng@xingstation.com',
          name: '刘峰',
          mobile: '13458900909',
          identity_card: '202123195612115532',
          department: '星视度',
          position: '产品经理',
          password: '12343434',
        },
        roles: []
      },
      mainForm: {
        imageUrl: '',
        user: {
          id: '7836097',
          email: 'contact@xingstation.com',
          name: '上海星视度科技有限公司',
          mobile: '+86 21 8888888',
          web: 'www.xingstation.com',
          address: '上海市浦东新区浦东南路1118号10楼',
          business: '',
          info: '',
          small_logo: '',
          url: '',
          titleInfo: '',
          copyright: '',
          logo: '',
          password: '12343434',
        },
      },
      rulesMain: {},
      panelVisible: false,
      userID: '',
      allStores: [],
      allRoles: [{
        display_name: "管理员", id: 1
      },
      {
        display_name: "审核发布", id: 5
      }],
      imageUrlLogo: 'http://sapi.xingstation.com/storage/2018/01/18/HvzTVmGedMvBcNBxYyzGTmaVsfK9GUqTH980oN9b.jpeg',
      imageUrl: '',
      rules: {
        "user.mobile": [
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
        "user.name": [
          { message: '请输入姓名', trigger: 'blur' }
        ],
        "user.password": [
          { validator: (rule, value, callback) => {
            if (/^\s*$/.test(value)) {
              callback('请输入密码')
            } else if(!/^.{8,20}$/.test(value)) {
              callback('密码长度不正确,请重新输入')
            } else {
              callback()
            }
          }, trigger: 'blur' }
        ],
        // "roles": [
        //   { message: '请选择角色', trigger: 'change', type: 'array' }
        // ]
      },
      loading: false
    }
  },
  created: function(){
    if(this.setting.loading == true){
      return false
    }
    this.userID = this.$route.params.uid
    this.setting.loadingText = "拼命加载中"
    this.setting.loading = false
  },
  methods: {
    onSubmit(formName) {
      this.$refs[formName].validate((valid) => {
        if(valid){
          this.loading = true;
          user.saveUser(this, this[formName], this.userID).then(result => {
            this.loading = false;
            this.$message({
              message: this.userID ? "修改成功" : "添加成功",
              type: "success"
            })
            // todo是否返回用户列表
            this.$router.push({
              path: "/system/user/"
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
      this.$refs[formName].resetFields();
      this.userForm.user.name = this.userFormBack.user.name;
      this.userForm.user.nick_name = this.userFormBack.user.nick_name;
      this.userForm.roles = [];
      for(let i = 0, uL = this.userFormBack.roles.length; i < uL; i++){
        this.userForm.roles.push(this.userFormBack.roles[i])
      }
    },
    historyBack () {
      router.back()
    }
  },
  components: {
    "el-button": Button,
    "el-input": Input,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-row": Row,
    "el-col": Col,
    picturePanel,
  }
}
</script>
<style scoped lang="less">
  .root {
    padding: 10px;
    font-size: 14px;
    color: #5E6D82;
    .account-wrap{
      background: #fff;
      padding: 30px;
      .user-form-input{
        width: 385px;
      }
      .system-title{
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
      }
      .item-info{
        .to_view{
          color: #074fc5;
        }
        label{
          margin-left: 15px;
        }
      }
      .company-logo-wrap{
        width: 180px;
        height: 180px;
        margin-left: 15px;
        .company-logo{
          display: inline-block;
          width: 50%;
        }
        .download{
          color: #074fc5;
        }
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
      .user-title{
        margin-bottom: 20px;
      }
      .el-checkbox{
        margin-left: 0px;
        margin-right: 15px;
      }
  }
}
</style>

