<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
    >
      <div class="label-title">{{ $route.name }}</div>
      <el-form
        ref="newsForm"
        :model="newsForm"
        :rules="rules"
        label-width="180px"
      >
        <el-form-item
          :rules="{required: true, message: '新闻标题不能为空', trigger: 'submit'}"
          label="新闻标题"
          prop="title"
        >
          <el-input
            v-model="newsForm.name"
            placeholder="请输入新闻标题，至多18个字"
            class="news-form-input"
          />
        </el-form-item>
        <el-form-item
          label="新闻类型"
          prop="type"
        >
          <el-radio
            v-model="newsForm.type"
            label="1"
          >公司新闻</el-radio>
          <el-radio
            v-model="newsForm.type"
            label="0"
          >媒体报导</el-radio>
        </el-form-item>
        <el-form-item
          label="展示状态"
          prop="status"
        >
          <el-radio
            v-model="newsForm.status"
            label="1"
          >展示中</el-radio>
          <el-radio
            v-model="newsForm.status"
            label="0"
          >已隐藏</el-radio>
        </el-form-item>
        <el-form-item
          label="标签"
          prop="labels"
        >
          <el-tag
            :key="tag"
            v-for="tag in newsForm.labels"
            closable
            :disable-transitions="false"
            @close="handleTagClose(tag)"
          >
            {{tag}}
          </el-tag>
          <el-input
            class="input-new-tag"
            v-if="inputTagVisible"
            v-model="inputTagValue"
            ref="saveTagInput"
            size="small"
            @keyup.enter.native="handleTagInputConfirm"
            @blur="handleTagInputConfirm"
          >
          </el-input>
          <el-button
            v-else
            class="button-new-tag"
            size="small"
            @click="showTagInput"
          >+点击此处添加标签</el-button>

        </el-form-item>
        <el-form-item
          label="上传封面图片"
          prop="image_url"
        >
          <div
            class="avatar-uploader"
            @click="panelVisible=true,imgType = 'h5'"
          >
            <img
              v-if="newsForm.image_url"
              :src="newsForm.image_url"
              class="avatar"
            >
            <i
              v-else
              class="el-icon-plus avatar-uploader-icon"
            />
          </div>
        </el-form-item>
        <el-form-item
          :rules="{required: true, message: '正文不能为空', trigger: 'submit'}"
          label="正文"
          prop="content"
        >
          <!-- 富文本编辑器 -->
          <div class="edit-warp">

          </div>
        </el-form-item>
        <el-form-item
          label="上传轮播图片"
          prop="bs_image_url"
        >
          <el-upload
            action="https://jsonplaceholder.typicode.com/posts/"
            list-type="picture-card"
            :on-preview="handlePictureCardPreview"
            :on-remove="handleRemove"
          >
            <i class="el-icon-plus"></i>
          </el-upload>
          <el-dialog :visible.sync="dialogVisible">
            <img
              width="100%"
              :src="dialogImageUrl"
              alt=""
            >
          </el-dialog>
        </el-form-item>
        <el-form-item>
          <el-button
            type="primary"
            @click="onSubmit('newsForm')"
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
  Table,
  Row,
  Col,
  TableColumn,
  Pagination,
  Dialog,
  Form,
  FormItem,
  Tag,
  Radio,
  MessageBox,
  DatePicker,
  Select,
  Option,
  CheckboxGroup,
  Checkbox,
  Switch,
  Upload
} from "element-ui";

export default {
  components: {
    "el-row": Row,
    "el-col": Col,
    "el-table": Table,
    "el-date-picker": DatePicker,
    "el-table-column": TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-select": Select,
    "el-option": Option,
    "el-checkbox-group": CheckboxGroup,
    "el-checkbox": Checkbox,
    "el-dialog": Dialog,
    "el-switch": Switch,
    "el-tag": Tag,
    "el-radio": Radio,
    "el-upload": Upload,
    PicturePanel
  },
  data() {
    var checkEndDate = (rule, value, callback) => {
      if (!value) {
        callback();
        return;
      }
      if (
        new Date(value.replace(/\-/g, "/")).getTime() <
        new Date(this.newsForm.start_date.replace(/\-/g, "/")).getTime()
      ) {
        callback(new Error("结束日期要大于开始日期"));
      } else {
        callback();
      }
    };
    return {
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      panelVisible: false,
      singleFlag: true,
      multipleNum: 0,
      newsForm: {
        title: '',
        type: 1,
        status: 1,
        labels: ['标签一', '标签二', '标签三'],
      },
      rules: {
        end_date: [{ validator: checkEndDate, trigger: "submit" }]
      },
      inputTagVisible: false,
      inputTagValue: '',
      dialogImageUrl: '',
      dialogVisible: false
    }
  },
  methods: {
    handleTagClose(tag) {
      this.newsForm.labels.splice(this.newsForm.labels.indexOf(tag), 1);
    },
    showTagInput() {
      this.inputTagVisible = true;
      this.$nextTick(_ => {
        this.$refs.saveTagInput.$refs.input.focus();
      });
    },
    handleTagInputConfirm() {
      let inputValue = this.inputTagValue;
      if (inputValue) {
        this.newsForm.labels.push(inputValue);
      }
      this.inputTagVisible = false;
      this.inputTagValue = '';
    },
    handleClose(data) {
      if (data && data.length > 0) {
        let { url } = data[0];
        if (this.imgType === "h5") {
          this.newsForm.image_url = url;
        } else {
          this.newsForm.bs_image_url = url;
        }
      }
    },
    handleRemove(file, fileList) {
      console.log(file, fileList);
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
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
  .news-form-input {
    width: 300px;
  }
  .news-form-date {
    width: 300px;
  }
  .news-form-select {
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


