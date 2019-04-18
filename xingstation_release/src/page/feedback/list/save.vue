<template>
  <div class="add-wrap" v-loading="loading">
    <div class="feedback-title">回答问题反馈</div>
    <el-form ref="feedbackAskFrom" :model="feedbackAskFrom" :rules="rules">
      <el-form-item prop="company" label="公司:">{{feedbackAskFrom.company_name}}</el-form-item>
      <el-form-item prop="account" label="账号:">{{feedbackAskFrom.createable_name}}</el-form-item>
      <el-form-item prop="title" label="问题:">{{feedbackAskFrom.title}}</el-form-item>
      <el-form-item prop="conent" label="内容:">{{feedbackAskFrom.content}}</el-form-item>
      <el-form-item prop="photos" label="图片:">
        <div class="photos-list" v-if="feedbackAskFrom.photos.length>0">
          <div class="photos-list-item" v-for="(item,index) in feedbackAskFrom.photos" :key="index">
            <img :src="item.url">
          </div>
        </div>
      </el-form-item>
      <el-form-item
        prop="replyConent"
        label="回复内容:"
        v-if="feedbackAskFrom.replyConent"
      >{{feedbackAskFrom.replyConent}}</el-form-item>
      <el-form-item>
        <div class="btn-wrap">
          <el-button type="default" @click="back">取消</el-button>
          <el-button type="primary" @click="replay" v-if="feedbackAskFrom.status===1">回复</el-button>
        </div>
      </el-form-item>
    </el-form>
    <el-dialog title="回复" :visible.sync="dialogFormVisible" :rules="rules" :close="false">
      <el-form :model="replyForm" ref="replyForm">
        <el-form-item prop="content">
          <el-input
            v-model="replyForm.content"
            type="textarea"
            placeholder="请输入内容"
            style="width: 380px"
          />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false,replyForm.content=''">取 消</el-button>
        <el-button type="primary" @click="submit('replyForm')">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { Input, Button, FormItem, Form, Dialog } from "element-ui";
import { getFeedbackDetail, historyBack, saveFeedback } from "service";

export default {
  components: {
    "el-input": Input,
    "el-button": Button,
    "el-form-item": FormItem,
    "el-form": Form,
    "el-dialog": Dialog
  },
  data() {
    return {
      dialogFormVisible: false,
      replyForm: {
        content: ""
      },
      feedbackAskFrom: {
        title: "",
        company_name: "",
        createable_name: "",
        content: "",
        photos: [],
        status: "",
        replyConent: null
      },
      loading: false,
      rules: {
        content: [{ required: true, message: "请输入内容", trigger: "submit" }]
      },
      feedbackId: null
    };
  },
  created() {
    this.feedbackId = Number(this.$route.params.uid);
    this.getFeedbackDetail();
  },
  methods: {
    replay() {
      this.dialogFormVisible = true;
    },
    back() {
      historyBack();
    },
    getFeedbackDetail() {
      this.loading = true;
      let args = {
        include: "photos,childrenFeedback.photos"
      };
      getFeedbackDetail(this, this.feedbackId, args)
        .then(res => {
          this.feedbackAskFrom.title = res.title;
          this.feedbackAskFrom.createable_name = res.createable_name;
          this.feedbackAskFrom.company_name = res.company_name;
          this.feedbackAskFrom.content = res.content;
          this.feedbackAskFrom.status = res.status;
          this.feedbackAskFrom.photos =
            res.photos.data.length > 0 ? res.photos.data : [];
          this.feedbackAskFrom.replyConent =
            res.childrenFeedback.data.length > 0
              ? res.childrenFeedback.data[0].content
              : null;
          this.loading = false;
        })
        .catch(err => {
          this.loading = false;
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let args = {
            content: this.replyForm.content,
            parent_id: this.feedbackId
          };
          saveFeedback(this, args)
            .then(res => {
              this.$router.push({
                path: "/feedback/list"
              });
              this.$message({
                type: "success",
                message: "回复成功"
              });
              this.replyForm.content = "";
              this.dialogFormVisible = false;
            })
            .catch(err => {
              this.replyForm.content = "";
              this.dialogFormVisible = false;
              this.$message({
                type: "error",
                message: err.response.data.message
              });
            });
        } else {
          return false;
        }
      });
    }
  }
};
</script>
<style lang="less" scoped>
.add-wrap {
  background-color: #fff;
  padding: 40px 0;
  padding-left: 60px;
  padding-right: 25%;
  .el-input {
    width: 380px;
  }
  .feedback-title {
    padding-bottom: 20px;
    font-size: 18px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
  }
  .photos-list {
    display: flex;
    flex-direction: row;
    min-height: 300px;
    .photos-list-item {
      width: 150px;
      margin-bottom: 10px;
      margin-right: 15px;
      height: 150px;
      img {
        width: 100%;
      }
    }
  }
  .btn-wrap {
    text-align: center;
    .el-button {
      padding: 10px 50px;
    }
  }
}
</style>
