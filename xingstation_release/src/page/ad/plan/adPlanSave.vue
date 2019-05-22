<template>
  <div
    class="item-wrap-template">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="pane">
      <div
        class="pane-title">
        {{ adPlanId ? '编辑广告模版' : '新增广告模版' }}
      </div>
      <el-form
        ref="adPlanForm"
        :model="adPlanForm"
        label-width="150px">

        <el-form-item>
          <h3 class="item-title">
            <i class="el-icon-star-on"></i>
            <i class="el-icon-star-on"></i>
            <i class="el-icon-star-on"></i>
            广告模版
            <i class="el-icon-star-on"></i>
            <i class="el-icon-star-on"></i>
            <i class="el-icon-star-on"></i>
          </h3>
        </el-form-item>

        <el-form-item
          :rules="[{ required: true, message: '请选择类型', trigger: 'submit',type: 'string'}]"
          label="类型"
          prop="type">
          <el-radio
            v-model="adPlanForm.type"
            label="program">节目广告
          </el-radio>
          <el-radio
            v-model="adPlanForm.type"
            label="ads">小屏广告
          </el-radio>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请选择广告行业', trigger: 'submit',type: 'number'}]"
          label="广告行业"
          prop="atid">
          <el-select
            :disabled="isItem"
            v-model="adPlanForm.atid"
            filterable
            placeholder="请搜索"
            clearable
            @change="AdTradeChangeHandle">
            <el-option
              v-for="item in searchAdTradeList"
              :key="item.id"
              :label="item.id + '. ' + item.name"
              :value="item.id"/>
          </el-select>
        </el-form-item>
        <!--多选广告素材-->
        <el-form-item
          :rules="[{ required: true, message: '请选择广告素材', trigger: 'submit',type: 'array'}]"
          label="广告素材"
          prop="aids">
          <el-select
            :disabled="isItem"
            v-model="adPlanForm.aids"
            multiple
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
          :rules="[{ required: true, message: '请输入广告模版名称', trigger: 'submit',type: 'string'}]"
          label="广告模版名称"
          prop="name">
          <el-input
            v-model="adPlanForm.name"
            placeholder="请输入广告模版名称"/>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请选择附件', trigger: 'submit',type: 'string'}]"
          label="方案图标"
          prop="icon">
          <div
            class="avatar-uploader"
            @click="panelVisible=true">
            <img
              v-if="adPlanForm.icon"
              :src="adPlanForm.icon"
              class="avatar">
            <i
              v-else
              class="el-icon-plus avatar-uploader-icon"/>
          </div>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请选择播放模式', trigger: 'submit',type: 'string'}]"
          label="播放模式"
          prop="tmode">
          <el-radio-group
            v-model="adPlanForm.tmode"
            @change="tmodeChangeHandle">
            <el-radio
              label="div">自定义
            </el-radio>
            <el-radio
              label="hours">小时间隔
            </el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          v-if="adPlanForm.type === 'program'"
          :rules="[{ required: true, message: '请选择硬件加速', trigger: 'submit',type: 'number'}]"
          label="节目运行状态"
          prop="hardware">
          <el-radio
            v-model="adPlanForm.hardware"
            :label="0">开启
          </el-radio>
          <el-radio
            v-model="adPlanForm.hardware"
            :label="1">关闭
          </el-radio>
        </el-form-item>
        <el-form-item
          label="备注"
          prop="info">
          <el-input
            v-model="adPlanForm.info"
            type="textarea"
            maxlength="200"
            show-word-limit
            placeholder="请输入模版备注"/>
        </el-form-item>

        <template v-if="!isItem">
          <el-form-item>
            <h3 class="item-title">
              <i class="el-icon-star-on"></i>
              <i class="el-icon-star-on"></i>
              <i class="el-icon-star-on"></i>
              排期细节
              <i class="el-icon-star-on"></i>
              <i class="el-icon-star-on"></i>
              <i class="el-icon-star-on"></i>
            </h3>
          </el-form-item>
          <template v-if="adPlanForm.type === 'program'">
            <el-form-item
              :rules="[{ required: true, message: '请选择素材显示模式', trigger: 'submit',type: 'string'}]"
              label="素材显示模式"
              prop="mode">
              <el-select
                v-model="adPlanForm.mode"
                placeholder="请选择素材显示模式">
                <el-option
                  v-for="item in modeOptions"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"/>
              </el-select>
            </el-form-item>
            <template v-if="adPlanForm.mode !== 'fullscreen'">
              <el-form-item
                :rules="[{ required: true, message: '请选择素材显示位置', trigger: 'submit',type: 'string'}]"
                label="素材显示位置"
                prop="ori">
                <el-select
                  v-model="adPlanForm.ori"
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
                  v-model="adPlanForm.screen"
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
              v-model="adPlanForm.play"
              :label="1">自定义时长
            </el-radio>
            <el-radio
              v-model="adPlanForm.play"
              :label="0">默认时长
            </el-radio>
          </el-form-item>
          <el-form-item
            v-if="adPlanForm.play"
            :rules="[{ required: true, message: '请填入素材播放时长', trigger: 'submit',type: 'number'}]"
            label="素材播放时长"
            prop="ktime">
            <el-input-number
              v-model="adPlanForm.ktime"
              :min="0"
              controls-position="right"/>
            秒
          </el-form-item>
          <el-form-item
            :rules="[{ required: true, message: '请选择倒计时', trigger: 'submit',type: 'number'}]"
            label="倒计时"
            prop="cdshow">
            <el-radio
              v-model="adPlanForm.cdshow"
              :label="0">关闭
            </el-radio>
            <el-radio
              v-model="adPlanForm.cdshow"
              :label="1">开启
            </el-radio>
          </el-form-item>

          <el-form-item
            label="素材投放时间">
            <el-time-picker
              v-model="adPlanForm.shm"
              :format="time_format"
              :value-format="time_format"
              placeholder="选择开始时间"/>
            至
            <el-time-picker
              v-model="adPlanForm.ehm"
              :format="time_format"
              :value-format="time_format"
              placeholder="选择结束时间"/>
            <span v-if="adPlanForm.tmode === 'hours'"> 分 </span>
          </el-form-item>

          <el-form-item
            :rules="[{ required: true, message: '请选择状态', trigger: 'submit'}]"
            label="排期状态"
            prop="visiable">
            <el-radio
              v-model="adPlanForm.visiable"
              :label="1">运营中
            </el-radio>
            <el-radio
              v-model="adPlanForm.visiable"
              :label="0">下架
            </el-radio>
          </el-form-item>
        </template>
        <el-form-item>
          <el-button @click="historyBack">返回</el-button>
          <el-button
            type="primary"
            @click="submit('adPlanForm')">完成
          </el-button>
        </el-form-item>
      </el-form>
    </div>
    <PicturePanel
      :panel-visible.sync="panelVisible"
      :single-flag="singleFlag"
      @close="handleClose"/>
  </div>
