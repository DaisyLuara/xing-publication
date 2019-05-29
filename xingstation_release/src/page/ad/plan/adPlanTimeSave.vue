<template>
  <div
    class="item-wrap-template">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="pane">
      <div
        class="pane-title">
        {{ planTimeId ? '编辑素材排期' : '新增素材排期' }}
      </div>
      <el-form
        ref="adPlanTimeForm"
        :model="adPlanTimeForm"
        label-width="150px">

        <el-form-item
          label="类型">
          <el-input 
            :value="adPlan.type_text" 
            disabled />
        </el-form-item>
        <el-form-item
          label="广告行业">
          <el-input 
            :value="adPlan.ad_trade_name"
            disabled />
        </el-form-item>
        <el-form-item
          label="广告模版名称">
          <el-input 
            :value="adPlan.name" 
            disabled />
        </el-form-item>

        <!--单选广告素材-->
        <el-form-item
          :rules="[{ required: true, message: '请选择广告素材', trigger: 'submit',type: 'number'}]"
          label="广告素材"
          prop="aid">
          <el-select
            :disabled="!!planTimeId"
            v-model="adPlanTimeForm.aid"
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


        <template v-if="adPlan.type === 'program'">
          <el-form-item
            :rules="[{ required: true, message: '请选择显示模式', trigger: 'submit',type: 'string'}]"
            label="素材显示模式"
            prop="mode">
            <el-select 
              v-model="adPlanTimeForm.mode" 
              placeholder="请选择显示模式">
              <el-option
                v-for="item in modeOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value"/>
            </el-select>
          </el-form-item>
          <template v-if="adPlanTimeForm.mode !== 'fullscreen'">
            <el-form-item
              :rules="[{ required: true, message: '请选择素材显示位置', trigger: 'submit',type: 'string'}]"
              label="素材显示位置"
              prop="ori">
              <el-select
                v-model="adPlanTimeForm.ori"
                placeholder="请选择素材显示位置">
                <el-option
                  v-for="item in oriOptions"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"/>
              </el-select>
            </el-form-item>
            <el-form-item
              :rules="[{ required: true, message: '请填入素材显示尺寸', trigger: 'submit',type: 'number'}]"
              label="素材显示尺寸"
              prop="screen">
              <el-input-number
                v-model="adPlanTimeForm.screen"
                :min="1"
                :max="100"
                controls-position="right"/>
              %
            </el-form-item>
          </template>
        </template>

        <el-form-item
          :rules="[{ required: true, message: '请选择', trigger: 'submit',type: 'number'}]"
          label="素材播放"
          prop="cdshow">
          <el-radio
            v-model="adPlanTimeForm.play"
            :label="1">自定义时长
          </el-radio>
          <el-radio
            v-model="adPlanTimeForm.play"
            :label="0">默认时长
          </el-radio>
        </el-form-item>

        <el-form-item
          v-if="adPlanTimeForm.play"
          :rules="[{ required: true, message: '请填入素材播放时长', trigger: 'submit',type: 'number'}]"
          label="素材播放时长"
          prop="ktime">
          <el-input-number
            v-model="adPlanTimeForm.ktime"
            :min="0"
            :max="9999"
            controls-position="right"/>
          秒
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请选择倒计时', trigger: 'submit',type: 'number'}]"
          label="倒计时"
          prop="cdshow">
          <el-radio
            v-model="adPlanTimeForm.cdshow"
            :label="0">关闭
          </el-radio>
          <el-radio
            v-model="adPlanTimeForm.cdshow"
            :label="1">开启
          </el-radio>
        </el-form-item>

        <el-form-item
          :rules="[{ required: true, message: '请选择素材投放时间', trigger: 'submit'}]"
          prop="shm"
          label="素材投放时间">
          <el-time-picker
            :clearable="false"
            v-model="adPlanTimeForm.shm"
            :format="time_format"
            :value-format="time_format"
            placeholder="选择开始时间"/>
          至
          <el-time-picker
            :clearable="false"
            v-model="adPlanTimeForm.ehm"
            :format="time_format"
            :value-format="time_format"
            placeholder="选择结束时间"/>
          <span v-if="adPlan.tmode === 'hours'"> 分 </span>
        </el-form-item>

        <el-form-item
          :rules="[{ required: true, message: '请选择状态', trigger: 'submit'}]"
          label="状态"
          prop="visiable">
          <el-radio 
            v-model="adPlanTimeForm.visiable" 
            :label="1">运营中</el-radio>
          <el-radio 
            v-model="adPlanTimeForm.visiable" 
            :label="0">下架</el-radio>
        </el-form-item>

        <el-form-item>
          <el-button @click="historyBack">返回</el-button>
          <el-button
            type="primary"
            @click="submit('adPlanTimeForm')">完成
          </el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
  import {
    modifyAdPlanTime,
    addAdPlanTime,
    getAdPlanDetail,
    getSearchAdvertisementList,
    getAdPlanTimeDetail,
    historyBack
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
      ElRadio: Radio,
      ElInputNumber: InputNumber,
      elTimePicker: TimePicker
    },
    data() {
      return {
        time_format: "HH:mm",
        planTimeId: null,
        planId: null,
        adPlan: [],

        setting: {
          isOpenSelectAll: true,
          loading: false,
          loadingText: '拼命加载中'
        },
        searchAdTradeList: [],
        searchAdList: [],
        adPlanTimeForm: {
          aid: null,
          mode: 'fullscreen',
          ori: 'center',
          screen: 100,
          cdshow: 1,
          play: 0,
          ktime: 15,
          visiable: 1,
          only: 0,
          shm: "00:01",
          ehm: "23:59",
        },
        modeOptions: [
          {
            'label': '全屏显示',
            'value': 'fullscreen'
          },
          {
            'label': '无人互动',
            'value': 'unmanned'
          },
          {
            'label': '资源加载页',
            'value': 'init'
          },
          {
            'label': '二维码页面',
            'value': 'qrcode'
          },
          {
            'label': '浮窗显示',
            'value': 'floating'
          },
        ],

        oriOptions: [
          {
            'label': '居中',
            'value': 'center'
          },
          {
            'label': '顶部居中',
            'value': 'top'
          },
          {
            'label': '底部居中',
            'value': 'bottom'
          },
          {
            'label': '左上角',
            'value': 'left_top'
          },
          {
            'label': '左侧居中',
            'value': 'left'
          },
          {
            'label': '左下角',
            'value': 'left_bottom'
          },
          {
            'label': '右上角',
            'value': 'right_top'
          },
          {
            'label': '右侧居中',
            'value': 'right'
          },
          {
            'label': '右下角',
            'value': 'right_bottom'
          }
        ]
      }
    },
    mounted() {
    },
    created() {
      this.planTimeId = this.$route.params.plan_time_id;
      this.planId = this.$route.params.plan_id;

      this.setting.loading = true;

      if (this.planTimeId) {
        this.getAdPlanTimeDetail();
      } else {
        this.getAdPlanDetail();
      }

    },
    methods: {
      getAdPlanTimeDetail() {
        let args = {
          params: {
            include: 'ad_plan',
          }
        }

        //获取AdPlan 详情
        return getAdPlanTimeDetail(this, args, this.planTimeId)
          .then(response => {
            this.adPlan = response.ad_plan;

            let adPlanTimeForm = response;
            if(adPlanTimeForm.ktime){
              adPlanTimeForm.play = 1;
            } else {
              adPlanTimeForm.play = 0;
            }
            if(this.adPlan.tmode === 'hours'){
              this.time_format = "mm";
              adPlanTimeForm.shm = adPlanTimeForm.shm.toString().substring( adPlanTimeForm.shm.toString().length - 2);
              adPlanTimeForm.ehm = adPlanTimeForm.ehm.toString().substring( adPlanTimeForm.ehm.toString().length - 2);
            } else {
              this.time_format = "HH:mm";
            }

            this.adPlanTimeForm = adPlanTimeForm;

            this.getSearchAdList();
          })
          .catch(error => {
            console.log(error)
            this.setting.loading = false
          })
      },
      getAdPlanDetail() {
        //获取AdPlan 详情
        return getAdPlanDetail(this, {}, this.planId)
          .then(response => {
            this.adPlan = response;
            if(this.adPlan.tmode === 'hours'){
              this.time_format = "mm";
              this.adPlanTimeForm.shm = "00";
              this.adPlanTimeForm.ehm = "59";
            } else {
              this.time_format = "HH:mm";
            }
            this.getSearchAdList();
          })
          .catch(error => {
            console.log(error)
            this.setting.loading = false
          })
      },

      getSearchAdList() {
        let args = {
          atid: this.adPlan.atid,
        }
        return getSearchAdvertisementList(this, args)
          .then(response => {
            this.searchAdList = response.data
            this.setting.loading = false
          })
          .catch(error => {
            console.log(error)
            this.setting.loading = false
          })
      },
      submit(formName) {
        this.$refs[formName].validate(valid => {
          if (valid) {

            this.setting.loading = true

            if (this.adPlanTimeForm.mode === 'fullscreen') {
              this.adPlanTimeForm.ori = "center";
              this.adPlanTimeForm.screen = 100;
            }
            if (this.adPlanTimeForm.play === 0) {
              this.adPlanTimeForm.ktime = 0;
            }

            if(!this.adPlanTimeForm.shm || !this.adPlanTimeForm.ehm){
              this.setting.loading = false
              this.$message({
                message: "请选择素材投放时间",
                type: 'error'
              })
              return;
            }

            if(this.adPlanTimeForm.shm > this.adPlanTimeForm.ehm){
              this.setting.loading = false
              this.$message({
                message: "素材投放时间的开始时间需小于结束时间",
                type: 'error'
              })
              return;
            }

            if (this.adPlan.tmode === 'hours') {
              this.adPlanTimeForm.shm = '00:' + this.adPlanTimeForm.shm.substring(this.adPlanTimeForm.shm.length - 2);
              this.adPlanTimeForm.ehm = '00:' + this.adPlanTimeForm.ehm.substring(this.adPlanTimeForm.ehm.length - 2);
            }

            let args = {
              atiid: this.adPlan.id,
              aid: this.adPlanTimeForm.aid,
              type: this.adPlan.type,
              mode: this.adPlanTimeForm.mode,
              ori: this.adPlanTimeForm.ori,
              screen: this.adPlanTimeForm.screen,
              cdshow: this.adPlanTimeForm.cdshow,
              ktime: this.adPlanTimeForm.ktime,
              only: this.adPlanTimeForm.only,
              visiable: this.adPlanTimeForm.visiable,
              shm: this.adPlanTimeForm.shm,
              ehm: this.adPlanTimeForm.ehm,
            }

            if (this.planTimeId) {
              return modifyAdPlanTime(this, args, this.planTimeId)
                .then(response => {
                  this.setting.loading = false
                  this.$message({
                    message: '编辑成功',
                    type: 'success'
                  })
                  this.$router.push({
                    path: '/ad/plan/'+ this.adPlan.id + '/plan_time'
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
            } else {
              return addAdPlanTime(this, args, this.adPlan.id)
                .then(response => {
                  this.setting.loading = false
                  this.$message({
                    message: '添加成功',
                    type: 'success'
                  })
                  this.$router.push({
                    path: '/ad/plan/'+ this.adPlan.id + '/plan_time'
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
      },

      historyBack() {
        historyBack();
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

      .el-date-editor {
        width: 150px;
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
