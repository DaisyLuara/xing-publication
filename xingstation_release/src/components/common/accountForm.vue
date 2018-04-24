<template>
  <div class='account-form'>
    <div class="account-form__container">
      <div class="account-form__header clearfix">
        <div class="header-logo-wrap">
          <!--<img class="icon-header" src="../../assets/images/login_logo.png" alt="">-->
          <span class="title-header">{{headerTitle}}</span>
        </div>
        <div class="header-link">
          <div v-if="type == 'register'" @click="linkToLogin" class="register-label">
            <span class="m-label">已有账号？</span>
            <span class="sub-label">去登录</span>
          </div>
          <!-- <div v-if="type == 'login'" @click="linkToRegister" class="register-label">
            免费注册
          </div> -->
        </div>
      </div>
      <div class="account-form__body">
        <el-form @submit.native.prevent :model="accountForm" :rules="rules" ref="accountForm" label-position="top" label-width="0px" >
          <el-form-item prop="account" v-if="showItem('account')" class="account-form-item mobile" :class="{'error': validateError.account,'active': itemFocus.account}">
            <el-col :xs="10" :sm="9" :md="9" :lg="9">
              <div class="account-form-item-label">
                <!-- <span class="lable-text">手机号码</span>
                <span class="lable-prefix">+86</span> -->
                <el-select v-model="accountValue" placeholder="账号">
                  <el-option
                    v-for="item in accountOptions"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                  </el-option>
                </el-select>
              </div>
            </el-col>
            <el-col :xs="13" :sm="15" :md="15" :lg="15">
              <el-input type="text" :maxlength="11" @focus="itemFocus.account=true;validateError.account=false" @blur="itemFocus.account=false" v-model="accountForm.account" auto-complete="off" placeholder="请输入手机号"></el-input>
            </el-col>
            <div class="error-tip" v-show="validateError.account">{{validateErrorText.account}}</div>
          </el-form-item>
          <el-form-item prop="imageCaptcha.value" v-if="showImageCaptcha" class="account-form-item image-code" :class="{'error': validateError.imageCaptcha,'active': itemFocus.imageCaptcha}">
            <el-col :xs="4" :sm="5" :md="5" :lg="5">
              <div class="account-form-item-label">
                <span class="lable-text">验证码</span>
              </div>
            </el-col>
            <el-col :xs="20" :sm="19" :md="19" :lg="19">
              <el-col :xs="12" :sm="13" :md="13" :lg="13">
                <el-input type="text" :maxlength="4" @focus="itemFocus.imageCaptcha=true;validateError.imageCaptcha=false" @blur="itemFocus.imageCaptcha=false" v-model="accountForm.imageCaptcha.value" auto-complete="off" placeholder="请输入验证码"></el-input>
              </el-col>
              <el-col :xs="12" :sm="11" :md="11" :lg="11">
                <div class="image-code-wrap">
                  <img class="image-code" :src="setting.imageCaptcha.image_url" @click="getImageCaptcha()" alt="验证码图片">
                  <img class="check-image-code" v-show="checkImageCaptcha" src="../../assets/images/icon_check_image_code.png">
                </div>
              </el-col>
            </el-col>
            <div class="error-tip" v-show="validateError.imageCaptcha">{{validateErrorText.imageCaptcha}}</div>
          </el-form-item>
          <el-form-item prop="smsCaptcha" v-if="showItem('smsCaptcha')" class="account-form-item sms-code" :class="{'error': validateError.smsCaptcha,'active': itemFocus.smsCaptcha}">
            <el-col :xs="7" :sm="5" :md="5" :lg="5">
              <div class="account-form-item-label">
                <span class="lable-text">短信验证码</span>
              </div>
            </el-col>
            <el-col :xs="17" :sm="19" :md="19" :lg="19">
              <el-col :xs="15" :sm="14" :md="14" :lg="14">
                <el-input type="text" :maxlength="4" @focus="itemFocus.smsCaptcha=true;validateError.smsCaptcha=false" @blur="itemFocus.smsCaptcha=false" v-model="accountForm.smsCaptcha" auto-complete="off" placeholder="请输入验证码"></el-input>
              </el-col>
              <el-col :xs="9" :sm="10" :md="10" :lg="10">
                <div class="sms-code-wrap">
                  <span class="sms-code-label" @click="sendSmsCaptcha()" v-show="!setting.sendingSmsCaptcha">{{ setting.smsCaptchaText }}</span>
                  <span class="sms-code-label__countdown" v-show="setting.sendingSmsCaptcha">重新获取({{setting.sendingSmsCaptchaTimer}}s)</span>
                </div>
              </el-col>
            </el-col>
            <div class="error-tip" v-show="validateError.smsCaptcha">{{validateErrorText.smsCaptcha}}</div>
            <div class="voice-tip" v-show="!validateError.smsCaptcha && setting.useVoiceCaptcha">
              <div v-if="!setting.sendingVoiceCaptcha">收不到短信？使用<span class="btn-voice-captcha" @click="sendVoiceCaptcha()">语音验证码</span></div>
              <div class="voice-wait-tip" v-if="setting.sendingVoiceCaptcha">
                <div class="voice-wait-tip__top">电话拨打中，请留意您的手机来电</div>
                <div class="voice-wait-tip__bottom">{{setting.sendingVoiceCaptchaTimer}}秒后可以重新拨打...</div>
              </div>
            </div>
          </el-form-item>
          <el-form-item prop="password" v-if="showItem('password')" class="account-form-item password" :class="{'error': validateError.password,'active': itemFocus.password}">
            <el-col :xs="6" :sm="5" :md="5" :lg="5">
              <div class="account-form-item-label password">
                <div class="lable-text">{{ type == 'login' ? '登录密码' : '设置密码' }}</div>
              </div>
            </el-col>
            <el-col :xs="18" :sm="19" :md="19" :lg="19">
              <el-col :xs="20" :sm="21" :md="21" :lg="21">
                <el-input @keyup.enter.native="onSubmit(type)" :type="setting.showPassword ? 'text' : 'password'" :maxlength="20" @focus="itemFocus.password=true;validateError.password=false" @blur="itemFocus.password=false" v-model="accountForm.password" auto-complete="off" placeholder="请输入密码"></el-input>
              </el-col>
              <el-col :xs="4" :sm="3" :md="3" :lg="3">
                <div class="switch-show-off-password">
                  <img src="../../assets/images/icon_show_pwd.png" @click="setting.showPassword = !setting.showPassword" v-show="setting.showPassword">
                  <img src="../../assets/images/icon_hide_pwd.png" @click="setting.showPassword = !setting.showPassword" v-show="!setting.showPassword">
                </div>
              </el-col>
            </el-col>
            <div class="error-tip" v-show="validateError.password">{{validateErrorText.password}}</div>
          </el-form-item>
          <el-form-item style="width:100%;" class="account-form-submit">
            <el-button class="btn-login" @click="onSubmit(type)" :loading="setting.submiting">{{ submitTitle }}</el-button>
          </el-form-item>
        </el-form>
      </div>
      <div class="account-form__footer clearfix" v-if="type == 'login'">
        <div class="auto-login">
          <el-checkbox v-model="setting.remember">三天内自动登录</el-checkbox>
        </div>
        <!-- <div class="find-password" @click="linkToFindPassword">忘记密码</div> -->
      </div>
    </div>
    <div class="icp">
      Copyright©2018 星视度 版权所有   沪ICP备15015485号-1
    </div>
  </div>
