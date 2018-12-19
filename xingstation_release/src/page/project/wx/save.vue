<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="item-list-wrap"
    >
      <div class="item-content-right">
        <!-- 基本信息-->
        <div class="item-content-right-top">
          <div class="item-content-wrap">
            <div class="total-wrap">
              <span class="label">基本信息</span>
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
              label="商户"
              prop="description"
            >
              <img
                class="icon"
                src="https://cdn.exe666.com/fe/marketing/img/tiger/icon.png"
              >
            </el-form-item>
            <el-form-item
              label="卡券颜色"
              prop="color"
            >
              <el-select
                v-model="couponForm.company_id"
                placeholder="请选择颜色"
                class="coupon-form-select"
              >
                <el-option
                  v-for="item in colorList"
                  :key="item.id"
                  :label="item.color"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item
              :rules="{required: true, message: '优惠券名称不能为空', trigger: 'submit'}"
              label="折扣额度"
              prop="discount"
            >
              <el-input
                v-model="couponForm.name"
                class="coupon-form-input"
              />
              <span>折</span>
              <div class="message"> 请填写1-9.9之间的数字，精确到小数点后1位</div>
            </el-form-item>
            <el-form-item
              :rules="{required: true, message: '优惠券名称不能为空', trigger: 'submit'}"
              label="折扣券标题"
              prop="name"
            >
              <el-input
                v-model="couponForm.name"
                class="coupon-form-input"
                style="width:250px"
              />
              <div class="message"> 建议填写折扣券“折扣额度”及自定义内容，描述卡券提供的具体优惠</div>
            </el-form-item>
            <el-form-item
              label="有效期"
              prop="time"
            >
              <el-radio-group v-model="couponForm.is_fixed_date">
                <div class="box-segmentation">
                  <el-radio :label="1">固定时间
                    <el-date-picker
                      v-model="value13"
                      type="daterange"
                      start-placeholder="开始日期"
                      end-placeholder="结束日期"
                      :default-time="['00:00:00', '23:59:59']"
                      class="picker"
                    >
                    </el-date-picker>
                  </el-radio>
                </div>
                <div class="box-segmentation">
                  <el-radio :label="0">领取后，
                    <el-select
                      v-model="couponForm.company_id"
                      placeholder="请选择颜色"
                      class="coupon-form-select"
                    >
                      <el-option
                        v-for="item in colorList"
                        :key="item.id"
                        :label="item.color"
                        :value="item.id"
                      />
                    </el-select><span class="select">生效,有效天数</span>
                    <el-select
                      v-model="couponForm.company_id"
                      placeholder="请选择颜色"
                      class="coupon-form-select"
                    >
                      <el-option
                        v-for="item in colorList"
                        :key="item.id"
                        :label="item.color"
                        :value="item.id"
                      />
                    </el-select>

                  </el-radio>
                </div>

              </el-radio-group>
            </el-form-item>
            <el-form-item
              label="可用时段"
              prop="is_fixed_date"
            >
              <el-radio-group v-model="couponForm.is_fixed_date">
                <div class="box-segmentation">
                  <el-radio :label="1">全部时段</el-radio>
                </div>
                <div class="box-segmentation">
                  <el-radio :label="0">部分时段</el-radio>
                </div>

              </el-radio-group>
            </el-form-item>
          </el-form>
        </div>
        <!-- 优惠详情 -->
        <div class="item-content-right-bt">
          <div class="item-content-wrap">
            <div class="total-wrap">
              <span class="label">优惠详情</span>
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
              :rules="{required: true, message: '优惠券名称不能为空', trigger: 'submit'}"
              label="领券限制"
              prop="discount"
            >
              <el-input
                v-model="couponForm.name"
                class="coupon-form-input"
              />
              <span>张</span>
              <div class="message">每个用户领券上限，如不填，则默认为1</div>
            </el-form-item>
            <el-form-item
              :rules="{required: true, message: '优惠券名称不能为空', trigger: 'submit'}"
              label="使用条件"
              prop="discount"
            >
              <el-checkbox v-model="checked">适用范围　(至少填写一项)</el-checkbox>
              <div
                class="goods"
                v-show="checked"
              >
                <span>适用的商品</span>
                <el-input
                  v-model="couponForm.name"
                  class="coupon-form-input"
                />
                <div class="message-box">填写本券适用的商品、类目或服务</div>
                <span>不适用的商品</span>
                <el-input
                  v-model="couponForm.name"
                  class="coupon-form-input"
                />
                <div class="message-box">填写本券不适用的商品、类目或服务</div>

              </div>
              <div>
                <span>优惠共享</span>

                <el-select
                  v-model="couponForm.company_id"
                  placeholder="请选择颜色"
                  class="coupon-form-select"
                >
                  <el-option
                    v-for="item in colorList"
                    :key="item.id"
                    :label="item.color"
                    :value="item.id"
                  />
                </el-select>
              </div>

              <div class="message">使用条件的设置会在券上展示，请务必仔细确认。</div>
            </el-form-item>
            <el-form-item
              :rules="{required: true, message: '优惠券名称不能为空', trigger: 'submit'}"
              label="封面图片"
              prop="discount"
            >
              <div class="message">图片建议尺寸：850像素*350像素，大小不超过2M</div>
              <el-button
                type="success"
                size="medium"
                class="confirm"
                @click="upload()"
              >上传</el-button>
            </el-form-item>
            <el-form-item
              :rules="{required: true, message: '优惠券名称不能为空', trigger: 'submit'}"
              label="封面简介"
              prop="discount"
            >
              <el-input
                v-model="couponForm.name"
                placeholder="请输入封面图片的简介内容"
                class="coupon-form-input"
              />
            </el-form-item>
            <el-form-item
              :rules="{required: true, message: '优惠券名称不能为空', trigger: 'submit'}"
              label="使用须知"
              prop="discount"
            >
              <el-input
                type="textarea"
                :maxlength="500"
                placeholder="请填写使用本优惠券的注意事项"
                v-model="couponForm.title"
                class="coupon-form-input"
              />
            </el-form-item>
            <el-form-item
              :rules="{required: true, message: '优惠券名称不能为空', trigger: 'submit'}"
              label="图文介绍"
              prop="discount"
            >
              <div class="message">图片建议尺寸：900像素 * 500像素，大小不超过2M。
                至少上传1组图文，最多输入5000字</div>
              <div
                class="article-item"
                v-show="show"
              >
                <div class="article-item-t">
                  <img src="https://cdn.exe666.com/fe/marketing/img/tiger/icon.png">
                  <el-input
                    type="textarea"
                    :maxlength="500"
                    placeholder="图文内容建议上传商品、服务、环境等优质图片，并辅之以简单描述"
                    v-model="couponForm.title"
                    class="coupon-form"
                  />
                </div>
                <div class="article-item-b">
                  <el-button
                    type="success"
                    size="medium"
                    @click="upload()"
                  >确定</el-button>
                  <el-button
                    plain
                    size="medium"
                    @click.stop="()=>{ show = false}"
                  >取消</el-button>
                </div>
              </div>
              <div
                class="detail"
                @click.stop="()=>{ show = true}"
              >
                <img
                  class="uploadIcon"
                  src="https://cdn.exe666.com/fe/marketing/img/tiger/icon.png"
                >
              </div>
            </el-form-item>
          </el-form>
        </div>
        <el-button
          type="success"
          size="medium"
          class="next-step"
          @click="next()"
        >下一步</el-button>
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
  Checkbox
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
    "el-checkbox": Checkbox
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
      value13: "",
      dialogImageUrl: '',
      dialogVisible: false,
      show: false,
      checked: false,
      checkedShow: false,
      companyList: [],
      filters: {
        name: "",
        company_id: ""
      },
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      rules: {
        end_date: [{ validator: checkEndDate, trigger: "submit" }],
        sort_order: [{ validator: checkSortOrder, trigger: "submit" }]
      },
      colorList: [{ color: "#000" }, { color: "#fff" },],
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
    next() {
      console.log("提交券类型")
      this.$router.push({
        path: "/project/wx_cardpackage/use/"
      });
    },
    upload() {
      console.log("上传")
    },
  }
};
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;

  .item-list-wrap {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    font-size: 16px;
    background: #fff;
    padding: 30px;
    .item-content-right {
      width: 1000px;
      text-align: left;
      padding: 20px 10px;
      margin: 0 auto;
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
