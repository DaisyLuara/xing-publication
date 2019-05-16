<template>
  <div
    class="item-wrap-template" >
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="pane" >
      <div
        class="pane-title">
        新增广告方案
      </div>
      <el-form
        ref="adPlanForm"
        :model="adPlanForm"
        label-width="150px">

        <el-form-item
          :rules="[{ required: true, message: '请选择类型', trigger: 'submit',type: 'string'}]"
          label="类型"
          prop="type" >
          <el-select
            :disabled="adPlanId"
            v-model="adPlanForm.type"
            :loading="searchLoading"
            placeholder="请选择类型">
            <el-option key="program" label="节目广告" value="program"/>
            <el-option key="ads" label="小屏广告" value="ads"/>
          </el-select>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请选择广告行业', trigger: 'submit',type: 'number'}]"
          label="广告行业"
          prop="atid" >
          <el-select
            v-model="adPlanForm.atid"
            filterable
            placeholder="请搜索"
            @change="AdTradeChangeHandle"
            clearable>
            <el-option
              v-for="item in searchAdTradeList"
              :key="item.id"
              :label="item.id + '. ' + item.name"
              :value="item.id"/>
          </el-select>
        </el-form-item>
        <!--多选广告素材-->
        <el-form-item
          :rules="[{ required: true, message: '请选择广告素材', trigger: 'submit',type: 'number'}]"
          label="广告素材"
          prop="aids" >
          <el-select
            multiple
            v-model="adPlanForm.aids"
            filterable
            placeholder="请搜索"
            clearable>
            <el-option
              v-for="item in searchAdList"
              :key="item.id"
              :label="item.id + '. ' + item.name"
              :value="item.id"/>
          </el-select>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请输入广告方案名称', trigger: 'submit',type: 'string'}]"
          label="广告方案"
          prop="name">
          <el-input placeholder="请输入广告方案名称"  v-model="adPlanForm.name"></el-input>
        </el-form-item>

        <!--使用默认ICON-->
        <!--"icon":"http://image.xingstation.cn/1007/image/393_511_941_578_ic_launcher.png",-->

        <el-form-item
          label="广告方案介绍"
          prop="name">
          <el-input placeholder="请输入广告方案名称"  v-model="adPlanForm.name"></el-input>
        </el-form-item>

        <template v-if="adPlanForm.type === 'program'">
          <el-form-item
            :rules="[{ required: true, message: '请选择模式', trigger: 'submit',type: 'string'}]"
            label="模式"
            prop="mode">
            <el-select v-model="adPlanForm.mode" placeholder="请选择模式">
              <el-option
                v-for="item in modeOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item
            :rules="[{ required: true, message: '请选择位置', trigger: 'submit',type: 'string'}]"
            label="位置"
            prop="ori">
            <el-select v-model="adPlanForm.ori" placeholder="请选择位置">
              <el-option
                v-for="item in oriOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item
            :rules="[{ required: true, message: '请填入尺寸', trigger: 'submit',type: 'number'}]"
            label="尺寸"
            prop="screen">
            <el-input-number v-model="adPlanForm.screen" controls-position="right"  :min="1" :max="100"></el-input-number>%
          </el-form-item>
        </template>

        <el-form-item
          :rules="[{ required: true, message: '请选择倒计时', trigger: 'submit',type: 'number'}]"
          label="倒计时"
          prop="cdshow">
          <el-radio v-model="adPlanForm.cdshow" :label="0">关闭</el-radio>
          <el-radio v-model="adPlanForm.cdshow" :label="1">开启</el-radio>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请填入显示', trigger: 'submit',type: 'number'}]"
          label="显示"
          prop="ktime">
          <el-input-number v-model="adPlanForm.ktime" controls-position="right"  :min="1"></el-input-number>秒
        </el-form-item>
        <el-form-item
          label="开始时间"
          prop="shm">
          <el-time-picker
            v-model="adPlanForm.shm"
            placeholder="选择开始时间">
          </el-time-picker>
        </el-form-item>
        <el-form-item
          label="结束时间"
          prop="ehm">
          <el-time-picker
            v-model="adPlanForm.ehm"
            placeholder="选择结束时间">
          </el-time-picker>
        </el-form-item>
        <!--<el-form-item-->
          <!--:rules="[{ required: true, message: '请选择状态', trigger: 'submit'}]"-->
          <!--label="状态"-->
          <!--prop="visiable">-->
          <!--<el-radio v-model="adPlanForm.visiable" :label="1">运营中</el-radio>-->
          <!--<el-radio v-model="adPlanForm.visiable" :label="0">下架</el-radio>-->
        <!--</el-form-item>-->
        <!--<el-form-item-->
          <!--:rules="[{ required: true, message: '请选择唯一性', trigger: 'submit'}]"-->
          <!--label="唯一"-->
          <!--prop="only">-->
          <!--<el-radio v-model="adPlanForm.only" :label="1">是</el-radio>-->
          <!--<el-radio v-model="adPlanForm.only" :label="0">否</el-radio>-->
        <!--</el-form-item>-->
        <el-form-item>
          <el-button
            type="primary"
            @click="submit('adPlanForm')">完成</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import {
  saveAdPlan,
  modifyBatchAdPlan,
  getAdPlanDetail,
  getSearchAdTradeList,
  getSearchAdvertisementList,
} from 'service'

