<template>
  <div class="add-contract-wrap">
    <div class="topbar">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/contract/index' }">合约管理</el-breadcrumb-item>
        <el-breadcrumb-item>{{contractID ? '修改' : '添加'}}</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div>
      <div class="contract-title">
      {{$route.name}}
      </div>
      <el-form ref="contractForm" :model="contractForm" label-width="80px">
        <el-form-item label="资源信息" prop="info">
          <el-input class="user-form-input" v-model="contractForm.info"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary">保存</el-button>
          <el-button>取消</el-button>
        </el-form-item>
      </el-form>
    </div>
    <picture-panel :panelVisible.sync="panelVisible" @close="handleClose" :singleFlag="true"></picture-panel>
  </div>
</template>

<script>
import picturePanel from 'components/common/picturePanel'
import { Button, Input, Form, FormItem, Checkbox, CheckboxGroup, RadioGroup, Radio} from 'element-ui'

export default {
  name: 'addUser',
  data() {
    return {
      contractID: '',
      info: '',
      panelVisible: true,
      contractForm: {

      }
    }
  },
  created: function(){
    
  },
  methods: {
    handleClose(data) {
      if (data && data.length > 0 && data[0].media_url) {
        console.dir(data)
        this.imageUrl = data[0].media_url
      } else {
        console.log('图片上传失败')
      }
    },
    handleOpenPane() {
      this.panelVisible = true
    },
    handleImageDelete() {
      this.imageUrl = ''
    },

  },
  components: {
    "el-button": Button,
    "el-input": Input,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-checkbox": Checkbox,
    "el-checkbox-group": CheckboxGroup,
    picturePanel,
    "el-radio-group": RadioGroup,
    "el-radio": Radio
  }
}
</script>
<style scoped lang="less">
  .add-contract-wrap{
    background: #fff;
    margin: 10px;
    padding: 30px;
    .user-form-input{
      width: 385px;
    }
    .up-area-cover {
      border: 1px dashed #d9d9d9;
      width: 228px;
      height: 228px;
      cursor: pointer;
      position: relative;
      .cover{
        width: 228px;
        height: 228px;
        display: block
      }
      .cover-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 228px;
        height: 228px;
        line-height: 228px;
        text-align: center;
      }
      .delete-icon-image {
        position: absolute;
        top: 5px;
        right: 5px;
        font-size: 20px;
        color: #83909a;
        cursor: pointer;
      }
    }
    .contract-title{
      margin-bottom: 20px;
    }
    .el-checkbox{
      margin-left: 0px;
      margin-right: 15px;
    }
  }
</style>
