<template>
  <div class="item-wrap-template">
    <div 
      v-loading="setting.loading" 
      :element-loading-text="setting.loadingText" 
      class="pane">
      <div class="pane-title">{{ siteID ? '修改场地' : '新建场地' }}</div>
      <el-form 
        ref="siteForm" 
        :model="siteForm" 
        :rules="rules" 
        label-width="150px">
        <el-tabs 
          v-model="activeName" 
          type="card">
          <el-tab-pane 
            label="场地配置" 
            name="first">
            <el-form-item 
              label="场地名称" 
              prop="name">
              <el-input 
                v-model="siteForm.name" 
                placeholder="请输入场地名称" 
                class="item-input"/>
            </el-form-item>
            <el-form-item 
              label="公司名称" 
              prop="marketConfig.company_id">
              <el-select
                v-model="siteForm.marketConfig.company_id"
                :loading="searchLoading"
                placeholder="请选择公司名称"
                filterable
              >
                <el-option
                  v-for="item in companyList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item 
              label="区域" 
              prop="areaid">
              <el-select 
                v-model="siteForm.areaid" 
                placeholder="请选择" 
                filterable 
                clearable>
                <el-option
                  v-for="item in areaList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item 
              label="场地logo" 
              prop="media_id">
              <div 
                class="avatar-uploader" 
                @click="panelVisible=true">
                <img 
                  v-if="logoUrl" 
                  :src="logoUrl" 
                  class="avatar">
                <i 
                  v-else 
                  class="el-icon-plus avatar-uploader-icon"/>
              </div>
            </el-form-item>
            <el-form-item 
              label="场地电话" 
              prop="marketConfig.phone">
              <el-input
                v-model="siteForm.marketConfig.phone"
                placeholder="请输入场地电话"
                class="item-input"
              />
            </el-form-item>
            <el-form-item 
              label="场地地址" 
              prop="marketConfig.address">
              <el-input
                v-model="siteForm.marketConfig.address"
                placeholder="请输入场地地址"
                class="item-input"
              />
            </el-form-item>
            <el-form-item 
              label="场地详情" 
              prop="marketConfig.description">
              <el-input
                v-model="siteForm.marketConfig.description"
                type="textarea"
                placeholder="请输入场地详情"
                class="item-input"
              />
            </el-form-item>
          </el-tab-pane>
          <el-tab-pane 
            label="合约配置" 
            name="second">
            <el-form-item 
              label="场地类型" 
              prop="contract.type">
              <el-radio-group v-model="siteForm.contract.type">
                <el-radio 
                  v-for="item in typeList" 
                  :label="item.id" 
                  :key="item.id">{{ item.name }}</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item 
              label="合同" 
              prop="contract.contract">
              <el-radio-group 
                v-model="siteForm.contract.contract" 
                @change="handleContract">
                <el-radio :label="0">无</el-radio>
                <el-radio :label="1">有</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item 
              v-if="contractShow" 
              label="合同编号" 
              prop="contract_num">
              <el-select
                v-model="siteForm.contract.contract_num"
                :loading="searchLoading"
                placeholder="请选择合同编号"
                @change="changeContract"
              >
                <el-option
                  v-for="item in contractList"
                  :key="item.contract_number"
                  :label="item.contract_number"
                  :value="item.contract_number"
                />
              </el-select>
            </el-form-item>
            <el-form-item 
              label="合同公司" 
              prop="contract_company">
              <el-input
                v-model="siteForm.contract.contract_company"
                placeholder="请输入合同公司"
                class="item-input"
              />
            </el-form-item>
            <el-form-item 
              label="所属人" 
              prop="bd_user_id">
              <el-select
                v-model="siteForm.marketConfig.bd_user_id"
                :loading="searchLoading"
                filterable
                placeholder="请选择所属人"
              >
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item 
              label="合同联系人" 
              prop="contract_user">
              <el-select
                v-model="siteForm.contract.contract_user"
                :loading="searchLoading"
                filterable
                placeholder="请选择所属人"
                @change="contractUser"
              >
                <el-option
                  v-for="item in customerList"
                  :key="item.name"
                  :label="item.name"
                  :value="item.name"
                />
              </el-select>
            </el-form-item>
            <el-form-item 
              label="联系方式" 
              prop="contract_phone">
              <el-input
                v-model="siteForm.contract.contract_phone"
                placeholder="请输入联系方式"
                class="item-input"
              />
            </el-form-item>
            <el-form-item 
              v-if="payFlag" 
              label="租金" 
              prop="contract.pay">
              <el-input 
                v-model="siteForm.contract.pay" 
                placeholder="请输入租金" 
                class="item-input">
                <template slot="append">元／年</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="合同开始时间" 
              prop="contract.enter_sdate">
              <el-date-picker
                v-model="siteForm.contract.enter_sdate"
                type="date"
                placeholder="选择日期"
                class="coupon-form-date"
                value-format="yyyy-MM-dd HH:mm:ss"
              />
            </el-form-item>
            <el-form-item 
              label="合同结束时间" 
              prop="contract.enter_edate">
              <el-date-picker
                v-model="siteForm.contract.enter_edate"
                type="date"
                placeholder="选择日期"
                class="coupon-form-date"
                value-format="yyyy-MM-dd HH:mm:ss"
              />
            </el-form-item>
            <el-form-item 
              label="实际入驻开始时间" 
              prop="contract.oper_sdate">
              <el-date-picker
                v-model="siteForm.contract.oper_sdate"
                type="date"
                placeholder="选择日期"
                class="coupon-form-date"
                value-format="yyyy-MM-dd HH:mm:ss"
              />
            </el-form-item>
            <el-form-item 
              label="实际入驻结束时间" 
              prop="contract.oper_edate">
              <el-date-picker
                v-model="siteForm.contract.oper_edate"
                type="date"
                placeholder="选择日期"
                class="coupon-form-date"
                value-format="yyyy-MM-dd HH:mm:ss"
              />
            </el-form-item>
            <el-form-item 
              label="合约模式" 
              prop="contract.mode">
              <el-radio-group 
                v-model="siteForm.contract.mode" 
                @change="modeHandle">
                <el-radio 
                  v-for="item in modeList" 
                  :label="item.id" 
                  :key="item.id">{{ item.name }}</el-radio>
              </el-radio-group>
            </el-form-item>
            <div v-show="modeNone">
              <el-form-item 
                v-show="modeFlag" 
                label="A类广告分成" 
                prop="contract.ad_istar">
                <el-input
                  v-model="siteForm.contract.ad_istar"
                  placeholder="请输入A类广告分成"
                  class="item-input"
                >
                  <template slot="append">%(星视度引入)</template>
                </el-input>
              </el-form-item>
              <el-form-item 
                v-show="modeFlag" 
                label="B类广告分成" 
                prop="contract.ad_ads">
                <el-input
                  v-model="siteForm.contract.ad_ads"
                  placeholder="请输入B类广告分成"
                  class="item-input"
                >
                  <template slot="append">%(非星视度引入)</template>
                </el-input>
              </el-form-item>
              <el-form-item 
                v-show="!modeFlag" 
                label="置换节目数量" 
                prop="contract.exchange_num">
                <el-input
                  v-model="siteForm.contract.exchange_num"
                  placeholder="请输入置换节目数量"
                  class="item-input"
                >
                  <template slot="append">套</template>
                </el-input>
              </el-form-item>
            </div>
          </el-tab-pane>
          <el-tab-pane 
            label="共享配置" 
            name="third">
            <el-form-item 
              label="场地权限" 
              prop="permission">
              <el-checkbox-group v-model="siteForm.permission">
                <el-checkbox
                  v-for="item in permissionList"
                  :label="item.id"
                  :key="item.id"
                >{{ item.name }}</el-checkbox>
              </el-checkbox-group>
            </el-form-item>
            <el-form-item 
              label="报刊价" 
              prop="share.offer">
              <el-input 
                v-model="siteForm.share.offer" 
                placeholder="请输入报刊价" 
                class="item-input">
                <template slot="append">
                  <el-tooltip 
                    effect="dark" 
                    content="每个屏幕每次的价钱" 
                    placement="right" 
                    class="item">
                    <div>¥／分</div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="报刊价系数" 
              prop="share.offer_off">
              <el-input
                v-model="siteForm.share.offer_off"
                placeholder="请输入报刊价系数"
                class="item-input"
              >
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="曝光价" 
              prop="share.mad">
              <el-input 
                v-model="siteForm.share.mad" 
                placeholder="请输入曝光价" 
                class="item-input">
                <template slot="append">
                  <el-tooltip 
                    effect="dark" 
                    content="每个屏幕每次的价钱" 
                    placement="right" 
                    class="item">
                    <div>¥／分</div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="曝光价系数" 
              prop="share.mad_off">
              <el-input 
                v-model="siteForm.share.mad_off" 
                placeholder="请输入曝光价系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="冠名价" 
              prop="share.play">
              <el-input 
                v-model="siteForm.share.play" 
                placeholder="请输入冠名价" 
                class="item-input">
                <template slot="append">
                  <el-tooltip 
                    effect="dark" 
                    content="每个屏幕每次的价钱" 
                    placement="right" 
                    class="item">
                    <div>¥／分</div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="冠名价系数" 
              prop="share.play_off">
              <el-input 
                v-model="siteForm.share.play_off" 
                placeholder="请输入冠名价系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="链接跳转" 
              prop="share.qrcode">
              <el-input 
                v-model="siteForm.share.qrcode" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">
                  <el-tooltip 
                    effect="dark" 
                    content="每个场地每天的价钱" 
                    placement="right" 
                    class="item">
                    <div>¥／分</div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="链接跳转系数" 
              prop="share.qrcode_off">
              <el-input
                v-model="siteForm.share.qrcode_off"
                placeholder="请输入链接跳转系数"
                class="item-input"
              >
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="订阅/公众号" 
              prop="share.wx_pa">
              <el-input 
                v-model="siteForm.share.wx_pa" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">
                  <el-tooltip 
                    effect="dark" 
                    content="每个场地每天的价钱" 
                    placement="right" 
                    class="item">
                    <div>¥／分</div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="订阅/公众号系数" 
              prop="share.wx_pa_off">
              <el-input
                v-model="siteForm.share.wx_pa_off"
                placeholder="请输入订阅/公众号系数"
                class="item-input"
              >
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="小程序" 
              prop="share.wx_mp">
              <el-input 
                v-model="siteForm.share.wx_mp" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">
                  <el-tooltip 
                    effect="dark" 
                    content="每个场地每天的价钱" 
                    placement="right" 
                    class="item">
                    <div>¥／分</div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="小程序系数" 
              prop="share.wx_mp_off">
              <el-input
                v-model="siteForm.share.wx_mp_off"
                placeholder="请输入小程序系数"
                class="item-input"
              >
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="APP下载" 
              prop="share.app">
              <el-input 
                v-model="siteForm.share.app" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">
                  <el-tooltip 
                    effect="dark" 
                    content="每个场地每天的价钱" 
                    placement="right" 
                    class="item">
                    <div>¥／分</div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="APP系数" 
              prop="share.app_off">
              <el-input 
                v-model="siteForm.share.app_off" 
                placeholder="请输入APP系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="手机号提取" 
              prop="share.phone">
              <el-input 
                v-model="siteForm.share.phone" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">
                  <el-tooltip 
                    effect="dark" 
                    content="每个场地每天的价钱" 
                    placement="right" 
                    class="item">
                    <div>¥／分</div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="手机号系数" 
              prop="share.phone_off">
              <el-input
                v-model="siteForm.share.phone_off"
                placeholder="请输入手机号系数"
                class="item-input"
              >
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="优惠券" 
              prop="share.coupon">
              <el-input 
                v-model="siteForm.share.coupon" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">
                  <el-tooltip 
                    effect="dark" 
                    content="每个场地每天的价钱" 
                    placement="right" 
                    class="item">
                    <div>¥／分</div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="券系数" 
              prop="share.coupon_off">
              <el-input 
                v-model="siteForm.share.coupon_off" 
                placeholder="请输入券系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
          </el-tab-pane>
        </el-tabs>
        <el-form-item>
          <el-button 
            type="primary" 
            @click="submit('siteForm')">保存</el-button>
          <el-button @click="historyBack">返回</el-button>
        </el-form-item>
      </el-form>
    </div>
    <PicturePanel
      :panel-visible.sync="panelVisible"
      :single-flag="singleFlag"
      @close="handleClose"
    />
  </div>
