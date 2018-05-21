<template>
  <div class="item-wrap-template">
    <div class="team-project-wrap">
      <el-card class="box-card">
        <h3 class="project-headline">
          创建项目
        </h3>
        <div class="project-content-wrap">
          <el-form ref="form" :model="form" label-width="20px">
            <el-form-item prop="name" label=" " :rules="[{ required: true, message: '请输入项目名称', trigger: 'submit',type: 'number'}]">
              <el-input v-model="form.name" placeholder="项目名称" style="width: 548px;font-size: 18px;font-weight: 500;color: #444;"></el-input>
            </el-form-item>
            <el-form-item prop="content">
              <el-input
                type="textarea"
                placeholder="简单描述项目，便于其他人理解（选填）"
                :autosize="{ minRows: 4, maxRows: 4}"
                resize="none"
                v-model="form.content"  style="width: 548px;font-size: 12px;font-weight: 500;color: #444;">
              </el-input>
            </el-form-item>
            <el-form-item prop="type">
              <h4 class="project-type">项目类型</h4>
              <el-radio-group v-model="form.type">
                <div >
                  <el-radio label="看板项目" name="type" style="font-size: 18px;"></el-radio>
                </div>
                <div>
                  <el-radio label="标准项目" name="type"></el-radio>
                </div>
              </el-radio-group>
            </el-form-item>
          </el-form>
        </div>
      </el-card>
    </div>
  </div>
</template>
<script>
import {Card, Form, FormItem, Input, Radio, RadioGroup} from 'element-ui'
export default {
  data() {
    return {
      form: {
        name: '',
        content: '',
        type: ''
      }
    }
  },
  components: {
    ElCard: Card,
    ElForm: Form,
    ElFormItem: FormItem,
    ElInput: Input,
    ElRadio: Radio,
    ElRadioGroup: RadioGroup
  }
}
</script>
<style lang="less" scoped>
  .item-wrap-template{
    background: #fff;
    .team-project-wrap{
      padding: 30px 0;
      position: relative;
      width: 962px;
      margin: 0 auto;
      .project-headline {
        margin: 10px 15px;
        font-size: 21px;
        font-weight: 500;
      }
      .project-content-wrap{
        padding: 10px 0;
        .project-type{
          font-size: 16px;
          font-weight: 500;
        }
      }
    }
  }
</style>