import {
  Radio,
  Form,
  Select,
  Option,
  FormItem,
  Button,
  Input,
  DatePicker,
  MessageBox,
  InputNumber,
  TimePicker
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
    ElRadio:Radio,
    ElInputNumber:InputNumber,
    elTimePicker:TimePicker
  },
  data() {
    return {
      adPlanId:null,
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: '拼命加载中'
      },
      searchAdTradeList: [],
      searchLoading: false,
      searchAdList: [],
      adPlanForm: {
        type:'program',
        aids: [],
        atid: '',
        name: '',
        icon: 'http://image.xingstation.cn/1007/image/393_511_941_578_ic_launcher.png',
        info: '',
        mode: 'fullscreen',
        ori: 'center',
        screen: 100,
        cdshow: 1,
        ktime: 15,
        shm: new Date(2019, 1, 1, 0, 1),
        ehm: new Date(2019, 1, 1, 23, 59),
      },
      modeOptions:[
        {
          'label':'全屏显示',
          'value':'fullscreen'
        },
        {
          'label':'无人互动',
          'value':'unmanned'
        },
        {
          'label':'二维码页面',
          'value':'qrcode'
        },
        {
          'label':'浮窗显示',
          'value':'floating'
        },
      ],

      oriOptions:[
        {
          'label':'居中',
          'value':'center'
        },
        {
          'label':'顶部居中',
          'value':'top'
        },
        {
          'label':'底部居中',
          'value':'bottom'
        },
        {
          'label':'左上角',
          'value':'left_top'
        },
        {
          'label':'左侧居中',
          'value':'left'
        },
        {
          'label':'左下角',
          'value':'left_bottom'
        },
        {
          'label':'右上角',
          'value':'right_top'
        },
        {
          'label':'右侧居中',
          'value':'right'
        },
        {
          'label':'右下角',
          'value':'right_bottom'
        }
      ]
    }
  },
  mounted() {},
  created() {
    this.adPlanId = this.$route.params.ad_plan_id;

    this.setting.loading = true

    let searchAdTradeList = this.getSearchAdTradeList()

    if (this.adPlanId) {
      this.getAdPlanDetail();
    }

    Promise.all([searchAdTradeList])
      .then(() => {
        this.setting.loading = false
      })
      .catch(err => {
        console.log(err)
        this.setting.loading = false
      })

  },
  methods: {
    AdTradeChangeHandle(){
      this.adPlanForm.aids = [];
      this.getSearchAdList();
    },
    getAdPlanDetail(){
      //获取AdPlan 详情
      return getAdPlanDetail(this,{},this.adPlanId)
        .then(response => {
          this.adPlanForm.aids = response.aids,
          this.adPlanForm.atid = response.atid,
          this.adPlanForm.type = response.type,
          this.adPlanForm.icon = response.icon,
          this.adPlanForm.name = response.name,
          this.getSearchAdList();
        })
        .catch(error => {
          console.log(error)
          this.setting.loading = false
        })

    },
    getSearchAdTradeList() {
      return getSearchAdTradeList(this)
        .then(response => {
          this.searchAdTradeList = response.data
        })
        .catch(error => {
          console.log(error)
          this.setting.loading = false
        })
    },
    getSearchAdList() {
      let args = {
        atid: this.adPlanForm.atid,
      }
      this.searchLoading = true
      return getSearchAdvertisementList(this, args)
        .then(response => {
          this.searchAdList = response.data
          this.searchLoading = false
        })
        .catch(error => {
          console.log(error)
          this.searchLoading = false
        })
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.setting.loading = true
          let args = {
            aids: this.adPlanForm.aids,
            atid: this.adPlanForm.atid,
            type: this.adPlanForm.type,
            name: this.adPlanForm.name,
            icon: this.adPlanForm.icon,
            info: this.adPlanForm.info,
            mode: this.adPlanForm.mode,
            ori: this.adPlanForm.ori,
            screen: this.adPlanForm.screen,
            cdshow:this.adPlanForm.cdshow,
            ktime:this.adPlanForm.ktime,
            shm:this.adPlanForm.shm,
            ehm:this.adPlanForm.ehm,
          }

          if(this.adPlanId){
            return modifyBatchAdPlan(this, args)
              .then(response => {
                this.setting.loading = false
                this.$message({
                  message: '批量编辑成功',
                  type: 'success'
                })
                this.$router.push({
                  path: '/ad/plan'
                })
              })
              .catch(err => {
                this.setting.loading = false
                this.$message({
                  message: err.response.data.message,
                  type: 'error'
                })
                console.log(err)
              })
          }else{
            return saveAdPlan(this, args)
              .then(response => {
                this.setting.loading = false
                this.$message({
                  message: '添加成功',
                  type: 'success'
                })
                this.$router.push({
                  path: '/ad/plan'
                })
              })
              .catch(err => {
                this.setting.loading = false
                this.$message({
                  message: err.response.data.message,
                  type: 'error'
                })
                console.log(err)
              })
          }
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
    .el-input,
    .el-date-editor {
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
