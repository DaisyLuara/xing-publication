<template>
  <div 
    class="add-wrap">
    <div 
      class="url-title">
      新增短链接
    </div>
    <el-form 
      ref="urlInfo"
      :model="urlInfo" 
      :rules="rules">
      <el-form-item 
        prop="target_url">
        <el-input
          v-model="urlInfo.target_url"
          type="url" 
          placeholder="原始长链接"/>
      </el-form-item>
      <el-form-item 
        prop="description">
        <el-input
          v-model="urlInfo.description" 
          type="text" 
          placeholder="备注信息" />
      </el-form-item>
      <el-form-item 
        prop="url_type">
        是否外链:
        <el-radio v-model="urlInfo.url_type" label="1">是</el-radio>
        <el-radio v-model="urlInfo.url_type" label="0">否</el-radio>
      </el-form-item>
      <el-form-item>
        <div 
          class="btn-wrap">
          <el-button 
            type="default" 
            @click="resetForm('urlInfo')">取消</el-button>
          <el-button 
            type="primary" 
            @click="submitForm('urlInfo')">保存</el-button>
        </div>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
import { saveUrl } from 'service'
import router from 'router'
import { Input, Button, FormItem, Form, Radio } from 'element-ui'

export default {
  components: {
    'el-input': Input,
    'el-button': Button,
    'el-form-item': FormItem,
    'el-form': Form,
    'el-radio':Radio
  },
  data() {
    var checkUrl = (rule, value, callback) => {
      if (!value) {
        return callback(new Error('url不能为空'))
      }
      var reg = /(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/
      var re = new RegExp(reg)
      if (!re.test(value)) {
        callback(new Error('url必须符合规范'))
      } else {
        callback()
      }
    }
    return {
      urlInfo: {
        target_url: '',
        description: '',
        url_type: '1'
      },
      rules: {
        target_url: [{ validator: checkUrl, trigger: 'blur' }]
      },
    }
  },
  methods: {
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          saveUrl(this, this.urlInfo)
            .then(res => {
              this.$router.push({
                path: '/ad/url'
              })
              this.$message({
                type: 'success',
                message: '保存成功'
              })
            })
            .catch(err => {
              this.$message({
                type: 'error',
                message: '保存失败'
              })
            })
        } else {
          return false
        }
      })
    },
    resetForm(formName) {
      this.$refs[formName].resetFields()
      router.back()
    }
  }
}
</script>
<style lang="less" scoped>
.add-wrap {
  background-color: #fff;
  padding: 40px 0;
  padding-left: 60px;
  padding-right: 25%;
  .url-title {
    padding-bottom: 20px;
    font-size: 18px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
  }
  .btn-wrap {
    text-align: center;
    .el-button {
      padding: 10px 50px;
    }
  }
}
</style>
