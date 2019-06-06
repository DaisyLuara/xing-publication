<template>
  <div class="item-wrap-template">
    <div v-loading="setting.loading" :element-loading-text="setting.loadingText" class="pane">
      <div class="pane-title">{{ adPlanId ? '编辑广告模版' : '新增广告模版' }}</div>
      <el-form ref="adPlanForm" :model="adPlanForm" label-width="150px">
        <el-form-item
          :rules="[{ required: true, message: '请选择类型', trigger: 'submit',type: 'string'}]"
          label="类型"
          prop="type"
        >
          <el-radio v-model="adPlanForm.type" label="program">节目广告</el-radio>
          <el-radio v-model="adPlanForm.type" label="ads">小屏广告</el-radio>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请选择广告行业', trigger: 'submit',type: 'number'}]"
          label="广告行业"
          prop="atid"
        >
          <el-select v-model="adPlanForm.atid" filterable placeholder="请搜索" clearable>
            <el-option
              v-for="item in adTradeList"
              :key="item.id"
              :label="item.id + '. ' + item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请输入广告模版名称', trigger: 'submit',type: 'string'}]"
          label="广告模版名称"
          prop="name"
        >
          <el-input v-model="adPlanForm.name" placeholder="请输入广告模版名称"/>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请选择附件', trigger: 'submit',type: 'string'}]"
          label="方案图标"
          prop="icon"
        >
          <div class="avatar-uploader" @click="panelVisible=true">
            <img v-if="adPlanForm.icon" :src="adPlanForm.icon" class="avatar">
            <i v-else class="el-icon-plus avatar-uploader-icon"/>
          </div>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请选择播放模式', trigger: 'submit',type: 'string'}]"
          label="播放模式"
          prop="tmode"
        >
          <el-radio-group v-model="adPlanForm.tmode" @change="tmodeChangeHandle">
            <el-radio label="div">自定义</el-radio>
            <el-radio label="hours">小时间隔</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          v-if="adPlanForm.type === 'program'"
          :rules="[{ required: true, message: '请选择硬件加速', trigger: 'submit',type: 'number'}]"
          label="节目运行状态"
          prop="hardware"
        >
          <el-radio v-model="adPlanForm.hardware" :label="0">开启</el-radio>
          <el-radio v-model="adPlanForm.hardware" :label="1">关闭</el-radio>
        </el-form-item>
        <el-form-item label="备注" prop="info">
          <el-input
            v-model="adPlanForm.info"
            type="textarea"
            maxlength="200"
            show-word-limit
            placeholder="请输入模版备注"
          />
        </el-form-item>
        <el-form-item>
          <el-button @click="historyBack">返回</el-button>
          <el-button type="primary" @click="submit('adPlanForm')">完成</el-button>
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
  saveAdPlan,
  modifyAdPlan,
  getAdPlanDetail,
  getSearchAdTrade,
  historyBack
} from "service";

import {
  Radio,
  RadioGroup,
  Form,
  Select,
  Option,
  FormItem,
  Button,
  Input,
  MessageBox
} from "element-ui";

export default {
  components: {
    ElForm: Form,
    ElSelect: Select,
    ElOption: Option,
    ElFormItem: FormItem,
    ElButton: Button,
    ElInput: Input,
    ElRadio: Radio,
    ElRadioGroup: RadioGroup,
    PicturePanel
  },
  data() {
    return {
      panelVisible: false,
      singleFlag: true,
      time_format: "HH:mm",
      adPlanId: null,
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      adTradeList: [],
      searchLoading: false,
      adPlanForm: {
        type: "program",
        atid: "",
        name: "",
        icon:
          "http://image.xingstation.cn/1007/image/393_511_941_578_ic_launcher.png",
        info: "",
        hardware: 1,
        tmode: "div"
      },
      modeOptions: [
        {
          label: "全屏显示",
          value: "fullscreen"
        },
        {
          label: "无人互动",
          value: "unmanned"
        },
        {
          label: "资源加载页",
          value: "init"
        },
        {
          label: "二维码页面",
          value: "qrcode"
        },
        {
          label: "浮窗显示",
          value: "floating"
        }
      ],

      oriOptions: [
        {
          label: "居中",
          value: "center"
        },
        {
          label: "顶部居中",
          value: "top"
        },
        {
          label: "底部居中",
          value: "bottom"
        },
        {
          label: "左上角",
          value: "left_top"
        },
        {
          label: "左侧居中",
          value: "left"
        },
        {
          label: "左下角",
          value: "left_bottom"
        },
        {
          label: "右上角",
          value: "right_top"
        },
        {
          label: "右侧居中",
          value: "right"
        },
        {
          label: "右下角",
          value: "right_bottom"
        }
      ]
    };
  },
  created() {
    this.adPlanId = this.$route.params.plan_id;
    this.init();
    if (this.adPlanId) {
      this.getAdPlanDetail();
    }
  },

  methods: {
    async init() {
      try {
        let res = await getSearchAdTrade(this);
        this.adTradeList = res.data;
      } catch (e) {}
    },
    tmodeChangeHandle(tmode) {
      if (tmode === "hours") {
        this.adPlanForm.shm = "00";
        this.adPlanForm.ehm = "59";
        this.time_format = "mm";
      } else {
        this.adPlanForm.shm = "00:01";
        this.adPlanForm.ehm = "23:59";
        this.time_format = "HH:mm";
      }
    },
    handleClose(data) {
      if (data && data.length > 0) {
        let { url } = data[0];
        this.adPlanForm.icon = url;
      }
    },
    getAdPlanDetail() {
      //获取AdPlan 详情
      this.setting.loading = true;
      getAdPlanDetail(this, {}, this.adPlanId)
        .then(response => {
          this.adPlanForm = response;
          this.tmodeChangeHandle(response.tmode);
          this.setting.loading = false;
        })
        .catch(error => {
          console.log(error);
          this.setting.loading = false;
        });
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.setting.loading = true;
          if (this.adPlanForm.mode === "fullscreen") {
            this.adPlanForm.ori = "center";
            this.adPlanForm.screen = 100;
          }
          if (this.adPlanForm.play === 0) {
            this.adPlanForm.ktime = 0;
          }

          if (this.adPlanForm.tmode === "hours") {
            this.adPlanForm.shm =
              "00:" +
              this.adPlanForm.shm.substring(this.adPlanForm.shm.length - 2);
            this.adPlanForm.ehm =
              "00:" +
              this.adPlanForm.ehm.substring(this.adPlanForm.ehm.length - 2);
          }

          let args = this.adPlanForm;
          if (this.adPlanId) {
            modifyAdPlan(this, args, this.adPlanId)
              .then(response => {
                this.setting.loading = false;
                this.$message({
                  message: "编辑成功",
                  type: "success"
                });
                this.historyBack();
              })
              .catch(err => {
                this.setting.loading = false;
                this.$message({
                  message: err.response.data.message,
                  type: "warning"
                });
              });
          } else {
            saveAdPlan(this, args)
              .then(response => {
                this.setting.loading = false;
                this.$message({
                  message: "添加成功",
                  type: "success"
                });
                this.historyBack();
              })
              .catch(err => {
                this.setting.loading = false;
                this.$message({
                  message: err.response.data.message,
                  type: "warning"
                });
              });
          }
        }
      });
    },
    historyBack() {
      historyBack();
    }
  }
};
</script>

<style lang="less" scoped>
.item-title {
  color: #67c23a;
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
