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
            :rules="rules"
            label-width="180px"
          >
            <el-form-item
              :rules="{required: true, message: '库存只能是大于0的数字', trigger: 'submit'}"
              label="库存"
              prop="discount"
            >
              <el-input
                v-model="couponForm.name"
                class="coupon-form-input"
              />
              <span>份</span>
            </el-form-item>
            <el-form-item>
              <el-checkbox-group v-model="checkList">
                <el-checkbox label="用户可以分享领券链接"></el-checkbox>
                <el-checkbox label="用户领券后可转赠其他好友"></el-checkbox>
              </el-checkbox-group>
            </el-form-item>
            <el-form-item
              label="核销方式"
              prop="is_fixed_date"
            >
              <el-radio-group v-model="couponForm.is_fixed_date">
                <div class="box-segmentation">
                  <el-radio :label="1">自助买单</el-radio>
                </div>
                <div class="box-segmentation">
                  <el-radio :label="2">自助核销</el-radio>
                </div>
                <div class="box-segmentation">
                  <el-radio :label="3">用扫码核销</el-radio>
                </div>

              </el-radio-group>
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
            :rules="rules"
            label-width="180px"
          >

            <el-form-item
              label="适用门店"
              prop="is_fixed_date"
            >
              <el-radio-group v-model="couponForm.is_fixed_date">
                <div class="box-segmentation">
                  <el-radio :label="4">全部门店适用</el-radio>
                </div>
                <div class="box-segmentation">
                  <el-radio :label="5">指定门店适用</el-radio>
                </div>
                <div class="box-segmentation">
                  <el-radio :label="6">无指定门店</el-radio>
                </div>
              </el-radio-group>
            </el-form-item>
            <el-form-item
              :rules="{required: true, message: '操作提示不能为空且长度不超过16个汉字或32个英文字母', trigger: 'submit'}"
              label="操作提示"
              prop="discount"
            >
              <el-input
                v-model="couponForm.name"
                class="coupon-form-input"
              />
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
              @click.stop="()=>{ show = false}"
            >提交审核</el-button>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import {
  getCouponList,
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
    var checkEndDate = (rule, value, callback) => {
      if (!value) {
        callback();
        return;
      }
      if (
        new Date(value.replace(/\-/g, "/")).getTime() <
        new Date(this.couponForm.start_date.replace(/\-/g, "/")).getTime()
      ) {
        callback(new Error("结束日期要大于开始日期"));
      } else {
        callback();
      }
    };
    var checkSortOrder = (rule, value, callback) => {
      if (!value) {
        callback();
        return;
      }
      if (/^[0-9]+.?[0-9]*$/.test(value)) {
        if (parseInt(value) < 1 || parseInt(value) > 100) {
          callback(new Error("优先级只能是1-100"));
        } else {
          callback();
        }
      } else {
        callback("优先级只能是数字");
      }
    };
    return {
      loading: true,
      title: '',
      radio: "1",
      show: false,
      checked: false,
      checkedShow: false,
      checkList: [],
      filters: {
        name: "",
        company_id: ""
      },
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      rules: {
        end_date: [{ validator: checkEndDate, trigger: "submit" }],
        sort_order: [{ validator: checkSortOrder, trigger: "submit" }]
      },
      couponForm: {
        is_fixed_date: "",
        description: "",
        color: "",
        discount: "",
        time: ""
      },
    };
  },
  created() {
  },
  methods: {
    goBack() {
      console.log("提交券类型")
      this.$router.push({
        path: "/project/wx_cardpackage/add/"
      });
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
      .message-box {
        margin-left: 88px;
      }
      .box-segmentation {
        margin: 0 20px 20px 20px;
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
