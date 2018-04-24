<template>
  <div class="root">
    <div class="feedback-wrap">
      <el-card class="box-card">
        <div slot="header" class="clearfix header">
          <span>意见反馈</span>
        </div>
        <div class="feedback-content">
          <div class="feedback-title">
            <span class="feedback-title-line"></span>
            请选择意见类型
          </div>
          <el-form :model="feedbackForm" ref="searchForm" label-width="10px">
            <el-form-item label="" prop="option">
              <el-radio-group v-model="feedbackForm.option">
                <el-col :span="8" class="option-item">
                  <el-radio label="资质与审核"></el-radio>
                </el-col>
                <el-col :span="8" class="option-item">
                  <el-radio label="广告创建与工具使用"></el-radio>
                </el-col>
                <el-col :span="8" class="option-item">
                  <el-radio label="数据报表与数据监测"></el-radio>
                </el-col>
                <el-col :span="8" class="option-item">
                  <el-radio label="账号管理(绑定邮箱、变更主体等)"></el-radio>
                </el-col>
                <el-col :span="8" class="option-item">
                  <el-radio label="投放效果"></el-radio>
                </el-col>
                <el-col :span="8" class="option-item">
                  <el-radio label="财务管理(充值、退款、发票等)"></el-radio>
                </el-col>
                <el-col :span="8" class="option-item">
                  <el-radio label="其他"></el-radio>
                </el-col>
              </el-radio-group>
            </el-form-item>
            <el-form-item label="" prop="content">
              <el-input
                type="textarea"
                :rows="5"
                placeholder="请具体描述您的建议或遇到的问题 (500字内)"
                v-model="feedbackForm.textarea">
              </el-input>
            </el-form-item>
            <el-form-item label=''>
              <div class="up-area-cover">
                <img v-if="feedbackForm.imageUrl" :src="feedbackForm.imageUrl" class="cover">
                <i v-else class="el-icon-plus cover-uploader-icon" @click="handleOpenPane">上传照片</i>
                <i v-if="feedbackForm.imageUrl !== ''" class="el-icon-circle-cross delete-icon-image" @click="handleImageDelete()"></i>
              </div>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="submit">提交</el-button>
            </el-form-item>
          </el-form>
        </div>
      </el-card>
    </div>
    <picture-panel :panelVisible.sync="panelVisible" @close="handleClose" :singleFlag="true"></picture-panel>
  </div>
</template>

<script>
import { Button, Col, Card, RadioGroup, Radio, Form, FormItem, Input} from 'element-ui'
import picturePanel from 'components/common/picturePanel'

export default {
  data () {
    return {
      panelVisible: false,
      feedbackForm: {
        option: '',
        content: '',
        textarea: '',
        imageUrl: ''
      }
    }
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
    submit() {
      console.log('submit!');
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
    "el-col": Col,
    "el-card": Card,
    "el-radio-group": RadioGroup,
    "el-radio": Radio,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-input": Input,
    picturePanel,
  }
}
</script>

<style lang="less" scoped>
  .root {
    padding: 10px;
    font-size: 14px;
    color: #5E6D62;
    .feedback-wrap{
      padding: 50px 50px 100px;
      background: #fff;
      display: flex;
      justify-content: center;
      .box-card{
        min-width: 800px;
        width: 900px;
        .header{
          text-align: center;
        }
        .feedback-content{
          .feedback-title{
            font-size: 14px;
            display: flex;
            align-items: center;
            .feedback-title-line{
              height: 16px;
              width: 4px;
              margin-right: 5px;
              display: inline-block;
              background: #05c580;
            }
            padding: 5px 10px;
          }
          .option-item{
            margin-top: 15px;
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
          .el-button{
            width: 100%;
            background: #05c580;
            border: 1px solid #05c580;
          }
        }
      }
    }
  }
</style>