</template>
<script>
import auth from 'service/auth'
import validate from '../../utils/validate.js'
import md5 from 'js-md5'
import { Button, Input, Form, FormItem, Checkbox, CheckboxGroup, Col, Select, Option } from 'element-ui'
import axios from 'axios'
export default {
  name: 'account-form',
  // props: ["type",'facility'],
  props: ["type"],
  data() {
    let va = (rule, value, callback) => {
      let validateResult = validate.account(value);
      if(!validateResult.validate){
        this.validateError.account = true;
        this.validateErrorText.account = validateResult.errorText;
        return false;
      }

      this.validateError.account = false;
      callback();
    }
    let vp = (rule, value, callback) => {
      let validateResult = validate.password(value);
      if(!validateResult.validate){
        this.validateError.password = true;
        this.validateErrorText.password = validateResult.errorText;
        return false;
      }

      this.validateError.password = false;
      callback();
    }
    let vic = (rule, value, callback) => {
      let validateResult = validate.imageCaptcha(value, this.setting.imageCaptcha.md5);
      if(!validateResult.validate){
        this.validateError.imageCaptcha = true;
        this.setting.checkImageCaptcha = false;
        this.validateErrorText.imageCaptcha = validateResult.errorText;
        return false;
      }
      this.setting.checkImageCaptcha = true;
      this.validateError.imageCaptcha = false;
      callback();
    }
    let vs= (rule, value, callback) => {
      let validateResult = validate.smsCaptcha(value);
      if(!validateResult.validate){
        this.validateError.smsCaptcha = true;
        this.validateErrorText.smsCaptcha = validateResult.errorText;
        return false;
      }

      this.validateError.smsCaptcha = false;
      callback();
    }
    return {
      accountValue: '',
      accountOptions: [{
        value: '1',
        label: '管理员'
      }, {
        value: '2',
        label: '其他'
      }],
      accountForm: {
        account: '',
        password: '',
        imageCaptcha: {
          key: '',
          value: ''
        },
        smsCaptcha: ''
      },
      setting: {
        remember: true,
        submiting: false,
        smsCaptchaText: "获取验证码",
        sendingSmsCaptcha: false, //发送验证码60s重置为false,
        sendingSmsCaptchaTimer: 60, //发送验证码计时器,
        sendingVoiceCaptcha: false,
        sendingVoiceCaptchaTimer: 60,
        loginFailedTimes: 0, // 尝试登陆失败次数
        showPassword: false,
        imageCaptcha: {
          md5: '',
          image_url: ''
        },
        redirect_url: this.$route.query.redirect_url
      },
      rules: {
        account: [
          { validator: va, trigger: 'submit' },
        ],
        password: [
          { validator: vp, trigger: 'submit'},
        ],
        'imageCaptcha.value': [
          { validator: vic, trigger: 'submit' },
        ],
        smsCaptcha: [
          { validator: vs, trigger: 'submit' },
        ]
      },
      validateError: {
        account: false,
        password: false,
        imageCaptcha: false,
        smsCaptcha: false
      },
      validateErrorText: {
        account: '',
        password: '',
        imageCaptcha: '',
        smsCaptcha: false
      },
      itemFocus: {
        password: false,
        account: false,
        imageCaptcha: false,
        smsCaptcha: false
      }
    }
  },
  created(){
    // 从localstorage中取 记住密码的配置

  },
  methods: {
    onSubmit(type) {
      this[type]();
    },
    login() {
      // todo 验证码一并发送给后台
      if(!this.setting.submiting){
        this.$refs.accountForm.validate((valid) => {
          if (valid) {
            let loginParams = {
              passport: this.accountForm.account,
              password: this.accountForm.password,
              remember_token: this.setting.remember
            }
            if(this.setting.loginFailedTimes >= 3){
              loginParams.captcha = {
                key: this.accountForm.imageCaptcha.key,
                value: this.accountForm.imageCaptcha.value
              }
            }
            console.log(loginParams)
            auth.login(this, loginParams, this.setting.redirect_url)
          } else {
            return false;
          }
        });
      }
    },
    register() {
      if(!this.setting.submiting){
        this.$refs.accountForm.validate((valid) => {
          if (valid) {
            let registerParams = {
              mobile: this.accountForm.account,
              password: this.accountForm.password,
              verifyCode: this.accountForm.smsCaptcha,
              captcha: {
                key: this.accountForm.imageCaptcha.key,
                value: this.accountForm.imageCaptcha.value
              }
            }
            auth.register(this, registerParams, this.setting.redirect_url)
          } else {
            return false
          }
        });
      }
    },
    findPassword() {
      if(!this.setting.submiting){
        this.$refs.accountForm.validate((valid) => {
          if (valid) {
            let params = {
              mobile: this.accountForm.account,
              password: this.accountForm.password,
              verifyCode: this.accountForm.smsCaptcha,
              captcha:{
                key: this.accountForm.imageCaptcha.key,
                value: this.accountForm.imageCaptcha.value
              }
            }
            auth.resetPassword(this, params)
          } else {
            return false;
          }
        });
      }
    },
    setNewPassword() {
      if(!this.setting.submiting){
        this.$refs.accountForm.validate((valid) => {
          if (valid) {
            let params = {
              mobile: this.$store.state.curUserInfo.mobile,
              old_password: this.$store.state.curUserInfo.old_password,
              new_password: this.accountForm.password,
            }
            auth.setFirstPassword(this, params)
          } else {
            return false;
          }
        });
      }
    },

    linkToLogin() {
      this.$router.push({
        path: '/login'
      })
    },

    linkToRegister() {
      this.$router.push({
        path: '/register'
      })
    },

    linkToFindPassword() {
      this.$router.push({
        path: '/findPassword'
      })
    },

    resetForm(formName) {
      this.$refs[formName].resetFields();
    },

    showItem(itemType) {
      switch (this.type)
      {
        case 'login':
          if(itemType == 'account'){
            return true;
          }
          if(itemType == 'password'){
            return true;
          }
          if(itemType == 'smsCaptcha'){
            return false;
          }
          break;
        case 'register':
          if(itemType == 'account'){
            return true;
          }
          if(itemType == 'password'){
            return true;
          }
          if(itemType == 'smsCaptcha'){
            return true;
          }
          break;
        case 'findPassword':
          if(itemType == 'account'){
            return true;
          }
          if(itemType == 'password'){
            return true;
          }
          if(itemType == 'smsCaptcha'){
            return true;
          }
          break;
        case 'setNewPassword':
          if(itemType == 'password'){
            return true;
          }
          break;
        default:
          return false;
          break;
      }
    },

    getImageCaptcha() {
      auth.getImageCaptcha(this).then(result => {
        let imageCaptchaObj = result.data;
        this.accountForm.imageCaptcha.key = imageCaptchaObj.key;
        this.setting.imageCaptcha.md5 = imageCaptchaObj.md5
        this.setting.imageCaptcha.image_url = auth.getImageCaptchaUrl(imageCaptchaObj.key);
      }).catch(error => {
        console.log(error)
      })
    },

    sendSmsCaptcha() {
      // 校验手机号码、验证码
      if(this.setting.sendingSmsCaptcha){
        return false;
      }
      let va = validate.account(this.accountForm.account);
      if(!va.validate){
        this.validateError.account = true;
        this.validateErrorText.account = va.errorText;
        return false;
      }

      let vic = validate.imageCaptcha(this.accountForm.imageCaptcha.value, this.setting.imageCaptcha.md5);
      if(!vic.validate){
        this.validateError.imageCaptcha = true;
        this.validateErrorText.imageCaptcha = vic.errorText;
        return false;
      }

      auth.sendSmsCaptcha(this, this.accountForm.account).then(sendResult => {
        if(sendResult){
          let that = this;
          // 改变发送验证码的文字，倒计时,开启语音验证码
          this.setting.sendingSmsCaptcha = true;
          this.setting.useVoiceCaptcha = true;
          this.setting.smsCaptchaText = "重新获取"
          let smsIntervel = function(){
          // 倒计时结束： 改变发送验证码的文字为重新获取
            if(that.setting.sendingSmsCaptchaTimer == 0){
              window.clearInterval('smsIntervel');
              that.setting.sendingSmsCaptchaTimer = 59;
              that.setting.sendingSmsCaptcha = false;
              return false;
            }
            that.setting.sendingSmsCaptchaTimer--;
            setTimeout(smsIntervel, 1000)
          }
          smsIntervel();
        }
        this.validate.smsCaptcha = false;
        this.validateErrorText.smsCaptcha = "获取验证码失败请重新获取"
      }).catch(error => {
          console.log(error)
      })
    },

    sendVoiceCaptcha() {
      if(this.sendingVoiceCaptcha){
        return false;
      }

      let va = validate.account(this.accountForm.account);
      if(!va.validate){
        this.validateError.account = true;
        this.validateErrorText.account = va.errorText;
        return false;
      }

      auth.sendVoiceCaptcha(this, this.accountForm.account).then(sendResult => {
        if(sendResult){
          let that = this;
          // 改变发送验证码的文字，倒计时
          this.setting.sendingVoiceCaptcha = true;

          let voiceInterval = function(){
            if(that.setting.sendingVoiceCaptchaTimer == 0){
          // 倒计时结束: 改变发送语音验证码的文字
              window.clearInterval('voiceInterval');
              that.setting.sendingVoiceCaptchaTimer = 59;
              that.setting.sendingVoiceCaptcha = false;
              return false;
            }
            setTimeout(voiceInterval, 1000);
            that.setting.sendingVoiceCaptchaTimer--;
          }
          voiceInterval();
        }
        this.validate.smsCaptcha = false;
        this.validateErrorText.smsCaptcha = "获取验证码失败请重新获取"
      }).catch(error => {
          console.log(error)
      })
    },
  },
  computed: {
    headerTitle(){
      switch(this.type) {
        case 'login': return '用户登录'; break;
        case 'register': return '商户免费注册'; break;
        case 'findPassword': return '找回密码'; break;
        case 'setNewPassword': return '设置新密码'; break;
        default: return '';
      }
    },
    submitTitle() {

      switch(this.type) {
        case 'login': return '登 录'; break;
        case 'register': return '确 认 注 册'; break;
        case 'findPassword': return '完 成 设 置'; break;
        case 'setNewPassword': return '完 成 设 置'; break;
        default: return '';
      }
    },
    showImageCaptcha() {
      if(this.type == 'login'){
        if(this.setting.loginFailedTimes >= 3) {
          this.getImageCaptcha();
          return true;
        }else{
          return false;
        }
      }

      if(this.type == 'register' || this.type == 'findPassword'){
        this.getImageCaptcha();
        return true;
      }
    },
    checkImageCaptcha() {
      if(md5(md5(this.accountForm.imageCaptcha.value.toLowerCase())) == this.setting.imageCaptcha.md5){
        return true;
      }else{
        return false;
      }
    }
  },
  components: {
    "el-col": Col,
    "el-button": Button,
    "el-input": Input,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-checkbox": Checkbox,
    "el-checkbox-group": CheckboxGroup,
    "el-select": Select,
    "el-option": Option
  }
}
</script>
<style lang="less">
@import '../../assets/css/accountForm.less';
</style>
<style lang="less" scoped>
.account-form-wrap{
  .account-form-container{
    .account-form-header{
      .header-link{
        .register-label{
          color: #474747;
          font-size: 12px;
          .sub-label{
            color: #20A0FF;
            cursor: pointer;
          }
        }
      }
    }
    .account-form-body {
      .account-form-submit{
        margin-top: 32px;
      }
    }
  }
}
</style>

