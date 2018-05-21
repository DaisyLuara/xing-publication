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
                <div>
                  <el-radio label="看板项目" name="type" v-model="form.type" style="font-size:16px;">
                    <span class="label">看板项目</span><span class="note">更好地组织、细分和管理任务，适用于一般项目管理</span>
                  </el-radio>
                </div>
                <div>
                  <el-radio label="标准项目" name="type" v-model="form.type">
                    <span class="label">标准项目</span><span class="note">擅长处理流程化任务，适用于产品研发、用户支持等场景</span>
                  </el-radio>
                </div>
            </el-form-item>
            <el-form-item prop="type">
              <h4 class="project-type">项目公开性</h4>
                <div>
                  <el-radio label="私有项目" name="openness" v-model="form.openness" style="font-size:16px;">
                    <span class="label">私有项目</span><span class="note">仅项目成员可以查看和编辑该项目</span>
                  </el-radio>
                </div>
                <div>
                  <el-radio label="公开项目" name="openness" v-model="form.openness">
                    <span class="label">公开项目</span><span class="note">任何人都可以通过链接查看该项目，仅项目成员可以编辑该项目</span>
                  </el-radio>
                </div>
            </el-form-item>
            <el-form-item prop="type">
              <h4 class="project-type">项目分组</h4>
                 <el-checkbox-group v-model="form.grouping">
                  <el-checkbox label="铺屏" name="grouping"><span class="label">铺屏</span></el-checkbox>
                  <el-checkbox label="3月峰会" name="grouping"><span class="label">3月峰会</span></el-checkbox>
                  <el-checkbox label="APP开发" name="grouping"><span class="label">APP开发</span></el-checkbox>
                  <el-checkbox label="游戏模板修改" name="grouping"><span class="label">游戏模板修改</span></el-checkbox>
                  <el-checkbox label="星视度平台" name="grouping"><span class="label">星视度平台</span></el-checkbox>
                  <el-checkbox label="KA项目" name="grouping"><span class="label">KA项目</span></el-checkbox>
                </el-checkbox-group>
            </el-form-item>
            <el-form-item prop="type">
              <h4 class="project-type">选择项目成员</h4>
              <span class="note">管理员可以邀请和移除项目成员，只有被邀请的团队成员才能访问该项目的信息。</span>
               <el-checkbox-group v-model="form.projectMembers">
                  <el-checkbox label="陈重" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">陈重</span></el-checkbox>
                  <el-checkbox label="海阔天空" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">海阔天空</span></el-checkbox>
                  <el-checkbox label="fanyingzhao" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">fanyingzhao</span></el-checkbox>
                  <el-checkbox label="倪一旻" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">倪一旻</span></el-checkbox>
                  <el-checkbox label="钱雅婷" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">钱雅婷</span></el-checkbox>
                  <el-checkbox label="秦洁莲" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">秦洁莲</span></el-checkbox>
                  <el-checkbox label="陈重" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">陈重</span></el-checkbox>
                  <el-checkbox label="海阔天空" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">海阔天空</span></el-checkbox>
                  <el-checkbox label="fanyingzhao" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">fanyingzhao</span></el-checkbox>
                  <el-checkbox label="倪一旻" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">倪一旻</span></el-checkbox>
                  <el-checkbox label="钱雅婷" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">钱雅婷</span></el-checkbox>
                  <el-checkbox label="秦洁莲" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">秦洁莲</span></el-checkbox>
                  <el-checkbox label="陈重" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">陈重</span></el-checkbox>
                  <el-checkbox label="海阔天空" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">海阔天空</span></el-checkbox>
                  <el-checkbox label="fanyingzhao" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">fanyingzhao</span></el-checkbox>
                  <el-checkbox label="倪一旻" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">倪一旻</span></el-checkbox>
                  <el-checkbox label="钱雅婷" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">钱雅婷</span></el-checkbox>
                  <el-checkbox label="秦洁莲" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">秦洁莲</span></el-checkbox>
                  <el-checkbox label="陈重" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">陈重</span></el-checkbox>
                  <el-checkbox label="海阔天空" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">海阔天空</span></el-checkbox>
                  <el-checkbox label="fanyingzhao" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">fanyingzhao</span></el-checkbox>
                  <el-checkbox label="倪一旻" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">倪一旻</span></el-checkbox>
                  <el-checkbox label="钱雅婷" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">钱雅婷</span></el-checkbox>
                  <el-checkbox label="秦洁莲" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">秦洁莲</span></el-checkbox>
                  <el-checkbox label="陈重" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">陈重</span></el-checkbox>
                  <el-checkbox label="海阔天空" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">海阔天空</span></el-checkbox>
                  <el-checkbox label="fanyingzhao" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">fanyingzhao</span></el-checkbox>
                  <el-checkbox label="倪一旻" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">倪一旻</span></el-checkbox>
                  <el-checkbox label="钱雅婷" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">钱雅婷</span></el-checkbox>
                  <el-checkbox label="秦洁莲" name="projectMembers" style="width:170px;margin-right:10px;margin-left:0"><span class="label">秦洁莲</span></el-checkbox>

                </el-checkbox-group>
            </el-form-item>
          </el-form>
        </div>
      </el-card>
    </div>
  </div>
</template>
<script>
import {Card, Form, FormItem, Input, Radio, Checkbox, CheckboxGroup} from 'element-ui'
export default {
  data() {
    return {
      form: {
        name: '',
        content: '',
        type: '看板项目',
        grouping: '',
        openness: '公开项目',
        projectMembers: []
      }
    }
  },
  components: {
    ElCard: Card,
    ElForm: Form,
    ElFormItem: FormItem,
    ElInput: Input,
    ElRadio: Radio,
    ElCheckbox: Checkbox,
    ElCheckboxGroup: CheckboxGroup,
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
          margin-bottom: 10px;
          font-size: 16px;
          font-weight: 500;
        }
        .label{
          font-size: 16px;
          color: #444;
        }
        .note{
          color: #999;
          font-size: 14px;
          margin-left: 5px;
        }
      }
    }
  }
</style>