</template>

<script>
import PicturePanel from "components/common/picturePanel";
import {
  getSiteMarketDetail,
  historyBack,
  siteSaveMarket,
  siteModifyMarket,
  getSearchAera,
  getSearchUser,
  getContractReceipt,
  getSearchCompany
} from "service";

import {
  Form,
  Select,
  Option,
  FormItem,
  Button,
  Input,
  DatePicker,
  MessageBox,
  Tabs,
  TabPane,
  RadioGroup,
  Radio,
  Tooltip,
  CheckboxGroup,
  Checkbox
} from "element-ui";

export default {
  components: {
    ElForm: Form,
    ElSelect: Select,
    ElOption: Option,
    ElFormItem: FormItem,
    ElButton: Button,
    ElInput: Input,
    ElDatePicker: DatePicker,
    ElTabs: Tabs,
    ElTabPane: TabPane,
    ElRadioGroup: RadioGroup,
    ElRadio: Radio,
    ElTooltip: Tooltip,
    ElCheckboxGroup: CheckboxGroup,
    ElCheckbox: Checkbox,
    PicturePanel
  },
  data() {
    let checkEnterEndDate = (rule, value, callback) => {
      if (!value) {
        return callback(new Error("合同结束时间不能为空"));
      }
      if (
        new Date(value.replace(/\-/g, "/")).getTime() <
        new Date(
          this.siteForm.contract.enter_sdate.replace(/\-/g, "/")
        ).getTime()
      ) {
        callback(new Error("结束日期要大于开始日期"));
      } else {
        callback();
      }
    };
    let checkOperEndDate = (rule, value, callback) => {
      if (!value) {
        return callback(new Error("入驻结束时间不能为空"));
      }
      if (
        new Date(value.replace(/\-/g, "/")).getTime() <
        new Date(
          this.siteForm.contract.oper_edate.replace(/\-/g, "/")
        ).getTime()
      ) {
        callback(new Error("结束日期要大于开始日期"));
      } else {
        callback();
      }
    };
    let checkNumber = (rule, value, callback) => {
      if (value < 0) {
        callback(new Error("不能小于0"));
      } else if (typeof parseFloat(value) !== "number") {
        callback(new Error("必须是数字"));
      } else {
        callback();
      }
    };
    return {
      panelVisible: false,
      singleFlag: true,
      logoUrl: "",
      searchLoading: false,
      modeNone: false,
      modeFlag: true,
      contractShow: true,
      modeList: [
        {
          id: "part",
          name: "分成"
        },
        {
          id: "exchange",
          name: "置换"
        },
        {
          id: "none",
          name: "无要求"
        }
      ],
      permissionList: [
        {
          id: "agent",
          name: "代理"
        },
        {
          id: "site",
          name: "场地主"
        },
        {
          id: "ad",
          name: "广告主"
        },
        {
          id: "vipad",
          name: "VIP广告主"
        }
      ],
      typeList: [
        {
          id: "sell",
          name: "出售"
        },
        {
          id: "lease",
          name: "租借"
        },
        {
          id: "activity",
          name: "活动"
        },
        {
          id: "agent",
          name: "代理"
        },
        {
          id: "tmp",
          name: "过桥"
        },
        {
          id: "free",
          name: "免费入驻"
        },
        {
          id: "pay",
          name: "付费入驻"
        }
      ],
      activeName: "first",
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      siteID: "",
      passwordShow: false,
      siteForm: {
        areaid: "",
        name: "",
        contract: {
          type: "free",
          contract: 1,
          contract_company: "",
          contract_num: "",
          contract_user: "",
          contract_phone: "",
          pay: 0,
          enter_sdate: "",
          enter_edate: "",
          oper_sdate: "",
          oper_edate: "",
          mode: "none",
          ad_istar: 5,
          ad_ads: 5,
          exchange_num: 0
        },
        permission: ["agent", "site", "ad", "vipad"],
        share: {
          offer: 2000,
          offer_off: 100,
          mad: 50,
          mad_off: 100,
          play: 300,
          play_off: 100,
          qrcode: 1000,
          qrcode_off: 100,
          wx_pa: 1000,
          wx_pa_off: 100,
          wx_mp: 2000,
          wx_mp_off: 100,
          app: 5000,
          app_off: 100,
          phone: 5000,
          phone_off: 100,
          coupon: 2000,
          coupon_off: 100
        },
        marketConfig: {
          bd_user_id: null,
          phone: "",
          address: "",
          description: "",
          company_id: null,
          contract_id: null,
          media_id: null
        }
      },
      contractList: [],
      companyList: [],
      customerList: [],
      userList: [],
      areaList: [],
      rules: {
        name: [{ required: true, message: "请输入名称", trigger: "submit" }],
        "marketConfig.company_id": [
          { required: true, message: "请选择公司", trigger: "submit" }
        ],
        areaid: [{ required: true, message: "请选择区域", trigger: "submit" }],
        "contract.type": [
          { required: true, message: "请选择场地类型", trigger: "submit" }
        ],
        "contract.contract": [
          { required: true, message: "请选择是否有无合同", trigger: "submit" }
        ],
        "contract.pay": [
          { required: true, message: "请输入金额", trigger: "submit" },
          { validator: checkNumber, trigger: "submit" }
        ],
        "contract.enter_sdate": [
          { required: true, message: "请选择合同开始时间", trigger: "submit" }
        ],
        "contract.enter_edate": [
          { required: true, validator: checkEnterEndDate, trigger: "submit" }
        ],
        "contract.oper_sdate": [
          { required: true, message: "请选择入驻开始时间", trigger: "submit" }
        ],
        "contract.oper_edate": [
          { required: true, validator: checkOperEndDate, trigger: "submit" }
        ],
        "contract.mode": [
          { required: true, message: "请选择合作模式", trigger: "submit" }
        ],
        "contract.ad_istar": [
          { required: true, message: "请输入A类广告分成", trigger: "submit" },
          { validator: checkNumber, trigger: "submit" }
        ],
        "contract.ad_ads": [
          { required: true, message: "请输入B类广告分成", trigger: "submit" },
          { validator: checkNumber, trigger: "submit" }
        ],
        "contract.exchange_num": [
          { required: true, message: "请输入置换节目数量", trigger: "submit" },
          { validator: checkNumber, trigger: "submit" }
        ],
        permission: [
          {
            required: true,
            message: "请选择场地权限",
            trigger: "submit",
            type: "array"
          }
        ],
        "share.offer": [
          { required: true, message: "请输入报刊价", trigger: "submit" },
          { validator: checkNumber, trigger: "submit" }
        ],
        "share.offer_off": [{ validator: checkNumber, trigger: "submit" }],
        "share.mad": [
          { required: true, message: "请输入曝光价", trigger: "submit" },
          { validator: checkNumber, trigger: "submit" }
        ],
        "share.mad_off": [{ validator: checkNumber, trigger: "submit" }],
        "share.play": [
          { required: true, message: "请输入冠名价", trigger: "submit" },
          { validator: checkNumber, trigger: "submit" }
        ],
        "share.play_off": [{ validator: checkNumber, trigger: "submit" }],
        "share.qrcode": [
          { required: true, message: "请输入", trigger: "submit" },
          { validator: checkNumber, trigger: "submit" }
        ],
        "share.qrcode_off": [{ validator: checkNumber, trigger: "submit" }],
        "share.wx_pa": [
          { required: true, message: "请输入", trigger: "submit" },
          { validator: checkNumber, trigger: "submit" }
        ],
        "share.wx_pa_off": [{ validator: checkNumber, trigger: "submit" }],
        "share.wx_mp": [
          { required: true, message: "请输入", trigger: "submit" },
          { validator: checkNumber, trigger: "submit" }
        ],
        "share.wx_mp_off": [{ validator: checkNumber, trigger: "submit" }],
        "share.app": [
          { required: true, message: "请输入", trigger: "submit" },
          { validator: checkNumber, trigger: "submit" }
        ],
        "share.app_off": [{ validator: checkNumber, trigger: "submit" }],
        "share.phone": [
          { required: true, message: "请输入", trigger: "submit" },
          { validator: checkNumber, trigger: "submit" }
        ],
        "share.phone_off": [{ validator: checkNumber, trigger: "submit" }],
        "share.coupon": [
          { required: true, message: "请输入", trigger: "submit" },
          { validator: checkNumber, trigger: "submit" }
        ],
        "share.coupon_off": [{ validator: checkNumber, trigger: "submit" }]
      },
      payFlag: false,
      contractInfo: null
    };
  },
  created() {
    this.siteID = this.$route.params.uid;
    this.init();

    let roles = JSON.parse(this.$cookie.get("user_info")).roles.data;
    roles.map(r => {
      if (r.display_name === "管理员") {
        this.payFlag = true;
        return;
      }
    });
  },
  methods: {
    handleClose(data) {
      if (data && data.length > 0) {
        let { media_id, url } = data[0];
        this.siteForm.marketConfig.media_id = media_id;
        this.logoUrl = url;
      } else {
      }
    },
    async init() {
      this.setting.loading = true;
      try {
        
        this.searchLoading = true;
        let args = {
          include: "company.customers"
        };
        let resContractReceipt = await getContractReceipt(this, args);
        this.contractList = resContractReceipt;

        let resUser = await getSearchUser(this);
        this.userList = resUser.data;

        let resArea = await getSearchAera(this);
        this.areaList = resArea.data;

        let resCompany = await getSearchCompany(this);
        this.companyList = resCompany.data;
        if (this.siteID) {
          await this.getMarketDetail();
        }
        this.setting.loading = false;
        this.searchLoading = false;
      } catch (e) {
        this.searchLoading = false;
        this.setting.loading = false;
      }
    },
    contractUser(val) {
      this.customerList.find(item => {
        if (item.name === val) {
          this.siteForm.contract.contract_phone = item.phone;
          return;
        }
      });
    },
    changeContract(val) {
      this.contractList.find(item => {
        if (item.contract_number === val) {
          this.contractInfo = null;
          this.customerList = [];
          this.siteForm.contract.contract_company = "";
          this.contractInfo = item;
          this.siteForm.contract.contract_company = this.contractInfo.company.name;
          this.customerList = this.contractInfo.company.customers.data;
          return;
        }
      });
    },
    handleContract(val) {
      if (val === 1) {
        this.contractShow = true;
      } else {
        this.contractShow = false;
      }
    },
    getMarketDetail() {
      this.setting.loading = true;
      let id = this.siteID;
      let args = {
        include:
          "share,contract,area,marketConfig.company,marketConfig.media,marketConfig.bdUser,marketConfig.adContract,marketConfig.customer"
      };
      getSiteMarketDetail(this, args, id)
        .then(res => {
          this.siteForm.name = res.name;
          this.siteForm.areaid = res.area.id;
          if (res.contract) {
            this.siteForm.contract = res.contract;
            setTimeout(() => {
              this.changeContract(res.contract.contract_num);
              this.contractUser(res.contract.contract_user);
            }, 100);
            if (this.siteForm.contract.contract === 1) {
              this.contractShow = true;
            } else {
              this.contractShow = false;
            }
            if (res.contract.mode === "part") {
              this.modeNone = true;
              this.modeFlag = true;
            } else if (res.contract.mode === "exchange") {
              this.modeNone = true;
              this.modeFlag = false;
            } else {
              this.modeNone = false;
            }
          }
          if (res.share) {
            this.siteForm.share = res.share;
            this.siteForm.permission = [];
            if (
              res.share.site === 0 &&
              res.share.vipad === 0 &&
              res.share.ad === 0 &&
              res.share.agent === 0
            ) {
              this.siteForm.permission = [];
            } else {
              if (res.share.site === 1) {
                this.siteForm.permission.push("site");
              }
              if (res.share.vipad === 1) {
                this.siteForm.permission.push("vipad");
              }
              if (res.share.ad === 1) {
                this.siteForm.permission.push("ad");
              }
              if (res.share.agent === 1) {
                this.siteForm.permission.push("agent");
              }
            }
          }

          if (res.marketConfig) {
            if (res.marketConfig.media) {
              this.siteForm.marketConfig.media_id = res.marketConfig.media.id;
              this.logoUrl = res.marketConfig.media.url;
            }
            if (res.marketConfig.company) {
              this.siteForm.marketConfig.company_id =
                res.marketConfig.company.id;
            }
            if (res.marketConfig.bdUser) {
              this.siteForm.marketConfig.bd_user_id =
                res.marketConfig.bdUser.id;
            }
            if (res.marketConfig.adContract) {
              this.siteForm.marketConfig.contract_id =
                res.marketConfig.adContract.id;
            }
            this.siteForm.marketConfig.phone = res.marketConfig.phone;
            this.siteForm.marketConfig.address = res.marketConfig.address;
            this.siteForm.marketConfig.description =
              res.marketConfig.description;
          }
          this.setting.loading = false;
        })
        .catch(err => {
          console.log(err);
          this.setting.loading = false;
        });
    },
    modeHandle() {
      let { mode } = this.siteForm.contract;
      if (mode === "none") {
        this.modeNone = false;
      } else if (mode === "part") {
        this.modeNone = true;
        this.modeFlag = true;
      } else if (mode === "exchange") {
        this.modeNone = true;
        this.modeFlag = false;
      }
    },
    historyBack() {
      historyBack();
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          delete this.siteForm.contract.date;
          delete this.siteForm.share.date;
          this.siteForm.share.site,
            this.siteForm.share.vipad,
            this.siteForm.share.ad,
            (this.siteForm.share.agent = 0);
          for (let i = 0; i < this.siteForm.permission.length; i++) {
            let value = this.siteForm.permission[i];
            if (value === "site") {
              this.siteForm.share.site = 1;
            }
            if (value === "vipad") {
              this.siteForm.share.vipad = 1;
            }
            if (value === "ad") {
              this.siteForm.share.ad = 1;
            }
            if (value === "agent") {
              this.siteForm.share.agent = 1;
            }
          }

          let args = this.siteForm;
          if (this.siteID) {
            siteModifyMarket(this, args, this.siteID)
              .then(res => {
                this.$message({
                  message: "修改场地成功",
                  type: "success"
                });
                this.historyBack();
              })
              .catch(err => {
                this.$message({
                  type: "warning",
                  message: err.response.data.message
                });
              });
          } else {
            siteSaveMarket(this, args)
              .then(res => {
                this.$message({
                  message: "新建场地成功",
                  type: "success"
                });
                this.historyBack();
              })
              .catch(err => {
                this.$message({
                  type: "warning",
                  message: err.response.data.message
                });
              });
          }
        } else {
          this.$message({
            showClose: true,
            message: "请填写完表单必填项，在提交",
            type: "error",
            duration: 5000
          });
        }
      });
    }
  }
};
</script>

<style lang="less" scoped>
.item-wrap-template {
  .pane {
    border-radius: 5px;
    background-color: white;
    padding: 20px 40px 80px;

    .el-select,
    .item-input,
    .el-date-editor.el-input {
      width: 380px;
    }
    .item-list {
      .program-title {
        color: #555;
        font-size: 14px;
      }
    }
    .pane-title {
      padding-bottom: 20px;
      font-size: 18px;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
    }
    .avatar-uploader {
      width: 178px;
      height: 178px;
      line-height: 178px;
      border: 1px dashed #d9d9d9;
      border-radius: 6px;
    }
    .avatar-uploader .el-upload {
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }
    .avatar-uploader .el-upload:hover {
      border-color: #409eff;
    }
    .avatar-uploader-icon {
      font-size: 28px;
      color: #8c939d;
      width: 178px;
      height: 178px;
      line-height: 178px;
      text-align: center;
    }
    .avatar {
      width: 178px;
      height: 178px;
      display: block;
    }
  }
}
</style>
