<template>
  <div 
    class="item-wrap-template">
    <div 
      class="topbar">
      <el-breadcrumb 
        separator="/">
        <el-breadcrumb-item 
          :to="{ path: '/market/site' }">点位管理</el-breadcrumb-item>
        <el-breadcrumb-item>{{ siteID ? '修改' : '添加' }}</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div 
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText" 
      class="pane">
      <div 
        class="pane-title">
        {{ siteID ? '修改场地' : '新建场地' }}
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
                clearable>
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
                filterable 
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
              prop="type" >
              <el-radio-group v-model="pointForm.type" >
                <el-radio 
                  v-for="item in typeList"
                  :label="item.id"  
                  :key="item.id">{{ item.name }}</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item 
              label="合同" 
              prop="contract" >
              <el-radio  
                v-model="pointForm.contract" 
                :label="0" >无</el-radio>
              <el-radio  
                v-model="pointForm.contract" 
                :label="1" >有</el-radio>
            </el-form-item>
            <el-form-item 
              label="合同公司" 
              prop="contract_company" >
              <el-input 
                v-model="pointForm.contract_company" 
                placeholder="请输入合同公司" 
                class="item-input"/>
            </el-form-item>
            <el-form-item 
              label="合同编号" 
              prop="contract_num" >
              <el-input 
                v-model="pointForm.contract_num" 
                placeholder="请输入合同编号" 
                class="item-input"/>
            </el-form-item>
            <el-form-item 
              label="合同联系人" 
              prop="contract_user" >
              <el-input 
                v-model="pointForm.contract_user" 
                placeholder="请输入合同联系人" 
                class="item-input"/>
            </el-form-item>
            <el-form-item 
              label="联系方式" 
              prop="contract_phone" >
              <el-input 
                v-model="pointForm.contract_phone" 
                placeholder="请输入联系方式" 
                class="item-input"/>
            </el-form-item>
            <el-form-item 
              label="租金" 
              prop="pay" >
              <el-input 
                v-model="pointForm.pay" 
                placeholder="请输入租金" 
                class="item-input">
                <template slot="append">元／年</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="入驻开始时间" 
              prop="enter_sdate">
              <el-date-picker
                v-model="pointForm.enter_sdate"
                type="date"
                placeholder="选择日期"
                class="coupon-form-date"
                value-format="yyyy-MM-dd HH:mm:ss"/>
            </el-form-item>
            <el-form-item 
              label="入驻结束时间"
              prop="enter_edate">
              <el-date-picker
                v-model="pointForm.enter_edate"
                type="date"
                placeholder="选择日期"
                class="coupon-form-date"
                value-format="yyyy-MM-dd HH:mm:ss"
              />
            </el-form-item>
            <el-form-item 
              label="运营开始时间" 
              prop="oper_sdate">
              <el-date-picker
                v-model="pointForm.oper_sdate"
                type="date"
                placeholder="选择日期"
                class="coupon-form-date"
                value-format="yyyy-MM-dd HH:mm:ss"/>
            </el-form-item>
            <el-form-item 
              label="运营结束时间"
              prop="oper_edate">
              <el-date-picker
                v-model="pointForm.oper_edate"
                type="date"
                placeholder="选择日期"
                class="coupon-form-date"
                value-format="yyyy-MM-dd HH:mm:ss"
              />
            </el-form-item>
            <el-form-item 
              label="合约模式" 
              prop="mode" >
              <el-radio-group v-model="pointForm.mode" >
                <el-radio 
                  v-for="item in modeList"
                  :label="item.id"  
                  :key="item.id">{{ item.name }}</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item 
              label="A类广告分成" 
              prop="ad_istar" >
              <el-input 
                v-model="pointForm.ad_istar" 
                placeholder="请输入A类广告分成" 
                class="item-input">
                <template slot="append">%(星视度引入)</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="B类广告分成" 
              prop="ad_ads" >
              <el-input 
                v-model="pointForm.ad_ads" 
                placeholder="请输入B类广告分成" 
                class="item-input">
                <template slot="append">%(非星视度引入)</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="置换节目数量" 
              prop="exchange_num" >
              <el-input 
                v-model="pointForm.exchange_num" 
                placeholder="请输入置换节目数量" 
                class="item-input">
                <template slot="append">套</template>
              </el-input>
            </el-form-item>
          </el-tab-pane>
          <el-tab-pane label="共享配置" name="third">
            <el-form-item 
              label="点位权限" 
              prop="permission" >
              <el-radio-group v-model="pointForm.permission" >
                <el-radio 
                  v-for="item in permissionList"
                  :label="item.id"  
                  :key="item.id">{{ item.name }}</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item 
              label="报刊价" 
              prop="offer" >
              <el-input 
                v-model="pointForm.offer" 
                placeholder="请输入报刊价" 
                class="item-input">
                <template slot="append">分/屏/次</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="报刊价系数" 
              prop="offer_off" >
              <el-input 
                v-model="pointForm.offer_off" 
                placeholder="请输入报刊价系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="曝光价" 
              prop="mad" >
              <el-input 
                v-model="pointForm.mad" 
                placeholder="请输入曝光价" 
                class="item-input">
                <template slot="append">分/屏/次</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="曝光价" 
              prop="mad_off" >
              <el-input 
                v-model="pointForm.mad_off" 
                placeholder="请输入曝光价系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="冠名价" 
              prop="play" >
              <el-input 
                v-model="pointForm.play" 
                placeholder="请输入冠名价" 
                class="item-input">
                <template slot="append">分/屏/次</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="冠名价系数" 
              prop="play_off" >
              <el-input 
                v-model="pointForm.play_off" 
                placeholder="请输入冠名价系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="链接跳转" 
              prop="qrcode" >
              <el-input 
                v-model="pointForm.qrcode" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">分/场地/天</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="链接跳转系数" 
              prop="qrcode_off" >
              <el-input 
                v-model="pointForm.qrcode_off" 
                placeholder="请输入链接跳转系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="订阅/公众号" 
              prop="wx_pa" >
              <el-input 
                v-model="pointForm.wx_pa" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">分/场地/天</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="订阅/公众号系数" 
              prop="wx_pa_off" >
              <el-input 
                v-model="pointForm.wx_pa_off" 
                placeholder="请输入订阅/公众号系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="小程序" 
              prop="wx_mp" >
              <el-input 
                v-model="pointForm.wx_mp" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">分/场地/天</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="小程序系数" 
              prop="wx_mp_off" >
              <el-input 
                v-model="pointForm.wx_mp_off" 
                placeholder="请输入小程序系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="APP下载" 
              prop="app" >
              <el-input 
                v-model="pointForm.app" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">分/场地/天</template>
              </el-input>
            </el-form-item><el-form-item 
              label="APP系数" 
              prop="app_off" >
              <el-input 
                v-model="pointForm.app_off" 
                placeholder="请输入APP系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="手机号提取" 
              prop="phone" >
              <el-input 
                v-model="pointForm.phone" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">分/场地/天</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="手机号系数" 
              prop="phone_off" >
              <el-input 
                v-model="pointForm.phone_off" 
                placeholder="请输入手机号系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="优惠券" 
              prop="coupon" >
              <el-input 
                v-model="pointForm.coupon" 
                placeholder="请输入" 
                class="item-input">
                <template slot="append">分/场地/天</template>
              </el-input>
            </el-form-item>
            <el-form-item 
              label="卷系数" 
              prop="coupon_off" >
              <el-input 
                v-model="pointForm.coupon_off" 
                placeholder="请输入卷系数" 
                class="item-input">
                <template slot="append">%</template>
              </el-input>
            </el-form-item>
          </el-tab-pane>
        </el-tabs>
        <el-form-item>
          <el-button 
            type="primary">保存</el-button>
          <el-button >返回</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import search from 'service/search'
