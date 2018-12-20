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
      <div class="item-content-left">
        <div class="item-content-left-card">
          <div class="logo">
            <img src="https://cdn.exe666.com/fe/marketing/img/tiger/icon.png">
          </div>
          <div class="tickMsg">
            <p class="title">测试</p>
            <el-button
              type="success"
              size="medium"
              class="confirm"
            >使用</el-button>
            <p><span class="title-span">可用时间：</span><span>2018-11-12</span></p>
          </div>
          <div class="cardUsage">
            <img src="https://cdn.exe666.com/fe/marketing/img/tiger/icon.png">
            <p class="title">23232323</p>
          </div>
          <div class="shop">
            <p class="title">适用门店</p>
          </div>
          <div class="public">
            <p class="title">公众号</p>
          </div>
        </div>
        <div class="item-content-left-bt">
          <p class="title">自定义入口（选填）</p>
        </div>
      </div>
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
              <el-input
                v-model="input"
                suffix-icon="el-icon-caret-bottom"
                placeholder="请选择颜色"
              ></el-input>
              <div class="colorList">
                <ul>
                  <a
                    v-for="item in colorList"
                    :key="item.id"
                    :value="item.id"
                    :style="item.style"
                  >
                    <li></li>
                  </a>

                </ul>
              </div>
            </el-form-item>
            <el-form-item
              :rules="{required: true, message: '折扣额度只能是大于1且小于10的数字', trigger: 'submit'}"
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
              :rules="{required: true, message: '卡券名称不能为空且长度不超过9个汉字或18个英文字母', trigger: 'submit'}"
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
                      v-model="couponForm"
                      placeholder="当天"
                      class="coupon-form-select"
                    >
                      <el-option
                        v-for="item in dataList"
                        :key="item.id"
                        :value="item.dataTime"
                      />
                    </el-select><span class="select">生效,有效天数</span>
                    <el-select
                      v-model="couponForm"
                      placeholder="30天"
                      class="coupon-form-select"
                    >
                      <el-option
                        v-for="item in dataList"
                        :key="item.id"
                        :value="item.dataTime"
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
                <div
                  class="box-segmentation"
                  v-show="checkDataShow"
                >
                  <el-checkbox-group v-model="checkList">
                    <span class="check-data">日期</span>
                    <el-checkbox label="周一"></el-checkbox>
                    <el-checkbox label="周二"></el-checkbox>
                    <el-checkbox label="周三"></el-checkbox>
                    <el-checkbox label="周四"></el-checkbox>
                    <el-checkbox label="周五"></el-checkbox>
                    <el-checkbox label="周六"></el-checkbox>
                    <el-checkbox label="周日"></el-checkbox>
                  </el-checkbox-group>
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
                  v-model="couponForm"
                  placeholder="请选择"
                  class="coupon-form-select"
                >
                  <el-option
                    v-for="item in shareList"
                    :key="item.id"
                    :value="item.id"
                  />
                </el-select>
              </div>

              <div class="message">使用条件的设置会在券上展示，请务必仔细确认。</div>
            </el-form-item>
            <el-form-item
              :rules="{required: true, message: '请上传封面图片', trigger: 'submit'}"
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
              <div class="uploadImg ">
                <img src="https://cdn.exe666.com/fe/marketing/img/tiger/icon.png">
              </div>
            </el-form-item>
            <el-form-item
              :rules="{required: true, message: '简介不能为空且长度不超过12个汉字', trigger: 'submit'}"
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
                  src="https://cdn.exe666.com/fe/marketing/img/jia.png"
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
  Checkbox,
  CheckboxGroup,
  Autocomplete
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
    "el-autocomplete": Autocomplete
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
      checkDataShow: true,
      companyList: [],
      input: '',
      filters: {
        name: "",
        company_id: ""
      },
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      checkList: [],
      rules: {
        end_date: [{ validator: checkEndDate, trigger: "submit" }],
        sort_order: [{ validator: checkSortOrder, trigger: "submit" }]
      },
      colorList: [
        {
          id: 1,
          style: {
            background: "#63b359"
          }
        },
        {
          id: 2,
          style: {
            background: "#2c9f67"
          }
        },
        {
          id: 2,
          style: {
            background: "#509fc9"
          }
        },
        {
          id: 2,
          style: {
            background: "#5885cf"
          }
        },
        {
          id: 2,
          style: {
            background: "#9062c0"
          }
        },
        {
          id: 2,
          style: {
            background: "#d09a45"
          }
        },
        {
          id: 2,
          style: {
            background: "#e4b138"
          }
        },
        {
          id: 2,
          style: {
            background: "#ee903c"
          }
        },
        {
          id: 2,
          style: {
            background: "	#f08500"
          }
        },
        {
          id: 2,
          style: {
            background: "#a9d92d"
          }
        },
      ],
      shareList: [
        {
          id: 1,
          share: "请选择",
        },
        {
          id: 2,
          share: "不与其他优惠共享",
        },
        {
          id: 3,
          share: "可与其他优惠共享",
        },
      ],
      dataList: [
        {
          id: 1,
          dataTime: "当天",
        },
        {
          id: 2,
          dataTime: "1天",
        },
        {
          id: 3,
          dataTime: "2天",
        },

      ],
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
  mounted() {
    this.restaurants = this.loadAll();
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
    querySearch(queryString, cb) {
      var restaurants = this.restaurants;
      var results = queryString ? restaurants.filter(this.createFilter(queryString)) : restaurants;
      // 调用 callback 返回建议列表的数据
      cb(results);
    },
    createFilter(queryString) {
      return (restaurant) => {
        return (restaurant.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
      };
    },
    handleSelect(item) {
      console.log(item);
    },
    loadAll() {
      return [
        { "value": "三" },
        { "value": "新" },
        { "value": "泷" },
      ]
    }
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
    flex-direction: row;
    justify-content: flex-start;
    font-size: 16px;
    background: #fff;
    padding: 0 50px;
    .item-content-left {
      width: 350px;
      height: 672px;
      background: #f6f6f8;
      background-image: url("https://cdn.exe666.com/fe/marketing/img/bg.png");
      background-size: 100% auto;
      background-repeat: no-repeat;
      margin-right: 15px;
      display: flex;
      flex-direction: column;
      align-items: center;
      .item-content-left-card {
        background: #fff;
        width: 250px;
        border-radius: 10px;
        margin-top: 50px;
        .logo {
          width: 100%;
          margin: 0 auto;
          text-align: center;
          img {
            width: 50px;
            border-radius: 50%;
            margin-top: -25px;
          }
        }
        .tickMsg {
          width: 100%;
          margin: 0 auto;
          text-align: center;
          // border-bottom:1px dashed #8d8d8d;
          .title {
            font-size: 20px;
            color: #000;
          }
          .title-spa {
            font-size: 14px;
            color: #000;
          }
          span {
            font-size: 14px;
          }
        }
        .cardUsage {
          width: 200px;
          margin: 0 auto;
          text-align: center;
          border-top: 1px dashed #e7e7eb;
          padding: 15px 0;
          img {
            width: 200px;
          }
          p {
            width: 200px;
            color: #fff;
            text-align: left;
            background: rgba(0, 0, 0, 0.7);
            padding: 0;
            margin: 0;
          }
        }
        .shop {
          width: 200px;
          border-bottom: 1px solid #e7e7eb;
          padding: 15px 0;
          margin: 0 auto;
          text-align: center;
          p {
            width: 200px;
            color: #000;
            text-align: left;
            padding: 0;
            margin: 0;
            font-size: 14px;
          }
        }
        .public {
          width: 200px;
          padding: 15px 0;
          margin: 0 auto;
          text-align: center;
          p {
            width: 200px;
            color: #000;
            text-align: left;
            padding: 0;
            margin: 0;
            font-size: 14px;
          }
        }
      }
      .item-content-left-bt {
        width: 250px;
        margin: 0 auto;
        background: #fff;
        border-radius: 10px;
        text-align: center;
        margin-top: 10px;

        p {
          width: 200px;
          color: #000;
          text-align: left;
          padding: 0;
          margin: 0;
          font-size: 14px;
          padding: 15px;
        }
      }
    }
    .item-content-right {
      width: 1000px;
      text-align: left;
      padding: 20px 10px;
      border: 1px solid #e7e7eb;
      background: #f4f5f9;
      .check-data {
        font-size: 16px;
        margin-right: 20px;
      }
      .el-input {
        width: 200px;
      }
      .el-autocomplete-261-item-0 {
        display: inline-block;
      }
      .coupon-form-input {
        width: 200px;
      }
      .coupon-form-select {
        width: 180px;
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
      .uploadImg {
        margin-top: 15px;
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
      .colorList {
        width: 166px;
        text-align: center;
        margin: 0 10px;
        a {
          display: inline-block;
          margin: 10px 0px 10px 10px;
          line-height: 20px;

          li {
            width: 20px;
            height: 20px;
            display: inline-block;
          }
        }
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
