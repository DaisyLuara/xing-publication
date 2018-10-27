<template>
  <div 
    class="account-form">
    <div 
      class="account-form__container">
      <div 
        class="account-form__header clearfix">
        <div 
          class="header-logo-wrap">
          <span 
            class="title-header">用户登录</span>
        </div>
      </div>
      <div 
        class="account-form__body">
        <el-form 
          ref="accountForm" 
          :model="accountForm" 
          :rules="rules" 
          label-position="top" 
          label-width="0px" 
          @submit.native.prevent >
          <el-form-item 
            :class="{'error': validateError.account,'active': itemFocus.account}" 
            prop="account" 
            class="account-form-item mobile" >
            <el-col 
              :xs="10" 
              :sm="8" 
              :md="8" 
              :lg="8">
              <div 
                class="account-form-item-label">
                <span 
                  class="lable-text">手机号码</span>
                <span 
                  class="lable-prefix">+86</span>
              </div>
            </el-col>
            <el-col 
              :xs="14" 
              :sm="16" 
              :md="16" 
              :lg="16">
              <el-col 
                :xs="16" 
                :sm="16" 
                :md="16" 
                :lg="16">
                <el-input 
                  v-model="accountForm.account"
                  :maxlength="11" 
                  type="text"  
                  auto-complete="off"
                  placeholder="请输入手机号"/>
              </el-col>
              <el-col 
                :xs="8" 
                :sm="8" 
                :md="8" 
                :lg="8">
                <div 
                  class="btn-code-wrap" 
                  @click="phoneSuccessHandle()">
                  <span 
                    class="btn-code-label__countdown">点击验证</span>
                </div>
              </el-col>
            </el-col>
            <div 
              v-show="validateError.account"
              class="error-tip" 
            >{{ validateErrorText.account }}</div>
          </el-form-item>
          <el-form-item 
            v-if="showImageCaptcha"
            :class="{'error': validateError.imageCaptcha,'active': itemFocus.imageCaptcha}"
            prop="imageCaptcha.value" 
            class="account-form-item image-code" 
          >
            <el-col 
              :xs="4" 
              :sm="5" 
              :md="5" 
              :lg="5">
              <div 
                class="account-form-item-label">
                <span 
                  class="lable-text">验证码</span>
              </div>
            </el-col>
            <el-col 
              :xs="20" 
              :sm="19" 
              :md="19" 
              :lg="19">
              <el-col 
                :xs="12" 
                :sm="13" 
                :md="13" 
                :lg="13">
                <el-input 
                  v-model="accountForm.imageCaptcha.value" 
                  :maxlength="5" 
                  type="text" 
                  placeholder="请输入验证码"
                  auto-complete="off"
                  @focus="itemFocus.imageCaptcha=true;validateError.imageCaptcha=false" 
                  @change="getSmsCaptcha" 
                  @blur="itemFocus.imageCaptcha=false"/>
              </el-col>
              <el-col 
                :xs="12" 
                :sm="11" 
                :md="11" 
                :lg="11">
                <div 
                  class="image-code-wrap">
                  <img 
                    :src="setting.imageCaptcha.image_url" 
                    class="image-code" 
                    alt="验证码图片"
                    @click="getImageCaptcha()" >
                </div>
              </el-col>
            </el-col>
            <div 
              v-show="validateError.imageCaptcha"
              class="error-tip">{{ validateErrorText.imageCaptcha }}</div>
          </el-form-item>
          <el-form-item 
            v-if="showSmsCaptcha"
            :class="{'error': validateError.smsCaptcha,'active': itemFocus.smsCaptcha}"
            prop="smsCaptcha"
            class="account-form-item sms-code">
            <el-col 
              :xs="7" 
              :sm="7" 
              :md="7" 
              :lg="7">
              <div 
                class="account-form-item-label">
                <span 
                  class="lable-text">短信验证码</span>
              </div>
            </el-col>
            <el-col 
              :xs="17" 
              :sm="17" 
              :md="17" 
              :lg="17">
              <el-col 
                :xs="15" 
                :sm="14" 
                :md="14" 
                :lg="14">
                <el-input 
                  v-model="accountForm.smsCaptcha" 
                  :maxlength="5" 
                  type="text" 
                  auto-complete="off" 
                  placeholder="请输入验证码"
                  @focus="itemFocus.smsCaptcha=true;validateError.smsCaptcha=false" 
                  @blur="itemFocus.smsCaptcha=false"/>
              </el-col>
              <el-col
                :xs="9" 
                :sm="10" 
                :md="10" 
                :lg="10">
                <div 
                  class="sms-code-wrap">
                  <span 
                    v-show="!setting.sendingSmsCaptcha"
                    class="sms-code-label" 
                    @click="sendSmsCaptcha()" >{{ setting.smsCaptchaText }}</span>
                  <span 
                    v-show="setting.sendingSmsCaptcha"
                    class="sms-code-label__countdown" >重新获取({{ setting.sendingSmsCaptchaTimer }}s)</span>
                </div>
              </el-col>
            </el-col>
            <div 
              v-show="validateError.smsCaptcha"
              class="error-tip" >{{ validateErrorText.smsCaptcha }}</div>
          </el-form-item>
          <el-form-item  
            :class="{'error': validateError.password,'active': itemFocus.password}"
            prop="password" 
            class="account-form-item password">
            <el-col 
              :xs="6" 
              :sm="5"
              :md="5"
              :lg="5">
              <div 
                class="account-form-item-label password">
                <div 
                  class="lable-text">登录密码</div>
              </div>
            </el-col>
            <el-col 
              :xs="18" 
              :sm="19" 
              :md="19" 
              :lg="19">
              <el-col 
                :xs="20" 
                :sm="21" 
                :md="21" 
                :lg="21">
                <el-input 
                  v-model="accountForm.password"
                  :type="setting.showPassword ? 'text' : 'password'" 
                  :maxlength="20" 
                  auto-complete="off"
                  placeholder="请输入密码"
                  @keyup.enter.native="onSubmit(type)" 
                  @focus="itemFocus.password=true;validateError.password=false" 
                  @blur="itemFocus.password=false" />
              </el-col>
              <el-col 
                :xs="4" 
                :sm="3" 
                :md="3" 
                :lg="3">
                <div 
                  class="switch-show-off-password">
                  <img 
                    v-show="setting.showPassword"
                    src="../../assets/images/icon_show_pwd.png" 
                    @click="setting.showPassword = !setting.showPassword" >
                  <img 
                    v-show="!setting.showPassword"
                    src="../../assets/images/icon_hide_pwd.png"
                    @click="setting.showPassword = !setting.showPassword" >
                </div>
              </el-col>
            </el-col>
            <div 
              v-show="validateError.password"
              class="error-tip" >{{ validateErrorText.password }}</div>
          </el-form-item>
          <el-form-item 
            style="width:100%;" 
            class="account-form-submit">
            <el-button 
              :loading="setting.submiting"
              class="btn-login" 
              @click="onSubmit(type)" 
            >登录</el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
    <div class="icp">
      <a target="_blank" href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=31011502008032" style="display:inline-block;text-decoration:none;height:20px;line-height:20px;">
        <p style="height:20px;line-height:20px;margin: 0px 0px 0px 5px; color:#fff;">
          Copyright©2018 星视度 版权所有 <img src="~assets/images/reference.png"/> 沪公网安备 31011502008032号
        </p>
      </a>
      <a target="_blank" href="http://www.miitbeian.gov.cn" class="m-contact-text" style="display:inline-block;text-decoration:none;height:20px;line-height:20px;">
        <p style="height:20px;line-height:20px;margin: 0px 0px 0px 5px; color:#fff;">
          沪ICP备 17045724号
        </p>
      </a>
		</div>
  </div>
