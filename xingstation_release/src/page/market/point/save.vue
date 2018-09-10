<template>
  <div 
    class="item-wrap-template">
    <div 
      class="topbar">
      <el-breadcrumb 
        separator="/">
        <el-breadcrumb-item 
          :to="{ path: '/market/point' }">点位管理</el-breadcrumb-item>
        <el-breadcrumb-item>{{ pointID ? '修改' : '添加' }}</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div 
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText" 
      class="pane">
      <div 
        class="pane-title">
        {{ pointID ? '修改场地' : '新建场地' }}
      </div>
      <el-form
        ref="pointForm"
        :model="pointForm"
        :rules="rules"
        label-width="150px">
        <el-tabs 
          v-model="activeName" 
          type="card">
          <el-tab-pane label="点位配置" name="first">
            <el-form-item 
              label="点位名称" 
              prop="name" >
              <el-input 
                v-model="pointForm.name" 
                placeholder="请输入点位名称" 
                class="item-input"/>
            </el-form-item>
            <el-form-item 
              label="区域" 
              prop="area_id" >
              <el-select 
                v-model="pointForm.area_id" 
                placeholder="请选择" 
                filterable 
                clearable
                @change="areaHandle">
                <el-option
                  v-for="item in areaList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"/>
              </el-select>
            </el-form-item>
            <el-form-item 
              label="场地" 
              prop="site_id" >
              <el-select 
                v-model="pointForm.site_id" 
                placeholder="请选择"
                :remote-method="getMarket"
                :loading="searchLoading" 
                filterable
                remote 
                clearable>
                <el-option
                  v-for="item in siteList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"/>
              </el-select>
            </el-form-item>
          </el-tab-pane>
          <el-tab-pane label="合约配置" name="second">
            <el-form-item 
              label="点位类型" 
              prop="contract.type" >
              <el-radio-group v-model="pointForm.contract.type" >
                <el-radio 
                  v-for="item in typeList"
                  :label="item.id"  
                  :key="item.id">{{ item.name }}</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item 
              label="合同" 
              prop="contract.contract" >
              <el-radio  
                v-model="pointForm.contract.contract" 
                :label="0" >无</el-radio>
              <el-radio  
                v-model="pointForm.contract" 
                :label="1" >有</el-radio>
            </el-form-item>
            <el-form-item 
              label="合同公司" 
              prop="contract.contract_company" >
              <el-input 
                v-model="pointForm.contract.contract_company" 
                placeholder="请输入合同公司" 
                class="item-input"/>
            </el-form-item>
            <el-form-item 
              label="合同编号" 
              prop="contract.contract_num" >
              <el-input 
                v-model="pointForm.contract.contract_num" 
                placeholder="请输入合同编号" 
                class="item-input"/>
            </el-form-item>
            <el-form-item 
              label="合同联系人" 
              prop="contract.contract_user" >
              <el-input 
                v-model="pointForm.contract.contract_user" 
                placeholder="请输入合同联系人" 
                class="item-input"/>
            </el-form-item>
            <el-form-item 
              label="联系方式" 
              prop="contract.contract_phone" >
              <el-input 
                v-model="pointForm.contract.contract_phone" 
                placeholder="请输入联系方式" 
                class="item-input"/>
            </el-form-item>
            <el-form-item 
              label="租金" 
              prop="contract.pay" >
              <el-input 
                v-model="pointForm.contract.pay" 
                placeholder="请输入租金" 
                class="item-input">
                <template slot="append">元／年</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="合同开始时间" 
              prop="contract.enter_sdate">
              <el-date-picker
                v-model="pointForm.contract.enter_sdate"
                type="date"
                placeholder="选择日期"
                class="coupon-form-date"
                value-format="yyyy-MM-dd HH:mm:ss"/>
            </el-form-item>
            <el-form-item 
              label="合同结束时间"
              prop="contract.enter_edate">
              <el-date-picker
                v-model="pointForm.contract.enter_edate"
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
                v-model="pointForm.contract.oper_sdate"
                type="date"
                placeholder="选择日期"
                class="coupon-form-date"
                value-format="yyyy-MM-dd HH:mm:ss"/>
            </el-form-item>
            <el-form-item 
              label="实际入驻结束时间"
              prop="contract.oper_edate">
              <el-date-picker
                v-model="pointForm.contract.oper_edate"
                type="date"
                placeholder="选择日期"
                class="coupon-form-date"
                value-format="yyyy-MM-dd HH:mm:ss"
              />
            </el-form-item>
            <el-form-item 
              label="合约模式" 
              prop="contract.mode" >
              <el-radio-group 
                v-model="pointForm.contract.mode" 
                @change="modeHandle">
                <el-radio 
                  v-for="item in modeList"
                  :label="item.id"  
                  :key="item.id">{{ item.name }}</el-radio>
              </el-radio-group>
            </el-form-item>
            <div v-if="modeNone">
              <el-form-item 
                v-if="modeFlag"
                label="A类广告分成" 
                prop="contract.ad_istar" >
                <el-input 
                  v-model="pointForm.contract.ad_istar" 
                  placeholder="请输入A类广告分成" 
                  class="item-input">
                  <template slot="append">%(星视度引入)</template>
                </el-input>
              </el-form-item>
              <el-form-item 
                v-if="modeFlag"
                label="B类广告分成" 
                prop="contract.ad_ads" >
                <el-input 
                  v-model="pointForm.contract.ad_ads" 
                  placeholder="请输入B类广告分成" 
                  class="item-input">
                  <template slot="append">%(非星视度引入)</template>
                </el-input>
              </el-form-item>
              <el-form-item
                v-if="!modeFlag" 
                label="置换节目数量" 
                prop="contract.exchange_num" >
                <el-input 
                  v-model="pointForm.contract.exchange_num" 
                  placeholder="请输入置换节目数量" 
                  class="item-input">
                  <template slot="append">套</template>
                </el-input>
              </el-form-item>
            </div>
          </el-tab-pane>
          <el-tab-pane label="共享配置" name="third">
            <el-form-item 
              label="点位权限" 
              prop="permission" >
              <el-checkbox-group v-model="pointForm.permission" >
                <el-checkbox 
                  v-for="item in permissionList"
                  :label="item.id"  
                  :key="item.id">{{ item.name }}</el-checkbox>
              </el-checkbox-group>
            </el-form-item>
            <el-form-item 
              label="报刊价" 
              prop="share.offer" >
              <el-input 
                v-model="pointForm.share.offer" 
                placeholder="请输入报刊价" 
                class="item-input">
                <template slot="append">
                  <el-tooltip  
                    effect="dark" 
                    content="每个屏每次的价钱" 
                    placement="right"
                    class="item">
                    <div>
                      ¥／分
                    </div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="报刊价系数" 
              prop="share.offer_off" >
              <el-input 
                v-model="pointForm.share.offer_off" 
                placeholder="请输入报刊价系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="曝光价" 
              prop="share.mad" >
              <el-input 
                v-model="pointForm.share.mad" 
                placeholder="请输入曝光价" 
                class="item-input">
                <template slot="append">
                  <el-tooltip  
                    effect="dark" 
                    content="每个屏每次的价钱" 
                    placement="right"
                    class="item">
                    <div>
                      ¥／分
                    </div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="曝光价系数" 
              prop="share.mad_off" >
              <el-input 
                v-model="pointForm.share.mad_off" 
                placeholder="请输入曝光价系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="冠名价" 
              prop="share.play" >
              <el-input 
                v-model="pointForm.share.play" 
                placeholder="请输入冠名价" 
                class="item-input">
                  <template slot="append">
                    <el-tooltip  
                      effect="dark" 
                      content="每个屏每次的价钱" 
                      placement="right"
                      class="item">
                      <div>
                        ¥／分
                      </div>
                    </el-tooltip>
                  </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="冠名价系数" 
              prop="share.play_off" >
              <el-input 
                v-model="pointForm.share.play_off" 
                placeholder="请输入冠名价系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="链接跳转" 
              prop="share.qrcode" >
              <el-input 
                v-model="pointForm.share.qrcode" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">
                  <el-tooltip  
                    effect="dark" 
                    content="每个场地每天的价钱" 
                    placement="right"
                    class="item">
                    <div>
                      ¥／分
                    </div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="链接跳转系数" 
              prop="share.qrcode_off" >
              <el-input 
                v-model="pointForm.share.qrcode_off" 
                placeholder="请输入链接跳转系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="订阅/公众号" 
              prop="share.wx_pa" >
              <el-input 
                v-model="pointForm.share.wx_pa" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">
                  <el-tooltip  
                    effect="dark" 
                    content="每个场地每天的价钱" 
                    placement="right"
                    class="item">
                    <div>
                      ¥／分
                    </div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="订阅/公众号系数" 
              prop="share.wx_pa_off" >
              <el-input 
                v-model="pointForm.share.wx_pa_off" 
                placeholder="请输入订阅/公众号系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="小程序" 
              prop="share.wx_mp" >
              <el-input 
                v-model="pointForm.share.wx_mp" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">
                  <el-tooltip  
                    effect="dark" 
                    content="每个场地每天的价钱" 
                    placement="right"
                    class="item">
                    <div>
                      ¥／分
                    </div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="小程序系数" 
              prop="share.wx_mp_off" >
              <el-input 
                v-model="pointForm.share.wx_mp_off" 
                placeholder="请输入小程序系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="APP下载" 
              prop="share.app" >
              <el-input 
                v-model="pointForm.share.app" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">
                  <el-tooltip  
                    effect="dark" 
                    content="每个场地每天的价钱" 
                    placement="right"
                    class="item">
                    <div>
                      ¥／分
                    </div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item><el-form-item 
              label="APP系数" 
              prop="share.app_off" >
              <el-input 
                v-model="pointForm.share.app_off" 
                placeholder="请输入APP系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="手机号提取" 
              prop="share.phone" >
              <el-input 
                v-model="pointForm.share.phone" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">
                  <el-tooltip  
                    effect="dark" 
                    content="每个场地每天的价钱" 
                    placement="right"
                    class="item">
                    <div>
                      ¥／分
                    </div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="手机号系数" 
              prop="share.phone_off" >
              <el-input 
                v-model="pointForm.share.phone_off" 
                placeholder="请输入手机号系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="优惠券" 
              prop="share.coupon" >
              <el-input 
                v-model="pointForm.share.coupon" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">
                  <el-tooltip  
                    effect="dark" 
                    content="每个场地每天的价钱" 
                    placement="right"
                    class="item">
                    <div>
                      ¥／分
                    </div>
                  </el-tooltip>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="卷系数" 
              prop="share.coupon_off" >
              <el-input 
                v-model="pointForm.share.coupon_off" 
                placeholder="请输入卷系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
          </el-tab-pane>
        </el-tabs>
        <el-form-item>
          <el-button 
            type="primary"
            @click="submit('pointForm')">保存</el-button>
          <el-button 
            @click="historyBack" >返回</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import search from 'service/search'
