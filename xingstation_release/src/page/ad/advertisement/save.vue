<template>
  <div class="item-wrap-template">
    <div 
      v-loading="setting.loading" 
      :element-loading-text="setting.loadingText" 
      class="pane">
      <div class="pane-title">{{ aid ? '编辑广告素材' : '新增广告素材' }}</div>
      <el-form 
        ref="adForm" 
        :model="adForm" 
        label-width="150px">
        <el-form-item
          :rules="[{ required: true, message: '请输入广告行业名称', trigger: 'submit',type: 'number'}]"
          label="广告行业"
          prop="atid"
        >
          <el-select 
            v-model="adForm.atid" 
            filterable 
            placeholder="请搜索" 
            clearable>
            <el-option
              v-for="item in searchAdTradeList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请填写素材名', trigger: 'submit',type: 'string'}]"
          label="素材名"
          prop="name"
        >
          <el-input 
            v-model="adForm.name" 
            placeholder="请填写素材名"/>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请选择类型', trigger: 'submit',type: 'string'}]"
          label="类型"
          prop="type"
        >
          <template v-for="(typeOption,index) in typeOptions">
            <el-radio
              v-model="adForm.type"
              :label="typeOption.value"
              :key="index"
            >{{ typeOption.label }}</el-radio>
          </template>
        </el-form-item>

        <el-form-item
          v-if="adForm.type === 'static' || adForm.type === 'gif'"
          :rules="[{ required: true, message: '请选择附件', trigger: 'submit',type: 'string'}]"
          label="附件图"
          prop="link"
        >
          <div 
            class="avatar-uploader" 
            @click="panelVisible=true">
            <img 
              v-if="adForm.link" 
              :src="adForm.link" 
              class="avatar">
            <i 
              v-else 
              class="el-icon-plus avatar-uploader-icon"/>
          </div>
        </el-form-item>
        <el-form-item
          v-else
          :rules="[{ required: true, message: '请填写附件链接', trigger: 'submit',type: 'string'}]"
          label="附件"
          prop="link"
        >
          <el-input 
            v-model="adForm.link" 
            placeholder="请填写附件链接"/>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请选择广告标记', trigger: 'submit',type: 'number'}]"
          label="广告标记"
          prop="isad">
          <el-radio 
            v-model="adForm.isad" 
            :label="1">显示</el-radio>
          <el-radio 
            v-model="adForm.isad" 
            :label="0">隐藏</el-radio>
        </el-form-item>

        <el-form-item>
          <el-button @click="historyBack">返回</el-button>
          <el-button 
            type="primary" 
            @click="submit('adForm')">完成</el-button>
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
  getAdDetail,
  saveAd,
  modifyAd,
  getSearchAdTradeList,
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
    ElDatePicker: DatePicker,
    ElRadio: Radio,
    PicturePanel
  },
  data() {
    return {
      panelVisible: false,
      singleFlag: true,
      aid: null,
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },

      searchAdTradeList: [],
      searchLoading: false,

      adForm: {
        atid: "",
        name: "",
        img: "http://image.xingstation.cn/1007/image/426_video.jpg",
        type: "static",
        link: "",
        isad: 1
      },
      typeOptions: [
        {
          label: "静态图",
          value: "static"
        },
        {
          label: "gif",
          value: "gif"
        },
        {
          label: "帧序列",
          value: "fps"
        },
        {
          label: "视频",
          value: "video"
        }
      ]
    };
  },
  mounted() {},
  created() {
    this.aid = this.$route.params.aid;
    if (this.aid) {
      this.getAdDetail();
    }
    this.setting.loading = true;
    let searchAdTradeList = this.getSearchAdTradeList();
    Promise.all([searchAdTradeList])
      .then(() => {
        this.setting.loading = false;
      })
      .catch(err => {
        console.log(err);
        this.setting.loading = false;
      });
  },
  methods: {
    handleClose(data) {
      if (data && data.length > 0) {
        let { url } = data[0];
        this.adForm.link = url;
      } else {
        // this.$message({
        //   type: "warning",
        //   message: "图片上传失败"
        // });
      }
    },
    getAdDetail() {
      this.searchLoading = true;
      return getAdDetail(this, {}, this.aid)
        .then(response => {
          this.adForm = response;
          this.searchLoading = false;
        })
        .catch(err => {
          this.searchLoading = false;
        });
    },
    getSearchAdTradeList() {
      return getSearchAdTradeList(this)
        .then(response => {
          let data = response.data;
          this.searchAdTradeList = data;
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
          let args = {
            atid: this.adForm.atid,
            name: this.adForm.name,
            type: this.adForm.type,
            img: (this.adForm.type === 'static' || this.adForm.type === 'gif') ? this.adForm.link :this.adForm.img,
            link:this.adForm.link,
            isad:this.adForm.isad,
          }
          if(this.aid){
            return modifyAd(this, args, this.aid)
              .then(response => {
                this.setting.loading = false
                this.$message({
                  message: '编辑成功',
                  type: 'success'
                })
                this.$router.push({
                  path: '/ad/advertisement'
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
            return saveAd(this, args)
              .then(response => {
                this.setting.loading = false
                this.$message({
                  message: '添加成功',
                  type: 'success'
                })
                this.$router.push({
                  path: '/ad/advertisement'
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
          return;
        }
      })
    },

    historyBack() {
      historyBack();
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
    .el-input,
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
