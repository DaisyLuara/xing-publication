<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
    >
      <div class="label-title">{{ $route.name }}</div>
      <el-form
        ref="jobsForm"
        :model="jobsForm"
        :rules="rules"
        label-width="180px"
      >
        <el-form-item
          :rules="{required: true, message: '职位名称不能为空', trigger: 'submit'}"
          label="职位名称"
          prop="name"
        >
          <el-input
            v-model="jobsForm.name"
            placeholder="请输入职位名称，至多10个字"
            maxlength="10"
            class="jobs-form-input"
          />
        </el-form-item>
        <el-form-item
          label="展示状态"
          prop="status"
        >
          <el-radio
            v-model="jobsForm.status"
            label="1"
          >展示中</el-radio>
          <el-radio
            v-model="jobsForm.status"
            label="0"
          >已隐藏</el-radio>
        </el-form-item>
        <el-form-item
          :rules="{required: true, message: '工作地点不能为空', trigger: 'submit'}"
          label="工作地点"
          prop="address"
        >
          <el-input
            v-model="jobsForm.address"
            value="上海 - 浦东陆家嘴"
            class="jobs-form-input"
          />
        </el-form-item>
        <el-form-item
          :rules="{required: true, message: '工作经验不能为空', trigger: 'submit'}"
          label="工作经验"
          prop="experience"
        >
          <el-select
            v-model="jobsForm.experience"
            placeholder="请选择"
          >
            <el-option
              v-for="item in experienceList"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            >
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item
          :rules="{required: true, message: '学历不能为空', trigger: 'submit'}"
          label="学历"
          prop="education"
        >
          <el-input
            v-model="jobsForm.education"
            value="本科及以上"
            class="jobs-form-input"
          />
        </el-form-item>
        <el-form-item
          :rules="{required: true, message: '工作类型不能为空', trigger: 'submit'}"
          label="工作类型"
          prop="type"
        >
          <el-select
            v-model="jobsForm.type"
            placeholder="请选择工作类型"
          >
            <el-option
              v-for="item in jobTypeList"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            >
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item
          :rules="{required: true, message: '招聘人数不能为空', trigger: 'submit'}"
          label="招聘人数"
          prop="number"
        >
          <el-input
            v-model="jobsForm.number"
            placeholder="请输入招聘人数"
            class="jobs-form-input"
          />
        </el-form-item>
        <el-form-item
          :rules="{required: true, message: '职位描述不能为空', trigger: 'submit'}"
          label="职位描述"
          prop="jobDetail"
        >
          <!-- 富文本编辑器 -->
          <div class="edit-warp">

          </div>
        </el-form-item>
        <el-form-item
          :rules="{required: true, message: '职位要求不能为空', trigger: 'submit'}"
          label="职位要求"
          prop="require"
        >
          <!-- 富文本编辑器 -->
          <div class="edit-warp">

          </div>
        </el-form-item>
        <el-form-item
          :rules="{required: true, message: '邮箱不能为空', trigger: 'submit'}"
          label="发送简历至"
          prop="email"
        >
          <el-input
            v-model="jobsForm.email"
            value="hr@actiview.com"
            class="jobs-form-input"
          />
        </el-form-item>
        <el-form-item>
          <el-button
            type="primary"
            @click="onSubmit('jobsForm')"
          >保存</el-button>
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
  handleDateTransform,
  historyBack,
} from "service";
import {
  Loading,
  Button,
  Input,
  Form,
  FormItem,
  Radio,
  MessageBox,
  Select,
  Option,
} from "element-ui";
export default {
  components: {
    "el-button": Button,
    "el-input": Input,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-select": Select,
    "el-option": Option,
    "el-radio": Radio,
  },
  data() {
    return {
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      panelVisible: false,
      singleFlag: true,
      multipleNum: 0,
      jobsForm: {
        name: '',
        address: '上海-浦东陆家嘴',
        type: 1,
        status: 1,
      },
    }
  },
  methods: {
    onSubmit() { },
    historyBack() {
      historyBack();
    }
  }
}
</script>
<style lang="less" scoped>
.el-tag + .el-tag {
  margin-left: 10px;
}
.button-new-tag {
  margin-left: 10px;
  height: 32px;
  line-height: 30px;
  padding-top: 0;
  padding-bottom: 0;
}
.input-new-tag {
  width: 90px;
  margin-left: 10px;
  vertical-align: bottom;
}
.root {
  background: #fff;
  padding: 20px;
  min-height: 100vh;
  .jobs-form-input {
    width: 300px;
  }
  .jobs-form-date {
    width: 300px;
  }
  .jobs-form-select {
    width: 300px;
  }
  .up-area-cover {
    border: 1px dashed #d9d9d9;
    width: 228px;
    height: 228px;
    cursor: pointer;
    position: relative;
    .cover {
      width: 228px;
      height: 228px;
      display: block;
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
  .label-title {
    margin-bottom: 20px;
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
  .el-checkbox {
    margin-left: 0px;
    margin-right: 15px;
  }
}
</style>