import market from 'service/market'
import router from 'router'

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
  Checkbox,
  CheckboxGroup
} from 'element-ui'

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
    ElCheckbox: Checkbox
  },
  data() {
    let checkEnterEndDate = (rule, value, callback) => {
      if (!value) {
        return callback(new Error('合同结束时间不能为空'))
      }
      if (
        new Date(value).getTime() <
        new Date(this.pointForm.enter_sdate).getTime()
      ) {
        callback(new Error('结束日期要大于开始日期'))
      } else {
        callback()
      }
    }
    let checkOperEndDate = (rule, value, callback) => {
      if (!value) {
        return callback(new Error('实际入驻结束时间不能为空'))
      }
      if (
        new Date(value).getTime() <
        new Date(this.pointForm.oper_edate).getTime()
      ) {
        callback(new Error('结束日期要大于开始日期'))
      } else {
        callback()
      }
    }
    let checkNumber = (rule, value, callback) => {
      if (value < 0) {
        callback(new Error('不能小于0'))
      } else {
        callback()
      }
    }
    return {
      modeNone: false,
      modeFlag: true,
      searchLoading: false,
      modeList: [
        {
          id: 'part',
          name: '分成'
        },
        {
          id: 'exchange',
          name: '置换'
        },
        {
          id: 'none',
          name: '无要求'
        }
      ],
      permissionList: [
        {
          id: 0,
          name: '代理'
        },
        {
          id: 1,
          name: '场地主'
        },
        {
          id: 2,
          name: '广告主'
        },
        {
          id: 3,
          name: 'VIP广告主'
        }
      ],
      typeList: [
        {
          id: 'sell',
          name: '出售'
        },
        {
          id: 'lease',
          name: '租借'
        },
        {
          id: 'activity',
          name: '活动'
        },
        {
          id: 'agent',
          name: '代理'
        },
        {
          id: 'tmp',
          name: '过桥'
        },
        {
          id: 'free',
          name: '免费入驻'
        },
        {
          id: 'pay',
          name: '付费入驻'
        }
      ],
      activeName: 'first',
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: '拼命加载中'
      },
      siteList: [],
      pointID: '',
      pointForm: {
        site_id: '',
        area_id: '',
        name: '',
        contract: {
          type: 'free',
          contract: 1,
          contract_company: '',
          contract_num: '',
          contract_user: '',
          contract_phone: '',
          pay: 0,
          enter_sdate: '',
          enter_edate: '',
          oper_sdate: '',
          oper_edate: '',
          mode: 'none',
          ad_istar: 5,
          ad_ads: 5,
          exchange_num: 0
        },
        permission: [1],
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
        }
      },
      areaList: [],
      rules: {
        name: [{ required: true, message: '请输入名称', trigger: 'submit' }],
        area_id: [{ required: true, message: '请选择区域', trigger: 'submit' }],
        site_id: [{ required: true, message: '请选择场地', trigger: 'submit' }],
        'contract.type': [
          { required: true, message: '请选择场地类型', trigger: 'submit' }
        ],
        'contract.contract': [
          { required: true, message: '请选择是否有无合同', trigger: 'submit' }
        ],
        'contract.pay': [
          { required: true, message: '请输入金额', trigger: 'submit' },
          { type: 'number', message: '金额必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'contract.enter_sdate': [
          { required: true, message: '请选择合同开始时间', trigger: 'submit' }
        ],
        'contract.enter_edate': [
          { required: true, validator: checkEnterEndDate, trigger: 'submit' }
        ],
        'contract.oper_sdate': [
          { required: true, message: '请选择入驻开始时间', trigger: 'submit' }
        ],
        'contract.oper_edate': [
          { required: true, validator: checkOperEndDate, trigger: 'submit' }
        ],
        'contract.mode': [
          { required: true, message: '请选择合作模式', trigger: 'submit' }
        ],
        'contract.ad_istar': [
          { required: true, message: '请输入A类广告分成', trigger: 'submit' },
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'contract.ad_ads': [
          { required: true, message: '请输入B类广告分成', trigger: 'submit' },
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'contract.exchange_num': [
          { required: true, message: '请输入置换节目数量', trigger: 'submit' },
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        permission: [
          {
            required: true,
            message: '请选择场地权限',
            trigger: 'submit',
            type: 'array'
          }
        ],
        'share.offer': [
          { required: true, message: '请输入报刊价', trigger: 'submit' },
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.offer_off': [
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.mad': [
          { required: true, message: '请输入曝光价', trigger: 'submit' },
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.mad_off': [
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.play': [
          { required: true, message: '请输入冠名价', trigger: 'submit' },
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.play_off': [
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.qrcode': [
          { required: true, message: '请输入', trigger: 'submit' },
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.qrcode_off': [
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.wx_pa': [
          { required: true, message: '请输入', trigger: 'submit' },
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.wx_pa_off': [
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.wx_mp': [
          { required: true, message: '请输入', trigger: 'submit' },
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.wx_mp_off': [
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.app': [
          { required: true, message: '请输入', trigger: 'submit' },
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.app_off': [
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.phone': [
          { required: true, message: '请输入', trigger: 'submit' },
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.phone_off': [
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.coupon': [
          { required: true, message: '请输入', trigger: 'submit' },
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ],
        'share.coupon_off': [
          { type: 'number', message: '必须为数字', trigger: 'submit' },
          { validator: checkNumber, trigger: 'submit' }
        ]
      }
    }
  },
  created() {
    this.pointID = this.$route.params.uid
    if (this.pointID) {
      this.getPointDetail()
    }
    this.getAreaList()
  },
  methods: {
    getPointDetail() {
      this.setting.loading = true
      let id = this.pointID
      let args = {
        include: 'share,contract,area,market'
      }
      market
        .getPointDetail(this, args, id)
        .then(res => {
          this.pointForm.name = res.name
          this.pointForm.area_id = res.area.id
          this.pointForm.site_id = res.market.id
          this.getMarket()
          if (res.contract) {
            this.pointForm.contract = res.contract
          }
          if (res.share) {
            this.pointForm.permission = []
            this.pointForm.share = res.share
            if (
              res.share.site === 0 &&
              res.share.vipad === 0 &&
              res.share.ad === 0 &&
              res.share.agent === 0
            ) {
              this.pointForm.permission = []
            } else {
              if (res.share.site === 1) {
                this.pointForm.permission.push(1)
              }
              if (res.share.vipad === 1) {
                this.pointForm.permission.push(3)
              }
              if (res.share.ad === 1) {
                this.pointForm.permission.push(2)
              }
              if (res.share.agent === 1) {
                this.pointForm.permission.push(0)
              }
            }
          }
          this.setting.loading = false
        })
        .catch(err => {
          console.log(err)
          this.setting.loading = false
        })
    },
    getAreaList() {
      return search
        .getAeraList(this)
        .then(res => {
          this.areaList = res.data
        })
        .catch(error => {
          console.log(error)
        })
    },
    areaHandle() {
      this.pointForm.site_id = ''
      this.getMarket(this.pointForm.site_id)
    },
    getMarket(query) {
      this.searchLoading = true
      let args = {
        name: query,
        include: 'area',
        area_id: this.pointForm.area_id
      }
      return search
        .getMarketList(this, args)
        .then(response => {
          this.siteList = response.data
          if (this.siteList.length == 0) {
            this.pointForm.site_id = ''
            this.pointForm.siteList = []
          }
          this.searchLoading = false
        })
        .catch(err => {
          console.log(err)
          this.searchLoading = false
        })
    },
    modeHandle() {
      if (this.pointForm.contract.mode === 'none') {
        this.modeNone = false
        this.pointForm.contract.ad_istar = 5
        this.pointForm.contract.ad_ads = 5
        this.pointForm.contract.exchange_num = 0
      } else if (this.pointForm.contract.mode === 'part') {
        this.modeNone = true
        this.modeFlag = true
        this.pointForm.contract.exchange_num = 0
      } else if (this.pointForm.contract.mode === 'exchange') {
        this.modeNone = true
        this.modeFlag = false
        this.pointForm.contract.ad_istar = 5
        this.pointForm.contract.ad_ads = 5
      }
    },
    historyBack() {
      router.back()
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
        } else {
          return
        }
      })
    }
  }
}
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
  }
}
</style>
