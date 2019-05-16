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
            v-model="adPlanForm.type"
            :loading="searchLoading"
            @change="typeChangeHandle"
            placeholder="请选择类型">
            <el-option key="program" label="节目广告" value="program"/>
            <el-option key="ads" label="小屏广告" value="ads"/>
          </el-select>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请选择广告行业', trigger: 'submit',type: 'number'}]"
          label="广告行业"
          prop="ad_trade_id" >
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
          prop="ad_trade_id" >
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
        <!--"icon":"http://o9xrbl1oc.bkt.clouddn.com/1007/image/175_789_958_6_scene.png",-->

        <el-form-item
          label="广告方案介绍"
          prop="name">
          <el-input placeholder="请输入广告方案名称"  v-model="adPlanForm.name"></el-input>
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
        icon: 'http://o9xrbl1oc.bkt.clouddn.com/1007/image/175_789_958_6_scene.png',
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
    this.setting.loading = true
    let searchAdTradeList = this.getSearchAdTradeList()
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
    typeChangeHandle(){
      this.getSearchAdList();
    },
    AdTradeChangeHandle(){
      this.getSearchAdList();
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
      if(!this.adPlanForm.type){
        this.$message({
          message: "请先选择类型",
          type: 'error'
        })
      }

      let args = {
        atid: this.adPlanForm.atid,
        type: this.adPlanForm.type,
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
