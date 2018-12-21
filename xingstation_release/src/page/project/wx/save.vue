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
        :style="linkageColor"
      >
        <div class="item-content-left-card">
          <div class="logo">
            <img src="https://cdn.exe666.com/fe/marketing/img/tiger/icon.png">
          </div>
          <div class="tickMsg">
            <p
              class="title"
              v-if="card_type!=='CASH'"
            >{{base_info.title}}</p>
            <p
              class="title"
              v-if="card_type==='CASH'"
            >{{null===reduce_cost||''===reduce_cost?'代金券标题':reduce_cost+'元代金券'}}</p>
            <div class="use-button"><span :style="linkageColor">使用</span></div>
            <p><span class="title-span">可用时间：</span><span v-if="null!==startDate">{{startDate}}-{{endDate}}</span><span>周一至周日</span></p>
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
            <el-form-item label="商户">
              <img
                class="icon"
                src="https://cdn.exe666.com/fe/marketing/img/tiger/icon.png"
              >
            </el-form-item>
            <el-form-item label="卡券颜色">

              <div
                class="colorList-input"
                @click="()=>{colorListShow=true}"
              >
                <a :style="linkageColor"></a>
                <input
                  v-show="inputShow"
                  type="text"
                  placeholder="请选择颜色"
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
            </el-form-item>
            <el-form-item
              v-if="card_type==='DISCOUNT'"
              :rules="{required: true, message: '折扣额度只能是大于1且小于10的数字', trigger: 'submit'}"
              label="折扣额度"
            >
              <el-input
                v-model="discount"
                class="coupon-form-input"
              />
              <span>折</span>
              <div class="message"> 请填写1-9.9之间的数字，精确到小数点后1位</div>
            </el-form-item>
            <!-- 折扣券标题 -->
            <el-form-item
              v-if="card_type==='DISCOUNT'"
              :rules="{required: true, message: '卡券名称不能为空且长度不超过9个汉字或18个英文字母', trigger: 'submit'}"
              label="折扣券标题"
            >
              <el-input
                v-model="base_info.title"
                class="coupon-form-input"
                style="width:250px"
              />
              <div class="message"> 建议填写折扣券“折扣额度”及自定义内容，描述卡券提供的具体优惠</div>
            </el-form-item>
            <!-- 兑换券 -->
            <el-form-item
              v-if="card_type==='GIFT'"
              :rules="{required: true, message: '卡券名称不能为空且长度不超过9个汉字或18个英文字母', trigger: 'submit'}"
              label="兑换券标题"
            >
              <el-input
                v-model="base_info.title"
                class="coupon-form-input"
                style="width:250px"
              />
              <div class="message"> 建议填写兑换券提供的服务或礼品名称，描述卡券提供的具体优惠</div>
            </el-form-item>
            <!-- 优惠券券 -->
            <el-form-item
              v-if="card_type==='GENERAL_COUPON'"
              :rules="{required: true, message: '卡券名称不能为空且长度不超过9个汉字或18个英文字母', trigger: 'submit'}"
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
              :rules="{required: true, message: '卡券名称不能为空且长度不超过9个汉字或18个英文字母', trigger: 'submit'}"
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
              :rules="{required: true, message: '折扣额度只能是大于1且小于10的数字', trigger: 'submit'}"
              label="减免金额"
            >
              <el-input
                v-model="reduce_cost"
                class="coupon-form-input"
              />
              <span>元</span>
            </el-form-item>
            <el-form-item label="有效期">
              <el-radio-group v-model="base_info.date_info.type">
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
                  <el-radio :label="dateType.TERM">领取后，{{base_info.fixed_begin_term}}-{{base_info.fixed_term}}
                    <el-select
                      v-model="base_info.fixed_begin_term"
                      placeholder="当天"
                      class="coupon-form-select"
                    >
                      <el-option
                        v-for="item in dataList"
                        :key="item.id"
                        :value="item.count"
                        :label="item.title"
                      />
                    </el-select><span class="select">生效,有效天数</span>
                    <el-select
                      v-model="base_info.fixed_term"
                      placeholder="30天"
                      class="coupon-form-select"
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
            </el-form-item>
            <el-form-item label="可用时段">
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
                <div class="addDateTime "><span class="time">添加时间段</span><span class="time">删除时间段</span></div>
                <div class="addInput box-segmentation">
                  <span>时间：</span>
                  <a class="dataRange">
                    <el-input
                      v-model="base_info.title"
                      class="coupon-form-input"
                      style="width:100px"
                    />
                    <span class="to">至</span>
                    <el-input
                      v-model="base_info.title"
                      class="coupon-form-input"
                      style="width:100px"
                    />
                  </a>
                </div>
                <div class="message box-segmentation">请使用24小时制输入时间，格式如11:00至14:30</div>
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
                <el-checkbox v-model="checked">最低消费
                  <span>满
                    <el-input
                      v-model="least_cost"
                      class="coupon-form-input"
                    />元可用</span>
                </el-checkbox>
              </div>

              <!-- 兑换券 -->
              <div v-if="card_type==='GIFT'">
                <el-checkbox v-model="checked">消费
                  <span v-show="checked">
                    <el-select
                      v-model="couponForm"
                      placeholder="金额"
                      class="coupon-form-select"
                    >
                      <el-option
                        v-for="item in dataList"
                        :key="item.id"
                        :value="item.dataTime"
                      />

                    </el-select>
                    <b>满
                      <el-input
                        v-model="couponForm.name"
                        class="coupon-form-input"
                      />元可用</b>
                  </span>
                </el-checkbox>
              </div>

              <!-- 折扣券 -->
              <div v-if="card_type==='CASH'||card_type==='DISCOUNT'">
                <el-checkbox v-model="checked">适用范围　(至少填写一项)</el-checkbox>
                <div
                  class="goods"
                  v-show="checked"
                >
                  <span>适用的商品</span>
                  <el-input
                    v-model="advanced_info.use_condition.accept_category"
                    class="coupon-form-input"
                  />
                  <div class="message-box">填写本券适用的商品、类目或服务</div>
                  <span>不适用的商品</span>
                  <el-input
                    v-model="advanced_info.use_condition.reject_category"
                    class="coupon-form-input"
                  />
                  <div class="message-box">填写本券不适用的商品、类目或服务</div>
                </div>

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
                    :value="item.count"
                    :label="item.share"
                  />
                </el-select>
              </div>

              <div class="message">使用条件的设置会在券上展示，请务必仔细确认。</div>
            </el-form-item>
            <el-form-item
              :rules="{required: true, message: '请上传封面图片', trigger: 'submit'}"
              label="封面图片"
            >
              <div class="message">图片建议尺寸：850像素*350像素，大小不超过2M</div>
              <!-- 上传图片 -->
              <div class="confirm">
                <div class="upload-confirm"><span>上传</span></div>
                <input
                  type="file"
                  accept="image/*"
                  class="camera"
                  @change="upload"
                >
              </div>
              <div class="uploadImg ">
                <img src="https://cdn.exe666.com/fe/marketing/img/tiger/icon.png">
              </div>
            </el-form-item>
            <el-form-item
              :rules="{required: true, message: '简介不能为空且长度不超过12个汉字', trigger: 'submit'}"
              label="封面简介"
            >
              <el-input
                v-model="advanced_info.abstract.abstract"
                placeholder="请输入封面图片的简介内容"
                :maxlength="12"
                class="coupon-form-input"
              />
            </el-form-item>
            <el-form-item
              :rules="{required: true, message: '简介不能为空且长度不超过12个汉字', trigger: 'submit'}"
              label="优惠说明"
            >
              <div class="message">（本行是非自定义内容，无需填写）</div>
              <el-input
                v-if="card_type==='GROUPON'||card_type==='GENERAL_COUPON'"
                type="textarea"
                :maxlength="500"
                placeholder="请输入自定义优惠说明内容"
                v-model="detail"
                class="coupon-form-input"
              />
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
                v-for="(textImage,index) in advanced_info.text_image_list"
                :key="textImage.id"
              >
                <div class="article-item-t">
                  <!-- <img :src="textImage.image_url"> -->
                  <div class="upload-button">上传图片</div>
                  <input
                    type="file"
                    accept="image/*"
                    class="camera"
                    @change="upload"
                  >
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
      inputShow: true,
      colorListShow: false,
      startToEndDate: null,
      startDate: null,
      endDate: null,
      dateType: { RANGE: 'DATE_TYPE_FIX _TIME_RANGE', TERM: 'DATE_TYPE_FIX_TERM' },
      linkageColor: { background: "#f6f6f8" },
      card_type: this.$route.query.card_type,
      detail: '',
      least_cost: null,
      reduce_cost: null,
      gift: '',
      discount: null,
      card_types: {
        GROUPON: {
          "card": {
            "card_type": "GROUPON",
            "groupon": {
              "base_info": {
              },
              "advanced_info": {
              },
              "deal_detail": "示例"
            }
          }
        }, CASH: {
          "card": {
            "card_type": "CASH",
            "cash": {
              "base_info": {
              },
              "advanced_info": {
              },
              "least_cost": 1000,
              "reduce_cost": 100,
            }
          }
        }, GIFT: {
          "card": {
            "card_type": "GIFT",
            "gift": {
              "base_info": {
              },
              "advanced_info": {

              },
              "gift": "可兑换音乐木盒一个"
            }
          }
        }, DISCOUNT: {
          "card": {
            "card_type": "DISCOUNT",
            "discount": {
              "base_info": {
              },
              "advanced_info": {
              },
              "discount": 30
            }
          }
        }, GENERAL_COUPON: {
          "card": {
            "card_type": "GENERAL_COUPON",
            "general_coupon": {
              "base_info": {
              },
              "advanced_info": {
              },
              "default_detail": "优惠券专用，填写优惠详情"
            }
          }
        }      },
      base_info: {
        //true
        "logo_url":
          "http://mmbiz.qpic.cn/mmbiz/iaL1LJM1mF9aRKPZJkmG8xXhiaHqkKSVMMWeN3hLut7X7hicFNjakmxibMLGWpXrEXB33367o7zHN0CwngnQY7zb7g/0",
        "brand_name": "微信餐厅",//true
        "code_type": "CODE_TYPE_TEXT",//true
        "title": "",//true
        "color": "Color010",//true
        "notice": "使用时向服务员出示此券",//true
        "service_phone": "020-88888888",
        //true
        "description": "",
        //true
        "date_info": {
          //true
          "type": 'DATE_TYPE_FIX _TIME_RANGE',
          //true
          "begin_timestamp": 1397577600,
          //true
          "end_timestamp": 1472724261
        },
        //true
        "sku": {
          //true
          "quantity": 500000
        },
        //true
        "fixed_term": null,
        "fixed_begin_term": null,
        "use_limit": 100,
        "get_limit": 3,
        "use_custom_code": false,
        "bind_openid": false,
        "can_share": true,
        "can_give_friend": true
      },
      advanced_info: {
        "use_condition": {
          "accept_category": "鞋类",
          "reject_category": "阿迪达斯",
          "can_use_with_other_discount": true
        },
        "abstract": {
          "abstract": "",
          "icon_url_list": [
            "http://mmbiz.qpic.cn/mmbiz/p98FjXy8LacgHxp3sJ3vn97bGLz0ib0Sfz1bjiaoOYA027iasqSG0sjpiby4vce3AtaPu6cIhBHkt6IjlkY9YnDsfw/0"
          ]
        },
        "text_image_list": [],
        "time_limit": [
          {
            "type": "MONDAY",
            "begin_hour": 0,
            "end_hour": 10,
            "begin_minute": 10,
            "end_minute": 59
          },
          {
            "type": "HOLIDAY"
          }
        ],
        "business_service": [
          "BIZ_SERVICE_FREE_WIFI",
          "BIZ_SERVICE_WITH_PET",
          "BIZ_SERVICE_FREE_PARK",
          "BIZ_SERVICE_DELIVER"
        ]
      },
      loading: true,
      title: '',
      radio: "1",
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
      shareList: [
        {
          id: 0,
          count: 0,
          share: "请选择",
        },
        {
          id: 1,
          count: 1,
          share: "不与其他优惠共享",
        },
        {
          id: 2,
          count: 2,
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

  },
  methods: {
    logTimeChange(val) {
      this.startDate = this.dateFormat(val[0], 'yyyy.MM.dd')
      this.endDate = this.dateFormat(val[1], 'yyyy.MM.dd')
      this.base_info.date_info.begin_timestamp = val[0].getTime() / 1000
      this.base_info.date_info.end_timestamp = val[1].getTime() / 1000
    },
    //添加文本图片
    addTextImageList() {
      let text_image = {
        "image_url": "http://mmbiz.qpic.cn/mmbiz/p98FjXy8LacgHxp3sJ3vn97bGLz0ib0Sfz1bjiaoOYA027iasqSG0sjpiby4vce3AtaPu6cIhBHkt6IjlkY9YnDsfw/0",
        "text": "",
        "flag": true
      }
      this.advanced_info.text_image_list.push(text_image);
    },
    //时间格式化
    dateFormat(date, fmt) {
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
      this.linkageColor = item.style;
      this.base_info.color = item.color;
      this.inputShow = false;
      this.colorListShow = false;
    },
    next() {
      console.log("提交券类型")
      this.$router.push({
        path: "/project/wx_cardpackage/use/"
      });
    },
    upload() {
      console.log("上传")
    },
    confirme(index) {
      this.advanced_info.text_image_list[index].flag = false
    },
    cancel(index) {
      this.advanced_info.text_image_list.splice(index, 1)
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
      .colorList-input {
        width: 200px;
        height: 30px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-left: 10px;
        position: relative;

        a {
          width: 20px;
          height: 20px;
          display: inline-block;
          margin: 0 10px;
          position: absolute;
          left: 5px;
          top: 5px;
          background: rgba(0, 0, 0, 0.5);
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
        width: 150px;
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
          background: #f4f5f9;
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
      // .upload{

      // }
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