</template>

<script>
  import PicturePanel from "components/common/picturePanel";
  import {
    saveAdPlan,
    modifyBatchAdPlan,
    modifyAdPlan,
    getAdPlanDetail,
    getSearchAdTradeList,
    getSearchAdvertisementList,
    historyBack
  } from 'service'

  import {
    Radio,
    RadioGroup,
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
      ElRadioGroup: RadioGroup,
      ElInputNumber: InputNumber,
      elTimePicker: TimePicker,
      PicturePanel
    },
    data() {
      return {
        panelVisible: false,
        singleFlag: true,
        time_format: "HH:mm",
        isItem: false,
        adPlanId: null,
        setting: {
          isOpenSelectAll: true,
          loading: false,
          loadingText: '拼命加载中'
        },
        searchAdTradeList: [],
        searchLoading: false,
        searchAdList: [],
        adPlanForm: {
          type: 'program',
          aids: [],
          atid: '',
          name: '',
          icon: 'http://image.xingstation.cn/1007/image/393_511_941_578_ic_launcher.png',
          info: '',
          hardware: 1,
          tmode: 'div',
          mode: 'fullscreen',
          ori: 'center',
          screen: 100,
          cdshow: 1,
          play: 0,
          ktime: 15,
          only: 0,
          visiable: 1,
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
      this.adPlanId = this.$route.params.ad_plan_id;
      this.isItem = this.$route.params.is_item === 'true';

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
      tmodeChangeHandle(tmode) {
        if (tmode === 'hours') {
          this.adPlanForm.shm = '00';
          this.adPlanForm.ehm = '59';
          this.time_format = "mm";
        } else {
          this.adPlanForm.shm = '00:01';
          this.adPlanForm.ehm = '23:59';
          this.time_format = "HH:mm";
        }
      },
      handleClose(data) {
        if (data && data.length > 0) {
          let {url} = data[0];
          this.adPlanForm.icon = url;
        } else {
          // this.$message({
          //   type: "warning",
          //   message: "图片上传失败"
          // });
        }
      },
      AdTradeChangeHandle() {
        this.adPlanForm.aids = [];
        this.getSearchAdList();
      },
      getAdPlanDetail() {
        //获取AdPlan 详情
        return getAdPlanDetail(this, {}, this.adPlanId)
          .then(response => {
            this.adPlanForm.aids = response.aids;
            this.adPlanForm.atid = response.atid;
            this.adPlanForm.type = response.type;
            this.adPlanForm.icon = response.icon;
            this.adPlanForm.name = response.name;
            this.adPlanForm.info = response.info;
            this.adPlanForm.tmode = response.tmode;
            this.adPlanForm.hardware = response.hardware;
            this.tmodeChangeHandle(response.tmode);
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
            this.setting.loading = true;
            if (this.adPlanForm.mode === 'fullscreen') {
              this.adPlanForm.ori = "center";
              this.adPlanForm.screen = 100;
            }
            if (this.adPlanForm.play === 0) {
              this.adPlanForm.ktime = 0;
            }

            if (this.adPlanForm.tmode === 'hours') {
              this.adPlanForm.shm = '00:' + this.adPlanForm.shm.substring(this.adPlanForm.shm.length - 2);
              this.adPlanForm.ehm = '00:' + this.adPlanForm.ehm.substring(this.adPlanForm.ehm.length - 2);
            }

            let args = {
              aids: this.adPlanForm.aids,
              atid: this.adPlanForm.atid,
              type: this.adPlanForm.type,
              name: this.adPlanForm.name,
              icon: this.adPlanForm.icon,
              info: this.adPlanForm.info,
              tmode: this.adPlanForm.tmode,
              hardware: this.adPlanForm.hardware,
              mode: this.adPlanForm.mode,
              ori: this.adPlanForm.ori,
              screen: this.adPlanForm.screen,
              cdshow: this.adPlanForm.cdshow,
              ktime: this.adPlanForm.ktime,
              only: this.adPlanForm.only,
              visiable: this.adPlanForm.visiable,
              shm: this.adPlanForm.shm,
              ehm: this.adPlanForm.ehm,
            }

            if (this.adPlanId) {
              if (this.isItem) {
                return modifyAdPlan(this, args, this.adPlanId)
                  .then(response => {
                    this.setting.loading = false
                    this.$message({
                      message: '编辑成功',
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
              } else {
                return modifyBatchAdPlan(this, args, this.adPlanId)
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
              }

            } else {
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
      },
      historyBack() {
        historyBack();
      }
    }
  }
</script>

<style lang="less" scoped>
  .item-title {
    color: #67C23A;
    margin: 10px;
  }

  .item-wrap-template {
    .pane {
      border-radius: 5px;
      background-color: white;
      padding: 20px 40px 80px;

      .el-select,
      .item-input,
      .el-input,
      .el-textarea,
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
</style>
