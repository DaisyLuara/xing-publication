<template>
  <div class="root">
    <div class="item-content-title">
      <div class="total-wrap">
        <span class="label">创建优惠券</span>
      </div>
    </div>
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="item-list-wrap"
    >
      <!-- 基本信息-->
      <div class="item-content-right">
        <div class="item-content-right-top">
          <div class="item-content-wrap">
            <div class="total-wrap">
              <span class="label">使用设置</span>
            </div>
          </div>
          <hr />
          <el-form
            ref="couponForm"
            :model="couponForm"
            label-width="180px"
          >
            <el-form-item
              label="库存"
              prop="discount"
            >
              <el-input
                v-model="sku.quantity"
                class="coupon-form-input"
                :disabled="disabled"
              />
              <span>份</span>
              <div
                class="errMessage"
                v-show="submitCheck.inventory"
              >库存只能是大于0的数字</div>
            </el-form-item>
            <el-form-item>
              <el-checkbox
                v-model="can_share"
                label="用户可以分享领券链接"
              ></el-checkbox>
              <el-checkbox
                v-model="can_give_friend"
                label="用户领券后可转赠其他好友"
              ></el-checkbox>
            </el-form-item>
            <el-form-item
              label="核销方式"
              prop="is_fixed_date"
            >
              <div class="box-segmentation">
                <el-checkbox
                  v-model="couponForm.verification"
                  label="扫码核销"
                ></el-checkbox>
              </div>

              <div
                class="box-segmentation-b"
                v-show="verificationList"
              >
                <el-radio-group v-model="code_type">
                  <el-radio :label="code_types.QRCODE">二维码</el-radio>
                  <el-radio :label="code_types.BARCODE">条形码</el-radio>
                  <el-radio :label="code_types.TEXT">仅卡券号</el-radio>
                </el-radio-group>
              </div>
            </el-form-item>
          </el-form>
        </div>
        <div class="item-content-right-bt">
          <div class="item-content-wrap">
            <div class="total-wrap">
              <span class="label">门店信息</span>
            </div>
          </div>
          <hr />
          <el-form
            ref="couponForm"
            :model="couponForm"
            label-width="180px"
          >
            <el-form-item
              label="适用门店"
              prop="is_fixed_date"
            >

              <div class="box-segmentation">
                <el-checkbox
                  v-model="use_all_locations"
                  label="全部门店通用"
                ></el-checkbox>
              </div>

            </el-form-item>
            <el-form-item
              label="操作提示"
              prop="discount"
            >
              <el-input
                v-model="notice"
                :maxlength="16"
                class="coupon-form-input"
              />
              <div
                class="errMessage"
                v-show="submitCheck.operatingHints"
              >操作提示不能为空且长度不超过16个汉字或32个英文字母</div>
              <div class="message">建议引导用户到店出示卡券，由店员完成核销操作</div>
            </el-form-item>
          </el-form>
          <div class="item-button">
            <el-button
              plain
              size="medium"
              @click="goBack()"
            >上一步</el-button>
            <el-button
              type="success"
              size="medium"
              @click.stop="submit()"
            >提交审核</el-button>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import {
  addSingleCard,
  modifySingleCard
} from "service";

import {
  Button,
  Input,
  Form,
  FormItem,
  RadioGroup,
  Radio,
  DatePicker,
  Select,
  Option,
  Tooltip,
  Upload,
  Checkbox,
  CheckboxGroup
} from "element-ui";