</template>
<script>
import auth from 'service/auth'
import validate from '../../utils/validate.js'
import {
  Button,
  Input,
  Form,
  FormItem,
  Checkbox,
  CheckboxGroup,
  Col,
  Select,
  Option
} from 'element-ui'
import axios from 'axios'
export default {
  name: 'AccountForm',
  components: {
    'el-col': Col,
    'el-button': Button,
    'el-input': Input,
    'el-form': Form,
    'el-form-item': FormItem,
    'el-checkbox': Checkbox,
    'el-checkbox-group': CheckboxGroup,
    'el-select': Select,
    'el-option': Option
  },
  props: {
    type: {
      type: String,
      required: true
    }
  },
  data() {
    let va = (rule, value, callback) => {
      console.log(value)
      let validateResult = validate.account(value)
      if (!validateResult.validate) {
        this.validateError.account = true
        this.validateErrorText.account = validateResult.errorText
        return false
      }

      this.validateError.account = false
      callback()
    }
    let vp = (rule, value, callback) => {
      let validateResult = validate.password(value)
      if (!validateResult.validate) {
        this.validateError.password = true
        this.validateErrorText.password = validateResult.errorText
        return false
      }

      this.validateError.password = false
      callback()
    }
    let vic = (rule, value, callback) => {
      let validateResult = validate.imageCaptcha(value)
      if (!validateResult.validate) {
        this.validateError.imageCaptcha = true
        this.validateErrorText.imageCaptcha = validateResult.errorText
        return false
      }
      this.validateError.imageCaptcha = false
      callback()
    }
    let vs = (rule, value, callback) => {
      let validateResult = validate.smsCaptcha(value)
      if (!validateResult.validate) {
        this.validateError.smsCaptcha = true
        this.validateErrorText.smsCaptcha = validateResult.errorText
        return false
      }

      this.validateError.smsCaptcha = false
      callback()
    }
    return {
      accountForm: {
        account: '',
        password: '',
        verification_key: '',
        verification_code: '',
        imageCaptcha: {
          key: '',
          value: ''
        },
        smsCaptcha: ''
      },
      showSmsCaptcha: false,
      setting: {
        remember: true,
        submiting: false,
        smsCaptchaText: '获取验证码',
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
        redirect_url: '/inform/list'
      },
      rules: {
        account: [{ validator: va, trigger: 'blur' }],
        password: [{ validator: vp, trigger: 'submit' }],
        'imageCaptcha.value': [{ validator: vic, trigger: 'blur' }],
        smsCaptcha: [{ validator: vs, trigger: 'submit' }]
      },
      showImageCaptcha: false,
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
  created() {},
  methods: {
    onSubmit(type) {
      this[type]()
    },
    login() {
      // todo 验证码一并发送给后台
      if (!this.setting.submiting) {
        this.$refs.accountForm.validate(valid => {
          if (valid) {
            let loginParams = {
              username: this.accountForm.account,
              password: this.accountForm.password,
              verification_key: this.accountForm.verification_key,
              verification_code: this.accountForm.smsCaptcha
            }
            auth.login(this, loginParams, this.setting.redirect_url)
          } else {
            return false
          }
        })
      }
    },
    phoneSuccessHandle() {
      if (!this.validateError.account && this.accountForm.account) {
        this.ImageCaptchaHandle()
      } else {
        this.showImageCaptcha = false
      }
    },
    getSmsCaptcha() {
      if (
        !this.validateError.imageCaptcha &
        (this.accountForm.imageCaptcha.value.length == 5)
      ) {
        this.showSmsCaptcha = true
        this.sendSmsCaptcha()
      }
    },
    ImageCaptchaHandle() {
      this.getImageCaptcha()
    },
    linkToLogin() {
      this.$router.push({
        path: '/login'
      })
    },
    resetForm(formName) {
      this.$refs[formName].resetFields()
    },
    getImageCaptcha() {
      let args = {
        phone: this.accountForm.account,
        type: 'login'
      }
      auth
        .getImageCaptcha(this, args)
        .then(result => {
          if (result) {
            let imageCaptchaObj = result
            this.accountForm.imageCaptcha.key = imageCaptchaObj.captcha_key
            this.setting.imageCaptcha.image_url =
              imageCaptchaObj.captcha_image_content
            this.showImageCaptcha = true
          } else {
            this.showImageCaptcha = false
          }
        })
        .catch(error => {
          console.log(error)
        })
    },

    sendSmsCaptcha() {
      // 校验手机号码、验证码
      let args = {
        captcha_key: this.accountForm.imageCaptcha.key,
        captcha_code: this.accountForm.imageCaptcha.value
      }
      auth
        .sendSmsCaptcha(this, args)
        .then(sendResult => {
          console.log(sendResult)
          this.accountForm.verification_key = sendResult.key
          let that = this
          // 改变发送验证码的文字，倒计时,开启语音验证码
          this.setting.sendingSmsCaptcha = true
          this.setting.smsCaptchaText = '重新获取'
          let smsIntervel = function() {
            // 倒计时结束： 改变发送验证码的文字为重新获取
            if (that.setting.sendingSmsCaptchaTimer == 0) {
              window.clearInterval('smsIntervel')
              that.setting.sendingSmsCaptchaTimer = 59
              that.setting.sendingSmsCaptcha = false
              return false
            }
            that.setting.sendingSmsCaptchaTimer--
            setTimeout(smsIntervel, 1000)
          }
          smsIntervel()
        })
        .catch(error => {
          this.getImageCaptcha()
          this.validateError.imageCaptcha = true
          this.validateErrorText.imageCaptcha = '输入验证码不正确'
          console.log(error)
        })
    }
  }
}
</script>
<style lang="less">
@import '../../assets/css/accountForm.less';
</style>
<style lang="less" scoped>
.account-form-wrap {
  .account-form-container {
    .account-form-header {
      .header-link {
        .register-label {
          color: #474747;
          font-size: 12px;
          .sub-label {
            color: #20a0ff;
            cursor: pointer;
          }
        }
      }
    }
    .account-form-body {
      .account-form-submit {
        margin-top: 32px;
      }
    }
  }
}
</style>