import project from 'service/project'
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
  Radio
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
    ElRadio: Radio
  },
  data() {
    let checkEnterEndDate = (rule, value, callback) => {
      if (!value) {
        return callback(new Error('入驻结束时间不能为空'))
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
        return callback(new Error('运营结束时间不能为空'))
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
    return {
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
      siteID: '',
      pointForm: {
        site_id: '',
        area_id: '',
        name: '',
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
        exchange_num: 0,
        permission: 1,
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
      areaList: [],
      rules: {
        name: [{ required: true, message: '请输入名称', trigger: 'submit' }],
        area_id: [{ required: true, message: '请选择区域', trigger: 'submit' }],
        site_id: [{ required: true, message: '请选择场地', trigger: 'submit' }],
        type: [
          { required: true, message: '请选择场地类型', trigger: 'submit' }
        ],
        contract: [
          { required: true, message: '请选择是否有无合同', trigger: 'submit' }
        ],
        pay: [
          { required: true, message: '请输入金额', trigger: 'submit' },
          { type: 'number', message: '金额必须为数字', trigger: 'submit' }
        ],
        enter_sdate: [
          { required: true, message: '请选择入驻开始时间', trigger: 'submit' }
        ],
        enter_edate: [
          { required: true, validator: checkEnterEndDate, trigger: 'submit' }
        ],
        oper_sdate: [
          { required: true, message: '请选择运营开始时间', trigger: 'submit' }
        ],
        oper_edate: [
          { required: true, validator: checkOperEndDate, trigger: 'submit' }
        ],
        mode: [
          { required: true, message: '请选择合作模式', trigger: 'submit' }
        ],
        ad_istar: [
          { required: true, message: '请输入A类广告分成', trigger: 'submit' }
        ],
        ad_ads: [
          { required: true, message: '请输入B类广告分成', trigger: 'submit' }
        ],
        exchange_num: [
          { required: true, message: '请输入置换节目数量', trigger: 'submit' }
        ],
        permission: [
          { required: true, message: '请选择场地权限', trigger: 'submit' }
        ],
        offer: [{ required: true, message: '请输入报刊价', trigger: 'submit' }],
        offer_off: [
          { required: true, message: '报刊价系数', trigger: 'submit' }
        ],
        mad: [{ required: true, message: '请输入曝光价', trigger: 'submit' }],
        mad_off: [
          { required: true, message: '请输入曝光价系数', trigger: 'submit' }
        ],
        play: [{ required: true, message: '请输入冠名价', trigger: 'submit' }],
        play_off: [
          { required: true, message: '请输入冠名价系数', trigger: 'submit' }
        ],
        qrcode: [{ required: true, message: '请输入', trigger: 'submit' }],
        qrcode_off: [
          { required: true, message: '请输入链接跳转系数', trigger: 'submit' }
        ],
        wx_pa: [{ required: true, message: '请输入', trigger: 'submit' }],
        wx_pa_off: [
          {
            required: true,
            message: '请输入订阅/公众号系数',
            trigger: 'submit'
          }
        ],
        wx_mp: [{ required: true, message: '请输入', trigger: 'submit' }],
        wx_mp_off: [
          { required: true, message: '请输入小程序系数', trigger: 'submit' }
        ],
        app: [{ required: true, message: '请输入', trigger: 'submit' }],
        app_off: [
          { required: true, message: '请输入APP系数', trigger: 'submit' }
        ],
        phone: [{ required: true, message: '请输入', trigger: 'submit' }],
        phone_off: [
          { required: true, message: '请输入手机号系数', trigger: 'submit' }
        ],
        coupon: [{ required: true, message: '请输入', trigger: 'submit' }],
        coupon_off: [
          { required: true, message: '请输入优惠卷系数', trigger: 'submit' }
        ]
      }
    }
  },
  mounted() {},
  created() {
    this.getAreaList()
  },
  methods: {
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
