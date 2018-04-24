<template>
  <div class="add-user-wrap">
    <div class="topbar">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/system/user' }">用户管理</el-breadcrumb-item>
        <el-breadcrumb-item>{{userID ? '修改' : '添加'}}</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="user-title">
      {{$route.name}}
      </div>
      <el-form ref="userForm" :model="userForm" :rules="rules" label-width="80px">
        <el-form-item label='头像照片'>
          <div class="up-area-cover">
            <img v-if="imageUrl" :src="imageUrl" class="cover">
            <i v-else class="el-icon-plus cover-uploader-icon" @click="handleOpenPane"></i>
            <i v-if="imageUrl !== ''" class="el-icon-circle-cross delete-icon-image" @click="handleImageDelete()"></i>
          </div>
        </el-form-item>
        <el-form-item label="姓名" prop="user.name">
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
          <el-input class="user-form-input" v-model="userForm.user.department"></el-input>
        </el-form-item>
        <el-form-item label="职位" prop="user.position">
          <el-input class="user-form-input" v-model="userForm.user.position"></el-input>
        </el-form-item>
        <el-form-item label="角色" prop="roles">
          <el-radio-group v-model="userForm.roles">
            <el-radio v-for="role in allRoles" :data="role" :key="role.id" :label="role.id">{{role.display_name}}</el-radio>
          </el-radio-group>
          <el-checkbox v-model="checked">审核发布</el-checkbox>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" :loading="loading"  @click="onSubmit('userForm')">保存</el-button>
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
import { Button, Input, Form, FormItem, Checkbox, CheckboxGroup, RadioGroup, Radio} from 'element-ui'

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
          name: '',
          email: '',
          mobile: '',
          identity_card: '',
          department: '',
          position: '',
          optical_store_id: null
        },
        roles: []
      },
      panelVisible: false,
      // formReset
      userFormBack: {
        user: {
          name: '',
          mobile: '',
          identity_card: '',
          department: '',
          position: '',
          password: '',
          optical_store_id: null
        },
        roles: []
      },
      userID: '',
      allStores: [],
      allRoles: [{
        display_name: "管理员", id: 1
      },{
        display_name: "操作者", id: 2
      },
      {
        display_name: "观察者", id: 3
      },
      {
        display_name: "第三方", id: 4
      }],
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
    // 获取当前用户可分配的角色
    // let rolesPromise = user.getManageableRoles(this).then(result => {
    //   this.allRoles = result.data
    //   console.log(this.allRoles)
    // }).catch(error => {
    //   console.log(error)
    // })
    // // 获取当前用户可分配的门店
    // let storePromise = store.getStoreList(this).then(result => {
    //   this.allStores = result.data
    //   this.allStores.unshift({
    //     id: null,
    //     name: '请选择'
    //   })
    // }).catch(error => {
    //   console.log(error)
    // })

    // Promise.all([rolesPromise, storePromise]).then(() => {
    //   if(this.userID){
    //     user.getUserInfoByUid(this, this.userID).then(result => {
    //       let userInfo = result;
    //       this.userForm.user.name = userInfo.name;
    //       this.userFormBack.user.name = userInfo.name;
    //       this.userForm.user.nick_name = userInfo.nick_name;
    //       this.userFormBack.user.nick_name = userInfo.nick_name;
    //       this.userForm.user.mobile = userInfo.mobile
    //       this.userFormBack.user.mobile = userInfo.mobile
    //       this.userForm.user.optical_store_id = userInfo.optical_store_id
    //       this.userFormBack.user.optical_store_id = userInfo.optical_store_id
    //       for(let i = 0, uL = userInfo.roles.length; i < uL; i++){
    //         this.userForm.roles.push(userInfo.roles[i].id)
    //         this.userFormBack.roles.push(userInfo.roles[i].id)
    //       }
    //       this.setting.loading = false
    //     }).catch(error => {
    //       console.log(error)
    //     })
    //   } else {
    //     this.setting.loading = false
    //   }
    // })
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
    "el-checkbox": Checkbox,
    "el-checkbox-group": CheckboxGroup,
    picturePanel,
    "el-radio-group": RadioGroup,
    "el-radio": Radio
  }
}
</script>
<style scoped lang="less">
  .add-user-wrap{
    .user-form-input{
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
    .user-title{
      margin-bottom: 20px;
    }
    .el-checkbox{
      margin-left: 0px;
      margin-right: 15px;
    }
  }
</style>
