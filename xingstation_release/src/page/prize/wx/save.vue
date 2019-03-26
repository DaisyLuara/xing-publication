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
      <div
        class="item-content-left"
        :style="leftDetail.color"
      >
        <div class="item-content-left-card">
          <div class="logo">
            <!-- <img src="https://cdn.exe666.com/fe/marketing/img/tiger/icon.png"> -->
            <img :src="this.base_info.logo_url">
          </div>
          <div class="tickMsg">
            <p
              class="title"
              v-if="card_type!=='CASH'"
            >{{leftDetail.title}}</p>
            <p
              class="title"
              v-if="card_type==='CASH'"
            >{{null===reduce_cost||''===reduce_cost?'代金券标题':reduce_cost+'元代金券'}}</p>
            <div class="use-button"><span :style="leftDetail.color">使用</span></div>
            <p v-if="(card_type==='CASH'||card_type==='DISCOUNT')&&leftDetail.cashOrDiscountchecked">
              <span class="title-span">使用条件：</span><span v-if="leftDetail.accept_category!==''">适用于</span><span>{{leftDetail.accept_category}}</span><span v-if="leftDetail.accept_category!==''&&leftDetail.reject_category!==''">;</span><span v-if="leftDetail.reject_category!==''">不适用于</span><span>{{leftDetail.reject_category}}</span></p>
            <p><span class="title-span">可用时间：</span><span v-if="null!==leftDetail.formatStartDate">{{leftDetail.formatStartDate}}-{{leftDetail.formatEndDate}}</span><span>{{leftDetail.timeSegment}}</span></p>
          </div>
          <div class="cardUsage">
            <img :src="leftDetail.icon_url">
            <p class="title">{{leftDetail.abstract}}</p>
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
            label-width="180px"
          >
            <el-form-item label="商户">
              <img
                class="icon"
                :src="this.base_info.logo_url"
              >
            </el-form-item>
            <el-form-item label="卡券颜色">
              <div
                class="colorList-input"
                @click="()=>{colorListShow=true}"
              >
                <a :style="leftDetail.color"></a>
                <input
                  v-show="inputShow"
                  type="text"
                >
              </div>
              <div
                class="colorList"
                v-show="colorListShow"
              >
                <ul>
                  <a
                    v-for="item in colorList"
                    :key="item.id"
                    :value="item.color"
                    :style="item.style"
                    @click="changeColor(item)"
                  >
                    <li></li>
                  </a>

                </ul>
              </div>
              <div
                class="errMessage"
                v-show="submitCheck.color"
              >请选择颜色</div>
            </el-form-item>
            <el-form-item
              v-if="card_type==='DISCOUNT'"
              label="折扣额度"
            >
              <el-input
                v-model="discount"
                class="coupon-form-input"
                :disabled="disabled"
              />
              <span>折</span>
              <div
                class="errMessage"
                v-show="submitCheck.discountAmount"
              >折扣额度只能是大于1且小于10的数字</div>
              <div class="message"> 请填写1-9.9之间的数字，精确到小数点后1位</div>
            </el-form-item>
            <!-- 折扣券标题 -->
            <el-form-item
              v-if="card_type==='DISCOUNT'"
              label="折扣券标题"
            >
              <el-input
                v-model="base_info.title"
                class="coupon-form-input"
                style="width:250px"
                :maxlength="9"
              />
              <div
                class="errMessage"
                v-show="submitCheck.titleCheck"
              >卡券名称不能为空且长度不超过9个汉字或18个英文字母</div>
              <div class="message"> 建议填写折扣券“折扣额度”及自定义内容，描述卡券提供的具体优惠</div>
            </el-form-item>
            <!-- 兑换券 -->
            <el-form-item
              v-if="card_type==='GIFT'"
              label="兑换券标题"
            >
              <el-input
                v-model="base_info.title"
                :maxlength="9"
                class="coupon-form-input"
                style="width:250px"
              />
              <div
                class="errMessage"
                v-show="submitCheck.titleCheck"
              >卡券名称不能为空且长度不超过9个汉字或18个英文字母</div>
              <div class="message"> 建议填写兑换券提供的服务或礼品名称，描述卡券提供的具体优惠</div>
            </el-form-item>
            <!-- 优惠券券 -->
            <el-form-item
              v-if="card_type==='GENERAL_COUPON'"
              label="优惠券标题"
            >
              <el-input
                v-model="base_info.title"
                class="coupon-form-input"
                style="width:250px"
              />
              <div class="message"> 建议填写优惠券提供的服务或商品名称，描述卡券提供的具体优惠</div>
            </el-form-item>
            <!-- 团购券 -->

            <el-form-item
              v-if="card_type==='GROUPON'"
              label="团购券标题"
            >
              <el-input
                v-model="base_info.title"
                class="coupon-form-input"
                style="width:250px"
              />
              <div class="message">建议填写团购券提供的服务或商品名称、对应金额，描述卡券提供的具体优惠</div>
            </el-form-item>

            <!-- 代金券 -->
            <el-form-item
              v-if="card_type==='CASH'"
              label="减免金额"
            >
              <el-input
                v-model="reduce_cost"
                class="coupon-form-input"
                :disabled="disabled"
              />
              <span>元</span>
              <div
                class="errMessage"
                v-show="submitCheck.creditAmount"
              >减免金额只能是大于0.01的数字</div>
            </el-form-item>
            <el-form-item label="有效期">
              <el-radio-group
                v-model="date_info.type"
                @change="changeDataType"
              >
                <div class="box-segmentation">
                  <el-radio :label="dateType.RANGE">固定时间
                    <el-date-picker
                      v-model="startToEndDate"
                      type="daterange"
                      start-placeholder="开始日期"
                      end-placeholder="结束日期"
                      :default-time="['00:00:00', '23:59:59']"
                      class="picker"
                      @change="logTimeChange"
                    >
                    </el-date-picker>
                  </el-radio>
                </div>
                <div class="box-segmentation">
                  <el-radio :label="dateType.TERM">领取后，
                    <el-select
                      v-model="fixed_begin_term"
                      placeholder="当天"
                      class="coupon-form-select"
                      @change="changeDataType"
                    >
                      <el-option
                        v-for="item in dataList"
                        :key="item.id"
                        :value="item.count"
                        :label="item.title"
                      />
                    </el-select><span class="select">生效,有效天数</span>
                    <el-select
                      v-model="fixed_term"
                      placeholder="30天"
                      class="coupon-form-select"
                      @change="changeDataType"
                    >
                      <el-option
                        v-for="item in dataList"
                        :key="item.id"
                        :value="item.count"
                        :label="item.title"
                      />
                    </el-select>

                  </el-radio>
                </div>

              </el-radio-group>
              <div
                class="errMessage"
                v-show="submitCheck.dateCheck"
              >请选择日期</div>
            </el-form-item>
            <el-form-item label="可用时段">
              <el-radio-group
                v-model="couponForm.is_fixed_date"
                @change="changeTimeSegment"
              >
                <div class="box-segmentation">
                  <el-radio :label="1">全部时段</el-radio>
                </div>
                <div class="box-segmentation">
                  <el-radio :label="0">部分时段</el-radio>
                </div>
                <div v-if="!timeFrameShow">
                  <div class="box-segmentation">
                    <span class="check-data">日期</span>
                    <el-checkbox
                      v-for="(timeLimit,index) in timeLimitType"
                      :label="timeLimit.title"
                      :name="timeLimit.type"
                      :key="timeLimit.id"
                      v-model="timeLimit.isClick"
                      @change="addTimeLimit(index,timeLimit)"
                    ></el-checkbox>
                  </div>
                  <div class="addDateTime "><span
                      class="time"
                      @click="addHourOrMinute()"
                    >添加时间段</span><span
                      class="time"
                      @click="deleteHourOrMinute()"
                    >删除时间段</span></div>
                  <div
                    class="addInput box-segmentation"
                    v-for="hourMinute in beginToEndHourMinute"
                    :key="hourMinute.id"
                  >
                    <span>时间：</span>
                    <a class="dataRange">
                      <el-input
                        v-model="hourMinute.beginTime"
                        class="coupon-form-input"
                        style="width:100px"
                      />
                      <span class="to">至</span>
                      <el-input
                        v-model="hourMinute.endTime"
                        class="coupon-form-input"
                        style="width:100px"
                      />
                    </a>
                  </div>
                  <div class="message box-segmentation">请使用24小时制输入时间，格式如11:00至14:30</div>
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
            label-width="180px"
          >
            <el-form-item label="领券限制">
              <el-input
                v-model="base_info.get_limit"
                class="coupon-form-input"
              />
              <span>张</span>
              <div class="message">每个用户领券上限，如不填，则默认为1</div>
            </el-form-item>
            <el-form-item label="使用条件">
              <!-- 代金券 -->
              <div v-if="card_type==='CASH'">
                <el-checkbox
                  v-model="cashChecked"
                  :disabled="disabled"
                >最低消费
                  <span>满
                    <el-input
                      v-model="least_cost"
                      class="coupon-form-input"
                      :disabled="disabled"
                    />元可用</span>
                </el-checkbox>
                <div
                  class="errMessage"
                  v-show="submitCheck.consumptionAmount"
                >消费金额大于0且只能到百分位</div>
              </div>

              <!-- 兑换券 -->
              <div v-if="card_type==='GIFT'">
                <el-checkbox
                  v-model="giftChecked"
                  :disabled="disabled"
                >消费
                  <span v-show="giftChecked">
                    <el-select
                      v-model="couponForm.is_money"
                      placeholder="金额"
                      class="coupon-form-select"
                      :disabled="disabled"
                    >
                      <el-option
                        v-for="item in moneyList"
                        :key="item.id"
                        :value="item.count"
                        :label="item.title"
                      />
                    </el-select>
                    <b>满
                      <el-input
                        v-model="gift"
                        class="coupon-form-input"
                        :disabled="disabled"
                      />元可用</b>
                  </span>
                </el-checkbox>
                <div
                  class="errMessage"
                  v-show="submitCheck.ConsumptionAmount"
                >消费金额大于0且只能到百分位</div>
              </div>

              <!-- 折扣券 -->
              <div v-if="card_type==='CASH'||card_type==='DISCOUNT'">
                <el-checkbox v-model="cashOrDiscountchecked">适用范围　(至少填写一项)</el-checkbox>
                <div
                  class="goods"
                  v-show="cashOrDiscountchecked"
                >
                  <span>适用商品</span>
                  <el-input
                    v-model="use_condition.accept_category"
                    class="coupon-form-input"
                    :disabled="disabled"
                  />
                  <div
                    class="errMessage"
                    :maxlength="15"
                    v-show="submitCheck.commodity"
                  >适用商品不能为空且长度不能超过15个汉字或30个英文字母</div>
                  <div class="message-box">填写本券适用的商品、类目或服务</div>
                  <span>不适用商品</span>
                  <el-input
                    v-model="use_condition.reject_category"
                    class="coupon-form-input"
                    :disabled="disabled"
                  />
                  <div
                    class="errMessage"
                    :maxlength="15"
                    v-show="submitCheck.noCommodity"
                  >不适用商品不能为空且长度不能超过15个汉字或30个英文字母</div>
                  <div class="message-box">填写本券不适用的商品、类目或服务</div>
                </div>
              </div>
              <div>
                <span>优惠共享</span>
                <el-select
                  v-model="use_condition.can_use_with_other_discount"
                  placeholder="请选择"
                  class="coupon-form-select"
                >
                  <el-option
                    v-for="item in shareList"
                    :key="item.id"
                    :value="item.isShare"
                    :label="item.share"
                  />
                </el-select>
              </div>
              <div
                class="errMessage"
                v-show="submitCheck.discountsShare"
              >请选择优惠共享</div>
              <div class="message">使用条件的设置会在券上展示，请务必仔细确认。</div>
            </el-form-item>
            <el-form-item label="封面图片">
              <div class="message">图片建议尺寸：850像素*350像素，大小不超过2M</div>
              <!-- 上传图片 -->
              <div class="confirm">
                <el-upload
                  ref="upload"
                  :action="Domain"
                  :data="uploadForm"
                  :on-success="handleSuccessTop"
                  :before-upload="beforeUpload"
                  :on-remove="handleRemove"
                  :on-preview="handlePreview"
                  :before-remove="beforeRemove"
                  :file-list="fileList"
                  :show-file-list='false'
                  :on-exceed="handleExceed"
                  class="upload-demo"
                >
                  <el-button
                    size="mini"
                    type="success"
                  >点击上传</el-button>
                </el-upload>
              </div>
              <div class="uploadImg ">
                <img :src="abstract.icon_url_list[0]">
              </div>
              <div
                class="errMessage"
                v-show="submitCheck.uploadCheck"
              >请上传封面图片</div>
            </el-form-item>
            <el-form-item label="封面简介">
              <el-input
                v-model="abstract.abstract"
                placeholder="请输入封面图片的简介内容"
                :maxlength="12"
                class="coupon-form-input"
              />
              <div
                class="errMessage"
                v-show="submitCheck.introductionCheck"
              >简介不能为空且长度不超过12个汉字</div>
            </el-form-item>
            <el-form-item label="优惠说明">
              <div class="message">（本行是非自定义内容，无需填写）</div>
              <el-input
                v-if="card_type==='GROUPON'||card_type==='GENERAL_COUPON'"
                type="textarea"
                :maxlength="500"
                placeholder="请输入自定义优惠说明内容"
                v-model="detail"
                class="coupon-form-input"
              />
              <div
                class="errMessage"
                v-show="submitCheck.privilegeCheck"
              >自定义优惠说明不能为空且长度不超过300个汉字</div>
            </el-form-item>
            <el-form-item label="使用须知">
              <el-input
                type="textarea"
                :maxlength="500"
                placeholder="请填写使用本优惠券的注意事项"
                v-model="base_info.description"
                class="coupon-form-input"
              />
            </el-form-item>
            <el-form-item label="图文介绍">
              <div class="message">图片建议尺寸：900像素 * 500像素，大小不超过2M。
                至少上传1组图文，最多输入5000字</div>
              <div
                class="article-item"
                v-for="(textImage,index) in text_image_list"
                :key="textImage.id"
              >
                <div class="article-item-t">
                  <img :src="textImage.image_url">
                  <div
                    class="upload-button"
                    v-if="textImage.isUpload"
                  >
                    <el-upload
                      ref="upload"
                      :action="Domain"
                      :data="uploadForm"
                      :on-success="handleSuccessBottom"
                      :before-upload="beforeUpload"
                      :on-remove="handleRemove"
                      :on-preview="handlePreview"
                      :before-remove="beforeRemove"
                      :file-list="fileList"
                      :show-file-list='false'
                      :on-exceed="handleExceed"
                      class="upload-demo"
                    >
                      <el-button
                        @click="changeIndex(index)"
                        size="mini"
                        type="success"
                      >点击上传</el-button>
                    </el-upload>
                  </div>
                  <el-input
                    type="textarea"
                    :maxlength="500"
                    placeholder="图文内容建议上传商品、服务、环境等优质图片，并辅之以简单描述"
                    v-model="textImage.text"
                    class="coupon-form"
                  />
                </div>
                <div
                  class="article-item-b"
                  v-if="textImage.flag"
                >
                  <el-button
                    type="success"
                    size="medium"
                    @click="confirme(index)"
                  >确定</el-button>
                  <el-button
                    plain
                    size="medium"
                    @click.stop="cancel(index)"
                  >取消</el-button>
                </div>
              </div>
              <div
                class="detail"
                @click.stop="addTextImageList()"
              >
                <img
                  class="uploadIcon"
                  src="https://cdn.exe666.com/fe/marketing/img/jia.png"
                >
              </div>
              <div
                class="errMessage"
                v-show="submitCheck.graphicIntroduction"
              >图片和描述都不能为空</div>
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
  getQiniuToken,
  getMediaUpload,
  getSingleCard,
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
    return {
      Domain: "http://upload.qiniu.com",
      uploadToken: "",
      uploadKey: "",
      uploadForm: {
        token: "",
        key: ""
      },
      cashOrDiscountchecked: false,
      giftChecked: false,
      cashChecked: false,
      disabled: false,
      fileList: [],
      ids: [],
      inputShow: true,
      timeFrameShow: true,
      colorListShow: false,
      startToEndDate: [],
      card_type: this.$route.query.card_type,
      card_id: this.$route.query.card_id,
      detail: '',
      least_cost: null,
      reduce_cost: null,
      gift: '',
      discount: null,
      beginToEndHourMinute: [],
      flag: 7,
      index: null,
      base_info: {
        "logo_url":
          "http://mmbiz.qpic.cn/mmbiz/iaL1LJM1mF9aRKPZJkmG8xXhiaHqkKSVMMWeN3hLut7X7hicFNjakmxibMLGWpXrEXB33367o7zHN0CwngnQY7zb7g/0",
        "brand_name": "微信餐厅",//true
        "code_type": null,//true
        "title": "",//true
        "color": null,//true
        "notice": "使用时向服务员出示此券",//true
        "service_phone": "020-88888888",        //true
        "description": "", //true
        "get_limit": null,
      },
      //使用日期，有效期的信息
      date_info: {
        "type": 'DATE_TYPE_FIX_TIME_RANGE' //true
      },
      begin_timestamp: null,  //true
      end_timestamp: null,//true
      fixed_term: 2,
      fixed_begin_term: 0,
      use_condition: {
        "accept_category": "",
        "reject_category": "",
        "can_use_with_other_discount": null
      },
      abstract: {
        "abstract": "",
        "icon_url_list": []
      },
      text_image_list: [],
      time_limit: [],
      advanced_info: {},
      dateType: { RANGE: 'DATE_TYPE_FIX_TIME_RANGE', TERM: 'DATE_TYPE_FIX_TERM' },
      submitCheck: {
        color: false, //颜色
        titleCheck: false,//标题检查
        dateCheck: false,//日期
        uploadCheck: false, //上传
        introductionCheck: false,//封面简介
        privilegeCheck: false, //优惠说明
        discountAmount: false,//折扣额度
        commodity: false,//适用商品
        noCommodity: false,//不适用商品
        consumptionAmount: false, //消费
        ConsumptionAmount: false,//消费2
        creditAmount: false, //减免金额
        graphicIntroduction: false, //图文介绍
        discountsShare: false//优惠共享
      },
      leftDetail: {
        color: { "background": "#f6f6f8" },
        title: '',
        startDate: null,
        endDate: null,
        formatStartDate: null,
        formatEndDate: null,
        timeSegment: '周一至周日',
        cashOrDiscountchecked: false,
        giftChecked: false,
        cashChecked: false,
        accept_category: '',
        reject_category: '',
        icon_url: null,
        abstract: ''
      },
      saveTimeLimit: [
        { type: "MONDAY", title: "周一", sort: 1 },
        { type: "TUESDAY", title: "周二", sort: 2 },
        { type: "WEDNESDAY", title: "周三", sort: 3 },
        { type: "THURSDAY", title: "周四", sort: 4 },
        { type: "FRIDAY", title: "周五", sort: 5 },
        { type: "SATURDAY", title: "周六", sort: 6 },
        { type: "SUNDAY", title: "周日", sort: 7 }
      ],
      timeLimitType: [
        { type: "MONDAY", title: "周一", sort: 1, isClick: true },
        { type: "TUESDAY", title: "周二", sort: 2, isClick: true },
        { type: "WEDNESDAY", title: "周三", sort: 3, isClick: true },
        { type: "THURSDAY", title: "周四", sort: 4, isClick: true },
        { type: "FRIDAY", title: "周五", sort: 5, isClick: true },
        { type: "SATURDAY", title: "周六", sort: 6, isClick: true },
        { type: "SUNDAY", title: "周日", sort: 7, isClick: true }
      ],
      colorList: [
        {
          id: 1,
          color: 'Color010',
          style: {
            background: "#63b359"
          }
        },
        {
          id: 2,
          color: 'Color020',
          style: {
            background: "#2c9f67"
          }
        },
        {
          id: 3,
          color: 'Color030',
          style: {
            background: "#509fc9"
          }
        },
        {
          id: 4,
          color: 'Color040',
          style: {
            background: "#5885cf"
          }
        },
        {
          id: 5,
          color: 'Color050',
          style: {
            background: "#9062c0"
          }
        },
        {
          id: 6,
          color: 'Color060',
          style: {
            background: "#d09a45"
          }
        },
        {
          id: 7,
          color: 'Color070',
          style: {
            background: "#e4b138"
          }
        },
        {
          id: 8,
          color: 'Color080',
          style: {
            background: "#ee903c"
          }
        },
        {
          id: 9,
          color: 'Color090',
          style: {
            background: "	#f08500"
          }
        },
        {
          id: 10,
          color: 'Color100',
          style: {
            background: "#a9d92d"
          }
        },
      ],
      card_types: {
        GROUPON: {
          "card_type": "GROUPON",
          "groupon": {
            "deal_detail": ""
          }
        }, CASH: {
          "card_type": "CASH",
          "cash": {
            "least_cost": null,
            "reduce_cost": null,
          }
        }, GIFT: {
          "card_type": "GIFT",
          "gift": {
            "gift": ""
          }
        }, DISCOUNT: {
          "card_type": "DISCOUNT",
          "discount": {
            "discount": null
          }
        }, GENERAL_COUPON: {
          "card_type": "GENERAL_COUPON",
          "general_coupon": {
            "default_detail": ""
          }
        }      },
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      shareList: [
        {
          id: 0,
          isShare: null,
          share: "请选择",
        },
        {
          id: 1,
          isShare: false,
          share: "不与其他优惠共享",
        },
        {
          id: 2,
          isShare: true,
          share: "可与其他优惠共享",
        },
      ],
      dataList: [
        {
          id: 0,
          title: '当天',
          count: 0,
        },
        {
          id: 1,
          title: '1天',
          count: 1,
        },
        {
          id: 2,
          title: '2天',
          count: 2,
        },

      ],
      moneyList: [
        {
          id: 0,
          title: '金额',
          count: 0,
        },
        {
          id: 1,
          title: '指定商品',
          count: 1,
        },
      ],

      couponForm: {
        is_fixed_date: 1,
        is_money: 0
      },
      testData: {}
    };
  },
  watch: {
    newValue(val, oldVal) {
      if (val === 1) {
        this.timeFrameShow = true;
      } else {
        this.timeFrameShow = false;
      }
    },
    changeTitles(val, oldVal) {
      this.leftDetail.title = val;
    },
    cashOrDiscountchecked(val, oldVal) {
      this.leftDetail.cashOrDiscountchecked = val
    },
    giftChecked(val, oldVal) {
      this.leftDetail.giftChecked = val
    },
    cashChecked(val, oldVal) {
      this.leftDetail.cashChecked = val
    },
    acceptCategory(val, oldVal) {
      this.leftDetail.accept_category = val
    },
    rejectcCategory(val, oldVal) {
      this.leftDetail.reject_category = val
    },
    abstracts(val, oldVal) {
      this.leftDetail.abstract = val
    }
  },
  computed: {
    newValue() {
      return this.couponForm.is_fixed_date;
    },
    changeTitles() {
      return this.base_info.title;
    },
    acceptCategory() {
      return this.use_condition.accept_category
    },
    rejectcCategory() {
      return this.use_condition.reject_category
    },
    abstracts() {
      return this.abstract.abstract
    }
  },
  created() {
    this.getQiniuToken();
  },
  mounted() {
    //线上
    this.getSingleCard()
  },
  methods: {
    //以下为查询卡劵修改初始化操作
    //卡劵修改
    getSingleCard() {
      if (this.card_id != undefined) {
        let params = {
          authorizer_id: 6,
          card_id: this.card_id
        }
        //查询卡劵详情
        getSingleCard(this, params)
          .then(res => {
            this.cardDetailsHandle(res.card)
          })
          .catch(err => {
            console.log(err);
          });
      }
    },
    //初始化卡劵信息
    cardDetailsHandle(data) {
      this.code_type = data.card_type
      if (this.card_type === 'GROUPON') {
        this.detail = data.groupon.deal_detail
        this.base_info = data.groupon.base_info
        this.commonHandleInit('groupon', data)
      }
      //代金券
      else if (this.card_type === 'CASH') {
        this.least_cost = data.cash.least_cost
        this.reduce_cost = data.cash.reduce_cost
        this.base_info = data.cash.base_info
        this.commonHandleInit('cash', data)
      }
      //折扣券
      else if (this.card_type === 'DISCOUNT') {
        this.discount = data.discount.discount
        this.base_info = data.discount.base_info
        this.commonHandleInit('discount', data)
      }
      //兑换券
      else if (this.card_type === 'GIFT') {
        this.gift = data.gift.gift
        this.base_info = data.gift.base_info
        this.commonHandleInit('gift', data)
      }
      //优惠券
      else {
        this.detail = data.general_coupon.default_detail
        this.base_info = data.general_coupon.base_info
        this.commonHandleInit('general_coupon', data)
      }
      this.disabled = true
    },
    commonHandleInit(type, data) {
      this.colorHandle(data[type].base_info.color)
      this.useConditionInit(data[type].advanced_info.use_condition)
      this.abstractInit(data[type].advanced_info.abstract)
      this.textImageInit(data[type].advanced_info.text_image_list)
      this.dateInfoInit(data[type].base_info.date_info)
      this.timelimitInit(data[type].advanced_info.time_limit)
      this.defaultSelected(type)
    },
    //颜色
    colorHandle(color) {
      this.leftDetail.color = { "background": color }
      this.inputShow = false
    },
    //最低消费是否点击
    defaultSelected(type) {
      if (type === 'cash' && this.least_cost != null && this.least_cost != undefined && this.least_cost != '') {
        this.cashChecked = true
      }
      if (type === 'gift' && this.gift != null && this.gift != undefined) {
        this.giftChecked = true
      }
    },
    //封面
    useConditionInit(use_condition) {
      if (use_condition !== null && use_condition != undefined && use_condition != '') {
        this.use_condition = use_condition
        this.cashOrDiscountchecked = true
      }
    },
    //封面初始化
    abstractInit(abstract) {
      this.abstract = abstract
      this.leftDetail.icon_url = abstract.icon_url_list[0]
      this.leftDetail.abstract = abstract.abstract
    },
    //图文介绍初始化
    textImageInit(text_image_list) {
      let array = new Array()
      for (let i = 0; i < text_image_list.length; i++) {
        let text_image = {
          "image_url": text_image_list[i].image_url,
          "text": text_image_list[i].text,
          "flag": false,
          "isUpload": false
        }
        array.push(text_image)
      }
      this.text_image_list = array
    },
    //有效期初始化
    dateInfoInit(date_info) {
      this.date_info.type = date_info.type
      if (date_info.type === 'DATE_TYPE_FIX_TIME_RANGE') {
        this.begin_timestamp = date_info.begin_timestamp
        this.end_timestamp = date_info.end_timestamp
        let begin = this.formatDateTime(this.begin_timestamp)
        let end = this.formatDateTime(this.end_timestamp)
        this.startToEndDate.push(begin)
        this.startToEndDate.push(end)
        this.leftDetail.startDate = new Date(begin)
        this.leftDetail.endDate = new Date(end)
        this.changeDataType()
      } else {
        this.fixed_term = date_info.fixed_term
        this.fixed_begin_term = date_info.fixed_begin_term
        var dateTime = new Date()
        this.leftDetail.formatStartDate = this.dateFormat(this.addTime(dateTime, this.fixed_begin_term), 'yyyy.MM.dd')
        this.leftDetail.formatEndDate = this.dateFormat(this.addTime(dateTime, this.fixed_term), 'yyyy.MM.dd')
      }
    },
    //时段
    timelimitInit(time_limit) {
      if (this.isSegment(time_limit)) {
        this.couponForm.is_fixed_date = 0
      } else {
        this.couponForm.is_fixed_date = 1
      }
      for (let j = 0; j < this.saveTimeLimit.length; j++) {
        let flag = false
        for (let i = 0; i < time_limit.length; i++) {
          if (this.saveTimeLimit[j].type === time_limit[i].type) {
            flag = true
            break
          }
        }
        if (!flag) {
          this.timeLimitType[j].isClick = false
          this.saveTimeLimit[j] = undefined
        }
      }
      this.changeTimeSegment()
    },
    //是否是部分时段
    isSegment(time_limit) {
      if (time_limit.length < 7) {
        return true
      }
      for (let i = 0; i < time_limit.length; i++) {
        if ((time_limit[i].begin_hour !== null || time_limit[i].begin_hour !== '' || time_limit[i].begin_hour !== undefined) ||
          (time_limit[i].begin_minute !== null || time_limit[i].begin_minute !== '' || time_limit[i].begin_minute !== undefined) ||
          (time_limit[i].end_hour !== null || time_limit[i].end_hour !== '' || time_limit[i].end_hour !== undefined) ||
          (time_limit[i].end_minute !== null || time_limit[i].end_minute !== '' || time_limit[i].end_minute !== undefined)) {
          return false
        }
      }
      return true
    },
    //下一步卡劵信息处理
    dataHandle() {
      let card = this.card_types[this.card_type]
      let advancedInfo = this.advancedInfoHandle()
      let dateInfo = this.dateInfoHandle()
      //团购券
      if (this.card_type === 'GROUPON') {
        card.groupon.deal_detail = this.detail
        card.groupon.base_info = this.base_info
        card.groupon.advanced_info = advancedInfo
        card.groupon.base_info.date_info = dateInfo
      }
      //代金券
      else if (this.card_type === 'CASH') {
        card.cash.least_cost = this.least_cost
        card.cash.reduce_cost = this.reduce_cost
        card.cash.base_info = this.base_info
        card.cash.advanced_info = advancedInfo
        card.cash.base_info.date_info = dateInfo

      }
      //折扣券
      else if (this.card_type === 'DISCOUNT') {
        card.discount.discount = this.discount
        card.discount.base_info = this.base_info
        card.discount.advanced_info = advancedInfo
        card.discount.base_info.date_info = dateInfo

      }
      //兑换券
      else if (this.card_type === 'GIFT') {
        card.gift.gift = this.gift
        card.gift.base_info = this.base_info
        card.gift.advanced_info = advancedInfo
        card.gift.base_info.date_info = dateInfo

      }
      //优惠券
      else {
        card.general_coupon.default_detail = this.detail
        card.general_coupon.base_info = this.base_info
        card.general_coupon.advanced_info = advancedInfo
        card.general_coupon.base_info.date_info = dateInfo

      }
      return card
    },

    //有效期
    dateInfoHandle() {
      let date_info = { type: this.date_info.type }
      if (date_info.type === 'DATE_TYPE_FIX_TIME_RANGE') {
        date_info.begin_timestamp = this.begin_timestamp
        date_info.end_timestamp = this.end_timestamp
      } else {
        date_info.fixed_term = this.fixed_term
        date_info.fixed_begin_term = this.fixed_begin_term
      }
      return date_info
    },
    //卡券高级信息
    advancedInfoHandle() {
      let advancedInfo = {}
      if (this.cashOrDiscountchecked) {
        advancedInfo.use_condition = this.use_condition
      }
      //封面信息
      advancedInfo.abstract = this.abstract
      //图文列表，显示在详情内页
      let array = new Array()
      for (let i = 0; i < this.text_image_list.length; i++) {
        array.push({ image_url: this.text_image_list[i].image_url, text: this.text_image_list[i].text })
      }
      if (array.length > 0) {
        advancedInfo.text_image_list = array
      }
      //使用时段限制，包含以下字段
      array = new Array()
      let total = 0
      for (let i = 0; i < this.timeLimitType.length; i++) {
        let time_limit = {}
        time_limit.type = this.timeLimitType[i].type
        if (this.saveTimeLimit[i] !== undefined && total < this.beginToEndHourMinute.length && this.couponForm.is_fixed_date === 0) {
          if (this.beginToEndHourMinute[total].beginTime !== '') {
            let beginTime = this.beginToEndHourMinute[total].beginTime.split(':')
            time_limit.begin_hour = parseInt(beginTime[0])
            time_limit.begin_minute = parseInt(beginTime[1])
          }
          if (this.beginToEndHourMinute[total].endTime !== '') {
            let endTime = this.beginToEndHourMinute[total].endTime.split(':')
            time_limit.end_hour = parseInt(endTime[0])
            time_limit.end_minute = parseInt(endTime[1])
          }
          total++
        }

        array.push(time_limit)
      }
      if (array.length > 0) {
        advancedInfo.time_limit = array
      }
      return advancedInfo
    },
    //可用时段改变
    changeTimeSegment() {
      //全部时段
      if (this.couponForm.is_fixed_date === 1) {
        this.leftDetail.timeSegment = '周一至周日'
      } else {
        let strs = ''
        let array = new Array()
        for (let i = 0; i < this.saveTimeLimit.length; i++) {
          if (this.saveTimeLimit[i] === undefined || i === this.saveTimeLimit.length - 1) {
            if (this.saveTimeLimit[i] !== undefined) {
              array.push(this.saveTimeLimit[i].title)
            }
            let str = ''
            if (array.length === 1) {
              str = array[0]
            } else if (array.length > 1) {
              str = array[0] + '至' + array[array.length - 1]
            }
            else {
              str = ''
            }
            if (str !== '') {
              strs = strs + (str + (i === this.saveTimeLimit.length - 1 ? '' : '、'))
            }
            array = new Array()
          } else {
            array.push(this.saveTimeLimit[i].title)
          }
        }
        this.leftDetail.timeSegment = strs
      }
    },
    //时间改变
    changeDataType() {
      //固定时间
      if (this.date_info.type === 'DATE_TYPE_FIX_TIME_RANGE') {
        this.leftDetail.formatStartDate = this.dateFormat(this.leftDetail.startDate, 'yyyy.MM.dd')
        this.leftDetail.formatEndDate = this.dateFormat(this.leftDetail.endDate, 'yyyy.MM.dd')
      } else {
        var dateTime = new Date()
        this.leftDetail.formatStartDate = this.dateFormat(this.addTime(dateTime, this.fixed_begin_term), 'yyyy.MM.dd')
        this.leftDetail.formatEndDate = this.dateFormat(this.addTime(dateTime, this.fixed_term), 'yyyy.MM.dd')
      }
    },
    addTime(dateTime, count) {
      dateTime = dateTime.setDate(dateTime.getDate() + count);
      dateTime = new Date(dateTime);
      return dateTime
    },
    //改变标题
    changeTitle() {
      this.leftDetail.title = this.base_info.title;
    },
    changeIndex(i) {
      this.index = i;
    },
    //获取七牛token
    getQiniuToken() {
      getQiniuToken(this)
        .then(res => {
          this.uploadToken = res;
          this.uploadForm.token = this.uploadToken;
        })
        .catch(err => {
          console.log(err);
        });
    },
    handleRemove(file, fileList) {
      this.fileList = fileList;
    },
    handlePreview(file) {
      let url = file.url;
      const xhr = new XMLHttpRequest();
      xhr.open("GET", url, true);
      xhr.responseType = "blob";
      xhr.onload = () => {
        var urlObject = window.URL || window.webkitURL || window;
        let a = document.createElement("a");
        a.href = urlObject.createObjectURL(new Blob([xhr.response]));
        a.download = file.name;
        a.click();
      };
      xhr.send();
    },
    handleExceed(files, fileList) {
      this.$message.warning(
        `当前限制选择 1 个文件，本次选择了 ${
        files.length
        } 个文件，共选择了 ${files.length + fileList.length} 个文件`
      );
    },
    beforeRemove(file, fileList) {
      return this.$confirm(`确定移除 ${file.name}？`);
    },
    //上传前
    beforeUpload(file) {
      let isLt100M = file.size / 1024 / 1024 < 100;
      let time = new Date().getTime();
      let random = parseInt(Math.random() * 10 + 1, 10);
      let suffix = time + "_" + random + "_" + file.name;
      let key = encodeURI(`${suffix}`);
      if (!isLt100M) {
        this.uploadForm.token = "";
        return this.$message.error("上传大小不能超过 100MB!");
      } else {
        this.uploadForm.token = this.uploadToken;
      }
      this.uploadForm.key = key;
      return this.uploadForm;
    },
    // 上传成功后的处理
    handleSuccessTop(response, file, fileList) {
      let key = response.key;
      let name = file.raw.name;
      let size = file.size;
      this.getMediaUpload(null, key, name, size);
    },
    handleSuccessBottom(response, file, fileList) {
      let key = response.key;
      let name = file.raw.name;
      let size = file.size;
      let type = 2;
      this.getMediaUpload(type, key, name, size);
    },
    //请求接口返回图片路径
    getMediaUpload(type, key, name, size) {
      let params = {
        key: key,
        name: name,
        size: size
      };
      getMediaUpload(this, params)
        .then(res => {
          //处理图文照片
          if (null != type) {
            this.handleTextImage(res.url)
          } else {
            //cover处理封面图片
            this.handleCoverImage(res.url)
          }
          this.fileList.push(res);
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    handleTextImage(url) {
      this.text_image_list[this.index].image_url = url
      this.text_image_list[this.index].isUpload = false
    },
    handleCoverImage(url) {
      this.abstract.icon_url_list[0] = url
      this.leftDetail.icon_url = url;
    },
    logTimeChange(val) {
      this.leftDetail.startDate = val[0]
      this.leftDetail.endDate = val[1]
      this.leftDetail.formatStartDate = this.dateFormat(val[0], 'yyyy.MM.dd')
      this.leftDetail.formatEndDate = this.dateFormat(val[1], 'yyyy.MM.dd')
      this.begin_timestamp = val[0].getTime() / 1000
      this.end_timestamp = val[1].getTime() / 1000
    },
    //添加时间段
    addHourOrMinute() {
      //时段与添加时间数量相同不添加
      if (this.flag === this.beginToEndHourMinute.length) {
        alert('最多只可添加' + this.flag + '个')
        return;
      }
      var hourMinute = { beginTime: '', endTime: '' }
      this.beginToEndHourMinute.push(hourMinute)
    },
    //删除时间段
    deleteHourOrMinute() {
      if (this.beginToEndHourMinute.length > 0) {
        this.beginToEndHourMinute.splice(this.beginToEndHourMinute.length - 1, 1)
      }
    },
    //添加可用时段
    addTimeLimit(index, item) {
      if (this.saveTimeLimit[index] === undefined) {
        this.saveTimeLimit[index] = item;
        this.flag++
      } else {
        //取消复选框删除
        this.saveTimeLimit[index] = undefined;
        if (this.beginToEndHourMinute.length === this.flag) {
          this.beginToEndHourMinute.splice(this.beginToEndHourMinute.length - 1, 1)
        }
        this.flag--
      }
      this.changeTimeSegment()
    },
    //添加文本图片
    addTextImageList() {
      let text_image = {
        "image_url": "",
        "text": "",
        "flag": true,
        "isUpload": true
      }
      this.text_image_list.push(text_image);
    },
    //时间格式化
    dateFormat(date, fmt) {
      if (null === date) {
        return ''
      }
      let o = {
        'M+': date.getMonth() + 1,
        'd+': date.getDate(),
        'h+': date.getHours(),
        'm+': date.getMinutes(),
        's+': date.getSeconds(),
        'q+': Math.floor((date.getMonth() + 3) / 3),
        S: date.getMilliseconds()
      }
      if (/(y+)/.test(fmt))
        fmt = fmt.replace(
          RegExp.$1,
          (date.getFullYear() + '').substr(4 - RegExp.$1.length)
        )
      for (let k in o)
        if (new RegExp('(' + k + ')').test(fmt))
          fmt = fmt.replace(
            RegExp.$1,
            RegExp.$1.length == 1 ? o[k] : ('00' + o[k]).substr(('' + o[k]).length)
          )
      return fmt
    },
    changeColor(item) {
      this.leftDetail.color = item.style;
      this.base_info.color = item.color;
      this.inputShow = false;
      this.colorListShow = false;
    },
    next() {
      if (!this.validate()) {
        //alert("校验不通过")
        return
      }
      //处理提交的数据
      let card = this.dataHandle()
      //更新
      if (this.card_id != undefined) {
        this.$router.push({
          name: "微信卡券使用设置",
          params: { 'card': card, 'card_type': this.card_type, 'card_id': this.card_id }
        })
      }
      //新增
      else {
        this.$router.push({
          name: "微信卡券使用设置",
          params: { 'card': card, 'card_type': this.card_type }
        })
      }
    },
    upload() {
    },
    confirme(index) {
      this.text_image_list[index].flag = false
    },
    cancel(index) {
      this.text_image_list.splice(index, 1)
    },
    //时间戳转化为日期
    formatDateTime(timeStamp) {
      var date = new Date()
      date.setTime(timeStamp * 1000)
      var y = date.getFullYear()
      var m = date.getMonth() + 1
      m = m < 10 ? ('0' + m) : m
      var d = date.getDate()
      d = d < 10 ? ('0' + d) : d
      var h = date.getHours()
      h = h < 10 ? ('0' + h) : h
      var minute = date.getMinutes()
      var second = date.getSeconds()
      minute = minute < 10 ? ('0' + minute) : minute
      second = second < 10 ? ('0' + second) : second
      return y + '-' + m + '-' + d
    },
    //字符长度
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
      let flag = true
      let reg = /^[0-9]*$/
      let reg1 = /^0\.([1-9]|\d[1-9])$|^[1-9]\d{0,8}\.\d{0,2}$|^[1-9]\d{0,8}$/
      this.submitCheck.color = false,//颜色
        this.submitCheck.titleCheck = false,//标题检查
        this.submitCheck.dateCheck = false,  //日期
        this.submitCheck.uploadCheck = false,   //上传  
        this.submitCheck.introductionCheck = false, //封面简介   
        this.submitCheck.privilegeCheck = false,  //优惠说明      
        this.submitCheck.discountAmount = false, //折扣额度      
        this.submitCheck.commodity = false, //适用商品       
        this.submitCheck.noCommodity = false, //不适用商品        
        this.submitCheck.consumptionAmount = false,//消费        
        this.submitCheck.ConsumptionAmount = false,//消费2        
        this.submitCheck.creditAmount = false,//减免金额        
        this.submitCheck.graphicIntroduction = false,//图文介绍
        this.submitCheck.discountsShare = false //优惠共享
      //颜色校验
      if (this.base_info.color === null) {
        //alert("1")
        //显示提醒颜色的提示语
        this.submitCheck.color = true
        flag = false
      }
      if (this.abstract.icon_url_list.length === 0) {
        //alert("2")
        this.submitCheck.uploadCheck = true
        flag = false
      }
      //标题base_info.title
      if ((this.card_type !== 'CASH') && (this.base_info.title === '' || this.base_info.title === null || this.characterLength(this.base_info.title) > 18)) {
        // alert("3")
        this.submitCheck.titleCheck = true
        flag = false
      }
      //日期
      if (this.date_info.type === 'DATE_TYPE_FIX_TIME_RANGE') {
        if (this.begin_timestamp === null || this.end_timestamp === null) {
          // alert("4")
          this.submitCheck.dateCheck = true
          flag = false
        }
      }
      //上传图片
      if (this.abstract.icon_url_list.length === 0) {
        //alert("5")
        this.submitCheck.uploadCheck = true
        flag = false
      }
      //封面简介
      if (this.abstract.abstract === '' || this.abstract.abstract === null || this.characterLength(this.abstract.abstract) >= 24) {
        //alert("6")
        this.submitCheck.introductionCheck = true
        flag = false
      }
      //图文介绍
      for (let i = 0; i < this.text_image_list.length; i++) {
        if (this.text_image_list[i].image_url === '' || this.text_image_list[i].image_url === null ||
          this.text_image_list[i].text === '' || this.text_image_list[i].text === null) {
          //alert("7")
          this.submitCheck.graphicIntroduction = true
          flag = false
          break
        }
      }
      //优惠共享 
      if (this.use_condition.can_use_with_other_discount === null || this.use_condition.can_use_with_other_discount === '') {
        // alert("8")
        this.submitCheck.discountsShare = true
        flag = false
      }
      if (this.card_type === 'GROUPON') {
        if (this.detail === '' || this.detail === null || this.characterLength(this.detail) > 600) {
          this.submitCheck.privilegeCheck = true
          flag = false
        }
      }
      //代金券
      if (this.card_type === 'CASH') {
        if (this.reduce_cost === null || this.reduce_cost === '' || this.reduce_cost <= 0.01 || !reg1.test(this.reduce_cost)) {
          // alert("9")
          this.submitCheck.creditAmount = true
          flag = false
        }
        if (this.cashChecked) {
          if (this.least_cost === null || this.least_cost === '' || this.least_cost <= 0 || !reg1.test(this.least_cost)) {
            //alert("10")
            this.submitCheck.consumptionAmount = true
            flag = false
          }
        }
        if (this.cashOrDiscountchecked) {
          if (this.use_condition.accept_category === null ||
            this.use_condition.accept_category === '') {
            //alert("11")
            this.submitCheck.commodity = true
            flag = false
          } if (
            this.use_condition.reject_category === null ||
            this.use_condition.reject_category === '') {
            // alert("12")
            this.submitCheck.noCommodity = true
            flag = false
          }
        }
      }
      //折扣券
      if (this.card_type === 'DISCOUNT') {
        if (this.cashOrDiscountchecked) {
          if (this.use_condition.accept_category === null ||
            this.use_condition.accept_category === '') {
            this.submitCheck.commodity = true
            flag = false
          } if (
            this.use_condition.reject_category === null ||
            this.use_condition.reject_category === '') {
            this.submitCheck.noCommodity = true
            flag = false
          }
        }
        if (this.discount === null || this.discount === '' || !reg1.test(this.discount) || this.discount <= 0 || this.discount > 10) {
          this.submitCheck.discountAmount = true
          flag = false
        }
      }
      //兑换券
      if (this.card_type === 'GIFT') {
        if (this.giftChecked) {
          if (this.gift === '' || this.gift === null || this.gift <= 0 || !reg1.test(this.gift)) {
            this.submitCheck.ConsumptionAmount = true
            flag = false
          }
        }
      }
      //优惠券
      if (this.card_type === 'GENERAL_COUPON') {
        if (this.detail === '' || this.detail === null || this.characterLength(this.detail) > 600) {
          this.submitCheck.privilegeCheck = true
          flag = false
        }
      }
      return flag
    }
  },
  beforeRouteEnter(to, from, nextTo) {
    if (from.name === '微信卡券配置' && to.name === '微信卡券新增') {
      let isRefresh = sessionStorage.getItem('isRefresh')
      if (isRefresh === '0') {
        sessionStorage.setItem('isRefresh', null)
        window.location.reload()
      } else {
        sessionStorage.setItem('isRefresh', 0)
      }
    }
    nextTo();
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
      padding: 0 20px;
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
        .use-button {
          text-align: center;
          margin: 0 auto;
          span {
            width: 80px;
            line-height: 25px;
            border-radius: 8px;
            display: inline-block;
            color: #000;
          }
        }
        .tickMsg {
          width: 100%;
          margin: 0 auto;
          text-align: center;
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
      .colorList-input {
        width: 200px;
        height: 30px;
        border: 1px solid #ccc;
        border-radius: 5px;
        position: relative;

        a {
          width: 20px;
          height: 20px;
          display: inline-block;
          margin: 0 10px;
          position: absolute;
          left: 5px;
          top: 5px;
          z-index: 3;
        }
        input {
          width: 150px;
          height: 20px;
          background: #f6f6f8;
          display: inline-block;
          position: absolute;
          left: 5px;
          top: 5px;
        }
      }
      .addDateTime {
        line-height: 30px;
        padding: 0 10px;
        font-size: 14px;
        color: #000;
        margin-left: 12px;
        .time {
          display: inline-block;
          color: #409eff;
          font-size: 14px;
          margin: 0 10px 0 0;
        }
        span {
          margin: 0;
        }
      }
      .addInput {
        .dataRange {
          display: inline-block;
        }
        span {
          font-size: 14px;
          margin: 0;
        }
        .to {
          margin: 0 5px;
          font-size: 14px;
        }
      }
      .check-data {
        font-size: 14px;
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
        width: 165px;
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
      .uploadImg {
        margin-top: 15px;
      }
      .message-box {
        font-size: 12px;
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
      .confirm {
        position: relative;
        input {
          display: inline-block;
          width: 100px;
          opacity: 0;
        }
        .upload-confirm {
          width: 100px;
          line-height: 30px;
          position: absolute;
          left: 0;
          top: 1px;
          background: #67c23a;
          color: #fff;
          text-align: center;
          border-radius: 5px;
        }
      }
      .article-item-t {
        width: 350px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        input {
          display: inline-block;
          width: 350px;
          height: 50px;
          opacity: 0;
          position: absolute;
        }
        .upload-button {
          width: 350px;
          line-height: 50px;
          border: 1px solid #8d8d8d;
          margin: 0px auto;
          text-align: center;
        }
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