export default {
  components: {
    "el-button": Button,
    "el-input": Input,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-radio-group": RadioGroup,
    "el-radio": Radio,
    "el-select": Select,
    "el-date-picker": DatePicker,
    "el-option": Option,
    "el-tooltip": Tooltip,
    "el-upload": Upload,
    "el-checkbox": Checkbox,
    "el-checkbox-group": CheckboxGroup,
  },
  data() {
    return {
      sku: {
        "quantity": null
      },
      disabled: false,
      can_share: false,
      can_give_friend: false,
      data: null,
      notice: null,
      card_type: this.$route.params.card_type,
      card_id: this.$route.params.card_id,
      verificationList: false,
      code_types: { QRCODE: 'CODE_TYPE_QRCODE', BARCODE: 'CODE_TYPE_BARCODE', TEXT: 'CODE_TYPE_TEXT' },
      code_type: null,
      use_all_locations: false,
      type: '',
      card_types: {
        GROUPON: {
          "card_type": "GROUPON",
          "groupon": {
          }
        }, CASH: {
          "card_type": "CASH",
          "cash": {
          }
        }, GIFT: {
          "card_type": "GIFT",
          "gift": {
          }
        }, DISCOUNT: {
          "card_type": "DISCOUNT",
          "discount": {
          }
        }, GENERAL_COUPON: {
          "card_type": "GENERAL_COUPON",
          "general_coupon": {
          }
        }      },
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      submitCheck: {
        inventory: false,
        operatingHints: false
      },
      couponForm: {
        verification: "",
        use_all_locations: "",
        code: "",
        description: "",
        color: "",
        discount: "",
        time: "",


      },
    };
  },
  watch: {
    verification(val, oldVal) {
      this.verificationList = val
      if (!val) {
        this.code_type = null
      }
    },

  },
  computed: {
    verification() {
      return this.couponForm.verification;
    },
  },
  created() {
  },
  mounted() {
    console.log('..................')
    console.log(this.$route.params.card)
    console.log(this.$route.params.card_id)
    console.log('..................>')
    if (this.$route.params.card !== undefined) {
      this.data = this.$route.params.card
      //更新新增判断过滤
      if (this.$route.params.card_id !== undefined) {
        //初始化数据
        this.useInit()
      }
    }
  },
  methods: {
    //新增
    addSingleCard(args) {
      addSingleCard(this, args)
        .then(res => {
          console.log(res)
          if (res.errcode === 0) {
            this.$router.push({
              path: '/project/wx_cardpackage/index/'
            })
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    //修改
    modifySingleCard(args) {
      modifySingleCard(this, args)
        .then(res => {
          console.log(res)
          if (res.errcode === 0) {
            this.$router.push({
              path: '/project/wx_cardpackage/index/'
            })
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    goBack() {
      console.log("提交券类型")
      this.$router.push({
        path: "/project/wx_cardpackage/add/"
      });
    },
    submit() {
      if (!this.validate()) {
        return
      }
      console.log("---------------")
      console.log(this.data)
      if (null === this.data) {
        this.$router.go(-1);
        return
      }
      //团购券
      if (this.card_type === 'GROUPON') {
        this.dataHandle('groupon')
      }
      //代金券
      else if (this.card_type === 'CASH') {
        this.dataHandle('cash')
      }
      //折扣券
      else if (this.card_type === 'DISCOUNT') {
        this.dataHandle('discount')
      }
      //兑换券
      else if (this.card_type === 'GIFT') {
        this.dataHandle('gift')
      }
      //优惠券
      else {
        this.dataHandle('general_coupon')
      }
      console.log('..............')
      console.log(this.data)
      if (this.card_id !== undefined) {
        this.update()
      } else {
        this.save()
      }
    },
    //新增
    save() {
      let card = { card: this.data }
      this.addSingleCard(card);
    },
    //修改
    update() {
      let card = this.data
      let query = {
        authorizer_id: 6,
        card_id: this.card_id,
        card: card
      }
      console.log('更新')
      console.log(card)
      this.modifySingleCard(query)
    },
    dataHandle(type) {
      this.type = type
      this.data[type].base_info.sku = this.sku
      this.data[type].base_info.can_share = this.can_share
      this.data[type].base_info.can_give_friend = this.can_give_friend
      this.data[type].base_info.notice = this.notice
      this.data[type].base_info.code_type = this.code_type
      this.data[type].base_info.use_all_locations = this.use_all_locations
    },
    useInit() {
      //团购券
      if (this.card_type === 'GROUPON') {
        this.dataHandleInit('groupon')
      }
      //代金券
      else if (this.card_type === 'CASH') {
        this.dataHandleInit('cash')
      }
      //折扣券
      else if (this.card_type === 'DISCOUNT') {
        this.dataHandleInit('discount')
      }
      //兑换券
      else if (this.card_type === 'GIFT') {
        this.dataHandleInit('gift')
      }
      //优惠券
      else {
        this.dataHandleInit('general_coupon')
      }
    },
    dataHandleInit(type) {
      this.sku = this.data[type].base_info.sku
      this.can_share = this.data[type].base_info.can_share
      this.can_give_friend = this.data[type].base_info.can_give_friend
      this.notice = this.data[type].base_info.notice
      this.code_type = this.data[type].base_info.code_type
      this.use_all_locations = this.data[type].base_info.use_all_locations
      if (this.code_type === this.code_types.QRCODE ||
        this.code_type === this.code_types.BARCODE ||
        this.code_type === this.code_types.TEXT) {
        this.verificationList = true
        this.couponForm.verification = true
      }
      this.disabled = true
    },
    //
    characterLength(s) {
      var len = 0;
      for (var i = 0; i < s.length; i++) {
        if (s.charCodeAt(i) > 127 || s.charCodeAt(i) == 94) {
          len += 2;
        } else {
          len++;
        }
      }
      return len;
    },
    //校验
    validate() {
      this.submitCheck.inventory = false
      this.submitCheck.operatingHints = false
      let flag = true
      let reg = /^[0-9]*$/
      //库存校验
      if (!reg.test(this.sku.quantity) || this.sku.quantity <= 0 || this.sku.quantity === '' || this.sku.quantity === null) {
        this.submitCheck.inventory = true
        flag = false
      }
      //操作提示
      if (this.notice === null || this.notice === '' || this.characterLength(this.notice) > 32) {
        this.submitCheck.operatingHints = true
        flag = false
      }
      return flag
    },
  }
};
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;
  .item-content-title {
    padding: 20px;
    background: #fff;
    .total-wrap {
      margin-top: 5px;
      margin-left: 20px;
      margin-bottom: 10px;
      .label {
        font-size: 20px;
        margin: 5px 0;
      }
    }
  }
  .item-list-wrap {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    font-size: 16px;
    background: #fff;
    padding: 0 50px 30px 50px;
    .item-content-right {
      width: 1000px;
      text-align: left;
      padding: 20px 10px;
      // margin: 0 auto;
      border: 1px solid #e7e7eb;
      background: #f4f5f9;
      .coupon-form-input {
        width: 200px;
      }
      .coupon-form {
        width: 350px;
        margin: 10px 0;
      }
      .next-step {
        margin: 0 300px;
      }
      .message {
        font-size: 12px;
        color: #8d8d8d;
      }
      .errMessage {
        color: #e15f63;
        font-size: 12px;
      }
      .message-box {
        margin-left: 88px;
      }
      .box-segmentation {
        margin: 13px;
        margin-top: 0;
      }
      .box-segmentation-b {
        margin-left: 45px;
      }
      .select {
        margin: 0 10px;
      }
      .picker {
        margin-left: 15px;
      }
      .article-item-t {
        width: 350px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
      }
      .article-item-b {
        width: 200px;
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        align-items: center;
      }
      .detail {
        width: 350px;
        height: 60px;
        border: 1px dashed #8d8d8d;
        position: relative;
        margin: 10px 0;
        .uploadIcon {
          width: 30px;
          position: absolute;
          left: 50%;
          top: 50%;
          transform: translate(-50%, -50%);
        }
      }
      .item-button {
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        //align-items: center;
        margin: 50px 100px;
        .el-button {
          margin: 0 20px;
        }
      }
      .item-content-wrap {
        padding: 0 20px;
        .el-form-item__label {
          width: 50px;
          text-align: left;
        }
        .total-wrap {
          margin-top: 5px;
          margin-bottom: 10px;
          .label {
            font-size: 14px;
            margin: 5px 0;
          }
        }
      }
      .icon {
        width: 60px;
        border-radius: 50%;
        margin: 0 30px;
      }
    }
  }
}
</style>
